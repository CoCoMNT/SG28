<?php

/**
 * 菜单配置
 */


return [

    'admin' => [

        'member' => [

            'left' => [

                'member-list' => [
                    'link' => [
                        'app-notice' =>
                            [
                                'name' => '站内消息',
                                'icon' => 'fa fa-bell',
                                'uri' => 'notice/home/index',
                            ],

                    ]
                ],


            ],



        ],

    ],


    'member' => [



        'user' => [
            'link' => [

                'app-notice' =>
                    [
                        'name' => '站内消息',
                        'icon' => 'fa fa-bell',
                        'uri' => 'notice/home/index',
                    ],
            ],
        ],







    ],

];