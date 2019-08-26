<?php

$sub_menu = "600200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

//회원 테이블을 불러 온다. Level별로 불러올까?
$get_cond = "select * from eos_daily_paid";
$list = sql_query($get_cond);

$v7_cost = number_format(get_coin_cost('v7'),2);


$pay_percent = array();

while($row = sql_fetch_array($list)){
	$pay_percent[$row['eos_grade']] = $row['eos_per'];
	echo $pay_percent[$row['eos_grade']];
}
$mem_list = "select * from {$g5['member_table']} where mb_deposit_point >= 1" ;//Yellow
$rst_list = sql_query($mem_list);

$point_day = $_GET['to_date'];

$day = $point_day;

$get_today = sql_fetch("select mb_id from soodang_pay where day='".$day."' and allowance_name = 'daily payout' " );
if($get_today[mb_id]){
	alert("this work is already excuted");
	die;
}

while($mrow = sql_fetch_array($rst_list)){
	$benefit = round($mrow[mb_deposit_point] * $pay_percent[$mrow['grade']]/100 ,3);
	$allowance_name = "daily payout";
	$rec_adm = "daily payout";
	$rec = "daily payout";
	$mb_level = $mrow['grade'];
	
	$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from soodang_pay where 1=1 and mb_id='".$mrow[mb_id]."'");
	save_benefit($day, $mrow[mb_id], $mrow[mb_no], $mrow[mbname], $recom, $allowance_name, $benefit, $rec_adm, $rec, $mb_level,$mrow['mb_v7_account']);
	/*
	if($soodang_sum[eb_sum]+$benefit>=$mrow[mb_deposit_point]*5){//보유 EOS의 5배를 수당으로 받았을 시에 이 회원 레벨은 0으로 바뀌고 매출도 사라 진다.
		//$reset_mem = "update g5_member set mb_level=0, mb_deposit_point=0 where mb_id ='".$mrow[mb_id]."'";
		//sql_query($reset_mem);
	}else{
		save_benefit($day, $mrow[mb_id], $mrow[mb_no], $mrow[mbname], $recom, $allowance_name, $benefit, $rec_adm, $rec, $mb_level);
	}*/
}

function save_benefit($day, $mbid, $mbno, $mbname, $recom, $allowance_name, $benefit, $rec_adm, $rec, $mb_level,$v7_account){
	global $v7_cost;
	$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit.",3), mb_v7_account = round(mb_v7_account+ ".$benefit."/".$v7_cost.",3) where mb_id = '".$mbid."';";
	
	print_r($balance_up);

	sql_query($balance_up);
	$temp_sql1 = " insert soodang_pay set day='".$day."'";
	$temp_sql1 .= " ,mb_no			= ".$mbno;
	$temp_sql1 .= " ,mb_id			= '".$mbid."'";
	$temp_sql1 .= " ,mb_level      = ".$mb_level;
	$temp_sql1 .= " ,mb_name		= '".$mbname."'";
	$temp_sql1 .= " ,mb_recommend	= '".$recom."'";
	$temp_sql1 .= " ,source	= '".$v7_account."'";
	$temp_sql1 .= " ,allowance_name	= '".$allowance_name."'";
	$temp_sql1 .= " ,benefit		=  ".$benefit;	
	$temp_sql1 .= " ,rec			= '".$rec."'";
	$temp_sql1 .= " ,rec_adm		= '".$rec_adm."'";
	$temp_sql1 .= " ,datetime		= '".date("Y-m-d H:i:s")."'";
	sql_query($temp_sql1);
echo	$temp_sql1;
echo "<br>";
}
?>

