-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Anamakine: localhost:3306
-- Üretim Zamanı: 02 Ağu 2022, 20:14:00
-- Sunucu sürümü: 8.0.29-cll-lve
-- PHP Sürümü: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `oyunrehb_cryptomessenger`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `privatemessage`
--

CREATE TABLE `privatemessage` (
  `MessageId` int UNSIGNED NOT NULL,
  `RoomId` int UNSIGNED NOT NULL,
  `Messager` varchar(256) NOT NULL,
  `Message` text NOT NULL,
  `MessageDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Tablo döküm verisi `privatemessage`
--

INSERT INTO `privatemessage` (`MessageId`, `RoomId`, `Messager`, `Message`, `MessageDate`) VALUES
(111, 20, '5de6a1cd1ce5657c6e80dd2404f4f0db6f5a4de7ff3dd2b77be9909c9096c7fe', 't0BYR8X3D/7s2KH+CmLMzQ==', '2022-06-09 21:02:20'),
(112, 20, '5de6a1cd1ce5657c6e80dd2404f4f0db6f5a4de7ff3dd2b77be9909c9096c7fe', 'DxBqcz1/hf3KpKssSnFyow==', '2022-06-09 21:02:21'),
(113, 20, '5de6a1cd1ce5657c6e80dd2404f4f0db6f5a4de7ff3dd2b77be9909c9096c7fe', 'eClgcCjzKzkNn+rXyAFlrw==', '2022-06-09 21:02:22'),
(114, 20, '5de6a1cd1ce5657c6e80dd2404f4f0db6f5a4de7ff3dd2b77be9909c9096c7fe', '/OUxtysUBhGfgLhCuWkPIQ==', '2022-06-09 21:02:23'),
(115, 20, '', '', '2022-06-09 21:02:30'),
(116, 21, '', '', '2022-06-11 18:34:11'),
(117, 21, 'cb5e0d832a7145a0a37723343ac689d4e3de80f89e902de598a5ded4411fb9b7', 'XCqjWx84CKdLaAYP3BaOYL95fn/qszImX6E9QS0dSJo=', '2022-06-11 18:47:32'),
(118, 21, '', '', '2022-06-11 19:00:25'),
(121, 21, 'cb5e0d832a7145a0a37723343ac689d4e3de80f89e902de598a5ded4411fb9b7', 'UYuMhGfgKi6Q7aSQyp6pEt6Vf1oDy3P4IDAkgqNYeZ8=', '2022-06-11 20:41:54'),
(122, 21, '', '', '2022-06-11 20:50:36'),
(127, 23, '1c7f4194ee6f2fe2f26c62b0cb2d2b2f85d0e05ff81ab91bec767da6f9bd5ba6', '+VGWkraWuG4T0Y+gLU8U7A==', '2022-06-12 13:46:37'),
(128, 23, '', '', '2022-06-12 13:46:45'),
(129, 23, '1c7f4194ee6f2fe2f26c62b0cb2d2b2f85d0e05ff81ab91bec767da6f9bd5ba6', '2gugfczpjlPV8PdgoUy0g5nDXT6OYtNPLxOa8BJlHLtjsODQyswwvFBO9y3Qpz41QmOOur6WrhyvPHRhXaAmAQ==', '2022-06-12 13:47:13'),
(130, 23, '', '', '2022-06-12 13:47:24'),
(131, 23, '', '', '2022-06-12 13:47:30'),
(138, 25, 'f27be2edf1160cae33c2b48d23b6c530c9693dede379379f68d211821df6dc51', 'LNdorwU/Lq/mkfELQhATFg==', '2022-07-04 18:20:36'),
(139, 25, 'a23312ce8eca2bcbdcd80c704c95f9d63cf696de16a62fc0ddc419f863ed0e37', 'eClgcCjzKzkNn+rXyAFlrw==', '2022-07-04 18:20:44');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `privateroom`
--

CREATE TABLE `privateroom` (
  `RoomId` int UNSIGNED NOT NULL,
  `MessagerA` varchar(256) NOT NULL,
  `MessagerB` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Tablo döküm verisi `privateroom`
--

INSERT INTO `privateroom` (`RoomId`, `MessagerA`, `MessagerB`) VALUES
(20, '', '5de6a1cd1ce5657c6e80dd2404f4f0db6f5a4de7ff3dd2b77be9909c9096c7fe'),
(21, 'cb5e0d832a7145a0a37723343ac689d4e3de80f89e902de598a5ded4411fb9b7', ''),
(23, '', '1c7f4194ee6f2fe2f26c62b0cb2d2b2f85d0e05ff81ab91bec767da6f9bd5ba6'),
(25, 'a23312ce8eca2bcbdcd80c704c95f9d63cf696de16a62fc0ddc419f863ed0e37', 'f27be2edf1160cae33c2b48d23b6c530c9693dede379379f68d211821df6dc51');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `request`
--

CREATE TABLE `request` (
  `RequestId` int UNSIGNED NOT NULL,
  `UserFrom` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `UserTo` varchar(256) CHARACTER SET utf8mb3 COLLATE utf8_general_ci NOT NULL,
  `RequestDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user`
--

CREATE TABLE `user` (
  `UserId` int UNSIGNED NOT NULL,
  `Name` varchar(40) NOT NULL,
  `Surname` varchar(40) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `EmailConfirmed` tinyint(1) NOT NULL DEFAULT '0',
  `Token` varchar(256) DEFAULT NULL,
  `TokenDate` timestamp NULL DEFAULT NULL,
  `RegisterDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LastLoginDate` datetime NOT NULL,
  `PasswordResetToken` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Tablo döküm verisi `user`
--

INSERT INTO `user` (`UserId`, `Name`, `Surname`, `Email`, `Password`, `EmailConfirmed`, `Token`, `TokenDate`, `RegisterDate`, `LastLoginDate`, `PasswordResetToken`) VALUES
(18, 'selçuk', 'şahin', 'muselcuksahin@gmail.com', '2018d31f5007c006c901837ea314dd00659640505fb184efe3e98c08cd873f47', 0, 'a23312ce8eca2bcbdcd80c704c95f9d63cf696de16a62fc0ddc419f863ed0e37', '2022-06-09 20:55:09', '2022-06-09 20:55:05', '2022-08-02 20:12:34', NULL),
(19, 'asddasd', 'asdasdasd', 'ahmetmehmet@gmail.com', '2018d31f5007c006c901837ea314dd00659640505fb184efe3e98c08cd873f47', 0, '5de6a1cd1ce5657c6e80dd2404f4f0db6f5a4de7ff3dd2b77be9909c9096c7fe', '2022-06-09 21:02:01', '2022-06-09 21:01:57', '2022-06-11 22:01:00', NULL),
(20, 'Pompa', 'Pumpgun', 'bayazitdogancan@gmail.com', 'da0a3020d0019a6ae7890e9a23bf665efcc617b3269439b63033c07aa8e9ecaa', 0, 'cb5e0d832a7145a0a37723343ac689d4e3de80f89e902de598a5ded4411fb9b7', '2022-06-10 04:24:27', '2022-06-10 04:24:11', '2022-06-11 23:41:33', NULL),
(21, 'Gara', 'Yarak', 'gara@hotmail.com', '1b509c6a961269f73e0e9a8b1c3d6931edd4a3ca6adf5a7d19b47f0ec66f7a8c', 0, '52e8f1ebeae37432cd496941e5ff3f11de52ac0fe9aa6cf0f1e86a37e30d2409', '2022-06-10 05:52:01', '2022-06-10 05:51:39', '2022-06-12 00:23:15', NULL),
(22, 'selçuk', 'şahin', 'asdasdasd@mail.com', 'ecca7bcbf9f1718b71b26edf92486fabe7fb27c2e9f1d77edefb3491d2b7aaf8', 0, '7da924ddf04c362495a35b2fc6d897ff6a972230f222062687dd233b35bb17ae', '2022-06-11 19:19:50', '2022-06-11 19:19:40', '2022-06-11 22:19:49', NULL),
(23, 'test', 'test', 'asdasd@gmail.com', '2018d31f5007c006c901837ea314dd00659640505fb184efe3e98c08cd873f47', 0, '243a2813a00d36cf099a91eb3ae873ac2ffdf254e04c7fd74bc77670986a6abf', '2022-06-11 22:10:13', '2022-06-11 22:10:00', '2022-06-12 01:10:08', NULL),
(24, 'Erislam', 'Nurluyol', 'nurluyolerislam@gmail.com', 'fb0936018d4416c93ff0c4e19b3676bbbe0a57434818873dfbdab78b08efc38f', 0, '1c7f4194ee6f2fe2f26c62b0cb2d2b2f85d0e05ff81ab91bec767da6f9bd5ba6', '2022-06-12 13:45:25', '2022-06-12 13:44:54', '2022-06-12 16:45:15', NULL),
(25, 'deneme', 'deneme', 'deneme@gmail.com', '2018d31f5007c006c901837ea314dd00659640505fb184efe3e98c08cd873f47', 0, 'f27be2edf1160cae33c2b48d23b6c530c9693dede379379f68d211821df6dc51', '2022-07-04 18:05:09', '2022-07-04 18:05:05', '2022-07-04 21:18:39', NULL),
(26, 'selçuk', 'şahin', 'selcuksahin@gmail.com', '67111ed3a5f53b2cc1609afa3ff7391241cd50a65711159b7254a1d9abf28c47', 0, 'dc65ecf897b2fb4c88e7f5bf6147ecba1731d27e83f226fdf87f89815f8c8613', '2022-07-08 15:32:58', '2022-07-08 15:32:33', '2022-07-08 18:32:41', NULL);

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `privatemessage`
--
ALTER TABLE `privatemessage`
  ADD PRIMARY KEY (`MessageId`);

--
-- Tablo için indeksler `privateroom`
--
ALTER TABLE `privateroom`
  ADD PRIMARY KEY (`RoomId`);

--
-- Tablo için indeksler `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`RequestId`);

--
-- Tablo için indeksler `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`UserId`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `privatemessage`
--
ALTER TABLE `privatemessage`
  MODIFY `MessageId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- Tablo için AUTO_INCREMENT değeri `privateroom`
--
ALTER TABLE `privateroom`
  MODIFY `RoomId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Tablo için AUTO_INCREMENT değeri `request`
--
ALTER TABLE `request`
  MODIFY `RequestId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Tablo için AUTO_INCREMENT değeri `user`
--
ALTER TABLE `user`
  MODIFY `UserId` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
