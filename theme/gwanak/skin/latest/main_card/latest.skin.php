<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// $options : 카드 상단에 표시할 이름 (예: 뉴스레터, 관악동영상)
$label    = $options ? $options : $bo_subject;
$is_video = ($bo_table === 'movie');
$row      = $list_count ? $list[0] : null;
$thumb    = $row ? get_list_thumbnail($bo_table, $row['wr_id'], 640, 480, false, true) : array('src' => '');
?>

<article class="card<?php echo $is_video ? ' card--video' : ''; ?>">
    <div class="card-head">
        <p class="card-label"><?php echo gwanak_icon($is_video ? 'video' : 'mail'); ?><?php echo get_text($label); ?></p>
        <a class="btn-plus" href="<?php echo gwanak_url('board', $bo_table); ?>" aria-label="<?php echo get_text($label); ?> 더보기"><?php echo gwanak_icon('plus'); ?></a>
    </div>
    <?php if ($row) { ?>
    <a class="card-body" href="<?php echo $row['href']; ?>">
        <span class="card-thumb">
            <?php if (!empty($thumb['src'])) { ?>
            <img src="<?php echo $thumb['src']; ?>" alt="" loading="lazy" width="640" height="480">
            <?php } else { ?>
            <span class="gal-noimg"><?php echo gwanak_icon($is_video ? 'video' : 'image'); ?></span>
            <?php } ?>
            <?php if ($is_video) { ?><span class="card-play"><?php echo gwanak_icon('play'); ?></span><?php } ?>
        </span>
        <strong class="card-tit"><?php echo $row['subject']; ?></strong>
        <span class="card-more"><?php echo $is_video ? '영상 보기' : '읽어보기'; ?><?php echo gwanak_icon('arrow'); ?></span>
    </a>
    <?php } else { ?>
    <p class="card-empty">등록된 게시물이 없습니다.</p>
    <?php } ?>
</article>
