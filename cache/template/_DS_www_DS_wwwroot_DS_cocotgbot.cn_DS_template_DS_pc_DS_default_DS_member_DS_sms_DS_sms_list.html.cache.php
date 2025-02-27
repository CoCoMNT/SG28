<?php if ($fn_include = $this->_include("mheader.html")) include($fn_include); ?>

<div class="portlet light ">
    <div class="portlet-title">
        <div class="caption">
            <span class="caption-subject font-green sbold ">私信记录</span>
        </div>
    </div>
    <div class="portlet-body">

        <div class="mt-comments">
            <?php if (isset($list) && is_array($list) && $list) { $key_t=-1;$count_t=dr_count($list);foreach ($list as $t) { $key_t++; $is_first=$key_t==0 ? 1 : 0;$is_last=$count_t==$key_t+1 ? 1 : 0;?>
            <div class="mt-comment">
                <div class="mt-comment-img">
                    <img src="<?php echo $t['avatar']; ?>" width="40">
                </div>
                <div class="mt-comment-body">
                    <div class="mt-comment-info">
                        <span class="mt-comment-author"><?php echo $t['name'];  if (!$t['is_read']) { ?><i class="sms_new">新消息</i><?php } ?></span>
                        <span class="mt-comment-date"><?php echo dr_date($t['inputtime']); ?></span>
                    </div>
                    <div class="mt-comment-text">
                        <a href="<?php echo dr_member_url(APP_DIR.'/home/show', ['uid' => $t['to_uid']]); ?>"><?php echo trim($t['content']); ?></a>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>

            <div class="row fc-list-footer table-checkable ">
                <div class="col-md-12 fc-list-page">
                    <?php echo $mypages; ?>
                </div>
            </div>
    </div>
</div>

<style>
    .mt-comments .mt-comment {
        padding: 10px;
        border-bottom: 1px dashed #c0c0c0;
        margin: 0 0 10px 0; }
    .mt-comments .mt-comment .mt-comment-img {
        width: 40px;
        float: left; }
    .mt-comments .mt-comment .mt-comment-img > img {
        border-radius: 50% !important; }
    .mt-comments .mt-comment .mt-comment-body {
        padding-left: 20px;
        position: relative;
        overflow: hidden; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-info:before, .mt-comments .mt-comment .mt-comment-body .mt-comment-info:after {
        content: " ";
        display: table; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-info:after {
        clear: both; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-info .mt-comment-author {
        display: inline-block;
        float: left;
        margin: 0px 0px 10px 0;
        color: #060606;
        font-weight: 600; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-info .mt-comment-date {
        display: inline-block;
        float: right;
        margin: 0px;
        color: #BABABA; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-text {
        color: #999999; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details {
        margin: 10px 0px 0px 0; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-status {
        text-transform: uppercase;
        float: left; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-status.mt-comment-status-pending {
        color: #B8C0F5; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-status.mt-comment-status-approved {
        color: #6BD873; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-status.mt-comment-status-rejected {
        color: red; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-actions {
        display: none;
        list-style: none;
        margin: 0;
        padding: 0;
        float: right; }
    .sms_new {
        margin-left: 10px;
        color: red;
    }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-actions > li {
        float: left;
        padding: 0 5px;
        margin: 0; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-actions > li > a {
        text-transform: uppercase;
        color: #999999; }
    .mt-comments .mt-comment .mt-comment-body .mt-comment-details .mt-comment-actions > li > a:hover {
        color: #666666;
        text-decoration: none; }
    .mt-comments .mt-comment:hover {
        background: #f9f9f9; }
    .mt-comments .mt-comment:hover .mt-comment-body .mt-comment-details .mt-comment-actions {
        display: inline-block; }
</style>
<?php if ($fn_include = $this->_include("mfooter.html")) include($fn_include); ?>