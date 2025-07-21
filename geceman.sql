-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Jul 2025 pada 19.58
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geceman`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id` int(11) NOT NULL,
  `no_resi` varchar(20) NOT NULL,
  `nama_penerima` varchar(100) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Selesai','Proses','Menunggu') DEFAULT NULL,
  `status_tracking` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengiriman`
--

INSERT INTO `pengiriman` (`id`, `no_resi`, `nama_penerima`, `alamat`, `tanggal`, `status`, `status_tracking`) VALUES
(1, 'RE12343673', 'Akbar Sutejo', 'Jakarta', '2025-06-20', 'Selesai', 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim, Barang Diterima'),
(2, 'FG7634424', 'Kemal Jaya', 'Bandung', '2025-06-23', 'Selesai', 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim, Barang Diterima'),
(3, 'VN6877993', 'Rascal', 'Surabaya', '2025-07-21', 'Selesai', 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim, Barang Diterima'),
(4, 'XC6838776', 'Mamat Sabeni', 'Medan', '2025-07-13', 'Selesai', 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim, Barang Diterima'),
(5, 'TF6573219', 'Agung Sadewa', 'Yogyakarta', '2025-07-21', 'Proses', 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim'),
(6, 'GT1575218', 'Putri Ajeng ', 'Bandung', '2025-07-11', 'Menunggu', 'Dalam Perjalanan, Tiba Digudang'),
(7, 'WM6673025', 'Akmaludin', 'Tangerang', '2025-07-10', 'Menunggu', 'Dalam Perjalanan, Tiba Digudang');

--
-- Trigger `pengiriman`
--
DELIMITER $$
CREATE TRIGGER `insert_status_tracking` BEFORE INSERT ON `pengiriman` FOR EACH ROW BEGIN
    IF NEW.status = 'Menunggu' THEN
        SET NEW.status_tracking = 'Dalam Perjalanan, Tiba Digudang';
    ELSEIF NEW.status = 'Proses' THEN
        SET NEW.status_tracking = 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim';
    ELSEIF NEW.status = 'Selesai' THEN
        SET NEW.status_tracking = 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim, Barang Diterima';
    ELSE
        SET NEW.status_tracking = NULL;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `pengiriman_insert_status_tracking` BEFORE INSERT ON `pengiriman` FOR EACH ROW BEGIN
  IF NEW.status = 'Proses' THEN
    SET NEW.status_tracking = 'Dalam Perjalanan, Tiba Digudang';
  ELSEIF NEW.status = 'Selesai' THEN
    SET NEW.status_tracking = 'Barang Diterima';
  ELSE
    SET NEW.status_tracking = 'Menunggu';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_status_tracking` BEFORE UPDATE ON `pengiriman` FOR EACH ROW BEGIN
    IF NEW.status = 'Menunggu' THEN
        SET NEW.status_tracking = 'Dalam Perjalanan, Tiba Digudang';
    ELSEIF NEW.status = 'Proses' THEN
        SET NEW.status_tracking = 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim';
    ELSEIF NEW.status = 'Selesai' THEN
        SET NEW.status_tracking = 'Dalam Perjalanan, Tiba Digudang, Sedang Dikirim, Barang Diterima';
    ELSE
        SET NEW.status_tracking = NULL;
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_resi` (`no_resi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
