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
    http_response_code($code); echo json_encode($data); exit;
}

try {
    $pdo = Database::getInstance()->getPDO();
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    // GET all users (no passwords)
    if ($method === 'GET' && !$id) {
        if (!in_array($currentUser['role'], ['admin', 'officer'], true)) {
            respond(['success' => false, 'message' => 'Forbidden.'], 403);
        }
        $rows = $pdo->query("SELECT id, username, full_name, rank, role, unit_id, created_at FROM users WHERE " . unit_sql() . " ORDER BY id")->fetchAll();
        respond(['success' => true, 'data' => $rows]);
    }

    // POST — create user (Admin only)
    if ($method === 'POST') {
        if ($currentUser['role'] !== 'admin') respond(['success' => false, 'message' => 'Forbidden.'], 403);
        $b = json_input();
        foreach (['username','password','full_name','rank','role'] as $f) {
            if (empty($b[$f])) respond(['success' => false, 'message' => "Field '{$f}' is required."], 422);
        }
        $b['role'] = normalize_role($b['role']);
        if (!in_array($b['role'], supported_roles(), true)) respond(['success' => false, 'message' => 'Invalid role.'], 422);
        $pdo->prepare("INSERT INTO users (username, password, full_name, rank, role, unit_id) VALUES (?, ?, ?, ?, ?, ?)")
            ->execute([$b['username'], password_hash($b['password'], PASSWORD_DEFAULT), $b['full_name'], $b['rank'], $b['role'], unit_value($b)]);
        respond(['success' => true, 'message' => 'User created.'], 201);
    }

    // PUT — update profile or password (Own user or Admin)
    if ($method === 'PUT' && $id) {
        if ($currentUser['role'] !== 'admin' && (string)$currentUser['id'] !== (string)$id) {
            respond(['success' => false, 'message' => 'Forbidden.'], 403);
        }
        $b = json_input();

        if (!empty($b['password'])) {
            // Password change — require current_password verification
            $row = $pdo->prepare("SELECT password FROM users WHERE id=? AND (" . unit_sql() . ")");
            $row->execute([$id]);
            $old = $row->fetchColumn();
            $ok = $old && password_verify((string)($b['current_password'] ?? ''), $old);
            if (!$ok && $old && preg_match('/^[a-f0-9]{64}$/i', $old)) $ok = hash_equals(strtolower($old), hash('sha256', (string)($b['current_password'] ?? '')));
            if (!$ok) respond(['success' => false, 'message' => 'Current password is incorrect.'], 403);
            $pdo->prepare("UPDATE users SET password=? WHERE id=?")->execute([password_hash($b['password'], PASSWORD_DEFAULT), $id]);
        }

        if (!empty($b['full_name'])) {
            $pdo->prepare("UPDATE users SET full_name=?, rank=? WHERE id=? AND (" . unit_sql() . ")")->execute([$b['full_name'], $b['rank'] ?? '', $id]);
        }

        if (!empty($b['role']) && $currentUser['role'] === 'admin') {
            $newRole = normalize_role($b['role']);
            if (!in_array($newRole, supported_roles(), true)) {
                respond(['success' => false, 'message' => 'Invalid role.'], 422);
            }
            $pdo->prepare("UPDATE users SET role=?, unit_id=? WHERE id=? AND (" . unit_sql() . ")")->execute([$newRole, unit_value($b), $id]);
        }

        if (!empty($b['username']) && $currentUser['role'] === 'admin') {
            $pdo->prepare("UPDATE users SET username=? WHERE id=? AND (" . unit_sql() . ")")->execute([$b['username'], $id]);
        }

        respond(['success' => true, 'message' => 'User updated.']);
    }

    // DELETE (Admin only)
    if ($method === 'DELETE' && $id) {
        if ($currentUser['role'] !== 'admin') respond(['success' => false, 'message' => 'Forbidden.'], 403);
        // Prevent deleting last admin
        $adminCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
        $userRole = $pdo->prepare("SELECT role FROM users WHERE id=? AND (" . unit_sql() . ")");
        $userRole->execute([$id]);
        $role = $userRole->fetchColumn();
        if ($role === 'admin' && $adminCount <= 1)
            respond(['success' => false, 'message' => 'Cannot delete the last admin user.'], 403);

        $pdo->prepare("DELETE FROM users WHERE id=? AND (" . unit_sql() . ")")->execute([$id]);
        respond(['success' => true, 'message' => 'User deleted.']);
    }

    respond(['success' => false, 'message' => 'Method not allowed.'], 405);

} catch (PDOException $e) {
    $dup = $e->getCode() === '23000';
    respond(['success' => false, 'message' => $dup ? 'Username already exists.' : 'DB error: '.'Service temporarily unavailable.'], $dup ? 409 : 500);
}