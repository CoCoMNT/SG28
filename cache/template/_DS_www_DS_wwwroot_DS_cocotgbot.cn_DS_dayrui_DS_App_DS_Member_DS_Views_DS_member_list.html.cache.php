<?php if ($fn_include = $this->_include("header.html")) include($fn_include);  echo \Phpcmf\Service::L('Field')->get('select')->get_select_search_code(); ?>

<div class="note note-danger" <?php if (!isset($get['submit']) && !$is_show_search_bar) { ?>style="display: none"<?php } ?> id="table-search-tool">

    <div class="row table-search-tool">
        <form action="<?php echo SELF; ?>" method="get">
            <?php echo dr_form_search_hidden();  if ($status) { ?>
            <div class="col-md-12 col-sm-12">
                <label>
                    <select name="status[]" class="form-control bs-select" data-title="<?php echo dr_lang('账号状态'); ?>" multiple="multiple">
                        <?php if (isset($status) && is_array($status) && $status) { $key_t=-1;$count_t=dr_count($status);foreach ($status as $i=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; ?>
                        <option value="<?php echo $i; ?>" <?php if (dr_in_array($i, $param['status'])) { ?>selected<?php } ?>><?php echo dr_lang($t); ?></option>
                        <?php } } ?>
                    </select>
                </label>
            </div>
            <?php } ?>
            <div class="col-md-12 col-sm-12">
                <label style="min-width: 200px;">
                    <select name="groupid[]" class="form-control bs-select" multiple="multiple" data-title="<?php echo dr_lang('用户组'); ?>" data-actions-box="true">
                        <?php if (isset($group) && is_array($group) && $group) { $key_t=-1;$count_t=dr_count($group);foreach ($group as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;?>
                        <option value="<?php echo $t['id']; ?>" <?php if (dr_in_array($t['id'], $param['groupid'])) { ?>selected<?php } ?>><?php echo dr_lang($t['name']); ?></option>
                        <?php } } ?>
                    </select>
                </label>
            </div>
            <div class="col-md-12 col-sm-12">
                <label>
                    <select name="field" class="form-control">
                        <option value="id"> Id </option>
                        <?php if (isset($field) && is_array($field) && $field) { $key_t=-1;$count_t=dr_count($field);foreach ($field as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; if (dr_is_admin_search_field($t)) { ?>
                        <option value="<?php echo $t['fieldname']; ?>" <?php if ($param['field']==$t['fieldname']) { ?>selected<?php } ?>><?php echo dr_lang($t['name']); ?></option>
                        <?php }  } } ?>
                    </select>
                </label>
                <label><i class="fa fa-caret-right"></i></label>
                <label><input type="text" class="form-control" placeholder="" value="<?php echo $param['keyword']; ?>" name="keyword" /></label>
            </div>



            <div class="col-md-12 col-sm-12">
                <label><button id="table-search-tool-submit" type="button" class="btn blue btn-sm " name="submit" > <i class="fa fa-search"></i> <?php echo dr_lang('搜索'); ?></button></label>
                <label><button id="table-search-tool-reset" type="reset" class="btn red btn-sm " name="reset" > <i class="fa fa-refresh"></i> <?php echo dr_lang('重置'); ?></button></label>
            </div>
        </form>
    </div>
</div>

<div class="right-card-box">
    <div id="toolbar" class="toolbar">
        <?php echo $mytable['foot_tpl']; ?>

    </div>

    <?php if ($fn_include = $this->_include("mytable.html")) include($fn_include); ?>

    <script>
        $(function () {
            var up = 0;
            $('.dr_username_phone').each(function () {
                var html = $(this).html();
                if (html.length > 44) {
                    up = 1;
                }
            });
            if (up == 0) {
                $('.dr_username_phone').remove();
            }
        });
        // ajax 批量操作确认
        function dr_ajax_option_user(url, remove) {

            layer.confirm(
                '<?php echo dr_lang('全部删除: 会联动删除所属的内容和附件。'); ?><br>'+
            '<?php echo dr_lang('只删除账号: 不会影响该账号的相关数据和附件。'); ?><br>',
                {
                    icon: 3,
                    shade: 0,
                    area: ['500px', '220px'],
                    title: '<?php echo dr_lang('确认删除'); ?>',
                    btn: ['<?php echo dr_lang('全部删除'); ?>','<?php echo dr_lang('只删除账号'); ?>', lang['esc']]
                }, function(index){
                layer.close(index);
                var loading = layer.load(2, {
                    shade: [0.3,'#fff'], //0.1透明度的白色背景
                    time: 5000
                });
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: url+'&sync=1',
                    data: $("#myform").serialize(),
                    success: function(json) {
                        layer.close(loading);
                        if (json.code) {
                            if (remove) {
                                // 批量移出去
                                var ids = json.data.ids;
                                if (typeof ids != "undefined" ) {
                                    console.log(ids);
                                    for ( var i = 0; i < ids.length; i++){
                                        $("#dr_row_"+ids[i]).remove();
                                    }
                                }
                            }
                            if (json.data.htmlfile) {
                                // 执行生成htmljs
                                $.ajax({
                                    type: "GET",
                                    url: json.data.htmlfile,
                                    dataType: "jsonp",
                                    success: function(json){ },
                                    error: function(){ }
                                });
                            }
                            if (json.data.url) {
                                setTimeout("window.location.href = '"+json.data.url+"'", 2000);
                            } else {
                                setTimeout("window.location.reload(true)", 3000)
                            }
                        }
                        dr_cmf_tips(json.code, json.msg);
                    },
                    error: function(HttpRequest, ajaxOptions, thrownError) {
                        dr_ajax_alert_error(HttpRequest, this, thrownError);
                    }
                });
            }, function(index){
                layer.close(index);
                var loading = layer.load(2, {
                    shade: [0.3,'#fff'], //0.1透明度的白色背景
                    time: 5000
                });
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: url+'&sync=0',
                    data: $("#myform").serialize(),
                    success: function(json) {
                        layer.close(loading);
                        if (json.code) {
                            if (remove) {
                                // 批量移出去
                                var ids = json.data.ids;
                                if (typeof ids != "undefined" ) {
                                    console.log(ids);
                                    for ( var i = 0; i < ids.length; i++){
                                        $("#dr_row_"+ids[i]).remove();
                                    }
                                }
                            }
                            if (json.data.htmlfile) {
                                // 执行生成htmljs
                                $.ajax({
                                    type: "GET",
                                    url: json.data.htmlfile,
                                    dataType: "jsonp",
                                    success: function(json){ },
                                    error: function(){ }
                                });
                            }
                            if (json.data.url) {
                                setTimeout("window.location.href = '"+json.data.url+"'", 2000);
                            } else {
                                setTimeout("window.location.reload(true)", 3000)
                            }
                        }
                        dr_cmf_tips(json.code, json.msg);
                    },
                    error: function(HttpRequest, ajaxOptions, thrownError) {
                        dr_ajax_alert_error(HttpRequest, this, thrownError);
                    }
                });

            });
        }
    </script>
</div>

<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>