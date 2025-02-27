CREATE TABLE IF NOT EXISTS `{dbprefix}member_paylog` (
    `id` bigint(18) unsigned NOT NULL AUTO_INCREMENT,
    `site` int(10) NOT NULL COMMENT '站点',
    `mid` varchar(100) NOT NULL COMMENT '支付标识',
    `uid` int(10) unsigned NOT NULL COMMENT '付款人',
    `touid` int(10) unsigned DEFAULT 0 COMMENT '收款人',
    `title` varchar(255) NOT NULL COMMENT '支付标题',
    `url` varchar(255) NOT NULL COMMENT '相关链接',
    `value` decimal(10,2) NOT NULL COMMENT '支付金额',
    `money` decimal(10,2) NOT NULL COMMENT '余额',
    `type` varchar(20) NOT NULL COMMENT '支付方式',
    `status` tinyint(1) unsigned NOT NULL COMMENT '支付状态',
    `result` text NOT NULL COMMENT '支付结果',
    `paytime` int(10) unsigned NOT NULL COMMENT '支付时间',
    `inputtime` int(10) unsigned NOT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`),
    KEY `touid` (`touid`),
    KEY `mid` (`mid`),
    KEY `status` (`status`),
    KEY `value` (`value`),
    KEY `paytime` (`paytime`),
    KEY `inputtime` (`inputtime`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='用户支付记录表';