DROP TABLE IF EXISTS `{dbprefix}block`;
CREATE TABLE IF NOT EXISTS `{dbprefix}block` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '名称',
  `code` varchar(100) NOT NULL COMMENT '别名',
  `hide` tinyint(1) unsigned NOT NULL COMMENT '隐藏',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资料块表';