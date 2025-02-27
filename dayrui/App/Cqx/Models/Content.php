<?php namespace Phpcmf\Model\Cqx; // 这里需要把App改成项目目录名称


class Content extends \Phpcmf\Model
{
    private $config;
    private $data_catid;
    private $data_groupid;

    public function __construct() {
        parent::__construct();
        $file = WRITEPATH.'config/cqx.php';
        if (!is_file($file)) {
            $this->config = [
                SITE_ID => [
                    'category' => [],
                ],
                'role' => [],
                'group' => [],
            ];
        } else {
            $this->config = require $file;
        }
    }

    // 可管理的角色管理权限
    public function myrole() {

        $my = [];
        if (isset($this->config['role'])) {
            $ids = [];
            foreach (\Phpcmf\Service::C()->admin['roleid'] as $gid) {
                if (isset($this->config['role'][$gid]) && $this->config['role'][$gid]) {
                    foreach ($this->config['role'][$gid] as $t => $v) {
                        $ids[] = $t;
                    }
                }
            }
            if ($ids) {
                // 不能管理的组
                $role = \Phpcmf\Service::C()->get_cache('auth');
                foreach ($role as $rid => $t) {
                    if ($rid > 1) {
                        if (dr_in_array($rid, $ids)) {

                            $my[] = $rid;
                        }
                    }
                }
            }
        }
        if (!$my) {
            return \Phpcmf\Service::C()->admin['roleid'];
        } else {
            return $my;
        }
    }

    // 后台管理的列表条件
    public function get_list_where($table = '') {

        if (\Phpcmf\Service::C()->admin['adminid'] == 1) {
            return '';
        }

        $where = [];

        $catid = $this->_get_role_catid(MOD_DIR);
        if ($catid) {
            $where[] = 'catid IN('.implode(',', $catid).')';
        }

        $gid = $this->_get_role_groupid();
        if ($gid) {
            $where[] = 'uid IN(select uid from '.$this->dbprefix('member_group_index').' where gid in('.implode(',', $gid).'))';
        }

        return $where ? '('.implode(' AND ', $where).')' : 'catid=-2';
    }

    // 获取当前管理员不能管辖的用户id
    private function _get_role_groupid() {

        $cname = md5(dr_array2string(\Phpcmf\Service::C()->admin['roleid']));
        if (isset($this->data_groupid[$cname])) {
            return $this->data_groupid[$cname];
        }

        $ids = [];
        if (isset($this->config['group'])) {
            foreach ($this->config['group'] as $gid => $t) {
                // $t的主键是角色id
                if ($t && dr_array_intersect($t, \Phpcmf\Service::C()->admin['roleid'])) {
                    // 说明此此管理员能看到这个栏目
                    $ids[] = $gid;
                }
            }
        }

        if ($ids) {
            $this->data_groupid[$cname] = $ids = array_unique($ids);
        }

        return $ids;

    }

    // 根据反向存储获取正向对应的值
    private function _get_f_role($ids) {
        $rt = [];
        // 角色权限缓存
        $role = \Phpcmf\Service::C()->get_cache('auth');
        foreach ($role as $rid => $t) {
            if (isset($ids[$rid]) && $ids[$rid]) {
                $rt[] = $rid;
            }
        }
        return $rt;
    }

    // 获取当前管理员不能管辖的栏目id
    public function _get_role_catid($mid) {

        $cname = md5($mid.dr_array2string(\Phpcmf\Service::C()->admin['roleid']));
        if (isset($this->data_catid[$cname])) {
            return $this->data_catid[$cname];
        }

        // 判断是否是共享栏目
        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$mid);
        if ($module['share']) {
            $mid = 'share';
        }

        if (isset($this->config[SITE_ID]['category'][$mid])) {
            $my = $this->config[SITE_ID]['category'][$mid];
            foreach ($my as $catid => $t) {
                // $t的主键是角色id
                if ($t && dr_array_intersect($t, \Phpcmf\Service::C()->admin['roleid'])) {
                    // 表示有的权限
                    continue;
                }
                unset($my[$catid]);
            }
            $this->data_catid[$cname] = array_keys($my);
            return $this->data_catid[$cname];
        } else {
            return [];
        }
    }

    // 判断是否有权限管理栏目，1表示没权限，0有权限
    public function is_edit($catid, $uid = 0) {

        // 当前是超管登录放过验证
        if (\Phpcmf\Service::C()->admin['adminid'] == 1) {
            return 0;
        }

        $ids = $this->_get_role_catid(APP_DIR ? APP_DIR : 'share');
        if ($ids && dr_in_array($catid, $ids)) {
            return 0;
        }

        if ($uid) {
            $user = dr_member_info($uid);
            $gid = $this->_get_role_groupid();
            if ($gid && $user['groupid']) {
                foreach ($user['groupid'] as $id => $t) {
                    if (dr_in_array($id, $gid)) {
                        return 0;
                    }
                }
            }
        }

        return 1;
    }


}