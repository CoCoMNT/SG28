<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

    <form class="login-form"  id="myform" action="" method="post">
        <?php echo $form; ?>
        <div class="logo">
            <a href="/"><img src="<?php echo SITE_LOGO; ?>" /> </a>
        </div>

        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">账号</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="账号/邮箱/手机" name="data[username]" />
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">密码</label>
            <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="登录密码" name="data[password]" />
        </div>
        <?php if ($is_code) { ?>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('验证码'); ?></label>
            <div class="input-group">
                <div class="input-icon">
                    <input style="padding-left: 15px" type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="<?php echo dr_lang('验证码'); ?>" name="code">
                </div>
                <div class="input-group-btn fc-code" style="padding-left: 10px;">
                    <?php echo dr_code(120, 35); ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="login-oauth text-center">
            <?php if (isset($ci->member_cache['oauth']['qq']['id']) && $ci->member_cache['oauth']['qq']['id']) { ?>
                <a href="<?php echo \Phpcmf\Service::L('Router')->oauth_url('qq', 'login'); ?>"> <img src="<?php echo THEME_PATH; ?>assets/oauth/qq.png"> </a>
            <?php }  if (isset($ci->member_cache['oauth']['weixin']['id']) && $ci->member_cache['oauth']['weixin']['id']) { ?>
                <a href="<?php echo \Phpcmf\Service::L('Router')->oauth_url('weixin', 'login'); ?>"> <img src="<?php echo THEME_PATH; ?>assets/oauth/weixin.png"> </a>
            <?php } ?>
        </div>

        <div class="form-actions">
            <label class="right-button"><button type="button" onclick="dr_ajax_member('<?php echo dr_member_url('login/index'); ?>', 'myform');" class="btn green pull-right"> 登录 </button>
            </label>
            <label class="right-button rememberme check mt-checkbox mt-checkbox-outline">
                <input type="checkbox" name="remember" value="1"> 记住登录
                <span></span>
            </label>
            <label>
                <a href="<?php echo dr_member_url('login/find'); ?>" class="forget-password">找回密码</a></label>
        </div>

        <div class="create-account">
            <p>
                <a href="<?php echo dr_member_url('register/index'); ?>" class="uppercase">注册新账号</a>
            </p>
        </div>
    </form>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>