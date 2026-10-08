<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 내용관리 페이지 스킨 (제목은 서브 비주얼에 표시)
?>

<article id="ctt" class="ctt ctt_<?php echo $co_id; ?>">
    <h3 class="sound_only"><?php echo $g5['title']; ?> 본문</h3>

    <div id="ctt_con" class="ctt-con">
        <?php echo $str; ?>
    </div>

    <?php if ($is_admin) { ?>
    <div class="ctt-admin">
        <a href="<?php echo G5_ADMIN_URL; ?>/contentform.php?w=u&amp;co_id=<?php echo $co_id; ?>" class="btn_admin">이 페이지 내용 수정</a>
    </div>
    <?php } ?>
</article>
