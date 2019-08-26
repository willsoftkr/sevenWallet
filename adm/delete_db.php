<?
include_once('./_common.php');

include_once ('./admin.head.php');



if($func == 'member'){
	$sql_clear = "update g5_member set mb_balance = '0', mb_v7_account = '0'";
	$sql_result = sql_query($sql_clear);
	echo "result : ".$sql_result;
	
}else if($func == 'soodang'){
	$sql_clear2 = " TRUNCATE table bnoo2; ";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table bthirty2; ";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table iwol;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table soodang_pay;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table noo2;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = "TRUNCATE table btoday2;";
		$sql_result = sql_query($sql_clear2);
	$sql_clear2 = " TRUNCATE table thirty2";
		$sql_result = sql_query($sql_clear2);
	
	echo "result : ".$sql_result;
}
?>
