<?php

function dr_sms_replace_content($content) {

    $face = ["[微笑]", "[嘻嘻]", "[哈哈]", "[可爱]", "[可怜]", "[挖鼻]", "[吃惊]", "[害羞]", "[挤眼]", "[闭嘴]", "[鄙视]", "[爱你]", "[泪]", "[偷笑]", "[亲亲]", "[生病]", "[太开心]", "[白眼]", "[右哼哼]", "[左哼哼]", "[嘘]", "[衰]", "[委屈]", "[吐]", "[哈欠]", "[抱抱]", "[怒]", "[疑问]", "[馋嘴]", "[拜拜]", "[思考]", "[汗]", "[困]", "[睡]", "[钱]", "[失望]", "[酷]", "[色]", "[哼]", "[鼓掌]", "[晕]", "[悲伤]", "[抓狂]", "[黑线]", "[阴险]", "[怒骂]", "[互粉]", "[心]", "[伤心]", "[猪头]", "[熊猫]", "[兔子]", "[ok]", "[耶]", "[good]", "[NO]", "[赞]", "[来]", "[弱]", "[草泥马]", "[神马]", "[囧]", "[浮云]", "[给力]", "[围观]", "[威武]", "[奥特曼]", "[礼物]", "[钟]", "[话筒]", "[蜡烛]", "[蛋糕]"];
    foreach ($face as $i => $t) {
        $content = str_replace('face'.$t, '<img src="'.dr_get_app_css('sms').'images/face/'.$i.'.gif">', $content);
    }
    if (strpos($content, 'img[') !== false) {
        $content = preg_replace('/img\[(.+)\]/U', '<img class="layui-layim-photos" src="$1">', $content);

    }
    if (strpos($content, 'a(') !== false) {
        $content = preg_replace("/a\((.+)\)/U", "<a href='$1' target='_blank'>$1</a>", $content);
    }

    return $content;
}