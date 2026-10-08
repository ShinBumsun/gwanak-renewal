<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
include_once(G5_THEME_PATH.'/inc/theme.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css?ver='.G5_CSS_VER.'">', 0);

$gw_is_gallery = isset($gw_is_gallery) ? $gw_is_gallery : false;
?>

<div id="bo_list" class="bo<?php echo $gw_is_gallery ? ' bo--gallery' : ''; ?>">

    <?php if ($is_category) { ?>
    <nav class="bo-cate" aria-label="<?php echo get_text($board['bo_subject']); ?> 분류">
        <ul><?php echo $category_option; ?></ul>
    </nav>
    <?php } ?>

    <div class="bo-top">
        <p class="bo-total">전체 <strong><?php echo number_format($total_count); ?></strong>건<?php if ($page > 1) { ?> <span>· <?php echo $page; ?>페이지</span><?php } ?></p>

        <form name="fsearch" method="get" class="bo-sch" role="search">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
            <input type="hidden" name="sca" value="<?php echo $sca; ?>">
            <input type="hidden" name="sop" value="and">
            <label for="sfl" class="sound_only">검색대상</label>
            <select name="sfl" id="sfl">
                <?php if (function_exists('get_board_sfl_select_options')) { echo get_board_sfl_select_options($sfl); } else { ?>
                <option value="wr_subject"<?php echo get_selected($sfl, 'wr_subject', true); ?>>제목</option>
                <option value="wr_content"<?php echo get_selected($sfl, 'wr_content'); ?>>내용</option>
                <option value="wr_subject||wr_content"<?php echo get_selected($sfl, 'wr_subject||wr_content'); ?>>제목+내용</option>
                <?php } ?>
            </select>
            <label for="stx" class="sound_only">검색어</label>
            <input type="search" name="stx" value="<?php echo stripslashes($stx); ?>" id="stx" maxlength="20" placeholder="검색어를 입력하세요" required>
            <button type="submit" class="bo-sch-btn"><?php echo gwanak_icon('search'); ?><span class="sound_only">검색</span></button>
        </form>
    </div>

    <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
    <input type="hidden" name="stx" value="<?php echo $stx; ?>">
    <input type="hidden" name="spt" value="<?php echo $spt; ?>">
    <input type="hidden" name="sca" value="<?php echo $sca; ?>">
    <input type="hidden" name="sst" value="<?php echo $sst; ?>">
    <input type="hidden" name="sod" value="<?php echo $sod; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <input type="hidden" name="sw" value="">

    <?php if ($is_checkbox) { ?>
    <div class="bo-admin-bar">
        <label class="bo-chk-all"><input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);"> 전체선택</label>
        <button type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value" class="btn_b01">선택삭제</button>
        <button type="submit" name="btn_submit" value="선택복사" onclick="document.pressed=this.value" class="btn_b01">선택복사</button>
        <button type="submit" name="btn_submit" value="선택이동" onclick="document.pressed=this.value" class="btn_b01">선택이동</button>
    </div>
    <?php } ?>

    <?php if ($gw_is_gallery) { ?>
    <!-- 갤러리형 목록 -->
    <ul class="bo-gal">
        <?php for ($i = 0; $i < count($list); $i++) {
            $row   = $list[$i];
            $thumb = get_list_thumbnail($board['bo_table'], $row['wr_id'], 600, 420, false, true);
        ?>
        <li class="bo-gal-item<?php echo $row['is_notice'] ? ' is-notice' : ''; ?>">
            <?php if ($is_checkbox) { ?><label class="bo-chk"><input type="checkbox" name="chk_wr_id[]" value="<?php echo $row['wr_id']; ?>"><span class="sound_only"><?php echo $row['subject']; ?> 선택</span></label><?php } ?>
            <a href="<?php echo $row['href']; ?>">
                <span class="bo-gal-thumb">
                    <?php if (!empty($thumb['src'])) { ?>
                    <img src="<?php echo $thumb['src']; ?>" alt="" loading="lazy" width="600" height="420">
                    <?php } else { ?>
                    <span class="bo-noimg"><?php echo gwanak_icon($bo_table === 'movie' ? 'video' : 'image'); ?></span>
                    <?php } ?>
                    <?php if ($bo_table === 'movie') { ?><span class="bo-play"><?php echo gwanak_icon('play'); ?></span><?php } ?>
                </span>
                <span class="bo-gal-body">
                    <?php if ($is_category && $row['ca_name']) { ?><em class="bo-tag"><?php echo $row['ca_name']; ?></em><?php } ?>
                    <strong class="bo-gal-tit"><?php if (!empty($row['icon_secret'])) echo '<span class="bo-ico-lock">비밀글</span>'; ?><?php echo $row['subject']; ?></strong>
                    <span class="bo-meta"><time datetime="<?php echo date('Y-m-d', strtotime($row['wr_datetime'])); ?>"><?php echo date('Y.m.d', strtotime($row['wr_datetime'])); ?></time><span>조회 <?php echo number_format($row['wr_hit']); ?></span></span>
                </span>
            </a>
        </li>
        <?php } ?>
    </ul>
    <?php if (count($list) == 0) { ?><p class="bo-empty">등록된 게시물이 없습니다.</p><?php } ?>

    <?php } else { ?>
    <!-- 목록형 -->
    <div class="bo-tbl">
        <table>
            <caption class="sound_only"><?php echo get_text($board['bo_subject']); ?> 목록</caption>
            <thead>
            <tr>
                <?php if ($is_checkbox) { ?><th scope="col" class="col-chk"><span class="sound_only">선택</span></th><?php } ?>
                <th scope="col" class="col-num">번호</th>
                <th scope="col" class="col-subject">제목</th>
                <th scope="col" class="col-name">작성자</th>
                <th scope="col" class="col-date">등록일</th>
                <th scope="col" class="col-hit">조회</th>
            </tr>
            </thead>
            <tbody>
            <?php for ($i = 0; $i < count($list); $i++) {
                $row = $list[$i];
            ?>
            <tr class="<?php echo $row['is_notice'] ? 'is-notice' : ''; ?><?php echo $wr_id == $row['wr_id'] ? ' is-current' : ''; ?>">
                <?php if ($is_checkbox) { ?><td class="col-chk"><label class="bo-chk"><input type="checkbox" name="chk_wr_id[]" value="<?php echo $row['wr_id']; ?>"><span class="sound_only"><?php echo $row['subject']; ?> 선택</span></label></td><?php } ?>
                <td class="col-num">
                    <?php if ($row['is_notice']) { ?><strong class="bo-badge">공지</strong>
                    <?php } else if ($wr_id == $row['wr_id']) { ?><span class="bo-badge is-reading">열람중</span>
                    <?php } else { echo $row['num']; } ?>
                </td>
                <td class="col-subject">
                    <div class="bo-subject" style="<?php echo $row['reply'] ? 'padding-left:'.(strlen($row['wr_reply']) * 16).'px' : ''; ?>">
                        <?php if ($is_category && $row['ca_name']) { ?><a href="<?php echo $row['ca_name_href']; ?>" class="bo-tag"><?php echo $row['ca_name']; ?></a><?php } ?>
                        <a href="<?php echo $row['href']; ?>" class="bo-subject-link">
                            <?php if ($row['reply']) { ?><span class="bo-reply" aria-label="답변글">└</span><?php } ?>
                            <?php if (!empty($row['icon_secret'])) { ?><span class="bo-ico-lock">비밀글</span><?php } ?>
                            <?php echo $row['subject']; ?>
                        </a>
                        <?php if ($row['icon_new']) { ?><i class="bo-new" aria-label="새 글">N</i><?php } ?>
                        <?php if (!empty($row['icon_file'])) { ?><span class="bo-ico-file" aria-label="첨부파일 있음"></span><?php } ?>
                        <?php if ($row['comment_cnt']) { ?><span class="bo-cmt" aria-label="댓글 <?php echo $row['wr_comment']; ?>개"><?php echo $row['wr_comment']; ?></span><?php } ?>
                    </div>
                    <p class="bo-m-meta"><span><?php echo $row['name']; ?></span><span><?php echo date('Y.m.d', strtotime($row['wr_datetime'])); ?></span><span>조회 <?php echo number_format($row['wr_hit']); ?></span></p>
                </td>
                <td class="col-name"><?php echo $row['name']; ?></td>
                <td class="col-date"><?php echo date('Y.m.d', strtotime($row['wr_datetime'])); ?></td>
                <td class="col-hit"><?php echo number_format($row['wr_hit']); ?></td>
            </tr>
            <?php } ?>
            <?php if (count($list) == 0) { ?>
            <tr><td colspan="<?php echo $is_checkbox ? 6 : 5; ?>" class="bo-empty">등록된 게시물이 없습니다.</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <?php } ?>
    </form>

    <div class="bo-bottom">
        <?php echo $write_pages; ?>
        <div class="bo-btns">
            <?php if ($admin_href) { ?><a href="<?php echo $admin_href; ?>" class="btn_admin">관리자</a><?php } ?>
            <?php if ($list_href) { ?><a href="<?php echo $list_href; ?>" class="btn_b01">목록</a><?php } ?>
            <?php if ($write_href) { ?><a href="<?php echo $write_href; ?>" class="btn_b02"><?php echo gwanak_icon('plus'); ?> 글쓰기</a><?php } ?>
        </div>
    </div>
</div>

<?php if ($is_checkbox) { ?>
<noscript><p>자바스크립트를 사용하지 않는 경우 별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p></noscript>
<script>
function all_checked(sw) {
    var f = document.fboardlist;
    for (var i = 0; i < f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]") f.elements[i].checked = sw;
    }
}
function fboardlist_submit(f) {
    var chk_count = 0;
    for (var i = 0; i < f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked) chk_count++;
    }
    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }
    if (document.pressed == "선택복사") { select_copy("copy"); return; }
    if (document.pressed == "선택이동") { select_copy("move"); return; }
    if (document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;
        f.removeAttribute("target");
        f.action = g5_bbs_url + "/board_list_update.php";
    }
    return true;
}
function select_copy(sw) {
    var f = document.fboardlist;
    window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");
    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url + "/move.php";
    f.submit();
}
</script>
<?php } ?>
