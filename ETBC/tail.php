<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가


?>

    </div>
</div>

<!-- } 콘텐츠 끝 -->


<script>
$(function() {
    // 폰트 리사이즈 쿠키있으면 실행
    font_resize("container", get_cookie("ck_font_resize_rmv_class"), get_cookie("ck_font_resize_add_class"));
});
</script>

<?php
include_once(G5_PATH."/tail.sub.php");
?>