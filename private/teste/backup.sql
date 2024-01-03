-- MySQL dump 10.13  Distrib 8.0.34, for Linux (x86_64)
--
-- Host: srv952.hstgr.io    Database: u921294813_mobilex
-- ------------------------------------------------------
-- Server version	5.5.5-10.6.14-MariaDB-cll-lve

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `arquivos`
--

DROP TABLE IF EXISTS `arquivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arquivos` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `referencia` int(11) DEFAULT NULL,
  `tipo` enum('img','pdf','xml','txt','html') DEFAULT NULL,
  `chave` varchar(255) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `referencia` (`referencia`) USING BTREE,
  KEY `nome` (`nome`) USING BTREE,
  KEY `chave` (`chave`) USING BTREE,
  KEY `tipo` (`tipo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arquivos`
--

LOCK TABLES `arquivos` WRITE;
/*!40000 ALTER TABLE `arquivos` DISABLE KEYS */;
INSERT INTO `arquivos` VALUES (8,3,'img','perfil','3ef08d0884510193d283b.jpg','2023-08-15 23:38:57','2023-08-15 23:38:57'),(20,1,'img','perfil','10e50c44bd723ffd74e0f.jpg','2023-08-25 02:49:41','2023-08-25 02:49:41');
/*!40000 ALTER TABLE `arquivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enderecos`
--

DROP TABLE IF EXISTS `enderecos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enderecos` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `pais` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `cidade` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `rua` varchar(255) DEFAULT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_id_usuario_enderecos` (`id_usuario`),
  CONSTRAINT `FK_id_usuario_enderecos` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enderecos`
--

LOCK TABLES `enderecos` WRITE;
/*!40000 ALTER TABLE `enderecos` DISABLE KEYS */;
/*!40000 ALTER TABLE `enderecos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `firebase` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `online_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `FK_id_usuario_sessions` (`id_usuario`),
  KEY `token` (`token`) USING BTREE,
  CONSTRAINT `FK_id_usuario_sessions` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES (9,1,'$2y$10$IMt7XNqGNnTlXNJoRzAxiO8YuYx.InYT39YdeShSVJC.rASgqgdZ6',1,'cfSHyEpAQtuWFEsFLMUGAE:APA91bF5rwafzphIowFWXpJc0X_UDZ70M-qSFlbgjyxrcHdueBW74eDgH-Sh3LGXek-py_4KR-_bUWR2MK03IYlDkbwMcKPgJ-FjgngfbiAxW6T4XU0AVn0flE8Ch2AuddOPWedletMS','2804:214:862e:7943:1:0:e7ef:6c69','Linux armv8l','-20.02644','-20.02644','2023-08-25 21:50:03','2023-08-25 21:50:03','2023-08-14 19:26:20'),(10,1,'$2y$10$NaC4dwEVO2o/Up1FenXdruJrISc2Il.9bM7jUsbNlLHMM/2lti2qG',1,NULL,'177.39.123.129','Linux x86_64',NULL,NULL,'2023-08-16 01:10:02','2023-08-16 01:10:02','2023-08-15 13:58:54'),(11,2,'$2y$10$DulR5BHTlhUcutM2LZjBg.f90Wh1jHt32q/CTFEGFAQd7Qq7A33EW',1,'ei7pSlRkRbmWBjH1j8dejk:APA91bEKYt2C8CTskxdEfNh7PuQDq8vukdzxF9vG3akQ0TVsakiQlgLcC3v6iD7v6WnuuF_DvchyBM9xUUKE9skTzvFLYfBdZwwcjhNvaqENL_DOBqv5G-cpZT0YZn0AYirkJpPpvyPf','45.164.211.28','Linux armv8l','-23.7297468','-23.7297468','2023-08-15 23:41:14','2023-08-15 23:41:14','2023-08-15 23:37:27'),(12,3,'$2y$10$qexzw.iOk/pwnWdfjeAxSe.nJlxhszjQEvJ0cGN6JNPcbRuoUp25i',1,'d1aSAdfzTVidO4ZKoF5w1i:APA91bEOC1Qvf05VVwd6qYBlAETFfOOOgHBVfBr3e3qO-ADPArSF3IhdFvcqdsdbR8myK6JY7KKsfv-eYYz-j2AN0XJtPWocTuReW7ZD3oRZilzNIxQmJoCqGPwgmiUtGys5-GlAuZ2-','131.0.61.20','Linux aarch64','-23.6716542','-23.6716542','2023-08-20 14:11:09','2023-08-20 14:11:09','2023-08-15 23:37:29');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT NULL,
  `tipo` enum('administrador','funcionario','motorista','cliente') DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `cpf` varchar(255) DEFAULT NULL,
  `cnpj` varchar(255) DEFAULT NULL,
  `telefone` varchar(255) DEFAULT NULL,
  `celular` varchar(255) DEFAULT NULL,
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `email` (`email`) USING BTREE,
  KEY `tipo` (`tipo`) USING BTREE,
  KEY `codigo` (`codigo`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'abf580bd4e829e54ae3c',1,'cliente','Warley Rodrigues','haleytrader@hotmail.com','$2y$10$6QgVwTw59F/8pVPfU.iNv.X.RSUZNHsT33/I/3I1It485cPO.8f8W',NULL,NULL,NULL,NULL,'2023-08-15 23:13:36','2023-08-08 01:16:54'),(2,'ba5fd00fc2',1,'cliente','Gustavo','gustavo.carvalho@code4solution.com.br','$2y$10$X7CxSjmlmocfU537S7QxyOop8DdZe9jLDPbaR92juUdaZrxwAlhXW',NULL,NULL,NULL,NULL,'2023-08-15 23:37:08','2023-08-15 23:37:08'),(3,'35898464dc',1,'cliente','Thales Nascimento','thalesmilton@hotmail.com','$2y$10$oguNp553RSBKbNeZr9Jwq.chZzRB/QwYt6tMYQyfX5b4MQ9ABw.HC',NULL,NULL,NULL,NULL,'2023-08-15 23:41:06','2023-08-15 23:37:13');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-08-25 20:17:36
