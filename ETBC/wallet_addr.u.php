<?php
include_once('../common.php');

if($_POST['coin'] == 'btc' ){
	$sql = " update {$g5['member_table']} set btc_my_wallet = '".$_POST['btc_my_wallet']."' where mb_id = '{$member['mb_id']}' ";
}else if($_POST['coin'] == 'eth'){
	$sql = " update {$g5['member_table']} set eth_my_wallet = '".$_POST['eth_my_wallet']."' where mb_id = '{$member['mb_id']}' ";
}
else if($_POST['coin'] == 'rwc'){
	$sql = " update {$g5['member_table']} set rwc_wallet = '".$_POST['rwc_my_wallet']."' where mb_id = '{$member['mb_id']}' ";

}
else if($_POST['coin'] == 'lkc'){
	$sql = " update {$g5['member_table']} set lkc_wallet = '".$_POST['lkc_my_wallet']."' where mb_id = '{$member['mb_id']}' ";
}
sql_query($sql);

$myObj = new stdClass();
echo json_encode($myObj);


?>