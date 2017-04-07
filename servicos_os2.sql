CREATE TABLE `servicos_os2` (
  `idServicos_os` int(11) NOT NULL,
  `os_id` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `totalsrv` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for table `servicos_os2`
--
ALTER TABLE `servicos_os2`
  ADD PRIMARY KEY (`idServicos_os`);
