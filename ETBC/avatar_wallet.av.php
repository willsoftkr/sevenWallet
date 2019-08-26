<?php
include_once('./_common.php');

$sql = "select mb_id, spend_btc, DATE_FORMAT(create_date, '%Y/%m/%d') as create_date from pinna_avatar_purchase where origin_mb_no = {$member['mb_no']}";

$sth = sql_query($sql);
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
	$rows[] = $r;
}

print json_encode($rows);
?>
