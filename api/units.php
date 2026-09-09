<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$currentUser = require_auth();
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET','POST'], true)) api_response(['success'=>false,'message'=>'Method not allowed.'],405);
try {
    $pdo = Database::getInstance()->getPDO();
    $pdo->exec("CREATE TABLE IF NOT EXISTS units (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL UNIQUE,code VARCHAR(30) NULL UNIQUE,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
    if ($_SERVER['REQUEST_METHOD'] === 'GET') api_response(['success'=>true,'data'=>$pdo->query('SELECT id,name,code,created_at FROM units ORDER BY name')->fetchAll()]);
    if ($currentUser['role'] !== 'admin') api_response(['success'=>false,'message'=>'Forbidden.'],403);
    $b = json_input(); $name = trim((string)($b['name'] ?? ''));
    if ($name === '') api_response(['success'=>false,'message'=>'Unit name is required.'],422);
    $stmt = $pdo->prepare('INSERT INTO units (name,code) VALUES (?,?)');
    $stmt->execute([$name, trim((string)($b['code'] ?? '')) ?: null]);
    api_response(['success'=>true,'id'=>$pdo->lastInsertId()],201);
} catch (PDOException $e) {
    api_response(['success'=>false,'message'=>$e->getCode()==='23000'?'Unit already exists.':'Service temporarily unavailable.'],500);
}
