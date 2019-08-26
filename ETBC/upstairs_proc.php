<?php
include_once('./_common.php');



$amount = $_POST['save_eos'];
$coin_symbol = $_POST['coin_symbol'];
$mb_up_id = $_POST['mb_id'];

$now_date = date('Y-m-d H:i:s');

$cnt='01';
$orderid = date("YmdHis",time()).$cnt;
$pv = $amount;


/*
//$amount : deposit EOS 수량 PV랑 동일 수
//$orderid : depost id 년월일시분초01
//$pv : deposit EOS수량 만큼 PV (수당 계산)
//바이너리 및 바이너리 추천 계산용 기록.
*/

$math_sql = "select  sum(mb_save_point + mb_balance + mb_shift_amt + mb_deposit_calc) as total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);
$EOS_TOTAL =  number_format($math_total['total'],3);  //합계잔고  //합계잔고

if($EOS_TOTAL < $amount){
	echo (json_encode(array("result" => "failed",  "code" => "0002", "sql" => 'not enough balance')));
}else{

$sql = "insert g5_shop_order set
	od_id				= '".$orderid."'
	, mb_id             = '".$mb_up_id."'
	, od_cart_price     = '".$amount."'
	, od_receipt_time   = '".$now_date."'
	, od_status         = '입금'
	, od_time           = '".$now_date."'
	, od_settle_case    = 'EosDeposit'
	, pv				= ".$pv;
$rst = sql_query($sql, false);

if($rst){//오더 테이블 기록이 이상 없을 시에

/*
//전송된 EOS량에서 차감하고
//Deposit EOS량에는 증가시킨다.
*/
	
	

	$sum_deposit = "select mb_deposit_point, mb_save_point, mb_deposit_acc, mb_deposit_calc  from g5_member where mb_id='".$mb_up_id."'";
	$point = sql_fetch($sum_deposit);
	
	$save_p = $point['mb_deposit_point'] + $amount;
	
	if($save_p>=1 && $save_p<=100){
		$mb_level = 1;
	}
	else if($save_p>=101 && $save_p<=500){
		$mb_level = 2;
	}
	else if($save_p>=501){
		$mb_level = 3;
	}
	

	$update_point = " update g5_member set mb_deposit_calc = ".($point['mb_deposit_calc'] - $amount);

	if($mb_up_id != 'copy5285m'){
		$update_point .= ", mb_level = '".$mb_level."'" ;
	}
	$update_point .= ", mb_deposit_point = ".($point['mb_deposit_point'] + $amount);
	$update_point .= ", mb_deposit_acc = ".($point['mb_deposit_acc'] + $amount);
	$update_point .= " where mb_id ='".$mb_up_id."'";
	
	//print_r($update_point);

	sql_query($update_point);
	
	//echo "<br>";
	echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $save_hist)));
}
else{
	echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
}
}
?>