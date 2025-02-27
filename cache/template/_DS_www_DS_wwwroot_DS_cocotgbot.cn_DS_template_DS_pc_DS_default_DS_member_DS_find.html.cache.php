<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

<form class="login-form"  id="myform" action="" method="post">
    <?php echo dr_form_hidden(); ?>
    <div class="logo2">
        <a href="/"><img src="<?php echo SITE_LOGO; ?>" /> </a>
    </div>

    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">凭证</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"  id="dr_value" name="data[value]" placeholder="输入邮箱或手机号码" />
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">新密码</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"  id="dr_password" name="data[password]" placeholder="输入新的密码" />
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">确认密码</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"  id="dr_password2" name="data[password2]" placeholder="再次输入新的密码" />
    </div>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('图片验证码'); ?></label>
        <div class="input-group">
            <div class="input-icon">
                <input style="padding-left: 15px" type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="<?php echo dr_lang('验证码'); ?>" name="code" id="dr_code">
            </div>
            <div class="input-group-btn fc-code" style="padding-left: 10px;">
                <?php echo dr_code(120, 35); ?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('获得验证码'); ?></label>
        <div class="input-group">
            <div class="input-icon">
                <input style="padding-left: 15px" type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="<?php echo dr_lang('获得验证码'); ?>" name="data[code]">
            </div>
            <div class="input-group-btn fc-code" style="padding-left: 10px;">
                <a class="btn " type="button" onclick="dr_ajax_url('<?php echo $api_url; ?>&code='+$('#dr_code').val()+'&value='+$('#dr_value').val())">发送验证码</a>
            </div>
        </div>
    </div>

    <div class="form-actions text-right">
        <label>
            <button type="button" onclick="dr_ajax_submit('<?php echo dr_now_url(); ?>', 'myform', '2000', '<?php echo dr_member_url('login/index'); ?>')" class="btn green pull-right"> 登录 </button>
        </label>
    </div>


    <div class="create-account">
        <p>
            <a href="<?php echo dr_member_url('login/index'); ?>" class="uppercase">登录账号</a>
        </p>
    </div>
</form>



<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>