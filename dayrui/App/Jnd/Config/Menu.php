<?php

/**
 * 菜单配置
 */

return [

    'admin' => [


        // 往已有的菜单下增加链接菜单的写法


        'app' => [
            'left' => [
                'app-plugin' => [ // 把菜单追加到[功能插件]之下
                    'link' => [
                        [
                            'name' => '加拿大28',
                            'icon' => 'fa fa-hand-peace-o',
                            'uri' => 'jnd/home/index',
                        ],
                    ]
                ],
            ],
        ],

    ],

    'member' => [

        'content-module' => [// 把菜单追加到[内容管理]之下
            'link' => [
                [
                    'name' => '加拿大28',
                    'icon' => 'fa fa-hand-peace-o',
                    'uri' => 'jnd/home/index',
                ],
            ],
        ],



    ],

];