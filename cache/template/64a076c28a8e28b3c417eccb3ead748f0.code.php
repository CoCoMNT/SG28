<form action="<?php echo $pay_url; ?>" class="form-horizontal" method="post" name="myform" id="payform">
    <?php echo dr_form_hidden(); ?>
    <input type="hidden" name="pay[mark]" value="<?php echo $html['mark']; ?>" />
    <input type="hidden" name="pay[title]" value="<?php echo $html['title']; ?>" />
    <div class="form-body form">

        <div class="form-group">
            <label class="col-md-2 control-label">付款金额</label>
            <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-rmb"></i>
                    </span>
                    <input type="text" name="pay[money]" value="<?php echo $html['pay_value']; ?>" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-2 control-label" style="padding-top:10px">付款方式</label>
            <div class="col-md-8">
                <div class="btn-group" data-toggle="buttons">
                    <?php if (isset($html['pay_type']) && is_array($html['pay_type']) && $html['pay_type']) { $key_t=-1;$count_t=dr_count($html['pay_type']);foreach ($html['pay_type'] as $name=>$t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0; ?>
                    <label onclick="dr_select_paytype('<?php echo $name; ?>')" class="btn btn-lg btn-default <?php if ($name == $html['pay_default']) { ?>active<?php } ?>"> <input type="radio" class="toggle"> <?php echo $t['icon'];  echo $t['name']; ?> </label>
                    <?php } } ?>
                    <input type="hidden" name="pay[type]" value="<?php echo $html['pay_default']; ?>" id="dr_payselect">
                </div>
            </div>
        </div>

        <div class="form-actions fc-form-actions">
            <label class="col-md-2 control-label">  </label>
            <div class="col-md-9 fc-form-submit">
                <button type="submit"  class="btn btn-lg green"> 确认付款 </button>
            </div>
        </div>

    </div>
</form>