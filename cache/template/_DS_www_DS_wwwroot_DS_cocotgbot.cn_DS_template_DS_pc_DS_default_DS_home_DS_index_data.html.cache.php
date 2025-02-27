<!--用于首页瀑布列调用的写法-->
<?php $list_return = $this->list_tag("action=module module=news order=updatetime page=1 maxlimit=100"); if ($list_return && is_array($list_return)) extract($list_return, EXTR_OVERWRITE); $count=dr_count($return); if (is_array($return) && $return) { $key=-1; foreach ($return as $t) { $key++; $is_first=$key==0 ? 1 : 0;$is_last=$count==$key+1 ? 1 : 0; ?>
<div class="card mb-5">
    <div class="card-body d-flex align-items-center p-5 p-lg-8">
        <!--begin::Icon-->
        <div class="mobile-hidden d-flex h-120px w-120px h-lg-120px w-lg-120px flex-shrink-0 flex-center position-relative align-self-start align-self-lg-center mt-3 mt-lg-0">
            <div class="symbol symbol-90px symbol-2by3 me-4">
                <div class="symbol-label" style="background-image: url('<?php echo dr_thumb($t['thumb']); ?>')"></div>
            </div>
        </div>
        <!--end::Icon-->

        <!--begin::Description-->
        <div class="ms-6">
            <h5 class="article-title">
                <a href="<?php echo $t['url']; ?>" class="text-dark"><?php echo $t['title']; ?></a>
            </h5>
            <p class="list-unstyled text-gray-600 fw-semibold fs-6 p-0 m-0">
                <?php echo dr_strcut($t['description'], 80); ?>
            </p>
            <div class="article-tag">
                <?php $kws=dr_get_content_kws($t['keywords'], 'news');  if ($kws) {  $kws=dr_arraycut($kws, 3);  if (isset($kws) && is_array($kws) && $kws) { $key_url=-1;$count_url=dr_count($kws);foreach ($kws as $name=>$url) { $key_url++; $is_first=$key_url==0 ? 1 : 0;$is_last=$count_url==$key_url+1 ? 1 : 0; ?>
                <a href="<?php echo $url; ?>" class="badge badge-light-primary fw-bold my-2"><?php echo $name; ?></a>
                <?php } }  } ?>

                <span class="text-muted   mx-2 fs-7"><i class="fa fa-calendar"></i> <?php echo $t['updatetime']; ?></span>
                <span class="text-muted  mx-2  fs-7" itemprop="comments"><i class="fa fa-comments"></i> <?php echo intval($t['comments']); ?> 评论</span>
                <span class="text-muted  mx-2  fs-7" itemprop="views" title="浏览次数"><i class="fa fa-eye"></i> <?php echo intval($t['hits']); ?> 浏览</span>
            </div>
        </div>
        <!--end::Description-->
    </div>
</div>
<?php } } ?>