<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/* ==========================================================
   관악노인종합복지관 테마 공통 함수
   ========================================================== */

// 운영 요일 (0=일 ~ 6=토)
if (!defined('GWANAK_OPEN_DAYS')) define('GWANAK_OPEN_DAYS', '1,2,3,4,5');

// 게시판/내용/일반 링크 생성
function gwanak_url($type, $id = '')
{
    if ($type === 'board') {
        return function_exists('get_pretty_url') ? get_pretty_url($id) : G5_BBS_URL.'/board.php?bo_table='.$id;
    }
    if ($type === 'content') {
        return function_exists('get_pretty_url') ? get_pretty_url('content', $id) : G5_BBS_URL.'/content.php?co_id='.$id;
    }
    if ($type === 'faq') return G5_BBS_URL.'/faq.php';
    return $id;
}

// 기본 메뉴 구조 (관리자 > 메뉴설정이 비어 있을 때 사용, 설치 스크립트에서도 사용)
function gwanak_default_menu()
{
    return array(
        array('복지관소개', 'content', 'greeting', array(
            array('인사말', 'content', 'greeting'),
            array('미션과 비전', 'content', 'mission'),
            array('인재상', 'content', 'human'),
            array('기관로고', 'content', 'ci'),
            array('기관연혁', 'content', 'history'),
            array('시설안내', 'content', 'facility'),
            array('조직도·직원소개', 'content', 'memberinfo'),
            array('법인소개', 'content', 'organization'),
            array('오시는 길', 'content', 'roadmap'),
        )),
        array('이용안내', 'content', 'guide', array(
            array('복지관 이용안내', 'content', 'guide'),
            array('경로식당 이용안내', 'content', 'restaurant'),
            array('월별 식단표', 'board', 'food'),
            array('프로그램 시간표', 'content', 'timetable'),
            array('셔틀버스 안내', 'content', 'bus'),
        )),
        array('사업소개', 'content', 'biz1', array(
            array('상담', 'content', 'biz1'),
            array('건강증진사업', 'content', 'biz2'),
            array('노년사회화교육사업', 'content', 'biz3'),
            array('재가복지사업', 'content', 'biz4'),
            array('지역복지활성화사업', 'content', 'biz5'),
            array('노인사회활동지원사업', 'content', 'biz6'),
            array('직원교육사업', 'content', 'biz8'),
            array('취업알선사업', 'content', 'biz9'),
            array('특화서비스', 'content', 'biz10'),
            array('지역복지협동사업', 'content', 'biz12'),
            array('노인맞춤돌봄서비스사업', 'content', 'biz13'),
            array('기능회복운영사업', 'content', 'biz14'),
        )),
        array('후원·자원봉사', 'content', 'service', array(
            array('자원봉사 안내', 'content', 'service'),
            array('자원봉사활동 현황', 'content', 'service_status'),
            array('자원봉사 신청', 'board', 'form_service'),
            array('후원 안내', 'content', 'sponsor'),
            array('후원 신청', 'board', 'form_sponsor'),
        )),
        array('복지관 소식', 'board', 'notice', array(
            array('공지사항', 'board', 'notice'),
            array('인재채용', 'board', 'recruit'),
            array('재정보고', 'board', 'report'),
            array('온라인강의', 'board', 'study'),
            array('자료실', 'board', 'data'),
            array('FAQ', 'faq', ''),
            array('어르신 참여방', 'board', 'participation'),
            array('프로그램 강좌신청', 'board', 'program'),
        )),
        array('관악홍보실', 'board', 'photo', array(
            array('관악앨범', 'board', 'photo'),
            array('관악동영상', 'board', 'movie'),
            array('언론에 비친 관악', 'board', 'news'),
            array('뉴스레터', 'board', 'newsletter'),
        )),
    );
}

// 헤더용 메뉴 : 관리자 메뉴설정(DB) 우선, 없으면 기본 메뉴
function gwanak_menu()
{
    $menu = array();

    if (function_exists('get_menu_db')) {
        $rows = get_menu_db(0, true);
        foreach ((array) $rows as $row) {
            if (empty($row) || empty($row['me_name'])) continue;
            $item = array(
                'name'   => $row['me_name'],
                'href'   => $row['me_link'],
                'target' => $row['me_target'],
                'sub'    => array(),
            );
            if (!empty($row['sub'])) {
                foreach ((array) $row['sub'] as $row2) {
                    if (empty($row2) || empty($row2['me_name'])) continue;
                    $item['sub'][] = array('name' => $row2['me_name'], 'href' => $row2['me_link'], 'target' => $row2['me_target']);
                }
            }
            $menu[] = $item;
        }
    }

    if (!$menu) {
        foreach (gwanak_default_menu() as $m) {
            $item = array('name' => $m[0], 'href' => gwanak_url($m[1], $m[2]), 'target' => 'self', 'sub' => array());
            foreach ($m[3] as $s) {
                $item['sub'][] = array('name' => $s[0], 'href' => gwanak_url($s[1], $s[2]), 'target' => 'self');
            }
            $menu[] = $item;
        }
    }

    return $menu;
}

// 메인 최신글 : 게시판이 아직 없거나 글이 없을 때도 스킨의 빈 화면을 출력
function gwanak_latest($skin, $bo_table, $rows, $subject_len, $options = '')
{
    $html = function_exists('latest') ? latest('theme/'.$skin, $bo_table, $rows, $subject_len, 1, $options) : '';

    if (trim($html) === '') {
        $list = array();
        $list_count = 0;
        $bo_subject = '';
        $board = array('bo_table' => $bo_table, 'bo_new' => 24);
        $latest_skin_url = G5_THEME_URL.'/'.G5_SKIN_DIR.'/latest/'.$skin;
        ob_start();
        include G5_THEME_PATH.'/'.G5_SKIN_DIR.'/latest/'.$skin.'/latest.skin.php';
        $html = ob_get_clean();
    }

    return $html;
}

// 최신글 공통 : 새 글 여부 / 날짜
function gwanak_is_new($row, $board)
{
    $hours = !empty($board['bo_new']) ? (int) $board['bo_new'] : 24;
    $now   = defined('G5_SERVER_TIME') ? G5_SERVER_TIME : time();
    return ($now - strtotime($row['wr_datetime'])) < $hours * 3600;
}

function gwanak_date($row, $format = 'Y.m.d')
{
    return date($format, strtotime($row['wr_datetime']));
}

// 관련 기관 (상단 유틸바 · 푸터)
function gwanak_family()
{
    return array(
        array('사회복지법인 한주재단', gwanak_url('content', 'organization')),
        array('관악데이케어센터', 'https://noinjigi.org/daycare'),
        array('관악치매전문요양센터', 'https://noinjigi.org/hospital'),
    );
}

// 메뉴 링크 → 비교용 키 (b:게시판, c:내용, f:faq)
function gwanak_link_key($href)
{
    if (preg_match('/[?&]co_id=([a-z0-9_]+)/i', $href, $m)) return 'c:'.$m[1];
    if (preg_match('/[?&]bo_table=([a-z0-9_]+)/i', $href, $m)) return 'b:'.$m[1];
    if (strpos($href, 'faq.php') !== false) return 'f:faq';
    $path = rtrim(preg_replace('#^'.preg_quote(G5_URL, '#').'#', '', strtok($href, '?#')), '/');
    if (preg_match('#^/content/([a-z0-9_]+)$#i', $path, $m)) return 'c:'.$m[1];   // 짧은 주소 : 내용
    if (preg_match('#^/([a-z0-9_]+)(/[0-9]+)?$#i', $path, $m)) return 'b:'.$m[1]; // 짧은 주소 : 게시판
    return '';
}

// 현재 페이지가 속한 메뉴 찾기 : array(대메뉴 index, 소메뉴 index) 또는 null
function gwanak_current($menu)
{
    global $bo_table, $co_id;

    if (!empty($bo_table)) $key = 'b:'.$bo_table;
    else if (!empty($co_id)) $key = 'c:'.$co_id;
    else if (isset($_SERVER['SCRIPT_NAME']) && basename($_SERVER['SCRIPT_NAME']) === 'faq.php') $key = 'f:faq';
    else return null;

    foreach ($menu as $i => $m) {
        foreach ($m['sub'] as $j => $s) {
            if (gwanak_link_key($s['href']) === $key) return array($i, $j);
        }
        if (gwanak_link_key($m['href']) === $key) return array($i, -1);
    }
    return null;
}

// 대메뉴별 서브 비주얼 문구
function gwanak_slogan($name)
{
    $s = array(
        '복지관소개' => '어르신의 주체적인 삶을 위해 함께 동행하겠습니다.',
    );
    return isset($s[$name]) ? $s[$name] : '';
}

function gwanak_target($target)
{
    return ($target && $target !== 'self') ? ' target="_'.$target.'" rel="noopener"' : '';
}

// 기관 정보 : 관리자 > 환경설정 > 기본환경설정 > 여분필드(1~6)에서 수정
function gwanak_info()
{
    global $config;

    $d = array(
        'name'    => '관악노인종합복지관',
        'tel'     => '02-888-6144~5',
        'fax'     => '02-888-8026',
        'addr'    => '(08708) 서울특별시 관악구 보라매로 35',
        'email'   => 'nambunoin@hanmail.net',
        'hours'   => '평일 09:00 ~ 18:00',
        'holiday' => '토·일요일 및 공휴일 휴관',
    );
    $map = array('cf_1' => 'tel', 'cf_2' => 'fax', 'cf_3' => 'addr', 'cf_4' => 'email', 'cf_5' => 'hours', 'cf_6' => 'holiday');
    foreach ($map as $k => $v) {
        if (!empty($config[$k])) $d[$v] = $config[$k];
    }
    foreach ($d as $k => $v) $d[$k] = get_text($v);

    $tel = explode('~', $d['tel']);
    $d['tel_link'] = preg_replace('/[^0-9]/', '', $tel[0]);

    return $d;
}

// 운영 상태 : open / closed / holiday
function gwanak_open_status($hours)
{
    $now  = defined('G5_SERVER_TIME') ? G5_SERVER_TIME : time();
    $days = explode(',', GWANAK_OPEN_DAYS);
    if (!in_array(date('w', $now), $days)) return array('holiday', '오늘은 휴관일');

    if (preg_match('/(\d{1,2}):(\d{2})\s*~\s*(\d{1,2}):(\d{2})/', $hours, $m)) {
        $cur  = (int) date('G', $now) * 60 + (int) date('i', $now);
        $from = (int) $m[1] * 60 + (int) $m[2];
        $to   = (int) $m[3] * 60 + (int) $m[4];
        if ($cur >= $from && $cur < $to) return array('open', '지금 운영 중');
        return array('closed', '운영시간 아님');
    }
    return array('open', '오늘 운영');
}

function gwanak_today()
{
    $now  = defined('G5_SERVER_TIME') ? G5_SERVER_TIME : time();
    $week = array('일', '월', '화', '수', '목', '금', '토');
    return date('n월 j일', $now).' ('.$week[date('w', $now)].')';
}

// 아이콘 (SVG, currentColor)
function gwanak_icon($name, $class = '')
{
    static $p = array(
        'clipboard' => '<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>',
        'calendar'  => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/>',
        'meal'      => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2M7 2v20M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',
        'bus'       => '<path d="M8 6v6M15 6v6M2 12h19.6M18 18h3s.5-1.7.8-2.8c.1-.4.2-.8.2-1.2 0-.4-.1-.8-.2-1.2l-1.4-5C20.1 6.8 19.1 6 18 6H4a2 2 0 0 0-2 2v10h3"/><circle cx="7" cy="18" r="2"/><path d="M9 18h5"/><circle cx="16" cy="18" r="2"/>',
        'building'  => '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
        'map'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
        'users'     => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'heart'     => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
        'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'clock'     => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'search'    => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'menu'      => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'close'     => '<path d="M18 6 6 18M6 6l12 12"/>',
        'arrow'     => '<path d="M5 12h14M12 5l7 7-7 7"/>',
        'prev'      => '<path d="m15 18-6-6 6-6"/>',
        'next'      => '<path d="m9 18 6-6-6-6"/>',
        'down'      => '<path d="m6 9 6 6 6-6"/>',
        'up'        => '<path d="m5 12 7-7 7 7M12 19V5"/>',
        'pause'     => '<path d="M9 5v14M15 5v14"/>',
        'play'      => '<path d="m7 4 13 8-13 8V4z"/>',
        'plus'      => '<path d="M5 12h14M12 5v14"/>',
        'user'      => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
        'chat'      => '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M8 12h.01M12 12h.01M16 12h.01"/>',
        'pulse'     => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'book'      => '<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2zM22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
        'home'      => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
        'briefcase' => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>',
        'smile'     => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>',
        'video'     => '<path d="m22 8-6 4 6 4V8Z"/><rect x="2" y="6" width="14" height="12" rx="2"/>',
        'external'  => '<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
        'image'     => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.09-3.09a2 2 0 0 0-2.82 0L6 21"/>',
        'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
    );
    if (!isset($p[$name])) return '';
    return '<svg class="ico'.($class ? ' '.$class : '').'" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">'.$p[$name].'</svg>';
}
