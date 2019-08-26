<?php
include_once('./_common.php');

$re1 = str_replace("%","",$_POST['repool1']);
$re2 = $_POST['repool2'];
$re3 = $_POST['repool3'];
$re4 = $_POST['repool4'];
$re5 = $_POST['repool5'];
$re6 = $_POST['repool6'];
$re7 = $_POST['repool7'];
$reg = $_POST['repool_gpu'];

	
	$sql = " update pina_mb_hashpower set ";
	if($re1)
	$sql .= " p1_repurchase  = ".$re1;
	if($re2)
	$sql .= ", p2_repurchase  = ".$re2;
	if($re3)
	$sql .= ", p3_repurchase  = ".$re3;
	if($re4)
	$sql .= ", p4_repurchase  = ".$re4;
	if($reg)
	$sql .= ", pg_repurchase  = ".$reg;

	$sql .=" where mb_id = '".$member['mb_id']."' ";
	$ret = sql_query($sql);

	$myObj = new stdClass();
echo json_encode($myObj);

?>