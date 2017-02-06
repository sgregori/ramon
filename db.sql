--
-- Table structure for table `bot`
--

DROP TABLE IF EXISTS `bot`;

CREATE TABLE `bot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL COMMENT 'Nombre del servidor',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Activa o no la comprobación del servidor',
  `url_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 para activado, 0 para desactivado',
  `url` varchar(200) NOT NULL DEFAULT '',
  `sql_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 para activar comprobar una base de datos',
  `host` varchar(80) NOT NULL DEFAULT '',
  `user` varchar(80) NOT NULL DEFAULT '',
  `password` varchar(80) NOT NULL DEFAULT '',
  `database` varchar(80) NOT NULL DEFAULT '',
  `last_time_seen_online` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='Servidores del bot de telegram';

--
-- Dumping data for table `bot`
--

INSERT INTO `bot` VALUES (1,'Web Ejemplo',1,1,'http://www.webejemplo.com',0,'','','','','2017-02-06 10:42:04');

--
-- Table structure for table `bot_conf`
--

DROP TABLE IF EXISTS `bot_conf`;

CREATE TABLE `bot_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bot_id` int(11) NOT NULL DEFAULT '0',
  `day` varchar(50) NOT NULL DEFAULT '',
  `hour` varchar(50) NOT NULL DEFAULT '',
  `minute` varchar(50) NOT NULL DEFAULT '',
  `last_execution` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;


--
-- Dumping data for table `bot_conf`
--

INSERT INTO `bot_conf` VALUES (1,1,'*','*','*','2017-02-06 09:42:04');