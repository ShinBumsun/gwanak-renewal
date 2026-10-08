<?php
/*
 * 관악노인종합복지관 테마 초기 설정
 * 접속 : https://도메인/theme/gwanak/setup/  (최고관리자 로그인 필요)
 *  - 게시판 그룹 / 게시판 생성 (이미 있으면 건너뜀)
 *  - 내용관리(페이지) 생성 (이미 있으면 건너뜀)
 *  - 메뉴설정 등록 (선택 시 기존 메뉴를 교체)
 *  - 기본환경설정 여분필드에 기관 정보 등록 (비어 있을 때만)
 * 설정이 끝나면 이 폴더(setup)를 삭제해 주세요.
 */
include_once('../../../common.php');
include_once(G5_THEME_PATH.'/inc/theme.lib.php');

if ($is_admin !== 'super') alert('최고관리자로 로그인 후 이용해 주세요.', G5_BBS_URL.'/login.php?url='.urlencode(G5_THEME_URL.'/setup/'));

// 게시판 그룹
$gw_groups = array(
    'gwanak_main' => '메인관리',
    'gwanak_info' => '이용안내',
    'gwanak_news' => '복지관소식',
    'gwanak_pr'   => '관악홍보실',
    'gwanak_form' => '신청접수',
);

// 게시판 : bo_table => [그룹, 이름, 스킨, 글쓰기권한, 읽기권한, 비밀글(0/1/2), 분류]
// 권한 : 1 비회원, 2 회원, 10 관리자
$gw_boards = array(
    'mainvisual'    => array('gwanak_main', '메인비주얼', 'gallery', 10, 1, 0, '텍스트 표시|이미지만'),
    'food'          => array('gwanak_info', '월별 식단표', 'gallery', 10, 1, 0, ''),
    'notice'        => array('gwanak_news', '공지사항', 'basic', 10, 1, 0, ''),
    'recruit'       => array('gwanak_news', '인재채용', 'basic', 10, 1, 0, '채용공고|합격자공고'),
    'report'        => array('gwanak_news', '재정보고', 'basic', 10, 1, 0, ''),
    'study'         => array('gwanak_news', '온라인강의', 'gallery', 10, 1, 0, ''),
    'data'          => array('gwanak_news', '자료실', 'basic', 10, 1, 0, ''),
    'participation' => array('gwanak_news', '어르신 참여방', 'basic', 2, 1, 0, ''),
    'program'       => array('gwanak_news', '프로그램 강좌신청', 'basic', 10, 1, 0, '접수중|접수마감'),
    'photo'         => array('gwanak_pr', '관악앨범', 'gallery', 10, 1, 0, ''),
    'movie'         => array('gwanak_pr', '관악동영상', 'gallery', 10, 1, 0, ''),
    'news'          => array('gwanak_pr', '언론에 비친 관악', 'basic', 10, 1, 0, ''),
    'newsletter'    => array('gwanak_pr', '뉴스레터', 'gallery', 10, 1, 0, ''),
    'volunteer'     => array('gwanak_info', '자원봉사활동 현황', 'basic', 10, 1, 0, ''),
    'form_service'  => array('gwanak_form', '자원봉사 신청', 'basic', 1, 10, 2, ''),
    'form_sponsor'  => array('gwanak_form', '후원 신청', 'basic', 1, 10, 2, ''),
);

// 기관 정보 여분필드
$gw_cf = array(
    'cf_1' => array('대표전화', '02-888-6144~5'),
    'cf_2' => array('팩스', '02-888-8026'),
    'cf_3' => array('주소', '(08708) 서울특별시 관악구 보라매로 35'),
    'cf_4' => array('이메일', 'nambunoin@hanmail.net'),
    'cf_5' => array('운영시간', '평일 09:00 ~ 18:00'),
    'cf_6' => array('휴관 안내', '토·일요일 및 공휴일 휴관'),
);

$logs = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ss_token = get_session('ss_gwanak_setup');
    if (!$ss_token || !isset($_POST['token']) || !hash_equals($ss_token, $_POST['token'])) alert('잘못된 요청입니다. 다시 시도해 주세요.');
    sql_query(" SET SESSION sql_mode = '' ", false);

    // 1. 그룹
    foreach ($gw_groups as $gr_id => $gr_subject) {
        $row = sql_fetch(" select gr_id from {$g5['group_table']} where gr_id = '{$gr_id}' ");
        if (!empty($row['gr_id'])) { $logs[] = "그룹 [{$gr_subject}] 이미 있음"; continue; }
        sql_query(" insert into {$g5['group_table']} set gr_id = '{$gr_id}', gr_subject = '".sql_real_escape_string($gr_subject)."', gr_device = 'both' ");
        $logs[] = "그룹 [{$gr_subject}] 생성";
    }

    // 2. 게시판
    $order = 0;
    foreach ($gw_boards as $bo_table => $b) {
        $order++;
        $row = sql_fetch(" select bo_table from {$g5['board_table']} where bo_table = '{$bo_table}' ");
        if (!empty($row['bo_table'])) { $logs[] = "게시판 [{$b[1]}] 이미 있음"; continue; }

        $is_gallery = ($b[2] === 'gallery');
        sql_query(" insert into {$g5['board_table']}
                    set bo_table = '{$bo_table}',
                        gr_id = '{$b[0]}',
                        bo_subject = '".sql_real_escape_string($b[1])."',
                        bo_mobile_subject = '".sql_real_escape_string($b[1])."',
                        bo_device = 'both',
                        bo_skin = '{$b[2]}',
                        bo_mobile_skin = '{$b[2]}',
                        bo_list_level = 1,
                        bo_read_level = '{$b[4]}',
                        bo_write_level = '{$b[3]}',
                        bo_reply_level = 10,
                        bo_comment_level = ".($bo_table === 'participation' ? 2 : 10).",
                        bo_upload_level = '{$b[3]}',
                        bo_download_level = 1,
                        bo_html_level = 10,
                        bo_link_level = '{$b[3]}',
                        bo_count_modify = 1,
                        bo_count_delete = 1,
                        bo_use_secret = '{$b[5]}',
                        bo_use_dhtml_editor = 1,
                        bo_use_category = ".($b[6] ? 1 : 0).",
                        bo_category_list = '".sql_real_escape_string($b[6])."',
                        bo_use_search = 1,
                        bo_table_width = 100,
                        bo_subject_len = 60,
                        bo_mobile_subject_len = 30,
                        bo_page_rows = ".($is_gallery ? 12 : 15).",
                        bo_mobile_page_rows = ".($is_gallery ? 12 : 15).",
                        bo_new = 72,
                        bo_hot = 100,
                        bo_image_width = 900,
                        bo_gallery_cols = 4,
                        bo_gallery_width = 600,
                        bo_gallery_height = 420,
                        bo_mobile_gallery_width = 600,
                        bo_mobile_gallery_height = 420,
                        bo_upload_count = ".($bo_table === 'mainvisual' ? 2 : 5).",
                        bo_upload_size = 10485760,
                        bo_reply_order = 1,
                        bo_order = '{$order}',
                        bo_include_head = '_head.php',
                        bo_include_tail = '_tail.php',
                        bo_content_head = '',
                        bo_content_tail = '',
                        bo_mobile_content_head = '',
                        bo_mobile_content_tail = '',
                        bo_insert_content = '',
                        bo_notice = '' ");

        // 게시판 테이블 생성 (관리자 게시판 생성과 동일한 방식)
        $file = file(G5_ADMIN_PATH.'/sql_write.sql');
        if (function_exists('get_db_create_replace')) $file = get_db_create_replace($file);
        $sql = implode("\n", $file);
        $sql = preg_replace(array('/__TABLE_NAME__/', '/;/'), array($g5['write_prefix'].$bo_table, ''), $sql);
        sql_query($sql, false);

        @mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
        @chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);

        $logs[] = "게시판 [{$b[1]}] 생성 ({$bo_table})";
    }

    // 3. 내용관리 페이지
    $pages = array();
    foreach (gwanak_default_menu() as $m) {
        foreach ($m[3] as $s) if ($s[1] === 'content') $pages[$s[2]] = $s[0];
    }
    foreach ($pages as $co_id => $co_subject) {
        // 테마 기본 내용 : setup/content/{co_id}.html
        $tpl  = __DIR__.'/content/'.$co_id.'.html';
        $body = is_file($tpl) ? str_replace(array('{THEME_URL}', '{BBS_URL}'), array(G5_THEME_URL, G5_BBS_URL), file_get_contents($tpl))
                              : '<p>'.$co_subject.' 내용을 입력해 주세요. (관리자 &gt; 게시판관리 &gt; 내용관리)</p>';

        $row = sql_fetch(" select co_id from {$g5['content_table']} where co_id = '{$co_id}' ");
        if (!empty($row['co_id'])) {
            if (!empty($_POST['overwrite_content']) && is_file($tpl)) {
                sql_query(" update {$g5['content_table']}
                            set co_html = 1, co_content = '".sql_real_escape_string($body)."',
                                co_skin = 'theme/basic', co_mobile_skin = 'theme/basic'
                            where co_id = '{$co_id}' ");
                $logs[] = "페이지 [{$co_subject}] 테마 기본 내용으로 덮어씀";
            } else {
                $logs[] = "페이지 [{$co_subject}] 이미 있음";
            }
            continue;
        }
        sql_query(" insert into {$g5['content_table']}
                    set co_id = '{$co_id}',
                        co_html = 1,
                        co_subject = '".sql_real_escape_string($co_subject)."',
                        co_content = '".sql_real_escape_string($body)."',
                        co_mobile_content = '',
                        co_skin = 'theme/basic',
                        co_mobile_skin = 'theme/basic' ");
        $logs[] = "페이지 [{$co_subject}] 생성 ({$co_id})";
    }

    // 4. 메뉴
    if (!empty($_POST['replace_menu'])) {
        sql_query(" delete from {$g5['menu_table']} ");
        $i = 0;
        foreach (gwanak_default_menu() as $m) {
            $i++;
            $code = (string) ($i * 10);
            sql_query(" insert into {$g5['menu_table']}
                        set me_code = '{$code}', me_name = '".sql_real_escape_string($m[0])."',
                            me_link = '".sql_real_escape_string(gwanak_url($m[1], $m[2]))."',
                            me_target = 'self', me_order = '{$i}', me_use = 1, me_mobile_use = 1 ");
            $j = 0;
            foreach ($m[3] as $s) {
                $j++;
                sql_query(" insert into {$g5['menu_table']}
                            set me_code = '".$code.($j * 10)."', me_name = '".sql_real_escape_string($s[0])."',
                                me_link = '".sql_real_escape_string(gwanak_url($s[1], $s[2]))."',
                                me_target = 'self', me_order = '{$j}', me_use = 1, me_mobile_use = 1 ");
            }
        }
        $logs[] = '메뉴설정을 테마 기본 메뉴로 교체';
    }

    // 5. 기관 정보 (여분필드)
    $set = array();
    foreach ($gw_cf as $k => $v) {
        if (empty($config[$k.'_subj'])) $set[] = "{$k}_subj = '".sql_real_escape_string($v[0])."'";
        if (empty($config[$k]))         $set[] = "{$k} = '".sql_real_escape_string($v[1])."'";
    }
    if ($set) {
        sql_query(" update {$g5['config_table']} set ".implode(', ', $set));
        $logs[] = '기본환경설정 여분필드 1~6에 기관 정보 등록';
    }

    // 캐시 정리
    if (function_exists('g5_delete_all_cache')) g5_delete_all_cache();
}

$token = md5(uniqid(mt_rand(), true));
set_session('ss_gwanak_setup', $token);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>관악 테마 초기 설정</title>
<style>
body { margin: 0; padding: 40px 16px; background: #f4f1ec; font-family: -apple-system, "Apple SD Gothic Neo", "Malgun Gothic", sans-serif; color: #1a1f27; line-height: 1.6; }
.wrap { max-width: 760px; margin: 0 auto; padding: 36px; background: #fff; border-radius: 20px; }
h1 { margin: 0 0 8px; font-size: 26px; }
h2 { margin: 28px 0 8px; font-size: 18px; }
table { width: 100%; border-collapse: collapse; font-size: 14px; }
th, td { padding: 8px; border-bottom: 1px solid #e7e1d9; text-align: left; }
th { background: #fbf7f2; }
.btn { display: inline-block; margin-top: 24px; padding: 14px 28px; border: 0; border-radius: 999px; background: #1f3a5f; color: #fff; font-size: 16px; font-weight: 700; cursor: pointer; }
.log { margin-top: 20px; padding: 16px 20px; background: #e7f4ec; border-radius: 12px; font-size: 14px; }
.warn { padding: 12px 16px; background: #fff1e6; border-radius: 12px; color: #9c3e03; font-size: 14px; }
label { display: block; margin-top: 16px; font-weight: 600; }
</style>
</head>
<body>
<div class="wrap">
    <h1>관악노인종합복지관 테마 초기 설정</h1>
    <p>테마에 필요한 게시판·페이지·메뉴를 한 번에 만듭니다. 이미 있는 게시판과 페이지는 건드리지 않습니다.</p>

    <?php if ($logs) { ?>
    <div class="log">
        <strong>완료되었습니다.</strong>
        <ul><?php foreach ($logs as $l) echo '<li>'.get_text($l).'</li>'; ?></ul>
    </div>
    <p class="warn">설정이 끝났다면 보안을 위해 서버에서 <b>theme/gwanak/setup</b> 폴더를 삭제해 주세요.</p>
    <?php } ?>

    <h2>생성할 게시판</h2>
    <table>
        <tr><th>bo_table</th><th>이름</th><th>스킨</th><th>글쓰기</th></tr>
        <?php foreach ($gw_boards as $k => $b) { ?>
        <tr><td><?php echo $k; ?></td><td><?php echo $b[1]; ?></td><td><?php echo $b[2]; ?></td><td><?php echo $b[3] == 10 ? '관리자' : ($b[3] == 2 ? '회원' : '누구나'); ?><?php echo $b[5] == 2 ? ' · 비밀글' : ''; ?></td></tr>
        <?php } ?>
    </table>

    <form method="post">
        <input type="hidden" name="token" value="<?php echo $token; ?>">
        <label><input type="checkbox" name="replace_menu" value="1"> 관리자 메뉴설정을 테마 기본 메뉴로 교체 (기존 메뉴 삭제)</label>
        <label><input type="checkbox" name="overwrite_content" value="1"> 이미 있는 페이지 중 테마 기본 내용이 준비된 페이지(인사말 등)를 테마 내용으로 덮어쓰기</label>
        <button type="submit" class="btn">초기 설정 실행</button>
    </form>
</div>
</body>
</html>
