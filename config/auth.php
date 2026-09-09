<?php
// Shared API security bootstrap. Set APP_ORIGIN in production to the exact UI origin.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'httponly' => true,
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'samesite' => 'Lax'
    ]);
    session_start();
}

/**
 * Unit isolation helpers.  Administrators are HQ users and may see all units;
 * officers and drivers are restricted to the unit stored in their session.
 */
function current_user(): array { return $_SESSION['vms_user'] ?? []; }
function normalize_role($role): string { return strtolower(trim((string)$role)); }
function supported_roles(): array { return ['admin', 'officer', 'driver', 'mechanic', 'fuel_manager']; }
function current_unit_id(): ?int {
    $id = current_user()['unit_id'] ?? null;
    return ($id === null || $id === '' || (int)$id === 0) ? null : (int)$id;
}
function is_hq_user(?array $user = null): bool {
    $user = $user ?: current_user();
    return (($user['role'] ?? '') === 'admin') || empty($user['unit_id']);
}
function unit_sql(string $alias = ''): string {
    if (is_hq_user()) return '1=1';
    $prefix = $alias !== '' ? rtrim($alias, '.') . '.' : '';
    return $prefix . 'unit_id=' . (int)current_unit_id();
}
function unit_value(?array $body = null): ?int {
    return is_hq_user() && isset($body['unit_id']) && (int)$body['unit_id'] > 0
        ? (int)$body['unit_id'] : current_unit_id();
}

$allowedOrigin = getenv('APP_ORIGIN') ?: '';
if ($allowedOrigin !== '' && isset($_SERVER['HTTP_ORIGIN']) && hash_equals($allowedOrigin, $_SERVER['HTTP_ORIGIN'])) {
    header('Access-Control-Allow-Origin: ' . $allowedOrigin);
    header('Access-Control-Allow-Credentials: true');
}
header('Vary: Origin');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

function api_response(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function require_auth(array $roles = []): array {
    $user = $_SESSION['vms_user'] ?? null;
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (!$user && preg_match('/^Bearer\s+(.+)$/i', $auth, $m)) {
        $token = $m[1];
        if (session_id() !== $token) {
            session_write_close();
            session_id($token);
            session_start();
        }
        $user = $_SESSION['vms_user'] ?? null;
    }
    if (!$user) api_response(['success' => false, 'message' => 'Authentication required.'], 401);
    $user['role'] = normalize_role($user['role'] ?? '');
    $_SESSION['vms_user']['role'] = $user['role'];
    $allowedRoles = array_map('normalize_role', $roles);
    if ($roles && !in_array($user['role'], $allowedRoles, true)) {
        audit_log('ACCESS_DENIED', 'api', basename($_SERVER['SCRIPT_NAME'] ?? 'unknown'), ['method' => $_SERVER['REQUEST_METHOD'] ?? '']);
        api_response(['success' => false, 'message' => 'Insufficient permissions.'], 403);
    }
    audit_log('API_ACCESS', 'api', basename($_SERVER['SCRIPT_NAME'] ?? 'unknown'), ['method' => $_SERVER['REQUEST_METHOD'] ?? '']);
    return $user;
}

function json_input(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (!is_array($data)) api_response(['success' => false, 'message' => 'Invalid JSON request.'], 400);
    return $data;
}

function audit_log(string $action, ?string $entity = null, ?string $entityId = null, array $details = []): void {
    try {
        $pdo = Database::getInstance()->getPDO();
        $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
            id BIGINT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NULL,
            username VARCHAR(100) NULL,
            action VARCHAR(80) NOT NULL,
            entity VARCHAR(80) NULL,
            entity_id VARCHAR(80) NULL,
            details JSON NULL,
            ip_address VARCHAR(45) NULL,
            user_agent VARCHAR(255) NULL,
            unit_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_audit_created (created_at),
            INDEX idx_audit_user (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        $user = $_SESSION['vms_user'] ?? [];
        $stmt = $pdo->prepare('INSERT INTO audit_logs (user_id,username,action,entity,entity_id,details,ip_address,user_agent,unit_id) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            $user['id'] ?? null, $user['username'] ?? null, $action, $entity, $entityId,
            $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
            $_SERVER['REMOTE_ADDR'] ?? null, substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255), current_unit_id()
        ]);
    } catch (Throwable $e) {
        error_log('Audit logging failed: ' . $e->getMessage());
    }
}
