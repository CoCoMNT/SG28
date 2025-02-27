<?php namespace Phpcmf\Controllers\Member;

class Paylog extends \Phpcmf\Table
{

    public function __construct()
    {
        parent::__construct();
        // 支持附表存储
        $this->is_data = 0;
        // 表单显示名称
        $this->name = dr_lang('资金流水');
        // 初始化数据表
        $this->_init([
            'table' => 'member_paylog',
            'order_by' => 'inputtime desc',
            'date_field' => 'inputtime',
        ]);
    }

    // index
    public function index() {

        $tid = (int)\Phpcmf\Service::L('input')->get('tid');
        $where = ['`uid`='.$this->uid];
        switch ($tid) {
            case 1: // 收入
                $where[] = '`value` > 0';
                break;
            case -1: // 消费
                $where[] = '`value` < 0';
                break;
            default : // 全部
                break;
        }

        if (isset($this->member_cache['pay']['year']) && $this->member_cache['pay']['year']) {
            $year = (int)$this->member_cache['pay']['year'];
            if ($year) {
                $where[] = 'inputtime > '.strtotime('-'.$year.' year');
            }
        }

        \Phpcmf\Service::M()->set_where_list(implode(' AND ', $where));
        list($tpl, $data) = $this->_List(['tid' => $tid]);

        // 初始化
        $data['param']['tid'] = $data['param']['total'] = 0;

        // 列出类别
        $my = [];
        $type = ['0' => '全部', '1' => '收入', '-1' => '消费'];
        foreach ($type as $i => $t) {
            $data['param']['tid'] = $i;
            $my[$i] = [
                'name' => dr_lang($t),
                'url' => dr_member_url('pay/paylog/index', $data['param'])
            ];
        }

        \Phpcmf\Service::V()->assign([
            'tid' => $tid,
            'type' => $my,
        ]);

        \Phpcmf\Service::V()->display('paylog_index.html');
    }
    
    public function show() {

        $id = \Phpcmf\Service::L('input')->get('id');
        if (strpos($id, '-') !== false) {
            list($a, $id) = explode('-', $id);
        }

        list($a, $data) = $this->_Show((int)$id);
        if (!$data) {
            $this->_msg(0, dr_lang('交易记录不存在'));exit;
        } elseif ($data['uid'] != $this->uid) {
            $this->_msg(0, dr_lang('无权限查看'));exit;
        }

        \Phpcmf\Service::V()->display('paylog_show.html');
    }
    
}
