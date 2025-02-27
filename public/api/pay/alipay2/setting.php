<div class="form-group">
    <label class="col-md-2 control-label">APPID</label>
    <div class="col-md-9">
        <input type="text" class="form-control input-large" name="data[<?php echo $dir;?>][id]" value="<?php echo $data[$dir]['id']?>" >
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">私钥</label>
    <div class="col-md-9">
        <input type="text" class="form-control" name="data[<?php echo $dir;?>][key]" value="<?php echo $data[$dir]['key']?>" >
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">公钥</label>
    <div class="col-md-9">
        <input type="text" class="form-control" name="data[<?php echo $dir;?>][key2]" value="<?php echo $data[$dir]['key2']?>" >
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">手机网站Wap支付</label>
    <div class="col-md-9">
        <input type="checkbox" name="data[<?php echo $dir;?>][wap]" value="1" <?php echo $data[$dir]['wap'] ? "checked" : '';?> data-on-text="启用" data-off-text="关闭" data-on-color="success" data-off-color="danger" class="make-switch" data-size="small">
    </div>
</div>