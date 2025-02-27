<?php

/**
 * 菜单配置
 */


return [

    'admin' => [

        'app' => [

            'left' => [


                // 分组菜单
                'app-sms' => [
                    'name' => '私信管理',
                    'icon' => 'fa fa-envelope',
                    'link' => [
                        [
                            'name' => '插件配置',
                            'icon' => 'fa fa-cog',
                            'uri' => 'sms/config/index',
                        ],
                        [
                            'name' => '沟通记录',
                            'icon' => 'fa fa-envelope',
                            'uri' => 'sms/home/index',
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
                    'name' => '私信记录',
                    'icon' => 'fa fa-envelope',
                    'uri' => 'sms/home/index',
                ],

            ],
        ],
    ]


];