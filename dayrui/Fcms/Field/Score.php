<?php namespace Phpcmf\Field;
/**
 * www.xunruicms.com
 * 迅睿内容管理框架系统（简称：迅睿CMS）
 * 本文件是框架系统文件，二次开发时不可以修改本文件，可以通过继承类方法来重写此文件
 **/

class Score extends \Phpcmf\Library\A_Field  {

	/**
	 * 字段相关属性参数
	 */
	public function option($option, $field = null) {
        return ['', '<div class="form-group">
			<label class="col-md-2 control-label">'.dr_lang('显示游客组').'</label>
			<div class="col-md-9">
				<input type="checkbox" name="data[setting][option][guest]" '.($option['guest'] ? 'checked' : '').' value="1"  data-on-text="'.dr_lang('已开启').'" data-off-text="'.dr_lang('已关闭').'" data-on-color="success" data-off-color="danger" class="make-switch" data-size="small">

				<span class="help-block">'.dr_lang('把游客组作为一个用户组来显示').'</span>
			</div>
		</div>'];
	}

    /**
     * 创建sql语句
     */
    public function create_sql($name, $value, $cname = '') {
        $sql = 'ALTER TABLE `{tablename}` ADD `'.$name.'` varchar(255) NULL , ADD `'.$name.'_sku` TEXT NULL';
        return $sql;
    }

    /**
     * 修改sql语句
     */
    public function alter_sql($name, $value, $cname = '') {
        return NULL;
    }

    /**
     * 删除sql语句
     */
    public function drop_sql($name) {
        $sql = 'ALTER TABLE `{tablename}` DROP `'.$name.'`, DROP `'.$name.'_sku`';
        return $sql;
    }

    // 测试字段是否被创建成功，默认成功为0，需要继承开发
    public function test_sql($tables, $field) {

        if (!$tables) {
            return 0;
        }

        foreach ($tables as $table) {
            if (!\Phpcmf\Service::M()->db->fieldExists($field.'_sku', $table)) {
                return '给表['.$table.']创建字段['.$field.'_sku'.']失败';
            }
            if (!\Phpcmf\Service::M()->db->fieldExists($field.'', $table)) {
                return '给表['.$table.']创建字段['.$field.''.']失败';
            }
        }

        return 0;
    }

    /**
     * 字段入库值
     */
    public function insert_value($field) {

        if ((int)$_POST['is_field_'.$field['fieldname']]) {
            // 用户组
            $sku = $_POST['data'][$field['fieldname'].'_sku'];
            $price = min($sku);
            \Phpcmf\Service::L('Field')->data[$field['ismain']][$field['fieldname']] = $price;
            \Phpcmf\Service::L('Field')->data[$field['ismain']][$field['fieldname'].'_sku'] = dr_array2string($sku);
        } else {
            // 单一
            \Phpcmf\Service::L('Field')->data[$field['ismain']][$field['fieldname']] = $_POST['data'][$field['fieldname']];
            \Phpcmf\Service::L('Field')->data[$field['ismain']][$field['fieldname'].'_sku'] = '';
        }

    }

    /**
     * 字段输出
     *
     * @param	array	$value	值
     * @return  string
     */
    public function output($value) {
        return $value;
    }

    /**
     * 字段表单输入
     *
     * @return  string
     */
    public function input($field, $value = []) {

        // 字段显示名称
        $text = ($field['setting']['validate']['required'] ? '<span class="required" aria-required="true"> * </span>' : '').dr_lang($field['name']);

        // 字段提示信息
        $tips = $field['setting']['validate']['tips'] ? '<span class="help-block" id="dr_'.$field['fieldname'].'_tips">'.$field['setting']['validate']['tips'].'</span>' : '';

        // 模式
        $sku = dr_string2array(\Phpcmf\Service::L('Field')->value[$field['fieldname'].'_sku']);
        $html = '';
        if ($field['setting']['option']['guest']) {
            $group = dr_array22array([
                0 => [
                    'id' => 0,
                    'name' => dr_lang('游客'),
                ],
            ], \Phpcmf\Service::C()->member_cache['group']);
        } else {
            $group = \Phpcmf\Service::C()->member_cache['group'];
        }
        foreach ($group as $g) {
            $html.= '<label><div class="input-group">
<span class="input-group-addon">'.$g['name'].'</span>
<input type="text" class="form-control" name="data['.$field['fieldname'].'_sku]['.$g['id'].']" value="'.(string)$sku[$g['id']].'" /> 
</div></label>';
        }
        $is_field = $sku? 1 : 0;

        $str = '
            <div class="mt-radio-inline">
                <label class="mt-radio">
                    <input type="radio" onclick="$(\'#dr_field_'.$field['fieldname'].'\').show();$(\'#dr_field_'.$field['fieldname'].'2\').hide();" name="is_field_'.$field['fieldname'].'" value="0" '.(!$is_field ? 'checked' : '').'> '.dr_lang('全局').'
                    <span></span>
                </label>
                <label class="mt-radio">
                    <input type="radio" onclick="$(\'#dr_field_'.$field['fieldname'].'2\').show();$(\'#dr_field_'.$field['fieldname'].'\').hide();" name="is_field_'.$field['fieldname'].'" value="1" '.($is_field ? 'checked' : '').'> '.dr_lang('用户组').'
                    <span></span>
                </label>
            </div>
            <div id="dr_field_'.$field['fieldname'].'" style="display:'.(!$is_field ? 'block' : 'none').';">
                <label><input class="form-control " type="text" name="data['.$field['fieldname'].']" id="dr_'.$field['fieldname'].'" value="'.$value.'" /></label>
            </div>
            <div id="dr_field_'.$field['fieldname'].'2" style="display:'.($is_field ? 'block' : 'none').';">
                <div style="width: 50%">
                '.$html.'
                </div>
            </div>
            ';
        return $this->input_format($field['fieldname'], $text, $str.$tips);

	}

    /**
     * 字段表单显示
     */
    public function show($field, $value = null) {

        // 字段显示名称
        $text = ($field['setting']['validate']['required'] ? '<span class="required" aria-required="true"> * </span>' : '').$field['name'];

        // 字段提示信息
        $tips = $field['setting']['validate']['tips'] ? '<span class="help-block" id="dr_'.$field['fieldname'].'_tips">'.$field['setting']['validate']['tips'].'</span>' : '';

        // 模式
        $sku = dr_string2array(\Phpcmf\Service::L('Field')->value['score_sku']);
        $html = '';
        if ($field['setting']['option']['guest']) {
            $group = dr_array22array([
                0 => [
                    'id' => 0,
                    'name' => dr_lang('游客'),
                ],
            ], \Phpcmf\Service::C()->member_cache['group']);
        } else {
            $group = \Phpcmf\Service::C()->member_cache['group'];
        }
        foreach ($group as $g) {
            $html.= '<label><div class="input-group">
<span class="input-group-addon">'.$g['name'].'</span>
<input type="text" class="form-control"  readonly name="data['.$field['fieldname'].'_sku]['.$g['id'].']" value="'.(string)$sku[$g['id']].'" /> 
</div></label>';
        }

        $is_field = $sku? 1 : 0;

        $str = '
            <div id="dr_field_'.$field['fieldname'].'" style="display:'.(!$is_field ? 'block' : 'none').';">
                <label><input class="form-control " readonly type="text" name="data['.$field['fieldname'].']" id="dr_'.$field['fieldname'].'" value="'.$value.'" /></label>
            </div>
            <div id="dr_field_'.$field['fieldname'].'2" style="display:'.($is_field ? 'block' : 'none').';">
                <div style="width: 50%">
                '.$html.'
                </div>
            </div>
            ';
        return $this->input_format($field['fieldname'], $text, $str.$tips);

    }

}