-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 10-05-2019 a las 11:56:29
-- Versión del servidor: 5.7.24
-- Versión de PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `wkleoparada`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proceso`
--

DROP TABLE IF EXISTS `proceso`;
CREATE TABLE IF NOT EXISTS `proceso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `archivo` varchar(200) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proceso`
--

INSERT INTO `proceso` (`id`, `created_at`, `updated_at`, `descripcion`, `archivo`, `estado`) VALUES
(1, '2019-05-10 11:33:34', '2019-05-10 11:33:34', 'algo aqui', 'procesos\\1557488014.xlsx', 1),
(2, '2019-05-10 11:41:35', '2019-05-10 11:41:35', 'algo aqui', 'procesos\\1557488494.xlsx', 1),
(3, '2019-05-10 11:49:21', '2019-05-10 11:49:21', 'algo aqui', 'procesos\\1557488961.xlsx', 1),
(4, '2019-05-10 11:50:33', '2019-05-10 11:50:33', 'algo aqui', 'procesos\\1557489032.xlsx', 1),
(5, '2019-05-10 11:55:39', '2019-05-10 11:55:39', 'algo aqui', 'procesos\\1557489339.xlsx', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proceso_detalle`
--

DROP TABLE IF EXISTS `proceso_detalle`;
CREATE TABLE IF NOT EXISTS `proceso_detalle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL,
  `id_proceso` text NOT NULL,
  `igr_area` text NOT NULL COMMENT 'Área Departamento',
  `igr_ges` text NOT NULL COMMENT 'GES Grupo de Exposición Similar',
  `igr_trabajador` text NOT NULL COMMENT 'Trabajador',
  `igr_fri` text NOT NULL COMMENT 'Fuentes de Ruido Incidentes (y estado)',
  `igr_ciclott` text NOT NULL COMMENT 'Ciclo de Trabajo o Tareas incluidas en medición',
  `igr_neqdbc_1` decimal(10,2) NOT NULL COMMENT 'Neq dBC - #1',
  `igr_neqdbc_2` decimal(10,2) NOT NULL COMMENT 'Neq dBC - #2',
  `igr_neqdbc_3` decimal(10,2) NOT NULL COMMENT 'Neq dBC - #3',
  `igr_neqdbc_4` decimal(10,2) NOT NULL COMMENT 'Neq dBC - #4',
  `igr_neqdbc_5` decimal(10,2) NOT NULL COMMENT 'Neq dBC - #5',
  `igr_peakc_1` decimal(10,2) NOT NULL COMMENT 'PeakC - #1',
  `igr_peakc_2` decimal(10,2) NOT NULL COMMENT 'PeakC - #2',
  `igr_peakc_3` decimal(10,2) NOT NULL COMMENT 'PeakC - #3',
  `igr_peakc_4` decimal(10,2) NOT NULL COMMENT 'PeakC - #4',
  `igr_peakc_5` decimal(10,2) NOT NULL COMMENT 'PeakC - #5',
  `cic_neqdbc_1` decimal(10,2) NOT NULL COMMENT 'Neq dBA Cada Ciclo - #1',
  `cic_neqdbc_2` decimal(10,2) NOT NULL COMMENT 'Neq dBA Cada Ciclo - #2',
  `cic_neqdbc_3` decimal(10,2) NOT NULL COMMENT 'Neq dBA Cada Ciclo - #3',
  `cic_neqdbc_4` decimal(10,2) NOT NULL COMMENT 'Neq dBA Cada Ciclo - #4',
  `cic_neqdbc_5` decimal(10,2) NOT NULL COMMENT 'Neq dBA Cada Ciclo - #5',
  `cic_tm_1` decimal(10,2) NOT NULL COMMENT 'Tiempo Medición (minutos) - #1',
  `cic_tm_2` decimal(10,2) NOT NULL COMMENT 'Tiempo Medición (minutos) - #2',
  `cic_tm_3` decimal(10,2) NOT NULL COMMENT 'Tiempo Medición (minutos) - #3',
  `cic_tm_4` decimal(10,2) NOT NULL COMMENT 'Tiempo Medición (minutos) - #4',
  `cic_tm_5` decimal(10,2) NOT NULL COMMENT 'Tiempo Medición (minutos) - #5',
  `cic_tej_1` decimal(10,2) NOT NULL COMMENT 'Tiempo Efectivo por jornada (horas) - #1',
  `cic_tej_2` decimal(10,2) NOT NULL COMMENT 'Tiempo Efectivo por jornada (horas) - #2',
  `cic_tej_3` decimal(10,2) NOT NULL COMMENT 'Tiempo Efectivo por jornada (horas) - #3',
  `cic_tej_4` decimal(10,2) NOT NULL COMMENT 'Tiempo Efectivo por jornada (horas) - #4',
  `cic_tej_5` decimal(10,2) NOT NULL COMMENT 'Tiempo Efectivo por jornada (horas) - #5',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `proceso_detalle`
--

INSERT INTO `proceso_detalle` (`id`, `created_at`, `updated_at`, `id_proceso`, `igr_area`, `igr_ges`, `igr_trabajador`, `igr_fri`, `igr_ciclott`, `igr_neqdbc_1`, `igr_neqdbc_2`, `igr_neqdbc_3`, `igr_neqdbc_4`, `igr_neqdbc_5`, `igr_peakc_1`, `igr_peakc_2`, `igr_peakc_3`, `igr_peakc_4`, `igr_peakc_5`, `cic_neqdbc_1`, `cic_neqdbc_2`, `cic_neqdbc_3`, `cic_neqdbc_4`, `cic_neqdbc_5`, `cic_tm_1`, `cic_tm_2`, `cic_tm_3`, `cic_tm_4`, `cic_tm_5`, `cic_tej_1`, `cic_tej_2`, `cic_tej_3`, `cic_tej_4`, `cic_tej_5`) VALUES
(1, '2019-05-10 11:50:33', '2019-05-10 11:50:33', '4', 'TALLER', 'OPERADORES DE TALLER', 'RAMON ALBORNOZ', 'TALADRO VERTICAL | ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9) | MARTILLO DE PUNTO Y COMBO | LETRAS DE GOLPE | SIERRA DE CORTE', 'SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.\nUSO DE TALADRO VERTICAL EN MATERIALES FERROSOS\nCORTES DE MATERIALES FERROSOS, DESBASTES. CON ESMERIL \nOPERADOR UTILIZA MARTILLO PARA MARCAR PIEZAS \nUSO DE MAQUINA DE CORTE EN OCACIONES DE ANGULOS U OTRAS PIEZAS\nSEGÚN NECESIDADES DE FABRICACIÓN SE REALIZAN CORTES CON PLASMA\nLABORES DE ASEO, BUSQUEDA DE PLANOS, BAÑO,  BEBER AGUA, BUSQUEDA DE MATERIALES Y EPP.', '94.90', '12.00', '12.00', '456.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(2, '2019-05-10 11:50:33', '2019-05-10 11:50:33', '4', 'TALLER', 'OPERADORES DE TALLER', 'HECTOR RUIZ', 'PLASMA', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(3, '2019-05-10 11:50:33', '2019-05-10 11:50:33', '4', 'TALLER', 'AYUDANTE SOLDADOR Y SOLDADORES', 'CARLOS ARREDONDO', 'ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9) | MAQUINA SOLDADORA (indura mig,263 Pro-280 amps', 'SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.\nTRABAJADOR COMIENZA A UTILIZAR MAQUINA SOLDADORA, PARA LA UNION DE MATERIALES FERROSOS. \nLABORES DE ASEO, BUSQUEDA DE PLANOS, BAÑO,  BEBER AGUA, BUSQUEDA DE MATERIALES Y EPP.', '94.80', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(4, '2019-05-10 11:50:33', '2019-05-10 11:50:33', '4', 'TALLER', 'ARMADOR', 'LUIS LOPEZ', 'MAQUINA SOLDADORA (indura mig,263 Pro-280 amps | MARTILLO DE PUNTO | ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9)', 'SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.\nArmador  recibe el material, comienzan a empalmar utilizando maquina soldadora para luego entregar al soldador. \nARMADOR UTILIZA EL  MARTILLO DE PUNTO PARA ALINEAR ESTRUCTURAS Y ENCAJARLAS BIEN NO QUEDEN MAL EMPALMADAS\nTRABAJADOR OPERA ESMERIL PARA LIMPIAR MATERIAL FERROSOS,  SACAR EXCESO DE SOLDADURA. \nLABORES DE ASEO, BUSQUEDA DE PLANOS, BAÑO,  BEBER AGUA, BUSQUEDA DE MATERIALES Y EPP, TAREAS PROPIAS DEL CARGO, ENCAJAR PIEZAS Y ARMAR', '101.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(5, '2019-05-10 11:50:33', '2019-05-10 11:50:33', '4', 'TALLER', 'ADMINISTRATIVO, SUPERVISOR', 'JUAN DIAZ', 'TALADRO VERTICAL | ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9) | MARTILLO DE PUNTO Y COMBO | LETRAS DE GOLPE | SIERRA DE CORTE', '', '102.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00'),
(6, '2019-05-10 11:55:39', '2019-05-10 11:55:39', '5', 'TALLER', 'OPERADORES DE TALLER', 'RAMON ALBORNOZ', 'TALADRO VERTICAL | ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9) | MARTILLO DE PUNTO Y COMBO | LETRAS DE GOLPE | SIERRA DE CORTE', 'SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.\nUSO DE TALADRO VERTICAL EN MATERIALES FERROSOS\nCORTES DE MATERIALES FERROSOS, DESBASTES. CON ESMERIL \nOPERADOR UTILIZA MARTILLO PARA MARCAR PIEZAS \nUSO DE MAQUINA DE CORTE EN OCACIONES DE ANGULOS U OTRAS PIEZAS\nSEGÚN NECESIDADES DE FABRICACIÓN SE REALIZAN CORTES CON PLASMA\nLABORES DE ASEO, BUSQUEDA DE PLANOS, BAÑO,  BEBER AGUA, BUSQUEDA DE MATERIALES Y EPP.', '94.90', '12.00', '12.00', '456.00', '0.00', '132.00', '534.00', '23.00', '1.00', '0.00', '94.70', '0.00', '0.00', '0.00', '0.00', '70.00', '0.00', '0.00', '0.00', '0.00', '8.00', '0.00', '0.00', '0.00', '0.00'),
(7, '2019-05-10 11:55:39', '2019-05-10 11:55:39', '5', 'TALLER', 'OPERADORES DE TALLER', 'HECTOR RUIZ', 'PLASMA', '', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '92.50', '0.00', '0.00', '0.00', '0.00', '72.00', '0.00', '0.00', '0.00', '0.00', '8.00', '0.00', '0.00', '0.00', '0.00'),
(8, '2019-05-10 11:55:39', '2019-05-10 11:55:39', '5', 'TALLER', 'AYUDANTE SOLDADOR Y SOLDADORES', 'CARLOS ARREDONDO', 'ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9) | MAQUINA SOLDADORA (indura mig,263 Pro-280 amps', 'SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.\nTRABAJADOR COMIENZA A UTILIZAR MAQUINA SOLDADORA, PARA LA UNION DE MATERIALES FERROSOS. \nLABORES DE ASEO, BUSQUEDA DE PLANOS, BAÑO,  BEBER AGUA, BUSQUEDA DE MATERIALES Y EPP.', '94.80', '0.00', '0.00', '0.00', '0.00', '138.00', '0.00', '0.00', '0.00', '0.00', '95.50', '0.00', '0.00', '0.00', '0.00', '70.00', '0.00', '0.00', '0.00', '0.00', '8.00', '0.00', '0.00', '0.00', '0.00'),
(9, '2019-05-10 11:55:39', '2019-05-10 11:55:39', '5', 'TALLER', 'ARMADOR', 'LUIS LOPEZ', 'MAQUINA SOLDADORA (indura mig,263 Pro-280 amps | MARTILLO DE PUNTO | ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9)', 'SE REUNEN PARA REALIZAR CHARLA DE SEGURIDAD, RETIRAN HERRAMIENTAS, VER PLANOS, DESIGNACION DE TAREAS, BUSQUEDA DE MATERIALES.\nArmador  recibe el material, comienzan a empalmar utilizando maquina soldadora para luego entregar al soldador. \nARMADOR UTILIZA EL  MARTILLO DE PUNTO PARA ALINEAR ESTRUCTURAS Y ENCAJARLAS BIEN NO QUEDEN MAL EMPALMADAS\nTRABAJADOR OPERA ESMERIL PARA LIMPIAR MATERIAL FERROSOS,  SACAR EXCESO DE SOLDADURA. \nLABORES DE ASEO, BUSQUEDA DE PLANOS, BAÑO,  BEBER AGUA, BUSQUEDA DE MATERIALES Y EPP, TAREAS PROPIAS DEL CARGO, ENCAJAR PIEZAS Y ARMAR', '101.00', '0.00', '0.00', '0.00', '0.00', '143.50', '0.00', '0.00', '0.00', '0.00', '100.90', '0.00', '0.00', '0.00', '0.00', '65.00', '0.00', '0.00', '0.00', '0.00', '8.00', '0.00', '0.00', '0.00', '0.00'),
(10, '2019-05-10 11:55:39', '2019-05-10 11:55:39', '5', 'TALLER', 'ADMINISTRATIVO, SUPERVISOR', 'JUAN DIAZ', 'TALADRO VERTICAL | ESMERIL Angular  (Indura año 2014,modelo WEPBA 14-125 HM  tamaño 4.5, 7, 9) | MARTILLO DE PUNTO Y COMBO | LETRAS DE GOLPE | SIERRA DE CORTE', '', '102.00', '0.00', '0.00', '0.00', '0.00', '142.00', '0.00', '0.00', '0.00', '0.00', '100.10', '0.00', '0.00', '0.00', '0.00', '63.00', '0.00', '0.00', '0.00', '0.00', '8.00', '0.00', '0.00', '0.00', '0.00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
