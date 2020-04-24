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

-- Volcando datos para la tabla id12329915_decoyeso.cliaddres_status: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `cliaddres_status` DISABLE KEYS */;
INSERT INTO `cliaddres_status` (`CliAddresStatus_Id`, `CliAddresStatus_Name`, `CliAddresStatus_Desc`) VALUES
	(1, 'Activo', 'La dirección esta activa.'),
	(2, 'Activo envios', 'La dirección esta activa y es para envios.'),
	(3, 'Inactiva_', 'La dirección no esta activa.');
/*!40000 ALTER TABLE `cliaddres_status` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.clients: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.clients_adress: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `clients_adress` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients_adress` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.clients_phones: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `clients_phones` DISABLE KEYS */;
/*!40000 ALTER TABLE `clients_phones` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.client_status: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `client_status` DISABLE KEYS */;
INSERT INTO `client_status` (`CliStatus_Id`, `CliStaus_Name`, `CliStatus_Desc`) VALUES
	(1, 'Activo', 'El cliente puede comprar.'),
	(2, 'Inactivo', 'El cliente no puede comprar.'),
	(3, 'Eliminado', 'El cliente ha sido eliminado y no prodra activarse.'),
	(4, 'Deudor', 'El cliente tiene una deuda.');
/*!40000 ALTER TABLE `client_status` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.employees: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.employees_roles: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `employees_roles` DISABLE KEYS */;
INSERT INTO `employees_roles` (`EmpRoles_Id`, `EmpRoles_Name`, `EmpRoles_Desc`) VALUES
	(1, 'Vendedor', 'El empleado se encargara de atender a los clientes ofreciendo los productos de la tienda.'),
	(2, 'Gerente', 'El empleado tiene el rol de gerente de la tienda. Puede administrar el inventario, así como a los empleados.'),
	(3, 'Administrador', 'Tiene todos los permisos.'),
	(4, 'Gerente general', 'Tiene todos los permisos.');
/*!40000 ALTER TABLE `employees_roles` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.employees_status: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `employees_status` DISABLE KEYS */;
INSERT INTO `employees_status` (`EmpStatus_Id`, `EmpStatus_Name`, `EmpStaus_Desc`) VALUES
	(1, 'Habilitado', 'El empleado esta habilitado para utilizar el sistema.'),
	(2, 'Deshabilitado', 'El empleado no tiene permiso de usar el sistema.');
/*!40000 ALTER TABLE `employees_status` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.products: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.products_images: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `products_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `products_images` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.products_status: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `products_status` DISABLE KEYS */;
INSERT INTO `products_status` (`ProdStatus_Id`, `ProdStatus_Name`, `ProdStaus_Desc`) VALUES
	(1, 'Disponible', 'Existen al menos cinco elementos en el inventario.'),
	(2, 'No disponible', 'No hay producto en el inventario, pero seguira surtiendose.'),
	(3, 'Poco inventario', 'Existe inventario de este producto, pero esta pronto a terminarse.'),
	(4, 'Eliminado', 'Este producto ya no se surte más.'),
	(5, 'Programado', 'Este producto saldrá a la venta en un futuro.'),
	(6, 'Pausado con inventario', 'Existe inventario de este producto, pero su venta no esta disponible hasta nuevo aviso.'),
	(7, 'Pausado sin inventario', 'La venta de este producto esta detenida, pero puede reaunudarse. No existe inventario.');
/*!40000 ALTER TABLE `products_status` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.sales: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.sales_details: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sales_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `sales_details` ENABLE KEYS */;

-- Volcando datos para la tabla id12329915_decoyeso.sale_client: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `sale_client` DISABLE KEYS */;
/*!40000 ALTER TABLE `sale_client` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
