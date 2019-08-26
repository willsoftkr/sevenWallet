<?php
	include_once('./_common.php');

	sql_query("update g5_member set g5_member.avatar_rate = {$_POST['avatar_rate']} WHERE mb_id = '{$member[mb_id]}'");

	$result = new stdClass();
	$result->avatar_rate = $_POST['avatar_rate'];
	
	print json_encode($result);
?>
