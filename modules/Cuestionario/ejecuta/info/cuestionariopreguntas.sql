-- phpMyAdmin SQL Dump
-- version 2.11.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 23-10-2009 a las 04:51:54
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de datos: `cuestionariopreguntas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta_revision`
--

CREATE TABLE `pregunta_revision` (
  `revisionid` int(11) default NULL,
  `preguntasid` int(11) default NULL,
  `question` varchar(250) default NULL,
  `categoriapregunta` varchar(100) default NULL,
  `subcategoriapregunta` varchar(100) default NULL,
  `respondida` varchar(3) default NULL,
  `respuesta` varchar(500) default NULL,
  `respuestaid` varchar(4) default NULL,
  `yes_points` float default NULL,
  `no_points` float default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `pregunta_revision`
--

INSERT INTO `pregunta_revision` (`revisionid`, `preguntasid`, `question`, `categoriapregunta`, `subcategoriapregunta`, `respondida`, `respuesta`, `respuestaid`, `yes_points`, `no_points`) VALUES
(5970, 5960, 'Esta es la 3 pregunta del primer cuestionario', 'Revision', 'Subcat1', '1', 'Respuesta no p3', 'No', 4, 2),
(5970, 5967, 'Esta es la 9 pregunta del 2 cuestionario', 'Revision', 'Subcat2', '1', 'Ingrese su respuesta', 'Text', 4, 2),
(5970, 5969, 'Esta es la 5 pregunta del 2 cuestionario', 'Auditoria', 'Subcat1', '1', '', 'NoA', 4, 2),
(5957, 5952, 'Esta es la primera pregunta del primer cuestionario', 'Auditoria', 'Subcat2', '0', NULL, NULL, 8, 4),
(5957, 5953, 'Esta es la segunda pregunta del primer cuestionario', 'Revision', 'Subcat3', '0', NULL, NULL, 4, 2),
(5957, 5960, 'Esta es la 3 pregunta del primer cuestionario', 'Revision', 'Subcat1', '0', NULL, NULL, 4, 2),
(5957, 5961, 'Esta es la 4 pregunta del primer cuestionario', 'Revision', 'Subcat2', '1', 'Respuesta no p4', 'No', 4, 2),
(5957, 5962, 'Esta es la 5 pregunta del primer cuestionario', 'Revision', 'Subcat2', '1', '', 'NoA', 4, 2),
(5957, 5963, 'Esta es la 6 pregunta del primer cuestionario', 'Categoria', 'Subcat1', '0', NULL, NULL, 4, 2),
(5957, 5964, 'Esta es la 7 pregunta del primer cuestionario', 'Revision', 'Subcat3', '0', NULL, NULL, 4, 2),
(5957, 5965, 'Esta es la 8 pregunta del primer cuestionario', 'Categoria', 'Subcat3', '1', 'Ingrese su respuesta afgsdfgs', 'Text', 4, 2),
(5957, 5966, 'Esta es la 9 pregunta del primer cuestionario', 'Revision', 'Subcat3', '0', NULL, NULL, 4, 2),
(5957, 5968, 'Esta es la 10 pregunta del primer cuestionario', 'Auditoria', 'Subcat3', '0', NULL, NULL, 8, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vtiger_cuestionario`
--

CREATE TABLE `vtiger_cuestionario` (
  `cuestionarioid` int(11) default NULL,
  `name` varchar(100) default NULL,
  `estadocuestionario` varchar(100) default NULL,
  `note` float default NULL,
  `description` varchar(256) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `vtiger_cuestionario`
--

INSERT INTO `vtiger_cuestionario` (`cuestionarioid`, `name`, `estadocuestionario`, `note`, `description`) VALUES
(5957, 'C1', 'Vigente', 6, 'desc'),
(5970, 'Cuestionario 2', 'Vigente', 8, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vtiger_cuestiones`
--

CREATE TABLE `vtiger_cuestiones` (
  `cuestionesid` int(19) NOT NULL auto_increment,
  `cuestionarioid` int(19) NOT NULL,
  `preguntasid` int(11) NOT NULL,
  `pregunta` varchar(250) NOT NULL,
  `categoria` varchar(250) NOT NULL,
  `subcategoria` varchar(250) NOT NULL,
  `yes_points` float NOT NULL,
  `no_points` float NOT NULL,
  PRIMARY KEY  (`cuestionesid`),
  KEY `cuestionarioid` (`cuestionarioid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31 ;

--
-- Volcar la base de datos para la tabla `vtiger_cuestiones`
--

INSERT INTO `vtiger_cuestiones` (`cuestionesid`, `cuestionarioid`, `preguntasid`, `pregunta`, `categoria`, `subcategoria`, `yes_points`, `no_points`) VALUES
(16, 5970, 5967, 'Esta es la 9 pregunta del 2 cuestionario', 'Revision', 'Subcat2', 4, 2),
(17, 5970, 5969, 'Esta es la 5 pregunta del 2 cuestionario', 'Auditoria', 'Subcat1', 4, 2),
(18, 5957, 5952, 'Esta es la primera pregunta del primer cuestionario', 'Auditoria', 'Subcat2', 8, 4),
(19, 5957, 5953, 'Esta es la segunda pregunta del primer cuestionario', 'Revision', 'Subcat3', 4, 2),
(20, 5957, 5960, 'Esta es la 3 pregunta del primer cuestionario', 'Revision', 'Subcat1', 4, 2),
(21, 5957, 5961, 'Esta es la 4 pregunta del primer cuestionario', 'Revision', 'Subcat2', 4, 2),
(22, 5957, 5962, 'Esta es la 5 pregunta del primer cuestionario', 'Revision', 'Subcat2', 4, 2),
(23, 5957, 5963, 'Esta es la 6 pregunta del primer cuestionario', 'Categoria', 'Subcat1', 4, 2),
(24, 5957, 5964, 'Esta es la 7 pregunta del primer cuestionario', 'Revision', 'Subcat3', 4, 2),
(25, 5957, 5965, 'Esta es la 8 pregunta del primer cuestionario', 'Categoria', 'Subcat3', 4, 2),
(26, 5957, 5966, 'Esta es la 9 pregunta del primer cuestionario', 'Revision', 'Subcat3', 4, 2),
(27, 5957, 5968, 'Esta es la 10 pregunta del primer cuestionario', 'Auditoria', 'Subcat3', 8, 4),
(30, 5970, 5960, 'Esta es la 3 pregunta del primer cuestionario', 'Revision', 'Subcat1', 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vtiger_preguntas`
--

CREATE TABLE `vtiger_preguntas` (
  `preguntasid` int(11) default NULL,
  `question` varchar(250) default NULL,
  `categoriapregunta` varchar(100) default NULL,
  `subcategoriapregunta` varchar(100) default NULL,
  `estadopregunta` varchar(100) default NULL,
  `yes` varchar(250) default NULL,
  `no` varchar(250) default NULL,
  `yes_points` float default NULL,
  `no_points` float default NULL,
  `description` varchar(256) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcar la base de datos para la tabla `vtiger_preguntas`
--

INSERT INTO `vtiger_preguntas` (`preguntasid`, `question`, `categoriapregunta`, `subcategoriapregunta`, `estadopregunta`, `yes`, `no`, `yes_points`, `no_points`, `description`) VALUES
(5952, 'Esta es la primera pregunta del primer cuestionario', 'Auditoria', 'Subcat2', 'Requerida', 'Respuesta sí p1', 'Respuesta no p1\r\ncon dos líneas', 8, 4, 'desc p1'),
(5953, 'Esta es la segunda pregunta del primer cuestionario', 'Revision', 'Subcat3', 'Normal', 'Respuesta sí p2', 'Respuesta no p1', 4, 2, 'desc p2'),
(5960, 'Esta es la 3 pregunta del primer cuestionario', 'Revision', 'Subcat1', 'Normal', 'Respuesta sí p3', 'Respuesta no p3', 4, 2, 'desc p3'),
(5961, 'Esta es la 4 pregunta del primer cuestionario', 'Revision', 'Subcat2', 'Normal', 'Respuesta sí p4', 'Respuesta no p4', 4, 2, 'desc p4'),
(5962, 'Esta es la 5 pregunta del primer cuestionario', 'Revision', 'Subcat2', 'Normal', 'Respuesta sí p5', 'Respuesta no p5', 4, 2, 'desc p5'),
(5963, 'Esta es la 6 pregunta del primer cuestionario', 'Categoria', 'Subcat1', 'Normal', 'Respuesta sí p6', 'Respuesta no p6', 4, 2, 'desc p6'),
(5964, 'Esta es la 7 pregunta del primer cuestionario', 'Revision', 'Subcat3', 'Normal', 'Respuesta sí p7', 'Respuesta no p7', 4, 2, 'desc p7'),
(5965, 'Esta es la 8 pregunta del primer cuestionario', 'Categoria', 'Subcat3', 'Normal', 'Respuesta sí p8', 'Respuesta no p8', 4, 2, 'desc p8'),
(5966, 'Esta es la 9 pregunta del primer cuestionario', 'Revision', 'Subcat3', 'Normal', 'Respuesta sí p9', 'Respuesta no p9', 4, 2, 'desc p9'),
(5967, 'Esta es la 9 pregunta del 2 cuestionario', 'Revision', 'Subcat2', 'Normal', 'Respuesta sí p9', 'Respuesta no p9', 4, 2, 'desc p9'),
(5968, 'Esta es la 10 pregunta del primer cuestionario', 'Auditoria', 'Subcat3', 'Requerida', 'Respuesta sí p10', 'Respuesta no p10\r\ncon dos líneas', 8, 4, 'desc p10'),
(5969, 'Esta es la 5 pregunta del 2 cuestionario', 'Auditoria', 'Subcat1', 'Importante', 'Respuesta sí p5', 'Respuesta no p5', 4, 2, 'desc p5');
