<?php

/**
 * 菜单配置
 */


return [

    'admin' => [

        'member' => [

            'left' => [


                'app-yaoqing' => [
                    'name' => '邀请注册',
                    'icon' => 'fa fa-user-md',
                    'link' => [
                        [
                            'name' => '用户关系',
                            'icon' => 'fa fa-user-md',
                            'uri' => 'yaoqing/member/index',
                        ],

                        [
                            'name' => '日志记录',
                            'icon' => 'fa fa-edit',
                            'uri' => 'yaoqing/log/index',
                        ],

                        [
                            'name' => '邀请设置',
                            'icon' => 'fa fa-cog',
                            'uri' => 'yaoqing/config/index',
                        ],

                    ]
                ],
            ],



        ],

    ],


    'member' => [

        'user' => [
            'link' => [
                [
                    'name' => '邀请注册',
                    'icon' => 'fa fa-user-md',
                    'uri' => 'yaoqing/home/index',
                ],
            ],
        ],
    ],
];