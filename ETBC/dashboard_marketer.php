<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8" />
	<title>FIJI MINING</title>
	
	<meta property="og:type" content="article" />
	<meta property="og:title" content="FIJI" />
	<meta property="og:url" content="" />
	<meta property="og:description" content="." />
	<meta property="og:site_name" content="" />
	<meta property="og:image" content="FIJI Mining Have" />
	<meta property="og:image:width" content="800" />
	<meta property="og:image:height" content="400" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="shortcut icon" href="http://pinnacle_mining.qtorrent.co.kr/img/favicon.ico">
	<!-- css연결 -->
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/adm.css">
	<style>
		table.regTb {width:100%;table-layout:fixed;border-collapse:collapse;margin: 20px 0 0 0;}
		table.regTb {border-top:solid 1px #777;}
		table.regTb th,
		table.regTb td {padding:4px 0 4px 10px;border-bottom:solid 1px #ddd;line-height:28px;font-size:12px;}
		table#list_writing.regTb tbody td {cursor: pointer;}
		table#list_writing.regTb tbody tr:hover td {background-color:#ccc;}
		table.regTb td.mid {text-align: center;}
		table.regTb th {font-weight:normal;font-family:"nngdb";font-size:12px;color:#444;background-color:#f5f5f5;}

		.pg_wrap {margin: 15px;}

		/* 페이징 */
		.pg_wrap {clear:both;text-align:center}
		.pg_wrap a {padding:0;margin:0;display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;width:33px;height:33px;line-height:33px;color:#000;font-size:14px;border:solid 1px #ddd;margin-right:3px;background-color:#fff;}
		.pg_page, .pg_current {padding:0;margin:0;display:inline-block;*display:inline;*zoom:1;margin-right:-4px;*margin-right:0;width:33px;height:33px;line-height:33px;color:#000;font-size:16px;margin-right:3px;background-color:#555;border:solid 1px #555;}
		.pg a:focus, .pg a:hover {background-color:#bbb;}
		.pg_page {background:#e4eaec;text-decoration:none}
		.pg_start, .pg_prev {/* 이전 */}
		.pg_end, .pg_next {/* 다음 */}
		.pg_current {display:inline-block;margin:0 4px 0 0;background:#333;color:#fff;font-weight:normal}

		.msg_sound_only, .sound_only {display:inline-block !important;position:absolute;top:0;left:0;margin:0 !important;padding:0 !important;width:1px !important;height:1px !important;font-size:0;line-height:0;border:0 !important;overflow:hidden !important}
	</style>
	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>

</head>
<body>
<?php include_once('mypage_head.php')?>
<?php include_once('mypage_left.php')?>
<?php

	$sql_condition = " and A.mb_mprecommend = '".$member['mb_id']."' ";

	$sql = " select count(*) as cnt, sum(round(ifnull(commission/usdbtc,0),8)) as coin from mp_soodang A inner join g5_member M on A.mb_id = M.mb_id WHERE 1=1 ";
	$sql .= $sql_condition;
	$row = sql_fetch($sql);
	$tot_cnt = $row['cnt'];
	$coin = $row['coin'];
	// echo $sql;
	$rows = $config['cf_page_rows'];
	$total_page  = ceil($tot_cnt / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = "select * from mp_soodang A inner join g5_member M on A.mb_id = M.mb_id WHERE 1=1 ";
	$sql .= $sql_condition;

	$sql .= " order by create_dt desc ";
	$sql .= " limit {$from_record}, {$rows} ";

	$list = sql_query($sql);

	$startDate =  date("Y-m-d", mktime(0, 0, 0, intval(date('m')), 1, intval(date('Y'))  )); // 이번달 1일 
	$endDate = date("Y-m-d", mktime(0, 0, 0, intval(date('m'))+1, 0, intval(date('Y'))  )); // 이번달 말일 
	
	$sql = "select count(*) as cnt, sum(round(ifnull(commission/usdbtc,0),8)) as coin from mp_soodang A WHERE create_dt between '".$startDate."' and '".$endDate."' ";
	$sql .= $sql_condition;
	$row = sql_fetch($sql,true);
	$tot_cnt_cur = $row['cnt'];
	$coin_cur = $row['coin'];
	
	echo $startDatePre = date("Y-m-d", mktime(0, 0, 0, intval(date('m'))-1, 1, intval(date('Y'))  )); // 지난달 1일 
	echo "<br>";
	echo $endDatePre = date("Y-m-d", mktime(0, 0, 0, intval(date('m')), 0, intval(date('Y'))  )); // 지난달 1일 
	echo "<br>";
	 $sql = "select count(*) as cnt, sum(round(ifnull(commission/usdbtc,0),8)) as coin from mp_soodang A WHERE create_dt between '".$startDatePre."' and '".$endDatePre."' ";
	$sql .= $sql_condition;
	$row = sql_fetch($sql,true);
	echo $sql;
	$tot_cnt_pre = $row['cnt'];
	$coin_pre = $row['coin'];
?>
	<div id="content">
		<br>
		<p>총 유치 회원수 : <?php echo $tot_cnt;?> 명</p>
		<p>전월 회원수 : <?php echo $tot_cnt_pre;?> 명</p>
		<p>금월 회원수 : <?php echo $tot_cnt_cur;?> 명</p>
		<br>
		<p>총 커미션 : <?php echo $coin;?> BTC</p>
		<p>전월 커미션 : <?php echo $coin_pre;?> BTC</p>
		<p>금월 커미션 : <?php echo $coin_cur;?> BTC</p>
		
		<br>
		<table cellspacing="0" cellpadding="0" border="0" class="regTb">
			<colgroup>
				<col style="width:50px;"/>
				<col style="width:200px;"/>
				<col style="width:200px;"/>
				<col style="width:200px;"/>
				<col style="width:auto;"/>
			</colgroup>
			<thead>
				<th>No</th>
				<th>가입날짜</th>
				<th>아이디</th>
				<th>이름</th>
				<th>커미션($)</th>
				<th>커미션(BTC) </th>
				<th>USD/BTC</th>
				
			</thead>
			<tbody>
			<?for ($i=0; $row=sql_fetch_array($list); $i++) {?>
				<tr>
					<td align='center'><?=$row[idx]?></td>
					<td align='center'><?=$row[create_dt]?></td>
					<td align='center'><?=$row[mb_id]?></td>
					<td align='center'><?=$row[first_name].' '.$row[last_name]?></td>
					<td align='center'><?=$row[commission]?></td>
					<td align='center'><?=round($row[commission]/$row[usdbtc],8)?></td>
					<td align='center'><?=$row[usdbtc]?></td>
				</tr>
			<?}?>
			</tbody>
		</table>

  


  
	</div>
	<?include_once('mypage_footer.php')?>
</body>
</html>
