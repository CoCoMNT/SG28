

CREATE TABLE IF NOT EXISTS `{dbprefix}app_sms_gx` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_uid` varchar(50) NOT NULL COMMENT '发送人uid',
  `to_uid` varchar(50) NOT NULL COMMENT '接收人uid',
  `inputtime` int(10) NOT NULL COMMENT '最近时间',
  PRIMARY KEY (`id`),
  KEY `from_uid` (`from_uid`),
  KEY `to_uid` (`to_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='好友关系表';

CREATE TABLE IF NOT EXISTS `{dbprefix}app_sms_content` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `from_uid` varchar(50) NOT NULL COMMENT '发送人uid',
  `to_uid` varchar(50) NOT NULL COMMENT '接收人uid',
  `content` text DEFAULT NULL COMMENT '消息内容',
  `is_read` tinyint(1) unsigned NOT NULL COMMENT '1已读0未读',
  `inputip` varchar(200) NOT NULL COMMENT '客户端ip',
  `inputtime` int(10) unsigned NOT NULL COMMENT '写入时间',
  PRIMARY KEY (`id`),
  KEY `from_uid` (`from_uid`),
  KEY `to_uid` (`to_uid`),
  KEY `inputtime` (`inputtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息记录表';

CREATE TABLE IF NOT EXISTS `{dbprefix}app_sms_new` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) NOT NULL COMMENT '收件人',
  `nums` int(10) unsigned NOT NULL COMMENT '未读数',
  `sendtime` int(10) NOT NULL COMMENT '提醒时间',
  `inputtime` int(10) unsigned NOT NULL COMMENT '最近时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `sendtime` (`sendtime`),
  KEY `inputtime` (`inputtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新的消息表';