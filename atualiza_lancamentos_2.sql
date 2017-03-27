ALTER TABLE `lancamentos` ADD `numcheque` varchar(11) DEFAULT NULL AFTER `dataparcela4`;
ALTER TABLE `lancamentos` ADD `nomecheque` varchar(100) DEFAULT NULL AFTER `numcheque`;