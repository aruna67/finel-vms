<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$currentUser = require_auth(['admin', 'officer', 'fuel_manager']);

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
    $action = $_GET['action'] ?? 'transactions';
    $id = $_GET['id'] ?? null;

    // GET stock or transactions
    if ($method === 'GET') {
        if ($action === 'stock') {
            $row = $pdo->query("SELECT * FROM fuel_stock WHERE id=1 AND (" . unit_sql() . ")")->fetch();
            respond(['success' => true, 'data' => $row]);
        }
        $stmt = $pdo->query("SELECT ft.*, v.model as vehicle_model FROM fuel_transactions ft LEFT JOIN vehicles v ON ft.vehicle_id = v.id WHERE " . unit_sql("ft") . " ORDER BY ft.date DESC, ft.created_at DESC LIMIT 50");
        respond(['success' => true, 'data' => $stmt->fetchAll()]);
    }

    if ($method === 'POST') {
        $b = json_input();

        $pdo->beginTransaction();
        // Update stock settings (from manage stock modal)
        if ($action === 'stock') {
            $pdo->prepare("UPDATE fuel_stock SET diesel=?,petrol=?,diesel_threshold=?,petrol_threshold=? WHERE id=1")
                ->execute([(int)$b['diesel'], (int)$b['petrol'], (int)$b['diesel_threshold'], (int)$b['petrol_threshold']]);
            $pdo->commit();
            respond(['success' => true, 'message' => 'Stock settings saved.']);
        }

        // Update stock (add/remove/update)
        if ($action === 'update_stock') {
            $fuelType = $b['fuel_type'];
            $qty = (int)$b['quantity'];
            $act = $b['action']; // add|remove|update
            $col = $fuelType === 'diesel' ? 'diesel' : 'petrol';

            if ($act === 'add')         $pdo->prepare("UPDATE fuel_stock SET {$col}={$col}+? WHERE id=1")->execute([$qty]);
            elseif ($act === 'remove')  $pdo->prepare("UPDATE fuel_stock SET {$col}=GREATEST(0,{$col}-?) WHERE id=1")->execute([$qty]);
            else                        $pdo->prepare("UPDATE fuel_stock SET {$col}=? WHERE id=1")->execute([$qty]);

            $stock = $pdo->query("SELECT * FROM fuel_stock WHERE id=1 AND (" . unit_sql() . ")")->fetch();
            $tid = 'FT' . strtoupper(substr(uniqid(), -6));
            $pdo->prepare("INSERT INTO fuel_transactions (id,type,date,fuel_type,amount,purpose,authorized_by,stock_balance_diesel,stock_balance_petrol,unit_id) VALUES (?,?,?,?,?,?,?,?,?,?)")
                ->execute([$tid, "stock_{$act}", date('Y-m-d'), $fuelType, $qty, $b['notes']??'Stock update', $b['authorized_by']??'System', $stock['diesel'], $stock['petrol'], unit_value($b)]);

            $pdo->commit();
             respond(['success' => true, 'message' => 'Stock updated.', 'stock' => $stock]);
        }

        // Allocate fuel to vehicle
        if ($action === 'allocate') {
            foreach (['vehicle_id','amount','fuel_type','date'] as $f) {
                if (empty($b[$f])) respond(['success' => false, 'message' => "Field '{$f}' required."], 422);
            }
            $col = $b['fuel_type'] === 'diesel' ? 'diesel' : 'petrol';
            $stock = $pdo->query("SELECT * FROM fuel_stock WHERE id=1 AND (" . unit_sql() . ")")->fetch();

            if ((int)$b['amount'] > (int)$stock[$col])
                respond(['success' => false, 'message' => "Insufficient {$b['fuel_type']}. Available: {$stock[$col]}L"], 422);

            $pdo->prepare("UPDATE fuel_stock SET {$col}={$col}-? WHERE id=1")->execute([(int)$b['amount']]);

            // Update vehicle fuel level
            $v = $pdo->prepare("SELECT fuel_capacity, fuel_level FROM vehicles WHERE id=? AND (" . unit_sql() . ")");
            $v->execute([$b['vehicle_id']]);
            $veh = $v->fetch();
            if ($veh) {
                $cap = $veh['fuel_capacity'] ?: 80;
                $current = ($veh['fuel_level'] / 100) * $cap;
                $newLevel = min($current + (int)$b['amount'], $cap);
                $pdo->prepare("UPDATE vehicles SET fuel_level=? WHERE id=?")->execute([round(($newLevel/$cap)*100), $b['vehicle_id']]);
            }

            $stock = $pdo->query("SELECT * FROM fuel_stock WHERE id=1 AND (" . unit_sql() . ")")->fetch();
            $tid = 'FT' . strtoupper(substr(uniqid(), -6));
            $pdo->prepare("INSERT INTO fuel_transactions (id,type,date,vehicle_id,fuel_type,amount,purpose,authorized_by,stock_balance_diesel,stock_balance_petrol,unit_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)")
                ->execute([$tid,'allocation',$b['date'],$b['vehicle_id'],$b['fuel_type'],(int)$b['amount'],$b['purpose']??'Routine',$b['authorized_by']??'System',$stock['diesel'],$stock['petrol'],unit_value($b)]);

            $pdo->commit();
             respond(['success' => true, 'message' => 'Fuel allocated.', 'stock' => $stock], 201);
        }
    }

    if ($method === 'DELETE' && $id) {
        $pdo->prepare("DELETE FROM fuel_transactions WHERE id=? AND (" . unit_sql() . ")")->execute([$id]);
        respond(['success' => true, 'message' => 'Deleted.']);
    }

    respond(['success' => false, 'message' => 'Method not allowed.'], 405);

} catch (PDOException $e) {
    respond(['success' => false, 'message' => 'DB error: '.'Service temporarily unavailable.'], 500);
}