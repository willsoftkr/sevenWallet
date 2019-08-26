<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8" />
	<title>PINNACLE MINING</title>
	
	<meta property="og:type" content="article" />
	<meta property="og:title" content="Pinnacle" />
	<meta property="og:url" content="" />
	<meta property="og:description" content="." />
	<meta property="og:site_name" content="" />
	<meta property="og:image" content="Pinnacle Mining Have" />
	<meta property="og:image:width" content="800" />
	<meta property="og:image:height" content="400" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

	<link rel="shortcut icon" href="http://pinnacle_mining.qtorrent.co.kr/img/favicon.ico">
	<!-- css연결 -->
	<!-- <link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/adm.css"> -->
	<!-- <link rel="stylesheet" href="css/dashboard.css"> -->
	<link rel="stylesheet" href="css/new_template.css">
	
	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>

<?
$mbid = $member['mb_id'];
$sql = "select count(mb_recommend) as enroll from g5_member where mb_recommend='$mbid'";
//echo $sql;
$ret = sql_fetch($sql);
$enroll = sql_fetch_array($ret);
$cont = $ret['enroll'];

$placement = $member['mb_brecommend'];
if($member['mb_level']==10){
	$member_lv='admin';
}
else if($member['mb_level']==9){
	$member_lv='manager';
}
else if($member['mb_level']==8){
	$member_lv='6star';
}
else if($member['mb_level']==7){
	$member_lv='5star';
}
else if($member['mb_level']==6){
	$member_lv='4star';
}
else if($member['mb_level']==5){
	$member_lv='3star';
}
else if($member['mb_level']==4){
	$member_lv='2star';
}
else if($member['mb_level']==3){
	$member_lv='1star';
}
else if($member['mb_level']==2){
	$member_lv='0star';
}
else if($member['mb_level']==1){
	$member_lv='Miner';
}

if($member['it_pool1']>0){
	$my_pool_lv = '<img src="../img/P1.png" >';
}
if($member['it_pool2']>0){
	$my_pool_lv = $my_pool_lv.'<img src="../img/P2.png" >';
}	
if($member['it_pool3']>0){
	$my_pool_lv = $my_pool_lv.'<img src="../img/P3.png" >';
}	

if($member['it_pool4']>0){
	$my_pool_lv = $my_pool_lv.'<img src="../img/P4.png" >';
}	
if($member['it_GPU']>0){
	$my_pool_lv = $my_pool_lv.'<img src="../img/P5.png">';
}

?>

<?

$get_summary = "select mb_id, total_pinnacle, total_mining, pinnacle, prevpoint, mining_btc, withdrawal, now_balance from soodang_calc where mb_id = '{$member['mb_id']}';";
$run_q = sql_query($get_summary);
$soodang_raw = sql_fetch_array($run_q);

 $total_soodang = $soodang_raw['total_pinnacle']+$soodang_raw['prevpoint'];
/*## calendar ################################################*/
/*## calendar ################################################*/
// 1. 총일수 구하기
$year = ($_GET['year'])? $_GET['year'] : date( "Y" ); 
$month = ($_GET['month'])? $_GET['month'] : date( "m" ); 
$mktime = mktime( 0, 0, 0, $month, 1, $year ); 
$last_day = date("t", $mktime);
// 2. 시작요일 구하기
$start_week = date("w", strtotime($year."-".$month."-01"));
// 3. 총 몇 주인지 구하기
$total_week = ceil(($last_day + $start_week) / 7);
// 4. 마지막 요일 구하기
$last_week = date('w', strtotime($year."-".$month."-".$last_day));
?>

<?
if ($month == 1) {
	$prv_year = $year - 1;
	$prv_month = 12;
	$nxt_year = $year;
	$nxt_month = $month + 1;
} else if ($month == 12) {
	$prv_year = $year;
	$prv_month = $month - 1;
	$nxt_year = $year + 1;
	$nxt_month = 1;
} else {
	$prv_year = $year;
	$prv_month = $month - 1;
	$nxt_year = $year;
	$nxt_month = $month + 1;
}
?>
</head>
<body>
	<?include_once('mypage_head2.php')?>
	<?include_once('mypage_left2.php')?>
	
	<div class="main-container">
		<div class="overview-container">
			<h2 class="gray">Overview</h2>

			<div class="congrats-container shadow">
				<div class="congrats-text">					
					<h2>Congratulations TonyStark!</h2>
					<p>Your rank is now 5 star! Keep up your earnings and <br> referrals to level up even more!</p>
				</div>
				<div class="congrats-rank">
					<img src="images/5star.png" class="congrats-rank" alt="5 star rank">
					<span><strong>5 STAR</strong><span>
				</div>
			</div>

			<div class="total-sales-volume-container shadow">
				<span class="total-sales-volume">$246,000</span>
				<h5 class="gray">
					Total Sales Volume
				</h5> <br>
				<i class="fas fa-long-arrow-alt-up"></i> <span class="total-sales-volume-pct">13.2%</span>
			</div>

			<div class="total-sales-volume-container shadow">
				<span class="total-sales-volume">1,337</span>
				<h5 class="gray">
					Total Pools Sold
				</h5> <br>
				<i class="fas fa-long-arrow-alt-up"></i> <span class="total-sales-volume-pct">9.6%</span>
			</div>

			<br>

			<div class="my-balances-container shadow">
				<h2>
					My Balances
				</h2>
				<img src="https://via.placeholder.com/400x300"> <br>
				(Place donut chart here ^)
			</div>

			<div class="price-chart-container shadow">
				<h2>
					Price Charts
				</h2>
				<img src="https://via.placeholder.com/400x300"> <br>
				(Place price chart here ^)
			</div>
		</div>

		<div class="my-business-status-container">
			<h2 class="gray">My Business Status</h2>
			<div class="business-status-row">
				<div class="card shadow business-status-card">
				  <div class="card-body">
				  	<img src="images/sponsor.png" width="50" alt="my sponsor" /> <br>
				    <span class="card-info blue">angel7682</span> <br>
				    <span class="gray">My Sponsor</span>
				  </div>
				</div>
				<div class="card shadow business-status-card">
				  <div class="card-body">
				  	<img src="images/package_1.png" width="50" alt="package 1" />
				  	<img src="images/package_2.png" width="50" alt="package 2" />
				  	<img src="images/package_3.png" width="50" alt="package 3" />
				  	<img src="images/package_4.png" width="50" alt="package 4" />
				  	<img src="images/package_gpu.png" width="50" alt="package gpu" />
				  	 <br>
				  	<span class="card-info blue">Five Packages</span> <br>
				    <span class="gray">My Packages</span>
				  </div>
				</div>
				<div class="card shadow business-status-card">
				  <div class="card-body">
				  	<img src="images/binary_placement.png" width="50" alt="binary placement"> <br>
				    <span class="card-info blue">grace77</span> <br>
				    <span class="gray">Binary Placement</span>
				  </div>
				</div>
			</div>

			<div class="business-status-row">
				<div class="card shadow business-status-card">
				  <div class="card-body">
				  	<img src="images/bonus_payout.png" width="50" alt="bonus payout"> <br>
				    <span class="card-info blue">5.2849149</span> <br>
				    <span class="gray">Total Bonus BTC Payout</span>
				  </div>
				</div>
				<div class="card shadow business-status-card">
				  <div class="card-body">
				  	<img src="images/mining_payout.png" width="50" alt="mining payout"> <br>
				  	<span class="card-info blue">3.1415926</span> <br>
				    <span class="gray">Total BTC Mining Payout</span>
				  </div>
				</div>
				<div class="card shadow business-status-card">
				  <div class="card-body">
				  	<img src="images/withdraw_btc.png" width="50" alt="withdraw btc"> <br>
				    <span class="card-info blue">0.0025234</span> <br>
				    <span class="gray">Total BTC Withdrawn</span>
				  </div>
				</div>
			</div>
		</div>

		<div class="enrollment-summary-container">
			<h2 class="gray">Enrollment Summary</h2>

			<div class="enrollmentary-summary-row">

				<div class="card shadow enrollment-summary-card enrollment-chart">
				  <div class="card-body">
				    <h5>Enrollments</h5>
				    <img src="https://via.placeholder.com/250x200"> <br>
						(Place bar chart here ^)
				  </div>
				</div>

				<div class="card shadow enrollment-summary-card">
				  <div class="card-body">
				  	
				  	<div class="personal-enrollment-container">
				  		<div class="personal-enrollment-circle">
				  			<span class="enrollment-number">42</span>
				  		</div>
				  		<h4>Total Personal Enrollments</h4>
				  	</div>

				  	<div class="card enrollment-stat">
						  <div class="card-body">
						  	<span class="enrollment-stat-info">79</span> <br>
						    <span class="gray">Total Members in Binary Tree</span>
						  </div>
						</div>

						<div class="card enrollment-stat">
						  <div class="card-body">
						  	<span class="enrollment-stat-info">Thor</span> <br>
						    <span class="gray">Newest Enrollment</span>
						  </div>
						</div>

						<div class="card enrollment-stat">
						  <div class="card-body">
						  	<span class="enrollment-stat-info">Spiderman</span> <br>
						    <span class="gray">Newest Member of Binary</span>
						  </div>
						</div>

				  </div>
				</div>

			</div>
		</div>

		<div class="sales-volume-summary-container">
			<h2 class="gray">Sales Volume Summary</h2>
			<div class="card shadow sales-volume-chart">
				  <div class="card-body">
				    <img src="https://via.placeholder.com/1000x300"> <br>
						(Place line graph chart here ^)
				  </div>
				</div>
		</div>

	</div>
</body>
	
</html>
