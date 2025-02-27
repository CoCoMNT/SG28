<?php namespace Phpcmf\Controllers\Admin;

class Home extends \Phpcmf\Common
{

    public function index() {

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-content');
        if (!$module) {
            $this->_admin_msg(0, dr_lang('未安装任何内容模块'));
        }

        $data = [];
        $config = \Phpcmf\Service::M('app')->get_config(APP_DIR);
        foreach ($module as $t) {
            //$m = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$t['dirname']);
            $is_init = $is_field = 0;
            // 是否有字段
            $table = \Phpcmf\Service::M()->dbprefix(SITE_ID.'_'.$t['dirname']);
            if (\Phpcmf\Service::M()->is_field_exists($table, 'fstatus')) {
                $is_field = 1;
                $is_init = isset($config[$t['dirname']]) ? 1 : 0;
            }
            $data[] = [
                'name' => $t['name'],
                'icon' => $t['icon'],
                'dirname' => $t['dirname'],
                'is_init' => $is_init,
                'is_field' => $is_field,
            ];
        }
        if (!$data) {
            $this->_admin_msg(0, dr_lang('未安装任何内容模块'));
        }

        \Phpcmf\Service::V()->assign([
            'menu' => \Phpcmf\Service::M('auth')->_admin_menu(
                [
                    '内容模块权限' => [APP_DIR.'/'.\Phpcmf\Service::L('Router')->class.'/index', 'fa fa-gears'],
                    'help' => [853],
                ]
            ),
            'data' => $data,
        ]);
        \Phpcmf\Service::V()->display('module_qx.html');
    }

    // 创建模块字段
    public function add() {

        $mid = dr_safe_filename($_GET['dir']);
        $row = \Phpcmf\Service::M('module')->table('module')->where('dirname', $mid)->getRow();
        if (!$row) {
            $this->_json(0, dr_lang('此模块[%s]未安装', $mid));
        }

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$mid);
        if (!$module) {
            $this->_json(0, dr_lang('此模块[%s]没有安装在当前站点', $mid));
        }

        $table = \Phpcmf\Service::M()->prefix.SITE_ID.'_'.$mid;

        // 创建字段
        if (!\Phpcmf\Service::M()->db->fieldExists('fstatus', $table)) {
            \Phpcmf\Service::M()->query('ALTER TABLE `'.$table.'` ADD `fstatus` int(1) unsigned DEFAULT \'1\' COMMENT \'状态\';');
            \Phpcmf\Service::M()->db->table($table)->update([
                'fstatus' => 1, // 初始化1表示开启
            ]);
        }

        if (isset($module['is_ctable']) && $module['is_ctable']) {
            $cats = \Phpcmf\Service::M()->table($module['share'] ? SITE_ID.'_share_category' : SITE_ID.'_'.$mid.'_category')->where('is_ctable=1 and pid=0')->getAll();
            if ($cats) {
                foreach ($cats as $t) {
                    $ftable = $table.'_c'.$t['id'];
                    // 创建字段
                    if (\Phpcmf\Service::M()->db->tableExists($ftable)
                        && !\Phpcmf\Service::M()->db->fieldExists('fstatus', $ftable)) {
                        \Phpcmf\Service::M()->query('ALTER TABLE `'.$ftable.'` ADD `fstatus` int(1) unsigned DEFAULT \'1\' COMMENT \'状态\';');
                        \Phpcmf\Service::M()->db->table($ftable)->update([
                            'fstatus' => 1, // 初始化1表示开启
                        ]);
                    }
                }
            }
        }

        if (!\Phpcmf\Service::M()->db->table('field')->where('fieldname', 'fstatus')->where('relatedid', $row['id'])->where('relatedname', 'module')->countAllResults()) {
            \Phpcmf\Service::M()->db->table('field')->insert(array(
                'name' => '状态',
                'ismain' => 1,
                'setting' => dr_array2string(array (
                    'option' =>
                        array (
                            'options' => '正常|1'.PHP_EOL.'关闭|2',
                            'value' => '',
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
                    'is_right' => '0',
                )),
                'issystem' => 1,
                'ismember' => 0,
                'disabled' => 0,
                'fieldname' => 'fstatus',
                'fieldtype' => 'Radio',
                'relatedid' => $row['id'],
                'relatedname' => 'module',
                'displayorder' => 0,
            ));
        }

        // 复制文件
        //dr_mkdirs(dr_get_app_dir($mid).'Mwhere/');
       // file_put_contents(dr_get_app_dir($mid).'Mwhere/Mwhere.php', file_get_contents(APPPATH.'Config/Mwhere.php'));

        \Phpcmf\Service::M('cache')->sync_cache('');
        $this->_json(1, '安装成功');
    }

    // 删除模块字段
    public function del() {

        $mid = dr_safe_filename($_GET['dir']);
        $row = \Phpcmf\Service::M('Module')->table('module')->where('dirname', $mid)->getRow();
        if (!$row) {
            $this->_json(0, dr_lang('此模块[%s]未安装', $mid));
        }

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$mid);
        if (!$module) {
            $this->_json(0, dr_lang('此模块[%s]没有安装在当前站点', $mid));
        }

        $table = \Phpcmf\Service::M()->prefix.SITE_ID.'_'.$mid;

        if (\Phpcmf\Service::M()->db->fieldExists('fstatus', $table)) {
            \Phpcmf\Service::M()->query('ALTER TABLE `'.$table.'` DROP `fstatus`;');
        }

        \Phpcmf\Service::M()->db->table('field')->where('fieldname', 'fstatus')->where('relatedid', $row['id'])->where('relatedname', 'module')->delete();

        //@unlink(dr_get_app_dir($mid).'Mwhere/Mwhere.php');

        \Phpcmf\Service::M('cache')->sync_cache('');
        $this->_json(1, '卸载成功');
    }


    // 删除模块字段
    public function init_edit() {

        $mid = dr_safe_filename($_GET['dir']);
        $row = \Phpcmf\Service::M('Module')->table('module')->where('dirname', $mid)->getRow();
        if (!$row) {
            $this->_json(0, dr_lang('此模块[%s]未安装', $mid));
        }

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$mid);
        if (!$module) {
            $this->_json(0, dr_lang('此模块[%s]没有安装在当前站点', $mid));
        }

        $data = \Phpcmf\Service::M('app')->get_config(APP_DIR);
        if (!$data) {
            $data = [];
        }

        if (isset($data[$mid])) {
            unset($data[$mid]);
            $msg = '已设置关闭';
        } else {
            $data[$mid] = 1;
            $msg = '已设置开启';
        }

        \Phpcmf\Service::M('app')->save_config(APP_DIR, $data);

        $this->_json(1, $msg);
    }

    // 设置模块状态
    public function close_edit() {

        $ids = \Phpcmf\Service::L('input')->get('ids');
        if (!$ids) {
            $this->_json(0, dr_lang('所选内容不存在'));
        }

        $mid = dr_safe_filename($_GET['mid']);
        $row = \Phpcmf\Service::M('Module')->table('module')->where('dirname', $mid)->getRow();
        if (!$row) {
            $this->_json(0, dr_lang('此模块[%s]未安装', $mid));
        }

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$mid);
        if (!$module) {
            $this->_json(0, dr_lang('此模块[%s]没有安装在当前站点', $mid));
        }

        $table = \Phpcmf\Service::M()->prefix.SITE_ID.'_'.$mid;
        if ($module['is_ctable']) {
            // 主表不存在尝试判断分表
            foreach ($ids as $id) {
                $index = \Phpcmf\Service::M()->table($table.'_index')->get($id);
                if (!$index) {
                    $this->_json(0, dr_lang('所选内容不存在'));
                }
                $table = dr_module_ctable($table, dr_cat_value($index['catid']));
                break;
            }
        }

        if (!\Phpcmf\Service::M()->db->fieldExists('fstatus', $table)) {
            $this->_json(0, dr_lang('此模块[%s]没有安装字段fstatus', $mid));
        }

        \Phpcmf\Service::M()->db->table($table)->whereIn('id', $ids)->update([
            'fstatus' => 2,
        ]);

        // 变更关闭后的钩子点
        \Phpcmf\Hooks::trigger('fstatus_edit', [
            'mid' => $mid,
            'ids' => $ids,
            'value' => 2,
        ]);

        $this->_json(1, '设置成功');
    }

    // 设置模块状态
    public function open_edit() {

        $ids = \Phpcmf\Service::L('input')->get('ids');
        if (!$ids) {
            $this->_json(0, dr_lang('所选内容不存在'));
        }

        $mid = dr_safe_filename($_GET['mid']);
        $row = \Phpcmf\Service::M('Module')->table('module')->where('dirname', $mid)->getRow();
        if (!$row) {
            $this->_json(0, dr_lang('此模块[%s]未安装', $mid));
        }

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$mid);
        if (!$module) {
            $this->_json(0, dr_lang('此模块[%s]没有安装在当前站点', $mid));
        }

        $table = \Phpcmf\Service::M()->prefix.SITE_ID.'_'.$mid;

        if ($module['is_ctable']) {
            // 主表不存在尝试判断分表
            foreach ($ids as $id) {
                $index = \Phpcmf\Service::M()->table($table.'_index')->get($id);
                if (!$index) {
                    $this->_json(0, dr_lang('所选内容不存在'));
                }
                $table = dr_module_ctable($table, dr_cat_value($index['catid']));
                break;
            }
        }

        if (!\Phpcmf\Service::M()->db->fieldExists('fstatus', $table)) {
            $this->_json(0, dr_lang('此模块[%s]没有安装字段fstatus', $mid));
        }

        \Phpcmf\Service::M()->db->table($table)->whereIn('id', $ids)->update([
            'fstatus' => 1,
        ]);

        // 变更开启后的钩子点
        \Phpcmf\Hooks::trigger('fstatus_edit', [
            'mid' => $mid,
            'ids' => $ids,
            'value' => 1,
        ]);
        $this->_json(1, '设置成功');
    }


    // 设置模块状态
    public function edit() {

        $id = \Phpcmf\Service::L('input')->get('id');
        if (!$id) {
            $this->_json(0, dr_lang('所选内容不存在'));
        }

        $mid = dr_safe_filename($_GET['mid']);
        $row = \Phpcmf\Service::M('Module')->table('module')->where('dirname', $mid)->getRow();
        if (!$row) {
            $this->_json(0, dr_lang('此模块[%s]未安装', $mid));
        }

        $module = \Phpcmf\Service::L('cache')->get('module-'.SITE_ID.'-'.$mid);
        if (!$module) {
            $this->_json(0, dr_lang('此模块[%s]没有安装在当前站点', $mid));
        }

        $table = \Phpcmf\Service::M()->prefix.SITE_ID.'_'.$mid;

        if ($module['is_ctable']) {
            // 主表不存在尝试判断分表
            $index = \Phpcmf\Service::M()->table($table.'_index')->get($id);
            if (!$index) {
                $this->_json(0, dr_lang('所选内容不存在'));
            }
            $table = dr_module_ctable($table, dr_cat_value($index['catid']));
        }

        $data = \Phpcmf\Service::M()->table($table)->get($id);
        if (!$data) {
            $this->_json(0, dr_lang('所选内容不存在'));
        }

        if (!\Phpcmf\Service::M()->db->fieldExists('fstatus', $table)) {
            $this->_json(0, dr_lang('此模块[%s]没有安装字段fstatus', $mid));
        }

        $value = $data['fstatus'] == 1 ? 2 : 1;
        \Phpcmf\Service::M()->db->table($table)->where('id', $id)->update([
            'fstatus' => $value,
        ]);

        // 变更开启后的钩子点
        \Phpcmf\Hooks::trigger('fstatus_edit', [
            'mid' => $mid,
            'ids' => [$id],
            'value' => $value,
        ]);

        $this->_json(1, '设置'.($value == 1 ? '开启' : '关闭').'状态', [ 'value' => $value == 1 ? 1 : 0 ]);
    }

    // 查看代码
    public function show() {

        $code1 = '


内容详情页面
{if $fstatus == 1}
表示开启时
{else}
表示关闭时
{/if}
';

        \Phpcmf\Service::V()->assign([
            'code1' => $code1,
        ]);
        \Phpcmf\Service::V()->display('code.html');

    }
}
