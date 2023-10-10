-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 08, 2020 at 05:52 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wcc`
--

--
-- Dumping data for table `associate_commission_percentages`
--

INSERT INTO `associate_commission_percentages` (`id`, `customer_id`, `associate_id`, `customer_investment_id`, `commission_percent`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 4, 1, 2.00, 1, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(2, 2, 5, 1, 2.00, 1, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(3, 2, 6, 1, 2.00, 1, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(4, 2, 7, 1, 2.00, 1, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(5, 2, 8, 1, 2.00, 1, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(6, 2, 9, 1, 2.00, 1, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(7, 10, 9, 2, 1.00, 1, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(8, 10, 20, 2, 2.00, 1, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(9, 10, 21, 2, 3.00, 1, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(10, 10, 22, 2, 4.00, 1, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(11, 10, 23, 2, 5.00, 1, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(12, 10, 4, 2, 6.00, 1, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(13, 11, 22, 3, 2.00, 1, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(14, 11, 23, 3, 3.00, 1, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(15, 11, 4, 3, 1.00, 1, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(16, 11, 5, 3, 1.00, 1, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(17, 11, 6, 3, 2.00, 1, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(18, 11, 7, 3, 3.00, 1, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(19, 12, 4, 4, 5.00, 1, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(20, 12, 5, 4, 4.00, 1, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(21, 12, 6, 4, 6.00, 1, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(22, 12, 8, 4, 4.00, 1, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(23, 12, 9, 4, 4.00, 1, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(24, 12, 20, 4, 4.00, 1, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(25, 13, 4, 5, 2.00, 1, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(26, 13, 6, 5, 2.00, 1, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(27, 13, 5, 5, 2.00, 1, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(28, 13, 8, 5, 5.00, 1, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(29, 13, 9, 5, 3.00, 1, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(30, 13, 20, 5, 2.00, 1, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(31, 14, 23, 6, 2.00, 1, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(32, 14, 22, 6, 3.00, 1, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(33, 14, 21, 6, 4.00, 1, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(34, 14, 20, 6, 5.00, 1, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(35, 14, 9, 6, 6.00, 1, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(36, 14, 8, 6, 4.00, 1, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(37, 15, 4, 7, 2.00, 1, NULL, NULL, '2020-12-08 04:53:15', '2020-12-08 04:53:15', NULL),
(38, 15, 5, 7, 3.00, 1, NULL, NULL, '2020-12-08 04:53:15', '2020-12-08 04:53:15', NULL),
(39, 15, 6, 7, 4.00, 1, NULL, NULL, '2020-12-08 04:53:15', '2020-12-08 04:53:15', NULL),
(40, 15, 7, 7, 5.00, 1, NULL, NULL, '2020-12-08 04:53:15', '2020-12-08 04:53:15', NULL),
(41, 15, 8, 7, 6.00, 1, NULL, NULL, '2020-12-08 04:53:16', '2020-12-08 04:53:16', NULL),
(42, 15, 9, 7, 1.00, 1, NULL, NULL, '2020-12-08 04:53:16', '2020-12-08 04:53:16', NULL),
(43, 16, 8, 8, 2.00, 1, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(44, 16, 9, 8, 3.00, 1, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(45, 16, 20, 8, 4.00, 1, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(46, 16, 21, 8, 5.00, 1, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(47, 16, 22, 8, 5.00, 1, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(48, 16, 23, 8, 1.00, 1, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(49, 17, 4, 9, 1.00, 1, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL),
(50, 17, 5, 9, 2.00, 1, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL),
(51, 17, 7, 9, 3.00, 1, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL),
(52, 17, 8, 9, 4.00, 1, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL),
(53, 17, 9, 9, 5.00, 1, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL),
(54, 17, 20, 9, 4.00, 1, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL);

--
-- Dumping data for table `associate_details`
--

INSERT INTO `associate_details` (`id`, `associate_id`, `dob`, `sex`, `father_husband_wife`, `mother_name`, `image`, `address_one`, `address_two`, `city_id`, `state_id`, `country_id`, `zipcode`, `account_holder_name`, `bank_name`, `branch`, `account_number`, `ifsc_code`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 20, NULL, NULL, NULL, NULL, '1607401271.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-08 04:21:11', '2020-12-08 04:21:11', NULL),
(2, 21, NULL, NULL, NULL, NULL, '1607401459.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-08 04:24:19', '2020-12-08 04:24:19', NULL),
(3, 22, NULL, NULL, NULL, NULL, '1607401514.png', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-08 04:25:14', '2020-12-08 04:25:14', NULL),
(4, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-08 04:26:20', '2020-12-08 04:26:20', NULL);

--
-- Dumping data for table `customer_details`
--

INSERT INTO `customer_details` (`id`, `customer_id`, `dob`, `age`, `nationality`, `sex`, `father_husband_wife`, `address_one`, `address_two`, `city_id`, `state_id`, `country_id`, `zipcode`, `account_holder_name`, `bank_name`, `account_number`, `ifsc_code`, `nominee_name`, `nominee_age`, `nominee_dob`, `nominee_relation_with_applicable`, `nominee_sex`, `nominee_address_one`, `nominee_address_two`, `nominee_city_id`, `nominee_state_id`, `nominee_country_id`, `nominee_zipcode`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '2020-12-07', '25', 'indian', 'male', 'pkm', 'as', NULL, 4827, 38, 101, 222131, 'ashd', 'ashd', 'ashd', 'ashd', 'ashd', 'ashd', '2020-12-07', 'ashd', NULL, 'male', 'ashd', 4827, 38, 101, 222135, NULL, NULL, '2020-12-07 04:50:00', NULL, NULL),
(2, 10, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:05:37', '2020-12-07 13:05:37', NULL),
(3, 11, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:06:55', '2020-12-07 13:06:55', NULL),
(4, 12, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:07:39', '2020-12-07 13:07:39', NULL),
(5, 13, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:08:13', '2020-12-07 13:08:13', NULL),
(6, 14, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:08:45', '2020-12-07 13:08:45', NULL),
(7, 15, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:09:28', '2020-12-07 13:09:28', NULL),
(8, 16, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:10:14', '2020-12-07 13:10:14', NULL),
(9, 17, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-07 13:10:42', '2020-12-07 13:10:42', NULL),
(10, 18, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-08 04:18:51', '2020-12-08 04:18:51', NULL),
(11, 19, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1970-01-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2020-12-08 04:19:16', '2020-12-08 04:19:16', NULL);

--
-- Dumping data for table `customer_investments`
--

INSERT INTO `customer_investments` (`id`, `customer_id`, `amount`, `deposit_date`, `customer_interest_rate`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '100000.00', '2020-11-08', 12, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(2, 10, '100000.00', '2020-12-08', 12, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(3, 11, '1500000.00', '2020-10-31', 24, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(4, 12, '100000.00', '2020-12-01', 9, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(5, 13, '100000.00', '2020-11-30', 20, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(6, 14, '100000.00', '2020-11-19', 12, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(7, 15, '100000.00', '2020-11-01', 15, NULL, NULL, '2020-12-08 04:53:15', '2020-12-08 04:53:15', NULL),
(8, 16, '100000.00', '2020-10-14', 16, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(9, 17, '100000.00', '2020-09-17', 17, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL);

--
-- Dumping data for table `customer_transactions`
--

INSERT INTO `customer_transactions` (`id`, `customer_id`, `customer_investment_id`, `amount`, `cr_dr`, `payment_type`, `transaction_type`, `deposit_date`, `cheque_dd_date`, `bank_name`, `cheque_dd_number`, `respective_table_id`, `respective_table_name`, `remarks`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 1, '100000.00', 'cr', 'cash', 'deposit', '2020-11-08', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:29:03', '2020-12-08 04:29:03', NULL),
(2, 10, 2, '100000.00', 'cr', 'cash', 'deposit', '2020-12-08', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:33:52', '2020-12-08 04:33:52', NULL),
(3, 11, 3, '1500000.00', 'cr', 'cash', 'deposit', '2020-10-31', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:36:02', '2020-12-08 04:36:02', NULL),
(4, 12, 4, '100000.00', 'cr', 'cash', 'deposit', '2020-12-01', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:40:16', '2020-12-08 04:40:16', NULL),
(5, 13, 5, '100000.00', 'cr', 'cash', 'deposit', '2020-11-30', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:47:02', '2020-12-08 04:47:02', NULL),
(6, 14, 6, '100000.00', 'cr', 'cash', 'deposit', '2020-11-19', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:48:56', '2020-12-08 04:48:56', NULL),
(7, 15, 7, '100000.00', 'cr', 'cash', 'deposit', '2020-11-01', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:53:16', '2020-12-08 04:53:16', NULL),
(8, 16, 8, '100000.00', 'cr', 'cash', 'deposit', '2020-10-14', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 04:54:42', '2020-12-08 04:54:42', NULL),
(9, 2, 1, '50000.00', 'dr', 'cash', 'withdraw', '2020-11-30', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:00:21', '2020-12-08 05:00:21', NULL),
(10, 11, 3, '70000.00', 'dr', 'cash', 'withdraw', '2020-11-14', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:02:44', '2020-12-08 05:02:44', NULL),
(11, 11, 3, '50000.00', 'dr', 'cash', 'withdraw', '2020-11-30', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:03:14', '2020-12-08 05:03:14', NULL),
(12, 12, 4, '40000.00', 'dr', 'cash', 'withdraw', '2020-12-07', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:09:38', '2020-12-08 05:09:38', NULL),
(13, 12, 4, '30000.00', 'dr', 'cash', 'withdraw', '2020-12-08', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:09:54', '2020-12-08 05:09:54', NULL),
(14, 13, 5, '50000.00', 'dr', 'cash', 'withdraw', '2020-12-05', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:12:39', '2020-12-08 05:12:39', NULL),
(15, 14, 6, '45000.00', 'dr', 'cash', 'withdraw', '2020-11-25', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:14:53', '2020-12-08 05:14:53', NULL),
(16, 17, 9, '100000.00', 'cr', 'cash', 'deposit', '2020-09-17', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:24:45', '2020-12-08 05:24:45', NULL),
(17, 17, 9, '50000.00', 'dr', 'cash', 'withdraw', '2020-12-04', NULL, NULL, NULL, NULL, NULL, 'testing', 1, NULL, NULL, '2020-12-08 05:26:08', '2020-12-08 05:26:08', NULL);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `code`, `login_type`, `name`, `email`, `email_verified_at`, `mobile`, `password`, `remember_token`, `status`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', 'superadmin', 'admin', 'admin@gmail.com', NULL, '1234567895', '$2y$10$FAorW6fvOiJenS9KUb04qegg6SlU0o5Vk67hwLStHQpfHaocJtWc2', NULL, 0, 12, NULL, '2020-12-07 04:50:00', NULL, NULL),
(2, 'customer', 'customer', 'customer', 'customer@gmail.com', NULL, '1234567890', '$2y$10$aXvUg2mMy.huRhWSlnxcu.BtCDmIMjiCAle2ZHRtPgLTGQVaF3rES', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(3, 'Employee', 'employee', 'Employee', 'employee@gmail.com', NULL, '1234567800', '$2y$10$vmjQyc.ADGSFwrlacTC2A.k8ZJPJBhtK3uNi/Is8q2bb10SUHm67.', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(4, 'Associate', 'associate', 'Associate1', 'associ1ate@gmail.com', NULL, '1234567190', '$2y$10$8yvYzPxCj29n4z/vVLVKhON3xz4KK1fAx0nZl6F6O5Zv0YLer0exm', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(5, 'Associate', 'associate', 'Associate2', 'assoc2iate@gmail.com', NULL, '1234522790', '$2y$10$eDA5rU.dOE7QCAgBZYgq.eyBEJfV8rTFKxV42ZDHTXz0b4Uo7sfFK', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(6, 'Associate', 'associate', 'Associate3', 'associate3@gmail.com', NULL, '1234563790', '$2y$10$5n3Ynt6qwmGxiAiAfZyw.OhzzvKpY8T37jhw0kPW49qbuv/f8RmNi', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(7, 'Associate4', 'associate', 'Associate', 'associat4e@gmail.com', NULL, '1234564790', '$2y$10$V/fUw2cYWqrMhs/O24VFYuk98ThaDwJWTWPxZ6e1xIa8QGo7Yk75C', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(8, 'Associate', 'associate', 'Associate5', 'asso45ciate@gmail.com', NULL, '1234567750', '$2y$10$dYmP19jd6lUPbghsOUvEpe3jXLhMcp4ic5dlBQhzVbt1/hbQg/nve', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(9, 'Associate', 'associate', 'Associate6', 'associa45te@gmail.com', NULL, '1234567690', '$2y$10$ZLVoGLMWVnp4eCUNXg65b.DvY.GuLvM4QuBTjf2WupFjzy3D9dM6y', NULL, 0, 1, NULL, '2020-12-07 04:50:00', NULL, NULL),
(10, '#GDF4G65', 'customer', 'Customer1', 'customer1@gmail.com', NULL, '1236547845', NULL, NULL, 1, 1, NULL, '2020-12-07 13:05:37', '2020-12-07 13:05:37', NULL),
(11, '#FSD3F4FD4', 'customer', 'Customer2', 'customer2@gmail.com', NULL, '3219784152', NULL, NULL, 1, 1, NULL, '2020-12-07 13:06:55', '2020-12-07 13:06:55', NULL),
(12, '#F35F4354DF', 'customer', 'Customer3', 'customer3@gmail.com', NULL, '6451338543', NULL, NULL, 1, 1, NULL, '2020-12-07 13:07:39', '2020-12-07 13:07:39', NULL),
(13, '#FSD3F4FD5', 'customer', 'Customer5', 'customer5@gmail.com', NULL, '1236547846', NULL, NULL, 1, 1, NULL, '2020-12-07 13:08:13', '2020-12-07 13:08:13', NULL),
(14, '#FSD3F4FD4', 'customer', 'Customer4', 'customer4@gmail.com', NULL, '1236547844', NULL, NULL, 1, 1, NULL, '2020-12-07 13:08:45', '2020-12-07 13:08:45', NULL),
(15, '#FSD3F4FD6', 'customer', 'Customer6', 'customer6@gmail.com', NULL, '1236547847', NULL, NULL, 1, 1, NULL, '2020-12-07 13:09:28', '2020-12-07 13:09:28', NULL),
(16, '#FSD3F4FD7', 'customer', 'Customer7', 'customer7@gmail.com', NULL, '1236547877', NULL, NULL, 1, 1, NULL, '2020-12-07 13:10:14', '2020-12-07 13:10:14', NULL),
(17, '#FSD3F4FD8', 'customer', 'Customer8', 'customer8@gmail.com', NULL, '1236547848', NULL, NULL, 1, 1, NULL, '2020-12-07 13:10:42', '2020-12-07 13:10:42', NULL),
(18, '#FSD3F4FD9', 'customer', 'Customer9', 'customer9@gmail.com', NULL, '1236547849', NULL, NULL, 1, 1, NULL, '2020-12-08 04:18:51', '2020-12-08 04:18:51', NULL),
(19, '#FSD3F4F10', 'customer', 'Customer10', 'customer10@gmail.com', NULL, '1236547810', NULL, NULL, 1, 1, NULL, '2020-12-08 04:19:16', '2020-12-08 04:19:16', NULL),
(20, '#4ATT6T54', 'associate', 'Associate7', 'fhyhd@gh.in', NULL, '45647756634', NULL, NULL, 1, 1, NULL, '2020-12-08 04:21:11', '2020-12-08 04:21:11', NULL),
(21, '#GS5TY5YDY', 'associate', 'Associate8', 'associate8@gmail.com', NULL, '1236547887', NULL, NULL, 1, 1, NULL, '2020-12-08 04:24:19', '2020-12-08 04:24:19', NULL),
(22, '#GS5TY5YF9', 'associate', 'Associate9', 'associate9@gmail.com', NULL, '1236547890', NULL, NULL, 1, 1, NULL, '2020-12-08 04:25:14', '2020-12-08 04:25:14', NULL),
(23, '#GS5TY5YD10', 'associate', 'Associate10', 'associate10@gmail.com', NULL, '1236547100', NULL, NULL, 1, 1, NULL, '2020-12-08 04:26:20', '2020-12-08 04:26:20', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
