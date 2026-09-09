<?php
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') api_response(['success' => false, 'message' => 'Method not allowed.'], 405);
require_auth();
audit_log('LOGOUT', 'user', (string)($_SESSION['vms_user']['id'] ?? ''));
$_SESSION = [];
session_destroy();
api_response(['success' => true]);
