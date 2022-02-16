
CREATE DATABASE;

USE `chat`;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atendente` int(1) DEFAULT '0',
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`id`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=0DEFAULT CHARSET=utf8;

-- USUÁRIO CRIADO COMO UNICO OPERADOR DO CHAT
insert  into `usuario`(`id`,`atendente`,`nome`,`email`) values 
(1,1,'SUPORTE_S3ND','suporte@s3nd.com.br');

CREATE TABLE `atendimento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `atendente_id` int(11) NOT NULL DEFAULT '1',
  `finalizado` int(1) DEFAULT '0',
  `data_inicio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_fim` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`usuario_id`,`atendente_id`),
  KEY `atendente_id` (`atendente_id`),
  KEY `atendimento_ibfk_2` (`usuario_id`),
  CONSTRAINT `atendimento_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

CREATE TABLE `mensagem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `atendimento_id` int(11) NOT NULL,
  `atendente` int(1) NOT NULL DEFAULT '0',
  `conteudo` text NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`atendimento_id`),
  KEY `atendimento_id` (`atendimento_id`),
  CONSTRAINT `mensagem_ibfk_1` FOREIGN KEY (`atendimento_id`) REFERENCES `atendimento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;