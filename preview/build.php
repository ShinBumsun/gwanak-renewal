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
);
$page  = isset($argv[1]) ? $argv[1] : '';
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
    return '#bo_table='.$folder;
}
function html_end() { return ''; }
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
} else {
    // bbs/content.php 와 같은 흐름
    $co_id = $page;
    $g5['title'] = $PAGES[$page];
    $str = str_replace('{THEME_URL}', G5_THEME_URL, file_get_contents(G5_THEME_PATH.'/setup/content/'.$co_id.'.html'));
    include G5_THEME_PATH.'/head.php';
    include G5_THEME_PATH.'/skin/content/basic/content.skin.php';
    include G5_THEME_PATH.'/tail.php';
}
$html = ob_get_clean();
$html = str_replace('</head>', implode("\n", $__css)."\n</head>", $html);
file_put_contents($PREVIEW.'/'.$page.'.html', $html);
echo "preview/{$page}.html 생성 (".strlen($html)." bytes)\n";
