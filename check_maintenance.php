<?php
/**
 * Vehicle Maintenance Alert Script
 * Checks for vehicles due for service today and sends an email alert.
 * Use as a CRON job or periodic background task.
 */
require_once __DIR__ . '/config/database.php';

try {
    $pdo = Database::getInstance()->getPDO();
    $today = date('Y-m-d');

    // 1. Get vehicles with maintenance due today
    $stmt = $pdo->prepare("SELECT id, model, registration FROM vehicles WHERE next_maintenance_date = ?");
    $stmt->execute([$today]);
    $vehicles = $stmt->fetchAll();

    if (count($vehicles) > 0) {
        $admin_email = "arunasubash522@gmail.com"; 
        $subject = "Vehicle Maintenance Alert - " . date('l, F jS, Y');
        
        $message = "ARMY VMS - MAINTENANCE ALERT\n";
        $message .= "=============================================\n";
        $message .= "The following vehicles are scheduled for maintenance today ($today):\n\n";

        foreach ($vehicles as $vehicle) {
            $message .= "• Registration: " . $vehicle['registration'] . " | Model: " . $vehicle['model'] . " | ID: " . $vehicle['id'] . "\n";
        }

        $message .= "\n=============================================\n";
        $message .= "Please log into the system to update service records.\n";
        
        $headers = "From: system@yourdomain.com\r\n"; 
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send Email
        if (mail($admin_email, $subject, $message, $headers)) {
            echo "Maintenance alert successfully sent to arunasubash522@gmail.com";
        } else {
            echo "Error: Email delivery failed.";
        }
    } else {
        echo "No vehicles scheduled for maintenance today ($today).";
    }

} catch (Exception $e) {
    die("Error in maintenance check: " . $e->getMessage());
}
?>