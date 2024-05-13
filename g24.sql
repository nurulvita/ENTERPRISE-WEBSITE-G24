-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2024 at 05:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `g24`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `nama_admin` varchar(100) DEFAULT NULL,
  `username_admin` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `foto_admin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username_admin`, `password`, `no_hp`, `foto_admin`) VALUES
(1, 'Bayu Purnama Aji', 'adming24', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', '081234567893', '../../src/assets/img/profile-30.png');

-- --------------------------------------------------------

--
-- Table structure for table `admin_keranjang`
--

CREATE TABLE `admin_keranjang` (
  `id` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_keranjang`
--

INSERT INTO `admin_keranjang` (`id`, `id_produk`, `jumlah`, `harga`) VALUES
(87, 12, 1, 26000.00);

-- --------------------------------------------------------

--
-- Table structure for table `blog_post`
--

CREATE TABLE `blog_post` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `konten` longtext NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tanggal_publikasi` timestamp NOT NULL DEFAULT current_timestamp(),
  `showPublicly` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_post`
--

INSERT INTO `blog_post` (`id`, `judul`, `konten`, `gambar`, `tanggal_publikasi`, `showPublicly`) VALUES
(23, 'Pentingnya untuk Menjaga Tubuh Tetap Terhidrasi', 'Air putih adalah salah satu kebutuhan penting bagi tubuh manusia. Kebutuhan akan air putih tidak hanya berkaitan dengan kebutuhan fisik, tetapi juga memiliki dampak besar pada kesehatan secara keseluruhan. Dalam artikel ini, kita akan menjelajahi manfaat air putih serta memberikan tips dan panduan untuk menjaga tubuh tetap terhidrasi.\r\n\r\nManfaat Air Putih :\r\n\r\n\r\nAir putih memiliki berbagai manfaat penting bagi kesehatan tubuh. Pertama-tama, air putih membantu menjaga keseimbangan cairan dalam tubuh, yang penting untuk berbagai fungsi fisiologis seperti pencernaan, sirkulasi darah, dan regulasi suhu tubuh. Selain itu, air putih juga membantu dalam proses detoksifikasi tubuh, membersihkan racun dan limbah dari tubuh melalui urine dan keringat.\r\n\r\n\r\nTips untuk Meminum Air Putih:\r\n\r\n\r\n1. Minumlah Air Secukupnya: Minumlah air secara teratur sepanjang hari, bahkan sebelum merasa haus.\r\n\r\n\r\n2. Perhatikan Warna Urin: Perhatikan warna urine Anda sebagai indikator tingkat hidrasi tubuh.\r\n\r\n\r\n3. Sesuaikan dengan Aktivitas Fisik: Tingkatkan asupan air saat berolahraga atau melakukan aktivitas fisik yang menguras cairan.\r\n\r\n\r\n3. Perhatikan Lingkungan: Minumlah lebih banyak air saat terpapar cuaca panas atau lingkungan yang membuat tubuh cepat kehilangan cairan.\r\n\r\n\r\n4. Variasi Sumber Air: Selain air putih, Anda juga dapat mendapatkan cairan dari makanan dan minuman lainnya.\r\n\r\n', 'tips-1.png', '2024-05-04 11:34:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `catatan`
--

CREATE TABLE `catatan` (
  `id` int(11) NOT NULL,
  `judul` varchar(60) DEFAULT NULL,
  `deskripsi` varchar(100) DEFAULT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `catatan`
--

INSERT INTO `catatan` (`id`, `judul`, `deskripsi`, `tanggal`) VALUES
(36, 'tes', 'tes123', '2024-05-10 15:09:16');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemesanan`
--

CREATE TABLE `detail_pemesanan` (
  `id` int(11) NOT NULL,
  `no_pemesanan` varchar(20) DEFAULT NULL,
  `produk_id` int(11) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pemesanan`
--

INSERT INTO `detail_pemesanan` (`id`, `no_pemesanan`, `produk_id`, `username`, `nama_produk`, `jumlah`, `harga`) VALUES
(54, 'P2024050968', 13, 'verasw', 'Air Isi Ulang G-24', 2, 6000),
(55, 'P2024050968', 14, 'verasw', 'Air Galon Aqua Asli Pabrik', 1, 46000),
(73, 'P2024050950', 15, 'verasw', 'Filter Air', 1, 13000),
(80, 'P2024050921', 13, 'NON-MEMBER', 'Air Isi Ulang G-24', 2, 6000),
(84, 'P2024050962', 15, 'sandrinaaulia', 'Filter Air', 1, 13000),
(85, 'P2024050962', 13, 'sandrinaaulia', 'Air Isi Ulang G-24', 1, 6000),
(88, 'P2024050929', 13, 'verasw', 'Air Isi Ulang G-24', 2, 6000),
(116, 'P2024050929', 12, 'verasw', 'Galon DC Asli Pabrik', 2, 26000),
(132, 'P2024051044', 13, 'NON-MEMBER', 'Air Isi Ulang G-24', 1, 6000),
(133, 'P2024051044', 15, 'NON-MEMBER', 'Filter Air', 2, 13000),
(140, 'P2024051077', 12, 'NON-MEMBER', 'Galon DC Asli Pabrik', 1, 26000),
(151, 'P2024051046', 13, 'aristyav', 'Air Isi Ulang G-24', 2, 6000),
(154, 'P2024051046', 12, 'verasw', 'Galon DC Asli Pabrik', 2, 26000),
(155, 'P2024051046', 14, 'verasw', 'Air Galon Aqua Asli Pabrik', 2, 46000),
(161, 'P2024051061', 12, 'karinda', 'Galon DC Asli Pabrik', 2, 26000),
(162, 'P2024051061', 14, 'karinda', 'Air Galon Aqua Asli Pabrik', 2, 46000),
(163, 'P2024051061', 16, 'karinda', 'Tutup Galon', 1, 150000),
(164, 'P2024051092', 14, 'verasw', 'Air Galon Aqua Asli Pabrik', 1, 46000);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id` int(11) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `gambar_produk` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `kupon` int(11) DEFAULT 0,
  `kode_member` varchar(20) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `alamat`, `no_hp`, `foto_profil`, `kupon`, `kode_member`, `username`, `password`) VALUES
(11, 'Sandrina Aulia', 'Jalan M.Said Gang 4', '086567896433', 'sandrina.jpg', 0, 'G2400002', 'sandrinaaulia', '$2y$10$palNrwqCSCj2NWZCyDRJLewWJpxdpXX6XWPO83nx/fRtB0lePwxr.'),
(35, 'Vera Santi Wijaya', 'Jalan Pramuka 19', '08875435786', 'profil.png', 14, 'G2400003', 'verasw', '$2y$10$62y4OtrG85qwo/nS.qgR/uhpie0blSD4kmfdgO5rQInijlKGvLqRe'),
(36, 'Nurul Vita Azizah', 'Jalan Pramuka No 169B', '081356514550', 'profil.png', 0, 'G2400004', 'nurulvta', '$2y$10$qx4f2qPxYsBiLlsS5L7A1OEL/gOBQYxyACPiGel.m.G.7gQ0bJws2'),
(37, 'Bayu Purnama Aji', 'Jalan Juanda 8', '081356514550', 'profil.png', 0, 'G2400005', 'bayupr', '$2y$10$PB1j8jWKY/fO1WHzIc1z0OO5ypjUw5SGFCfMz0EphoCMnQHkufejW');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id` int(11) NOT NULL,
  `no_pemesanan` varchar(20) NOT NULL,
  `username` varchar(20) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `total_barang` int(11) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `jenis_pemesanan` varchar(20) DEFAULT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `no_pemesanan`, `username`, `nama`, `alamat`, `total_barang`, `total_harga`, `tanggal`, `jenis_pemesanan`, `status`) VALUES
(165, 'P2024050950', 'verasw', 'Vera Santi Wijaya', 'Jalan Pramuka 19', 1, 13000, '2024-05-09 15:35:42', 'Beli Langsung', 'Dikonfirmasi'),
(170, 'P2024050921', 'NON-MEMBER', '', '', 2, 12000, '2024-05-09 19:23:02', 'Antar', 'Dikonfirmasi'),
(172, 'P2024050962', 'sandrinaaulia', 'Sandrina Aulia', 'Jalan M.Said Gang 4', 2, 19000, '2024-05-09 20:10:27', 'Antar', 'Diantar'),
(173, 'P2024050929', 'verasw', 'Vera Santi Wijaya', 'Jalan Pramuka 19', 3, 38000, '2024-05-09 20:22:34', 'Antar', 'Selesai'),
(175, 'P2024051044', 'NON-MEMBER', '', '', 3, 32000, '2024-05-10 12:48:41', 'Beli Langsung', 'Dikonfirmasi'),
(179, 'P2024051077', 'NON-MEMBER', '', '', 1, 26000, '2024-05-10 12:53:27', 'Beli Langsung', 'Selesai'),
(192, 'P2024051046', 'aristyav', 'Aristy Avrianti', 'Jalan Loa Janan No 12', 6, 156000, '2024-05-10 15:08:18', 'Antar', 'Menunggu'),
(195, 'P2024051061', 'karinda', 'Siti Solikah Yosi ', 'Jalan Loa Bakung', 6, 307000, '2024-05-10 22:30:21', 'Antar', 'Selesai'),
(196, 'P2024051092', 'verasw', 'Vera Santi Wijaya', 'Jalan Pramuka 19', 1, 53000, '2024-05-10 23:07:40', 'Antar', 'Menunggu');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(255) DEFAULT NULL,
  `gambar_produk` varchar(255) DEFAULT NULL,
  `stok` enum('tersedia','tidak tersedia') DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `kategori` enum('galon','aksesoris','lainnya') DEFAULT NULL,
  `unit` enum('per pcs','per galon','per bungkus','per item') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `gambar_produk`, `stok`, `harga`, `kategori`, `unit`) VALUES
(12, 'Galon DC Asli Pabrik', 'produk_66361fa2b7fbe.jpg', 'tersedia', 26000.00, 'galon', 'per galon'),
(14, 'Air Galon Aqua Asli Pabrik', 'produk_66361fe4df045.jpg', 'tersedia', 46000.00, 'galon', 'per galon'),
(16, 'Tutup Galon', 'produk_66362040c5c89.jpg', 'tersedia', 150000.00, 'aksesoris', 'per bungkus'),
(17, 'Tissue Galon', 'produk_663620a722f17.png', 'tersedia', 10000.00, 'aksesoris', 'per bungkus'),
(18, 'Filter Air', 'produk_663e2b3ebd83b.png', 'tersedia', 13000.00, 'aksesoris', 'per item');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id_ulasan` int(11) NOT NULL,
  `username` text NOT NULL,
  `deskripsi` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ulasan`
--

INSERT INTO `ulasan` (`id_ulasan`, `username`, `deskripsi`, `rating`) VALUES
(23, 'sandrinaaulia', 'tes', 4),
(24, 'angelcrstn', 'tessss', 4),
(25, 'angelcrstn', 'bagusss', 5),
(26, 'nurulvta', 'ssss', 4),
(27, 'verasw', 'tess', 4),
(28, 'verasw', 'dfdghh', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `admin_keranjang`
--
ALTER TABLE `admin_keranjang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_post`
--
ALTER TABLE `blog_post`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catatan`
--
ALTER TABLE `catatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id_ulasan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_keranjang`
--
ALTER TABLE `admin_keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `blog_post`
--
ALTER TABLE `blog_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `catatan`
--
ALTER TABLE `catatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id_ulasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
