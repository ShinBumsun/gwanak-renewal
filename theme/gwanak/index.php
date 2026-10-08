<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

define('_INDEX_', true);

add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_CSS_URL.'/main.css?ver='.G5_CSS_VER.'">', 1);

include_once(G5_THEME_PATH.'/head.php');

// 팝업레이어 (관리자 > 팝업레이어관리)
if (is_file(G5_BBS_PATH.'/newwin.inc.php')) include_once(G5_BBS_PATH.'/newwin.inc.php');

$gw_status = gwanak_open_status($gw_info['hours']);

// 빠른 메뉴
$gw_quick = array(
    array('프로그램 신청', 'clipboard', gwanak_url('board', 'program')),
    array('프로그램 시간표', 'calendar', gwanak_url('content', 'timetable')),
    array('월별 식단표', 'meal', gwanak_url('board', 'food')),
    array('셔틀버스', 'bus', gwanak_url('content', 'bus')),
    array('시설안내', 'building', gwanak_url('content', 'facility')),
    array('오시는 길', 'map', gwanak_url('content', 'roadmap')),
    array('자원봉사 신청', 'users', gwanak_url('board', 'form_service')),
    array('후원 신청', 'heart', gwanak_url('board', 'form_sponsor')),
);

// 주요 사업
$gw_biz = array(
    array('상담', 'chat', '어르신의 고민을 듣고 필요한 도움을 함께 찾습니다.', 'biz1'),
    array('건강증진', 'pulse', '운동·물리치료·건강관리로 건강한 일상을 지킵니다.', 'biz2'),
    array('노년사회화교육', 'book', '배움과 여가가 있는 평생교육 프로그램을 운영합니다.', 'biz3'),
    array('재가복지', 'home', '거동이 불편한 어르신 댁으로 직접 찾아갑니다.', 'biz4'),
    array('노인맞춤돌봄', 'smile', '홀로 계신 어르신의 안부와 생활을 살핍니다.', 'biz13'),
    array('노인사회활동지원', 'briefcase', '일자리와 사회활동으로 보람 있는 노후를 돕습니다.', 'biz6'),
);

// 복지관 소식 탭
$gw_tabs = array(
    array('notice', '공지사항'),
    array('program', '프로그램 신청'),
    array('recruit', '인재채용'),
    array('data', '자료실'),
);
?>

<!-- 메인 비주얼 + 오늘의 관악 -->
<section class="hero" aria-label="주요 안내">
    <div class="inner hero-grid">
        <div class="hero-visual">
            <?php echo gwanak_latest('main_visual', 'mainvisual', 5, 80); ?>
        </div>

        <aside class="today" aria-labelledby="today_tit">
            <div class="today-head">
                <p class="today-date"><?php echo gwanak_today(); ?></p>
                <h2 class="today-tit" id="today_tit">오늘의 관악</h2>
                <span class="today-status is-<?php echo $gw_status[0]; ?>"><?php echo $gw_status[1]; ?></span>
            </div>
            <ul class="today-list">
                <li>
                    <span class="today-ic"><?php echo gwanak_icon('clock'); ?></span>
                    <div>
                        <p class="today-label">운영시간</p>
                        <p class="today-val"><?php echo $gw_info['hours']; ?></p>
                        <p class="today-sub"><?php echo $gw_info['holiday']; ?></p>
                    </div>
                </li>
                <li>
                    <span class="today-ic is-green"><?php echo gwanak_icon('meal'); ?></span>
                    <div>
                        <p class="today-label">이달의 식단</p>
                        <?php echo gwanak_latest('main_food', 'food', 1, 30); ?>
                    </div>
                </li>
                <li>
                    <span class="today-ic is-navy"><?php echo gwanak_icon('bus'); ?></span>
                    <div>
                        <p class="today-label">셔틀버스</p>
                        <a class="today-link" href="<?php echo gwanak_url('content', 'bus'); ?>">운행 노선·시간 보기<?php echo gwanak_icon('arrow'); ?></a>
                    </div>
                </li>
            </ul>
            <a class="today-call" href="tel:<?php echo $gw_info['tel_link']; ?>">
                <?php echo gwanak_icon('phone'); ?>
                <span><small>궁금한 점은 전화주세요</small><strong><?php echo $gw_info['tel']; ?></strong></span>
            </a>
        </aside>
    </div>
</section>

<!-- 빠른 메뉴 -->
<section class="quick" aria-labelledby="quick_tit">
    <div class="inner">
        <h2 class="sound_only" id="quick_tit">자주 찾는 서비스</h2>
        <ul class="quick-list">
            <?php foreach ($gw_quick as $q) { ?>
            <li>
                <a class="quick-item" href="<?php echo $q[2]; ?>">
                    <span class="quick-ic"><?php echo gwanak_icon($q[1]); ?></span>
                    <span class="quick-txt"><?php echo $q[0]; ?></span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>

<!-- 복지관 소식 -->
<section class="sec news" aria-labelledby="news_tit">
    <div class="inner">
        <div class="sec-head">
            <div>
                <p class="eyebrow">NEWS</p>
                <h2 class="sec-tit" id="news_tit">복지관 소식</h2>
            </div>
        </div>
        <div class="news-grid">
            <div class="tabs" data-tabs>
                <div class="tab-list" role="tablist" aria-label="복지관 소식 분류">
                    <?php foreach ($gw_tabs as $i => $t) { ?>
                    <button type="button" role="tab" id="tab_<?php echo $t[0]; ?>" aria-controls="panel_<?php echo $t[0]; ?>" aria-selected="<?php echo $i ? 'false' : 'true'; ?>" tabindex="<?php echo $i ? '-1' : '0'; ?>"><?php echo $t[1]; ?></button>
                    <?php } ?>
                </div>
                <?php foreach ($gw_tabs as $i => $t) { ?>
                <div class="tab-panel" role="tabpanel" id="panel_<?php echo $t[0]; ?>" aria-labelledby="tab_<?php echo $t[0]; ?>"<?php echo $i ? ' hidden' : ''; ?>>
                    <?php echo gwanak_latest('main_list', $t[0], 5, 60); ?>
                </div>
                <?php } ?>
            </div>

            <div class="news-side">
                <?php echo gwanak_latest('main_card', 'newsletter', 1, 40, '뉴스레터'); ?>
            </div>
        </div>
    </div>
</section>

<!-- 주요 사업 -->
<section class="sec biz" aria-labelledby="biz_tit">
    <div class="inner">
        <div class="sec-head">
            <div>
                <p class="eyebrow">OUR WORK</p>
                <h2 class="sec-tit" id="biz_tit">어르신의 하루에 필요한<br class="m-br"> 모든 것을 함께합니다</h2>
            </div>
            <a class="btn-more" href="<?php echo gwanak_url('content', 'biz1'); ?>">전체 사업 보기<?php echo gwanak_icon('arrow'); ?></a>
        </div>
        <ul class="biz-list">
            <?php foreach ($gw_biz as $b) { ?>
            <li>
                <a class="biz-item" href="<?php echo gwanak_url('content', $b[3]); ?>">
                    <span class="biz-ic"><?php echo gwanak_icon($b[1]); ?></span>
                    <strong class="biz-tit"><?php echo $b[0]; ?></strong>
                    <span class="biz-desc"><?php echo $b[2]; ?></span>
                    <span class="biz-go"><?php echo gwanak_icon('arrow'); ?></span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </div>
</section>

<!-- 관악앨범 -->
<section class="sec gallery" aria-labelledby="gallery_tit">
    <div class="inner">
        <div class="sec-head">
            <div>
                <p class="eyebrow">PHOTO</p>
                <h2 class="sec-tit" id="gallery_tit">관악앨범</h2>
            </div>
            <div class="sec-ctrl">
                <button type="button" class="ctrl-btn js-gal-prev" aria-label="이전 사진" aria-controls="gal_track"><?php echo gwanak_icon('prev'); ?></button>
                <button type="button" class="ctrl-btn js-gal-next" aria-label="다음 사진" aria-controls="gal_track"><?php echo gwanak_icon('next'); ?></button>
                <a class="btn-more" href="<?php echo gwanak_url('board', 'photo'); ?>">더보기<?php echo gwanak_icon('arrow'); ?></a>
            </div>
        </div>
        <?php echo gwanak_latest('main_gallery', 'photo', 10, 40); ?>
    </div>
</section>

<!-- 언론 · 참여방 · 동영상 -->
<section class="sec board3" aria-label="홍보 및 참여">
    <div class="inner board3-grid">
        <div class="board-box">
            <div class="board-box-head">
                <h2 class="board-box-tit"><?php echo gwanak_icon('book'); ?>언론에 비친 관악</h2>
                <a class="btn-plus" href="<?php echo gwanak_url('board', 'news'); ?>" aria-label="언론에 비친 관악 더보기"><?php echo gwanak_icon('plus'); ?></a>
            </div>
            <?php echo gwanak_latest('main_list', 'news', 4, 40, 'nomore'); ?>
        </div>
        <div class="board-box">
            <div class="board-box-head">
                <h2 class="board-box-tit"><?php echo gwanak_icon('chat'); ?>어르신 참여방</h2>
                <a class="btn-plus" href="<?php echo gwanak_url('board', 'participation'); ?>" aria-label="어르신 참여방 더보기"><?php echo gwanak_icon('plus'); ?></a>
            </div>
            <?php echo gwanak_latest('main_list', 'participation', 4, 40, 'nomore'); ?>
        </div>
        <div class="board-box is-video">
            <?php echo gwanak_latest('main_card', 'movie', 1, 40, '관악동영상'); ?>
        </div>
    </div>
</section>

<!-- 자원봉사 · 후원 -->
<section class="cta" aria-label="자원봉사와 후원">
    <div class="inner cta-grid">
        <div class="cta-card is-volunteer">
            <p class="cta-eyebrow">VOLUNTEER</p>
            <h2 class="cta-tit">아름다운 삶,<br>희망과 웃음을 함께 만들어요</h2>
            <p class="cta-txt">어르신 곁에서 나누는 작은 시간이 큰 힘이 됩니다.</p>
            <div class="cta-btns">
                <a class="btn btn--solid" href="<?php echo gwanak_url('board', 'form_service'); ?>">자원봉사 신청</a>
                <a class="btn btn--line" href="<?php echo gwanak_url('content', 'service'); ?>">안내 보기</a>
            </div>
            <span class="cta-ic" aria-hidden="true"><?php echo gwanak_icon('users'); ?></span>
        </div>
        <div class="cta-card is-sponsor">
            <p class="cta-eyebrow">SPONSOR</p>
            <h2 class="cta-tit">어르신의 권익 향상과<br>사회통합을 응원해 주세요</h2>
            <p class="cta-txt">보내주신 후원금은 투명하게 사용하고 보고합니다.</p>
            <div class="cta-btns">
                <a class="btn btn--solid" href="<?php echo gwanak_url('board', 'form_sponsor'); ?>">후원 신청</a>
                <a class="btn btn--line" href="<?php echo gwanak_url('content', 'sponsor'); ?>">안내 보기</a>
            </div>
            <span class="cta-ic" aria-hidden="true"><?php echo gwanak_icon('heart'); ?></span>
        </div>
    </div>
</section>

<?php
include_once(G5_THEME_PATH.'/tail.php');
