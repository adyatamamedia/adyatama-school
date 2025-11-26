-- Add featured_img column to galleries table
ALTER TABLE galleries 
ADD COLUMN featured_img VARCHAR(255) NULL AFTER extracurricular_id;
