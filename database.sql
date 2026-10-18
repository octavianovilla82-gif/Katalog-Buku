CREATE DATABASE IF NOT EXISTS katalog_buku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE katalog_buku;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS books (
  id INT AUTO_INCREMENT PRIMARY KEY,
  judul VARCHAR(150) NOT NULL,
  penulis VARCHAR(100) NOT NULL,
  penerbit VARCHAR(100) NOT NULL,
  tahun SMALLINT NOT NULL,
  kategori VARCHAR(30) NOT NULL
);

-- Login: admin / admin123
INSERT INTO users (username, password) VALUES
('admin', '$2y$10$jkqTkOte.1rJjfIKEV2UjeTP1.GK2J8HleyZruND02CgV1nwDVwd.');

INSERT INTO books (judul, penulis, penerbit, tahun, kategori) VALUES
('Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 'fiksi'),
('Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', 1980, 'fiksi'),
('Pemrograman PHP', 'Budi Raharjo', 'Informatika', 2020, 'teknologi');
