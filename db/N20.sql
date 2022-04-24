-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 08-04-2022 a las 01:14:09
-- Versión del servidor: 8.0.18
-- Versión de PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `secure_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

CREATE TABLE `articulo` (
  `id_articulo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `texto` text COLLATE utf8_bin NOT NULL,
  `publico` varchar(2) COLLATE utf8_bin NOT NULL,
  `fecha_publi` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `articulo`
--

INSERT INTO `articulo` (`id_articulo`, `id_usuario`, `texto`, `publico`, `fecha_publi`) VALUES
(1, 1, 'hola!', 'SI', '2022-04-02 05:00:00'),
(3, 2, 'prueba de arley.', 'SI', '2022-04-04 04:03:29'),
(4, 1, 'Prueba de Jose..', 'NO', '2022-04-04 04:10:58'),
(5, 1, 'cundinamarca', 'SI', '2022-04-04 23:09:24'),
(6, 1, 'prueba 12345', 'NO', '2022-04-05 00:22:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensaje`
--

CREATE TABLE `mensaje` (
  `id_mensaje` int(11) NOT NULL,
  `id_remite` int(11) NOT NULL,
  `id_destino` int(11) NOT NULL,
  `texto` text COLLATE utf8_bin NOT NULL,
  `archivo` text CHARACTER SET utf8 COLLATE utf8_bin,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `mensaje`
--

INSERT INTO `mensaje` (`id_mensaje`, `id_remite`, `id_destino`, `texto`, `archivo`, `fecha`) VALUES
(1, 2, 1, 'Hola jose', '', '2022-04-04 17:24:18'),
(2, 2, 1, 'ola k hace', '', '2022-04-04 17:58:24'),
(3, 11, 1, 'hhhhh', '', '2022-04-06 04:16:07'),
(4, 1, 2, 'holaaaaaaaaa', './archivos/0a7391347397727a9df25a53d6f04d1e.pdf', '2022-04-07 02:25:32'),
(5, 2, 11, 'ototototot', '', '2022-04-07 02:35:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_bin NOT NULL,
  `apellido` varchar(255) COLLATE utf8_bin NOT NULL,
  `correo` varchar(255) COLLATE utf8_bin NOT NULL,
  `direccion` varchar(255) COLLATE utf8_bin NOT NULL,
  `num_hijos` varchar(255) COLLATE utf8_bin NOT NULL,
  `estado_civil` varchar(255) COLLATE utf8_bin NOT NULL,
  `foto` text CHARACTER SET utf8 COLLATE utf8_bin,
  `usuario` varchar(255) COLLATE utf8_bin NOT NULL,
  `clave` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `apellido`, `correo`, `direccion`, `num_hijos`, `estado_civil`, `foto`, `usuario`, `clave`) VALUES
(1, 'jose', 'Munoz', 'jose@gmail.com', 'calle otra', '3', 'Otro', 'img/$2y$10$VWkUoSLos1e5zOdUe3BO0.8JhrTkC2VS6SWMXJRUDKb3AvVp.B46K.jpg', 'jose', '$2y$10$claTsBiFFSTzgchi5ohjUu4AanN78tPHBc2dm9pOb5yANdBAKtT1i'),
(2, 'Arley', 'Lara', 'jojo@gmail.com', 'carre1 123 # 34 21', '3', 'Soltero', './img/573cb01c8c07b81780037296654d694a.jpg', 'Arley94', '$2y$10$aPv.VoELHeuvgKxIzceAeuP16SakEnKLjmvgj.TNVyuTAKUnHUwiO'),
(3, 'Ricardo', 'Trivino', 'rtr@soy.com', 'lala', '00000', 'Soltero', 'img/$2y$10$lzTXic9FtZXuBRpL8.MVCOAEvkv/A.y9kZT3stQHewkkH.cVr3pm2.jpg', 'rtr', '$2y$10$od7dtyaswEB4roAj3/R7c..9mWhkfW3g9Bje9Cg/Pk/JTFImYJ3J6'),
(4, 'cr', 'de', 'rd@dl.co', 'fdl', '0', 'Soltero', 'img/$2y$10$bjjEtJFum9mHfw2c3m0IfuzIbFMBL30Rfc1uk3rKQtRuETvVQm2YO.jpg', 'cr', '$2y$10$D0IgXiuY2GfGWIDpoj0L8uyWnvfr7EaNujyaycedVE898BF1jE1jm'),
(5, 'pepe', 'Gomez', 'pepe@gmail.com', 'calle 1233', '1', 'Soltero', 'img/$2y$10$hH0S5kiG0PCeuObDBooBOuYwJ7F5D5jN8zHNL86ekPRxvS6.O0IPi.jpg', 'Pepe', '$2y$10$dAreS9TBJIYQnWWsTyJVhejUqnKi6IsXKeEEca/8dV8NM0RhiAg8G'),
(11, 'Oto', 'serge', 'oto@gmail.com', 'carre1 123 # 34 21', '9', 'Soltero', './img/f4652ab02b7f1196276bd75f59172a5d.jpg', 'Oto', '$2y$10$38clfXp0CsKVK6DVxRHqTOtTc4baG793P14SANOIfbFBkg.O.Rctq');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD PRIMARY KEY (`id_articulo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_remite` (`id_remite`),
  ADD KEY `id_destino` (`id_destino`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `articulo`
--
ALTER TABLE `articulo`
  MODIFY `id_articulo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `mensaje`
--
ALTER TABLE `mensaje`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT `articulo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `mensaje`
--
ALTER TABLE `mensaje`
  ADD CONSTRAINT `mensaje_ibfk_1` FOREIGN KEY (`id_remite`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `mensaje_ibfk_2` FOREIGN KEY (`id_destino`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
