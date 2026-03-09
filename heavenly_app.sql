-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 09, 2026 at 04:13 AM
-- Server version: 5.7.44
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `heavenly_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `application_fees`
--

CREATE TABLE `application_fees` (
  `application_fee_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `fee_reference` varchar(50) NOT NULL,
  `fee_date` date NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `income_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `vat_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) DEFAULT 'Cash',
  `notes` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `asset_number` varchar(20) NOT NULL,
  `category` varchar(50) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text,
  `serial_number` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `assigned_user` varchar(100) DEFAULT NULL,
  `acquisition_date` date NOT NULL,
  `acquisition_value` decimal(15,2) NOT NULL,
  `supplier` varchar(200) DEFAULT NULL,
  `additions` decimal(15,2) DEFAULT '0.00',
  `lifespan_years` int(11) NOT NULL,
  `depreciation_rate` decimal(5,2) NOT NULL,
  `asset_condition` varchar(1000) DEFAULT NULL,
  `monthly_depreciation` decimal(15,2) DEFAULT NULL,
  `daily_depreciation` decimal(15,2) DEFAULT NULL,
  `accumulated_depreciation` decimal(15,2) DEFAULT '0.00',
  `reporting_date` date DEFAULT NULL,
  `disposal_date` date DEFAULT NULL,
  `disposal_value` decimal(15,2) DEFAULT NULL,
  `disposal_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `chart_of_accounts`
--

CREATE TABLE `chart_of_accounts` (
  `account_id` int(11) NOT NULL,
  `class` varchar(50) NOT NULL,
  `account_code` varchar(10) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_type` varchar(50) DEFAULT NULL,
  `sub_type` varchar(50) DEFAULT NULL,
  `normal_balance` varchar(10) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birth_place` text COLLATE utf8mb4_unicode_ci,
  `id_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `occupation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_unicode_ci DEFAULT 'Male',
  `date_of_birth` date DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT 'Capital Bridge Finance',
  `father_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mother_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marriage_type` enum('Single','Married','Divorced') COLLATE utf8mb4_unicode_ci DEFAULT 'Single',
  `spouse` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_occupation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spouse_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `location` text COLLATE utf8mb4_unicode_ci,
  `project` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_location` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caution_location` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loan_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Approved',
  `doc_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'National ID Path',
  `doc_contract` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Work Contract Path',
  `doc_statement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Bank Statement Path',
  `doc_payslip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Payslip Path',
  `doc_marital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Marital Status Cert Path',
  `doc_rdb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'RDB Certificate Path',
  `has_guarantor` enum('Yes','No') COLLATE utf8mb4_unicode_ci DEFAULT 'No',
  `guarantor_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_occupation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tin_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `risk_rating` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_balance` decimal(15,2) DEFAULT '0.00',
  `total_loans` decimal(15,2) DEFAULT '0.00',
  `total_paid` decimal(15,2) DEFAULT '0.00',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `correction_fields` text COLLATE utf8mb4_unicode_ci,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `client_resubmitted` tinyint(1) DEFAULT '0',
  `resubmitted_fields` text COLLATE utf8mb4_unicode_ci,
  `total_disbursed` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_outstanding` decimal(15,2) NOT NULL DEFAULT '0.00',
  `last_loan_date` date DEFAULT NULL,
  `record_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `customer_code`, `customer_name`, `birth_place`, `id_number`, `account_number`, `occupation`, `gender`, `date_of_birth`, `phone`, `email`, `organization`, `father_name`, `mother_name`, `marriage_type`, `spouse`, `spouse_id`, `spouse_occupation`, `spouse_phone`, `address`, `location`, `project`, `project_location`, `caution_location`, `loan_type`, `created_by`, `created_at`, `updated_at`, `is_active`, `status`, `doc_id`, `doc_contract`, `doc_statement`, `doc_payslip`, `doc_marital`, `doc_rdb`, `has_guarantor`, `guarantor_name`, `guarantor_id`, `guarantor_phone`, `guarantor_occupation`, `tin_number`, `risk_rating`, `current_balance`, `total_loans`, `total_paid`, `rejection_reason`, `correction_fields`, `admin_note`, `client_resubmitted`, `resubmitted_fields`, `total_disbursed`, `total_outstanding`, `last_loan_date`, `record_date`) VALUES
(26, 'C001', 'tes', 'Nyamiyaga, Muyira, Nyanza, South', '0000000000000000', 'n/a', 'n/a', 'Male', '2022-03-08', 'n/a', 'test@gmail.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Single', '', '', '', '', 'na', '', 'n/a', '', 'n/a', 'Personal Loan', 'admin', '2026-02-25 09:03:04', '2026-03-03 05:32:44', 1, '0', 'uploads/documents/doc_id_1772031784_72.jpeg', 'uploads/documents/doc_contract_1772031784_59.jpeg', 'uploads/documents/doc_statement_1772031784_44.jpeg', 'uploads/documents/doc_payslip_1772031784_25.jpeg', 'uploads/documents/doc_marital_1772031784_82.jpeg', 'uploads/documents/doc_rdb_1772031784_90.jpeg', 'No', '', '', '', '', NULL, NULL, 19894900.00, 19894900.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-02-25 09:03:04'),
(136, 'C002', 'UWITONZE Sandrine', 'Kagenge, Mayange, Bugesera, East', '1199770021250130', 'N/A', 'Entrepreneur', 'Female', '1997-01-01', '0 788880643', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Single', '', '', '', '', 'n/a', 'Kagenge, Mayange, Bugesera, East', 'Business Loan', 'n/a', 'n/a', 'Business Loan', 'admin', '2026-03-05 03:33:00', '2026-03-05 03:33:00', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(137, 'C003', 'KARINGANIRE HUBERT DIAMA', 'Akinyambo, Muyumbu, Rwamagana, East', '1198280202289190', 'N/A', 'entrepreneur', 'Male', '1982-01-01', '0 788 313 049', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Married', '', '', '', '', 'n/a', 'Akinyambo, Muyumbu, Rwamagana, East', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 03:38:20', '2026-03-05 03:38:20', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(138, 'C004', 'IZABARERA THEONESTE', 'Kigali', '1198280015506170', '', 'entrepreneur', 'Male', '1982-02-01', '0 788 630 293', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Divorced', '', '', '', '', 'n/a', 'Kagugu, Kinyinya, Gasabo, Kigali', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 04:00:28', '2026-03-05 04:00:28', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(139, 'C005', 'HAKIZIMAANA MUSSA', '', '198280184972051', 'N/A', 'Entrepreneur', 'Male', '1981-02-01', '0 788 356 146', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Single', '', '', '', '', 'n/a', 'Bisenga, Rusororo, Gasabo, Kigali', 'n/a', 'n/a', 'n/a', 'Business Loan', 'admin', '2026-03-05 04:08:01', '2026-03-05 04:08:01', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(140, 'C006', 'NKURUNZIZA STEVEN', '', '1198480048627070', 'N/A', 'entrepreneur', 'Male', '1984-01-01', '0 788 817 578', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Single', '', '', '', '', 'n/a', 'Kayonza, Mukarange, Kayonza, East', 'Business Loan', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 04:14:20', '2026-03-05 04:14:20', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(141, 'C007', 'IRANKUNDA PLACIDE', 'Gako, Masaka, Kicukiro, Kigali', '1199080012467050', 'N/A', 'entrepreneur', 'Male', '1990-01-01', '0 788 434 852', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Single', '', '', '', '', 'n/a', 'East', 'n/a', 'n/a', 'n/a', 'Business Loan', 'admin', '2026-03-05 04:19:47', '2026-03-05 04:19:47', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(142, 'C008', 'MUGABO EMMY', 'Rudashya, Ndera, Gasabo, Kigali', '1199380009456180', '', 'entrepreneur', 'Male', '1993-01-01', '0 788 410 188', 'na@na.com', 'Capital Bridge Finance', '', '', 'Single', '', '', '', '', 'n/a', 'East', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 04:22:50', '2026-03-05 04:22:50', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(143, 'C009', 'RURANGIRWA BENARD', '', '1197280010128120', 'N/A', 'Entrepreneur', 'Male', '1972-01-01', '0 788 229 278', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Married', '', '', '', '', 'n/a', 'Mbabe, Masaka, Kicukiro, Kigali', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 04:30:53', '2026-03-05 04:30:53', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(144, 'C010', 'UMUTESI ANETH', '', '1199570197062010', 'N/A', 'entrepreneur', 'Female', '1995-01-01', '0 793 710 610', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Single', '', '', '', '', 'n/a', 'Murehe, Muyumbu, Rwamagana, East', '', '', '', '', 'admin', '2026-03-05 04:38:41', '2026-03-05 04:38:41', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(145, 'C011', 'GATARAYIHA MARCELINE', 'Rubirizi, Kanombe, Kicukiro, Kigali', '1197370009982070', 'N/A', 'entrepreneur', 'Female', '1973-01-01', '0 788 778 940', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'n/a', 'Married', '', '', '', '', 'n/a', 'East', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 04:44:22', '2026-03-05 04:44:22', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(146, 'C012', 'BATAMURIZA ESTHER', '', '1197270090474020', 'N/A', 'Entrepreneur', 'Female', '1972-01-01', '0 735 030 009', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Married', '', '', '', '', 'n/a', 'Murehe, Muyumbu, Rwamagana, East', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 04:51:22', '2026-03-05 04:51:22', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(147, 'C013', 'KABEGA BEATHA', 'Kanombe, Kicukiro, Kigali', '1195970004611030', '', 'entrepreneur', 'Female', '1959-01-01', '0 788918610', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Married', '', '', '', '', 'n/a', 'Karama, Kanombe, Kicukiro, Kigali', '', '', '', '', 'admin', '2026-03-05 04:56:40', '2026-03-05 04:56:40', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(148, 'C014', 'IZABARERA THEONESTE', '', '1198280015506170', '', 'Entrepreneur', 'Male', '1982-01-01', '0 788 630 293', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Married', '', '', '', '', 'n/a', 'Kagugu, Kinyinya, Gasabo, Kigali', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 05:00:12', '2026-03-05 05:00:12', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(149, 'C015', 'MUSABYIMANA EMMACULEE', '', '1194370006088120', '', 'entrepreneur', 'Female', '1943-01-01', '0 781 100 840', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Married', '', '', '', '', 'n/a', 'East', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 05:03:26', '2026-03-05 05:03:26', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(150, 'C016', 'UWURUKUNDO JEAN LEONARD', '', '1197780080620090', 'N/A', 'entrepreneur', 'Male', NULL, '0788549798', 'na@na.com', 'Capital Bridge Finance', '', '', 'Single', '', '', '', '', 'n/a', 'Gako, Masaka, Kicukiro, Kigali', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 05:05:21', '2026-03-05 05:05:21', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(151, 'C017', 'KABAREBE GEOFREY', '', '1199180153588040', 'N/A', 'entrepreneur', 'Male', '1991-01-01', '0 782 442 669', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Married', '', '', '', '', 'n/a', 'Gakamba, Mayange, Bugesera, East', 'n/a', 'n/a', 'n/a', 'Business Loan', 'admin', '2026-03-05 05:09:30', '2026-03-05 05:09:30', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(152, 'C018', 'NIYOTWAGIRA EGIDE', '', '1199480103500240', 'N/A', 'entrepreneur', 'Male', '1994-01-01', '0 784 018 822', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Married', '', '', '', '', 'n/a', 'Rutonde, Shyorongi, Rulindo, North', '', '', '', 'Business Loan', 'admin', '2026-03-05 05:15:07', '2026-03-05 05:15:07', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(153, 'C019', 'MUHOZA KEVIN', '', '1198680009276080', 'N/A', 'entrepreneur', 'Male', '1998-01-01', '0 788 824 166', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Married', '', '', '', '', 'n/a', 'Musave, Bumbogo, Gasabo, Kigali', 'n/a', 'n/a', 'n/a', 'Business Loan', 'admin', '2026-03-05 05:18:29', '2026-03-05 05:18:29', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(154, 'C020', 'KAZANIRWA VENERANDA', 'Musezero, Gisozi, Gasabo, Kigali', '1195770002289010', 'N/A', 'entrepreneur', 'Female', NULL, '0\'788249097', 'na@na.com', 'Capital Bridge Finance', 'N/A', 'N/A', 'Married', '', '', '', '', 'n/a', 'East', 'n/a', 'n/a', '', 'Business Loan', 'admin', '2026-03-05 05:21:00', '2026-03-05 05:21:00', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00'),
(155, 'C021', 'TUMWEBAZE ELIJAH', '', '1198880011934040', 'N/A', 'Entrepreneur', 'Male', '1998-01-01', '0 788 343 430', 'na@na.com', 'Capital Bridge Finance', 'n/a', 'n/a', 'Single', '', '', '', '', 'n/a', 'Kayonza, Mukarange, Kayonza, East', '', '', '', 'Business Loan', 'admin', '2026-03-05 05:26:36', '2026-03-05 05:26:36', 1, 'Approved', NULL, NULL, NULL, NULL, NULL, NULL, 'No', '', '', '', '', NULL, NULL, 0.00, 0.00, 0.00, NULL, NULL, NULL, 0, NULL, 0.00, 0.00, NULL, '2026-03-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `expense_reference` varchar(50) NOT NULL,
  `expense_date` date NOT NULL,
  `account_code` varchar(20) NOT NULL,
  `account_name` varchar(100) NOT NULL,
  `expense_amount` decimal(15,2) NOT NULL,
  `payment_type` enum('cash','bank') DEFAULT 'bank',
  `description` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `expense_reference`, `expense_date`, `account_code`, `account_name`, `expense_amount`, `payment_type`, `description`, `created_by`, `created_at`) VALUES
(28, 'EXP-20260204-152322', '2026-02-04', '5101', 'Salaries & Wages', 300000.00, 'bank', '', 1, '2026-02-04 14:23:22'),
(29, 'EXP-20260225-123912', '2026-02-25', '5101', 'Salaries & Wages', 500000.00, 'bank', '', 1, '2026-02-25 12:39:12'),
(30, 'EXP-20260227-104941', '2026-02-27', '5101', 'Salaries & Wages', 250000.00, 'bank', '', 1, '2026-02-27 10:49:41'),
(31, 'EXP-20260227-105026', '2026-02-27', '5101', 'Salaries & Wages', 270000.00, 'bank', '', 1, '2026-02-27 10:50:26'),
(32, 'EXP-20260227-105252', '2026-02-27', '5101', 'Salaries & Wages', 270000.00, 'bank', '', 1, '2026-02-27 10:52:52'),
(33, 'EXP-20260227-105310', '2026-02-27', '5102', 'Staff Training & Development', 280000.00, 'bank', '', 1, '2026-02-27 10:53:10'),
(34, 'EXP-20260227-105330', '2026-02-27', '5101', 'Salaries & Wages', 280000.00, 'bank', '', 1, '2026-02-27 10:53:30'),
(35, 'EXP-20260227-125138', '2026-02-27', '5104', 'Rent', 300000.00, 'bank', '', 1, '2026-02-27 12:51:38'),
(36, 'EXP-20260227-125158', '2026-02-27', '5203', 'Audit & Accounting Services', 300000.00, 'bank', '', 1, '2026-02-27 12:51:58'),
(37, 'EXP-20260227-125240', '2026-02-27', '5270', 'Marketing & Advertising Expense', 300000.00, 'bank', '', 1, '2026-02-27 12:52:40'),
(38, 'EXP-20260227-125317', '2026-02-27', '5101', 'Salaries & Wages', 300000.00, 'bank', '', 1, '2026-02-27 12:53:17'),
(39, 'EXP-20260227-125343', '2026-02-27', '5101', 'Salaries & Wages', 260000.00, 'bank', '', 1, '2026-02-27 12:53:43'),
(40, 'EXP-20260302-074946', '2026-03-02', '5101', 'Salaries & Wages', 220000.00, 'bank', '', 1, '2026-03-02 07:49:46'),
(41, 'EXP-20260302-100417', '2026-03-02', '5101', 'Salaries & Wages', 220000.00, 'bank', '', 1, '2026-03-02 10:04:17'),
(42, 'EXP-20260302-100450', '2026-03-02', '5101', 'Salaries & Wages', 200000.00, 'bank', '', 1, '2026-03-02 10:04:50'),
(43, 'EXP-20260302-100530', '2026-03-02', '5101', 'Salaries & Wages', 180000.00, 'bank', '', 1, '2026-03-02 10:05:30'),
(44, 'EXP-20260302-101912', '2026-03-02', '5101', 'Salaries & Wages', 190000.00, 'bank', '', 1, '2026-03-02 10:19:12'),
(45, 'EXP-20260302-101958', '2026-03-02', '5101', 'Salaries & Wages', 250000.00, 'bank', '', 1, '2026-03-02 10:19:58'),
(46, 'EXP-20260302-102014', '2026-03-02', '5101', 'Salaries & Wages', 260000.00, 'bank', '', 1, '2026-03-02 10:20:14');

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

CREATE TABLE `ledger` (
  `ledger_id` int(11) NOT NULL,
  `transaction_date` date NOT NULL,
  `class` varchar(50) NOT NULL,
  `account_code` varchar(10) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `particular` varchar(255) NOT NULL,
  `voucher_number` varchar(50) DEFAULT NULL,
  `narration` text,
  `beginning_balance` decimal(15,2) DEFAULT '0.00',
  `debit_amount` decimal(15,2) DEFAULT '0.00',
  `credit_amount` decimal(15,2) DEFAULT '0.00',
  `movement` decimal(15,2) DEFAULT '0.00',
  `ending_balance` decimal(15,2) DEFAULT '0.00',
  `reference_type` varchar(50) DEFAULT NULL,
  `reference_id` varchar(50) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sequence_number` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ledger`
--

INSERT INTO `ledger` (`ledger_id`, `transaction_date`, `class`, `account_code`, `account_name`, `particular`, `voucher_number`, `narration`, `beginning_balance`, `debit_amount`, `credit_amount`, `movement`, `ending_balance`, `reference_type`, `reference_id`, `created_by`, `created_at`, `updated_at`, `sequence_number`) VALUES
(14642, '2026-03-03', 'Assets', '1201', 'Loans to Customers', 'Principal Repayment', 'C002', 'Loan payment - Instalment #1 - Loan #LN-20260303-105525 - Reference: ', 0.00, 0.00, 127590.00, -127590.00, -127590.00, 'loan_payment', '1100', 1, '2026-03-03 11:12:17', '2026-03-03 11:12:17', 0),
(14649, '2026-03-03', 'Assets', '1102', 'Bank Account', 'Loan Payment Received', 'C002', 'Loan payment - Instalment #2 - Loan #LN-20260303-105525 - Reference: ', 0.00, 387590.00, 0.00, 387590.00, 387590.00, 'loan_payment', '1101', 1, '2026-03-03 11:15:15', '2026-03-03 11:15:15', 0),
(14650, '2026-03-03', 'Assets', '1201', 'Loans to Customers', 'Principal Repayment', 'C002', 'Loan payment - Instalment #2 - Loan #LN-20260303-105525 - Reference: ', -127590.00, 0.00, 160350.00, -160350.00, -287940.00, 'loan_payment', '1101', 1, '2026-03-03 11:15:15', '2026-03-03 11:15:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `loan_application_fees`
--

CREATE TABLE `loan_application_fees` (
  `id` int(11) NOT NULL,
  `loan_number` varchar(50) DEFAULT NULL,
  `applicant_name` varchar(255) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `fee_amount` decimal(15,2) DEFAULT NULL,
  `payment_status` enum('Paid','Pending','Refunded') DEFAULT 'Pending',
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_instalments`
--

CREATE TABLE `loan_instalments` (
  `instalment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_number` varchar(50) NOT NULL,
  `instalment_number` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Principal balance at start of period',
  `closing_balance` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Principal balance at end of period',
  `principal_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Principal portion of payment',
  `interest_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Interest portion of payment',
  `management_fee` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Management fee for this instalment',
  `total_payment` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Total payment required (principal + interest + mgmt fee)',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Amount actually paid',
  `principal_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `interest_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `management_fee_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_remaining` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Unpaid portion of this instalment',
  `days_overdue` int(11) NOT NULL DEFAULT '0',
  `penalty_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `penalty_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('Pending','Partially Paid','Fully Paid','Overdue') NOT NULL DEFAULT 'Pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `overdue_ledger_recorded` tinyint(1) DEFAULT '0',
  `ninety_day_recorded` tinyint(1) DEFAULT '0',
  `monitoring_fee_net` decimal(15,2) DEFAULT '0.00',
  `monitoring_fee_vat` decimal(15,2) DEFAULT '0.00',
  `monitoring_fee_total` decimal(15,2) DEFAULT '0.00',
  `provision_calculated` tinyint(1) DEFAULT '0',
  `provision_amount` decimal(15,2) DEFAULT '0.00',
  `provision_date` date DEFAULT NULL,
  `suspension_recorded` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payments`
--

CREATE TABLE `loan_payments` (
  `payment_id` int(11) NOT NULL,
  `loan_instalment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `month_paid` varchar(200) DEFAULT NULL,
  `payment_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `beginning_balance` decimal(15,2) DEFAULT '0.00',
  `payment_amount` decimal(15,2) NOT NULL,
  `interest_amount` decimal(15,2) DEFAULT '0.00',
  `principal_amount` decimal(15,2) DEFAULT '0.00',
  `monitoring_fee` decimal(15,2) DEFAULT '0.00',
  `days_overdue` int(11) DEFAULT '0',
  `penalties` decimal(15,2) DEFAULT '0.00',
  `final_payment` decimal(15,2) DEFAULT '0.00',
  `payment_method` varchar(50) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `adjustment_id` int(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payment_adjustments`
--

CREATE TABLE `loan_payment_adjustments` (
  `adjustment_id` bigint(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `instalment_id` int(11) NOT NULL,
  `instalment_number` int(11) NOT NULL,
  `record_date` date NOT NULL,
  `adjustment_amount` decimal(15,2) NOT NULL,
  `adjustment_type` varchar(100) NOT NULL DEFAULT 'ADJUSTMENT',
  `status` varchar(200) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_payment_variance`
--

CREATE TABLE `loan_payment_variance` (
  `variance_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instalment_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `expected_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `actual_amount_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `variance_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `variance_type` enum('overpayment','underpayment','prepayment','exact_payment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `principal_expected` decimal(15,2) DEFAULT '0.00',
  `principal_paid` decimal(15,2) DEFAULT '0.00',
  `principal_variance` decimal(15,2) DEFAULT '0.00',
  `interest_expected` decimal(15,2) DEFAULT '0.00',
  `interest_paid` decimal(15,2) DEFAULT '0.00',
  `interest_variance` decimal(15,2) DEFAULT '0.00',
  `monitoring_fee_expected` decimal(15,2) DEFAULT '0.00',
  `monitoring_fee_paid` decimal(15,2) DEFAULT '0.00',
  `monitoring_fee_variance` decimal(15,2) DEFAULT '0.00',
  `penalty_expected` decimal(15,2) DEFAULT '0.00',
  `penalty_paid` decimal(15,2) DEFAULT '0.00',
  `penalty_variance` decimal(15,2) DEFAULT '0.00',
  `unallocated_balance` decimal(15,2) DEFAULT '0.00',
  `allocated_balance` decimal(15,2) DEFAULT '0.00',
  `is_prepayment` tinyint(1) DEFAULT '0',
  `instalments_covered` int(11) DEFAULT '0',
  `prepayment_discount` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','allocated','partially_allocated','refunded','carried_forward') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `allocation_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_portfolio`
--

CREATE TABLE `loan_portfolio` (
  `loan_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `loan_number` varchar(50) NOT NULL,
  `loan_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Principal amount given to customer',
  `management_fee_rate` decimal(5,2) NOT NULL DEFAULT '5.50' COMMENT 'Management fee percentage (5.5%)',
  `management_fee_amount` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Management fee (5.5% of loan amount)',
  `total_disbursed` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Loan amount + Management fee',
  `interest_rate` decimal(5,2) NOT NULL COMMENT 'Monthly interest rate percentage',
  `number_of_instalments` int(11) NOT NULL,
  `disbursement_date` date NOT NULL,
  `maturity_date` date NOT NULL,
  `total_interest` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Sum of all interest payments',
  `total_management_fees` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Total management fees across all instalments',
  `total_payment` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Total to be paid (principal + interest + mgmt fees)',
  `monthly_payment` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Fixed monthly payment amount (after 1st instalment)',
  `principal_outstanding` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Remaining principal balance',
  `interest_outstanding` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Remaining interest to be paid',
  `total_outstanding` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Total remaining balance',
  `total_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_principal_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_interest_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_management_fees_paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `accrued_interest` decimal(15,2) NOT NULL DEFAULT '0.00',
  `accrued_days` int(11) NOT NULL DEFAULT '0',
  `accrued_management_fees` decimal(15,2) NOT NULL DEFAULT '0.00',
  `deferred_management_fees` decimal(15,2) NOT NULL DEFAULT '0.00',
  `days_overdue` int(11) NOT NULL DEFAULT '0',
  `penalties` decimal(15,2) NOT NULL DEFAULT '0.00',
  `cash_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `bank_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `collateral_type` varchar(50) DEFAULT NULL,
  `collateral_description` text,
  `collateral_value` decimal(15,2) DEFAULT '0.00',
  `collateral_net_value` decimal(15,2) DEFAULT '0.00',
  `provisional_rate` decimal(5,2) NOT NULL DEFAULT '1.00',
  `general_provision` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_book_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `loan_status` varchar(100) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_provision_date` date DEFAULT NULL,
  `record_date` date DEFAULT NULL,
  `deduct_fee_from_disbursed` decimal(50,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_transactions`
--

CREATE TABLE `loan_transactions` (
  `transaction_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `loan_number` varchar(50) NOT NULL,
  `transaction_type` enum('Disbursement','Payment','Fee','Adjustment','Write-off','Recovery') NOT NULL,
  `transaction_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text,
  `reference_number` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','manager','user') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `role`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@example.com', 'admin', 'active', NULL, '2026-01-16 08:41:39', '2026-01-16 08:41:39'),
(2, 'manager', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'Finance Manager', 'manager@example.com', 'manager', 'active', NULL, '2026-01-16 08:41:39', '2026-01-16 08:41:39'),
(3, 'user', '$2y$10$E9xG2pKXF3X8h6MxLjLxNe8BVxGKxMxqxQxH8v9v5pKXq2v3VxHEi', 'Regular User', 'user@example.com', 'user', 'active', NULL, '2026-01-16 08:41:39', '2026-01-16 08:41:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_fees`
--
ALTER TABLE `application_fees`
  ADD PRIMARY KEY (`application_fee_id`),
  ADD UNIQUE KEY `fee_reference` (`fee_reference`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_fee_date` (`fee_date`),
  ADD KEY `idx_reference` (`fee_reference`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`),
  ADD UNIQUE KEY `asset_number` (`asset_number`);

--
-- Indexes for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `account_code` (`account_code`),
  ADD KEY `idx_account_code` (`account_code`),
  ADD KEY `idx_account_type` (`account_type`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `uq_customer_code` (`customer_code`),
  ADD KEY `idx_id_number` (`id_number`),
  ADD KEY `idx_customer_code` (`customer_code`),
  ADD KEY `idx_phone` (`phone`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD UNIQUE KEY `expense_reference` (`expense_reference`),
  ADD KEY `idx_expense_date` (`expense_date`),
  ADD KEY `idx_account_code` (`account_code`);

--
-- Indexes for table `ledger`
--
ALTER TABLE `ledger`
  ADD PRIMARY KEY (`ledger_id`),
  ADD KEY `idx_transaction_date` (`transaction_date`),
  ADD KEY `idx_account_code` (`account_code`),
  ADD KEY `idx_voucher_number` (`voucher_number`),
  ADD KEY `idx_reference` (`reference_type`,`reference_id`),
  ADD KEY `idx_ledger_sequence` (`account_code`,`transaction_date`,`sequence_number`);

--
-- Indexes for table `loan_application_fees`
--
ALTER TABLE `loan_application_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_instalments`
--
ALTER TABLE `loan_instalments`
  ADD PRIMARY KEY (`instalment_id`),
  ADD KEY `loan_id` (`loan_id`),
  ADD KEY `loan_number` (`loan_number`),
  ADD KEY `instalment_number` (`instalment_number`),
  ADD KEY `due_date` (`due_date`),
  ADD KEY `status` (`status`),
  ADD KEY `idx_instalment_dates` (`due_date`,`payment_date`),
  ADD KEY `idx_instalment_status` (`loan_id`,`status`);

--
-- Indexes for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `loan_instalment_id` (`loan_instalment_id`),
  ADD KEY `idx_loan_id` (`loan_id`),
  ADD KEY `idx_payment_date` (`payment_date`);

--
-- Indexes for table `loan_payment_adjustments`
--
ALTER TABLE `loan_payment_adjustments`
  ADD PRIMARY KEY (`adjustment_id`),
  ADD KEY `fk_loan` (`loan_id`),
  ADD KEY `idx_customer_loan` (`customer_id`,`loan_id`),
  ADD KEY `idx_instalment` (`instalment_id`),
  ADD KEY `idx_record_date` (`record_date`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `loan_payment_variance`
--
ALTER TABLE `loan_payment_variance`
  ADD PRIMARY KEY (`variance_id`),
  ADD KEY `idx_payment_id` (`payment_id`),
  ADD KEY `idx_loan_id` (`loan_id`),
  ADD KEY `idx_instalment_id` (`instalment_id`),
  ADD KEY `idx_customer_id` (`customer_id`),
  ADD KEY `idx_variance_type` (`variance_type`),
  ADD KEY `idx_unallocated_balance` (`unallocated_balance`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `loan_portfolio`
--
ALTER TABLE `loan_portfolio`
  ADD PRIMARY KEY (`loan_id`),
  ADD UNIQUE KEY `loan_number` (`loan_number`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `loan_status` (`loan_status`),
  ADD KEY `disbursement_date` (`disbursement_date`),
  ADD KEY `idx_loan_customer` (`customer_id`,`loan_status`),
  ADD KEY `idx_loan_dates` (`disbursement_date`,`maturity_date`);

--
-- Indexes for table `loan_transactions`
--
ALTER TABLE `loan_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `idx_loan_id` (`loan_id`),
  ADD KEY `idx_transaction_date` (`transaction_date`),
  ADD KEY `idx_transaction_type` (`transaction_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `application_fees`
--
ALTER TABLE `application_fees`
  MODIFY `application_fee_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `chart_of_accounts`
--
ALTER TABLE `chart_of_accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `ledger`
--
ALTER TABLE `ledger`
  MODIFY `ledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14653;

--
-- AUTO_INCREMENT for table `loan_application_fees`
--
ALTER TABLE `loan_application_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_instalments`
--
ALTER TABLE `loan_instalments`
  MODIFY `instalment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1111;

--
-- AUTO_INCREMENT for table `loan_payments`
--
ALTER TABLE `loan_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payment_adjustments`
--
ALTER TABLE `loan_payment_adjustments`
  MODIFY `adjustment_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_payment_variance`
--
ALTER TABLE `loan_payment_variance`
  MODIFY `variance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_portfolio`
--
ALTER TABLE `loan_portfolio`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=239;

--
-- AUTO_INCREMENT for table `loan_transactions`
--
ALTER TABLE `loan_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=594;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan_instalments`
--
ALTER TABLE `loan_instalments`
  ADD CONSTRAINT `loan_instalments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan_portfolio` (`loan_id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_payments`
--
ALTER TABLE `loan_payments`
  ADD CONSTRAINT `loan_payments_ibfk_1` FOREIGN KEY (`loan_instalment_id`) REFERENCES `loan_instalments` (`instalment_id`),
  ADD CONSTRAINT `loan_payments_ibfk_2` FOREIGN KEY (`loan_id`) REFERENCES `loan_portfolio` (`loan_id`);

--
-- Constraints for table `loan_payment_adjustments`
--
ALTER TABLE `loan_payment_adjustments`
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_instalment` FOREIGN KEY (`instalment_id`) REFERENCES `loan_instalments` (`instalment_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_loan` FOREIGN KEY (`loan_id`) REFERENCES `loan_portfolio` (`loan_id`) ON UPDATE CASCADE;

--
-- Constraints for table `loan_transactions`
--
ALTER TABLE `loan_transactions`
  ADD CONSTRAINT `loan_transactions_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan_portfolio` (`loan_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
