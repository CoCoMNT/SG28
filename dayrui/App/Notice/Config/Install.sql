CREATE TABLE IF NOT EXISTS `{dbprefix}member_notice` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `type` tinyint(2) unsigned NOT NULL COMMENT '类型',
    `uid` mediumint(8) unsigned NOT NULL COMMENT '通知者uid',
    `isnew` tinyint(1) unsigned NOT NULL COMMENT '新提醒',
    `content` text NOT NULL COMMENT '通知内容',
    `url` varchar(255) NOT NULL COMMENT '相关地址',
    `mark` varchar(255) NOT NULL COMMENT '标记字符',
    `inputtime` int(10) unsigned NOT NULL COMMENT '提交时间',
    PRIMARY KEY (`id`),
    KEY (`isnew`),
    KEY `type` (`type`,`uid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='会员通知提醒表';