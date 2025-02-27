<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>


    <div class="form-body">


        <div class="form-group">
            <label class="col-xs-3 control-label ajax_name">URI地址</label>
            <div class="col-xs-8">
                <div class="form-control-static"><?php echo $uri; ?></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-3 control-label ajax_name">控制器文件</label>
            <div class="col-xs-8">
                <div class="form-control-static"><?php echo $cfile; ?></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-3 control-label ajax_name">访问地址</label>
            <div class="col-xs-8">
                <div class="form-control-static"><?php echo $curl; ?></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-3 control-label ajax_name">&nbsp;</label>
            <div class="col-xs-8">
                <div class="form-control-static">m参数是方法名称，指控制器中的function函数方法名称</div>
            </div>
        </div>

    </div>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>