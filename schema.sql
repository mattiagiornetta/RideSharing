CREATE TABLE IF NOT EXISTS `Regione` (
  `id` int unsigned NOT NULL,
  `nome` varchar(31) NOT NULL,
  PRIMARY KEY (`id`)
);
CREATE TABLE IF NOT EXISTS `Provincia` (
  `id` int NOT NULL,
  `nome` varchar(23) NOT NULL,
  `id_regione` int unsigned NOT NULL,
  `sigla_automobilistica` char(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_regione` (`id_regione`),
  CONSTRAINT `Provincia_ibfk_1` FOREIGN KEY (`id_regione`) REFERENCES `Regione` (`id`)
);
CREATE TABLE IF NOT EXISTS `Comune` (
  `id` int  primary key,
  `id_provincia` int NOT NULL,
  `nome` text NOT NULL,
  `latitudine` decimal(9,6) NOT NULL,
  `longitudine` decimal(9,6) NOT NULL,
  CONSTRAINT `fk_id_provincia` FOREIGN KEY (`id_provincia`) REFERENCES `Provincia` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
CREATE TABLE IF NOT EXISTS `Utente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `data_di_nascita` date NOT NULL,
  `data_registrazione` date NOT NULL DEFAULT CURRENT_DATE,
  `email` varchar(320) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  CONSTRAINT `CONSTRAINT_4` CHECK (LENGTH(password) > 6),
  CONSTRAINT `CONSTRAINT_5` CHECK (LENGTH(nome) > 1),
  CONSTRAINT `CONSTRAINT_6` CHECK (LENGTH(cognome) > 1)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE IF NOT EXISTS `Sessione` (
  `session_id` varchar(32) NOT NULL,
  `id_utente` int NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `stato` tinyint(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`session_id`),
  KEY `fk_sid_utente` (`id_utente`),
   CONSTRAINT `CONSTRAINT_7` CHECK (`stato` >= -1 and `stato` <= 1),

  CONSTRAINT `fk_sid_utente` FOREIGN KEY (`id_utente`) REFERENCES `Utente` (`id`)
);

CREATE TABLE IF NOT EXISTS `Viaggio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_destinazione` int NOT NULL,
  `id_partenza` int NOT NULL,
  `posti` smallint(5) unsigned NOT NULL,
  `prezzo_posto` smallint(5) unsigned NOT NULL,
  `data_partenza` datetime NOT NULL,
  `id_organizzatore` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_organizzatore` (`id_organizzatore`),
  KEY `fk_id_partenza` (`id_partenza`),
  KEY `fk_id_destinazione` (`id_destinazione`),
  CONSTRAINT `fk_id_destinazione` FOREIGN KEY (`id_destinazione`) REFERENCES `Comune`(`id`)  ,
  CONSTRAINT `fk_id_organizzatore` FOREIGN KEY (`id_organizzatore`) REFERENCES `Utente` (`id`)  ,
  CONSTRAINT `fk_id_partenza` FOREIGN KEY (`id_partenza`) REFERENCES `Comune` (`id`),
  CONSTRAINT `CONSTRAINT_2` CHECK (`posti` > 0 and `posti` < 12),
  CONSTRAINT `CONSTRAINT_3` CHECK (`prezzo_posto` > 0)

) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
CREATE TABLE IF NOT EXISTS `ViaggioUtente` (
  `id_utente` int NOT NULL,
  `id_viaggio` int NOT NULL,
  PRIMARY KEY (`id_utente`,`id_viaggio`),
  KEY `fk_id_viaggio_v` (`id_viaggio`),
  CONSTRAINT `fk_id_utente` FOREIGN KEY (`id_utente`) REFERENCES `Utente` (`id`),
  CONSTRAINT `fk_id_viaggio_v` FOREIGN KEY (`id_viaggio`) REFERENCES `Viaggio` (`id`)
);
CREATE TABLE IF NOT EXISTS `Recensione` (
  `id_from` int NOT NULL,
  `id_viaggio` int NOT NULL,
  `id_to` int NOT NULL,
  `voto` int NOT NULL,
  PRIMARY KEY (`id_from`,`id_to`,`id_viaggio`),
  KEY `fk_id_to` (`id_to`),
  KEY `fk_id_viaggio` (`id_viaggio`),
  CONSTRAINT `fk_id_from` FOREIGN KEY (`id_from`) REFERENCES `Utente` (`id`),
  CONSTRAINT `fk_id_to` FOREIGN KEY (`id_to`) REFERENCES `Utente` (`id`),
  CONSTRAINT `fk_id_viaggio` FOREIGN KEY (`id_viaggio`) REFERENCES `Viaggio` (`id`),
  CONSTRAINT `CONSTRAINT_1` CHECK (`voto` > 0 and `voto` <= 5)
);
