<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$currentUser = require_auth();

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

function respond(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

try {
    $pdo = Database::getInstance()->getPDO();
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    // GET all drivers
    if ($method === 'GET' && !$id) {
        $stmt = $pdo->query("SELECT d.*, GROUP_CONCAT(dv.vehicle_id) as assigned_vehicles FROM drivers d LEFT JOIN driver_vehicles dv ON d.id = dv.driver_id WHERE " . unit_sql("d") . " GROUP BY d.id ORDER BY d.created_at DESC");
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $r['assigned_vehicles'] = $r['assigned_vehicles'] ? json_encode(explode(',', $r['assigned_vehicles'])) : '[]';
        }
        respond(['success' => true, 'data' => $rows]);
    }

    // Write operations require admin or officer
    if (!in_array($currentUser['role'], ['admin', 'officer'], true)) {
        respond(['success' => false, 'message' => 'Forbidden.'], 403);
    }

    // POST — add driver
    if ($method === 'POST') {
        $b = json_decode(file_get_contents('php://input'), true);
        $required = ['id', 'first_name', 'last_name', 'rank', 'license_class', 'license_expiry', 'phone', 'whatsapp', 'status'];
        foreach ($required as $f) {
            if (empty($b[$f])) respond(['success' => false, 'message' => "Field '{$f}' is required."], 422);
        }

        $pdo->prepare("INSERT INTO drivers (id,first_name,last_name,rank,license_class,license_expiry,phone,whatsapp,email,status,notes,unit_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)")
            ->execute([$b['id'],$b['first_name'],$b['last_name'],$b['rank'],$b['license_class'],$b['license_expiry'],$b['phone'],$b['whatsapp'],$b['email']??null,$b['status'],$b['notes']??null,unit_value($b)]);

        // Assign vehicles
        $vehicles = json_decode($b['assigned_vehicles'] ?? '[]', true) ?: [];
        if ($vehicles) {
            $ins = $pdo->prepare("INSERT IGNORE INTO driver_vehicles (driver_id, vehicle_id, unit_id) VALUES (?,?,?)");
            $upd = $pdo->prepare("UPDATE vehicles SET assigned_driver=? WHERE id=? AND (" . unit_sql() . ")");
            foreach ($vehicles as $vid) {
                $ins->execute([$b['id'], $vid, unit_value($b)]);
                $upd->execute([$b['id'], $vid]);
            }
        }

        respond(['success' => true, 'message' => 'Driver saved.'], 201);
    }

    // PUT — update driver
    if ($method === 'PUT' && $id) {
        $b = json_decode(file_get_contents('php://input'), true);
        $pdo->prepare("UPDATE drivers SET first_name=?,last_name=?,rank=?,license_expiry=?,phone=?,whatsapp=?,status=? WHERE id=? AND (" . unit_sql() . ")")
            ->execute([$b['first_name'],$b['last_name'],$b['rank'],$b['license_expiry'],$b['phone'],$b['whatsapp'],$b['status'],$id]);
        respond(['success' => true, 'message' => 'Driver updated.']);
    }

    // DELETE
    if ($method === 'DELETE' && $id) {
        $pdo->prepare("DELETE FROM drivers WHERE id=? AND (" . unit_sql() . ")")->execute([$id]);
        respond(['success' => true, 'message' => 'Driver deleted.']);
    }

    respond(['success' => false, 'message' => 'Method not allowed.'], 405);

} catch (PDOException $e) {
    $dup = $e->getCode() === '23000';
    respond(['success' => false, 'message' => $dup ? 'Driver ID already exists.' : 'DB error: '.'Service temporarily unavailable.'], $dup ? 409 : 500);
}