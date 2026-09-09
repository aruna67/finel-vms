<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$currentUser = require_auth(['admin', 'officer', 'driver']);

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
        exit;
    }

    $b = json_input();
    $driver_id = $currentUser['role'] === 'driver' ? (string)$currentUser['id'] : ($b['driver_id'] ?? null);
    $lat = $b['latitude'] ?? null;
    $lon = $b['longitude'] ?? null;
    $client_ts = $b['timestamp'] ?? null; // Optional timestamp from client for offline sync
    $speedKmh = isset($b['speed_kmh']) && is_numeric($b['speed_kmh']) ? (float)$b['speed_kmh'] : 0.0;

    if (!$driver_id || !is_numeric($lat) || !is_numeric($lon) ||
        (float)$lat < -90 || (float)$lat > 90 || (float)$lon < -180 || (float)$lon > 180) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Missing driver_id, latitude or longitude']);
        exit;
    }
    if ($speedKmh < 0 || $speedKmh > 300) {
        api_response(['success' => false, 'message' => 'Invalid speed value.'], 422);
    }

    $pdo = Database::getInstance()->getPDO();
    
    // Check for active vehicle
    $stmt = $pdo->prepare("SELECT id FROM vehicles WHERE assigned_driver=? AND (" . unit_sql() . ") AND status='active'");
    $stmt->execute([$driver_id]);
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($vehicles) === 0) {
        echo json_encode(['success' => false, 'message' => 'No active vehicle found for this driver']);
        exit;
    }

    $updatedVehicles = [];
    $db_now = date('Y-m-d H:i:s');
    $parsedTs = $client_ts ? strtotime((string)$client_ts) : false;
    $final_ts = $parsedTs ? date('Y-m-d H:i:s', $parsedTs) : $db_now;

    // --- GEOFENCE CHECK LOGIC ---
    function isPointInPolygon($lat, $lon, $polygon) {
        $vertices_count = count($polygon);
        $inside = false;
        for ($i = 0, $j = $vertices_count - 1; $i < $vertices_count; $j = $i++) {
            if (
                (($polygon[$i]['lat'] > $lat) != ($polygon[$j]['lat'] > $lat)) &&
                ($lon < ($polygon[$j]['lng'] - $polygon[$i]['lng']) * ($lat - $polygon[$i]['lat']) / ($polygon[$j]['lat'] - $polygon[$i]['lat']) + $polygon[$i]['lng'])
            ) {
                $inside = !$inside;
            }
        }
        return $inside;
    }

    // Fetch all forbidden geofences
    $gf_stmt = $pdo->query("SELECT id, coordinates FROM geofences WHERE (" . unit_sql() . ") AND type='forbidden'");
    $forbiddenZones = $gf_stmt->fetchAll(PDO::FETCH_ASSOC);
    $violation_zone_id = null;

    foreach ($forbiddenZones as $zone) {
        $coords = json_decode($zone['coordinates'], true);
        if (isPointInPolygon($lat, $lon, $coords)) {
            $violation_zone_id = $zone['id'];
            break;
        }
    }
    // --- END GEOFENCE CHECK ---

    foreach ($vehicles as $v) {
        $vid = $v['id'];
        
        // Update current location and violation status
        $violation_flag = $violation_zone_id ? 1 : 0;
        $updateStmt = $pdo->prepare("UPDATE vehicles SET latitude=?, longitude=?, location_timestamp=?, location_source='driver_gps', is_geofence_violation=?, last_geofence_id=? WHERE id=?");
        $updateStmt->execute([$lat, $lon, $final_ts, $violation_flag, $violation_zone_id, $vid]);
        
        // Add to tracking history
        $histStmt = $pdo->prepare("INSERT INTO gps_tracking (vehicle_id, latitude, longitude, accuracy, source, timestamp, speed_kmh) VALUES (?, ?, ?, 10, 'driver_gps', ?, ?)");
        $histStmt->execute([$vid, $lat, $lon, $final_ts, $speedKmh]);

        if ($speedKmh > 50) {
            $mStmt = $pdo->prepare("SELECT id, authorized_officer_phone FROM movements WHERE vehicle_id=? AND (" . unit_sql() . ") AND status='out' ORDER BY check_out DESC LIMIT 1");
            $mStmt->execute([$vid]);
            $movement = $mStmt->fetch(PDO::FETCH_ASSOC);
            $phone = preg_replace('/\D+/', '', (string)($movement['authorized_officer_phone'] ?? getenv('ALERT_OFFICER_PHONE')));
            $recentStmt = $pdo->prepare("SELECT id FROM speed_alerts WHERE vehicle_id=? AND notified_at >= DATE_SUB(NOW(), INTERVAL 10 MINUTE) LIMIT 1");
            $recentStmt->execute([$vid]);
            if ($phone !== '' && !$recentStmt->fetch()) {
                $pdo->prepare("INSERT INTO speed_alerts (vehicle_id,movement_id,speed_kmh,officer_phone) VALUES (?,?,?,?)")
                    ->execute([$vid, $movement['id'] ?? null, $speedKmh, $phone]);
                $sid = getenv('TWILIO_ACCOUNT_SID');
                $token = getenv('TWILIO_AUTH_TOKEN');
                $from = getenv('TWILIO_FROM_NUMBER');
                if ($sid && $token && $from) {
                    $ch = curl_init("https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json");
                    curl_setopt_array($ch, [
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => http_build_query([
                            'To' => '+' . $phone,
                            'From' => $from,
                            'Body' => "ARMY VMS ALERT: Vehicle {$vid} exceeded speed limit at " . round($speedKmh, 1) . " km/h. Location: {$lat}, {$lon}."
                        ]),
                        CURLOPT_USERPWD => "{$sid}:{$token}",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_TIMEOUT => 10
                    ]);
                    curl_exec($ch);
                    curl_close($ch);
                }
            }
        }
        
        $updatedVehicles[] = $vid;
    }

    echo json_encode([
        'success' => true, 
        'updated_vehicles' => $updatedVehicles,
        'geofence_violation' => ($violation_zone_id !== null),
        'zone_id' => $violation_zone_id
        ,'speed_kmh' => $speedKmh
        ,'speed_alert' => $speedKmh > 50
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'DB error: ' . 'Service temporarily unavailable.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Service temporarily unavailable.']);
}