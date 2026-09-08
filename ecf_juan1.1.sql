-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 08-09-2026 a las 08:51:22
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ecf_juan`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `absences`
--

DROP TABLE IF EXISTS `absences`;
CREATE TABLE IF NOT EXISTS `absences` (
  `absence_id` int NOT NULL AUTO_INCREMENT,
  `absence_date` date NOT NULL,
  `reason` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `justification_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `trainee_id` int NOT NULL,
  PRIMARY KEY (`absence_id`),
  KEY `trainee_id` (`trainee_id`)
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admins`
--

INSERT INTO `admins` (`admin_id`, `login`, `password_hash`) VALUES
(1, 'ADMINAFPA', '$2y$10$F/v7xcc5JZ.eQwictAP/G.mJNq2oHrGlDuRm3MekzWdVA3v.cT9mm');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trainees`
--

DROP TABLE IF EXISTS `trainees`;
CREATE TABLE IF NOT EXISTS `trainees` (
  `trainee_id` int NOT NULL AUTO_INCREMENT,
  `afpa_id` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `personal_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `professional_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `professional_email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `residence` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`trainee_id`),
  UNIQUE KEY `afpa_id` (`afpa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trainees`
--

INSERT INTO `trainees` (`trainee_id`, `afpa_id`, `first_name`, `last_name`, `personal_email`, `phone`, `professional_url`, `professional_email`, `residence`, `birth_date`, `photo_path`) VALUES
(1, '22116576', 'Adila', 'Kehlaoui', 'adi.kehlaoui@gmail.com', '0645557195', 'https://adila-k.fr/index.php', 'hello@adila-k.fr', 'Bordeaux', '1990-12-18', NULL),
(2, '26020093', 'Mohammed', 'Benerroua', 'benerrouamohammed@gmail.com', '0767250170', 'https://mohammed-benerroua.fr', NULL, 'Bordeaux', '1990-04-01', NULL),
(3, '26020095', 'Ghislène', 'Bellia', 'ghislenebellia@gmail.com', '0662877894', NULL, NULL, NULL, '2005-08-25', NULL),
(4, '26020096', 'Aurèle', 'Camps', 'campsaurele@gmail.com', '0668368996', 'https://campsa.fr/', 'contact@campsa.fr', NULL, '1995-11-26', NULL),
(5, '26020097', 'Nelly', 'Fabre', 'nelly.fabre@hotmail.fr', '0627154096', 'https://nelly-fabre.fr/', 'contact@nelly-fabre.fr', 'Cussac Fort Médoc', '1983-10-02', NULL),
(6, '26020141', 'Sarah', 'Casabianca', 'sarah.casabianca@gmail.com', '0683049749', NULL, NULL, NULL, '1996-06-10', NULL),
(7, '26020143', 'Juan', 'Rojas Cuicas', 'rjuan3683@gmail.com', '0635902566', 'https://juanrojas.fr/', 'hello@juanrojas.fr', 'Bordeaux', '2000-08-04', NULL),
(8, '26020156', 'Lucas', 'Merlet', 'merletlucas2@gmail.com', '0788697051', NULL, 'contact@lucas-merlet.fr', NULL, '2002-09-12', NULL),
(9, '26020263', 'Faten', 'Bannani', 'belmahriafatenn@gmail.com', '0602568388', NULL, NULL, NULL, '1997-05-04', NULL),
(10, '26020268', 'Nathanael', 'Kenzey', 'nathanael.kenzey@gmail.com', '0745165819', 'https://nathanaelk.fr', 'contact@nathanaelk.fr', 'Paris', '1998-10-22', NULL),
(11, '26020916', 'Anthony', 'Lutard', 'anthony.lutard33@gmail.com', '0750862760', NULL, NULL, NULL, '2003-12-03', NULL),
(12, '26028145', 'Mélanie', 'Saez', 'emel.saez@gmail.com', '0760227763', NULL, NULL, NULL, '1986-02-16', NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `absences`
--
ALTER TABLE `absences`
  ADD CONSTRAINT `absences_ibfk_1` FOREIGN KEY (`trainee_id`) REFERENCES `trainees` (`trainee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
