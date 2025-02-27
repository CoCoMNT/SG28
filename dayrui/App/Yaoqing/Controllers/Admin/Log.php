<?php namespace Phpcmf\Controllers\Admin;

// 日志
class Log extends \Phpcmf\Table
{
    public function __construct()
    {
        parent::__construct();
        // 表单显示名称
        $this->name = dr_lang('收益日志');
        // 模板前缀(避免混淆)
        $this->tpl_prefix = 'log_';
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
            'content' => array(
                'ismain' => 1,
                'name' => dr_lang('日志内容'),
                'fieldname' => 'content',
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
            'table' => 'app_yaoqing_log', // 表的名字
            'field' => $field, // 设置入库字段
            'show_field' => 'username', // 表的主字段
            'order_by' => 'id desc', // 列表排序显示方式
        ]);
        \Phpcmf\Service::V()->assign([
            'field' => $field,
            'menu' => \Phpcmf\Service::M('auth')->_admin_menu(
                [
                    $this->name => [APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/index', 'fa fa-calendar'],
                ]
            ),
        ]);
    }

    public function status() {

        $ids = \Phpcmf\Service::L('Input')->get_post_ids();
        if (!$ids) {
            $this->_json(0, '未选择任何记录');
        }

        $tid = intval($_GET['tid']);
        \Phpcmf\Service::M()->db->table('app_yaoqing_log')->whereIn('id', $ids)->update([
            'status' => $tid,
        ]);
        $this->_json(1, '操作成功');


    }

    public function index() {
        list($tpl) = $this->_List(); // 完成table自动查询并分页显示动作
        \Phpcmf\Service::V()->display($tpl); // 设定显示模板
    }

    public function del() {
        $this->_Del(
            \Phpcmf\Service::L('Input')->get_post_ids(), // 获取批量删除id号
            null, // 删除之前的函数验证
            null, // 删除之后的函数处理
            \Phpcmf\Service::M()->dbprefix($this->init['table']) // 设定删除表名称
        );
    }


}
