-- Add all supported VMS roles to existing installations.
-- Run once against the army_vms database before creating mechanic or fuel manager users.
ALTER TABLE users
    MODIFY role ENUM('admin','officer','driver','mechanic','fuel_manager')
    NOT NULL DEFAULT 'officer';
