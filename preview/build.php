<?php
/*
 * 미리보기 생성기 : 그누보드 없이 테마 index.php 를 샘플 데이터로 실행해 preview/index.html 을 만듭니다.
 * 실행 : php preview/build.php
 * (실제 서비스에는 필요 없는 파일입니다)
 */
error_reporting(E_ALL);

$PAGES = array(
    'index' => '메인', 'greeting' => '인사말', 'mission' => '미션과 비전', 'human' => '인재상', 'ci' => '기관로고',
    'history' => '기관연혁', 'facility' => '시설안내', 'memberinfo' => '조직도·직원소개', 'organization' => '법인소개', 'roadmap' => '오시는 길',
    'guide' => '복지관 이용안내', 'restaurant' => '경로식당 이용안내', 'timetable' => '프로그램 시간표', 'bus' => '셔틀버스 안내',
    'biz1' => '상담', 'biz2' => '건강증진사업', 'biz3' => '노년사회화교육사업', 'biz4' => '재가복지사업', 'biz5' => '지역복지활성화사업',
    'biz6' => '노인사회활동지원사업', 'biz8' => '직원교육사업', 'biz9' => '취업알선사업', 'biz10' => '특화서비스', 'biz12' => '지역복지협동사업',
    'biz13' => '노인맞춤돌봄서비스사업', 'biz14' => '기능회복운영사업', 'service' => '자원봉사 안내', 'sponsor' => '후원 안내',
    'board-notice' => '공지사항', 'board-recruit' => '인재채용', 'board-photo' => '관악앨범', 'board-view' => '공지사항', 'faq' => 'FAQ',
);
$page  = isset($argv[1]) ? $argv[1] : '';
$PAGE_NAME = $page;
if (!isset($PAGES[$page])) {
    foreach ($PAGES as $p => $n) passthru(escapeshellarg(PHP_BINARY).' '.escapeshellarg(__FILE__).' '.$p);
    exit;
}
ini_set('display_errors', '1');
date_default_timezone_set('Asia/Seoul');

$_SERVER['REQUEST_URI'] = '/';
$PREVIEW = __DIR__;
$THEME   = dirname(__DIR__).'/theme/gwanak';

define('_GNUBOARD_', true);
define('G5_URL', 'index.html');
define('G5_BBS_URL', '#bbs');
define('G5_ADMIN_URL', '#adm');
define('G5_ADMIN_DIR', 'adm');
define('G5_JS_URL', '#js');
define('G5_DATA_URL', 'sample');
define('G5_THEME_PATH', $THEME);
define('G5_THEME_URL', '../theme/gwanak');
define('G5_THEME_CSS_URL', '../theme/gwanak/css');
define('G5_LIB_PATH', $PREVIEW.'/mocklib');
define('G5_BBS_PATH', $PREVIEW.'/mocklib');
define('G5_SKIN_DIR', 'skin');
define('G5_CSS_VER', 'preview');
define('G5_JS_VER', 'preview');
define('G5_IS_MOBILE', false);
define('G5_COOKIE_DOMAIN', '');
define('G5_SERVER_TIME', getenv('PREVIEW_TIME') ? strtotime(getenv('PREVIEW_TIME')) : time());

$config = array('cf_title' => '관악노인종합복지관', 'cf_add_meta' => '', 'cf_add_script' => '', 'cf_editor' => '');
$g5 = array();
$is_member = $is_admin = '';
$member = array('mb_nick' => '');

$__css = $__js = array();
function add_stylesheet($s, $o = 0) { global $__css; $__css[] = $s; }
function add_javascript($s, $o = 0) { /* 그누보드 코어 JS 는 미리보기에서 생략 */ }
function run_event() {}
function run_replace($tag, $v) { return $v; }
function get_microtime() { return microtime(true); }
function clean_xss_tags($s) { return $s; }
function get_text($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function cut_str($s, $len, $suffix = '…') { return mb_strlen($s) > $len ? mb_substr($s, 0, $len).$suffix : $s; }
function get_pretty_url($folder, $no = '') {
    global $PAGES;
    if ($folder === 'content') return isset($PAGES[$no]) ? $no.'.html?co_id='.$no : '#co_id='.$no;
    if (isset($PAGES['board-'.$folder])) return 'board-'.$folder.'.html?bo_table='.$folder;
    return '#bo_table='.$folder;
}
function html_end() { return ''; }
function get_selected($a, $b) { return $a == $b ? ' selected="selected"' : ''; }
function get_view_thumbnail($c) { return $c; }
function conv_content($c) { return $c; }
function get_paging($rows, $cur, $total, $url) {
    $h = '<nav class="pg_wrap"><span class="pg">';
    for ($i = 1; $i <= $total; $i++) $h .= $i == $cur ? '<strong class="pg_current">'.$i.'</strong>' : '<a href="#" class="pg_page">'.$i.'</a>';
    return $h.'<a href="#" class="pg_page pg_next">다음</a><a href="#" class="pg_page pg_end">맨끝</a></span></nav>';
}
function get_file($bo_table, $wr_id) {
    global $FIX_FILES;
    $f = array('count' => 0);
    if (isset($FIX_FILES[$bo_table][$wr_id])) {
        foreach ($FIX_FILES[$bo_table][$wr_id] as $i => $name) $f[$i] = array('path' => 'sample', 'file' => $name);
        $f['count'] = count($FIX_FILES[$bo_table][$wr_id]);
    }
    return $f;
}
function get_list_thumbnail($bo_table, $wr_id, $w, $h) {
    global $FIX_THUMBS;
    return array('src' => isset($FIX_THUMBS[$bo_table][$wr_id]) ? 'sample/'.$FIX_THUMBS[$bo_table][$wr_id] : '');
}

// ---------- 샘플 데이터 (현 사이트 게시물 제목 기준) ----------
$now = G5_SERVER_TIME;
function d($days) { global $now; return date('Y-m-d H:i:s', $now - $days * 86400); }

$FIX = array(
    'mainvisual' => array(
        array('wr_subject' => '어르신의 삶에 / 복지를 더하겠습니다', 'wr_content' => '세대가 공존하는 지역사회, 관악노인종합복지관이 어르신의 건강하고 활기찬 하루를 함께합니다.', 'wr_link1' => '#content/greeting', 'ca_name' => '텍스트 표시'),
        array('wr_subject' => '2026년 하반기 / 노년사회화교육 수강생 모집', 'wr_content' => '스마트폰·건강체조·서예·합창 등 다양한 평생교육 프로그램을 만나보세요.', 'wr_link1' => '#program', 'ca_name' => '텍스트 표시'),
        array('wr_subject' => '관악노인종합복지관 메인 배너', 'wr_content' => '', 'wr_link1' => '', 'ca_name' => '이미지만'),
    ),
    'food' => array(array('wr_subject' => '2026년 10월 식단표')),
    'notice' => array(
        array('wr_subject' => '[강사모집공고] 노년사회화교육 프로그램 강사 모집', 'days' => 0.5, 'is_notice' => 1),
        array('wr_subject' => '[노년사회화교육사업] 2026년 하반기 프로그램 수강생 추가 모집 안내', 'days' => 3),
        array('wr_subject' => '[노년사회화교육] 방학 및 프로그램실 이용 안내', 'days' => 9),
        array('wr_subject' => '[노년사회화교육] 2026년 하반기 프로그램 안내', 'days' => 14),
        array('wr_subject' => '2026 하계 사회복지 현장실습 1차 서류 합격자 안내', 'days' => 20),
    ),
    'program' => array(
        array('wr_subject' => '스마트폰 활용 교실 (초급) 수강신청', 'ca_name' => '접수중', 'days' => 1),
        array('wr_subject' => '건강 체조 · 라인댄스 반 수강신청', 'ca_name' => '접수중', 'days' => 2),
        array('wr_subject' => '서예 · 캘리그라피 반 수강신청', 'ca_name' => '접수마감', 'days' => 6),
    ),
    'recruit' => array(
        array('wr_subject' => '[합격자 공고] 노인맞춤돌봄서비스 생활지원사 최종 합격자 공고', 'days' => 2, 'ca_name' => '합격자공고'),
        array('wr_subject' => '[1차 합격자 공고] 노인맞춤돌봄서비스 생활지원사 서류전형', 'days' => 5, 'ca_name' => '합격자공고'),
        array('wr_subject' => '[채용공고] 노인맞춤돌봄서비스 생활지원사 채용', 'days' => 18, 'ca_name' => '채용공고'),
        array('wr_subject' => '[긴급채용공고] 환경미화원 기간제근로자 채용', 'days' => 25, 'ca_name' => '채용공고'),
    ),
    'data' => array(
        array('wr_subject' => '[관악 이음] 10월호', 'days' => 1),
        array('wr_subject' => '[관악 이음] 9월호', 'days' => 35),
        array('wr_subject' => '[지역복지협동][서울AI재단] 말하면 들어주는 AI 안내 자료', 'days' => 50),
    ),
    'newsletter' => array(array('wr_subject' => '[관악 이음] 10월호 - 제3회 사랑해요, 관악노인 어르신 축제 이야기', 'days' => 1)),
    'photo' => array(
        array('wr_subject' => '[관악노인종합복지관] 제3회 사랑해요, 관악노인 어르신 축제', 'days' => 2),
        array('wr_subject' => '[재가복지사업] 사단법인 정해복지 추석 명절 나눔', 'days' => 6),
        array('wr_subject' => '[노인맞춤돌봄서비스&재가복지사업] 서울시 어르신 나들이', 'days' => 9),
        array('wr_subject' => '[지역복지활성화사업] 2026년 서울시 우리동네 나눔', 'days' => 12),
        array('wr_subject' => '[노년사회화교육] 2026년 하반기 노년사회화교육 개강식', 'days' => 15),
        array('wr_subject' => '[지역복지활성화사업] 2026년 서울시 이웃사랑 캠페인', 'days' => 20),
        array('wr_subject' => '[자원봉사사업] 관악애발견 & 문영여고 RCY 봉사활동', 'days' => 24),
    ),
    'news' => array(
        array('wr_subject' => '[국제뉴스] 제3회 사랑해요 관악노인 어르신 축제 성료', 'days' => 2),
        array('wr_subject' => '[국제뉴스] 관악노인종합복지관, 어르신 문화행사 개최', 'days' => 2),
        array('wr_subject' => '[웹이코노미] 새마을금고중앙회 MG 사회공헌 삼계탕 나눔', 'days' => 79),
        array('wr_subject' => '[아시아에이] "뜨끈한 삼계탕 드세요" 복날 나눔 행사', 'days' => 79),
    ),
    'participation' => array(
        array('wr_subject' => '4구 당구장용 큐대를 개인 전용으로 쓰고 싶습니다', 'days' => 3, 'wr_comment' => 1),
        array('wr_subject' => '당구장 토요일에도 표를 뽑아서 이용할 수 있을까요', 'days' => 4, 'wr_comment' => 1),
        array('wr_subject' => '탁구 사구 사용자 확대를 위해 건의드립니다', 'days' => 14, 'wr_comment' => 1),
        array('wr_subject' => '컴퓨터&코딩 프로그램 재능기부로 함께하고 싶어요', 'days' => 45, 'wr_comment' => 1),
    ),
    'movie' => array(array('wr_subject' => '[관악동영상] 제3회 사랑해요, 관악노인 어르신 축제 현장 스케치', 'days' => 3)),
);
$FIX_FILES  = array('mainvisual' => array(1 => array('hero1.jpg'), 2 => array('hero2.jpg'), 3 => array('visual1.jpg')));
$FIX_THUMBS = array('newsletter' => array(1 => 'g2.jpg'), 'movie' => array(1 => 'g1.jpg'));
for ($i = 1; $i <= 7; $i++) $FIX_THUMBS['photo'][$i] = 'g'.$i.'.jpg';

$BOARD_NAMES = array('notice' => '공지사항', 'program' => '프로그램 강좌신청', 'recruit' => '인재채용', 'data' => '자료실');

function latest($skin_dir, $bo_table, $rows = 10, $subject_len = 40, $cache_time = 1, $options = '')
{
    global $FIX, $BOARD_NAMES;
    if (!isset($FIX[$bo_table])) return '';
    $board = array('bo_table' => $bo_table, 'bo_new' => 72);
    $bo_subject = isset($BOARD_NAMES[$bo_table]) ? $BOARD_NAMES[$bo_table] : '';
    $list = array();
    foreach (array_slice($FIX[$bo_table], 0, $rows) as $i => $r) {
        $r += array('wr_content' => '', 'wr_link1' => '', 'ca_name' => '', 'days' => 1, 'wr_comment' => 0, 'is_notice' => 0);
        $r['wr_id'] = $i + 1;
        $r['wr_datetime'] = d($r['days']);
        $r['subject'] = get_text(cut_str($r['wr_subject'], $subject_len));
        $r['href'] = '#'.$bo_table.'/'.$r['wr_id'];
        $r['link'] = array(1 => $r['wr_link1']);
        $list[] = $r;
    }
    $list_count = count($list);
    $latest_skin_url = '';
    ob_start();
    include G5_THEME_PATH.'/skin/latest/'.preg_replace('#^theme/#', '', $skin_dir).'/latest.skin.php';
    return ob_get_clean();
}

ob_start();
if ($page === 'index') {
    include G5_THEME_PATH.'/index.php';
} else if (strpos($page, 'board-') === 0 || $page === 'faq') {
    // 게시판 · FAQ 미리보기
    $kind = $page === 'faq' ? 'faq' : substr($page, 6);
    $bo_table = $kind === 'view' ? 'notice' : ($kind === 'faq' ? '' : $kind);
    if ($kind === 'faq') $_SERVER['SCRIPT_NAME'] = 'faq.php';
    $is_gallery = ($bo_table === 'photo');
    $board = array('bo_table' => $bo_table, 'bo_subject' => $PAGES[$page], 'bo_use_comment' => 0, 'bo_download_point' => 0);
    $g5['title'] = $PAGES[$page];
    $sfl = 'wr_subject'; $stx = $sca = $spt = $sst = $sod = ''; $page_no = 1; $wr_id = 0; $qstr = '';
    $is_checkbox = false; $admin_href = $rss_href = ''; $write_href = '#write'; $list_href = '';
    $is_category = ($bo_table === 'recruit');
    $category_option = '<li><a href="#" id="bo_cate_on">전체</a></li><li><a href="#">채용공고</a></li><li><a href="#">합격자공고</a></li>';
    $write_pages = get_paging(15, 1, 5, '#');
    $board_skin_url = G5_THEME_URL.'/skin/board/'.($is_gallery ? 'gallery' : 'basic');
    $list = array(); $src = isset($FIX[$bo_table]) ? $FIX[$bo_table] : $FIX['notice'];
    $n = 128;
    foreach ($src as $i => $r) {
        $r += array('days' => 1, 'ca_name' => '', 'wr_comment' => 0, 'is_notice' => 0);
        $list[] = array('wr_id' => $i + 1, 'num' => $n--, 'is_notice' => $r['is_notice'], 'subject' => get_text($r['wr_subject']), 'href' => 'board-view.html',
            'wr_datetime' => d($r['days']), 'name' => '<span class="sv_member">관리자</span>', 'wr_hit' => 120 + $i * 37, 'ca_name' => $r['ca_name'], 'ca_name_href' => '#',
            'reply' => '', 'wr_reply' => '', 'icon_new' => $r['days'] < 3, 'icon_file' => $i % 2 ? '1' : '', 'comment_cnt' => $r['wr_comment'], 'wr_comment' => $r['wr_comment']);
    }
    $total_count = 128; $page = 1;
    include G5_THEME_PATH.'/head.php';
    if ($kind === 'faq') {
        $faq_skin_url = G5_THEME_URL.'/skin/faq/basic';
        $fm_id = 1; $fm = array(); $page_rows = 10; $total_page = 1; $category_href = '#';
        $faq_master_list = array(array('fm_id' => 1, 'fm_subject' => '복지관 이용'), array('fm_id' => 2, 'fm_subject' => '회원등록 및 회원증'));
        $faq_list = array(
            array('fa_subject' => '식권은 어디서 발급 받나요?', 'fa_content' => '<p>안내데스크에서 발급 받을 수 있습니다.</p>'),
            array('fa_subject' => '프로그램 이용 및 식사는 언제부터 가능한가요?', 'fa_content' => '<p>회원증 수령 이후 가능합니다.</p><p>복지관 내 모든 프로그램 이용은 전산에 회원 등록된 후 가능합니다. 비회원일 경우 견학만 가능하며, 이용할 수 없습니다.</p>'),
            array('fa_subject' => '참여자의 윤리, 권리와 존중, 학대금지 조항 안내', 'fa_content' => '<p>본 복지관 운영규정 제4장 참여자의 윤리, 제21조 참여자의 권리와 존중 / 제22조 참여자의 학대금지 조항에 대한 안내입니다.</p>'),
        );
        include G5_THEME_PATH.'/skin/faq/basic/list.skin.php';
    } else if ($kind === 'view') {
        $view = array('wr_subject' => $FIX['notice'][1]['wr_subject'], 'ca_name' => '', 'name' => '<span class="sv_member">관리자</span>', 'wr_datetime' => d(3), 'wr_hit' => 342, 'wr_comment' => 0,
            'content' => '<p>안녕하세요. 관악노인종합복지관입니다.</p><p>2026년 하반기 노년사회화교육 프로그램 수강생을 추가 모집합니다. 관심 있는 어르신들의 많은 참여 바랍니다.</p><p><strong>접수기간</strong> : 10월 13일(월) ~ 10월 17일(금)<br><strong>접수장소</strong> : 2층 사무실 (노년사회화교육팀)<br><strong>문의</strong> : 02-888-6145</p>',
            'file' => array('count' => 1, 0 => array('source' => '2026_하반기_추가모집_안내.hwp', 'href' => '#', 'size' => '84.0K', 'download' => 12, 'view' => '')), 'link' => array(1 => '', 2 => ''));
        $category_name = false; $is_ip_view = false; $is_signature = false; $good_href = $nogood_href = '';
        $update_href = $delete_href = $copy_href = $move_href = $scrap_href = $reply_href = ''; $list_href = 'board-notice.html';
        $prev_href = '#'; $prev_wr_subject = get_text($FIX['notice'][2]['wr_subject']); $prev_wr_date = d(9);
        $next_href = '#'; $next_wr_subject = get_text($FIX['notice'][0]['wr_subject']); $next_wr_date = d(0.5);
        define('G5_BBS_PATH_VIEW', 1);
        $board_skin_path = G5_THEME_PATH.'/skin/board/basic';
        include G5_THEME_PATH.'/skin/board/basic/view.skin.php';
    } else {
        include G5_THEME_PATH.'/skin/board/'.($is_gallery ? 'gallery' : 'basic').'/list.skin.php';
    }
    include G5_THEME_PATH.'/tail.php';
} else {
    // bbs/content.php 와 같은 흐름
    $co_id = $page;
    $g5['title'] = $PAGES[$page];
    $str = str_replace(array('{THEME_URL}', '{BBS_URL}'), array(G5_THEME_URL, G5_BBS_URL), file_get_contents(G5_THEME_PATH.'/setup/content/'.$co_id.'.html'));
    include G5_THEME_PATH.'/head.php';
    include G5_THEME_PATH.'/skin/content/basic/content.skin.php';
    include G5_THEME_PATH.'/tail.php';
}
$html = ob_get_clean();
$html = str_replace('</head>', implode("\n", $__css)."\n</head>", $html);
file_put_contents($PREVIEW.'/'.$PAGE_NAME.'.html', $html);
echo "preview/{$PAGE_NAME}.html 생성 (".strlen($html)." bytes)\n";
