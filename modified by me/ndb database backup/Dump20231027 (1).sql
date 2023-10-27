-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: p3nlmysql83plsk.secureserver.net    Database: TestNDB
-- ------------------------------------------------------
-- Server version	5.7.26-29-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `attach_file`
--

DROP TABLE IF EXISTS `attach_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attach_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attach_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `file_name` text NOT NULL,
  `old_id` int(10) NOT NULL,
  `old_id2` int(11) NOT NULL,
  `old_id3` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3780 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `bank`
--

DROP TABLE IF EXISTS `bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_account_name` varchar(255) NOT NULL,
  `bank_account_number` varchar(255) NOT NULL,
  `bank_account_address` text NOT NULL,
  `opening_balance` decimal(15,2) DEFAULT NULL,
  `opening_balance_date` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `exact_bank_name` varchar(255) NOT NULL,
  `ifsc_code` varchar(255) NOT NULL,
  `bank_address` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `clear_due_amount`
--

DROP TABLE IF EXISTS `clear_due_amount`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clear_due_amount` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `cust_id` int(20) NOT NULL,
  `invoice_id` int(20) NOT NULL,
  `trans_id` int(20) NOT NULL,
  `pp_linkid_1` int(11) NOT NULL COMMENT 'customer payment id',
  `payment_plan_id` int(20) NOT NULL,
  `due_amount` int(20) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(50) NOT NULL COMMENT '(GST/TDS/Invoice)',
  `due_date` date NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `countryID` smallint(9) NOT NULL DEFAULT '0',
  `countryName` varchar(35) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`countryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer` (
  `cust_id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `current_address` text NOT NULL,
  `same_address` varchar(255) NOT NULL DEFAULT 'no',
  `permanent_address` text NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `client_contact_per` varchar(255) NOT NULL,
  `client_gst` varchar(50) NOT NULL,
  `project_manager` varchar(50) NOT NULL,
  `client_mobile` varchar(50) NOT NULL,
  `client_email` varchar(255) NOT NULL,
  `supply_gst_no` varchar(50) NOT NULL DEFAULT '',
  `project` varchar(255) NOT NULL,
  `project_type` varchar(50) NOT NULL,
  `type` varchar(255) NOT NULL,
  `bank_attachment` int(50) NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL,
  `opening_balance_date` int(11) NOT NULL,
  `customer_type` varchar(50) NOT NULL,
  `tenant_first_rent_agree_date` int(100) NOT NULL,
  `tenant_current_rent_agree_date` int(100) NOT NULL,
  `tenant_current_rent` int(100) NOT NULL,
  `tenant_nextrenawal_duedate` int(100) NOT NULL,
  `tenant_nextrenewal_rent` int(100) NOT NULL,
  `tenant_registered` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `userid_create` int(50) NOT NULL,
  `userid_update` int(50) NOT NULL,
  `short_name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `document_category`
--

DROP TABLE IF EXISTS `document_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `email_detail`
--

DROP TABLE IF EXISTS `email_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_to` text NOT NULL,
  `document_file` text NOT NULL,
  `send_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `file_import`
--

DROP TABLE IF EXISTS `file_import`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `file_import` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(50) NOT NULL,
  `user` int(11) NOT NULL,
  `file_path` text NOT NULL,
  `date` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `flag` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `goods_details`
--

DROP TABLE IF EXISTS `goods_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `goods_details` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `subdivision` int(11) NOT NULL,
  `gst_subdivision` int(11) NOT NULL,
  `link1_id` int(11) NOT NULL,
  `link2_id` int(11) NOT NULL,
  `link3_id` int(11) NOT NULL,
  `link4_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `hsn_code` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `gst_per` int(11) NOT NULL,
  `gst_amount` decimal(15,2) NOT NULL,
  `tds_per` int(11) NOT NULL,
  `tds_amount` decimal(15,2) NOT NULL,
  `tds_subdivision` int(11) NOT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `trans_type` int(11) NOT NULL,
  `trans_type_name` varchar(50) NOT NULL,
  `payment_date` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `invoice_type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1479 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gst_due_info`
--

DROP TABLE IF EXISTS `gst_due_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gst_due_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_plan_id` int(11) NOT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `pp_linkid_1` int(11) NOT NULL COMMENT 'customer payment id',
  `pp_linkid_2` int(11) NOT NULL COMMENT 'bank payment id',
  `amount` decimal(15,2) NOT NULL,
  `received_amount` decimal(15,2) NOT NULL,
  `due_date` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `description` text NOT NULL,
  `combine_description` text NOT NULL,
  `userid_create` int(50) NOT NULL,
  `userid_update` int(50) NOT NULL,
  `cert_file_name` text NOT NULL,
  `clear_due_flag` int(11) NOT NULL COMMENT 'clear_amount_entry then 1',
  `combind_payment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=469 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gst_list`
--

DROP TABLE IF EXISTS `gst_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gst_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gst` int(11) NOT NULL,
  `active` int(11) DEFAULT '1',
  `default_select` int(11) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `gst_subdivision`
--

DROP TABLE IF EXISTS `gst_subdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gst_subdivision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `default_select` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hsn_list`
--

DROP TABLE IF EXISTS `hsn_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hsn_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hsn_code` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `active` int(11) DEFAULT '1',
  `create_date` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_due_info`
--

DROP TABLE IF EXISTS `invoice_due_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_due_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_plan_id` int(11) NOT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `pp_linkid_1` int(11) NOT NULL COMMENT 'customer payment id',
  `pp_linkid_2` int(11) NOT NULL COMMENT 'bank payment id',
  `amount` decimal(15,2) NOT NULL,
  `received_amount` decimal(15,2) NOT NULL,
  `due_date` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `description` text NOT NULL,
  `combine_description` text NOT NULL,
  `userid_create` int(50) NOT NULL,
  `userid_update` int(50) NOT NULL,
  `cert_file_name` text NOT NULL,
  `clear_due_flag` int(11) NOT NULL COMMENT 'clear_amount_entry then 1',
  `combind_payment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=717 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `invoice_issuer`
--

DROP TABLE IF EXISTS `invoice_issuer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoice_issuer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `issuer_name` varchar(255) NOT NULL,
  `display_name` varchar(255) DEFAULT NULL,
  `company_name` text NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` int(20) NOT NULL,
  `address` text NOT NULL,
  `reg_no` varchar(50) NOT NULL,
  `vat_no` varchar(50) NOT NULL,
  `gst_no` varchar(50) DEFAULT NULL,
  `cin_no` varchar(50) DEFAULT NULL,
  `pan_no` varchar(50) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `logo` blob,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loan_advance`
--

DROP TABLE IF EXISTS `loan_advance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_advance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `type_id` int(11) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `current_address` text NOT NULL,
  `company_name` varchar(50) NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL,
  `opening_balance_date` int(11) NOT NULL,
  `another_contact_name` varchar(50) NOT NULL,
  `another_mobile` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loan_advance_payment`
--

DROP TABLE IF EXISTS `loan_advance_payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_advance_payment` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) DEFAULT NULL,
  `bank_id` int(50) DEFAULT NULL,
  `client_id` int(50) DEFAULT NULL,
  `invoice_id` int(11) NOT NULL,
  `debit` decimal(15,2) DEFAULT NULL,
  `credit` decimal(15,2) DEFAULT NULL,
  `interest_per` decimal(15,2) NOT NULL,
  `description` text,
  `payment_flag` int(11) NOT NULL DEFAULT '0',
  `payment_date` int(11) DEFAULT NULL,
  `payment_method` varchar(25) NOT NULL,
  `payment_checkno` varchar(100) NOT NULL,
  `invoice_issuer_id` int(11) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime NOT NULL,
  `trans_type` int(10) NOT NULL,
  `trans_type_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loan_attach_file`
--

DROP TABLE IF EXISTS `loan_attach_file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loan_attach_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attach_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `file_name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `menu_old`
--

DROP TABLE IF EXISTS `menu_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_plan`
--

DROP TABLE IF EXISTS `payment_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_plan` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `trans_id` int(11) DEFAULT NULL,
  `advance_loan_no` int(50) NOT NULL,
  `bank_id` int(50) DEFAULT NULL,
  `cust_id` int(50) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `invoice_id` varchar(255) NOT NULL,
  `loan_id` int(50) NOT NULL,
  `debit` decimal(15,2) DEFAULT NULL,
  `credit` decimal(15,2) DEFAULT NULL,
  `invoice_pay_id` text NOT NULL,
  `invoice_due_pay_id` text NOT NULL,
  `invoice_pay_amount` decimal(15,2) NOT NULL,
  `gst_amount` decimal(15,2) NOT NULL,
  `gst_id` text NOT NULL,
  `gst_due_id` text NOT NULL,
  `tds_per` int(11) NOT NULL,
  `tds_amount` decimal(15,2) NOT NULL,
  `tds_id` text NOT NULL,
  `tds_due_id` text NOT NULL,
  `description` text,
  `hsn_code` varchar(100) NOT NULL,
  `on_customer` int(11) DEFAULT NULL,
  `on_project` int(11) DEFAULT NULL,
  `on_bank` int(11) DEFAULT NULL,
  `on_loan` int(50) NOT NULL,
  `payment_flag` int(11) NOT NULL DEFAULT '0',
  `payment_date` int(11) DEFAULT NULL,
  `payment_method` varchar(25) NOT NULL,
  `payment_checkno` varchar(100) NOT NULL,
  `interest_per` decimal(15,2) NOT NULL,
  `link_id` int(11) NOT NULL,
  `link2_id` int(11) NOT NULL,
  `link3_id` int(11) NOT NULL,
  `multi_project_id` text NOT NULL,
  `goods_detail_id` text NOT NULL,
  `invoice_issuer_id` int(11) NOT NULL,
  `subdivision` int(11) NOT NULL,
  `gst_subdivision` int(11) NOT NULL,
  `tds_subdivision` int(11) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime NOT NULL,
  `trans_type` int(15) NOT NULL,
  `trans_type_name` varchar(40) NOT NULL,
  `multi_invoice_flag` int(11) NOT NULL DEFAULT '0',
  `multi_invoice_detail` text NOT NULL,
  `multi_invoice_id` text NOT NULL,
  `tds_flag` int(11) NOT NULL DEFAULT '0',
  `gst_flag` int(11) NOT NULL DEFAULT '0',
  `invoice_flag` int(11) NOT NULL DEFAULT '0',
  `clear_invoice_flag` tinyint(1) NOT NULL DEFAULT '0',
  `clear_gst_flag` tinyint(1) NOT NULL DEFAULT '0',
  `clear_tds_flag` tinyint(1) NOT NULL DEFAULT '0',
  `file_import_id` int(11) NOT NULL DEFAULT '0',
  `file_import_data_id` int(11) NOT NULL DEFAULT '0',
  `userid_create` int(50) NOT NULL,
  `userid_update` int(50) NOT NULL,
  `clear_table_link_id` int(11) NOT NULL COMMENT 'clear_table_link_id',
  `supplier_invoice_number` varchar(255) DEFAULT NULL,
  `printable_invoice_number` varchar(255) DEFAULT NULL,
  `invoice_type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25159 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `opening_balance` decimal(15,2) DEFAULT NULL,
  `opening_balance_date` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `subdivision`
--

DROP TABLE IF EXISTS `subdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subdivision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_info`
--

DROP TABLE IF EXISTS `tbl_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tbl_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_id` int(11) NOT NULL DEFAULT '0',
  `invoice_id` int(11) NOT NULL DEFAULT '0',
  `payment_plan_link1` int(11) NOT NULL DEFAULT '0',
  `payment_plan_link2` int(11) NOT NULL DEFAULT '0',
  `payment_plan_link3` int(11) NOT NULL COMMENT 'purchases_1_cust_id',
  `payment_plan_link4` int(11) NOT NULL COMMENT 'purchases_1_bank_id',
  `file_import_id` int(11) NOT NULL,
  `file_series` int(11) NOT NULL DEFAULT '0',
  `from_name` varchar(50) NOT NULL DEFAULT '',
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_name` varchar(50) NOT NULL DEFAULT '',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `project` varchar(50) NOT NULL DEFAULT '',
  `project_id` int(11) NOT NULL DEFAULT '0',
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` varchar(50) NOT NULL,
  `payment_date` int(11) NOT NULL,
  `payment_type` varchar(50) NOT NULL DEFAULT '',
  `final_payment_type` varchar(50) NOT NULL,
  `final_pay_type_flag` int(11) NOT NULL DEFAULT '0' COMMENT 'if final selected payment type then flag value is 1 other wise 0',
  `file_name` varchar(50) NOT NULL DEFAULT '',
  `action_flag` int(11) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `transfer_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tds_due_info`
--

DROP TABLE IF EXISTS `tds_due_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tds_due_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_plan_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `trans_id` int(11) NOT NULL,
  `pp_linkid_1` int(11) NOT NULL COMMENT 'customer payment id',
  `pp_linkid_2` int(11) NOT NULL COMMENT 'bank payment id',
  `amount` decimal(15,2) NOT NULL,
  `received_amount` decimal(15,2) NOT NULL,
  `due_date` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `description` text NOT NULL,
  `combine_description` text NOT NULL,
  `userid_create` int(50) NOT NULL,
  `userid_update` int(50) NOT NULL,
  `cert_file_name` text NOT NULL,
  `clear_due_flag` int(11) NOT NULL COMMENT 'clear_amount_entry then 1',
  `combind_payment` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tds_list`
--

DROP TABLE IF EXISTS `tds_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tds_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tds` int(11) NOT NULL,
  `active` int(11) DEFAULT '1',
  `default_select` int(11) NOT NULL DEFAULT '0',
  `create_date` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tds_subdivision`
--

DROP TABLE IF EXISTS `tds_subdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tds_subdivision` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `default_select` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `current_address` text NOT NULL,
  `same_address` varchar(50) NOT NULL DEFAULT 'no',
  `permanent_address` text NOT NULL,
  `admin` varchar(20) NOT NULL,
  `authority` text NOT NULL,
  `create_date` datetime NOT NULL,
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-10-27 15:48:29
