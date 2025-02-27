<?php

/**
 * 菜单配置
 */


return [

    'admin' => [

        'auth' => [

            'left' => [


                'auth-admin' => [
                    'link' => [
                        [
                            'name' => '栏目权限',
                            'icon' => 'fa fa-th-large',
                            'uri' => 'cqx/category/index',
                        ],
                        [
                            'name' => '角色级别',
                            'icon' => 'fa fa-user',
                            'uri' => 'cqx/role/index',
                        ],
                        [
                            'name' => '用户组归属',
                            'icon' => 'fa fa-users',
                            'uri' => 'cqx/group/index',
                        ],

                    ]
                ],
            ],



        ],

    ],
];