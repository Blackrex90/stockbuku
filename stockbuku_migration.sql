-- stockbuku_migration.sql
-- Migration script to add sessions, loans, and constraints. Review before running. Backup DB first.

-- Create sessions table for custom session handler
CREATE TABLE IF NOT EXISTS sessions (
  id VARCHAR(128) PRIMARY KEY,
  data TEXT NOT NULL,
  last_access TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Create loans table to support perpanjangan (extensions) and returns
CREATE TABLE IF NOT EXISTS loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  idbuku INT NOT NULL,
  iduser INT NULL,
  borrowed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  due_date DATETIME NOT NULL,
  returned_at DATETIME NULL,
  fine_amount INT DEFAULT 0,
  extended_times INT DEFAULT 0,
  CONSTRAINT fk_loans_buku FOREIGN KEY (idbuku) REFERENCES buku(idbuku) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Ensure detil_pembeli has created_at for revenue queries
ALTER TABLE detil_pembeli ADD COLUMN IF NOT EXISTS created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- Add index on detil_pembeli.created_at for reporting
CREATE INDEX IF NOT EXISTS idx_detil_pembeli_created_at ON detil_pembeli(created_at);

-- Ensure login password column length is sufficient for bcrypt/argon2
ALTER TABLE login MODIFY COLUMN password VARCHAR(255) NOT NULL;

-- Add index for buku.judulbuku
CREATE INDEX IF NOT EXISTS idx_buku_judul ON buku(judulbuku(255));

-- Add index for pengirim.nobukti
CREATE INDEX IF NOT EXISTS idx_pengirim_nobukti ON pengirim(nobukti(100));
