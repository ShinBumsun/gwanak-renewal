<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 오늘의 관악 > 이달의 식단 (게시판 : food)
?>
<?php if ($list_count) { ?>
<p class="today-val"><?php echo $list[0]['subject']; ?></p>
<a class="today-link" href="<?php echo $list[0]['href']; ?>">식단표 보기<?php echo gwanak_icon('arrow'); ?></a>
<?php } else { ?>
<p class="today-val">이번 달 경로식당 식단</p>
<a class="today-link" href="<?php echo gwanak_url('board', $bo_table); ?>">식단표 보기<?php echo gwanak_icon('arrow'); ?></a>
<?php } ?>
