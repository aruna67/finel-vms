<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
require_auth(['admin', 'officer', 'mechanic']);

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
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
        $stmt = $pdo->query("SELECT s.*, v.model as vehicle_model FROM service_records s LEFT JOIN vehicles v ON s.vehicle_id = v.id WHERE " . unit_sql("s") . " ORDER BY s.date DESC");
        respond(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'POST') {
        $b = json_decode(file_get_contents('php://input'), true);
        foreach (['vehicle_id','date','type','description','cost','technician'] as $f) {
            if (empty($b[$f])) respond(['success' => false, 'message' => "Field '{$f}' is required."], 422);
        }
        $sid = 'S' . strtoupper(substr(uniqid(), -6));
        $pdo->prepare("INSERT INTO service_records (id,vehicle_id,date,type,description,cost,technician,unit_id) VALUES (?,?,?,?,?,?,?,?)")
            ->execute([$sid, $b['vehicle_id'], $b['date'], $b['type'], $b['description'], (float)$b['cost'], $b['technician'], unit_value($b)]);
        respond(['success' => true, 'message' => 'Service record saved.', 'id' => $sid], 201);
    }

    if ($method === 'DELETE' && $id) {
        $pdo->prepare("DELETE FROM service_records WHERE id=? AND (" . unit_sql() . ")")->execute([$id]);
        respond(['success' => true, 'message' => 'Deleted.']);
    }

    respond(['success' => false, 'message' => 'Method not allowed.'], 405);

} catch (PDOException $e) {
    respond(['success' => false, 'message' => 'DB error: '.'Service temporarily unavailable.'], 500);
}