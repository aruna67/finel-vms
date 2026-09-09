<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$user = require_auth(['admin']);
if ($_SERVER['REQUEST_METHOD'] !== 'GET') api_response(['success' => false, 'message' => 'Method not allowed.'], 405);
try {
    $pdo = Database::getInstance()->getPDO();
    $limit = min(500, max(1, (int)($_GET['limit'] ?? 100)));
    $stmt = $pdo->query("SELECT id,user_id,username,action,entity,entity_id,details,ip_address,user_agent,created_at FROM audit_logs WHERE " . unit_sql() . " ORDER BY created_at DESC LIMIT {$limit}");
    api_response(['success' => true, 'data' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    api_response(['success' => false, 'message' => 'Service temporarily unavailable.'], 500);
}
