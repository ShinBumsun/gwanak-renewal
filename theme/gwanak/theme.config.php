<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 반응형 단일 테마 : 모바일에서도 PC 테마(반응형)를 사용
define('G5_THEME_DEVICE', 'pc');

$theme_config = array();

// 게시판관리 > 테마 스킨 가져오기에 사용되는 기본값
$theme_config = array(
    'set_default_skin'          => false,
    'preview_board_skin'        => 'basic',
    'preview_mobile_board_skin' => 'basic',
    'cf_member_skin'            => 'basic',
    'cf_mobile_member_skin'     => 'basic',
    'cf_new_skin'               => 'basic',
    'cf_mobile_new_skin'        => 'basic',
    'cf_search_skin'            => 'basic',
    'cf_mobile_search_skin'     => 'basic',
    'cf_connect_skin'           => 'basic',
    'cf_mobile_connect_skin'    => 'basic',
    'cf_faq_skin'               => 'basic',
    'cf_mobile_faq_skin'        => 'basic',
    'bo_gallery_cols'           => 4,
    'bo_gallery_width'          => 600,
    'bo_gallery_height'         => 420,
    'bo_mobile_gallery_width'   => 600,
    'bo_mobile_gallery_height'  => 420,
    'bo_image_width'            => 900,
);
