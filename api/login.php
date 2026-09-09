<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') api_response(['success' => false, 'message' => 'Method not allowed.'], 405);

try {
    $b = json_input();
    $username = trim((string)($b['username'] ?? ''));
    $password = (string)($b['password'] ?? '');
    if ($username === '' || $password === '') api_response(['success' => false, 'message' => 'Username and password required.'], 422);
    $pdo = Database::getInstance()->getPDO();
    $stmt = $pdo->prepare('SELECT id, username, password, full_name, rank, role, unit_id FROM users WHERE username=? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    $valid = $user && password_verify($password, $user['password']);
    if (!$valid && $user && preg_match('/^[a-f0-9]{64}$/i', $user['password'])) {
        $valid = hash_equals(strtolower($user['password']), hash('sha256', $password));
        if ($valid) $pdo->prepare('UPDATE users SET password=? WHERE id=?')->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
    }
    if (!$valid) {
        audit_log('LOGIN_FAILED', 'user', $username);
        api_response(['success' => false, 'message' => 'Invalid credentials.'], 401);
    }
    $user['role'] = normalize_role($user['role'] ?? '');
    unset($user['password']);
    session_regenerate_id(true);
    $_SESSION['vms_user'] = $user;
    audit_log('LOGIN_SUCCESS', 'user', (string)$user['id']);
    $user['token'] = session_id();
    api_response(['success' => true, 'user' => $user]);
} catch (Throwable $e) {
    api_response(['success' => false, 'message' => 'Service temporarily unavailable.'], 500);
}
