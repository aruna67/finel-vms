<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
require_auth(['admin', 'officer', 'mechanic', 'fuel_manager']);

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

function respond(array $data, int $code = 200): void { http_response_code($code); echo json_encode($data); exit; }

try {
    $pdo = Database::getInstance()->getPDO();
    $pdo->exec("CREATE TABLE IF NOT EXISTS reports (
        id VARCHAR(20) PRIMARY KEY,
        type VARCHAR(30) NOT NULL,
        start_date DATE,
        end_date DATE,
        generated_by VARCHAR(100),
        content LONGTEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    if ($method === 'GET' && $id) {
        $stmt = $pdo->prepare("SELECT * FROM reports WHERE id=? AND (" . unit_sql() . ")");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        respond($row ? ['success' => true, 'data' => $row] : ['success' => false, 'message' => 'Not found'], $row ? 200 : 404);
    }

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT id, type, start_date, end_date, generated_by, created_at FROM reports WHERE " . unit_sql() . " ORDER BY created_at DESC LIMIT 50");
        respond(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'POST') {
        $b = json_decode(file_get_contents('php://input'), true);
        $pdo->prepare("INSERT INTO reports (id, type, start_date, end_date, generated_by, content, unit_id) VALUES (?,?,?,?,?,?,?)")
            ->execute([$b['id'], $b['type'], $b['start_date'], $b['end_date'], $b['generated_by'], $b['content'], unit_value($b)]);
        respond(['success' => true], 201);
    }

} catch (PDOException $e) {
    respond(['success' => false, 'message' => 'Service temporarily unavailable.'], 500);
}