<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
require_auth(['admin', 'officer']);

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

try {
    $pdo = Database::getInstance()->getPDO();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        // List all geofences
        $stmt = $pdo->query("SELECT * FROM geofences WHERE " . unit_sql() . " ORDER BY created_at DESC");
        $geofences = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Decode coordinates JSON for each geofence
        foreach ($geofences as &$gf) {
            $gf['coordinates'] = json_decode($gf['coordinates'], true);
        }
        
        echo json_encode(['success' => true, 'geofences' => $geofences]);
    } 
    elseif ($method === 'POST') {
        // Create new geofence
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'] ?? 'Unnamed Zone';
        $type = $data['type'] ?? 'forbidden';
        $coords = $data['coordinates'] ?? null;
        
        if (!$coords || !is_array($coords)) {
            throw new Exception("Invalid coordinates provided");
        }
        
        $stmt = $pdo->prepare("INSERT INTO geofences (name, type, coordinates, unit_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $type, json_encode($coords), unit_value($data)]);
        
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    }
    elseif ($method === 'DELETE') {
        // Remove geofence
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception("ID required for deletion");
        
        $stmt = $pdo->prepare("DELETE FROM geofences WHERE id = ? AND (" . unit_sql() . ")");
        $stmt->execute([$id]);
        
        echo json_encode(['success' => true]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Service temporarily unavailable.']);
}