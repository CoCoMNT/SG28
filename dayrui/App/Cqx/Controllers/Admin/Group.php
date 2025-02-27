<?php namespace Phpcmf\Controllers\Admin;


class Group extends \Phpcmf\App
{

    public function index() {
        if (!dr_in_array(1, $this->admin['roleid'])) {
            $this->_admin_msg(0, dr_lang('需要超级管理员账号操作'));
        }
        $auth = $this->get_cache('auth');

        $file = WRITEPATH.'config/cqx.php';
        if (!is_file($file)) {
            $cache = [
                SITE_ID => [
                    'category' => [],
                ],
                'role' => [],
                'group' => [],
            ];
        } else {
            $cache = require $file;
        }

        if (IS_POST) {
            $data = [];
            $post = \Phpcmf\Service::L('input')->post('data', true);
            foreach ($this->member_cache['group'] as $t) {
                if ($auth) {
                    foreach ($auth as $a) {
                        if ($a['id'] > 1 && !$a['application']['tid']) {
                            if (dr_in_array($a['id'], $post[$t['id']])) {
                                // 说明勾上了，
                                $data[$t['id']][$a['id']] = $a['id'];
                            }
                        }
                    }
                }
            }
            $cache['group'] = $data;
            \Phpcmf\Service::L('Config')->file($file, '后台内容权限插件', 32)->to_require($cache);
            $this->_json(1, dr_lang('操作成功'));
        }

        \Phpcmf\Service::V()->assign('menu', \Phpcmf\Service::M('auth')->_admin_menu(
            [
                '用户组归属设置' => ['cqx/group/index', 'fa fa-cog'],
                'help' => [585],
            ]
        ));

        \Phpcmf\Service::V()->assign([
            'auth' => $auth,
            'value' => $cache['group'],
            'group' => $this->member_cache['group'],
        ]);
        \Phpcmf\Service::V()->display('group.html');
    }

}
