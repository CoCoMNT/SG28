<?php

/**
 * 更新数据结构
 **/

$prefix = \Phpcmf\Service::M()->prefix;

// 站点
foreach ($this->site as $siteid) {
    // 升级资料库
    $table = $prefix.$siteid.'_block';
    if (\Phpcmf\Service::M()->db->tableExists($table)) {
        // 创建code字段 代码
        if (!\Phpcmf\Service::M()->db->fieldExists('code', $table)) {
            \Phpcmf\Service::M()->query('ALTER TABLE `'.$table.'` ADD `code` VARCHAR(100) NOT NULL');
        }
    }
}