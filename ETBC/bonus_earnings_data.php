<?php
	include_once('./_common.php');

	$sth = sql_query("SELECT DATE_FORMAT(day, '%b %d') as Day, DATE_FORMAT(day, '%Y%m%d') as dt, round(sum(benefit),8) as benefit FROM soodang_pay WHERE mb_id = '{$member['mb_id']}' and day between '".$_GET['startDate']." 00:00:00' AND '".$_GET['endDate']." 23:59:59' GROUP by day ORDER BY day DESC");
	$rows = array();
	while($r = mysqli_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);

?>