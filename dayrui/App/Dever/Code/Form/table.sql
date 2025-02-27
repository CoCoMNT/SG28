CREATE TABLE IF NOT EXISTS `{table}` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `uid` int(10) unsigned NOT NULL,
 `siteid` smallint(5) unsigned NOT NULL,
 PRIMARY KEY (`id`),
 KEY (`siteid`),
 KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='{name}';