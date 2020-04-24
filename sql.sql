-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         10.4.11-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando estructura para tabla id12329915_decoyeso.cliaddres_status
CREATE TABLE IF NOT EXISTS `cliaddres_status` (
  `CliAddresStatus_Id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `CliAddresStatus_Name` varchar(50) NOT NULL,
  `CliAddresStatus_Desc` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`CliAddresStatus_Id`),
  UNIQUE KEY `CliAddresStatus_Name` (`CliAddresStatus_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `Cli_Id` int(10) unsigned NOT NULL DEFAULT 0,
  `Cli_RFC` char(13) NOT NULL DEFAULT '',
  `Cli_Firstname` varchar(50) NOT NULL,
  `Cli_Lastname` varchar(50) NOT NULL,
  `Cli_Email` varchar(50) DEFAULT NULL,
  `Cli_Status` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `Cli_Date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`Cli_Id`),
  UNIQUE KEY `Cli_RFC` (`Cli_RFC`),
  KEY `Cli_Status` (`Cli_Status`),
  CONSTRAINT `clients_ibfk_1` FOREIGN KEY (`Cli_Status`) REFERENCES `client_status` (`CliStatus_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.clients_adress
CREATE TABLE IF NOT EXISTS `clients_adress` (
  `CliAddres_Id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Cli_Id` int(10) unsigned NOT NULL DEFAULT 0,
  `CliAddres_Street` varchar(50) NOT NULL,
  `CliAddres_Extnum` varchar(15) NOT NULL,
  `CliAddres_Intnum` varchar(15) NOT NULL DEFAULT '',
  `CliAddres_City` varchar(50) NOT NULL,
  `CliAddres_State` varchar(50) NOT NULL,
  `CliAddres_Status` tinyint(4) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`CliAddres_Id`),
  KEY `CliAddres_Status` (`CliAddres_Status`),
  KEY `Cli_Id` (`Cli_Id`),
  CONSTRAINT `clients_adress_ibfk_1` FOREIGN KEY (`CliAddres_Status`) REFERENCES `cliaddres_status` (`CliAddresStatus_Id`) ON UPDATE CASCADE,
  CONSTRAINT `clients_adress_ibfk_2` FOREIGN KEY (`Cli_Id`) REFERENCES `clients` (`Cli_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.clients_phones
CREATE TABLE IF NOT EXISTS `clients_phones` (
  `CliPhones_Id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `CliPhones_Number` varchar(15) NOT NULL DEFAULT '0',
  `Cli_Id` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`CliPhones_Id`),
  KEY `Cli_Id` (`Cli_Id`),
  CONSTRAINT `clients_phones_ibfk_1` FOREIGN KEY (`Cli_Id`) REFERENCES `clients` (`Cli_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.client_status
CREATE TABLE IF NOT EXISTS `client_status` (
  `CliStatus_Id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `CliStaus_Name` varchar(15) NOT NULL,
  `CliStatus_Desc` varchar(240) NOT NULL DEFAULT '',
  PRIMARY KEY (`CliStatus_Id`),
  UNIQUE KEY `CliStaus_Name` (`CliStaus_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.employees
CREATE TABLE IF NOT EXISTS `employees` (
  `Emp_Id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `Emp_CURP` char(18) COLLATE utf8_unicode_ci NOT NULL,
  `Emp_Nickname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Emp_Password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Emp_Fistname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Emp_Lastname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Emp_Birthday` date NOT NULL,
  `Emp_Addres` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `Emp_Phone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `Emp_Status` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `Emp_Role` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `Emp_InitDate` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Emp_Id`),
  UNIQUE KEY `Emp_CURP` (`Emp_CURP`),
  UNIQUE KEY `Emp_Nickname` (`Emp_Nickname`),
  KEY `Emp_Status` (`Emp_Status`),
  KEY `Emp_Role` (`Emp_Role`),
  CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`Emp_Role`) REFERENCES `employees_roles` (`EmpRoles_Id`) ON UPDATE CASCADE,
  CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`Emp_Status`) REFERENCES `employees_status` (`EmpStatus_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.employees_roles
CREATE TABLE IF NOT EXISTS `employees_roles` (
  `EmpRoles_Id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `EmpRoles_Name` varchar(15) NOT NULL,
  `EmpRoles_Desc` varchar(240) NOT NULL DEFAULT '',
  PRIMARY KEY (`EmpRoles_Id`),
  UNIQUE KEY `EmpRoles_Name` (`EmpRoles_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.employees_status
CREATE TABLE IF NOT EXISTS `employees_status` (
  `EmpStatus_Id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `EmpStatus_Name` varchar(50) NOT NULL,
  `EmpStaus_Desc` varchar(240) NOT NULL DEFAULT '',
  PRIMARY KEY (`EmpStatus_Id`),
  UNIQUE KEY `EmpStatus_Name` (`EmpStatus_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.products
CREATE TABLE IF NOT EXISTS `products` (
  `Prod_Id` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `Prod_Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Prod_Descr` varchar(240) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Prod_Value` decimal(10,3) unsigned NOT NULL,
  `Prod_Quantity` smallint(6) NOT NULL,
  `Prod_Status` tinyint(4) unsigned NOT NULL DEFAULT 0,
  `Prod_IntroDate` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`Prod_Id`) USING BTREE,
  KEY `Prod_Status` (`Prod_Status`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`Prod_Status`) REFERENCES `products_status` (`ProdStatus_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.products_images
CREATE TABLE IF NOT EXISTS `products_images` (
  `ProImage_Id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `ProImage_Url` varchar(100) NOT NULL,
  `Pro_Id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`ProImage_Id`) USING BTREE,
  KEY `Pro_Id` (`Pro_Id`),
  CONSTRAINT `products_images_ibfk_1` FOREIGN KEY (`Pro_Id`) REFERENCES `products` (`Prod_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.products_status
CREATE TABLE IF NOT EXISTS `products_status` (
  `ProdStatus_Id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `ProdStatus_Name` varchar(50) NOT NULL,
  `ProdStaus_Desc` varchar(240) NOT NULL DEFAULT '',
  PRIMARY KEY (`ProdStatus_Id`),
  UNIQUE KEY `ProdStatus_Name` (`ProdStatus_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.sales
CREATE TABLE IF NOT EXISTS `sales` (
  `Sal_Id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `Sal_Date` datetime NOT NULL DEFAULT current_timestamp(),
  `Sal_EmpId` smallint(5) unsigned NOT NULL DEFAULT 0,
  `Sal_Net` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  `Sal_IVA` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  `Sal_Total` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`Sal_Id`),
  KEY `Sal_EmpId` (`Sal_EmpId`),
  CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`Sal_EmpId`) REFERENCES `employees` (`Emp_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.sales_details
CREATE TABLE IF NOT EXISTS `sales_details` (
  `SalDet_Id` bigint(20) unsigned NOT NULL,
  `SalDet_SalId` bigint(20) unsigned NOT NULL,
  `SalDet_ProdId` mediumint(8) unsigned NOT NULL,
  `SalDet_Quantity` smallint(5) NOT NULL DEFAULT 0,
  `SalDet_Price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `SalDet_Cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`SalDet_Id`),
  KEY `SalDet_SalId` (`SalDet_SalId`),
  KEY `SalDet_ProdId` (`SalDet_ProdId`),
  CONSTRAINT `sales_details_ibfk_1` FOREIGN KEY (`SalDet_SalId`) REFERENCES `sales` (`Sal_Id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_details_ibfk_2` FOREIGN KEY (`SalDet_ProdId`) REFERENCES `products` (`Prod_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla id12329915_decoyeso.sale_client
CREATE TABLE IF NOT EXISTS `sale_client` (
  `Sal_Id` bigint(20) unsigned NOT NULL DEFAULT 0,
  `Cli_Id` int(10) unsigned NOT NULL DEFAULT 0,
  KEY `Sal_Id` (`Sal_Id`),
  KEY `Cli_Id` (`Cli_Id`),
  CONSTRAINT `sale_client_ibfk_1` FOREIGN KEY (`Sal_Id`) REFERENCES `sales` (`Sal_Id`) ON UPDATE CASCADE,
  CONSTRAINT `sale_client_ibfk_2` FOREIGN KEY (`Cli_Id`) REFERENCES `clients` (`Cli_Id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- La exportación de datos fue deseleccionada.

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
