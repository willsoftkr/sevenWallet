<?php
include_once('./_common.php');

header('Content-Type: application/json');

$eth = sql_fetch("select mb_no, eth_addr, eth_key from g5_member where mb_no = {$member[mb_no]}");
if(!$eth['eth_addr']){
	sql_query("update g5_member set eth_addr = '{$_POST['address']}', eth_key = '{$_POST['privateKey']}' where mb_no = {$member[mb_no]} ");
	print json_encode(array('eth_addr' => $_POST['address'], 'eth_key' => $_POST['privateKey']));
}else{
	print json_encode(array('eth_addr' => $eth['eth_addr'], 'eth_key' => $eth['eth_key']));
}

?>