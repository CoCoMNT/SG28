<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include);  if ($ci->member_cache['config']['mobile'] && !$member['is_mobile']) { ?>
<div class="alert alert-danger">
    系统强制用户进行手机认证
</div>
<?php } ?>
<div class="row">

    <div class="col-md-12">

        <div class="portlet light ">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject"> 手机认证</span>
                </div>
            </div>
            <div class="portlet-body form fc-form">
                <form action="" class="form-horizontal" method="post" name="myform" id="myform">
                    <?php echo $form;  if ($member['phone']) { ?>
                    <div class="form-group">
                        <label class="col-md-2 control-label"> 手机号码 </label>
                        <div class="col-md-9">
                            <p class="form-control-static"> <?php echo $member['phone']; ?> &nbsp;&nbsp;<?php if (!$member['is_mobile']) { ?><span class="label label-sm label-danger">未认证</span><?php } else { ?><span class="label label-sm label-success">已认证</span><?php } ?></p>
                        </div>
                    </div>
                    <?php }  if ($is_update) { ?>
                    <div class="form-group" id="dr_row_phone">
                        <label class="col-md-2 control-label"> <?php if ($member['phone']) { ?>变更手机<?php } else { ?>设置手机<?php } ?> </label>
                        <div class="col-md-9">
                            <label><input type="text" id="dr_phone" style="width: 300px" class="form-control"  name="data[phone]"></label>
                            <span class="help-block">留空表示不修改手机号码</span>
                        </div>
                    </div>
                    <?php } else { ?>
                    <input type="hidden" id="dr_phone" value="<?php echo $member['phone']; ?>">
                    <?php }  if ($is_update || $is_mobile) { ?>
                    <div class="form-group" id="dr_row_code">
                        <label class="col-md-2 control-label">短信验证码</label>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 300px">
                                <input type="text" id="dr_code" name="data[code]" class="form-control" placeholder="验证码">
                                <span class="input-group-btn">
                                        <button class="btn green" type="button" onclick="dr_ajax_url('<?php echo $api_url; ?>&value='+$('#dr_phone').val())">发送验证码</button>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions fc-form-actions">
                        <label class="col-md-2 control-label">  </label>
                        <div class="col-md-9 fc-form-submit">
                            <button type="button" onclick="dr_ajax_submit('<?php echo dr_now_url(); ?>', 'myform', '2000')" class="btn blue"> 提交保存 </button>
                        </div>

                    </div>
                    <?php } ?>


                </form>
            </div>
        </div>

    </div>

</div>

<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>