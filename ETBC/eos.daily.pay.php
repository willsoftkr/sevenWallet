<?php

$sub_menu = "600200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

//회원 테이블을 불러 온다. Level별로 불러올까?
$get_cond = "select * from EOS_Daily_Paid";
$list = sql_query($get_cond);

$pay_percent = array();

while($row = sql_fetch_array($list)){
	$pay_percent[$row['eos_grade']] = $row['eos_per'];
}
$mem_list = "select * from {$g5['member_table']} ";//Yellow
$rst_list = sql_query($mem_list);
while($mrow = sql_fetch_array($rst_list)){
	$mrow[mb_save_point] * $pay_percent[$mrow[mb_level]];
}






function save_benefit($day,$mbid, $mbname, $recom, $allowance_name, $sales_day, $habu_day_sales, $benefit, $rec_adm,$rec,$exchange_rate ){
	//수당을 비트로 환산 한다.
	$benefit_bit = round($benefit/$exchange_rate,8);
	//회원 잔고에 더해 준다.
	$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit_bit.",8)
	where mb_id = '".$mbid."';";
	sql_query($balance_up);

	$temp_sql1 = " insert soodang_pay set day='".$day."'";
	$temp_sql1 .= " ,mb_id		= '".$mbid."'";
	$temp_sql1 .= " ,mb_name		= '".$mbname."'";
	$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
	$temp_sql1 .= " ,allowance_name		= '".$allowance_name."'";
	
	//$temp_sql1 .= " ,day_sales		=  '".$sales_day."'";
	//$temp_sql1 .= " ,habu_day_sales 		=  '".$habu_day_sales."'";
	$temp_sql1 .= " ,benefit			=  ".$benefit_bit;
	$temp_sql1 .= " ,benefit_usd		=  '".($benefit)."'";
	$temp_sql1 .= " ,exchange_rate      =  ".$exchange_rate;
	$temp_sql1 .= " ,rec		= '".$rec."'";
	$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";
	sql_query($temp_sql1);

	//echo $temp_sql1.'<br>';

}
?>