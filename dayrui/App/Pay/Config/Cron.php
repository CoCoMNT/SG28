<?php

// 3天清理一次系统缓存
if (!is_file(WRITEPATH.'config/run_auto_pay_time.php')) {
    $time = SYS_TIME;
    file_put_contents(WRITEPATH.'config/run_auto_pay_time.php', $time);
} else {
    $time = file_get_contents(WRITEPATH.'config/run_auto_pay_time.php');
}

// 3天清理一次系统缓存
if (SYS_TIME - $time > 3600 * 24 * 3) {
    // 未付款的清理
    \Phpcmf\Service::M('pay', 'pay')->clear_paylog();
    file_put_contents(WRITEPATH.'config/run_auto_pay_time.php', SYS_TIME);
}
