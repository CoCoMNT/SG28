DROP TABLE IF EXISTS `{dbprefix}app_yaoqing_user`;
CREATE TABLE IF NOT EXISTS `{dbprefix}app_yaoqing_user` (
  `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` INT(10) unsigned NOT NULL COMMENT '当前会员',
  `username` varchar(255) NOT NULL COMMENT '当前会员',
  `puid` INT(10) unsigned NOT NULL COMMENT '父级会员',
  `pusername` varchar(255) NOT NULL COMMENT '父级会员',
  `money` decimal(10,2) unsigned NOT NULL COMMENT '获得金额',
  `czfx` int(10) unsigned NOT NULL COMMENT '充值返现比例',
  `inputtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `puid` (`puid`),
  KEY `inputtime` (`inputtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='邀请用户表';


DROP TABLE IF EXISTS `{dbprefix}app_yaoqing_log`;
CREATE TABLE IF NOT EXISTS `{dbprefix}app_yaoqing_log` (
  `id` INT(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` INT(10) unsigned NOT NULL COMMENT '当前会员',
  `username` varchar(255) NOT NULL COMMENT '当前会员',
  `puid` INT(10) unsigned NOT NULL COMMENT '父级会员',
  `pusername` varchar(255) NOT NULL COMMENT '父级会员',
  `value` decimal(10,2) unsigned NOT NULL COMMENT '金额',
  `money` decimal(10,2) unsigned NOT NULL COMMENT '获得金额',
  `status` INT(10) unsigned NOT NULL COMMENT '状态',
  `content` text NOT NULL COMMENT '日志内容',
  `inputtime` int(10) unsigned NOT NULL COMMENT '写入时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `puid` (`puid`),
  KEY `inputtime` (`inputtime`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='邀请日志记录';




