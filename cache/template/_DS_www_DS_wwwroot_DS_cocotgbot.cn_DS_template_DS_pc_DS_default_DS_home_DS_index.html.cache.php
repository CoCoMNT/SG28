<?php if ($fn_include = $this->_include("header.html")) include($fn_include); ?>


<div class="row g-5 g-xl-10 mb-5 mb-xl-10 mt-1">

    <div class="col-xl-8">


        <!-- Swiper  -->
        <link rel="stylesheet" href="<?php echo HOME_THEME_PATH; ?>swiper/css/swiper.min.css">
        <script src="<?php echo HOME_THEME_PATH; ?>swiper/js/swiper.min.js"></script>
        <div data-action="site_param:hdtp" class="tpl-edit-show swiper-container card pt-5 pb-5">
            <div class="swiper-wrapper">
                <?php $mysite=dr_site_value('hdtp'); if (isset($mysite) && is_array($mysite) && $mysite) { $key_v=-1;$count_v=dr_count($mysite);foreach ($mysite as $v) { $key_v++; $is_first=$key_v==0 ? 1 : 0;$is_last=$count_v==$key_v+1 ? 1 : 0;?>
                <div class="swiper-slide">
                    <a href="<?php echo $v[3]; ?>" target="_blank"><img src="<?php echo dr_get_file($v[1]); ?>" style="height:400px;width: 100%" /></a>
                </div>
                <?php } } ?>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>


        <!-- Initialize Swiper -->
        <script>
            var swiper = new Swiper('.swiper-container', {
                spaceBetween: 30,
                centeredSlides: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        </script>

    </div>
    <div class="col-xl-4">
        <!--begin::Chart Widget 35-->
        <div class="card card-flush h-md-100">
            <!--begin::Body-->
            <div data-action="index_zuixin" class="tpl-edit-show card-body pt-6">
                <!--最新文章-->
                <?php $list_return = $this->list_tag("action=module module=news num=6"); if ($list_return && is_array($list_return)) extract($list_return, EXTR_OVERWRITE); $count=dr_count($return); if (is_array($return) && $return) { $key=-1; foreach ($return as $t) { $key++; $is_first=$key==0 ? 1 : 0;$is_last=$count==$key+1 ? 1 : 0; ?>
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                        <!--begin:Author-->
                        <div class="flex-grow-1 me-2">
                            <a href="<?php echo $t['url']; ?>" class="text-gray-800 text-hover-primary fs-6 fw-bold"><?php echo dr_strcut($t['title'], 15); ?></a>
                            <span class="text-muted fw-semibold d-block fs-7"><?php echo $t['updatetime']; ?></span>
                        </div>
                        <!--end:Author-->
                        <!--begin::Actions-->
                        <a href="<?php echo $t['url']; ?>" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="currentColor"></rect>
                                    <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="currentColor"></path>
                                </svg>
                            </span>
                        </a>
                        <!--begin::Actions-->
                    </div>
                    <!--end::Section-->
                </div>
                <!--end::Item-->
                <?php if (!$is_last) { ?>
                <!--begin::Separator-->
                <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->
                <?php } ?>
                <!--end::Item-->
                <?php } } ?>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart Widget 35-->
    </div>
</div>

<div class="row g-5 g-xl-10 mb-5 mb-xl-10 ">
    <!--begin::Col-->
    <div class="col-xl-8">

        <div data-action="index_zuixin_data" class="tpl-edit-show article-list" id="content_list">
            <?php if ($fn_include = $this->_include("index_data.html")) include($fn_include); ?>
        </div>

    </div>
    <!--end::Col-->

    <!--begin::Col-->
    <div class="col-xl-4">
        <!--begin::Chart Widget 35-->
        <div data-action="index_hits" class="tpl-edit-show card card-flush ">
            <!--begin::Header-->
            <div class="card-header pt-5 ">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <!--begin::Statistics-->
                    <div class="d-flex align-items-center mb-2">
                        <!--begin::Currency-->
                        <span class="fs-5 fw-bold text-gray-800 ">热门资讯</span>
                        <!--end::Currency-->
                    </div>
                    <!--end::Statistics-->
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-3">
                <!--热门文章-->
                <?php $list_return = $this->list_tag("action=module module=news order=hits num=10"); if ($list_return && is_array($list_return)) extract($list_return, EXTR_OVERWRITE); $count=dr_count($return); if (is_array($return) && $return) { $key=-1; foreach ($return as $t) { $key++; $is_first=$key==0 ? 1 : 0;$is_last=$count==$key+1 ? 1 : 0; ?>
                <!--begin::Item-->
                <div class="d-flex flex-stack mb-7">
                    <!--begin::Symbol-->
                    <div class="symbol symbol-60px symbol-2by3 me-4">
                        <div class="symbol-label" style="background-image: url('<?php echo dr_thumb($t['thumb']); ?>')"></div>
                    </div>
                    <!--end::Symbol-->
                    <!--begin::Title-->
                    <div class="m-0">
                        <a href="<?php echo $t['url']; ?>" class="text-dark fw-bold text-hover-primary fs-6"><?php echo dr_strcut($t['title'], 15); ?></a>
                        <span class="text-gray-600 fw-semibold d-block pt-1 fs-7"><?php echo dr_strcut($t['description'], 50); ?></span>
                    </div>
                    <!--end::Title-->
                </div>
                <?php } } ?>

            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart Widget 35-->


        <!--begin::Chart Widget 35-->
        <div data-action="site_param:yqlj" class="tpl-edit-show card card-flush mt-10">
            <!--begin::Header-->
            <div class="card-header pt-5 ">
                <!--begin::Title-->
                <h3 class="card-title align-items-start flex-column">
                    <!--begin::Statistics-->
                    <div class="d-flex align-items-center mb-2">
                        <!--begin::Currency-->
                        <span class="fs-5 fw-bold text-gray-800 ">友情链接</span>
                        <!--end::Currency-->
                    </div>
                    <!--end::Statistics-->
                </h3>
                <!--end::Title-->
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-3">
                <!--友情链接-->
                <?php $mysite=dr_site_value('yqlj'); if (isset($mysite) && is_array($mysite) && $mysite) { $key_v=-1;$count_v=dr_count($mysite);foreach ($mysite as $v) { $key_v++; $is_first=$key_v==0 ? 1 : 0;$is_last=$count_v==$key_v+1 ? 1 : 0;?>
                <div class="d-flex flex-stack">
                    <!--begin::Section-->
                    <a href="<?php echo $v[2]; ?>" target="_blank" class="text-primary fw-semibold fs-6 me-2"><?php echo $v[1]; ?></a>
                    <!--end::Section-->
                    <!--begin::Action-->
                    <a href="<?php echo $v[2]; ?>" target="_blank" class="btn btn-icon btn-sm h-auto btn-color-gray-400 btn-active-color-primary justify-content-end">
                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr095.svg-->
                        <span class="svg-icon svg-icon-2">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.3" d="M4.7 17.3V7.7C4.7 6.59543 5.59543 5.7 6.7 5.7H9.8C10.2694 5.7 10.65 5.31944 10.65 4.85C10.65 4.38056 10.2694 4 9.8 4H5C3.89543 4 3 4.89543 3 6V19C3 20.1046 3.89543 21 5 21H18C19.1046 21 20 20.1046 20 19V14.2C20 13.7306 19.6194 13.35 19.15 13.35C18.6806 13.35 18.3 13.7306 18.3 14.2V17.3C18.3 18.4046 17.4046 19.3 16.3 19.3H6.7C5.59543 19.3 4.7 18.4046 4.7 17.3Z" fill="currentColor"></path>
                                <rect x="21.9497" y="3.46448" width="13" height="2" rx="1" transform="rotate(135 21.9497 3.46448)" fill="currentColor"></rect>
                                <path d="M19.8284 4.97161L19.8284 9.93937C19.8284 10.5252 20.3033 11 20.8891 11C21.4749 11 21.9497 10.5252 21.9497 9.93937L21.9497 3.05029C21.9497 2.498 21.502 2.05028 20.9497 2.05028L14.0607 2.05027C13.4749 2.05027 13 2.52514 13 3.11094C13 3.69673 13.4749 4.17161 14.0607 4.17161L19.0284 4.17161C19.4702 4.17161 19.8284 4.52978 19.8284 4.97161Z" fill="currentColor"></path>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </a>
                    <!--end::Action-->
                </div>
                <?php if (!$is_last) { ?>
                <!--begin::Separator-->
                <div class="separator separator-dashed my-4"></div>
                <!--end::Separator-->
                <?php }  } } ?>


            </div>
            <!--end::Body-->
        </div>
        <!--end::Chart Widget 35-->
    </div>
    <!--end::Col-->
</div>

<script>
    var Mpage=1;

    //滚动显示更多
    var scroll_get = true;  //做个标志,不要反反复复的加载
    $(document).ready(function () {
        $(window).scroll(function () {
            if (scroll_get==true &&  (400 + $(window).scrollTop())>($(document).height() - $(window).height())) {
                scroll_get = false;
                layer.msg('内容加截中,请稍候',{time:1000});
                dr_ajax_load_more();
            }
        });
    });

    function dr_ajax_load_more(){
        Mpage++;
        $.get('/index.php?s=api&c=api&m=template&name=index_data.html&format=json&page='+Mpage+'&'+Math.random(),function(res){
            $('.footer-cont').hide();
            if(res.code==1){
                if (res.msg.indexOf("<!--begin::Icon-->") != -1) {
                    $('#content_list').append(res.msg);
                    scroll_get = true;
                } else {
                    layer.msg("已经显示完了",{time:500});
                }
            }else{
                layer.msg(res.msg,{time:2500});
            }
        }, 'json');
    }

    (function(){
        var bp = document.createElement('script');
        var curProtocol = window.location.protocol.split(':')[0];
        if (curProtocol === 'https') {
            bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
        }
        else {
            bp.src = 'http://push.zhanzhang.baidu.com/push.js';
        }
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(bp, s);
    })();
</script>
<?php if ($fn_include = $this->_include("footer.html")) include($fn_include); ?>