<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_THEME_PATH.'/inc/theme.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.$faq_skin_url.'/style.css?ver='.G5_CSS_VER.'">', 0);
?>

<div class="faq">
    <?php
    if (!empty($himg_src)) echo '<div class="faq-himg"><img src="'.$himg_src.'" alt=""></div>';
    if (!empty($fm['fm_head_html'])) echo '<div class="faq-html">'.conv_content($fm['fm_head_html'], 1).'</div>';
    ?>

    <div class="faq-top">
        <?php if (count($faq_master_list)) { ?>
        <nav class="bo-cate faq-cate" aria-label="자주하는 질문 분류">
            <ul>
                <?php foreach ($faq_master_list as $v) { ?>
                <li><a href="<?php echo $category_href; ?>?fm_id=<?php echo $v['fm_id']; ?>"<?php echo $v['fm_id'] == $fm_id ? ' id="bo_cate_on" aria-current="page"' : ''; ?>><?php echo $v['fm_subject']; ?></a></li>
                <?php } ?>
            </ul>
        </nav>
        <?php } ?>

        <form name="faq_search_form" method="get" class="bo-sch" role="search">
            <input type="hidden" name="fm_id" value="<?php echo $fm_id; ?>">
            <label for="stx" class="sound_only">질문 검색</label>
            <input type="search" name="stx" value="<?php echo $stx; ?>" id="stx" maxlength="15" placeholder="궁금한 내용을 검색하세요" required>
            <button type="submit" class="bo-sch-btn"><?php echo gwanak_icon('search'); ?><span class="sound_only">검색</span></button>
        </form>
    </div>

    <?php if (count($faq_list)) { ?>
    <ul class="faq-list">
        <?php foreach ($faq_list as $v) { if (empty($v)) continue; ?>
        <li>
            <details>
                <summary><span class="faq-q" aria-hidden="true">Q</span><span class="faq-tit"><?php echo conv_content($v['fa_subject'], 1); ?></span><?php echo gwanak_icon('down'); ?></summary>
                <div class="faq-a"><span class="faq-a-mark" aria-hidden="true">A</span><div class="faq-a-con"><?php echo conv_content($v['fa_content'], 1); ?></div></div>
            </details>
        </li>
        <?php } ?>
    </ul>
    <?php } else { ?>
    <p class="bo-empty"><?php echo $stx ? '검색된 질문이 없습니다.' : '등록된 질문이 없습니다.'; ?>
        <?php if ($is_admin) { ?><br><a href="<?php echo G5_ADMIN_URL; ?>/faqmasterlist.php" class="btn_admin">FAQ 관리</a><?php } ?>
    </p>
    <?php } ?>

    <?php echo get_paging($page_rows, $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;page='); ?>

    <?php
    if (!empty($fm['fm_tail_html'])) echo '<div class="faq-html">'.conv_content($fm['fm_tail_html'], 1).'</div>';
    if (!empty($timg_src)) echo '<div class="faq-himg"><img src="'.$timg_src.'" alt=""></div>';
    ?>

    <?php if ($is_admin) { ?><p class="ctt-admin"><a href="<?php echo G5_ADMIN_URL; ?>/faqmasterlist.php" class="btn_admin">FAQ 관리</a></p><?php } ?>
</div>
