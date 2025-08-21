-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 21 août 2025 à 09:26
-- Version du serveur : 8.0.43-0ubuntu0.24.04.1
-- Version de PHP : 8.3.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sgcm_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `agents`
--

CREATE TABLE `agents` (
  `id` bigint UNSIGNED NOT NULL,
  `mairie_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'recouvrement',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `type_piece` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_piece` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxe_id` json DEFAULT NULL,
  `secteur_id` json DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `agents`
--

INSERT INTO `agents` (`id`, `mairie_id`, `name`, `type`, `email`, `email_verified_at`, `password`, `otp_code`, `otp_expires_at`, `status`, `type_piece`, `numero_piece`, `genre`, `date_naissance`, `adresse`, `telephone1`, `telephone2`, `taxe_id`, `secteur_id`, `remember_token`, `last_activity`, `created_at`, `updated_at`) VALUES
(1, 1, 'agent soro', 'recenssement', 'soroddavid63@gmail.com', NULL, '$2y$12$aD7uw.FkkKwBlJuwfWasHeOA7PxTXrt.EqDfdCOp3yCkEjigChN5C', NULL, NULL, 'active', 'cni', 'ZGFR2567', 'masculin', '2000-02-10', 'Abobo', '088918712', '0106857565', '[\"1\"]', '[\"1\"]', 'jq8WBeLLlEUx2sCVcCl2gIEBZs7pjNvl5FVsqby6C9nbhhdNIvR9pnwV9FkO', NULL, '2025-08-13 10:49:10', '2025-08-13 10:57:48'),
(2, 1, 'agent 2', 'recouvrement', 'sorodavi3@zohomail.com', NULL, '$2y$12$Tkp8esDMIrY2FYQxTvO0Ae3Z/SKAA4HDRbFlfrIjZwtMEJWzWNCUm', NULL, NULL, 'active', 'cni', '3IJ3YU3', 'masculin', '0004-02-10', 'ABo', '088918712', NULL, '[\"1\"]', '[\"1\"]', 'CrdF2H8twGRXyxK4Ti43EPvICrMWNHI3vMYoNOXjN9rPc2umlYgbNgavyGbE', '2025-08-20 16:41:02', '2025-08-13 11:11:45', '2025-08-20 16:41:02');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commercants`
--

CREATE TABLE `commercants` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_commerce` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_piece` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_piece` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autre_type_piece` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_profil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_recto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_verso` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `autre_images` json DEFAULT NULL,
  `agent_id` bigint UNSIGNED NOT NULL,
  `mairie_id` bigint UNSIGNED NOT NULL,
  `secteur_id` bigint UNSIGNED NOT NULL,
  `type_contribuable_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commercants`
--

INSERT INTO `commercants` (`id`, `nom`, `email`, `telephone`, `adresse`, `num_commerce`, `password`, `type_piece`, `numero_piece`, `autre_type_piece`, `photo_profil`, `photo_recto`, `photo_verso`, `qr_code_path`, `autre_images`, `agent_id`, `mairie_id`, `secteur_id`, `type_contribuable_id`, `created_at`, `updated_at`) VALUES
(2, 'Elisée', 'elisee@gmail.com', '0769502967', 'Cocody', 'MAIR0001', '$2y$12$aD7uw.FkkKwBlJuwfWasHeOA7PxTXrt.EqDfdCOp3yCkEjigChN5C', 'cni', '68656GH', NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, '2024-06-13 11:01:58', '2024-06-13 11:01:58'),
(8, 'Soro David', 'ab@gmail.com', '0769502967', 'Marcory', 'MAIR0005', '$2y$12$aD7uw.FkkKwBlJuwfWasHeOA7PxTXrt.EqDfdCOp3yCkEjigChN5C', 'cni', '67242FG265ER', NULL, NULL, NULL, NULL, 'commercants/qrcodes/commercant_8_1755512142.png', NULL, 1, 1, 1, 1, '2025-01-18 10:15:42', '2025-01-18 10:15:42'),
(9, 'Soro David', 'soroddavid6@gmail.com', '07675426665', 'Cocody', 'MAIR0006', '$2y$12$aD7uw.FkkKwBlJuwfWasHeOA7PxTXrt.EqDfdCOp3yCkEjigChN5C', 'cni', 'CI87777', NULL, 'commercants/profils/W4G4A64x2N6fsTSTS73eVFpVaGSFvkDYpPigwCsm.jpg', 'commercants/recto/fCOcVHLI09I2JXh0Z4N0rkI9TnjvNd1EZDIRdx9A.jpg', 'commercants/verso/rspZOIZwNl4Kuw86g8UrNfS5LEvppWjLnnT9FllM.png', 'commercants/qrcodes/commercant_9_1755526575.png', NULL, 1, 1, 1, 1, '2025-04-18 14:16:15', '2025-04-18 14:16:15'),
(10, 'kouadio paul', 'paul@gmail.com', '098888766', 'Cocody', 'MAIR0007', '$2y$12$aD7uw.FkkKwBlJuwfWasHeOA7PxTXrt.EqDfdCOp3yCkEjigChN5C', 'attestation', 'G766', NULL, NULL, NULL, NULL, 'commercants/qrcodes/commercant_10_1755526743.png', NULL, 1, 1, 1, 1, '2024-08-18 14:19:03', '2024-08-18 14:19:03');

-- --------------------------------------------------------

--
-- Structure de la table `commercant_taxe`
--

CREATE TABLE `commercant_taxe` (
  `id` bigint UNSIGNED NOT NULL,
  `commercant_id` bigint UNSIGNED NOT NULL,
  `taxe_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commercant_taxe`
--

INSERT INTO `commercant_taxe` (`id`, `commercant_id`, `taxe_id`, `created_at`, `updated_at`) VALUES
(1, 8, 1, NULL, NULL),
(2, 9, 1, NULL, NULL),
(3, 10, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `communes`
--

CREATE TABLE `communes` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `communes`
--

INSERT INTO `communes` (`id`, `nom`, `region`, `created_at`, `updated_at`) VALUES
(1, 'Abengourou', 'Indénié-Djuablin', '2025-08-13 10:29:01', '2025-08-13 10:29:01'),
(2, 'Aboisso', 'Sud-Comoé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(3, 'Adiaké', 'Sud-Comoé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(4, 'Adzopé', 'La Mé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(5, 'Agboville', 'Agnéby-Tiassa', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(6, 'Agnibilékrou', 'Indénié-Djuablin', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(7, 'Akoupé', 'La Mé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(8, 'Alépé', 'La Mé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(9, 'Anyama', 'Abidjan', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(10, 'Arrah', 'Moronou', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(11, 'Assinie', 'Sud-Comoé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(12, 'Attécoubé', 'Abidjan', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(13, 'Attobrou', 'Agnéby-Tiassa', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(14, 'Ayamé', 'Sud-Comoé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(15, 'Azaguié', 'Agnéby-Tiassa', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(16, 'Bako', 'Bafing', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(17, 'Bangolo', 'Guémon', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(18, 'Bassawa', 'Hambol', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(19, 'Bédiala', 'Haut-Sassandra', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(20, 'Béoumi', 'Gbêkê', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(21, 'Béttié', 'Indénié-Djuablin', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(22, 'Biankouma', 'Tonkpi', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(23, 'Bingerville', 'Abidjan', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(24, 'Bin-Houyé', 'Tonkpi', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(25, 'Bloléquin', 'Cavally', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(26, 'Bocanda', 'N\'Zi', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(27, 'Bodokro', 'Gbêkê', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(28, 'Bondoukou', 'Gontougo', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(29, 'Bongouanou', 'Moronou', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(30, 'Boniérédougou', 'Tchologo', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(31, 'Bonon', 'Marahoué', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(32, 'Bonoua', 'Sud-Comoé', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(33, 'Booko', 'Kabadougou', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(34, 'Borotou', 'Bafing', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(35, 'Botro', 'Gbêkê', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(36, 'Bouaflé', 'Marahoué', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(37, 'Bouaké', 'Gbêkê', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(38, 'Bouna', 'Bounkani', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(39, 'Boundiali', 'Bagoué', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(40, 'Brobo', 'Gbêkê', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(41, 'Buyo', 'Nawa', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(42, 'Cocody', 'Abidjan', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(43, 'Dabakala', 'Hambol', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(44, 'Dabou', 'Grands-Ponts', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(45, 'Daloa', 'Haut-Sassandra', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(46, 'Danané', 'Tonkpi', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(47, 'Daoukro', 'Iffou', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(48, 'Diabo', 'Gbêkê', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(49, 'Dianra', 'Béré', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(50, 'Diawala', 'Tchologo', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(51, 'Didiévi', 'Bélier', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(52, 'Diégonéfla', 'Gôh', '2025-08-13 10:29:02', '2025-08-13 10:29:02'),
(53, 'Dikodougou', 'Poro', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(54, 'Dimbokro', 'N\'Zi', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(55, 'Divo', 'Lôh-Djiboua', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(56, 'Djebonoua', 'Gbêkê', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(57, 'Djèkanou', 'Bélier', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(58, 'Djouroutou', 'San-Pédro', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(59, 'Doropo', 'Bounkani', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(60, 'Duékoué', 'Guémon', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(61, 'Ferkessédougou', 'Tchologo', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(62, 'Fresco', 'Gbôklè', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(63, 'Gagnoa', 'Gôh', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(64, 'Gbeleban', 'Kabadougou', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(65, 'Gbon', 'Bagoué', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(66, 'Gohitafla', 'Marahoué', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(67, 'Goulia', 'Folon', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(68, 'Grand-Bassam', 'Sud-Comoé', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(69, 'Grand-Béréby', 'San-Pédro', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(70, 'Grand-Lahou', 'Grands-Ponts', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(71, 'Grand-Zattry', 'Nawa', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(72, 'Guéyo', 'Nawa', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(73, 'Guibéroua', 'Gôh', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(74, 'Guiembé', 'Poro', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(75, 'Guiglo', 'Cavally', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(76, 'Guintéguéla', 'Bafing', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(77, 'Issia', 'Haut-Sassandra', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(78, 'Jacqueville', 'Grands-Ponts', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(79, 'Kani', 'Worodougou', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(80, 'Kaniasso', 'Folon', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(81, 'Karakoro', 'Poro', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(82, 'Katiola', 'Hambol', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(83, 'Kokoumbo', 'Bélier', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(84, 'Kolian', 'Bagoué', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(85, 'Komborodougou', 'Poro', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(86, 'Kong', 'Tchologo', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(87, 'Koonan', 'Bafing', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(88, 'Korhogo', 'Poro', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(89, 'Koro', 'Bafing', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(90, 'Kouassi-Datékro', 'Gontougo', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(91, 'Kouassi-Kouassikro', 'N\'Zi', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(92, 'Kouibly', 'Guémon', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(93, 'Koumassi', 'Abidjan', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(94, 'Koun-Fao', 'Gontougo', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(95, 'Kounahiri', 'Béré', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(96, 'Kouto', 'Bagoué', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(97, 'Lakota', 'Lôh-Djiboua', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(98, 'Logoualé', 'Tonkpi', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(99, 'Madinani', 'Kabadougou', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(100, 'Maféré', 'Sud-Comoé', '2025-08-13 10:29:03', '2025-08-13 10:29:03'),
(101, 'Man', 'Tonkpi', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(102, 'Mankono', 'Béré', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(103, 'Marcory', 'Abidjan', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(104, 'Massala', 'Worodougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(105, 'Mayo', 'Nawa', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(106, 'M\'bahiakro', 'Iffou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(107, 'M\'batto', 'Moronou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(108, 'M\'bengué', 'Poro', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(109, 'Méagui', 'Nawa', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(110, 'Minignan', 'Folon', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(111, 'Morondo', 'Worodougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(112, 'Nafana', 'Poro', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(113, 'Nassian', 'Bounkani', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(114, 'N\'douci', 'Agnéby-Tiassa', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(115, 'Niablé', 'Indénié-Djuablin', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(116, 'Niakaramandougou', 'Hambol', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(117, 'Niofoin', 'Poro', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(118, 'Odienné', 'Kabadougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(119, 'Ouaninou', 'Bafing', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(120, 'Ouellé', 'Iffou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(121, 'Oumé', 'Gôh', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(122, 'Ouragahio', 'Gôh', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(123, 'Plateau', 'Abidjan', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(124, 'Port-Bouët', 'Abidjan', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(125, 'Prikro', 'Iffou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(126, 'Rubino', 'Agnéby-Tiassa', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(127, 'Sakassou', 'Gbêkê', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(128, 'Samatiguila', 'Kabadougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(129, 'San-Pédro', 'San-Pédro', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(130, 'Sandégué', 'Gontougo', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(131, 'Sangouiné', 'Tonkpi', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(132, 'Sassandra', 'Gbôklè', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(133, 'Satama-Sokoro', 'Hambol', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(134, 'Satama-Sokoura', 'Hambol', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(135, 'Séguéla', 'Worodougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(136, 'Séguelon', 'Kabadougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(137, 'Seydougou', 'Kabadougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(138, 'Sifié', 'Worodougou', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(139, 'Sikensi', 'Agnéby-Tiassa', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(140, 'Sinématiali', 'Poro', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(141, 'Sinfra', 'Marahoué', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(142, 'Sipilou', 'Tonkpi', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(143, 'Soubré', 'Nawa', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(144, 'Songon', 'Abidjan', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(145, 'Taabo', 'Agnéby-Tiassa', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(146, 'Tabou', 'San-Pédro', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(147, 'Tafiré', 'Hambol', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(148, 'Taï', 'Cavally', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(149, 'Tanda', 'Gontougo', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(150, 'Téhini', 'Bounkani', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(151, 'Tengréla', 'Bagoué', '2025-08-13 10:29:04', '2025-08-13 10:29:04'),
(152, 'Tiaporé', 'Sud-Comoé', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(153, 'Tiassalé', 'Agnéby-Tiassa', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(154, 'Tiédio', 'Bagoué', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(155, 'Tiénigboué', 'Gbêkê', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(156, 'Tioroniaradougou', 'Poro', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(157, 'Tiébissou', 'Bélier', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(158, 'Touba', 'Bafing', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(159, 'Toulépleu', 'Cavally', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(160, 'Toumodi', 'Bélier', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(161, 'Treichville', 'Abidjan', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(162, 'Transua', 'Gontougo', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(163, 'Vavoua', 'Haut-Sassandra', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(164, 'Yamoussoukro', 'Yamoussoukro', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(165, 'Yopougon', 'Abidjan', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(166, 'Zikisso', 'Lôh-Djiboua', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(167, 'Zouan-Hounien', 'Tonkpi', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(168, 'Zoukougbeu', 'Haut-Sassandra', '2025-08-13 10:29:05', '2025-08-13 10:29:05'),
(169, 'Zuénoula', 'Marahoué', '2025-08-13 10:29:05', '2025-08-13 10:29:05');

-- --------------------------------------------------------

--
-- Structure de la table `encaissements`
--

CREATE TABLE `encaissements` (
  `id` bigint UNSIGNED NOT NULL,
  `taxe_id` bigint UNSIGNED NOT NULL,
  `agent_id` bigint UNSIGNED NOT NULL,
  `mairie_id` bigint UNSIGNED NOT NULL,
  `num_commerce` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant_percu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'non versé',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `financiers`
--

CREATE TABLE `financiers` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commune` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `mot_de_passe` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mairies`
--

CREATE TABLE `mairies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `type_piece` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_piece` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `commune` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mairies`
--

INSERT INTO `mairies` (`id`, `name`, `genre`, `date_naissance`, `type_piece`, `numero_piece`, `adresse`, `telephone1`, `telephone2`, `region`, `commune`, `role`, `email`, `email_verified_at`, `password`, `otp_code`, `otp_expires_at`, `status`, `remember_token`, `last_activity`, `created_at`, `updated_at`) VALUES
(1, 'mairie cocody', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Abidjan', '42', 'admin', 'soroddavid63@gmail.com', NULL, '$2y$12$m17U.u8.Pd4xF6gxMCDNd.RngecdSQh02tNkTr60xW2gs4nmz5ity', NULL, NULL, 'active', NULL, '2025-08-19 13:09:29', '2025-08-13 10:32:24', '2025-08-19 13:09:29'),
(2, 'Soro David', 'masculin', '1999-10-10', 'cni', '236532JH', 'Abobo', '088918712', NULL, 'Abidjan', '42', 'financié', 'sorodavi3@zohomail.com', NULL, '$2y$12$WsD0u4oZI5x.4qkrMMBGIeSe2L7BJkSrtYSwtaHbUw14S5Y2Un3n2', NULL, NULL, 'active', NULL, '2025-08-21 09:01:17', '2025-08-13 10:36:23', '2025-08-21 09:01:17'),
(3, 'Soro David', 'masculin', '1999-10-10', 'cni', '236532JH', 'Abobo', '088918712', NULL, 'Abidjan', '42', 'caisse', 'sorodavid3@zohomail.com', NULL, '$2y$12$WsD0u4oZI5x.4qkrMMBGIeSe2L7BJkSrtYSwtaHbUw14S5Y2Un3n2', NULL, NULL, 'active', NULL, '2025-08-20 09:43:51', '2025-08-13 10:36:23', '2025-08-20 09:43:51');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_07_02_091705_create_mairies_table', 1),
(5, '2025_07_02_121350_create_communes_table', 1),
(6, '2025_07_08_114040_create_agents_table', 1),
(7, '2025_07_10_104410_create_taxes_table', 1),
(8, '2025_07_10_121615_create_secteurs_table', 1),
(9, '2025_07_21_134429_create_commercants_table', 1),
(10, '2025_07_23_092715_create_personal_access_tokens_table', 1),
(11, '2025_07_28_110428_create_versements_table', 1),
(12, '2025_07_28_113011_create_encaissements_table', 1),
(13, '2025_07_30_161636_create_payement_taxes_table', 1),
(14, '2025_08_04_171010_create_financiers_table', 1),
(15, '2025_08_06_110257_create_commercant_taxe_table', 1),
(16, '2025_08_12_140609_create_type_contribuables_table', 1),
(17, '2025_08_18_114637_add_recette_effecture_to_payement_taxes_table', 2),
(18, '2025_08_18_115222_add_recette_effectuee_to_paiement_taxes_table', 3),
(19, '2025_08_19_081538_create_user_logs_table', 4),
(20, '2025_08_19_083300_add_last_activity_to_agents_and_mairies', 4);

-- --------------------------------------------------------

--
-- Structure de la table `paiement_taxes`
--

CREATE TABLE `paiement_taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `secteur_id` int DEFAULT NULL,
  `agent_id` bigint UNSIGNED DEFAULT NULL,
  `taxe_id` bigint UNSIGNED NOT NULL,
  `mairie_id` bigint UNSIGNED NOT NULL,
  `num_commerce` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'payé',
  `periode` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `recette_effectuee` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiement_taxes`
--

INSERT INTO `paiement_taxes` (`id`, `secteur_id`, `agent_id`, `taxe_id`, `mairie_id`, `num_commerce`, `montant`, `statut`, `periode`, `created_at`, `updated_at`, `recette_effectuee`) VALUES
(1, 1, NULL, 1, 2, 'MAIR0005', '5000.00', 'payé', '2025-01-18 00:00:00', '2025-08-18 10:55:04', '2025-08-18 10:55:04', 0),
(2, 1, NULL, 1, 1, 'MAIR0007', '5000.00', 'payé', '2024-08-18 00:00:00', '2025-08-18 14:22:35', '2025-08-18 14:22:35', 0),
(3, 1, NULL, 1, 1, 'MAIR0007', '5000.00', 'payé', '2024-09-18 00:00:00', '2025-08-18 14:22:35', '2025-08-18 14:22:35', 0),
(4, 1, NULL, 1, 1, 'MAIR0007', '5000.00', 'payé', '2024-10-18 00:00:00', '2025-08-18 14:22:35', '2025-08-18 14:22:35', 0),
(5, 1, NULL, 1, 1, 'MAIR0007', '5000.00', 'payé', '2024-11-18 00:00:00', '2025-08-18 14:22:35', '2025-08-18 14:22:35', 0),
(6, 1, NULL, 1, 1, 'MAIR0007', '5000.00', 'payé', '2024-12-18 00:00:00', '2025-08-18 14:22:35', '2025-08-18 14:22:35', 0),
(7, 1, NULL, 1, 1, 'MAIR0007', '5000.00', 'payé', '2025-01-18 00:00:00', '2025-08-18 14:22:35', '2025-08-18 14:22:35', 0),
(8, 1, NULL, 1, 1, 'MAIR0007', '5000.00', 'payé', '2025-02-18 00:00:00', '2025-08-18 14:22:35', '2025-08-18 14:22:35', 0),
(9, 1, NULL, 1, 1, 'MAIR0006', '5000.00', 'payé', '2025-04-18 00:00:00', '2025-08-18 14:23:17', '2025-08-18 14:23:17', 0),
(10, 1, NULL, 1, 1, 'MAIR0006', '5000.00', 'payé', '2025-05-18 00:00:00', '2025-08-18 14:23:17', '2025-08-18 14:23:17', 0),
(11, 1, NULL, 1, 1, 'MAIR0006', '5000.00', 'payé', '2025-06-18 00:00:00', '2025-08-18 14:23:17', '2025-08-18 14:23:17', 0),
(12, 1, NULL, 1, 1, 'MAIR0006', '5000.00', 'payé', '2025-07-18 00:00:00', '2025-08-18 14:23:17', '2025-08-18 14:23:17', 0),
(13, 1, NULL, 1, 1, 'MAIR0006', '5000.00', 'payé', '2025-08-18 00:00:00', '2025-08-18 14:23:17', '2025-08-18 14:23:17', 0),
(14, 1, NULL, 1, 1, 'MAIR0006', '5000.00', 'payé', '2025-09-18 00:00:00', '2025-08-18 14:23:17', '2025-08-18 14:23:17', 0),
(15, 1, NULL, 1, 1, 'MAIR0006', '5000.00', 'payé', '2025-10-18 00:00:00', '2025-08-18 14:23:17', '2025-08-18 14:23:17', 0);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `secteurs`
--

CREATE TABLE `secteurs` (
  `id` bigint UNSIGNED NOT NULL,
  `mairie_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `secteurs`
--

INSERT INTO `secteurs` (`id`, `mairie_id`, `code`, `nom`, `created_at`, `updated_at`) VALUES
(1, 2, '-ANG-001', 'Angre', '2025-08-13 10:38:28', '2025-08-13 10:38:28'),
(2, 2, '-PAL-002', 'Palmeraie', '2025-08-13 10:38:39', '2025-08-13 10:38:39'),
(3, 2, '-ANO-003', 'Anono', '2025-08-13 10:38:48', '2025-08-13 10:38:48');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ztOS94yLSHgQXbbmVqZL1lpqUqRDz3V5uTYR5cw1', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicDE0eXNWVnVPQndYQktwZ0ptaXBrNjQzbVEySG96UTJEV0tabWI2RCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODA4Mi9sb2dpbi1tYWlyaWUiO319', 1755767461);

-- --------------------------------------------------------

--
-- Structure de la table `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint UNSIGNED NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `montant` decimal(10,2) DEFAULT NULL,
  `frequence` enum('jour','mois','an') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mois',
  `mairie_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `taxes`
--

INSERT INTO `taxes` (`id`, `nom`, `description`, `montant`, `frequence`, `mairie_id`, `created_at`, `updated_at`) VALUES
(1, 'Taxe d\'habitation', 'Description', 5000.00, 'mois', 2, '2025-08-13 10:40:16', '2025-08-13 10:40:16'),
(2, 'Taxe pub', 'Taxe pub', 3000.00, 'an', 2, '2025-08-13 10:40:55', '2025-08-13 10:40:55');

-- --------------------------------------------------------

--
-- Structure de la table `type_contribuables`
--

CREATE TABLE `type_contribuables` (
  `id` bigint UNSIGNED NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_id` bigint UNSIGNED NOT NULL,
  `mairie_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_contribuables`
--

INSERT INTO `type_contribuables` (`id`, `libelle`, `agent_id`, `mairie_id`, `created_at`, `updated_at`) VALUES
(1, 'Habitation', 1, 1, '2025-08-13 11:00:33', '2025-08-13 11:00:33');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'super admin', 'superadmin', 'superadmin@gmail.com', NULL, '$2y$12$83xgblKmoMM1hmRVE2Wgner7p0ZQUQCVSlvEVFmrbtzK6CrwYGYyC', NULL, '2025-08-13 10:31:19', '2025-08-13 10:31:19');

-- --------------------------------------------------------

--
-- Structure de la table `user_logs`
--

CREATE TABLE `user_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `user_type`, `event`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 2, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 08:41:00', '2025-08-19 08:41:00'),
(2, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 08:43:34', '2025-08-19 08:43:34'),
(3, 2, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 08:43:49', '2025-08-19 08:43:49'),
(4, 1, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 08:43:53', '2025-08-19 08:43:53'),
(5, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 09:11:39', '2025-08-19 09:11:39'),
(6, 2, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 09:12:24', '2025-08-19 09:12:24'),
(7, 2, 'App\\Models\\Agent', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 09:12:41', '2025-08-19 09:12:41'),
(8, 2, 'App\\Models\\Agent', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 09:33:01', '2025-08-19 09:33:01'),
(9, 2, 'App\\Models\\Agent', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 09:55:07', '2025-08-19 09:55:07'),
(10, 1, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 10:52:52', '2025-08-19 10:52:52'),
(11, 1, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 10:52:56', '2025-08-19 10:52:56'),
(12, 2, 'App\\Models\\Agent', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 10:53:34', '2025-08-19 10:53:34'),
(13, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 10:53:57', '2025-08-19 10:53:57'),
(14, 2, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:11:47', '2025-08-19 11:11:47'),
(15, 1, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:11:59', '2025-08-19 11:11:59'),
(16, 1, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:12:31', '2025-08-19 11:12:31'),
(17, 1, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:12:31', '2025-08-19 11:12:31'),
(18, 1, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:12:48', '2025-08-19 11:12:48'),
(19, 2, 'App\\Models\\Agent', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:12:59', '2025-08-19 11:12:59'),
(20, 2, 'App\\Models\\Agent', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:15:35', '2025-08-19 11:15:35'),
(21, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:15:36', '2025-08-19 11:15:36'),
(22, 1, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:20:27', '2025-08-19 11:20:27'),
(23, 1, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:30:36', '2025-08-19 11:30:36'),
(24, 2, 'App\\Models\\Agent', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 11:30:36', '2025-08-19 11:30:36'),
(25, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-19 16:03:01', '2025-08-19 16:03:01'),
(26, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-20 08:35:02', '2025-08-20 08:35:02'),
(27, 3, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-20 09:10:11', '2025-08-20 09:10:11'),
(28, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-20 09:50:05', '2025-08-20 09:50:05'),
(29, 2, 'App\\Models\\Agent', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-20 09:52:48', '2025-08-20 09:52:48'),
(30, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-20 16:39:35', '2025-08-20 16:39:35'),
(31, 2, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-20 16:40:18', '2025-08-20 16:40:18'),
(32, 2, 'App\\Models\\Agent', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-20 16:40:44', '2025-08-20 16:40:44'),
(33, 2, 'App\\Models\\Mairie', 'login', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-21 08:26:49', '2025-08-21 08:26:49'),
(34, 2, 'App\\Models\\Mairie', 'logout', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0', '2025-08-21 09:01:17', '2025-08-21 09:01:17');

-- --------------------------------------------------------

--
-- Structure de la table `versements`
--

CREATE TABLE `versements` (
  `id` bigint UNSIGNED NOT NULL,
  `taxe_id` bigint UNSIGNED NOT NULL,
  `agent_id` bigint UNSIGNED NOT NULL,
  `mairie_id` bigint UNSIGNED NOT NULL,
  `montant_percu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant_verse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reste` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `agents_email_unique` (`email`),
  ADD KEY `agents_mairie_id_foreign` (`mairie_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `commercants`
--
ALTER TABLE `commercants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commercants_num_commerce_unique` (`num_commerce`),
  ADD KEY `commercants_agent_id_foreign` (`agent_id`),
  ADD KEY `commercants_mairie_id_foreign` (`mairie_id`),
  ADD KEY `commercants_secteur_id_foreign` (`secteur_id`);

--
-- Index pour la table `commercant_taxe`
--
ALTER TABLE `commercant_taxe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commercant_taxe_commercant_id_foreign` (`commercant_id`),
  ADD KEY `commercant_taxe_taxe_id_foreign` (`taxe_id`);

--
-- Index pour la table `communes`
--
ALTER TABLE `communes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `communes_nom_unique` (`nom`);

--
-- Index pour la table `encaissements`
--
ALTER TABLE `encaissements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `encaissements_taxe_id_foreign` (`taxe_id`),
  ADD KEY `encaissements_agent_id_foreign` (`agent_id`),
  ADD KEY `encaissements_mairie_id_foreign` (`mairie_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `financiers`
--
ALTER TABLE `financiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `financiers_email_unique` (`email`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mairies`
--
ALTER TABLE `mairies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mairies_email_unique` (`email`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paiement_taxes`
--
ALTER TABLE `paiement_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paiement_taxes_agent_id_foreign` (`agent_id`),
  ADD KEY `paiement_taxes_taxe_id_foreign` (`taxe_id`),
  ADD KEY `paiement_taxes_mairie_id_foreign` (`mairie_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Index pour la table `secteurs`
--
ALTER TABLE `secteurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `secteurs_mairie_id_foreign` (`mairie_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taxes_mairie_id_foreign` (`mairie_id`);

--
-- Index pour la table `type_contribuables`
--
ALTER TABLE `type_contribuables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_contribuables_libelle_unique` (`libelle`),
  ADD KEY `type_contribuables_agent_id_foreign` (`agent_id`),
  ADD KEY `type_contribuables_mairie_id_foreign` (`mairie_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `versements`
--
ALTER TABLE `versements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `versements_taxe_id_foreign` (`taxe_id`),
  ADD KEY `versements_agent_id_foreign` (`agent_id`),
  ADD KEY `versements_mairie_id_foreign` (`mairie_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agents`
--
ALTER TABLE `agents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `commercants`
--
ALTER TABLE `commercants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `commercant_taxe`
--
ALTER TABLE `commercant_taxe`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `communes`
--
ALTER TABLE `communes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT pour la table `encaissements`
--
ALTER TABLE `encaissements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `financiers`
--
ALTER TABLE `financiers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mairies`
--
ALTER TABLE `mairies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `paiement_taxes`
--
ALTER TABLE `paiement_taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `secteurs`
--
ALTER TABLE `secteurs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `type_contribuables`
--
ALTER TABLE `type_contribuables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `versements`
--
ALTER TABLE `versements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `agents_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commercants`
--
ALTER TABLE `commercants`
  ADD CONSTRAINT `commercants_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commercants_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commercants_secteur_id_foreign` FOREIGN KEY (`secteur_id`) REFERENCES `secteurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commercant_taxe`
--
ALTER TABLE `commercant_taxe`
  ADD CONSTRAINT `commercant_taxe_commercant_id_foreign` FOREIGN KEY (`commercant_id`) REFERENCES `commercants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commercant_taxe_taxe_id_foreign` FOREIGN KEY (`taxe_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `encaissements`
--
ALTER TABLE `encaissements`
  ADD CONSTRAINT `encaissements_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `encaissements_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `encaissements_taxe_id_foreign` FOREIGN KEY (`taxe_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiement_taxes`
--
ALTER TABLE `paiement_taxes`
  ADD CONSTRAINT `paiement_taxes_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiement_taxes_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiement_taxes_taxe_id_foreign` FOREIGN KEY (`taxe_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `secteurs`
--
ALTER TABLE `secteurs`
  ADD CONSTRAINT `secteurs_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `taxes`
--
ALTER TABLE `taxes`
  ADD CONSTRAINT `taxes_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `type_contribuables`
--
ALTER TABLE `type_contribuables`
  ADD CONSTRAINT `type_contribuables_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `type_contribuables_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `versements`
--
ALTER TABLE `versements`
  ADD CONSTRAINT `versements_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `versements_mairie_id_foreign` FOREIGN KEY (`mairie_id`) REFERENCES `mairies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `versements_taxe_id_foreign` FOREIGN KEY (`taxe_id`) REFERENCES `taxes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
