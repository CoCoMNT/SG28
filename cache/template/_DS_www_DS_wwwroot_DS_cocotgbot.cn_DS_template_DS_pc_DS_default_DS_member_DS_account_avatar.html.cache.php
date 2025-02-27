<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include); ?>

<link href="<?php echo THEME_PATH; ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />

<script src="<?php echo THEME_PATH; ?>assets/js/avatar/iscroll-zoom.js"></script>
<script src="<?php echo THEME_PATH; ?>assets/js/avatar/hammer.js"></script>
<script src="<?php echo THEME_PATH; ?>assets/js/avatar/lrz.all.bundle.js"></script>
<script src="<?php echo THEME_PATH; ?>assets/js/avatar/jquery.photoClip.js"></script>
<style>

    #clipArea {
        margin-top: 20px;
        height: 300px;
        width: 300px;
    }
    #file,
    #clipBtn {
        margin: 5px;
    }
    #view {
        width: 300px; text-align: center; padding: 50px
    }

</style>
<?php if ($ci->member_cache['config']['avatar']) {  if ($member['is_avatar']) { ?>
<div class="alert alert-success">
    头像认证成功
</div>
<?php } else { ?>
<div class="alert alert-danger">
    系统强制用户上传头像
</div>
<?php }  }  if ($edit_verify) { ?>
<div class="alert alert-danger">
    <?php if ($edit_verify['status']) { ?>头像信息已提交，等待管理员审核<?php } else {  echo dr_date($edit_verify['updatetime']); ?>审核被拒绝：<?php echo $edit_verify['result'];  } ?>
</div>
<?php } ?>

<div class="portlet light ">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject"> 头像设置</span>
        </div>
    </div>
    <div class="portlet-body form fc-form">
        <div class="col-md-3 text-right" style="padding-top: 60px">
            <div id="view"><img width="100" height="100" class="img-circle" src="<?php echo $avatar_url; ?>?cache=<?php echo SYS_TIME; ?>"></div>
        </div>
        <div class="col-md-6">

            <div id="clipArea"></div>

            <div class="form-body" style="width: 300px; text-align: center">

                <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn green btn-file">
                                                <span class="fileinput-new"> 选择图片 </span>
                                                <input type="file" id="file">
                                            </span>
                </div>
                <button type="button" class="btn btn-info" id="clipBtn"> 保存头像</button>
            </div>

        </div>
    </div>

    <form action="" class="form-horizontal" method="post" name="myform" id="myform">
        <?php echo $form; ?>
    </form>
</div>

<script>
    var clipArea = new bjj.PhotoClip("#clipArea", {
        size: [200, 200],
        outputSize: [200, 200],
        file: "#file",
        view: "#view",
        ok: "#clipBtn",
        loadStart: function() {
            //console.log("照片读取中");
        },
        loadComplete: function() {
            //console.log("照片读取完成");
        },
        clipFinish: function(dataURL) {
            $('#dr_file').val(dataURL);
            dr_ajax_submit('<?php echo dr_now_url(); ?>', 'myform', '2000', '<?php echo dr_member_url('account/avatar', ['r'=>rand(0, 9999)]); ?>');
            if (window.applicationCache.status == window.applicationCache.UPDATEREADY) {
                window.applicationCache.update();
            }
        }
    });
</script>

<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>