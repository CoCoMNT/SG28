<?php namespace Phpcmf\Controllers\Admin;
/* *
 *
 * {fname}
 *
 * */
class {cname} extends \Phpcmf\Table
{

    private $cid;

    public function __construct()
    {
        parent::__construct();
        // 表单显示名称
        $this->name = dr_lang('{fname}');
        // 模板前缀(避免混淆)
        $this->tpl_prefix = '{tpl_prefix}';
    }

    public function index() {

        // 初始化数据表
        $this->_init([
            'table' => '{table}',  // （不带前缀的）表名字
            'field' => \Phpcmf\Service::M('field')->get_mytable_field('{table}', 0),
        ]);

        list($tpl) = $this->_Post(0);

        // 把公共变量传入模板
        \Phpcmf\Service::V()->assign([
            // 后台的菜单
            'menu' => \Phpcmf\Service::M('auth')->_admin_menu(
                [
                    $this->name => [APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/index', 'fa fa-code'],
                    '自定义字段' => ['show:'.'field/index{rname=table-{table}&rid=0&is_menu=1}', 'fa fa-code', '80%', '90%'],
                ]),
            'reply_url' => '',
        ]);
        \Phpcmf\Service::V()->display($tpl);
    }

    /**
     * 获取内容
     * $id      内容id,新增为0
     * */
    protected function _Data($id = 0) {

        $row = $this->_db()->init($this->init)->where('siteid='.SITE_ID)->getRow();
        if (!$row) {
            return [];
        }

        $this->cid = $row['id'];
        return $row;
    }

    // 格式化保存数据
    protected function _Format_Data($id, $data, $old) {
        $data[1]['uid'] = $this->uid;
        $data[1]['siteid'] = SITE_ID;
        return $data;
    }

    // 保存内容
    protected function _Save($id = 0, $data = [], $old = [], $func = null, $func2 = null) {
        $id = $this->cid;
        return parent::_Save($id, $data, $old, function($id, $data, $old){
            // 验证数据
            /*
            if (!$data[1]['title']) {
                return dr_return_data(0, '标题不能为空！', ['field' => 'title']);
            }*/
            // 保存之前执行的函数，并返回新的数据
            if (!$id) {
                // 当提交新数据时，把当前时间插入进去
                //$data[1]['inputtime'] = SYS_TIME;
            }

            return dr_return_data(1, null, $data);
        }, function ($id, $data, $old) {
            // 保存之后执行的动作
        });
    }

}
