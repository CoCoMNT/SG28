<?php

/**
 * 更新数据结构
 **/

$prefix = \Phpcmf\Service::M()->prefix;

$table = $prefix.'member_paylog';
if (\Phpcmf\Service::M()->db->tableExists($table)) {
    if (!\Phpcmf\Service::M()->db->fieldExists('money', $table)) {
        \Phpcmf\Service::M()->query('ALTER TABLE `'.$table.'` ADD `money` decimal(10,2) NOT NULL COMMENT \'余额\'');
    }
}
