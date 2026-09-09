-- Run once against an existing army_vms database before enabling overspeed alerts.
ALTER TABLE movements
    ADD COLUMN authorized_officer_phone VARCHAR(20) NULL AFTER authorized_by;

ALTER TABLE gps_tracking
    ADD COLUMN speed_kmh DECIMAL(6,2) NULL AFTER timestamp;

CREATE TABLE IF NOT EXISTS speed_alerts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id VARCHAR(20) NOT NULL,
    movement_id VARCHAR(20) NULL,
    speed_kmh DECIMAL(6,2) NOT NULL,
    officer_phone VARCHAR(20) NOT NULL,
    notified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_speed_alert_vehicle_time (vehicle_id, notified_at)
);
