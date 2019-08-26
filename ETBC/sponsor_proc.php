<?php 
include_once('./_common.php');

$mb_id = $member['mb_id'];
$url = $_POST['link'];
$brecommend = $_POST['mb_brecommend'];

/* 테스트
$mb_id  = 'arcthan';
$brecommend = 'test4';
*/

$sql = "select count(*) as cnt from g5_member where mb_brecommend = '$brecommend' ";
$row = sql_fetch($sql);

if($row['cnt'] > 1){
	alert("Unable to register as sponsor. input someone else.", $url);
}else{
	if($row['cnt'] == 1){
		$bre_commend_type = 'R';
	}else{
		$bre_commend_type = 'L';
	}
	update_brecommend2($mb_id,$brecommend,$bre_commend_type);
	alert("Sponsor regist complete!", $url);

}

function update_brecommend2($mb_id, $brecommend, $bre_commend_type){
	if($bre_commend_type == 'L'){
		$mb_lr =	1;	
	}
	else{
		$mb_lr = 2;
	}
	sql_query("update g5_member set mb_brecommend ='".$brecommend."', mb_lr = ".$mb_lr.", mb_brecommend_type = '".$bre_commend_type."' where mb_id='".$mb_id."'");
}
?>