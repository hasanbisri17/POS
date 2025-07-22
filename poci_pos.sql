-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2025 at 04:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `poci_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1751890035),
('laravel_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1751890035;', 1751890035),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1751945532),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1751945532;', 1751945532),
('laravel_cache_spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:8:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:17:\"view_transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:15:\"manage_products\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:17:\"manage_categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:22:\"manage_payment_methods\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:24:\"manage_cash_transactions\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:22:\"manage_cash_categories\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:16:\"manage_inventory\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"manage_users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:7:\"cashier\";s:1:\"c\";s:3:\"web\";}}}', 1752123891);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_categories`
--

CREATE TABLE `cash_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('income','expense') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_categories`
--

INSERT INTO `cash_categories` (`id`, `name`, `description`, `type`, `is_active`, `is_system`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Penjualan', 'Pemasukan dari penjualan produk', 'income', 1, 1, '2025-06-11 16:29:53', '2025-06-11 16:29:53', NULL),
(2, 'Modal', 'Pemasukan dari modal usaha', 'income', 1, 0, '2025-06-11 16:29:53', '2025-06-11 16:29:53', NULL),
(3, 'Pembelian Bahan', 'Pengeluaran untuk pembelian bahan baku', 'expense', 1, 0, '2025-06-11 16:29:53', '2025-06-17 06:26:10', '2025-06-17 06:26:10'),
(4, 'Gaji Karyawan', 'Pengeluaran untuk gaji karyawan', 'expense', 1, 0, '2025-06-11 16:29:53', '2025-06-11 16:29:53', NULL),
(5, 'Operasional', 'Pengeluaran untuk biaya operasional', 'expense', 1, 0, '2025-06-11 16:29:53', '2025-06-17 06:26:37', '2025-06-17 06:26:37'),
(6, 'Biaya Operasional', 'Pengeluaran untuk biaya operasional', 'expense', 1, 1, '2025-06-11 16:29:56', '2025-06-11 16:29:56', NULL),
(7, 'Pendapatan Lain', 'Pemasukan dari sumber lain', 'income', 1, 1, '2025-06-11 16:29:56', '2025-06-17 05:57:27', '2025-06-17 05:57:27'),
(8, 'et', 'Repellat itaque quos labore incidunt consequuntur beatae voluptatum aut.', 'income', 1, 0, '2025-06-11 16:29:56', '2025-06-17 05:59:41', '2025-06-17 05:59:41'),
(9, 'qui', 'Praesentium blanditiis assumenda quaerat quaerat minima dolorem sed.', 'income', 1, 0, '2025-06-11 16:29:56', '2025-06-17 05:59:41', '2025-06-17 05:59:41'),
(10, 'ratione', 'Itaque consectetur aspernatur vero reprehenderit.', 'income', 1, 0, '2025-06-11 16:29:56', '2025-06-17 05:59:41', '2025-06-17 05:59:41'),
(11, 'repellendus', 'Exercitationem quos voluptate voluptate.', 'expense', 1, 0, '2025-06-11 16:29:56', '2025-06-17 05:59:41', '2025-06-17 05:59:41'),
(12, 'laborum', 'Qui iste ut quae sint.', 'expense', 1, 0, '2025-06-11 16:29:56', '2025-06-17 05:59:41', '2025-06-17 05:59:41'),
(13, 'et', 'Ipsum eveniet fuga officiis facilis aliquam ut.', 'expense', 1, 0, '2025-06-11 16:29:56', '2025-06-17 05:59:41', '2025-06-17 05:59:41'),
(14, 'pendapatan lain', NULL, 'income', 1, 0, '2025-06-17 05:58:26', '2025-06-17 05:59:41', '2025-06-17 05:59:41');

-- --------------------------------------------------------

--
-- Table structure for table `cash_transactions`
--

CREATE TABLE `cash_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cash_category_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_transactions`
--

INSERT INTO `cash_transactions` (`id`, `cash_category_id`, `amount`, `type`, `description`, `transaction_date`, `created_by`, `transaction_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 32926.00, 'income', 'Pemasukan dari transaksi #INV202506170001', '2025-06-17', 1, 1, '2025-06-17 05:27:51', '2025-06-17 05:27:51', NULL),
(2, 1, 44081.00, 'income', 'Pemasukan dari transaksi #INV202506170002', '2025-06-17', 2, 2, '2025-06-17 05:45:35', '2025-06-17 05:45:35', NULL),
(3, 1, 71747.00, 'income', 'Pemasukan dari transaksi #INV202506170003', '2025-06-17', 1, 3, '2025-06-17 06:10:21', '2025-06-17 06:10:21', NULL),
(4, 1, 44081.00, 'income', 'Pemasukan dari transaksi #INV202506300001', '2025-06-30', 1, 4, '2025-06-29 18:04:48', '2025-06-29 18:04:48', NULL),
(5, 1, 36789.00, 'income', 'Pemasukan dari transaksi #INV202507060001', '2025-07-06', 1, 5, '2025-07-06 01:10:05', '2025-07-06 01:10:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Minuman', NULL, 1, '2025-06-11 16:29:56', '2025-06-11 16:29:56', NULL),
(2, 'Makanan', NULL, 1, '2025-06-11 16:29:56', '2025-06-11 16:29:56', NULL),
(3, 'Snack', NULL, 1, '2025-06-11 16:29:56', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(4, 'Topping', NULL, 1, '2025-06-11 16:29:56', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(5, 'Lainnya', NULL, 1, '2025-06-11 16:29:56', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(6, 'enim', 'Aut aut est voluptas itaque ducimus ducimus et.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(7, 'accusantium', 'Vel et inventore assumenda.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(8, 'harum', 'Perspiciatis recusandae voluptatem hic neque inventore quo.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(9, 'dignissimos', 'Exercitationem animi quaerat et dolores.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(10, 'fugit', 'Quia ipsum reprehenderit nostrum.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:39', '2025-06-17 06:14:39'),
(11, 'voluptatibus', 'Quaerat rem quos unde sunt.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:55', '2025-06-17 06:14:55'),
(12, 'laborum', 'Officiis delectus facilis culpa accusamus corrupti ullam.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:55', '2025-06-17 06:14:55'),
(13, 'officia', 'Et dolor incidunt ad iure maxime nostrum adipisci earum.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:55', '2025-06-17 06:14:55'),
(14, 'quia', 'Eum omnis consectetur tempore doloribus.', 1, '2025-06-11 16:29:57', '2025-06-17 06:14:55', '2025-06-17 06:14:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_06_01_120806_create_permission_tables', 1),
(5, '2025_06_01_120817_create_categories_table', 1),
(6, '2025_06_01_120818_create_products_table', 1),
(7, '2025_06_01_120819_create_product_variants_table', 1),
(8, '2025_06_01_120820_create_stock_movements_table', 1),
(9, '2025_06_01_120821_create_payment_methods_table', 1),
(10, '2025_06_01_120822_create_transactions_table', 1),
(11, '2025_06_01_120824_create_transaction_items_table', 1),
(12, '2025_06_01_120825_create_cash_categories_table', 1),
(13, '2025_06_01_120826_create_cash_transactions_table', 1),
(14, '2025_06_01_120827_add_foreign_keys_to_stock_movements_table', 1),
(15, '2025_06_01_121613_create_personal_access_tokens_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`config`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `code`, `description`, `config`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Tunai', 'cash', 'Pembayaran tunai', '{\"requires_verification\":false,\"auto_confirm\":true}', 1, '2025-06-11 16:29:53', '2025-06-11 16:29:56', NULL),
(2, 'QRIS', 'qris', 'Pembayaran menggunakan QRIS', '{\"requires_verification\":\"1\",\"auto_confirm\":null,\"merchant_id\":\"123456789\"}', 0, '2025-06-11 16:29:56', '2025-06-17 06:28:52', NULL),
(3, 'Transfer Bank', 'transfer-bank-9888', 'Molestiae sed temporibus et commodi similique quasi esse.', '{\"requires_verification\":null,\"auto_confirm\":\"1\"}', 0, '2025-06-11 16:29:56', '2025-06-17 05:23:04', NULL),
(4, 'E-Wallet', 'e-wallet-2218', 'Id eos molestiae quos quis in.', '{\"requires_verification\":null,\"auto_confirm\":\"1\"}', 0, '2025-06-11 16:29:56', '2025-06-17 05:22:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_transactions', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(2, 'manage_products', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(3, 'manage_categories', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(4, 'manage_payment_methods', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(5, 'manage_cash_transactions', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(6, 'manage_cash_categories', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(7, 'manage_inventory', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(8, 'manage_users', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `base_price`, `image`, `stock`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'quo', 'Tempore nulla aut cum libero cum minima velit.', 40081.00, NULL, 15, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(2, 1, 'numquam', 'Deserunt facilis ipsa rerum autem sunt molestiae aperiam.', 31666.00, NULL, 82, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(3, 1, 'ex', 'Quibusdam aut est quo qui sequi sint aliquid vero.', 22591.00, NULL, 2, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(4, 1, 'quia', 'Molestiae et eos sed neque ut error dolores.', 7302.00, NULL, 83, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(5, 1, 'est', 'Aspernatur commodi et odio assumenda quibusdam sint voluptatum.', 15548.00, NULL, 55, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(6, 2, 'rerum', 'Ipsa sunt id voluptatibus ut voluptas.', 5929.00, NULL, 44, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(7, 2, 'soluta', 'Ipsum corporis nisi quod molestiae dolor unde.', 6171.00, NULL, 1, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(8, 2, 'molestiae', 'Qui ut nostrum repudiandae et eligendi.', 42269.00, NULL, 25, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(9, 2, 'eos', 'Dolorem quo perspiciatis accusantium voluptatem in.', 15810.00, NULL, 39, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(10, 2, 'aliquid', 'Laudantium hic quasi omnis.', 26696.00, NULL, 92, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:19', '2025-07-07 04:58:19'),
(11, 3, 'natus', 'Ipsa velit ab nostrum.', 46027.00, NULL, 55, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(12, 3, 'est', 'Aperiam suscipit odio modi omnis voluptas minima consectetur numquam.', 34051.00, NULL, 91, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(13, 3, 'ipsum', 'Voluptas non aperiam magnam quia.', 6632.00, NULL, 34, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(14, 3, 'id', 'Neque harum ut possimus molestiae odit labore.', 30392.00, NULL, 93, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(15, 3, 'sit', 'Maiores voluptates corrupti vel et odio illum.', 48074.00, NULL, 2, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(16, 4, 'soluta', 'Consectetur autem reiciendis voluptas vel maxime aut.', 10221.00, NULL, 92, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(17, 4, 'totam', 'Pariatur minima itaque omnis totam nihil est esse.', 9891.00, NULL, 80, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(18, 4, 'ad', 'Voluptas modi aut natus soluta qui perferendis a.', 21795.00, NULL, 35, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(19, 4, 'fuga', 'Rerum tempore quaerat inventore illum eligendi eum.', 6235.00, NULL, 25, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(20, 4, 'voluptatem', 'Magni aut consequuntur error.', 29114.00, NULL, 24, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:35', '2025-07-07 04:58:35'),
(21, 5, 'ratione', 'Quaerat harum nemo alias consequatur consequatur.', 22936.00, NULL, 9, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(22, 5, 'quis', 'Dolores minima omnis est qui sit.', 30133.00, NULL, 51, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(23, 5, 'eum', 'Voluptas rerum dolor dolores earum deleniti qui.', 11175.00, NULL, 94, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(24, 5, 'non', 'Officia libero sunt ratione et est earum.', 33805.00, NULL, 86, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(25, 5, 'quia', 'Vel sed ut ut quisquam qui occaecati.', 34789.00, NULL, 14, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(26, 6, 'a', 'Esse facilis deserunt et numquam repellendus ipsa ut quo.', 20123.00, NULL, 29, 0, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(27, 7, 'maiores', 'Aut amet asperiores quam occaecati ratione.', 48904.00, NULL, 100, 0, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(28, 8, 'sed', 'Est praesentium ducimus aspernatur enim eveniet accusantium architecto.', 6328.00, NULL, 21, 0, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(29, 9, 'laudantium', 'Et quo aut quia culpa autem est cum.', 45561.00, NULL, 7, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(30, 10, 'molestiae', 'Reprehenderit suscipit ipsa autem sunt quis blanditiis quia.', 8261.00, NULL, 1, 1, '2025-06-11 16:29:57', '2025-07-07 04:58:50', '2025-07-07 04:58:50'),
(31, 11, 'quis', 'Ab alias perspiciatis eum hic reprehenderit.', 19257.00, NULL, 3, 1, '2025-06-11 16:29:57', '2025-07-07 04:59:06', '2025-07-07 04:59:06'),
(32, 12, 'voluptatum', 'Deleniti excepturi labore quidem facilis.', 49997.00, NULL, 10, 1, '2025-06-11 16:29:57', '2025-07-07 04:59:06', '2025-07-07 04:59:06'),
(33, 13, 'eligendi', 'Architecto saepe voluptatibus quaerat qui eum eos.', 25808.00, NULL, 0, 1, '2025-06-11 16:29:57', '2025-07-07 04:59:06', '2025-07-07 04:59:06'),
(34, 14, 'et', 'Voluptatem sit debitis quod.', 8810.00, NULL, 0, 1, '2025-06-11 16:29:57', '2025-07-07 04:59:06', '2025-07-07 04:59:06'),
(35, 1, 'Teh Poci', NULL, 5000.00, 'products/student (1).png', 100, 1, '2025-06-17 05:36:07', '2025-07-07 04:59:06', '2025-07-07 04:59:06'),
(36, 1, 'Jasmine Iced Tea', 'Es Teh Jasmin', 5000.00, 'products/ETP - Menu for Website ORI-01.png', 100, 1, '2025-07-07 05:02:32', '2025-07-07 05:02:32', NULL),
(37, 1, 'Vanilla Iced Tea', 'Es Teh Vanila', 5000.00, 'products/ETP - Menu for Website ORI-02.png', 100, 1, '2025-07-07 05:04:00', '2025-07-07 05:04:00', NULL),
(38, 1, 'Lychee Iced Tea', 'Es Teh Leci', 5000.00, 'products/ETP - Menu for Website FRUITY-05.png', 100, 1, '2025-07-07 05:05:16', '2025-07-07 05:05:16', NULL),
(39, 1, 'Blackcurrant Iced Tea', 'Es Teh Blackcurrant', 5000.00, 'products/ETP - Menu for Website FRUITY-02.png', 100, 1, '2025-07-07 05:06:35', '2025-07-07 05:06:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price_adjustment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `price_adjustment`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Medium', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(2, 1, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(3, 1, 'Bubble', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(4, 2, 'Small', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(5, 2, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(6, 2, 'Jelly', 5000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(7, 3, 'Medium', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(8, 3, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(9, 3, 'Jelly', 5000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(10, 4, 'Small', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(11, 4, 'Less Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(12, 4, 'Jelly', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(13, 5, 'Small', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(14, 5, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(15, 5, 'Pudding', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(16, 6, 'Small', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(17, 6, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(18, 6, 'Pudding', 5000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(19, 7, 'Large', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(20, 7, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(21, 7, 'Jelly', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(22, 8, 'Medium', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(23, 8, 'Less Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(24, 8, 'Cheese Foam', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(25, 9, 'Large', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(26, 9, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(27, 9, 'Pudding', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(28, 10, 'Large', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(29, 10, 'Less Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(30, 10, 'Cheese Foam', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(31, 11, 'Medium', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(32, 11, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(33, 11, 'Bubble', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(34, 12, 'Large', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(35, 12, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(36, 12, 'Pudding', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(37, 13, 'Small', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(38, 13, 'Less Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(39, 13, 'Pudding', 5000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(40, 14, 'Medium', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(41, 14, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(42, 14, 'Jelly', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(43, 15, 'Medium', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(44, 15, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(45, 15, 'Cheese Foam', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(46, 16, 'Small', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(47, 16, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(48, 16, 'Pudding', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(49, 17, 'Large', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(50, 17, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(51, 17, 'Pudding', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(52, 18, 'Small', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(53, 18, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(54, 18, 'Cheese Foam', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(55, 19, 'Medium', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(56, 19, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(57, 19, 'Cheese Foam', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(58, 20, 'Large', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(59, 20, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(60, 20, 'Jelly', 5000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(61, 21, 'Small', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(62, 21, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(63, 21, 'Cheese Foam', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(64, 22, 'Large', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(65, 22, 'Normal Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(66, 22, 'Cheese Foam', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(67, 23, 'Medium', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(68, 23, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(69, 23, 'Jelly', 4000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(70, 24, 'Large', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(71, 24, 'Extra Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(72, 24, 'Pudding', 5000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(73, 25, 'Medium', 2000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(74, 25, 'Less Sugar', 0.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(75, 25, 'Pudding', 3000.00, 1, '2025-06-11 16:29:57', '2025-06-11 16:29:57', NULL),
(76, 35, 'Large', 2000.00, 1, '2025-06-17 05:36:07', '2025-06-17 05:36:07', NULL),
(77, 35, 'Ekstra Large', 4000.00, 1, '2025-06-17 05:36:07', '2025-06-17 05:36:07', NULL),
(78, 36, 'Large', 2000.00, 1, '2025-07-07 05:02:32', '2025-07-07 05:02:32', NULL),
(79, 36, 'Hot', 1000.00, 1, '2025-07-07 05:02:32', '2025-07-07 05:02:32', NULL),
(80, 37, 'Large', 2000.00, 1, '2025-07-07 05:04:00', '2025-07-07 05:04:00', NULL),
(81, 37, 'Hot', 1000.00, 1, '2025-07-07 05:04:00', '2025-07-07 05:04:00', NULL),
(82, 38, 'Large', 2000.00, 1, '2025-07-07 05:05:16', '2025-07-07 05:05:16', NULL),
(83, 38, 'Hot', 1000.00, 1, '2025-07-07 05:05:16', '2025-07-07 05:05:16', NULL),
(84, 39, 'Large', 2000.00, 1, '2025-07-07 05:06:35', '2025-07-07 05:06:35', NULL),
(85, 39, 'Hot', 1000.00, 1, '2025-07-07 05:06:35', '2025-07-07 05:06:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(2, 'cashier', 'web', '2025-06-11 16:29:56', '2025-06-11 16:29:56');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(7, 2),
(8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('BPtBYFVIFurEnpUUyxD9dEgh0y74yWlhFqZKiuAj', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVHFITDVIMU8yUlZUb0o5akc1MjNsNnZqYWdGaEZ1OGpYbnh2cWpVTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wcm9kdWN0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkQ2ZsS2xVWGg2Y0NYRVQ2TUpjNHROLnVRYlZGT212WVNZUFRERFlqYUJvTTFLM3ovWFVXdnUiO30=', 1751945545),
('iOBXIeHtXehJ6bzhVdGixVWAZGDPZBCwMJ5rPNbE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZE1OZ1B6a1Rmekk5ejhOZWI4d2JqTlp2aGMzektacE04SnR0ejduRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1751888956),
('PPD8aXtTVunW5CJYn9D1Fknxl8YVYNIUiIY1tAe9', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidU0wNzcyV2N3N1kxcThPNXVnS0Y4RDlXQ1dQdkxtSzJMcmRNZ2ltbCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zdG9jay1tb3ZlbWVudHMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkQ2ZsS2xVWGg2Y0NYRVQ2TUpjNHROLnVRYlZGT212WVNZUFRERFlqYUJvTTFLM3ovWFVXdnUiO30=', 1751894270),
('WAJcQgRxaE1UqQC54XnYXrU6kB7zfhSpeSdxNqi1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieFhvOG54eTQ5Y0U3SVUyWll0dFZiYWdhWTI3Zmgzek5PZWxTQjRTbyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3N0b2NrLW1vdmVtZW50cyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQzOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4vc3RvY2stbW92ZW1lbnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1751945358);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('in','out') NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `quantity`, `type`, `notes`, `created_by`, `transaction_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 16, 1, 'out', 'Pengurangan stok dari transaksi penjualan', 1, 1, '2025-06-17 05:27:51', '2025-06-17 05:27:51', NULL),
(2, 19, 3, 'out', 'Pengurangan stok dari transaksi penjualan', 1, 1, '2025-06-17 05:27:51', '2025-06-17 05:27:51', NULL),
(3, 32, 10, 'in', 'tambah stok', 1, NULL, '2025-06-17 05:30:33', '2025-06-17 05:30:33', NULL),
(4, 35, 100, 'in', 'Stok awal produk', 1, NULL, '2025-06-17 05:36:07', '2025-06-17 05:36:07', NULL),
(5, 1, 1, 'out', 'Pengurangan stok dari transaksi penjualan', 2, 2, '2025-06-17 05:45:35', '2025-06-17 05:45:35', NULL),
(6, 1, 1, 'out', 'Pengurangan stok dari transaksi penjualan', 1, 3, '2025-06-17 06:10:21', '2025-06-17 06:10:21', NULL),
(7, 2, 1, 'out', 'Pengurangan stok dari transaksi penjualan', 1, 3, '2025-06-17 06:10:21', '2025-06-17 06:10:21', NULL),
(8, 1, 8, 'in', 'penambahan stok', 4, NULL, '2025-06-17 06:46:40', '2025-06-17 06:46:40', NULL),
(9, 1, 1, 'out', 'Pengurangan stok dari transaksi penjualan', 1, 4, '2025-06-29 18:04:48', '2025-06-29 18:04:48', NULL),
(10, 25, 1, 'out', 'Pengurangan stok dari transaksi penjualan', 1, 5, '2025-07-06 01:10:05', '2025-07-06 01:10:05', NULL),
(11, 36, 100, 'in', 'Stok awal produk', 1, NULL, '2025-07-07 05:02:32', '2025-07-07 05:02:32', NULL),
(12, 37, 100, 'in', 'Stok awal produk', 1, NULL, '2025-07-07 05:04:00', '2025-07-07 05:04:00', NULL),
(13, 38, 100, 'in', 'Stok awal produk', 1, NULL, '2025-07-07 05:05:16', '2025-07-07 05:05:16', NULL),
(14, 39, 100, 'in', 'Stok awal produk', 1, NULL, '2025-07-07 05:06:35', '2025-07-07 05:06:35', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `change_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `invoice_number`, `payment_method_id`, `total_amount`, `payment_amount`, `change_amount`, `status`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'INV202506170001', 1, 32926.00, 100000.00, 67074.00, 'completed', '', 1, '2025-06-17 05:27:51', '2025-06-17 05:27:51', NULL),
(2, 'INV202506170002', 1, 44081.00, 50000.00, 5919.00, 'completed', '', 2, '2025-06-17 05:45:35', '2025-06-17 05:45:35', NULL),
(3, 'INV202506170003', 1, 71747.00, 100000.00, 28253.00, 'completed', '', 1, '2025-06-17 06:10:21', '2025-06-17 06:10:21', NULL),
(4, 'INV202506300001', 1, 44081.00, 50000.00, 5919.00, 'completed', '', 1, '2025-06-29 18:04:48', '2025-06-29 18:04:48', NULL),
(5, 'INV202507060001', 1, 36789.00, 100000.00, 63211.00, 'completed', '', 1, '2025-07-06 01:10:05', '2025-07-06 01:10:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `product_variant_id`, `quantity`, `price`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 16, 48, 1, 14221.00, 14221.00, NULL, '2025-06-17 05:27:51', '2025-06-17 05:27:51'),
(2, 1, 19, 55, 3, 6235.00, 18705.00, NULL, '2025-06-17 05:27:51', '2025-06-17 05:27:51'),
(3, 2, 1, 1, 1, 44081.00, 44081.00, NULL, '2025-06-17 05:45:35', '2025-06-17 05:45:35'),
(4, 3, 1, NULL, 1, 40081.00, 40081.00, NULL, '2025-06-17 06:10:21', '2025-06-17 06:10:21'),
(5, 3, 2, 5, 1, 31666.00, 31666.00, NULL, '2025-06-17 06:10:21', '2025-06-17 06:10:21'),
(6, 4, 1, 1, 1, 44081.00, 44081.00, NULL, '2025-06-29 18:04:48', '2025-06-29 18:04:48'),
(7, 5, 25, 73, 1, 36789.00, 36789.00, NULL, '2025-07-06 01:10:05', '2025-07-06 01:10:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@example.com', '2025-06-11 16:29:55', '$2y$12$CflKlUXh6cCXET6MJc4tN.uQbVFOmvYSYPTDDYjaBoM1K3z/XUWvu', 'M7LxlxPYAkuovSRegYXpCZ4N3ab1R2t1VuIsgwklH0LjhZH9qujs6dCZgopW', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(2, 'Kasir', 'kasir@example.com', '2025-06-11 16:29:56', '$2y$12$9i.P5oCRv3/n/eZmAoM7..oC5Q4q/UPPiDccCqydRkOmQrXH301se', 'rVinGIVoZHDEQn2HNqenKtFzPsx9964GWRfoZHqJWt2uVyJgUrT7n9Rw0dAL', '2025-06-11 16:29:56', '2025-06-11 16:29:56'),
(3, 'shania', 'shaniady46@gmail.com', NULL, '$2y$12$GipaOg7nPHjHJHWr/0CCUO1311KlUEdoCv8GAbcIPsjCz.jnlBQ.W', 'ql6dTusIXnDcwKBp4ZWGN2O56XVw71HfnLIEhLGpZ2W5tE2tJPSVfp7PqtkC', '2025-06-17 06:31:08', '2025-06-17 06:31:08'),
(4, 'shania', 'shaniadwiy@gmail.com', NULL, '$2y$12$j9ZWTrehQqTy5wMmEq38VO9oKtGQNM/xjzTVo6Fi4a8pl2FLYrvua', NULL, '2025-06-17 06:35:16', '2025-06-17 06:35:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cash_categories`
--
ALTER TABLE `cash_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_transactions_cash_category_id_foreign` (`cash_category_id`),
  ADD KEY `cash_transactions_created_by_foreign` (`created_by`),
  ADD KEY `cash_transactions_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_code_unique` (`code`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_created_by_foreign` (`created_by`),
  ADD KEY `stock_movements_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_invoice_number_unique` (`invoice_number`),
  ADD KEY `transactions_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `transactions_created_by_foreign` (`created_by`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_items_transaction_id_foreign` (`transaction_id`),
  ADD KEY `transaction_items_product_id_foreign` (`product_id`),
  ADD KEY `transaction_items_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cash_categories`
--
ALTER TABLE `cash_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD CONSTRAINT `cash_transactions_cash_category_id_foreign` FOREIGN KEY (`cash_category_id`) REFERENCES `cash_categories` (`id`),
  ADD CONSTRAINT `cash_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cash_transactions_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `stock_movements_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`);

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `transaction_items_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`),
  ADD CONSTRAINT `transaction_items_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
