-- Move bio column to after bidang
ALTER TABLE guru_staff 
MODIFY COLUMN bio TEXT NULL AFTER bidang;
