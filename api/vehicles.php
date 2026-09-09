<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$currentUser = require_auth();

header('Content-Type: application/json');
// CORS is configured by config/auth.php
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function respond(array $data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data);
    exit;
}

function save_vehicle_image(string $data, string $prefix, string $id): ?string {
    if (!preg_match('/^data:image\/(jpeg|png|webp);base64,([A-Za-z0-9+\/=\r\n]+)$/', $data, $m)) throw new InvalidArgumentException('Invalid image format.');
    $decoded = base64_decode($m[2], true);
    if ($decoded === false || strlen($decoded) > 2 * 1024 * 1024) throw new InvalidArgumentException('Image must be under 2MB.');
    $info = @getimagesizefromstring($decoded);
    $mime = $info['mime'] ?? '';
    if (!$info || !in_array($mime, ['image/jpeg','image/png','image/webp'], true)) throw new InvalidArgumentException('Unsupported image type.');
    $ext = $mime === 'image/jpeg' ? 'jpg' : substr($mime, 6);
    $dir = __DIR__ . '/../uploads/vehicles/';
    if (!is_dir($dir) && !mkdir($dir, 0750, true)) throw new RuntimeException('Upload unavailable.');
    $name = $prefix . preg_replace('/[^a-zA-Z0-9_-]/', '', $id) . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    if (file_put_contents($dir . $name, $decoded, LOCK_EX) === false) throw new RuntimeException('Upload failed.');
    return 'uploads/vehicles/' . $name;
}

try {
    $pdo = Database::getInstance()->getPDO();
    $method = $_SERVER['REQUEST_METHOD'];

    // Ensure vehicle_photo_path column exists (safe migration)
    $pdo->exec("ALTER TABLE vehicles ADD COLUMN IF NOT EXISTS vehicle_photo_path VARCHAR(500) NULL");
    // Ensure in_out_status supports 'idle'
    $pdo->exec("ALTER TABLE vehicles MODIFY COLUMN in_out_status ENUM('idle','out') DEFAULT 'idle'");
    // Ensure driver status supports 'idle' and 'on_duty'
    $pdo->exec("ALTER TABLE drivers MODIFY COLUMN status ENUM('idle','on_duty','off','inactive') DEFAULT 'idle'");

    // GET /api/vehicles.php
    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM vehicles WHERE " . unit_sql() . " ORDER BY created_at DESC");
        respond(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    // POST /api/vehicles.php  — add new vehicle
    if ($method === 'POST') {
        if (!in_array($currentUser['role'], ['admin', 'officer'], true)) respond(['success' => false, 'message' => 'Forbidden.'], 403);
        $body = json_decode(file_get_contents('php://input'), true);

        $required = ['id', 'type', 'category', 'model', 'registration', 'year', 'fuel_type', 'fuel_capacity', 'status', 'unit'];
        foreach ($required as $field) {
            if (empty($body[$field])) {
                respond(['success' => false, 'message' => "Field '{$field}' is required."], 422);
            }
        }
        // Validate and store optional images
        $imagePath = !empty($body['image_base64']) ? save_vehicle_image($body['image_base64'], 'vehicle_', $body['id']) : null;
        $vehiclePhotoPath = !empty($body['vehicle_photo_base64']) ? save_vehicle_image($body['vehicle_photo_base64'], 'vehicle_photo_', $body['id']) : null;

        // Handle vehicle photo upload
        // DB-level duplicate checks with clear messages
        $dup = $pdo->prepare("SELECT id FROM vehicles WHERE (id = ? OR registration = ?) AND (" . unit_sql() . ") LIMIT 1");
        $dup->execute([$body['id'], $body['registration']]);
        $existing = $dup->fetch();
        if ($existing) {
            if ($existing['id'] === $body['id']) {
                respond(['success' => false, 'message' => 'Vehicle ID already exists. Please use a unique ID.'], 409);
            } else {
                respond(['success' => false, 'message' => 'Registration Number already exists. Please use a unique registration.'], 409);
            }
        }

        $stmt = $pdo->prepare("
            INSERT INTO vehicles
                (id, type, category, model, registration, year, fuel_type, fuel_capacity,
                 fuel_level, status, unit, assigned_driver, work_ticket, work_ticket_status,
                 in_out_status, latitude, longitude, location_accuracy, location_source,
                 location_timestamp, image_path, vehicle_photo_path, next_maintenance_date, current_odometer, unit_id)
            VALUES
                (:id, :type, :category, :model, :registration, :year, :fuel_type, :fuel_capacity,
                 :fuel_level, :status, :unit, :assigned_driver, :work_ticket, :work_ticket_status,
                 :in_out_status, :latitude, :longitude, :location_accuracy, :location_source,
                 :location_timestamp, :image_path, :vehicle_photo_path,  :next_maintenance_date, :current_odometer, :unit_id)
        ");

        $stmt->execute([
            ':id'                 => $body['id'],
            ':type'               => $body['type'],
            ':category'           => $body['category'],
            ':model'              => $body['model'],
            ':registration'       => $body['registration'],
            ':year'               => (int)$body['year'],
            ':fuel_type'          => $body['fuel_type'],
            ':fuel_capacity'      => (int)$body['fuel_capacity'],
            ':fuel_level'         => 100,
            ':status'             => $body['status'],
            ':unit'               => $body['unit'],
            ':assigned_driver'    => $body['assigned_driver'] ?? null,
            ':work_ticket'        => $body['work_ticket'] ?? null,
            ':work_ticket_status' => 'active',
            ':in_out_status'      => $body['in_out_status'] ?? 'idle',
            ':latitude'           => $body['latitude'] ?? null,
            ':longitude'          => $body['longitude'] ?? null,
            ':location_accuracy'  => $body['location_accuracy'] ?? null,
            ':location_source'    => $body['location_source'] ?? 'gps',
            ':location_timestamp' => $body['location_timestamp'] ?? date('Y-m-d H:i:s'),
            ':image_path'         => $imagePath,
            ':vehicle_photo_path' => $vehiclePhotoPath,
            ':next_maintenance_date' => $body['next_maintenance_date'] ?? null,
            ':current_odometer' => (int)($body['current_odometer'] ?? 0),
            ':unit_id'            => unit_value($body),
        ]);

        respond(['success' => true, 'message' => 'Vehicle saved successfully.', 'id' => $body['id']], 201);
    }

    // PUT /api/vehicles.php?id=X — update vehicle
    if ($method === 'PUT') {
        if (!in_array($currentUser['role'], ['admin', 'officer', 'mechanic', 'fuel_manager'], true)) respond(['success' => false, 'message' => 'Forbidden.'], 403);
        $id = $_GET['id'] ?? null;
        if (!$id) respond(['success' => false, 'message' => 'ID required.'], 422);
        $b = json_decode(file_get_contents('php://input'), true);
        if (!is_array($b)) respond(['success' => false, 'message' => 'Invalid request body.'], 400);

        $allowedFields = ['category', 'type', 'model', 'registration', 'year', 'fuel_type', 'fuel_capacity', 'fuel_level', 'status', 'unit', 'assigned_driver', 'work_ticket', 'work_ticket_status', 'in_out_status', 'current_odometer', 'next_maintenance_odometer', 'next_maintenance_date'];
        
        $setClauses = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $b)) {
                $setClauses[] = "`{$field}` = ?";
                $params[] = $b[$field];
            }
        }

        if (!empty($setClauses)) {
            $setClauses[] = "`updated_at` = NOW()";
            $sql = "UPDATE vehicles SET " . implode(', ', $setClauses) . " WHERE id=? AND (" . unit_sql() . ")";
            $params[] = $id;
            $pdo->prepare($sql)->execute($params);
        }

        respond(['success' => true, 'message' => 'Vehicle updated.']);
    }

    // DELETE /api/vehicles.php?id=X
    if ($method === 'DELETE') {
        if (!in_array($currentUser['role'], ['admin', 'officer'], true)) respond(['success' => false, 'message' => 'Forbidden.'], 403);
        $id = $_GET['id'] ?? null;
        if (!$id) respond(['success' => false, 'message' => 'ID required.'], 422);
        $pdo->prepare("DELETE FROM vehicles WHERE id=? AND (" . unit_sql() . ")")->execute([$id]);
        respond(['success' => true, 'message' => 'Vehicle deleted.']);
    }

    respond(['success' => false, 'message' => 'Method not allowed.'], 405);

} catch (PDOException $e) {
    $duplicate = $e->getCode() === '23000';
    respond([
        'success' => false,
        'message' => $duplicate ? 'Vehicle ID or Registration already exists.' : 'Database error: ' . 'Service temporarily unavailable.'
    ], $duplicate ? 409 : 500);
} catch (Exception $e) {
    respond(['success' => false, 'message' => 'Service temporarily unavailable.'], 500);
}