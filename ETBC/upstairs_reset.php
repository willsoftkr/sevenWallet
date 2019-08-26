<?php
include_once('./_common.php');

/*
$_POST['mb_id'] = 'test8';
$_POST['amount'] = '1,000.000';
$_POST['upstair_acc'] = '1,000.000';
*/

$mb_id = $_POST['mb_id'];
$upstair_acc = (int)str_replace(',', '', $_POST['upstair_acc']);
$amount = (int)str_replace(',', '', $_POST['amount']);

$now_date = date('Y-m-d H:i:s');


$sql = "insert g5_shop_upstair_reset_log set
	mb_id             = '".$mb_id."'
	, od_date     = '".$now_date."'
	, acc_num   = '".$upstair_acc."'
	, current_deposit         = '1000'";
//echo $sql;


$rst = sql_query($sql, false);

if($rst){

	$update_point = "update g5_member set ";
	$update_point .= " mb_deposit_point = '0' ";

	if($mb_id != 'copy5285m'){
		$update_point .= ", mb_level = '0' " ;
	}

	$update_point .= ", mb_deposit_acc ='".($upstair_acc+$amount)."'";
	$update_point .= " where mb_id ='".$mb_id."'";
	
	//echo "<br>".$update_point;

	sql_query($update_point);

	echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $save_hist)));
}
else{
	echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
}
?>