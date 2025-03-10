<?php

/**
 * 更新数据结构
 **/

$prefix = \Phpcmf\Service::M()->prefix;

// 模块
$is_module = \Phpcmf\Service::M()->db->tableExists('module');
if ($is_module) {
    $module = \Phpcmf\Service::M()->table('module')->order_by('displayorder ASC,id ASC')->getAll();
    // 栏目模型字段修正
    \Phpcmf\Service::M()->db->table('field')->where('relatedname', 'share-'.SITE_ID)->update(['relatedname' => 'catmodule-share']);
    if ($module) {
        foreach ($module as $m) {
            if (!\Phpcmf\Service::M()->table('field')->where('relatedname', 'module')
                ->where('relatedid', $m['id'])->where('fieldname', 'author')->counts()) {
                \Phpcmf\Service::M()->db->table('field')->insert(array(
                    'name' => '笔名',
                    'fieldname' => 'author',
                    'fieldtype' => 'Text',
                    'relatedid' => $m['id'],
                    'relatedname' => 'module',
                    'isedit' => 1,
                    'ismain' => 1,
                    'ismember' => 1,
                    'issystem' => 1,
                    'issearch' => 1,
                    'disabled' => 0,
                    'setting' => dr_array2string(array(
                        'is_right' => 1,
                        'option' => array(
                            'width' => 200, // 表单宽度
                            'fieldtype' => 'VARCHAR', // 字段类型
                            'fieldlength' => '255', // 字段长度
                            'value' => '{name}'
                        ),
                        'validate' => array(
                            'xss' => 1, // xss过滤
                        )
                    )),
                    'displayorder' => 0,
                ));
            }
        }
    }
}


// 站点
foreach ($this->site as $siteid) {
    // 升级栏目表
    if ($is_module) {
        $table = $prefix . $siteid . '_share_category';
        if (\Phpcmf\Service::M()->db->tableExists($table)) {
            // 创建字段 代码
            if (!\Phpcmf\Service::M()->db->fieldExists('disabled', $table)) {
                \Phpcmf\Service::M('table')->add_field($table, 'disabled', 'tinyint(1)', 'DEFAULT \'0\'', '');
                \Phpcmf\Service::M()->query('UPDATE `' . $table . '` SET `disabled` = 0');
            }
            \Phpcmf\Service::M()->query('UPDATE `' . $table . '` SET `disabled` = 0 WHERE `disabled` IS NULL ');
            if (!\Phpcmf\Service::M()->db->fieldExists('ismain', $table)) {
                \Phpcmf\Service::M('table')->add_field($table, 'ismain', 'tinyint(1)', 'DEFAULT \'0\'', '');
                \Phpcmf\Service::M()->query('UPDATE `' . $table . '` SET `ismain` = 1');
            }
        }
        if ($module) {
            foreach ($module as $m) {
                $mtable = $prefix . $siteid . '_' . $m['dirname'];
                if (!\Phpcmf\Service::M()->db->tableExists($mtable)) {
                    continue;
                }
                // 附表字段是否同步
                $tables = [];
                $otable = $mtable.'_data_0'; // 母表
                list($a, $sql) = \Phpcmf\Service::M('table')->create_table_sql($otable);

                $old = \Phpcmf\Service::M('table')->show_full_colunms($otable);

                // 模块附表
                for ($i = 1; $i < 200; $i ++) {
                    // 新表是否存在
                    if (!\Phpcmf\Service::M()->db->tableExists($mtable.'_data_'.$i)) {
                        break;
                    }
                    $new = \Phpcmf\Service::M('table')->show_full_colunms($mtable.'_data_'.$i);
                    foreach ($old as $t) {
                        $td = 0;
                        foreach ($new as $n) {
                            if ($t['Field'] == $n['Field']) {
                                $td = 1;
                                break;
                            }
                        }
                        if ($td == 0 && $sql[$t['Field']] && !\Phpcmf\Service::M()->db->fieldExists($t['Field'], $mtable.'_data_'.$i)) {
                            // 新增表字段
                            $sql2 = 'alter table `'.$mtable.'_data_'.$i.'` add '.$sql[$t['Field']];
                            \Phpcmf\Service::M()->db->query($sql2);
                        }
                    }
                }

                // 增加长度
                $table = $prefix . $siteid . '_' . $m['dirname'];
                if (\Phpcmf\Service::M()->db->fieldExists('inputip', $table)) {
                    \Phpcmf\Service::M('table')->edit_field($table, 'inputip', 'VARCHAR(100)', 'NOT NULL', '客户端ip信息');
                }
                $table = $prefix . $siteid . '_' . $m['dirname'] . '_recycle';
                if (\Phpcmf\Service::M()->db->tableExists($table)) {
                    // 创建字段 删除理由
                    if (!\Phpcmf\Service::M()->db->fieldExists('result', $table)) {
                        \Phpcmf\Service::M('table')->add_field($table, 'result', 'Text', 'NOT NULL', '');
                    }
                }
                $table = $prefix . $siteid . '_' . $m['dirname'] . '_support';
                if (\Phpcmf\Service::M()->db->tableExists($table)) {
                    // 创建字段 游客点赞
                    if (!\Phpcmf\Service::M()->db->fieldExists('agent', $table)) {
                        \Phpcmf\Service::M('table')->add_field($table, 'agent', 'VARCHAR(200)', 'DEFAULT NULL', '');
                    }
                }
                $table = $prefix . $siteid . '_' . $m['dirname'] . '_oppose';
                if (\Phpcmf\Service::M()->db->tableExists($table)) {
                    // 创建字段 游客点赞
                    if (!\Phpcmf\Service::M()->db->fieldExists('agent', $table)) {
                        \Phpcmf\Service::M('table')->add_field($table, 'agent', 'VARCHAR(200)', 'DEFAULT NULL', '');
                    }
                }
                $table = $prefix . $siteid . '_' . $m['dirname'] . '_verify';
                if (!\Phpcmf\Service::M()->db->fieldExists('vid', $table)) {
                    \Phpcmf\Service::M('table')->add_field($table, 'vid', 'INT(10)', 'DEFAULT NULL', '');
                }
                if (!\Phpcmf\Service::M()->db->fieldExists('islock', $table)) {
                    \Phpcmf\Service::M('table')->add_field($table, 'islock', 'tinyint(1)', 'DEFAULT NULL', '');
                }
                // 点击时间
                $table = $prefix . $siteid . '_' . $m['dirname'] . '_hits';
                foreach (['day_time', 'week_time', 'month_time', 'year_time'] as $a) {
                    if (!\Phpcmf\Service::M()->db->fieldExists($a, $table)) {
                        \Phpcmf\Service::M('table')->add_field($table, $a, 'INT(10)', 'DEFAULT NULL', '');
                    }
                }
                $table = $prefix . $siteid . '_' . $m['dirname'] . '_category';
                if (\Phpcmf\Service::M()->db->tableExists($table)) {
                    if (!\Phpcmf\Service::M()->db->fieldExists('disabled', $table)) {
                        \Phpcmf\Service::M('table')->add_field($table, 'disabled', 'tinyint(1)', 'DEFAULT \'0\'', '');
                        \Phpcmf\Service::M()->query('UPDATE `' . $table . '` SET `disabled` = 0');
                    }
                    \Phpcmf\Service::M()->query('UPDATE `' . $table . '` SET `disabled` = 0 WHERE `disabled` IS NULL ');
                    if (!\Phpcmf\Service::M()->db->fieldExists('ismain', $table)) {
                        \Phpcmf\Service::M('table')->add_field($table, 'ismain', 'tinyint(1)', 'DEFAULT \'0\'', '');
                        \Phpcmf\Service::M()->query('UPDATE `' . $table . '` SET `ismain` = 1');
                    }
                }
                // 栏目模型字段修正
                \Phpcmf\Service::M()->db->table('field')->where('relatedname', $m['dirname'] . '-' . $siteid)->update(['relatedname' => 'catmodule-' . $m['dirname']]);
                // 无符号修正
                //\Phpcmf\Service::M()->query('ALTER TABLE `'.$prefix.$siteid.'_'.$m['dirname'].'` CHANGE `updatetime` `updatetime` INT(10) NOT NULL COMMENT \'更新时间\'');
                //\Phpcmf\Service::M()->query('ALTER TABLE `'.$prefix.$siteid.'_'.$m['dirname'].'` CHANGE `inputtime` `inputtime` INT(10) NOT NULL COMMENT \'更新时间\'');
            }
        }
    }
}