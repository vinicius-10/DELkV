-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21-Jun-2022 às 01:36
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Banco de dados: `biblioteca`
--
CREATE DATABASE IF NOT EXISTS `biblioteca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `biblioteca`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `autora`
--

DROP TABLE IF EXISTS `autora`;
CREATE TABLE `autora` (
  `id_autora` int(11) NOT NULL,
  `nome_autora` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `autoraref`
--

DROP TABLE IF EXISTS `autoraref`;
CREATE TABLE `autoraref` (
  `id_autora_fk` int(11) NOT NULL,
  `id_livro_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `editora`
--

DROP TABLE IF EXISTS `editora`;
CREATE TABLE `editora` (
  `id_editora` int(11) NOT NULL,
  `nome_editora` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `emprestimo`
--

DROP TABLE IF EXISTS `emprestimo`;
CREATE TABLE `emprestimo` (
  `id_cliente_fk` int(11) NOT NULL,
  `id_exemplar_fk` int(11) NOT NULL,
  `emprestimo` date NOT NULL,
  `devolucao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `exemplar`
--

DROP TABLE IF EXISTS `exemplar`;
CREATE TABLE `exemplar` (
  `id_exemplar` int(11) NOT NULL,
  `id_livro_fk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `genero`
--

DROP TABLE IF EXISTS `genero`;
CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `genero` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro`
--

DROP TABLE IF EXISTS `livro`;
CREATE TABLE `livro` (
  `id_livro` int(11) NOT NULL,
  `id_editora_fk` int(11) NOT NULL,
  `nome_livro` varchar(40) NOT NULL,
  `sinopse` varchar(2500) NOT NULL,
  `genero_fk` int(11) NOT NULL,
  `descricao` varchar(500) NOT NULL,
  `imagem` varchar(70) NOT NULL,
  `acessos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `multa`
--

DROP TABLE IF EXISTS `multa`;
CREATE TABLE `multa` (
  `id_livro_fk` int(11) NOT NULL,
  `id_cliente_fk` int(11) NOT NULL,
  `valor` float(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reserva`
--

DROP TABLE IF EXISTS `reserva`;
CREATE TABLE `reserva` (
  `id_cliente_fk` int(11) NOT NULL,
  `id_exemplar_fk` int(11) NOT NULL,
  `reserva` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id_cliente` int(11) NOT NULL,
  `cpf` varchar(15) NOT NULL,
  `nome` varchar(200) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `bloqueado` int(1) DEFAULT NULL,
  `cep` int(8) NOT NULL,
  `nascimento` date NOT NULL,
  `tipo` int(1) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `autora`
--
ALTER TABLE `autora`
  ADD PRIMARY KEY (`id_autora`);

--
-- Índices para tabela `autoraref`
--
ALTER TABLE `autoraref`
  ADD KEY `id_autora_fk` (`id_autora_fk`),
  ADD KEY `id_livro_fk` (`id_livro_fk`);

--
-- Índices para tabela `editora`
--
ALTER TABLE `editora`
  ADD PRIMARY KEY (`id_editora`);

--
-- Índices para tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD KEY `id_cliente_fk` (`id_cliente_fk`),
  ADD KEY `id_exemplar_fk` (`id_exemplar_fk`);

--
-- Índices para tabela `exemplar`
--
ALTER TABLE `exemplar`
  ADD PRIMARY KEY (`id_exemplar`),
  ADD KEY `id_livro_fk` (`id_livro_fk`);

--
-- Índices para tabela `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Índices para tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id_livro`),
  ADD KEY `id_editora_fk` (`id_editora_fk`),
  ADD KEY `genero_fk` (`genero_fk`);

--
-- Índices para tabela `multa`
--
ALTER TABLE `multa`
  ADD KEY `id_cliente_fk` (`id_cliente_fk`),
  ADD KEY `id_livro_fk` (`id_livro_fk`);

--
-- Índices para tabela `reserva`
--
ALTER TABLE `reserva`
  ADD KEY `id_cliente_fk` (`id_cliente_fk`),
  ADD KEY `id_exemplar_fk` (`id_exemplar_fk`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_cliente`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `autora`
--
ALTER TABLE `autora`
  MODIFY `id_autora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `editora`
--
ALTER TABLE `editora`
  MODIFY `id_editora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `exemplar`
--
ALTER TABLE `exemplar`
  MODIFY `id_exemplar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `id_livro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `autoraref`
--
ALTER TABLE `autoraref`
  ADD CONSTRAINT `autoraref_ibfk_1` FOREIGN KEY (`id_autora_fk`) REFERENCES `autora` (`id_autora`),
  ADD CONSTRAINT `autoraref_ibfk_2` FOREIGN KEY (`id_livro_fk`) REFERENCES `livro` (`id_livro`);

--
-- Limitadores para a tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `emprestimo_ibfk_1` FOREIGN KEY (`id_cliente_fk`) REFERENCES `usuario` (`id_cliente`),
  ADD CONSTRAINT `emprestimo_ibfk_2` FOREIGN KEY (`id_exemplar_fk`) REFERENCES `exemplar` (`id_exemplar`);

--
-- Limitadores para a tabela `exemplar`
--
ALTER TABLE `exemplar`
  ADD CONSTRAINT `exemplar_ibfk_1` FOREIGN KEY (`id_livro_fk`) REFERENCES `livro` (`id_livro`);

--
-- Limitadores para a tabela `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `livro_ibfk_1` FOREIGN KEY (`id_editora_fk`) REFERENCES `editora` (`id_editora`),
  ADD CONSTRAINT `livro_ibfk_2` FOREIGN KEY (`genero_fk`) REFERENCES `genero` (`id_genero`);

--
-- Limitadores para a tabela `multa`
--
ALTER TABLE `multa`
  ADD CONSTRAINT `multa_ibfk_1` FOREIGN KEY (`id_cliente_fk`) REFERENCES `usuario` (`id_cliente`),
  ADD CONSTRAINT `multa_ibfk_2` FOREIGN KEY (`id_livro_fk`) REFERENCES `livro` (`id_livro`);

--
-- Limitadores para a tabela `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_cliente_fk`) REFERENCES `usuario` (`id_cliente`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_exemplar_fk`) REFERENCES `exemplar` (`id_exemplar`);
COMMIT;