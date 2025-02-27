<?php
/**
 * 控制器 - 任务计划执行
 */

$new = \Phpcmf\Service::M()->table('app_sms_new')->where('nums > 0 and sendtime = 0')->getAll();
if ($new) {

    $ids = [];
    foreach ($new as $t) {
        $t['url'] = dr_member_url('sms/home/index');
        \Phpcmf\Service::L('Notice')->send_notice('sms_notice', $t);
        $ids[] = $t['id'];
    }

    \Phpcmf\Service::M()->db->table('app_sms_new')->whereIn('id', $ids)->update([
        'sendtime' => SYS_TIME,
    ]);
}
