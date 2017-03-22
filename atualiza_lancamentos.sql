ALTER TABLE `lancamentos` ADD `parcela1` varchar(15) DEFAULT NULL AFTER `clientes_id`;
ALTER TABLE `lancamentos` ADD `dataparcela1` date DEFAULT NULL AFTER `parcela1`;
ALTER TABLE `lancamentos` ADD `parcela2` varchar(15) DEFAULT NULL AFTER `dataparcela1`;
ALTER TABLE `lancamentos` ADD `dataparcela2` date DEFAULT NULL AFTER `parcela2`;
ALTER TABLE `lancamentos` ADD `parcela3` varchar(15) DEFAULT NULL AFTER `dataparcela2`;
ALTER TABLE `lancamentos` ADD `dataparcela3` date DEFAULT NULL AFTER `parcela3`;
ALTER TABLE `lancamentos` ADD `parcela4` varchar(15) DEFAULT NULL AFTER `dataparcela3`;
ALTER TABLE `lancamentos` ADD `dataparcela4` date DEFAULT NULL AFTER `parcela4`;