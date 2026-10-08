<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once(G5_THEME_PATH.'/inc/theme.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css?ver='.G5_CSS_VER.'">', 0);
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<article id="bo_v" class="bo-view">
    <header class="bo-v-head">
        <?php if ($category_name) { ?><p class="bo-tag"><?php echo $view['ca_name']; ?></p><?php } ?>
        <h3 class="bo-v-tit"><?php echo get_text($view['wr_subject']); ?></h3>
        <ul class="bo-v-meta">
            <li><span class="sound_only">작성자</span><?php echo gwanak_icon('user'); ?><?php echo $view['name']; ?><?php if ($is_ip_view) { echo ' ('.$ip.')'; } ?></li>
            <li><span class="sound_only">작성일</span><?php echo gwanak_icon('calendar'); ?><time datetime="<?php echo date('Y-m-d\TH:i', strtotime($view['wr_datetime'])); ?>"><?php echo date('Y.m.d H:i', strtotime($view['wr_datetime'])); ?></time></li>
            <li><span class="sound_only">조회수</span><?php echo gwanak_icon('search'); ?>조회 <?php echo number_format($view['wr_hit']); ?></li>
            <?php if ($board['bo_use_comment'] || $view['wr_comment']) { ?><li><a href="#bo_vc"><?php echo gwanak_icon('chat'); ?>댓글 <?php echo number_format($view['wr_comment']); ?></a></li><?php } ?>
        </ul>
    </header>

    <section id="bo_v_atc" class="bo-v-body">
        <h4 class="sound_only">본문</h4>
        <?php
        // 본문 위 이미지 첨부파일
        if (!empty($view['file']) && is_array($view['file'])) {
            $gw_imgs = '';
            foreach ($view['file'] as $k => $view_file) {
                if (!is_int($k) || empty($view_file['view'])) continue;
                $gw_imgs .= function_exists('get_file_thumbnail') ? get_file_thumbnail($view_file) : $view_file['view'];
            }
            if ($gw_imgs) echo '<div id="bo_v_img" class="bo-v-img">'.$gw_imgs.'</div>';
        }
        ?>
        <div id="bo_v_con" class="bo-v-con"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php if ($is_signature) { ?><p class="bo-v-sign"><?php echo $signature; ?></p><?php } ?>

        <?php if ($good_href || $nogood_href) { ?>
        <div class="bo-v-act">
            <?php if ($good_href) { ?><a href="<?php echo $good_href.'&amp;'.$qstr; ?>" id="good_button" class="btn_b01">추천 <strong><?php echo number_format($view['wr_good']); ?></strong></a><b id="bo_v_act_good"></b><?php } ?>
            <?php if ($nogood_href) { ?><a href="<?php echo $nogood_href.'&amp;'.$qstr; ?>" id="nogood_button" class="btn_b01">비추천 <strong><?php echo number_format($view['wr_nogood']); ?></strong></a><b id="bo_v_act_nogood"></b><?php } ?>
        </div>
        <?php } ?>
    </section>

    <?php
    // 다운로드 첨부파일
    $gw_files = array();
    if (!empty($view['file']['count'])) {
        for ($i = 0; $i < count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && empty($view['file'][$i]['view'])) $gw_files[] = $view['file'][$i];
        }
    }
    if ($gw_files) { ?>
    <section id="bo_v_file" class="bo-v-files">
        <h4>첨부파일 <span><?php echo count($gw_files); ?></span></h4>
        <ul>
            <?php foreach ($gw_files as $f) { ?>
            <li>
                <a href="<?php echo $f['href']; ?>" class="view_file_download">
                    <span class="bo-file-name"><?php echo $f['source']; ?></span>
                    <span class="bo-file-info"><?php echo $f['size']; ?> · 다운로드 <?php echo $f['download']; ?>회</span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </section>
    <?php } ?>

    <?php if (isset($view['link']) && array_filter($view['link'])) { ?>
    <section id="bo_v_link" class="bo-v-files is-link">
        <h4>관련링크</h4>
        <ul>
            <?php for ($i = 1; $i <= count($view['link']); $i++) { if (!$view['link'][$i]) continue; ?>
            <li>
                <a href="<?php echo $view['link_href'][$i]; ?>" target="_blank" rel="noopener">
                    <span class="bo-file-name"><?php echo cut_str($view['link'][$i], 70); ?></span>
                    <span class="bo-file-info">새 창 · <?php echo $view['link_hit'][$i]; ?>회 연결</span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </section>
    <?php } ?>

    <div class="bo-v-btns">
        <div class="bo-v-btns-l">
            <?php if ($update_href) { ?><a href="<?php echo $update_href; ?>" class="btn_b01">수정</a><?php } ?>
            <?php if ($delete_href) { ?><a href="<?php echo $delete_href; ?>" onclick="del(this.href); return false;" class="btn_b01">삭제</a><?php } ?>
            <?php if ($copy_href) { ?><a href="<?php echo $copy_href; ?>" onclick="board_move(this.href); return false;" class="btn_admin">복사</a><?php } ?>
            <?php if ($move_href) { ?><a href="<?php echo $move_href; ?>" onclick="board_move(this.href); return false;" class="btn_admin">이동</a><?php } ?>
            <?php if ($scrap_href) { ?><a href="<?php echo $scrap_href; ?>" target="_blank" onclick="win_scrap(this.href); return false;" class="btn_b01">스크랩</a><?php } ?>
        </div>
        <div class="bo-v-btns-r">
            <?php if ($reply_href) { ?><a href="<?php echo $reply_href; ?>" class="btn_b01">답변</a><?php } ?>
            <?php if ($write_href) { ?><a href="<?php echo $write_href; ?>" class="btn_b01">글쓰기</a><?php } ?>
            <a href="<?php echo $list_href; ?>" class="btn_b02">목록</a>
        </div>
    </div>

    <?php if ($prev_href || $next_href) { ?>
    <ul class="bo-v-nav">
        <?php if ($prev_href) { ?><li><span class="bo-v-nav-label"><?php echo gwanak_icon('up'); ?>이전글</span><a href="<?php echo $prev_href; ?>"><?php echo $prev_wr_subject; ?></a><span class="bo-v-nav-date"><?php echo str_replace('-', '.', substr($prev_wr_date, 0, 10)); ?></span></li><?php } ?>
        <?php if ($next_href) { ?><li><span class="bo-v-nav-label"><?php echo gwanak_icon('down'); ?>다음글</span><a href="<?php echo $next_href; ?>"><?php echo $next_wr_subject; ?></a><span class="bo-v-nav-date"><?php echo str_replace('-', '.', substr($next_wr_date, 0, 10)); ?></span></li><?php } ?>
    </ul>
    <?php } ?>

    <?php
    // 댓글
    include_once(G5_BBS_PATH.'/view_comment.php');
    ?>
</article>

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if (!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }
        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']); ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";
        if (confirm(msg)) {
            $(this).attr("href", $(this).attr("href") + "&js=on");
            return true;
        }
        return false;
    });
});
<?php } ?>
function board_move(href) {
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });
    $("#good_button, #nogood_button").click(function() {
        var $tx = (this.id == "good_button") ? $("#bo_v_act_good") : $("#bo_v_act_nogood");
        excute_good(this.href, $(this), $tx);
        return false;
    });
    if ($.fn.viewimageresize) $("#bo_v_atc").viewimageresize();
});
function excute_good(href, $el, $tx) {
    $.post(href, { js: "on" }, function(data) {
        if (data.error) { alert(data.error); return false; }
        if (data.count) {
            $el.find("strong").text(number_format(String(data.count)));
            $tx.text(($el.attr("id") == "good_button") ? "이 글을 추천하셨습니다." : "이 글을 비추천하셨습니다.");
            $tx.fadeIn(200).delay(2500).fadeOut(200);
        }
    }, "json");
}
</script>
