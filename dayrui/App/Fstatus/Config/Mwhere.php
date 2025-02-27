<?php
/**
 * 针对module标签及search标签的where条件
 * $siteid 是站点id号
 * $mid 是模块目录
 * $field 模块主表的可用字段
 */

if ($field && in_array('fstatus', $field)) {
    return '`fstatus`=1';
}

// 不满足条件就不进行了
return false;
