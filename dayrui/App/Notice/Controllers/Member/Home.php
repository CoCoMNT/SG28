<?php namespace Phpcmf\Controllers\Member;

class Home extends \Phpcmf\Table
{

    public function __construct()
    {
        parent::__construct();
        // 支持附表存储
        $this->is_data = 0;
        // 表单显示名称
        $this->name = dr_lang('提醒');
        // 初始化数据表
        $this->_init([
            'table' => 'member_notice',
            'order_by' => 'inputtime desc',
        ]);
        \Phpcmf\Service::M()->db->table($this->init['table'])->where('uid', $this->uid)->update(['isnew' => 0]);
    }

    // index
    public function index() {

        $tid = (int)\Phpcmf\Service::L('input')->get('tid');
        $where = ['`uid`='.$this->uid];
        $tid && $where[] = '`type`='.$tid;

        \Phpcmf\Service::M()->set_where_list(implode(' AND ', $where));
        list($tpl, $data) = $this->_List(['tid' => $tid]);

        // 初始化
        $data['param']['tid'] = $data['param']['total'] = 0;

        // 列出类别
        $type = [
            0 => [
                'name' => dr_lang('全部'),
                'icon' => '<i class="fa fa-bell"></i>',
            ],
        ];
        $my = dr_notice_info();
        if ($my) {
            foreach ($my as $i => $t) {
                $t['icon'] ='<i class="'.$t['icon'].'"></i>';
                $type[$i] = $t;
            }
        }
        foreach ($type as $i => $t) {
            $data['param']['tid'] = $i;
            $type[$i]['url'] =\Phpcmf\Service::L('Router')->member_url('notice/home/index', $data['param']);
        }

        \Phpcmf\Service::V()->assign([
            'tid' => $tid,
            'type' => $type,
        ]);
        \Phpcmf\Service::V()->display('notice_index.html');
    }

    public function go() {

        $id = (int)\Phpcmf\Service::L('input')->get('id');
        $data = \Phpcmf\Service::M()->table('member_notice')->where('id', $id)->where('uid', $this->uid)->getRow();
        if (!$data) {
            $this->_msg(0, dr_lang('此消息不存在'));
        } elseif ($data['url']) {
            \Phpcmf\Service::M()->db->table('member_notice')->where('id', $id)->update(['isnew' => 0]);
            dr_redirect($data['url']);
        } else {
            dr_redirect(dr_member_url('notice/home/index'));
        }
        exit;
    }


}
