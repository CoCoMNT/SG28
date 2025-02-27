<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include);  if ($edit_verify) { ?>
<div class="alert alert-danger">
    <?php if ($edit_verify['status']) { ?>资料信息已提交，等待管理员审核<?php } else {  echo dr_date($edit_verify['updatetime']); ?>审核被拒绝：<?php echo $edit_verify['result'];  } ?>
</div>
<?php } ?>
    <div class="row">

        <div class="col-md-12">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"> 资料修改</span>
                    </div>
                </div>
                <div class="portlet-body form fc-form">
                    <form action="" class="form-horizontal" method="post" name="myform" id="myform">
                        <?php echo $form; ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label">账号</label>
                            <div class="col-md-9">
                                <p class="form-control-static"> <?php echo $member['username']; ?> </p>
                            </div>
                        </div>
                        <?php if ($member['email']) { ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label"> 邮箱 </label>
                            <div class="col-md-9">
                                <p class="form-control-static"> <?php echo $member['email']; ?> </p>
                            </div>
                        </div>
                        <?php }  if ($member['phone']) { ?>
                        <div class="form-group">
                            <label class="col-md-2 control-label"> 手机 </label>
                            <div class="col-md-9">
                                <p class="form-control-static"> <?php echo $member['phone']; ?> </p>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="form-group" id="dr_row_name">
                            <label class="col-md-2 control-label"><span class="required" aria-required="true"> * </span> <?php echo MEMBER_CNAME; ?> </label>
                            <div class="col-md-9">
                                <label><input type="text" <?php if (!$is_update_name) { ?> readonly<?php } ?> id="dr_name" class="form-control" value="<?php echo $member['name']; ?>" name="data[name]"></label>
                            </div>
                        </div>
                        <?php echo $myfield; ?>
                        <div class="form-actions fc-form-actions">
                            <label class="col-md-2 control-label">  </label>
                            <div class="col-md-9 fc-form-submit">
                                <button type="button" onclick="dr_ajax_submit('<?php echo dr_now_url(); ?>', 'myform', '2000')" class="btn blue"> 提交保存 </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>

    </div>

<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>