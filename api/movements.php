<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$currentUser = require_auth();

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

function respond(array $data, int $code = 200): void {
    http_response_code($code); echo json_encode($data); exit;
}

try {
    $pdo = Database::getInstance()->getPDO();
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT m.*, v.model as vehicle_model, CONCAT(d.first_name,' ',d.last_name) as driver_name FROM movements m LEFT JOIN vehicles v ON m.vehicle_id=v.id LEFT JOIN drivers d ON m.driver_id=d.id WHERE " . unit_sql("m") . " ORDER BY m.created_at DESC");
        respond(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if (!in_array($currentUser['role'], ['admin', 'officer', 'driver'], true)) {
        respond(['success' => false, 'message' => 'Forbidden.'], 403);
    }

    // POST — check out
    if ($method === 'POST') {
        $b = json_decode(file_get_contents('php://input'), true);

        foreach (['vehicle_id','driver_id','destination','purpose','authorized_by'] as $f) {
            if (empty($b[$f])) respond(['success' => false, 'message' => "Field '{$f}' required."], 422);
        }

        $pdo->beginTransaction();
        // Check vehicle not already out
        $v = $pdo->prepare("SELECT in_out_status FROM vehicles WHERE id=? AND (" . unit_sql() . ")");
        $v->execute([$b['vehicle_id']]);
        $veh = $v->fetch();
        if ($veh && $veh['in_out_status'] === 'out')
            respond(['success' => false, 'message' => 'Vehicle is already checked out.'], 409);

        $mid = uniqid();
        $pdo->prepare("INSERT INTO movements (id,vehicle_id,driver_id,check_out,expected_return,destination,purpose,authorized_by,authorized_officer_phone,status,notes,checkout_odometer,checkout_fuel_level,unit_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)")
            ->execute([$mid,$b['vehicle_id'],$b['driver_id'],date('Y-m-d H:i:s'),$b['expected_return']??null,$b['destination'],$b['purpose'],$b['authorized_by'],$b['authorized_officer_phone']??null,'out',$b['notes']??null,(int)($b['odometer']??0),(int)($b['checkout_fuel_level']??0),unit_value($b)]);

        // Update vehicle odometer and status
        $pdo->prepare("UPDATE vehicles SET in_out_status='out', current_odometer=? WHERE id=? AND (" . unit_sql() . ")")->execute([(int)($b['odometer']??0), $b['vehicle_id']]);
        $pdo->prepare("UPDATE drivers SET status='on_duty' WHERE id=? AND (" . unit_sql() . ")")->execute([$b['driver_id']]);

        $pdo->commit();
        respond(['success' => true, 'message' => 'Vehicle checked out.', 'id' => $mid], 201);
    }

    // PUT — check in
    if ($method === 'PUT' && $id) {
        $b = json_decode(file_get_contents('php://input'), true);

        // Simple status-only update (authorize movement)
        if (isset($b['status']) && count($b) === 1) {
            $pdo->prepare("UPDATE movements SET status=? WHERE id=? AND (" . unit_sql() . ")")->execute([$b['status'], $id]);
            respond(['success' => true]);
        }

        $pdo->beginTransaction();
        $pdo->prepare("UPDATE movements SET check_in=?,status='completed',return_notes=?,checked_in_by=?,fuel_level=?,condition_status=?,checkin_odometer=? WHERE id=? AND (" . unit_sql() . ")")
            ->execute([date('Y-m-d H:i:s'), $b['return_notes']??null, $b['checked_in_by']??null, (int)($b['fuel_level']??0), $b['condition']??'good', (int)($b['odometer']??0), $id]);

        // Get vehicle_id and driver_id from movement
        $m = $pdo->prepare("SELECT vehicle_id, driver_id FROM movements WHERE id=? AND (" . unit_sql() . ")");
        $m->execute([$id]);
        $mov = $m->fetch();

        if ($mov) {
            // Vehicle → idle, update fuel level and current_odometer
            $pdo->prepare("UPDATE vehicles SET in_out_status='idle', fuel_level=?, current_odometer=? WHERE id=? AND (" . unit_sql() . ")")
                ->execute([(int)($b['fuel_level']??0), (int)($b['odometer']??0), $mov['vehicle_id']]);

            // Driver → idle
            if ($mov['driver_id']) {
                $pdo->prepare("UPDATE drivers SET status='idle' WHERE id=? AND (" . unit_sql() . ")")->execute([$mov['driver_id']]);
            }
        }

        $pdo->commit();
        respond(['success' => true, 'message' => 'Vehicle checked in.']);
    }

    respond(['success' => false, 'message' => 'Method not allowed.'], 405);

} catch (PDOException $e) {
    respond(['success' => false, 'message' => 'DB error: '.'Service temporarily unavailable.'], 500);
}