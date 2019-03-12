CREATE TABLE IF NOT EXISTS `mc_homeblock` (
  `id_homeblock` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `date_register` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_homeblock`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_homeblock_content` (
  `id_content` smallint(3) NOT NULL AUTO_INCREMENT,
  `id_homeblock` smallint(3) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `name_homeblock` varchar(175) DEFAULT NULL,
  `content_homeblock` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `published_homeblock` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_content`),
  KEY `id_homeblock` (`id_homeblock`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_homeblock_content`
  ADD CONSTRAINT `mc_homeblock_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_homeblock_content_ibfk_1` FOREIGN KEY (`id_homeblock`) REFERENCES `mc_homeblock` (`id_homeblock`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'homeblock';