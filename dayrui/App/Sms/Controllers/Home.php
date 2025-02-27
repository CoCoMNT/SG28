<?php namespace Phpcmf\Controllers;

class Home extends \Phpcmf\App
{

    public function index() {

        $app = \Phpcmf\Service::M('app')->get_config(APP_DIR);
        $uid = dr_safe_filename(\Phpcmf\Service::L('input')->get('uid'));

        if ($uid) {
            $user = \Phpcmf\Service::M('sms', 'sms')->myinfo($uid);
        } else {
            $user = [];
        }

        // 我自己的信息
        $my = \Phpcmf\Service::M('sms', 'sms')->myinfo($this->uid);

        \Phpcmf\Service::V()->assign([
            'my' => $my,
            'user' => $user,
            'from_uid' => $uid,
            'meta_title' => $app['meta_title'] ? $app['meta_title'] : '私信',
        ]);

        $file = 'im/index.html';
        if (!is_file(\Phpcmf\Service::V()->get_dir().$file)) {
            \Phpcmf\Service::V()->admin();
        }
        \Phpcmf\Service::V()->display($file);exit;
    }

    public function weixin() {

        // 判断微信标记组
        if (dr_is_app('weixin')) {
            \Phpcmf\Service::C()->init_file('weixin');


            $code = \Phpcmf\Service::L('input')->get('code');
            $state = \Phpcmf\Service::L('input')->get('state');

            //通过code获得openid
            if (!$code){
                //触发微信返回code码
                $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);
                $url = weixin_CreateOauthUrlForCode($baseUrl);
                header ("Location: $url");
                exit();
            } else {
                //获取code码，以获取openid
                $openid = weixin_getOpenidFromMp($code);
                if (!$openid) {
                    $uid = (int)$this->session()->get("wxuid");
                    if ($uid) {
                        $user = \Phpcmf\Service::M()->table('weixin_user')->where('uid', $uid)->getRow();
                    }
                } else {
                    $user = \Phpcmf\Service::M()->table('weixin_user')->where('openid', $openid)->getRow();
                }
                if (!$user) {
                    $this->_msg(0, '你还没有关注微信公众号', dr_member_url('login/index'));
                } elseif (!$user['uid']) {
                    $this->_msg(0, '你没有绑定用户账号', dr_member_url('login/index'));
                }
                $this->session()->set("wxuid", $user['uid']);
            }

            // 会员登录
            $member = dr_member_info($user['uid']);
            if (!$member) {
                $this->_msg(0, '未绑定用户账号');
            }

            \Phpcmf\Service::M('member')->save_cookie($member, 1);

            $this->uid = $user['uid'];
            $this->member = $member;
            $this->index();

        } else {
            exit('没有安装微信插件');
        }

    }

}
