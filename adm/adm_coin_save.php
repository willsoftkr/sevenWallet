<?
include_once('./_common.php');
$exe_type		= trim($_POST['kind']);   //코인 종류
$mb_id			= trim($_POST['mb_id']); //
$amunt			= trim($_POST['amt']);

If($exe_type=='rwc'){
	$sql = "update g5_member set rwc_coin_num=".$amunt." where mb_id ='".$mb_id."'";
	sql_query($sql);
	echo  (json_encode(array("result" => "complete changing rwc_coin amount",  "code" => $sql)));
}
else if($exe_type=='lkc'){
	$sql = "update g5_member set lkc_coin_num=".$amunt." where mb_id ='".$mb_id."'";
	sql_query($sql);
	echo  (json_encode(array("result" => "complete changing rwc_coin amount",  "code" => "0000")));
}
else if($exe_type=='package'){
	$sql = "update g5_member set ";
	$sql .= "it_pool1 = ".$_POST['p1'];
	$sql .= ", it_pool2 = ".$_POST['p2'];
	$sql .= ", it_pool3 = ".$_POST['p3'];
	$sql .= ", it_pool4 = ".$_POST['p4'];
	$sql .= ", it_pool5 = ".$_POST['p5'];
	$sql .= ", it_pool6 = ".$_POST['p6'];
	$sql .= ", it_pool7 = ".$_POST['p7'];
	$sql .= ", it_pool8 = ".$_POST['p8'];
	$sql .= ", it_GPU = ".$_POST['gpu'];
	$sql .= " where mb_id ='".$mb_id."'";
	sql_query($sql);
	echo  (json_encode(array("result" => "complete changing package amount",  "code" => "0000")));
}

?>

