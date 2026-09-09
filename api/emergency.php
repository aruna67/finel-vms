<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }
$currentUser = require_auth();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $currentUser['role'] !== 'driver') api_response(['success'=>false,'message'=>'Only drivers may create alerts.'],403);
if (in_array($_SERVER['REQUEST_METHOD'], ['GET','PUT'], true) && !in_array($currentUser['role'], ['admin','officer'], true)) api_response(['success'=>false,'message'=>'Insufficient permissions.'],403);
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET','POST','PUT'], true)) api_response(['success'=>false,'message'=>'Method not allowed.'],405);
try {
 $db=Database::getInstance()->getPDO(); $method=$_SERVER['REQUEST_METHOD'];
 if($method==='POST') { $d=json_input(); if(!isset($d['latitude'],$d['longitude'])||!is_numeric($d['latitude'])||!is_numeric($d['longitude'])||(float)$d['latitude']<-90||(float)$d['latitude']>90||(float)$d['longitude']<-180||(float)$d['longitude']>180) api_response(['success'=>false,'message'=>'Valid location is required.'],422); $driverId=(string)$currentUser['id']; $q=$db->prepare('SELECT id FROM vehicles WHERE assigned_driver=? AND ('.unit_sql().') LIMIT 1');$q->execute([$driverId]);$v=$q->fetch();$q=$db->prepare('INSERT INTO emergency_alerts (driver_id,vehicle_id,latitude,longitude,alert_type,message,unit_id) VALUES (?,?,?,?,?,?,?)');$q->execute([$driverId,$v['id']??null,$d['latitude'],$d['longitude'],$d['type']??'other',$d['message']??'SOS triggered',unit_value($d)]);api_response(['success'=>true,'message'=>'Emergency alert sent.'],201); }
 if($method==='GET') {$q=$db->query("SELECT * FROM emergency_alerts WHERE (" . unit_sql() . ") AND status IN ('open','acknowledged') ORDER BY created_at DESC");api_response(['success'=>true,'alerts'=>$q->fetchAll()]);}
 $d=json_input(); if(empty($d['id'])) api_response(['success'=>false,'message'=>'ID required.'],422);$status=$d['status']??'resolved';if(!in_array($status,['open','acknowledged','resolved'],true)) api_response(['success'=>false,'message'=>'Invalid status.'],422);$q=$db->prepare('UPDATE emergency_alerts SET status=? WHERE id=? AND ('.unit_sql().')');$q->execute([$status,$d['id']]);api_response(['success'=>true,'message'=>'Alert updated.']);
} catch(Throwable $e) { api_response(['success'=>false,'message'=>'Service temporarily unavailable.'],500); }