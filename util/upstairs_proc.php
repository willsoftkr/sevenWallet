<?php
include_once('./_common.php');

$account = $_POST['account'];
$amount = $_POST['amount'];
$upstair = $_POST['upstair'];
$cost = $_POST['coin_cost'];
$mb_id = $_POST['mb_id'];
$coin_symbol = $_POST['coin_symbol'];

$now_date = date('Y-m-d H:i:s');

$cnt='01';
$orderid = date("YmdHis",time()).$cnt;
$pv = $amount;



//$amount : deposit EOS 수량 PV랑 동일 수
//$orderid : depost id 년월일시분초01
//$pv : deposit EOS수량 만큼 PV (수당 계산)
//바이너리 및 바이너리 추천 계산용 기록.

/*
$math_sql = "select  sum(mb_save_point + mb_balance + mb_shift_amt + mb_deposit_calc) as total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);
$EOS_TOTAL =  number_format($math_total['total'],3);  //합계잔고  //합계잔고
*/

if($account < $amount){
	echo (json_encode(array("result" => "failed",  "code" => "0002", "sql" => 'not enough balance')));
}else{

	$sql = "insert g5_shop_order set
		od_id				= '".$orderid."'
		, mb_id             = '".$mb_id."'
		, od_cart_price     = '".$amount."'
		, upstair     = '".$upstair."'
		, od_cash = '".$cost."'
		, od_time           = '".$now_date."'
		, od_receipt_time   = '".$now_date."'
		, od_status         = '입금'
		, od_settle_case    = '".$coin_symbol."'
		, pv				= ".$pv;
	$rst = sql_query($sql, false);

	if($rst){//오더 테이블 기록이 이상 없을 시에


		$sum_deposit = "select mb_btc_amt, mb_deposit_point from g5_member where mb_id='".$mb_id."'";
		$point = sql_fetch($sum_deposit);
		
		$save_a = ($point['mb_btc_amt'] - $amount);
		$save_p = ($point['mb_deposit_point'] + $upstair);
		
		if($save_p>=1 && $save_p<500){
			$grade = 0;
		}
		else if($save_p>=500 && $save_p<3000){
			$grade = 1;
		}
		else if($save_p>=3000 && $save_p<10000){
			$grade = 2;
		}
		else if($save_p>=10000){
			$grade = 3;
		}
		

		$update_point = "update g5_member set mb_btc_amt ='".$save_a."'";

		if($mb_id != 'copy5285m'){
			$update_point .= ", grade = '".$grade."'";
		}
		$update_point .= ", mb_deposit_point = '".$save_p."'";
		$update_point .= " where mb_id ='".$mb_id."'";
		
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