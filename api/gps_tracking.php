<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$user = require_auth(['admin', 'officer', 'driver']);
try {
    $pdo = Database::getInstance()->getPDO();
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') api_response(['success' => false, 'message' => 'Method not allowed.'], 405);
    $vehicle = trim((string)($_GET['vehicle_id'] ?? ''));
    $limit = min(500, max(1, (int)($_GET['limit'] ?? 100)));
    if ($vehicle === '') api_response(['success' => false, 'message' => 'vehicle_id is required.'], 422);
    $stmt = $pdo->prepare("SELECT vehicle_id, latitude, longitude, accuracy, source, timestamp FROM gps_tracking WHERE vehicle_id=? AND (" . unit_sql() . ") ORDER BY timestamp DESC LIMIT ".$limit);
    $stmt->execute([$vehicle]);
    api_response(['success' => true, 'data' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    api_response(['success' => false, 'message' => 'Service temporarily unavailable.'], 500);
}
