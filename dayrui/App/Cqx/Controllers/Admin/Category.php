<?php namespace Phpcmf\Controllers\Admin;

class Category extends \Phpcmf\App
{

    public function index() {

        if (!dr_in_array(1, $this->admin['roleid'])) {
            $this->_admin_msg(0, dr_lang('需要超级管理员账号操作'));
        }

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-content');
        if (!$module) {
            $this->_admin_msg(0, dr_lang('系统没有安装内容模块'));
        }

        $share = 0;

        // 设置url
        foreach ($module as $dir => $t) {
            if ($t['share']) {
                $share = 1;
                unset($module[$dir]);
                continue;
            } elseif ($t['hlist'] == 1) {
                unset($module[$dir]);
                continue;
            }
            $module[$dir]['url'] = dr_url(APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/show_index', ['dir' => $dir]);
        }

        if ($share) {
            $tmp['share'] = [
                'name' => '共享',
                'icon' => 'fa fa-share-alt',
                'title' => '共享',
                'url' => dr_url(APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/show_index', ['dir' => 'share']),
                'dirname' => 'share',
            ];
            $one = $tmp['share'];
            $module = dr_array22array($tmp, $module);
        } else {
            $one = reset($module);
        }

        // 只存在一个项目
        dr_count($module) == 1 && dr_redirect($one['url']);

        \Phpcmf\Service::V()->assign([
            'url' => $one['url'],
            'menu' => \Phpcmf\Service::M('auth')->_iframe_menu($module, $one['dirname']),
            'module' => $module,
            'dirname' => $one['dirname'],
        ]);
        \Phpcmf\Service::V()->display('iframe_content.html');exit;
    }

    // 获取树形结构列表
    protected function _get_tree_list($data, $value) {

        $tree = [];
        $auth = $this->get_cache('auth');
        foreach($data as $t) {
            if (!isset($value[$t['id']])) {
                $value[$t['id']] = [];
            }
            $t['name'] = dr_strcut($t['name'], 30);
            $t['setting'] = dr_string2array($t['setting']);

            $t['option'] = '<select data-none-selected-text="无权限" class="bs-select form-control" data-live-search="true" multiple="multiple" data-actions-box="true" name="data['.$t['id'].'][]">';
            if ($auth) {
                foreach ($auth as $a) {
                    if ($a['id'] > 1) {
                        $t['option'].= '<option '.(dr_in_array($a['id'], $value[$t['id']]) ? 'selected' : '').' value="'.$a['id'].'" /> '.$a['name'].' </option>';
                    }
                }
            }
            $t['option'].= '</select>';
            $tree[$t['id']] = $t;
        }

        $str = "<tr class='\$class'>";
        $str.= "<td style='text-align:center'>\$id</td>";
        $str.= "<td>\$spacer<a target='_blank' href='\$url'>\$name</a> </td>";
        //$str.= "<td style='text-align:center;vertical-align: top;'>\$option2</td>";
        $str.= "<td style='overflow:initial'>\$option</td>";
        $str.= "</tr>";


        return \Phpcmf\Service::L('Tree')->init($tree)->html_icon()->get_tree(0, $str);
    }


    public function show_index() {

        $dir = \Phpcmf\Service::L('input')->get('dir');
        $this->module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$dir);
        if (!$this->module) {
            $this->_admin_msg(0, dr_lang('模块[%s]缓存不存在', $dir));
        }

        if (is_file(dr_get_app_dir('module').'Libraries/Category.php')) {
            $this->module['category'] = \Phpcmf\Service::L('category', 'module')->get_category($this->module['dirname']);
        }

        $file = WRITEPATH.'config/cqx.php';
        if (!is_file($file)) {
            $cache = [
                SITE_ID => [
                    'category' => [],
                ],
                'role' => [],
                'group' => [],
            ];
        } else {
            $cache = require $file;
        }

        $auth = $this->get_cache('auth');

        $max = ini_get("max_input_vars");
        $len = dr_count($this->module['category']) * dr_count($auth);
        if ($len && $max && ($len + 100) > $max) {
            $this->_admin_msg(0, 'PHP.ini中需要扩大max_input_vars至'.($len*2).'以上');
        }

        //$this->module['category'] = \Phpcmf\Service::L('category', 'module')->get_category($this->module['mid']);
        if (IS_POST) {
            $data = [];
            $post = \Phpcmf\Service::L('input')->post('data', true);
            foreach ($this->module['category'] as $t) {
                if ($auth) {
                    foreach ($auth as $a) {
                        if ($a['id'] > 1) {
                            if (dr_in_array($a['id'], $post[$t['id']])) {
                                // 说明勾上了
                                $data[$t['id']][$a['id']] = $a['id'];
                            }
                        }
                    }
                }
            }
            $cache[SITE_ID]['category'][$dir] = $data;
            \Phpcmf\Service::L('Config')->file($file, '后台内容权限插件', 32)->to_require($cache);
            $this->_json(1, dr_lang('操作成功'));
        }

        \Phpcmf\Service::V()->assign([
            'list' => $this->_get_tree_list($this->module['category'], $cache[SITE_ID]['category'][$dir]),
            'dirname' => $dir,
            'site_name' => $this->site_info[SITE_ID]['SITE_NAME'],
        ]);
        \Phpcmf\Service::V()->display('category.html');
    }


}
