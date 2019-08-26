<?php
include_once('./_common.php');

$page = $_GET['page'];

$sql = " select count(*) as cnt from soodang_pay WHERE mb_id = '".$member['mb_id']."' ";

$condition .=  " and allowance_name = 'Avatar' ";

if($_GET['fromDate']) {
	$condition .=  " and day >= str_to_date('".$_GET['fromDate']."','%d.%m.%Y') ";
}
if($_GET['toDate']) {
	$condition .=  " and day <= str_to_date('".$_GET['toDate']."','%d.%m.%Y') ";
}

$sql .= $condition;
// print $sql;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$rows = 12;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql1 = " select * from (
			select 
				day,
				date_format(day, '%d.%m.%Y') as date, 
				avatar_rate, 
				benefit_usd,
				FORMAT(benefit,8) as benefit, 
				exchange_rate
			from soodang_pay where mb_id = '{$member['mb_id']}' $condition
		) a  order by a.day desc ";
$sql1 .= " limit {$from_record}, {$rows} ";

$sth = sql_query($sql1);
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
	$rows[] = $r;
}

$result = new stdClass();
$result -> totalPage = $total_page;
$result -> totalCnt = $total_count;
$result -> page = $page;
$result -> list = $rows;
$result -> sql = $sql;
$result -> sql1 = $sql1;

print json_encode($result);
?>
