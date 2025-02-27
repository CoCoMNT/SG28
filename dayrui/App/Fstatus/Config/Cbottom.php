<?php

return [

    [
        'icon' => 'fa fa-times-circle',
        'name' => dr_lang('设置为关闭状态'),
        'uri' => 'fstatus/home/edit',
        'url' => 'javascript:;" onclick="dr_module_send_ajax(\''.dr_url('fstatus/home/close_edit', ['mid' => '{mid}']).'\')',
    ],

    [
        'icon' => 'fa fa-check-circle',
        'name' => dr_lang('设置为开启状态'),
        'uri' => 'fstatus/home/edit',
        'url' => 'javascript:;" onclick="dr_module_send_ajax(\''.dr_url('fstatus/home/open_edit', ['mid' => '{mid}']).'\')',
    ]

];