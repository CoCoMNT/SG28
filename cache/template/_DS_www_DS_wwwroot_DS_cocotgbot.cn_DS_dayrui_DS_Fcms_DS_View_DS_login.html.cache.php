<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <title><?php echo dr_lang('%s - 后台管理平台', SITE_NAME); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="<?php echo $THEME_PATH; ?>assets/global/css/admin.min.css?v=<?php echo CMF_UPDATE_TIME; ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo $THEME_PATH; ?>assets/global/css/login.min.css?v=<?php echo CMF_UPDATE_TIME; ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo defined('MYCSS_FILE') ? MYCSS_FILE : $THEME_PATH.'assets/global/css/my.css'; ?>?v=<?php echo CMF_UPDATE_TIME; ?>" rel="stylesheet" type="text/css" />
</head>
<body class="login">
<div class="content">
    <div class="logo">
        <a href="<?php echo SITE_URL; ?>"><img src="<?php echo $THEME_PATH; ?>assets/logo-web.png" /> </a>
    </div>
    <form class="login-form" action="" onsubmit="return dr_submit()" method="post">
        <?php echo $form; ?>
        <div class="post-msg alert alert-danger display-hide">
            <span>  </span>
        </div>
        <?php if ($is_sms) { ?>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('手机'); ?></label>
            <div class="input-icon">
                <input style="padding-left:15px;" class="form-control placeholder-no-fix" type="text"  autocomplete="off" placeholder="<?php echo dr_lang('手机号码'); ?>" name="data[phone]" />
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('验证码'); ?></label>
            <div class="input-icon">
                <div class="input-inline " style="margin-right: 0px;">
                    <div class="input-group">
                        <input type="text" name="data[sms]" placeholder="<?php echo dr_lang('手机验证码'); ?>" id="dr_sms" class="form-control">
                        <span class="input-group-btn">
                            <a class="btn default" onclick="dr_send_sms()">获取验证码</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php } else { ?>
        <div class="form-group dr_row_username">
            <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('账号'); ?></label>
            <div class="input-icon">
                <input style="padding-left:15px;" class="form-control placeholder-no-fix" type="text" value="<?php echo  defined('DEMO_ADMIN_USERNAME') ? DEMO_ADMIN_USERNAME : ''?>" autocomplete="off" placeholder="<?php echo dr_lang('账号'); ?>" name="data[username]" /> </div>
        </div>
        <div class="form-group dr_row_password">
            <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('密码'); ?></label>
            <div class="input-icon">
                <input style="padding-left:15px;" id="password" class="form-control placeholder-no-fix" type="password" value="<?php echo  defined('DEMO_ADMIN_PASSWORD') ? DEMO_ADMIN_PASSWORD : ''?>" autocomplete="off" placeholder="<?php echo dr_lang('密码'); ?>" name="data[password]" /> </div>
        </div>
        <?php } ?>
        <div class="form-group dr_check dr_check_phone" style="display: none">

        </div>
        <div class="form-group dr_check" style="display: none">
            <input type="hidden" name="data[phone]" id="dr_check_phone">
            <input type="hidden" name="is_check" id="is_check">
            <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('验证码'); ?></label>
            <div class="input-icon">
                <div class="input-inline " style="margin-right: 0px;">
                    <div class="input-group">
                        <input type="text" name="data[sms]" placeholder="<?php echo dr_lang('手机验证码'); ?>" id="dr_sms" class="form-control">
                        <span class="input-group-btn">
                            <a class="btn default" onclick="dr_send_sms()">获取验证码</a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php if (SYS_ADMIN_CODE) {  if ($is_mobile) { ?>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9"><?php echo dr_lang('验证码'); ?></label>
            <div class="input-icon">
                <input style="padding-left:15px;" class="form-control placeholder-no-fix" type="text"  autocomplete="off" placeholder="<?php echo dr_lang('验证码'); ?>" name="code" />
            </div>
        </div>
        <div class="form-group text-center">
            <?php echo dr_code(120, 35); ?>
        </div>
        <?php } else { ?>
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
        <?php }  } ?>
        <div class="form-actions">
            <button type="button" onclick="dr_submit()" class="btn blue btn-block"> <?php echo dr_lang('登录后台'); ?> </button>
        </div>
        <?php if (SYS_ADMIN_SMS_LOGIN) { ?>
        <div class="form-actions text-right" style="margin-top: -20px;margin-bottom: -15px;">
            <?php if ($is_sms) { ?>
            <a href="<?php echo $rlogin_url; ?>"> <?php echo dr_lang('账号密码登录'); ?> </a>
            <?php } else { ?>
            <a href="<?php echo $login_url; ?>&is_sms=1"> <?php echo dr_lang('手机验证码登录'); ?> </a>
            <?php } ?>
        </div>
        <?php }  if (defined('IS_ADMIN_MIN') && IS_ADMIN_MIN) {  } else {  if (!SYS_ADMIN_MODE) { ?>
        <div class="form-group text-center" style="margin-bottom: -10px;">
            <div class="input-icon">
                <div class="mt-radio-inline">
                    <label class="mt-radio">
                        <input type="radio" name="data[mode]" value="1" <?php if ($mode == 1) { ?>checked=""<?php } ?>> <?php echo dr_lang('完整模式'); ?>
                        <span></span>
                    </label>
                    <label class="mt-radio">
                        <input type="radio" name="data[mode]" value="2" <?php if ($mode == 2) { ?>checked=""<?php } ?>> <?php echo dr_lang('简化模式'); ?>
                        <span></span>
                    </label>
                </div>
            </div>
        </div>
        <?php }  }  if (SYS_ADMIN_OAUTH && $oauth) { ?>
        <link href="<?php echo $THEME_PATH; ?>assets/icon/css/icon.css?v=<?php echo CMF_UPDATE_TIME; ?>" rel="stylesheet" type="text/css" />
        <div class="login-oauth">
            <?php if (isset($oauth) && is_array($oauth) && $oauth) { $key_t=-1;$count_t=dr_count($oauth);foreach ($oauth as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;?>
            <a href="<?php echo $t['url']; ?>" class="tooltips" data-container="body" data-placement="top" data-original-title="<?php echo $t['title']; ?>"><i class="fa fa-<?php echo $t['name']; ?>"></i></a>
            <?php } } ?>
        </div>
        <?php } ?>
    </form>
</div>
<script src="<?php echo $THEME_PATH; ?>assets/global/plugins/jquery.min.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<?php if (defined('SYS_ADMIN_LOGIN_AES') && SYS_ADMIN_LOGIN_AES) { ?>
<script src="<?php echo $THEME_PATH; ?>assets/global/plugins/crypto-js.min.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<?php } else { ?>
<script src="<?php echo $THEME_PATH; ?>assets/global/plugins/jquery.md5.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<?php } ?>
<script src="<?php echo $LANG_PATH; ?>lang.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<script src="<?php echo $THEME_PATH; ?>assets/js/cms.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<script src="<?php echo $THEME_PATH; ?>assets/global/plugins/bootstrap/js/bootstrap.min.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<script src="<?php echo $THEME_PATH; ?>assets/global/scripts/app.min.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<script type="text/javascript">
    var is_admin = 2;
    if (typeof parent.layer == 'function') {
        parent.layer.closeAll('loading');
    }
    function dr_send_sms() {
        $.ajax({type: "POST",dataType:"json", url: '<?php echo $sms_url; ?>', data: $(".login-form").serialize(),
            success: function(json) {
                // token 更新
                if (json.token) {
                    var token = json.token;
                    $(".login-form input[name='"+token.name+"']").val(token.value);
                }
                if (json.code == 1) {
                    dr_msg(1, json.msg);
                } else {
                    $('.fc-code img').click();
                    dr_msg(0, json.msg);
                }
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                $('.fc-code img').click();
                var msg = HttpRequest.responseText;
                if (!msg) {
                    dr_msg(0, "<?php echo dr_lang('系统错误'); ?>");
                } else {
                    dr_msg(0, msg);
                }
            }
        });
    }
    function dr_submit() {
        $('.post-msg', $('.login-form')).hide();
        // 这里进行md5加密存储
        <?php if ($is_sms) {  } else { ?>
        var pwd = $('#password').val();
        if (pwd.length == 32) {
            // 已经加密过
        } else {
            <?php if (defined('SYS_ADMIN_LOGIN_AES') && SYS_ADMIN_LOGIN_AES) { ?>
            $('.login-form').append('<input type="hidden" name="is_aes" value="1">');
            var key = CryptoJS.enc.Utf8.parse('<?php echo $crypto_key; ?>');
            var iv = CryptoJS.enc.Utf8.parse('<?php echo $crypto_iv; ?>');
            var pw = pwd;
            pwd = CryptoJS.AES.encrypt(pwd, key, {
                mode: CryptoJS.mode.CBC,
                iv: iv,
                padding: CryptoJS.pad.Pkcs7
            });
            <?php if (IS_DEV) { ?>
            pwd2 = CryptoJS.AES.decrypt(pwd, key, {
                mode: CryptoJS.mode.CBC,
                iv: iv,
                padding: CryptoJS.pad.Pkcs7
            });
            pwd2 = pwd2.toString(CryptoJS.enc.Utf8);
            if (pwd2 != pw) {
                alert("CryptoJS密码解析失败");
                return;
            }
            <?php }  } else { ?>
            pwd = $.md5(pwd); // 进行md5加密
            <?php } ?>
            $('#password').val(pwd);
        }
        <?php } ?>
        $.ajax({type: "POST",dataType:"json", url: '<?php echo $login_url; ?>', data: $(".login-form").serialize(),
            success: function(json) {
                // token 更新
                if (json.token) {
                    var token = json.token;
                    $(".login-form input[name='"+token.name+"']").val(token.value);
                }
                if (json.code == 9) {
                    // 二次验证
                    $(".dr_row_username").hide();
                    $(".dr_row_password").hide();
                    $(".dr_check").show();
                    $(".dr_check_phone").html(json.msg);
                    $("#dr_check_phone").val(json.data);
                    $("#is_check").val("yes");
                    $('.fc-code img').click();
                } else if (json.code == 1) {
                    window.location.href = json.data.url;
                } else {
                    <?php if (defined('SYS_ADMIN_LOGIN_AES') && SYS_ADMIN_LOGIN_AES) { ?>
                    $('#password').val("");
                    <?php } ?>
                    $('.fc-code img').click();
                    dr_msg(0, json.msg);
                }
            },
            error: function(HttpRequest, ajaxOptions, thrownError) {
                $('.fc-code img').click();
                var msg = HttpRequest.responseText;
                if (!msg) {
                    dr_msg(0, "<?php echo dr_lang('系统错误'); ?>");
                } else {
                    dr_msg(0, msg);
                }
            }
        });
        return false;
    }
    jQuery(document).ready(function() {
        $('.login-form input').keypress(function (e) {
            if (e.which == 13) {
                dr_submit();
                return false;
            }
        });
        top.$('#dr_go_url').hide();
    });
    function dr_msg(code, msg) {
        $('.post-msg span', $('.login-form')).html(msg);
        $('.post-msg', $('.login-form')).show();
        if (code) {
            $('.post-msg').removeClass("alert-danger");
            $('.post-msg').addClass("alert-info");
        } else {
            $('.post-msg').addClass("alert-danger");
            $('.post-msg').removeClass("alert-info");
        }
    }
</script>
<?php if (IS_OEM_CMS) { ?>
<script src="<?php echo $THEME_PATH; ?>assets/global/plugins/backstretch/jquery.backstretch.min.js?v=<?php echo CMF_UPDATE_TIME; ?>" type="text/javascript"></script>
<script type="text/javascript">
    <?php $bg = array('"'.$THEME_PATH.'assets/global/login/1.jpg"',
        '"'.$THEME_PATH.'assets/global/login/2.jpg"',
        '"'.$THEME_PATH.'assets/global/login/3.jpg"',
        '"'.$THEME_PATH.'assets/global/login/4.jpg"');
    shuffle($bg);
        ?>
    jQuery(document).ready(function() {
        $.backstretch([
            <?php echo implode(',', $bg); ?>
        ], {
                fade: 1000,
                    duration: 8000
            }
        );
    });
</script>
<?php }  if (isset($_GET['isback']) && $_GET['isback']) { ?>
<script type="text/javascript">
    $(function(){
        dr_tips(<?php echo intval($_GET['iscode']); ?>, "<?php echo dr_safe_replace($_GET['isback']); ?>")
    });
</script>
<?php } ?>
</body>
</html>