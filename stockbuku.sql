-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 24 Jan 2025 pada 01.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stockbuku` (merged and cleaned)
--

-- --------------------------------------------------------

--
-- Struktur tabel `buku`
--
CREATE TABLE IF NOT EXISTS `buku` (
  `idbuku` bigint(20) NOT NULL AUTO_INCREMENT,
  `judulbuku` varchar(255) NOT NULL,
  `genre_buku` varchar(100) DEFAULT NULL,
  `harga` decimal(30,2) NOT NULL,
  `stock` bigint(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idbuku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump data untuk tabel `buku`
INSERT INTO `buku` (`idbuku`, `judulbuku`, `genre_buku`, `harga`, `stock`, `image`) VALUES
(1, 'David Copperfield', 'Fiksi klasik', 85000.00, 9, '529cd71c859cf13313bb7c0a1d95d382.jpeg'),
(2, 'About a Boy', 'Fiksi dewasa, komedi romantis', 58000.00, 10, 'f4f2bf9450d56ef92e084fd33d43f801.jpg'),
(3, 'The Tower', 'Thriller, Suspense', 85000.00, 11, 'a1f7fa2462dd7937bd4b54a83fb2aff6.jpg'),
(4, 'Angela Ashes', 'Fiksi memoar, otobiografi', 75000.00, 14, 'ef866f4c6073835e418802de23a187ab.jpg'),
(5, 'The Innocent Man', 'Non-fiksi, Kejahatan, Hukum', 75000.00, 0, '45391e14d02d993437bec48095719152.jpg'),
(6, 'The King of Torts', 'Legal Thriller', 58000.00, 13, '08445422fb6cfadb09bdccf09fc26add.jpg'),
(7, 'Planet Earth', 'Non-fiction, Children''s Science', 58000.00, 4, 'c4d1bfdced955b71cf15aba2fda8ddd2.jpg');

-- --------------------------------------------------------

--
-- Struktur tabel `pembeli`
--
CREATE TABLE IF NOT EXISTS `pembeli` (
  `idpembeli` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_pembeli` varchar(255) NOT NULL,
  `tanggal_beli` date NOT NULL,
  PRIMARY KEY (`idpembeli`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump data untuk tabel `pembeli`
INSERT INTO `pembeli` (`idpembeli`, `nama_pembeli`, `tanggal_beli`) VALUES
(1, 'X', '2025-01-21'),
(2, 'A', '2025-01-22'),
(3, 'S', '2025-01-23'),
(4, 'V', '2025-01-24'),
(5, 'F', '2025-01-25'),
(6, 'G', '2025-01-26'),
(7, 'U', '2025-01-27'),
(8, 'W', '2025-01-28'),
(9, 'Q', '2025-01-29'),
(10, 'H', '2025-01-30'),
(11, 'I', '2025-01-31'),
(12, 'Z', '2025-02-01'),
(13, 'C', '2025-02-02'),
(14, 'B', '2025-02-03'),
(15, 'R', '2025-02-04'),
(16, 'O', '2025-02-05');

-- --------------------------------------------------------

--
-- Struktur tabel `penerbit`
--
CREATE TABLE IF NOT EXISTS `penerbit` (
  `idpenerbit` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `negara_asal` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idpenerbit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump data untuk tabel `penerbit`
INSERT INTO `penerbit` (`idpenerbit`, `nama`, `email`, `negara_asal`) VALUES
(1, 'X', 'x@c.co.id', 'United Kingdom'),
(2, 'J', 'j@a.co.uk', 'United Kingdom'),
(3, 'K', 'k@d.com', 'United Kingdom'),
(4, 'L', 'l@p.co.org', 'United Kingdom'),
(5, 'M', 'm@e.co.uk', 'United Kingdom'),
(6, 'P', 'p@s.co.uk', 'United Kingdom'),
(7, 'Q', 'q@z.co.uk', 'United Kingdom'),
(8, 'Z', 'z@h.co.uk', 'United Kingdom'),
(9, 'A', 'a@m.co.uk', 'United Kingdom'),
(10, 'C', 'c@B.co.uk', 'United Kingdom'),
(11, 'F', 'f@t.co.uk', 'United Kingdom'),
(12, 'V', 'v@o.co.uk', 'United Kingdom'),
(13, 'W', 'w@m.co.uk', 'United Kingdom'),
(14, 'G', 'g@q.co.uk', 'United Kingdom');

-- --------------------------------------------------------

--
-- Struktur tabel `pengirim`
--
CREATE TABLE IF NOT EXISTS `pengirim` (
  `idpenerbit` bigint(20) NOT NULL,
  `tanggal_kirim` date NOT NULL,
  `nobukti` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  PRIMARY KEY (`idpenerbit`),
  KEY `nobukti` (`nobukti`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump data untuk tabel `pengirim`
INSERT INTO `pengirim` (`idpenerbit`, `tanggal_kirim`, `nobukti`, `nama`) VALUES
(1, '2025-01-09', 'UK092431', 'X'),
(2, '2025-01-10', 'UK092432', 'J'),
(3, '2025-01-11', 'UK092433', 'K'),
(4, '2025-01-12', 'UK092434', 'L'),
(5, '2025-01-13', 'UK092435', 'M'),
(6, '2025-01-14', 'UK092436', 'P'),
(7, '2025-01-15', 'UK092437', 'Q');

-- --------------------------------------------------------

--
-- Struktur tabel `detil_pengirim`
--
CREATE TABLE IF NOT EXISTS `detil_pengirim` (
  `nobukti` varchar(50) NOT NULL,
  `idbuku` bigint(20) NOT NULL,
  `harga` decimal(30,2) NOT NULL,
  `qty` bigint(20) NOT NULL,
  PRIMARY KEY (`nobukti`),
  KEY `fk_detil_pengirim_idbuku` (`idbuku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump data untuk tabel `detil_pengirim`
INSERT INTO `detil_pengirim` (`nobukti`, `idbuku`, `harga`, `qty`) VALUES
('UK092431', 1, 1100000.00, 10),
('UK092432', 2, 1100000.00, 11),
('UK092433', 3, 1100000.00, 12),
('UK092434', 4, 1100000.00, 13),
('UK092435', 5, 1100000.00, 14),
('UK092436', 6, 1100000.00, 15),
('UK092437', 7, 1100000.00, 16);

-- --------------------------------------------------------

--
-- Struktur tabel `detil_pembeli`
-- Added created_at for reporting
--
CREATE TABLE IF NOT EXISTS `detil_pembeli` (
  `idpembeli` bigint(20) NOT NULL,
  `idbuku` bigint(20) NOT NULL,
  `qty` bigint(20) NOT NULL,
  `harga` decimal(30,2) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`idpembeli`),
  KEY `fk_detil_pembeli_idbuku` (`idbuku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump data untuk tabel `detil_pembeli`
INSERT INTO `detil_pembeli` (`idpembeli`, `idbuku`, `qty`, `harga`, `created_at`) VALUES
(1, 1, 1, 85000.00, CURRENT_TIMESTAMP),
(2, 2, 1, 58000.00, CURRENT_TIMESTAMP),
(3, 3, 1, 85000.00, CURRENT_TIMESTAMP),
(5, 7, 1, 58000.00, CURRENT_TIMESTAMP),
(6, 6, 1, 58000.00, CURRENT_TIMESTAMP),
(7, 6, 1, 58000.00, CURRENT_TIMESTAMP),
(8, 7, 1, 58000.00, CURRENT_TIMESTAMP),
(9, 7, 10, 580000.00, CURRENT_TIMESTAMP),
(11, 5, 10, 750000.00, CURRENT_TIMESTAMP),
(12, 5, 5, 375000.00, CURRENT_TIMESTAMP);

-- --------------------------------------------------------

--
-- Struktur tabel `login`
-- Ensure password column supports modern hashes
--
CREATE TABLE IF NOT EXISTS `login` (
  `iduser` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dump data untuk tabel `login`
INSERT INTO `login` (`iduser`, `username`, `email`, `password`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$P0ICcTHnyGem1IFac7q9veAypHOO9Plduo1jkr5WJutvOKk5QcH16');

-- --------------------------------------------------------

--
-- Struktur tabel `sessions`
-- (keberadaan sessions kept; no duplicate creation)
--
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `last_access` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- sample session data (kept from dump; may be truncated)
INSERT INTO `sessions` (`id`, `data`, `last_access`) VALUES
('0u9o0541486s6ld91gbblmroi5', 'LAST_ACTIVITY|i:1737655719;IP_ADDRESS|s:3:"::1";USER_AGENT|s:101:"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/...";', CURRENT_TIMESTAMP);

-- --------------------------------------------------------

-- Migration additions (merged from stockbuku_migration.sql)
-- Create loans table to support perpanjangan (extensions) and returns
CREATE TABLE IF NOT EXISTS `loans` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `idbuku` INT NOT NULL,
  `iduser` INT NULL,
  `borrowed_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `due_date` DATETIME NOT NULL,
  `returned_at` DATETIME NULL,
  `fine_amount` INT DEFAULT 0,
  `extended_times` INT DEFAULT 0,
  CONSTRAINT `fk_loans_buku` FOREIGN KEY (`idbuku`) REFERENCES `buku`(`idbuku`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add index on detil_pembeli.created_at for reporting
CREATE INDEX IF NOT EXISTS `idx_detil_pembeli_created_at` ON `detil_pembeli`(`created_at`);

-- Add index for buku.judulbuku (prefix to avoid large index size)
-- note: InnoDB/MariaDB may require a prefix for long TEXT; judulbuku is varchar(255)
CREATE INDEX IF NOT EXISTS `idx_buku_judul` ON `buku`(`judulbuku`(255));

-- Add index for pengirim.nobukti (prefix)
CREATE INDEX IF NOT EXISTS `idx_pengirim_nobukti` ON `pengirim`(`nobukti`(100));

-- --------------------------------------------------------

-- Foreign key constraints (ensure referential integrity)
ALTER TABLE `detil_pembeli`
  ADD CONSTRAINT `fk_detil_pembeli_idbuku` FOREIGN KEY (`idbuku`) REFERENCES `buku` (`idbuku`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detil_pembeli_idpembeli` FOREIGN KEY (`idpembeli`) REFERENCES `pembeli` (`idpembeli`) ON UPDATE CASCADE;

ALTER TABLE `detil_pengirim`
  ADD CONSTRAINT `fk_detil_pengirim_idbuku` FOREIGN KEY (`idbuku`) REFERENCES `buku` (`idbuku`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detil_pengirim_nobukti` FOREIGN KEY (`nobukti`) REFERENCES `pengirim` (`nobukti`) ON UPDATE CASCADE;

ALTER TABLE `pengirim`
  ADD CONSTRAINT `pengirim_ibfk_1` FOREIGN KEY (`idpenerbit`) REFERENCES `penerbit` (`idpenerbit`);

-- --------------------------------------------------------

-- Ensure AUTO_INCREMENT values are set sensibly
ALTER TABLE `buku`   AUTO_INCREMENT = 8;
ALTER TABLE `login`  AUTO_INCREMENT = 2;
ALTER TABLE `pembeli` AUTO_INCREMENT = 17;
ALTER TABLE `penerbit` AUTO_INCREMENT = 15;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
