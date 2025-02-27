<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

<form class="form-horizontal" role="form" id="myform">
    <?php echo $form; ?>
    <div class="form-body">
        <div class="form-group" id="dr_row_name">
            <label class="col-xs-3 control-label "><?php echo dr_lang('文件名'); ?></label>
            <div class="col-xs-8">
                <input type="text" class="form-control" id="dr_name" name="name">
                <span class="help-block"> <?php echo dr_lang('由a-z的字母开头，可以包含数字或_符号'); ?> </span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label "><?php echo dr_lang('存储路径'); ?></label>
            <div class="col-xs-8">
                <div class="form-control-static"><?php echo $path; ?></div>
            </div>
        </div>
    </div>
</form>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>