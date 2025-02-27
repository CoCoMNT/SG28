<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

<form class="form-horizontal" role="form" id="myform">
    <?php echo dr_form_hidden(); ?>
    <div class="form-body">
        <div class="form-group">
            <label class="col-xs-2  "><?php echo dr_lang('表名称'); ?></label>
            <div class="col-xs-8">
                <input type="text" class="form-control" value="<?php echo $table; ?>" name="table">
                <span class="help-block"> 确认当前控制器所绑定的表名称（不带前缀） </span>
            </div>
        </div>
    </div>
</form>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>