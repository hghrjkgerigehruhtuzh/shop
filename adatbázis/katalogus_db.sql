-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Ápr 29. 10:48
-- Kiszolgáló verziója: 10.4.28-MariaDB
-- PHP verzió: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `katalogus_db`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `termekek`
--

CREATE TABLE `termekek` (
  `id` int(11) NOT NULL,
  `nev` varchar(100) NOT NULL,
  `leiras` text DEFAULT NULL,
  `tipus` varchar(50) NOT NULL,
  `ar` int(11) NOT NULL,
  `ritkasag` char(1) NOT NULL,
  `kep_utvonal` varchar(255) DEFAULT 'default.jpg',
  `datum` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_hungarian_ci;

--
-- A tábla adatainak kiíratása `termekek`
--

INSERT INTO `termekek` (`id`, `nev`, `leiras`, `tipus`, `ar`, `ritkasag`, `kep_utvonal`, `datum`) VALUES
(1, 'Arany Sárkány', 'Legendás kártya', 'Kártya', 1000, 'A', 'default.jpg', '2026-04-29 08:06:55'),
(2, 'Ősi Kódex', 'Ritka antikvár könyv', 'Könyv', 2000, 'B', 'default.jpg', '2026-04-29 08:06:55'),
(3, 'Ezüst Kard', 'Közepesen ritka tárgy', 'Kártya', 500, 'C', 'default.jpg', '2026-04-29 08:06:55'),
(4, 'Egyszerű Lap', 'Gyakori kezdő kártya', 'Kártya', 100, 'D', 'default.jpg', '2026-04-29 08:06:55'),
(5, 'sgfdfhgdfg', 'dsfsdgsfgh', 'Kártya', 1000, 'C', '1777451309_letöltés.jpg', '2026-04-29 08:28:29'),
(6, 'sghfdshdfhfds', '457858957', 'Kártya', 4000, 'B', '1777451720_letöltés.jpg', '2026-04-29 08:35:20');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `termekek`
--
ALTER TABLE `termekek`
  ADD PRIMARY KEY (`id`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `termekek`
--
ALTER TABLE `termekek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
