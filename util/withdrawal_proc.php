<?php
include_once('./_common.php');
/*
$mb_id= 'coolrunning';
$account= '10.00091431';
$amount= '1';
$amount_left= '0.00054858';
$fee= '0.01000000';
$amount_total= '1.01000000';
$cost= '10609.13';
$source= 'btc';
$coin= 'btc';
$type= 'ithdrawal';
$address = 'address';
*/

$mb_id = $_POST['mb_id'];
$account = $_POST['account'];
$amount = $_POST['amount'];
$amount_left = $_POST['amount_left'];
$amount_total = $_POST['amount_total'];
$fee = $_POST['fee'];
$cost = $_POST['coin_cost'];
$source = $_POST['source'];
$coin = $_POST['coin'];
$type = $_POST['type'];
$address = $_POST['address'];

$cnt = '1';
$now_date = date('Y-m-d H:i:s');
$orderid = date("YmdHis",time()).$cnt;

$where_amt = "mb_".$source."_amt";
$where_calc = "mb_".$source."_calc";
$to_calc = "mb_".$coin."_calc";

$account_left = $account - $amount_total;
//$amount = deposit EOS 수량 PV랑 동일 수
//$orderid = depost id 년월일시분초01
//$pv = deposit EOS수량 만큼 PV (수당 계산)
//바이너리 및 바이너리 추천 계산용 기록.

/*
$math_sql = "select  sum(mb_save_point + mb_balance + mb_shift_amt + mb_deposit_calc) as total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);
$EOS_TOTAL =  number_format($math_total['total'],3);  //합계잔고  //합계잔고
*/

$sum_deposit = "select sum(mb_btc_account + mb_btc_calc + mb_btc_amt ) as hap from g5_member where mb_id='".$mb_id."'";
$sum_deposit_result= sql_fetch($sum_deposit);

$save = $sum_deposit_result['hap'];


if($save < $amount_total){
	echo (json_encode(array("result" => "failed",  "code" => "0002", "sql" => 'not enough balance')));
}else{

	$sql = "insert withdrawal_request set
		od_id				= '".$orderid."'
        , mb_id             = '".$mb_id."'
        , addr             = '".$address."'
        , amt     = '".$amount."'
		, account     = '".number_format($account,8)."'
		, amt_usd    = '".$amount_left."'
		, amt_total     = '".$amount_total."'
		, amt_left     = '".$account_left*1.01."'
		, fee = '".$fee."'
        , cost           = '".$cost."'
        , coin           = '".$coin."'
		, od_type   = '전환'
        , create_dt    = '".$now_date."'
        , update_dt    = '".$now_date."'";
	//print_r($sql);
	$rst = sql_query($sql, false);

	if($rst){//전환 테이블 기록이 이상 없을 시에
        
		
		/*
		$save_a = ($sum_deposit_result['mb_btc_amt'] - $amount);
		$save_p = ($sum_deposit_result['mb_deposit_point'] + $upstair);
		
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
        */
		

        $update_point = "update g5_member set {$where_amt} = $where_amt - $amount_total ";
		$update_point .= " where mb_id ='".$mb_id."'";
        
        //echo "<br>";
		//print_r($update_point);

		sql_query($update_point);
		
		//echo "<br>";
		echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $update_point)));
	}
	else{
		echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $update_point)));
	}
}
?>