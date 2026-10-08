<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 그누보드 기본 스킨의 view_comment 화면을 그대로 사용하고, 디자인은 이 테마 스킨의 style.css 로 입힙니다.
// (그누보드 버전이 바뀌어도 기능이 어긋나지 않도록 코어 스킨을 불러옵니다)
include G5_SKIN_PATH.'/board/basic/view_comment.skin.php';
