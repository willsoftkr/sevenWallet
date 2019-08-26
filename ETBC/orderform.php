<?php
include_once('./_common.php');
// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js

// 주문상품 재고체크 js 파일
add_javascript('<script src="'.G5_JS_URL.'/shop.order.js"></script>', 0);

// 모바일 주문인지
$is_mobile_order = is_mobile();

set_session("ss_direct", $sw_direct);
// 장바구니가 비어있는가?
if ($sw_direct) {
	$tmp_cart_id = get_session('ss_cart_direct');
}
else {
	$tmp_cart_id = get_session('ss_cart_id');
}

if (get_cart_count($tmp_cart_id) == 0)
    alert_modal('Cart is Empty.', G5_URL.'/new/purchase_hash_full.php');    

// 새로운 주문번호 생성
$od_id = get_uniqid();
set_session('ss_order_id', $od_id);
$s_cart_id = $tmp_cart_id;
if($default['de_pg_service'] == 'inicis')
	set_session('ss_order_inicis_id', $od_id);

$g5['title'] = '주문서 작성';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?include_once('../new/common_head.php')?>
	<script >
		var order_stock_check = function() {
			var result = "";
			$.ajax({
				type: "POST",
				url: g5_url+"/shop/ajax.orderstock.php",
				cache: false,
				async: false,
				success: function(data) {
					result = data;
				}
			});
			return result;
		};
		$(function() {
			$('.btn_submit').trigger('click');
		});
	</script>
</head>
<body>
	<?//include_once('../new/mypage_head.php')?>
<?php

include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');
include_once(G5_LIB_PATH.'/latest.lib.php');

$order_action_url = G5_HTTPS_SHOP_URL.'/orderformupdate.php';
require_once(G5_SHOP_PATH.'/orderform.sub.php');

?>
</body>
</html>
