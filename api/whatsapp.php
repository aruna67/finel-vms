<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
require_auth(['admin', 'officer']);

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

function respond(array $data, int $code = 200): void { http_response_code($code); echo json_encode($data); exit; }

try {
    $pdo = Database::getInstance()->getPDO();
    $pdo->exec("CREATE TABLE IF NOT EXISTS whatsapp_messages (
        id VARCHAR(20) PRIMARY KEY,
        unit_id INT NULL,
        type VARCHAR(30),
        message TEXT,
        recipient VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM whatsapp_messages WHERE " . unit_sql() . " ORDER BY created_at DESC LIMIT 20");
        respond(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'POST') {
        $b = json_decode(file_get_contents('php://input'), true);
        $pdo->prepare("INSERT INTO whatsapp_messages (id, type, message, recipient, unit_id) VALUES (?,?,?,?,?)")
            ->execute([$b['id'], $b['type'], $b['message'], $b['recipient'], unit_value($b)]);
        respond(['success' => true], 201);
    }

} catch (PDOException $e) {
    respond(['success' => false, 'message' => 'Service temporarily unavailable.'], 500);
}