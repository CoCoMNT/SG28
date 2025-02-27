<?php namespace Phpcmf\Controllers\Admin;
/* *
 *
 * 本TableDemo的语法参考： http://help.phpcmf.net/445.html
 *
 * */
class Home extends \Phpcmf\Table
{

    public function __construct()
    {
        parent::__construct();
        // 表单显示名称
        $this->name = dr_lang('沟通记录');
        // 模板前缀(避免混淆)
        $this->tpl_prefix = 'log_';
        // 用于表储存的字段，后台可修改的表字段
        // 用于表储存的字段，后台可修改的表字段，设置字段类别参考：http://help.xunruicms.com/1138.html
        $field = array (
            'from_uid' =>
                array (
                    'name' => '发送人',
                    'fieldname' => 'from_uid',
                    'ismain' => 1,
                    'ismember' => 1,
                    'fieldtype' => 'Uid',
                    'setting' => array (
                        'validate' =>
                            array (
                                'isedit' => '1',
                            ),
                    )
                ),
            'to_uid' =>
                array (
                    'name' => '接收人',
                    'fieldname' => 'to_uid',
                    'ismain' => 1,
                    'ismember' => 1,
                    'fieldtype' => 'Uid',
                    'setting' => array (
                        'validate' =>
                            array (
                                'isedit' => '1',
                            ),
                    )
                ),
            'content' =>
                array (
                    'name' => '消息内容',
                    'fieldname' => 'content',
                    'ismain' => 1,
                    'ismember' => 1,
                    'fieldtype' => 'Textarea',
                ),
            'is_read' =>
                array (
                    'name' => '消息状态',
                    'fieldname' => 'is_read',
                    'ismain' => 1,
                    'ismember' => 1,
                    'fieldtype' => 'Radio',
                    'setting' =>
                        array (
                            'option' =>
                                array (
                                    'options' => '已读|1
未读|0',
                                    'is_field_ld' => '0',
                                    'value' => '0',
                                    'fieldtype' => '',
                                    'fieldlength' => '',
                                    'show_type' => '0',
                                    'css' => '',
                                ),
                            'validate' =>
                                array (
                                    'required' => '0',
                                    'pattern' => '',
                                    'errortips' => '',
                                    'check' => '',
                                    'filter' => '',
                                    'formattr' => '',
                                    'tips' => '',
                                ),
                        ),
                ),
            'inputip' =>
                array (
                    'name' => '客户端ip',
                    'fieldname' => 'inputip',
                    'ismain' => 1,
                    'ismember' => 1,
                    'fieldtype' => 'Text',
                ),
            'inputtime' =>
                array (
                    'name' => '发送时间',
                    'fieldname' => 'inputtime',
                    'ismain' => 1,
                    'ismember' => 1,
                    'fieldtype' => 'Date',
                ),
        );

        // 用于列表显示的字段
        $list_field = array (
            'from_uid' =>
                array (
                    'use' => '1',
                    'name' => '发送人uid',
                    'width' => '',
                    'func' => '',
                    'center' => '0',
                ),
            'to_uid' =>
                array (
                    'use' => '1',
                    'name' => '接收人uid',
                    'width' => '',
                    'func' => '',
                    'center' => '0',
                ),
            'content' =>
                array (
                    'use' => '1',
                    'name' => '消息内容',
                    'width' => '',
                    'func' => '',
                    'center' => '0',
                ),
            'is_read' =>
                array (
                    'use' => '1',
                    'name' => '1已读0未读',
                    'width' => '',
                    'func' => '',
                    'center' => '0',
                ),
            'inputip' =>
                array (
                    'use' => '1',
                    'name' => '客户端ip',
                    'width' => '',
                    'func' => '',
                    'center' => '0',
                ),
            'inputtime' =>
                array (
                    'use' => '1',
                    'name' => '写入时间',
                    'width' => '',
                    'func' => '',
                    'center' => '0',
                ),
        );
        // 初始化数据表
        $this->_init([
            'table' => 'app_sms_content',  // （不带前缀的）表名字
            'field' => $field, // 可查询的字段
            'order_by' => 'id desc', // 列表排序，默认的排序方式
            'date_field' => 'inputtime', // 按时间段搜索字段，没有时间字段留空
        ]);
        // 把公共变量传入模板
        \Phpcmf\Service::V()->assign([
            // 搜索字段
            'field' => $field,
            'is_time_where' => $this->init['date_field'],
            // 后台的菜单
            'menu' => \Phpcmf\Service::M('auth')->_admin_menu(
                [
                    $this->name => [APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/index', 'fa fa-envelope'],
                    '修改' => ['hide:'.APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/edit', 'fa fa-edit'],
                    '模拟聊天' => ['hide:'.APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/info_edit', 'fa fa-comments'],
                    '聊天记录' => ['hide:'.APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/info_index', 'fa fa-table'],
                ])
        ]);
    }

    // 查看列表
    public function index() {
        list($tpl) = $this->_List();
        \Phpcmf\Service::V()->display($tpl);
    }

    // 添加内容
    public function add() {
        list($tpl) = $this->_Post(0);
        \Phpcmf\Service::V()->display($tpl);
    }

    // 修改内容
    public function edit() {
        list($tpl) = $this->_Post(intval(\Phpcmf\Service::L('input')->get('id')));
        \Phpcmf\Service::V()->display($tpl);
    }

    // 聊天详情
    public function info_show() {

        $fuid = \Phpcmf\Service::L('input')->get('fuid');
        $tuid = \Phpcmf\Service::L('input')->get('tuid');

        $logs = \Phpcmf\Service::M('sms', 'sms')->log($fuid, $tuid);

        \Phpcmf\Service::V()->assign([
            'logs' => $logs ? array_reverse($logs) : [],
        ]);
        \Phpcmf\Service::V()->display('comments_show.html');exit;
    }


    // 聊天详情
    public function info_index() {

        $fuid = \Phpcmf\Service::L('input')->get('fuid');
        $tuid = \Phpcmf\Service::L('input')->get('tuid');

        $to = \Phpcmf\Service::M('sms', 'sms')->myinfo($tuid);
        $from = \Phpcmf\Service::M('sms', 'sms')->myinfo($fuid);

        $this->_init([
            'table' => 'app_sms_content',
            'order_by' => 'inputtime desc',
            'where_list' => '((to_uid = "'.$tuid.'" and from_uid="'.$fuid.'") or to_uid = "'.$fuid.'" and from_uid="'.$tuid.'")',
        ]);

        list($tpl, $data) = $this->_List();
        $list = [];
        if ($data['list']) {
            foreach ($data['list'] as $t) {
                $user = \Phpcmf\Service::M('sms', 'sms')->myinfo($t['from_uid']);
                $list[] = [
                    'me' => $user['id'] == $this->uid,
                    'id' => $user['id'],
                    'uid' => $user['id'],
                    'name' => $user['name'],
                    'avatar' => $user['avatar'],
                    'content' => $t['content'],
                    'inputtime' => $t['inputtime'],
                ];
            }
        }

        \Phpcmf\Service::V()->assign([
            'to' => $to,
            'from' => $from,
            'list' => $list,
            'reply_url' => dr_url('sms/home/index')
        ]);
        \Phpcmf\Service::V()->display('log_show.html');
    }


    // 删除内容
    public function del() {
        $this->_Del(
            \Phpcmf\Service::L('Input')->get_post_ids(),
            function($rows) {
                // 删除前的验证
                return dr_return_data(1, 'ok', $rows);
            },
            function($rows) {
                // 删除后的处理
                return dr_return_data(1, 'ok');
            },
            \Phpcmf\Service::M()->dbprefix($this->init['table'])
        );
    }

    // 后台批量
    public function status_index() {

        $ids = \Phpcmf\Service::L('input')->get_post_ids();
        if (!$ids) {
            $this->_json(0, dr_lang('所选数据不存在'));
        }

        // 格式化
        $in = [];
        foreach ($ids as $i) {
            $i && $in[] = intval($i);
        }
        if (!$in) {
            $this->_json(0, dr_lang('所选数据不存在'));
        }

        \Phpcmf\Service::M()->db->table($this->init['table'])->whereIn('id', $in)->update([
            'is_read' => 1,
        ]);
   

        $this->_json(1, dr_lang('操作成功'));
    }

    /**
     * 获取内容
     * $id      内容id,新增为0
     * */
    protected function _Data($id = 0) {
        $row = parent::_Data($id);
        // 这里可以对内容进行格式化显示操处理
        return $row;
    }

    // 格式化保存数据
    protected function _Format_Data($id, $data, $old) {
        if ($id) {
            if (!$data[1]['from_uid']) {
                $data[1]['from_uid'] = $old['from_uid'];
            }
            if (!$data[1]['to_uid']) {
                $data[1]['to_uid'] = $old['to_uid'];
            }
        }
        return $data;
    }

}
