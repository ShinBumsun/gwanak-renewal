<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// $options : 'nomore' 이면 하단 더보기 링크 생략
$show_more = ($options !== 'nomore');
?>

<ul class="post-list">
    <?php for ($i = 0; $i < $list_count; $i++) {
        $row = $list[$i];
    ?>
    <li>
        <a href="<?php echo $row['href']; ?>" class="post-item">
            <span class="post-tit">
                <?php if (!empty($row['is_notice'])) { ?><em class="tag tag--notice">공지</em><?php } ?>
                <?php if (!empty($row['ca_name'])) { ?><em class="tag"><?php echo get_text($row['ca_name']); ?></em><?php } ?>
                <span class="post-subject"><?php echo $row['subject']; ?></span>
                <?php if (gwanak_is_new($row, $board)) { ?><i class="new" aria-label="새 글">N</i><?php } ?>
                <?php if (!empty($row['wr_comment'])) { ?><span class="post-cmt" aria-label="댓글 <?php echo (int) $row['wr_comment']; ?>개">[<?php echo (int) $row['wr_comment']; ?>]</span><?php } ?>
            </span>
            <time class="post-date" datetime="<?php echo gwanak_date($row, 'Y-m-d'); ?>"><?php echo gwanak_date($row); ?></time>
        </a>
    </li>
    <?php } ?>
    <?php if ($list_count == 0) { ?>
    <li class="post-empty">등록된 게시물이 없습니다.</li>
    <?php } ?>
</ul>
<?php if ($show_more) { ?>
<a class="post-more" href="<?php echo gwanak_url('board', $bo_table); ?>"><?php echo $bo_subject ? $bo_subject : '게시판'; ?> 전체보기<?php echo gwanak_icon('arrow'); ?></a>
<?php } ?>
