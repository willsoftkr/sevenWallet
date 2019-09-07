<?php
include_once('./_common.php');
//include_once(G5_LIB_PATH.'/mailer.lib.php');

if($_POST['status']=='N'){
	if($_POST['refund'] == 'Y'){
		$get_row = "Select * from withdrawal_request where uid  = ".$_POST['uid'];
		$slamdunk = sql_query($get_row);
		$ret = sql_fetch_array($slamdunk);
		$mb_id = $ret['mb_id'];
		$amt = ($ret['amt']);
		$sql1 = "update g5_member set mb_btc_amt = mb_btc_amt +".$amt." where mb_id='".$mb_id."'";
		sql_query($sql1);
		//print_r($sql1);
	}
}

$sql = " update withdrawal_request set status = '".$_POST[status]."' ";
$sql .= ", update_dt = now() ";
$sql .= " where uid = '".$_POST['uid']."' ";
$obj = new stdClass();
$obj->result = sql_query($sql);
$obj->status = $_POST['status'];
echo json_encode($obj);
