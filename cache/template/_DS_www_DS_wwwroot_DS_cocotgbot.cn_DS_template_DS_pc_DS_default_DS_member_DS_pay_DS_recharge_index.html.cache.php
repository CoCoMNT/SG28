<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include); ?>

    <div class="row">

        <div class="col-md-12">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption">
                        <span class="caption-subject"> 在线充值</span>
                    </div>
                </div>
                <div class="portlet-body form fc-form">

                    <?php echo $payfield; ?>

                </div>
            </div>

        </div>

    </div>


<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>