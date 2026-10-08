<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<?php if (!defined('_INDEX_')) { ?>
    </div><!-- /.sub-body -->
<?php } ?>
</main>

<!-- 하단 시작 { -->
<footer id="ft" class="ft">
    <div class="ft-top">
        <div class="inner">
            <ul class="ft-links">
                <li><a href="<?php echo gwanak_url('content', 'privacy'); ?>" class="is-strong">개인정보처리방침</a></li>
                <li><a href="<?php echo gwanak_url('content', 'provision'); ?>">이용약관</a></li>
                <li><a href="<?php echo gwanak_url('content', 'roadmap'); ?>">오시는 길</a></li>
                <li><a href="<?php echo gwanak_url('content', 'facility'); ?>">시설안내</a></li>
            </ul>
            <div class="ft-family">
                <label for="ft_family" class="sound_only">관련기관 바로가기</label>
                <select id="ft_family" class="js-family">
                    <option value="">관련기관 바로가기</option>
                    <?php foreach (gwanak_family() as $f) { ?>
                    <option value="<?php echo $f[1]; ?>"><?php echo $f[0]; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
    <div class="ft-body">
        <div class="inner">
            <div class="ft-brand">
                <img src="<?php echo G5_THEME_URL; ?>/img/logo.png" width="200" height="40" alt="사회복지법인 한주재단 시립관악노인종합복지관">
            </div>
            <address class="ft-info">
                <p><span>주소</span> <?php echo $gw_info['addr']; ?></p>
                <p><span>대표전화</span> <a href="tel:<?php echo $gw_info['tel_link']; ?>"><?php echo $gw_info['tel']; ?></a> <span>팩스</span> <?php echo $gw_info['fax']; ?></p>
                <p><span>이메일</span> <a href="mailto:<?php echo $gw_info['email']; ?>"><?php echo $gw_info['email']; ?></a></p>
            </address>
            <div class="ft-hours">
                <p class="ft-hours-tit">운영시간</p>
                <p class="ft-hours-txt"><?php echo $gw_info['hours']; ?></p>
                <p class="ft-hours-sub"><?php echo $gw_info['holiday']; ?></p>
            </div>
        </div>
        <div class="inner">
            <p class="ft-copy">Copyright &copy; <?php echo $gw_info['name']; ?>. All rights reserved.</p>
        </div>
    </div>
</footer>

<button type="button" class="to-top js-to-top" aria-label="맨 위로 이동"><?php echo gwanak_icon('up'); ?></button>
<!-- } 하단 끝 -->

<script src="<?php echo G5_THEME_URL; ?>/js/theme.js?ver=<?php echo G5_JS_VER; ?>"></script>

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');
