-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for db_barbershop
CREATE DATABASE IF NOT EXISTS `db_barbershop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_barbershop`;

-- Dumping structure for table db_barbershop.barber_capacities
CREATE TABLE IF NOT EXISTS `barber_capacities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `active_barbers` smallint unsigned NOT NULL DEFAULT '4',
  `opening_time` time NOT NULL DEFAULT '10:00:00',
  `closing_time` time NOT NULL DEFAULT '21:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `barber_capacities_date_unique` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.barber_capacities: ~1 rows (approximately)
INSERT INTO `barber_capacities` (`id`, `date`, `active_barbers`, `opening_time`, `closing_time`, `created_at`, `updated_at`) VALUES
	(1, '2026-06-20', 4, '10:00:00', '21:00:00', '2026-06-20 05:08:10', '2026-06-20 05:08:10');

-- Dumping structure for table db_barbershop.barber_logs
CREATE TABLE IF NOT EXISTS `barber_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `barber_slot` int unsigned NOT NULL,
  `service_start_at` datetime NOT NULL,
  `service_end_at` datetime NOT NULL,
  `status` enum('waiting','serving','done') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `barber_logs_booking_id_foreign` (`booking_id`),
  CONSTRAINT `barber_logs_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.barber_logs: ~1 rows (approximately)
INSERT INTO `barber_logs` (`id`, `booking_id`, `barber_slot`, `service_start_at`, `service_end_at`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2026-06-20 17:21:13', '2026-06-20 18:06:13', 'waiting', '2026-06-20 10:21:13', '2026-06-20 10:21:13'),
	(6, 2, 2, '2026-06-20 17:22:26', '2026-06-20 18:37:26', 'waiting', '2026-06-20 10:22:26', '2026-06-20 10:22:26'),
	(10, 3, 3, '2026-06-20 17:23:10', '2026-06-20 18:23:10', 'waiting', '2026-06-20 10:23:10', '2026-06-20 10:23:10'),
	(13, 4, 4, '2026-06-20 17:23:33', '2026-06-20 18:23:33', 'waiting', '2026-06-20 10:23:33', '2026-06-20 10:23:33'),
	(33, 7, 4, '2026-06-20 18:23:33', '2026-06-20 19:23:33', 'waiting', '2026-06-20 10:25:34', '2026-06-20 10:25:34'),
	(35, 5, 1, '2026-06-20 18:06:13', '2026-06-20 18:51:13', 'waiting', '2026-06-20 10:25:45', '2026-06-20 10:25:45'),
	(39, 6, 3, '2026-06-20 18:23:10', '2026-06-20 19:08:10', 'waiting', '2026-06-20 11:28:45', '2026-06-20 11:28:45'),
	(44, 8, 2, '2026-06-20 18:37:26', '2026-06-20 19:52:26', 'waiting', '2026-06-20 11:29:33', '2026-06-20 11:29:33'),
	(45, 9, 1, '2026-06-20 18:51:13', '2026-06-20 19:36:13', 'waiting', '2026-06-20 11:29:33', '2026-06-20 11:29:33'),
	(46, 10, 3, '2026-06-20 19:08:10', '2026-06-20 20:23:10', 'waiting', '2026-06-20 12:01:15', '2026-06-20 12:01:15');

-- Dumping structure for table db_barbershop.bookings
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `public_id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queue_number` int unsigned NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_duration` smallint unsigned NOT NULL,
  `visit_date` date NOT NULL,
  `estimated_waiting_time` int unsigned NOT NULL DEFAULT '0',
  `estimated_service_time` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Online',
  `status` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Menunggu',
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_visit_date_queue_number_unique` (`visit_date`,`queue_number`),
  UNIQUE KEY `bookings_public_id_unique` (`public_id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  KEY `bookings_service_id_foreign` (`service_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_visit_date_status_queue_number_index` (`visit_date`,`status`,`queue_number`),
  KEY `bookings_phone_index` (`phone`),
  KEY `bookings_visit_date_index` (`visit_date`),
  KEY `bookings_queue_type_index` (`queue_type`),
  KEY `bookings_status_index` (`status`),
  CONSTRAINT `bookings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.bookings: ~6 rows (approximately)
INSERT INTO `bookings` (`id`, `public_id`, `booking_code`, `queue_number`, `customer_name`, `phone`, `service_id`, `service_name`, `service_duration`, `visit_date`, `estimated_waiting_time`, `estimated_service_time`, `queue_type`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, '01KVJ8S03T2QKXXHTJTE50HG5S', 'XYZ-0001', 1, 'HensenRares', '0813212387642', 1, 'Basic Cut', 45, '2026-06-20', 0, '17:21', 'Online', 'Selesai', 3, '2026-06-20 10:21:13', '2026-06-20 10:25:22'),
	(2, '01KVJ8SB50NRB7JSJ608QW287A', 'XYZ-0002', 2, 'HensenRares2', '0813212387642', 4, 'Full Treatment', 75, '2026-06-20', 0, '17:22', 'Online', 'Selesai', 3, '2026-06-20 10:21:25', '2026-06-20 10:25:29'),
	(3, '01KVJ8SPT8G4T12R88AQ6VTX9E', 'XYZ-0003', 3, 'HensenRares3', '0813212387642', 3, 'Haircut + Beard', 60, '2026-06-20', 0, '17:23', 'Online', 'Selesai', 3, '2026-06-20 10:21:36', '2026-06-20 10:25:33'),
	(4, '01KVJ8T22FFNM6PHDPZ0S1HZ69', 'XYZ-0004', 4, 'HensenRares4', '0813212387642', 2, 'Haircut + Wash', 60, '2026-06-20', 0, '17:23', 'Online', 'Selesai', 3, '2026-06-20 10:21:48', '2026-06-20 10:25:34'),
	(5, '01KVJ8TPG2PFRA780WHG7R58R7', 'XYZ-0005', 5, 'naufal', '081308182723', 1, 'Basic Cut', 45, '2026-06-20', 40, '18:06', 'Walk-in', 'Selesai', NULL, '2026-06-20 10:22:09', '2026-06-20 11:29:33'),
	(6, '01KVJ8YJJYTDBGQ57T5FBGTEWR', 'XYZ-0006', 6, 'HensenRares6', '0813212387642', 1, 'Basic Cut', 45, '2026-06-20', 0, '18:23', 'Online', 'Sedang Dilayani', 3, '2026-06-20 10:24:16', '2026-06-20 11:29:14'),
	(7, '01KVJ8YX3G452VYAN7SYPQNNJ8', 'XYZ-0007', 7, 'HensenRares7', '0813212387642', 3, 'Haircut + Beard', 60, '2026-06-20', 58, '18:23', 'Online', 'Dibatalkan', 3, '2026-06-20 10:24:27', '2026-06-20 10:25:45'),
	(8, '01KVJ9043QQR61KQPZQ1E4BT0H', 'XYZ-0008', 8, 'HensenRares8', '0813212387642', 4, 'Full Treatment', 75, '2026-06-20', 8, '18:37', 'Online', 'Menunggu', 3, '2026-06-20 10:25:07', '2026-06-20 11:29:33'),
	(9, '01KVJCKJ0C0VVYZHJWDV7WV5MK', 'XYZ-0009', 9, 'HensenRares9', '0813212387642', 1, 'Basic Cut', 45, '2026-06-20', 22, '18:51', 'Online', 'Menunggu', 3, '2026-06-20 11:28:09', '2026-06-20 11:29:33'),
	(10, '01KVJEG4W6W3ZSRSTW29VT3P2X', 'XYZ-0010', 10, 'joko', '084132165498', 4, 'Full Treatment', 75, '2026-06-20', 7, '19:08', 'Walk-in', 'Menunggu', NULL, '2026-06-20 12:01:15', '2026-06-20 12:01:15');

-- Dumping structure for table db_barbershop.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.cache: ~0 rows (approximately)

-- Dumping structure for table db_barbershop.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.cache_locks: ~0 rows (approximately)

-- Dumping structure for table db_barbershop.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table db_barbershop.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.jobs: ~0 rows (approximately)

-- Dumping structure for table db_barbershop.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.job_batches: ~0 rows (approximately)

-- Dumping structure for table db_barbershop.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.migrations: ~1 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_01_01_000100_create_services_table', 1),
	(5, '2026_01_01_000200_create_barber_capacities_table', 1),
	(6, '2026_01_01_000300_create_queue_counters_table', 1),
	(7, '2026_01_01_000400_create_bookings_table', 1),
	(8, '2026_06_20_134442_create_barber_logs_table', 2);

-- Dumping structure for table db_barbershop.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table db_barbershop.queue_counters
CREATE TABLE IF NOT EXISTS `queue_counters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `last_number` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `queue_counters_date_unique` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.queue_counters: ~1 rows (approximately)
INSERT INTO `queue_counters` (`id`, `date`, `last_number`, `created_at`, `updated_at`) VALUES
	(1, '2026-06-20', 10, '2026-06-20 10:21:13', '2026-06-20 12:01:15');

-- Dumping structure for table db_barbershop.services
CREATE TABLE IF NOT EXISTS `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration` smallint unsigned NOT NULL,
  `price` bigint unsigned NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `services_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.services: ~4 rows (approximately)
INSERT INTO `services` (`id`, `name`, `description`, `duration`, `price`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Basic Cut', 'Potong rambut klasik rapi dan cepat.', 45, 35000, 'aktif', '2026-06-20 05:08:10', '2026-06-20 05:08:10'),
	(2, 'Haircut + Wash', 'Potong rambut plus cuci rambut menyegarkan.', 60, 50000, 'aktif', '2026-06-20 05:08:10', '2026-06-20 05:08:10'),
	(3, 'Haircut + Beard', 'Potong rambut dengan perapian jenggot premium.', 60, 60000, 'aktif', '2026-06-20 05:08:10', '2026-06-20 05:08:10'),
	(4, 'Full Treatment', 'Paket lengkap potong, cuci, jenggot, dan styling.', 75, 90000, 'aktif', '2026-06-20 05:08:10', '2026-06-20 05:08:10');

-- Dumping structure for table db_barbershop.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.sessions: ~2 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('BVX6B8hmfn6q8FHnM5yOiNSZD2lGjRPMXCDygO6v', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:152.0) Gecko/20100101 Firefox/152.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidnEyWDJyUjBDcWJ1Z2R2eEZqZEthaVJCT0IzZ2dUZFdBT2tITWF1MyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9hbnRyZWFuIjtzOjU6InJvdXRlIjtzOjEyOiJhZG1pbi5xdWV1ZXMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1781956875),
	('xYEa6laweoxI3m20rGbhk5dwYhaIIGrf4DdxVbxD', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.125.1 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNnBmT2FhVGdIM2VlWFJjUkhjbFRjWFpWTnFvYm9IZVVENGlLQk5ObCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib29raW5nIjtzOjU6InJvdXRlIjtzOjE0OiJib29raW5nLmNyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1781956854);

-- Dumping structure for table db_barbershop.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pelanggan',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_phone_index` (`phone`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table db_barbershop.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `phone`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin XYZ', '081200000000', 'admin@xyzbarbershop.com', NULL, '$2y$12$LnZam7raaT6iQD63.yKErOpGw3eQ9OhxcIWho1rd2.VklsU4G33ze', 'admin', NULL, '2026-06-20 05:08:10', '2026-06-20 05:08:10'),
	(2, 'Budi Santoso', '081234567890', 'pelanggan@mail.com', NULL, '$2y$12$cx/G8cK1t1Nwm3FJ9ZIVOuu0Z726G4wJp6reD1HQ2jFyQ4DvOss5O', 'pelanggan', NULL, '2026-06-20 05:08:10', '2026-06-20 05:08:10'),
	(3, 'HensenRares', '0813212387642', 'hensenyohanes@gmail.com', NULL, '$2y$12$l82AQGcAyZ7SpzwvwoaaWuIs5CxZYDJjIWf6NqyDEWzRoPDXVjhwa', 'pelanggan', NULL, '2026-06-20 05:16:49', '2026-06-20 05:16:49');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
