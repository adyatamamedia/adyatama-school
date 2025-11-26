-- Add bio column to guru_staff table
ALTER TABLE guru_staff 
ADD COLUMN bio TEXT NULL AFTER no_hp;
