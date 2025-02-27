<?php

/**
 * 菜单配置
 */


return [

    'admin' => [



        'app-pay' => [
            'name' => '财务',
            'icon' => 'fa fa-rmb',
            'left' => [
                'app-pay-list' => [
                    'name' => '财务管理',
                    'icon' => 'fa fa-rmb',
                    'link' => [
                        [
                            'name' => '已付流水',
                            'icon' => 'fa fa-calendar-check-o',
                            'uri' => 'pay/paylog/index',
                            'displayorder' => '-1',
                        ],
                        [
                            'name' => '未付流水',
                            'icon' => 'fa fa-calendar-times-o',
                            'uri' => 'pay/paylog/not_index',
                            'displayorder' => '-1',
                        ],
                        [
                            'name' => '用户资金',
                            'icon' => 'fa fa-plus',
                            'uri' => 'pay/pay/index',
                            'displayorder' => '-1',
                        ],
                    ]
                ],

                'app-config-pay' => [
                    'name' => '支付设置',
                    'icon' => 'fa fa-rmb',
                    'link' => [
                        [
                            'name' => '支付设置',
                            'icon' => 'fa fa-cog',
                            'uri' => 'pay/payconfig/index',
                        ],
                        [
                            'name' => '支付接口',
                            'icon' => 'fa fa-code',
                            'uri' => 'pay/payapi/index',
                        ],
                    ],
                ],

            ],
        ],




    ],

    'member' => [



        'app-pay' => [
            'name' => '财务管理',
            'icon' => 'fa fa-rmb',
            'link' => [
                [
                    'name' => '在线充值',
                    'icon' => 'fa fa-rmb',
                    'uri' => 'pay/recharge/index',
                    'displayorder' => '-1',
                ],
                [
                    'name' => '我的交易',
                    'icon' => 'fa fa-calendar',
                    'uri' => 'pay/paylog/index',
                    'displayorder' => '-1',
                ],
            ],
        ],

    ],



];