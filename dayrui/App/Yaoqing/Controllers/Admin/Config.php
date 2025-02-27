<?php namespace Phpcmf\Controllers\Admin;

class Config extends \Phpcmf\Common
{

	public function index() {

	    $data = \Phpcmf\Service::M('app')->get_config(APP_DIR);

        if (IS_AJAX_POST) {

            $post = \Phpcmf\Service::L('input')->post('data');

            \Phpcmf\Service::M('app')->save_config(APP_DIR, $post);

            $this->_json(1, dr_lang('操作成功'));
        }

        $page = intval(\Phpcmf\Service::L('input')->get('page'));

        \Phpcmf\Service::V()->assign([
            'page' => $page,
            'data' => $data,
            'form' => dr_form_hidden(['page' => $page]),
            'menu' => \Phpcmf\Service::M('auth')->_admin_menu(
                [
                    '邀请设置' => [APP_DIR.'/config/index', 'fa fa-cog'],
                    'help' => [548],
                ]
            ),
            'code' => nl2br(file_get_contents(APPPATH.'hook.php')),
        ]);
        \Phpcmf\Service::V()->display('config.html');

	}

    // 钩子测试
    public function test_index() {

        define('APP_TEST_HOOKS', 1);
        $this->session()->set('app_yaoqing_uid', 1);
        \Phpcmf\Hooks::trigger('member_register_after', ['test']);

        $this->_json(0, dr_lang('测试未安装成功'));
    }


}
