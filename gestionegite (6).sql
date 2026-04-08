-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Apr 08, 2026 alle 14:46
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestionegite`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `gitaorganizzata`
--

CREATE TABLE `gitaorganizzata` (
  `IDGita` int(11) NOT NULL,
  `IDUtente` int(11) NOT NULL,
  `DataInizio` date DEFAULT NULL,
  `DataFine` date DEFAULT NULL,
  `NumAlunni` int(11) DEFAULT NULL,
  `NumDocentiAccompagnatori` int(11) DEFAULT NULL,
  `NumAlunniDisabili` int(11) DEFAULT 0,
  `CostoTot` decimal(10,2) DEFAULT NULL,
  `IDStato` int(11) DEFAULT NULL,
  `OrarioPartenza` time DEFAULT NULL COMMENT 'Orario di partenza della gita',
  `OrarioArrivo` time DEFAULT NULL COMMENT 'Orario di arrivo della gita',
  `CostoMezzi` decimal(10,2) DEFAULT 0.00 COMMENT 'Costo dei mezzi di trasporto',
  `CostoAttivita` decimal(10,2) DEFAULT 0.00 COMMENT 'Costo delle attività previste',
  `ClassiPartecipanti` varchar(255) DEFAULT NULL COMMENT 'Classi che partecipano (es. 5A, 5B)',
  `NumManleveConsegnate` int(11) DEFAULT 0 COMMENT 'Numero di manleve consegnate, se necessarie'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `gitaorganizzata`
--

INSERT INTO `gitaorganizzata` (`IDGita`, `IDUtente`, `DataInizio`, `DataFine`, `NumAlunni`, `NumDocentiAccompagnatori`, `NumAlunniDisabili`, `CostoTot`, `IDStato`, `OrarioPartenza`, `OrarioArrivo`, `CostoMezzi`, `CostoAttivita`, `ClassiPartecipanti`, `NumManleveConsegnate`) VALUES
(8, 1, '2026-04-08', '2026-04-08', 0, 0, 0, 6546.00, 2, NULL, NULL, 0.00, 0.00, NULL, 0),
(9, 1, '2026-04-09', '2026-04-18', 36, 453, 543, 236046.00, 5, '00:00:14', '00:00:14', 45.00, 345.00, '5AII, 5BII', 0),
(10, 1, '2026-04-08', '2026-04-08', 0, 0, 0, 0.00, 1, NULL, NULL, 0.00, 0.00, NULL, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipanti`
--

CREATE TABLE `partecipanti` (
  `IDPartecipante` int(11) NOT NULL,
  `IDGita` int(11) NOT NULL COMMENT 'Riferimento alla gita organizzata',
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Classe` varchar(10) NOT NULL COMMENT 'Classe del partecipante (es. 5A)',
  `Descrizione` text DEFAULT NULL COMMENT 'Eventuali note o descrizioni'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `partecipanti`
--

INSERT INTO `partecipanti` (`IDPartecipante`, `IDGita`, `Nome`, `Cognome`, `Classe`, `Descrizione`) VALUES
(3, 9, 'bnlh', 'jgk', 'gh', 'jghjg');

-- --------------------------------------------------------

--
-- Struttura della tabella `statogita`
--

CREATE TABLE `statogita` (
  `IDStato` int(11) NOT NULL,
  `Stato` enum('Bozza','Approvata','Bocciata','Organizzazione','Conclusa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `statogita`
--

INSERT INTO `statogita` (`IDStato`, `Stato`) VALUES
(1, 'Bozza'),
(2, 'Approvata'),
(3, 'Bocciata'),
(4, 'Organizzazione'),
(5, 'Conclusa');

-- --------------------------------------------------------

--
-- Struttura della tabella `tipoutente`
--

CREATE TABLE `tipoutente` (
  `IDTipo` int(11) NOT NULL,
  `Descrizione` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tipoutente`
--

INSERT INTO `tipoutente` (`IDTipo`, `Descrizione`) VALUES
(1, 'Docente'),
(2, 'Commissione');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `IDUtente` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `Mail` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `IDTipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`IDUtente`, `Nome`, `Cognome`, `Mail`, `Password`, `IDTipo`) VALUES
(1, 'elisa', 'stanizzi', 'elisa.stanizzi.2007@calvino.edu.it', '$2y$10$PZt7aoV4cgla70ditsreVeNCs0gjmjVqSgd49VS5/6PUdyqs75nc.', 2);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `gitaorganizzata`
--
ALTER TABLE `gitaorganizzata`
  ADD PRIMARY KEY (`IDGita`),
  ADD KEY `IDUtente` (`IDUtente`),
  ADD KEY `IDStato` (`IDStato`);

--
-- Indici per le tabelle `partecipanti`
--
ALTER TABLE `partecipanti`
  ADD PRIMARY KEY (`IDPartecipante`),
  ADD KEY `IDGita` (`IDGita`);

--
-- Indici per le tabelle `statogita`
--
ALTER TABLE `statogita`
  ADD PRIMARY KEY (`IDStato`);

--
-- Indici per le tabelle `tipoutente`
--
ALTER TABLE `tipoutente`
  ADD PRIMARY KEY (`IDTipo`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`IDUtente`),
  ADD UNIQUE KEY `Mail` (`Mail`),
  ADD KEY `IDTipo` (`IDTipo`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `gitaorganizzata`
--
ALTER TABLE `gitaorganizzata`
  MODIFY `IDGita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `partecipanti`
--
ALTER TABLE `partecipanti`
  MODIFY `IDPartecipante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT per la tabella `statogita`
--
ALTER TABLE `statogita`
  MODIFY `IDStato` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `tipoutente`
--
ALTER TABLE `tipoutente`
  MODIFY `IDTipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `IDUtente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `gitaorganizzata`
--
ALTER TABLE `gitaorganizzata`
  ADD CONSTRAINT `gitaorganizzata_ibfk_2` FOREIGN KEY (`IDUtente`) REFERENCES `utente` (`IDUtente`),
  ADD CONSTRAINT `gitaorganizzata_ibfk_3` FOREIGN KEY (`IDStato`) REFERENCES `statogita` (`IDStato`);

--
-- Limiti per la tabella `partecipanti`
--
ALTER TABLE `partecipanti`
  ADD CONSTRAINT `partecipanti_ibfk_1` FOREIGN KEY (`IDGita`) REFERENCES `gitaorganizzata` (`IDGita`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `utente_ibfk_1` FOREIGN KEY (`IDTipo`) REFERENCES `tipoutente` (`IDTipo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
