<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>

<div class="note note-danger">
    <p> 这是我的模板  </p>
    <p>我传递过来的变量值是 <?php echo $testname; ?></p>
</div>



<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>