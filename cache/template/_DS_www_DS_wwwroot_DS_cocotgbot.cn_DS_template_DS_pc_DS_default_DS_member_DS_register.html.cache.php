<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>


<form class="login-form"  id="myform" action="" method="post">
    <?php echo dr_form_hidden(); ?>
    <div class="logo2">
        <a href="/"><img src="<?php echo SITE_LOGO; ?>" /> </a>
    </div>

    <?php if (dr_count($register['group'])>1) { ?>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9">注册类型</label>
        <select onchange="location.href=this.value" class="form-control valid" aria-required="true" aria-invalid="false" aria-describedby="country-error">
            <?php if (isset($register['group']) && is_array($register['group']) && $register['group']) { $key_i=-1;$count_i=dr_count($register['group']);foreach ($register['group'] as $i) { $key_i++; $is_first=$key_i==0 ? 1 : 0;$is_last=$count_i==$key_i+1 ? 1 : 0;?>
            <option value="<?php echo dr_member_url('register/index', ['groupid' => $i]); ?>" <?php if ($groupid == $i) { ?>selected<?php } ?>><?php echo $group[$i]['name']; ?></option>
            <?php } } ?>
        </select>
    </div>
    <?php }  if (dr_in_array('username', $register['field'])) { ?>
    <div class="form-group" id="dr_row_username">
        <label class="control-label visible-ie8 visible-ie9">账号</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off"  id="dr_username" name="data[username]" placeholder="输入注册账号" />
    </div>
    <?php }  if (dr_in_array('email', $register['field'])) { ?>
    <div class="form-group " id="dr_row_email">
        <label class="control-label visible-ie8 visible-ie9">邮箱</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" name="data[email]" id="dr_email" placeholder="输入电子邮箱">
    </div>
    <?php }  if (dr_in_array('phone', $register['field'])) { ?>
    <div class="form-group " id="dr_row_phone">
        <label class="control-label visible-ie8 visible-ie9">手机号</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" name="data[phone]" id="dr_phone" placeholder="输入手机号码">
    </div>
    <?php }  if (dr_in_array('name', $register['field'])) { ?>
    <div class="form-group " id="dr_row_name">
        <label class="control-label visible-ie8 visible-ie9"><?php echo MEMBER_CNAME; ?></label>
        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" name="data[name]" id="dr_name" placeholder="输入<?php echo MEMBER_CNAME; ?>">
    </div>
    <?php } ?>
    <div class="form-group"  id="dr_row_password">
        <label class="control-label visible-ie8 visible-ie9">登录密码</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"  id="dr_password" name="data[password]" placeholder="输入登录密码" />
    </div>
    <div class="form-group"  id="dr_row_password2">
        <label class="control-label visible-ie8 visible-ie9">确认密码</label>
        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off"  id="dr_password2" name="data[password2]" placeholder="再次输入密码" />
    </div>
    <?php echo $myfield;  if ($register['sms']) { ?>
    <div class="form-group" id="dr_row_sms">
        <label class="control-label visible-ie8 visible-ie9">短信验证</label>
        <div class="input-group">
            <div class="input-icon">
                <input style="padding-left: 15px" type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="短信验证"  id="dr_sms" name="sms">
            </div>
            <div class="input-group-btn fc-code" style="padding-left: 10px;">
                <a class="btn " type="button" onclick="dr_ajax_url('/index.php?s=member&c=api&m=register_code&id='+$('#dr_phone').val()+'&code='+$('#dr_code').val())">发送验证码</a>
            </div>
        </div>
    </div>
    <?php }  if ($is_img_code) { ?>
    <div class="form-group">
        <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('图片验证码'); ?></label>
        <div class="input-group">
            <div class="input-icon">
                <input style="padding-left: 15px" type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="<?php echo dr_lang('验证码'); ?>"  id="dr_code" name="code">
            </div>
            <div class="input-group-btn fc-code" style="padding-left: 10px;">
                <?php echo dr_code(120, 35); ?>
            </div>
        </div>
    </div>
    <?php } ?>




    <div class="form-actions text-right">
        <label class="mt-checkbox mt-checkbox-outline" style="margin-left:20px;">
            <input type="checkbox" name="is_protocol" value="1" checked> 我已阅读并同意
            <span></span>
        </label>
        <label>
            <a href="javascript:dr_show_protocol();">《用户注册协议》</a>
        </label>
        <label>
            <button type="button" onclick="dr_ajax_member('<?php echo dr_member_url('register/index', ['groupid'=>$groupid]); ?>', 'myform')" class="btn green pull-right"> 注册账号 </button>
        </label>
    </div>


    <div class="create-account">
        <p>
            <a href="<?php echo dr_member_url('login/index'); ?>" class="uppercase">登录账号</a>
        </p>
    </div>
</form>

<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>