-- Multi-unit data isolation (run once on an existing installation).
CREATE TABLE IF NOT EXISTS units (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    code VARCHAR(30) NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT IGNORE INTO units (id,name,code) VALUES (1,'Default Unit','DEFAULT');

ALTER TABLE users ADD COLUMN IF NOT EXISTS unit_id INT NULL;
UPDATE users SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE users ADD INDEX IF NOT EXISTS idx_users_unit (unit_id);

-- Add unit_id to every tenant-owned table and assign legacy rows to unit 1.
ALTER TABLE vehicles ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_vehicles_unit (unit_id);
UPDATE vehicles SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE drivers ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_drivers_unit (unit_id);
UPDATE drivers SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE geofences ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_geofences_unit (unit_id);
UPDATE geofences SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE emergency_alerts ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_emergency_unit (unit_id);
UPDATE emergency_alerts SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE driver_vehicles ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_driver_vehicles_unit (unit_id);
UPDATE driver_vehicles SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE fuel_stock ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_fuel_stock_unit (unit_id);
UPDATE fuel_stock SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE fuel_transactions ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_fuel_transactions_unit (unit_id);
UPDATE fuel_transactions SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE service_records ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_service_unit (unit_id);
UPDATE service_records SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE movements ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_movements_unit (unit_id);
UPDATE movements SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE gps_devices ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_gps_devices_unit (unit_id);
UPDATE gps_devices SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE gps_tracking ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_gps_tracking_unit (unit_id);
UPDATE gps_tracking SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE speed_alerts ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_speed_alerts_unit (unit_id);
UPDATE speed_alerts SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE reports ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_reports_unit (unit_id);
UPDATE reports SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE whatsapp_messages ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_whatsapp_unit (unit_id);
UPDATE whatsapp_messages SET unit_id=1 WHERE unit_id IS NULL;
ALTER TABLE audit_logs ADD COLUMN IF NOT EXISTS unit_id INT NULL, ADD INDEX IF NOT EXISTS idx_audit_unit (unit_id);
UPDATE audit_logs SET unit_id=1 WHERE unit_id IS NULL;
