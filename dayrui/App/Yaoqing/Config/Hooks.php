<?php

// 注册之后判断是否来自邀请
\Phpcmf\Hooks::on('member_register_after', function($data) {
    $puid = \Phpcmf\Service::C()->session()->get('app_yaoqing_uid');
    if ($puid) {
        \Phpcmf\Service::M('yq', 'yaoqing')->register($puid, $data);
    }
});

// 充值的回调
\Phpcmf\Hooks::on('pay_success', function($data) {
    if ($data['mid']== "recharge") {
        \Phpcmf\Service::M('yq', 'yaoqing')->czfx($data);
    }
});
