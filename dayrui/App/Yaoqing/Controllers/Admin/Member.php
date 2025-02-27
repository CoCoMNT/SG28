<?php namespace Phpcmf\Controllers\Admin;

// 邀请关系
class Member extends \Phpcmf\Table
{
    public function __construct()
    {
        parent::__construct();
        // 表单显示名称
        $this->name = dr_lang('邀请关系');
        // 模板前缀(避免混淆)
        $this->tpl_prefix = 'member_';
        // 入库字段，实例中用到两个字段录入
        $field = array(
            'username' => array(
                'ismain' => 1,
                'name' => dr_lang('用户账号'),
                'fieldname' => 'username',
                'fieldtype' => 'Text',
                'setting' => array(
                    'option' => array(
                        'width' => 400,
                    ),
                )
            ),
            'uid' => array(
                'ismain' => 1,
                'name' => dr_lang('用户id'),
                'fieldname' => 'uid',
                'fieldtype' => 'Text',
                'setting' => array(
                    'option' => array(
                        'width' => 400,
                    ),
                )
            ),
            'pusername' => array(
                'ismain' => 1,
                'name' => dr_lang('父级账号'),
                'fieldname' => 'pusername',
                'fieldtype' => 'Text',
                'setting' => array(
                    'option' => array(
                        'width' => 400,
                    ),
                )
            ),
            'puid' => array(
                'ismain' => 1,
                'name' => dr_lang('父级id'),
                'fieldname' => 'puid',
                'fieldtype' => 'Text',
                'setting' => array(
                    'option' => array(
                        'width' => 400,
                    ),
                )
            ),
        );
        // 初始化数据表
        $this->_init([
            'table' => 'app_yaoqing_user', // 表的名字
            'field' => $field, // 设置入库字段
            'show_field' => 'username', // 表的主字段
            'order_by' => 'id desc', // 列表排序显示方式
        ]);
        \Phpcmf\Service::V()->assign([
            'field' => $field,
            'menu' => \Phpcmf\Service::M('auth')->_admin_menu(
                [
                    $this->name => [APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/index', 'fa fa-user-md'],
                    '添加' => [APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/add', 'fa fa-plus'],
                    '修改' => ['hide:'.APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/edit', 'fa fa-edit'],
                ]
            ),
        ]);
    }

    public function index() {
        list($tpl) = $this->_List(); // 完成table自动查询并分页显示动作
        \Phpcmf\Service::V()->display($tpl); // 设定显示模板
    }

    public function add() {
        list($tpl) = $this->_Post(0); // 0表示新增post数据

        \Phpcmf\Service::V()->display($tpl);// 设定表单模板
    }

    public function edit() {
        // 传入id到post方法表示修改此id提交的数据
        list($tpl) = $this->_Post(intval(\Phpcmf\Service::L('Input')->get('id')));
        \Phpcmf\Service::V()->display($tpl);
    }

    public function del() {
        $this->_Del(
            \Phpcmf\Service::L('Input')->get_post_ids(), // 获取批量删除id号
            null, // 删除之前的函数验证
            null, // 删除之后的函数处理
            \Phpcmf\Service::M()->dbprefix($this->init['table']) // 设定删除表名称
        );
    }


    protected function _Save($id = 0, $data = [], $old = [], $func = null, $func2 = null) {
        return parent::_Save($id, $data, $old, function($id, $data, $old){
            // 保存之前执行的函数，并返回新的数据

            if ($data[1]['username'] == $data[1]['pusername']) {
                $this->_json(0, '账号不能相同');
            }

            $data[1]['uid'] = \Phpcmf\Service::M('member')->uid($data[1]['username']);
            $data[1]['puid'] = \Phpcmf\Service::M('member')->uid($data[1]['pusername']);

            if (!$data[1]['uid']) {
                $this->_json(0, '账号['.$data[1]['username'].']不存在');
            } elseif (!$data[1]['puid']) {
                $this->_json(0, '账号['.$data[1]['pusername'].']不存在');
            }

            $data[1]['czfx'] = intval($_POST['data']['czfx']);

            if (!$id) {
                $data[1]['money'] = 0;
                $data[1]['inputtime'] = SYS_TIME;
                if (\Phpcmf\Service::M()->table('app_yaoqing_user')->where('uid', $data[1]['uid'])->where('puid', $data[1]['puid'])->getRow()) {
                    $this->_json(0, '账号关系已经存在');
                }
            } else {
                if (\Phpcmf\Service::M()->table('app_yaoqing_user')->where('id<>'.$id)->where('uid', $data[1]['uid'])->where('puid', $data[1]['puid'])->getRow()) {
                    $this->_json(0, '账号关系已经存在');
                }
            }

            $gx = \Phpcmf\Service::M()->table('app_yaoqing_user')->where('uid', $data[1]['puid'])->where('puid', $data[1]['uid'])->getRow();
            if ($gx) {
                $this->_json(0, '无法建立此关系');
            }

            return dr_return_data(1, null, $data);
        }, function ($id, $data, $old) {
            // 保存之后执行的动作
        });
    }


}
