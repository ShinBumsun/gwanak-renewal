<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
?>

<div class="gal">
    <ul class="gal-track" id="gal_track" tabindex="-1">
        <?php for ($i = 0; $i < $list_count; $i++) {
            $row   = $list[$i];
            $thumb = get_list_thumbnail($bo_table, $row['wr_id'], 600, 420, false, true);
        ?>
        <li class="gal-item">
            <a href="<?php echo $row['href']; ?>">
                <span class="gal-thumb">
                    <?php if (!empty($thumb['src'])) { ?>
                    <img src="<?php echo $thumb['src']; ?>" alt="" loading="lazy" width="600" height="420">
                    <?php } else { ?>
                    <span class="gal-noimg"><?php echo gwanak_icon('image'); ?></span>
                    <?php } ?>
                </span>
                <span class="gal-tit"><?php echo $row['subject']; ?></span>
                <time class="gal-date" datetime="<?php echo gwanak_date($row, 'Y-m-d'); ?>"><?php echo gwanak_date($row); ?></time>
            </a>
        </li>
        <?php } ?>
    </ul>
    <?php if ($list_count == 0) { ?>
    <p class="gal-empty">등록된 사진이 없습니다.</p>
    <?php } ?>
</div>
