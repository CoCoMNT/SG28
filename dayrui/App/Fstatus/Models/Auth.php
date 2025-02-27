<?php namespace Phpcmf\Model\Fstatus;

// 权限验证
class Auth extends \Phpcmf\Model
{

    // 判断链接的显示权限
    public function is_auth($c, $m) {
        if (APP_DIR.'-'.\Phpcmf\Service::L('Router')->method.'-'.\Phpcmf\Service::L('Router')->method == '-home-index') {
            return 0;
        }
        return 1;
    }

    public function is_bottom_auth($mid) {

        $table = \Phpcmf\Service::M()->prefix.SITE_ID.'_'.$mid;
        if (\Phpcmf\Service::M()->db->fieldExists('fstatus', $table)) {
            return 1;
        }

        return 0;
    }

    public function is_link_auth($mid) {

        return 1;
    }

}