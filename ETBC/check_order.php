<?php

include_once('/home/sdevftv/html/common.php');
$ord_id = $_POST['ord_id'];
$chk_incoin = "select od_status from g5_shop_order where od_id = '$ord_id';";
$row = sql_fetch($chk_incoin);
if($row['od_status']=='입금'){

	$ret = "입금";
}
else{
	$ret = "미완료";
}
	try {
		

		$result['success']	= true;
		$result['data']		= $ret;


	} catch(exception $e) {
		
		$result['success']	= false;
		$result['msg']		= $e->getMessage();
		$result['code']		= $e->getCode();

	} finally {
	
		echo json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);

	}
?>