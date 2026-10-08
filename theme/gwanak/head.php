<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

run_event('pre_head');

include_once(G5_THEME_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_THEME_PATH.'/inc/theme.lib.php');

$gw_menu = gwanak_menu();
$gw_info = gwanak_info();
?>

<a class="skip" href="#container">본문 바로가기</a>

<!-- 상단 시작 { -->
<header id="hd" class="hd">
    <div class="hd-util">
        <div class="inner">
            <ul class="hd-family">
                <?php foreach (gwanak_family() as $f) { ?>
                <li><a href="<?php echo $f[1]; ?>"><?php echo $f[0]; ?></a></li>
                <?php } ?>
            </ul>
            <div class="hd-tools">
                <div class="fs-ctrl" role="group" aria-label="글자 크기 조절">
                    <span class="fs-label" aria-hidden="true">글자크기</span>
                    <button type="button" data-fs="0" aria-pressed="true">보통</button>
                    <button type="button" data-fs="1" aria-pressed="false">크게</button>
                    <button type="button" data-fs="2" aria-pressed="false">아주 크게</button>
                </div>
                <ul class="hd-member">
                    <?php if ($is_member) { ?>
                    <?php if ($is_admin) { ?><li><a href="<?php echo G5_ADMIN_URL; ?>" class="is-admin">관리자</a></li><?php } ?>
                    <li><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=<?php echo G5_BBS_URL; ?>/register_form.php">정보수정</a></li>
                    <li><a href="<?php echo G5_BBS_URL; ?>/logout.php">로그아웃</a></li>
                    <?php } else { ?>
                    <li><a href="<?php echo G5_BBS_URL; ?>/login.php">로그인</a></li>
                    <li><a href="<?php echo G5_BBS_URL; ?>/register.php">회원가입</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="hd-main">
        <div class="inner">
            <h1 class="hd-logo">
                <a href="<?php echo G5_URL; ?>"><img src="<?php echo G5_THEME_URL; ?>/img/logo.png" width="225" height="45" alt="사회복지법인 한주재단 시립관악노인종합복지관"></a>
            </h1>

            <nav class="gnb" id="gnb" aria-label="주 메뉴">
                <ul class="gnb-list">
                    <?php foreach ($gw_menu as $i => $m) { ?>
                    <li class="gnb-item">
                        <a class="gnb-link" href="<?php echo $m['href']; ?>"<?php echo gwanak_target($m['target']); ?>><?php echo $m['name']; ?></a>
                        <?php if ($m['sub']) { ?>
                        <ul class="gnb-sub">
                            <?php foreach ($m['sub'] as $s) { ?>
                            <li><a href="<?php echo $s['href']; ?>"<?php echo gwanak_target($s['target']); ?>><?php echo $s['name']; ?></a></li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
            </nav>

            <div class="hd-actions">
                <a class="hd-call" href="tel:<?php echo $gw_info['tel_link']; ?>">
                    <?php echo gwanak_icon('phone'); ?>
                    <span><small>대표전화</small><?php echo $gw_info['tel']; ?></span>
                </a>
                <button type="button" class="hd-btn js-search-open" aria-label="통합검색 열기" aria-expanded="false" aria-controls="hd-search"><?php echo gwanak_icon('search'); ?></button>
                <button type="button" class="hd-btn hd-btn--menu js-drawer-open" aria-label="전체메뉴 열기" aria-expanded="false" aria-controls="drawer"><?php echo gwanak_icon('menu'); ?><span class="hd-btn-txt">전체메뉴</span></button>
            </div>
        </div>
        <div class="gnb-bg" aria-hidden="true"></div>
    </div>

    <!-- 통합검색 -->
    <div class="hd-search" id="hd-search" hidden>
        <div class="inner">
            <form name="fsearchbox" method="get" action="<?php echo G5_BBS_URL; ?>/search.php" role="search">
                <input type="hidden" name="sfl" value="wr_subject||wr_content">
                <input type="hidden" name="sop" value="and">
                <label for="sch_stx" class="sound_only">검색어</label>
                <input type="search" name="stx" id="sch_stx" maxlength="20" placeholder="찾으시는 프로그램이나 소식을 입력하세요" required>
                <button type="submit" class="hd-search-submit"><?php echo gwanak_icon('search'); ?><span>검색</span></button>
            </form>
            <p class="hd-search-tags">
                <span>자주 찾는 검색어</span>
                <a href="<?php echo G5_BBS_URL; ?>/search.php?sfl=wr_subject&amp;sop=and&amp;stx=<?php echo urlencode('프로그램'); ?>">프로그램</a>
                <a href="<?php echo G5_BBS_URL; ?>/search.php?sfl=wr_subject&amp;sop=and&amp;stx=<?php echo urlencode('식단'); ?>">식단</a>
                <a href="<?php echo G5_BBS_URL; ?>/search.php?sfl=wr_subject&amp;sop=and&amp;stx=<?php echo urlencode('채용'); ?>">채용</a>
                <a href="<?php echo G5_BBS_URL; ?>/search.php?sfl=wr_subject&amp;sop=and&amp;stx=<?php echo urlencode('일자리'); ?>">일자리</a>
            </p>
            <button type="button" class="hd-search-close js-search-close" aria-label="검색 닫기"><?php echo gwanak_icon('close'); ?></button>
        </div>
    </div>
</header>

<!-- 전체메뉴 (모바일 · 태블릿) -->
<div class="drawer" id="drawer" hidden>
    <div class="drawer-dim js-drawer-close"></div>
    <div class="drawer-panel" role="dialog" aria-modal="true" aria-label="전체메뉴">
        <div class="drawer-head">
            <?php if ($is_member) { ?>
            <p class="drawer-hello"><strong><?php echo get_text($member['mb_nick']); ?></strong>님, 반갑습니다</p>
            <?php } else { ?>
            <p class="drawer-hello">로그인하고 프로그램을 신청하세요</p>
            <?php } ?>
            <button type="button" class="hd-btn js-drawer-close" aria-label="전체메뉴 닫기"><?php echo gwanak_icon('close'); ?></button>
        </div>
        <div class="drawer-member">
            <?php if ($is_member) { ?>
            <a href="<?php echo G5_BBS_URL; ?>/logout.php">로그아웃</a>
            <?php if ($is_admin) { ?><a href="<?php echo G5_ADMIN_URL; ?>">관리자</a><?php } ?>
            <?php } else { ?>
            <a href="<?php echo G5_BBS_URL; ?>/login.php" class="is-primary">로그인</a>
            <a href="<?php echo G5_BBS_URL; ?>/register.php">회원가입</a>
            <?php } ?>
        </div>
        <nav class="drawer-nav" aria-label="전체메뉴">
            <ul>
                <?php foreach ($gw_menu as $i => $m) { ?>
                <li class="drawer-item">
                    <button type="button" class="drawer-tit" aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>" aria-controls="drawer-sub-<?php echo $i; ?>"><?php echo $m['name']; ?><?php echo gwanak_icon('down'); ?></button>
                    <ul class="drawer-sub" id="drawer-sub-<?php echo $i; ?>"<?php echo $i === 0 ? '' : ' hidden'; ?>>
                        <?php foreach ($m['sub'] ? $m['sub'] : array($m) as $s) { ?>
                        <li><a href="<?php echo $s['href']; ?>"<?php echo gwanak_target($s['target']); ?>><?php echo $s['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </nav>
        <a class="drawer-call" href="tel:<?php echo $gw_info['tel_link']; ?>"><?php echo gwanak_icon('phone'); ?> 전화 문의 <?php echo $gw_info['tel']; ?></a>
    </div>
</div>
<!-- } 상단 끝 -->

<?php if (defined('_INDEX_')) { ?>
<main id="container" class="main">
<?php } else {
    $gw_page_title = isset($board['bo_subject']) && $board['bo_subject'] ? $board['bo_subject'] : $g5['title'];
?>
<main id="container" class="sub">
    <div class="sub-head">
        <div class="inner">
            <h2 class="sub-title"><?php echo get_text($gw_page_title); ?></h2>
        </div>
    </div>
    <div class="inner sub-body">
<?php } ?>
