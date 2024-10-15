-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 04-10-2024 a las 11:07:17
-- Versión del servidor: 8.0.39-0ubuntu0.22.04.1
-- Versión de PHP: 8.1.2-1ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parking`
--
CREATE DATABASE IF NOT EXISTS `parking` ;
USE `parking`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camara`
--

CREATE TABLE `camara` (
  `idCamara` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `urlConexion` varchar(255) NOT NULL,
  `idZona` int DEFAULT NULL
);

--
-- Volcado de datos para la tabla `camara`
--

INSERT INTO `camara` (`idCamara`, `nombre`, `descripcion`, `urlConexion`, `idZona`) VALUES
(1, 'Cámara Pública Suiza', 'Cámara pública en Solothurn, Suiza', 'http://212.56.215.108:8081/mjpg/video.mjpg', 1),
(2, 'Cámara Zona C', 'Cámara de seguridad en la Zona C', 'http://camarazonaC.example.com/stream', 3),
(3, 'Cámara Zona D', 'Cámara de seguridad en la Zona D', 'http://camarazonaD.example.com/stream', 4),
(4, 'Cámara Zona E', 'Cámara de seguridad en la Zona E', 'http://camarazonaE.example.com/stream', 5),
(5, 'Cámara Zona F', 'Cámara de seguridad en la Zona F', 'http://camarazonaF.example.com/stream', 6),
(6, 'Cámara Zona G', 'Cámara de seguridad en la Zona G', 'http://camarazonaG.example.com/stream', 7),
(7, 'Cámara Zona H', 'Cámara de seguridad en la Zona H', 'http://camarazonaH.example.com/stream', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `edificio`
--

CREATE TABLE `edificio` (
  `idEdificio` int NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `latitud` float DEFAULT NULL,
  `longitud` float DEFAULT NULL,
  `idUsuario` int NOT NULL
);

--
-- Volcado de datos para la tabla `edificio`
--

INSERT INTO `edificio` (`idEdificio`, `nombre`, `descripcion`, `latitud`, `longitud`, `idUsuario`) VALUES
(1, 'Medina Azahara', 'Parking subterráneo creado inagurado en 2020', 37.885, -4.78755, 2),
(2, 'Edificio Norte', 'Edificio de oficinas en el norte de la ciudad', 37.3891, -5.9845, 2),
(3, 'Edificio Sur', 'Edificio comercial en la zona sur', 36.7213, -4.4214, 2),
(4, 'Edificio Este', 'Residencial en la parte este', 40.4168, -3.7038, 2),
(5, 'Edificio Oeste', 'Centro de negocios en la zona oeste', 41.3825, 2.1769, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registrovehiculo`
--

CREATE TABLE `registrovehiculo` (
  `idRegistro` int NOT NULL,
  `matricula` varchar(20) NOT NULL,
  `entrada` varchar(50) NOT NULL,
  `salida` varchar(50) DEFAULT NULL,
  `idZona` int NOT NULL
);

--
-- Volcado de datos para la tabla `registrovehiculo`
--

INSERT INTO `registrovehiculo` (`idRegistro`, `matricula`, `entrada`, `salida`, `idZona`) VALUES
(2, 'CO1523AP', '2024-09-01 18:00', '2024-09-04 17:48', 1),
(3, '1234ABC', '2024-09-02 08:00', NULL, 3),
(4, '5678DEF', '2024-09-02 09:15', NULL, 4),
(5, '9012GHI', '2024-09-01 10:30', NULL, 5),
(6, '3456JKL', '2024-09-02 11:45', NULL, 6),
(7, '7890MNO', '2024-09-02 13:00', NULL, 7),
(8, '1122PQR', '2024-09-02 14:15', NULL, 8),
(9, '111a', '2024-09-01 18:00', '2024-09-03 12:32', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ticket`
--

CREATE TABLE `ticket` (
  `idTicket` int NOT NULL,
  `idRegistro` int NOT NULL,
  `estado` varchar(50) DEFAULT 'pendiente',
  `importe` float NOT NULL DEFAULT '0'
);

--
-- Volcado de datos para la tabla `ticket`
--

INSERT INTO `ticket` (`idTicket`, `idRegistro`, `estado`, `importe`) VALUES
(2, 3, 'pendiente', 0),
(3, 4, 'pendiente', 0),
(4, 5, 'pendiente', 0),
(5, 6, 'pendiente', 0),
(6, 7, 'pendiente', 0),
(7, 8, 'pendiente', 0),
(8, 2, 'pagado', 72),
(9, 9, 'pagado', 43);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `pass` varchar(200) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido1` varchar(100) NOT NULL,
  `apellido2` varchar(100) DEFAULT NULL,
  `rol` varchar(50) NOT NULL,
  `ultimo_acceso` varchar(50) DEFAULT NULL
);

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `email`, `pass`, `nombre`, `apellido1`, `apellido2`, `rol`, `ultimo_acceso`) VALUES
(1, 'pablocastro@gmail.com', '$2y$10$W4DweBzl2/4gBGJQ2NWl2.ACP/MGhhIdsLLo1PBE2ff3tX0VPw59O', 'Pablo', 'Castro', 'Martín', 'cliente', '03/09/2024 19:21'),
(2, 'miguelcastro@gmail.com', '$2y$10$aEuBym3E4fCMk6gGEeFaJ.ZOyUQ1yAgEi/S3jYSQtOw7VS3fUE36e', 'Miguel', 'Castro', 'Martín', 'administrador', '05/09/2024 22:14'),
(3, '', '', 'Sin registrar', '', '', 'cliente', ''),
(4, 'Nan', '$2y$10$2cE9oIW1wARE8F9j0GtO8u2qyf.9OPh2s7wLe.e8qtoE7Fz9UKgGq', '', '', '', '', NULL),
(5, 'juanperez@gmail.com', '', 'Juan', 'Pérez', 'García', 'cliente', '02/09/2024 12:30'),
(6, 'mariarojas@gmail.com', '', 'María', 'Rojas', 'Martínez', 'cliente', '02/09/2024 13:15'),
(7, 'carlossanchez@gmail.com', '', 'Carlos', 'Sánchez', 'Fernández', 'cliente', '02/09/2024 14:00'),
(8, 'anafernandez@gmail.com', '', 'Ana', 'Fernández', 'López', 'cliente', '02/09/2024 15:45'),
(9, 'pedrogomez@gmail.com', '', 'Pedro', 'Gómez', 'Rodríguez', 'cliente', '02/09/2024 16:30'),
(10, 'luciahernandez@gmail.com', '', 'Lucía', 'Hernández', 'Pérez', 'cliente', '02/09/2024 17:20'),
(11, 'javierlopez@gmail.com', '', 'Javier', 'López', 'González', 'cliente', '02/09/2024 18:00'),
(12, 'lauradiaz@gmail.com', '', 'Laura', 'Díaz', 'Ramírez', 'cliente', '02/09/2024 18:45'),
(13, 'franciscomartin@gmail.com', '', 'Francisco', 'Martín', 'Sánchez', 'cliente', '02/09/2024 19:15'),
(14, 'sandraalonso@gmail.com', '', 'Sandra', 'Alonso', 'García', 'cliente', '02/09/2024 19:50'),
(19, 'prueba@gmail.com', '$2y$10$Qac0ZMAcPeoYyXbPeM1Wv.mzixk9xw1/Lykk18DEJWH3QVrKwvnGC', 'nombre', 'apell 1 prueba', 'apell 2 prueba', 'cliente', '03/09/2024 12:32'),
(20, 'prueba2@gmail.com', '$2y$10$jv6rd1KEs8ahzUwhwlx0dO/Fy7nXG0WEutHH7wKvEEk45E9mix4.G', 'Federico Luis', 'García', 'Pedrajas', 'cliente', '02/09/2024 16:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `matricula` varchar(20) NOT NULL,
  `marca` varchar(200) DEFAULT NULL,
  `modelo` varchar(200) DEFAULT NULL,
  `propietario` int DEFAULT NULL
);

--
-- Volcado de datos para la tabla `vehiculo`
--

INSERT INTO `vehiculo` (`matricula`, `marca`, `modelo`, `propietario`) VALUES
('111a', 'marca1', 'modelo1', 19),
('1122PQR', 'Volkswagen', 'Golf', 10),
('1234ABC', 'Toyota', 'Corolla', 5),
('1422GFR', 'Renault', 'Clio', 20),
('222b', 'marca1', 'modelo1', 19),
('3344STU', 'BMW', 'X5', 11),
('3456JKL', 'Chevrolet', 'Cruze', 8),
('4345LNT', 'Ford', 'Kuga', 20),
('5566VWX', 'Mercedes', 'C-Class', 12),
('5678DEF', 'Honda', 'Civic', 6),
('7788YZA', 'Audi', 'A4', 13),
('7890MNO', 'Nissan', 'Sentra', 9),
('8878KVN', 'Ford', 'Kuga', 1),
('9012GHI', 'Ford', 'Focus', 7),
('9900BCD', 'Hyundai', 'Elantra', 14),
('CO1523AP', NULL, NULL, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zona`
--

CREATE TABLE `zona` (
  `idZona` int NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `num_plazas` int NOT NULL,
  `idEdificio` int DEFAULT NULL
);

--
-- Volcado de datos para la tabla `zona`
--

INSERT INTO `zona` (`idZona`, `nombre`, `num_plazas`, `idEdificio`) VALUES
(1, 'Zona A', 27, 1),
(2, 'Zona B', 50, 1),
(3, 'Zona C', 30, 2),
(4, 'Zona D', 25, 2),
(5, 'Zona E', 50, 3),
(6, 'Zona F', 20, 3),
(7, 'Zona G', 45, 4),
(8, 'Zona H', 40, 5);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `camara`
--
ALTER TABLE `camara`
  ADD PRIMARY KEY (`idCamara`),
  ADD KEY `idZona` (`idZona`);

--
-- Indices de la tabla `edificio`
--
ALTER TABLE `edificio`
  ADD PRIMARY KEY (`idEdificio`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `registrovehiculo`
--
ALTER TABLE `registrovehiculo`
  ADD PRIMARY KEY (`idRegistro`),
  ADD KEY `matricula` (`matricula`),
  ADD KEY `fk_idZona` (`idZona`) USING BTREE;

--
-- Indices de la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`idTicket`),
  ADD KEY `idRegistro` (`idRegistro`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`matricula`),
  ADD KEY `propietario` (`propietario`);

--
-- Indices de la tabla `zona`
--
ALTER TABLE `zona`
  ADD PRIMARY KEY (`idZona`),
  ADD KEY `idEdificio` (`idEdificio`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `camara`
--
ALTER TABLE `camara`
  MODIFY `idCamara` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `edificio`
--
ALTER TABLE `edificio`
  MODIFY `idEdificio` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registrovehiculo`
--
ALTER TABLE `registrovehiculo`
  MODIFY `idRegistro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ticket`
--
ALTER TABLE `ticket`
  MODIFY `idTicket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `zona`
--
ALTER TABLE `zona`
  MODIFY `idZona` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `camara`
--
ALTER TABLE `camara`
  ADD CONSTRAINT `camara_ibfk_1` FOREIGN KEY (`idZona`) REFERENCES `zona` (`idZona`) ON DELETE CASCADE;

--
-- Filtros para la tabla `edificio`
--
ALTER TABLE `edificio`
  ADD CONSTRAINT `edificio_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `registrovehiculo`
--
ALTER TABLE `registrovehiculo`
  ADD CONSTRAINT `fk_idZona` FOREIGN KEY (`idZona`) REFERENCES `zona` (`idZona`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `registrovehiculo_ibfk_1` FOREIGN KEY (`matricula`) REFERENCES `vehiculo` (`matricula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ticket`
--
ALTER TABLE `ticket`
  ADD CONSTRAINT `ticket_ibfk_1` FOREIGN KEY (`idRegistro`) REFERENCES `registrovehiculo` (`idRegistro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `vehiculo_ibfk_1` FOREIGN KEY (`propietario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `zona`
--
ALTER TABLE `zona`
  ADD CONSTRAINT `zona_ibfk_1` FOREIGN KEY (`idEdificio`) REFERENCES `edificio` (`idEdificio`) ON DELETE CASCADE ON UPDATE CASCADE;
