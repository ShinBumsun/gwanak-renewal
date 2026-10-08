<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

/*
 * 메인 비주얼 (게시판 : mainvisual)
 *  - 제목      : 큰 문구 ( " / " 를 넣으면 줄바꿈 )
 *  - 내용      : 작은 설명 문구
 *  - 링크 #1   : '자세히 보기' 버튼 주소
 *  - 첨부파일1 : PC 이미지 (권장 1600×1000)
 *  - 첨부파일2 : 모바일 이미지 (선택, 권장 900×1000)
 *  - 분류      : '이미지만' 선택 시 문구 없이 이미지 전체를 노출 (글자가 들어간 배너용)
 */

$slides = array();
for ($i = 0; $i < $list_count; $i++) {
    $row = $list[$i];
    $pc = $mo = '';

    if (function_exists('get_file')) {
        $files = get_file($bo_table, $row['wr_id']);
        for ($j = 0; $j < (int) $files['count']; $j++) {
            if (empty($files[$j]['file']) || !preg_match('/\.(jpe?g|png|gif|webp)$/i', $files[$j]['file'])) continue;
            $src = $files[$j]['path'].'/'.$files[$j]['file'];
            if (!$pc) $pc = $src;
            else if (!$mo) { $mo = $src; break; }
        }
    }
    if (!$pc) {
        $thumb = get_list_thumbnail($bo_table, $row['wr_id'], 1600, 1000, false, true);
        $pc = $thumb['src'];
    }

    $desc = html_entity_decode(strip_tags(preg_replace('#<br\s*/?>|</p>#i', ' ', $row['wr_content'])), ENT_QUOTES, 'UTF-8');
    $desc = trim(preg_replace('/\s+/u', ' ', $desc));

    $slides[] = array(
        'title'   => str_replace(' / ', '<br>', get_text($row['wr_subject'])),
        'alt'     => get_text(str_replace(' / ', ' ', $row['wr_subject'])),
        'desc'    => get_text(cut_str($desc, 90)),
        'link'    => !empty($row['link'][1]) ? $row['link'][1] : '',
        'blank'   => !empty($row['wr_link1']) && preg_match('#^https?://#i', $row['wr_link1']) && strpos($row['wr_link1'], G5_URL) !== 0,
        'pc'      => $pc,
        'mo'      => $mo,
        'imgonly' => (isset($row['ca_name']) && $row['ca_name'] === '이미지만' && $pc),
    );
}

// 등록된 비주얼이 없을 때 기본 문구
if (!$slides) {
    $slides[] = array(
        'title' => '어르신의 삶에<br>복지를 더하겠습니다',
        'alt'   => '어르신의 삶에 복지를 더하겠습니다',
        'desc'  => '세대가 공존하는 지역사회, 관악노인종합복지관이 어르신의 건강하고 활기찬 하루를 함께합니다.',
        'link'  => gwanak_url('content', 'greeting'),
        'blank' => false, 'pc' => '', 'mo' => '', 'imgonly' => false,
    );
}
$total = count($slides);
?>

<div class="vis" data-slider<?php echo $total > 1 ? ' data-autoplay="6000"' : ''; ?> aria-roledescription="carousel" aria-label="주요 소식">
    <div class="vis-track" aria-live="off">
        <?php foreach ($slides as $k => $s) {
            $target = $s['blank'] ? ' target="_blank" rel="noopener"' : '';
        ?>
        <div class="vis-slide<?php echo $k === 0 ? ' is-active' : ''; ?><?php echo $s['imgonly'] ? ' is-imgonly' : ''; ?><?php echo !$s['pc'] ? ' is-noimg' : ''; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo ($k + 1).' / '.$total; ?>"<?php echo $k === 0 ? '' : ' aria-hidden="true"'; ?>>
            <?php if ($s['imgonly']) { ?>
                <?php if ($s['link']) { ?><a class="vis-full" href="<?php echo $s['link']; ?>"<?php echo $target; ?>><?php } ?>
                <picture class="vis-img">
                    <?php if ($s['mo']) { ?><source media="(max-width: 767px)" srcset="<?php echo $s['mo']; ?>"><?php } ?>
                    <img src="<?php echo $s['pc']; ?>" alt="<?php echo $s['alt']; ?>"<?php echo $k ? ' loading="lazy"' : ' fetchpriority="high"'; ?>>
                </picture>
                <?php if ($s['link']) { ?></a><?php } ?>
            <?php } else { ?>
                <div class="vis-text">
                    <p class="vis-eyebrow">관악노인종합복지관</p>
                    <h2 class="vis-tit"><?php echo $s['title']; ?></h2>
                    <?php if ($s['desc']) { ?><p class="vis-desc"><?php echo $s['desc']; ?></p><?php } ?>
                    <?php if ($s['link']) { ?><a class="vis-btn" href="<?php echo $s['link']; ?>"<?php echo $target; ?>>자세히 보기<?php echo gwanak_icon('arrow'); ?></a><?php } ?>
                </div>
                <?php if ($s['pc']) { ?>
                <picture class="vis-img">
                    <?php if ($s['mo']) { ?><source media="(max-width: 767px)" srcset="<?php echo $s['mo']; ?>"><?php } ?>
                    <img src="<?php echo $s['pc']; ?>" alt=""<?php echo $k ? ' loading="lazy"' : ' fetchpriority="high"'; ?>>
                </picture>
                <?php } else { ?>
                <div class="vis-deco" aria-hidden="true"><span></span><span></span><span></span></div>
                <?php } ?>
            <?php } ?>
        </div>
        <?php } ?>
    </div>

    <?php if ($total > 1) { ?>
    <div class="vis-ctrl">
        <button type="button" class="vis-btn-ctrl js-vis-prev" aria-label="이전 슬라이드"><?php echo gwanak_icon('prev'); ?></button>
        <p class="vis-count"><b class="js-vis-cur">01</b><span>/</span><?php echo sprintf('%02d', $total); ?></p>
        <button type="button" class="vis-btn-ctrl js-vis-next" aria-label="다음 슬라이드"><?php echo gwanak_icon('next'); ?></button>
        <button type="button" class="vis-btn-ctrl js-vis-toggle" aria-label="자동 넘김 멈춤"><span class="is-pause"><?php echo gwanak_icon('pause'); ?></span><span class="is-play"><?php echo gwanak_icon('play'); ?></span></button>
    </div>
    <div class="vis-progress" aria-hidden="true"><span></span></div>
    <?php } ?>
</div>
