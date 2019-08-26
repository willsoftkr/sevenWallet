<?php
include_once('./_common.php');
?>

<?
$noo_sales = "SELECT * FROM  `noo2` where mb_id ='".$member[mb_id]."' ORDER BY  `noo2`.`day` DESC ";
$rst_noo = sql_fetch($noo_sales); 

$thirty_sales = "SELECT * FROM  `thirty2` where mb_id ='".$member[mb_id]."' ORDER BY  `day` DESC ";
$rst_thirty = sql_fetch($thirty_sales); 

$bonus_btc = "select sum(benefit) as btc_sum from soodang_pay where mb_id='".$member[mb_id]."' and allowance_name <> 'mining payout (eth)'" ;
$rst_btcB = sql_fetch($bonus_btc); 

$bonus_eth = "select sum(benefit) as eth_sum from soodang_pay where mb_id='".$member[mb_id]."' and  allowance_name = 'mining payout (eth)'" ;
$rst_ethB = sql_fetch($bonus_eth); 

$currency = "select * from pinna_mining_day4 order by day desc limit 1";
$rst = sql_fetch($currency);

$total_bonus = $rst_btcB[btc_sum]*$rst[btcrate];
$total_bonus2 = $rst_ethB[eth_sum]*$rst[ethrate];
$mining_pw_sql = "SELECT * FROM pina_mb_hashpower where mb_id='".$member[mb_id]."'";
$mining_pw = sql_fetch($mining_pw_sql );







/**/
$mbid = $member['mb_id'];

function total_commend($type,$mbid){
$sql = "select count('$type') as enroll from g5_member where $type='$mbid'";
$ret = sql_fetch($sql);
$enroll = sql_fetch_array($sql );
return $ret['enroll']; // 총 추천인수
}

$Total_Referrer = total_commend('mb_recommend',$mbid);  // 총추천인
$Total_Sponsor = total_commend('mb_brecommend',$mbid); //총 후원인




$placement = $member['mb_brecommend'];
$package_arry = array(1 => 'One Package', 'Two Packages', 'Three Packages', 'Four Packages', 'Five Packages');
$package_cnt =0;

if($member['it_pool1']>0){
$my_pool_lv = '<img src="./images/package-1.png" width="50" alt="package 1" />';
$package_cnt += 1;
}
if($member['it_pool2']>0){
$my_pool_lv = $my_pool_lv.'<img src="./images/package-2.png" width="50" alt="package 1" />';
	$package_cnt += 1;
}	
if($member['it_pool3']>0){
$my_pool_lv = $my_pool_lv.'<img src="./images/package-3.png" width="50" alt="package 1" />';
	$package_cnt += 1;
}	

if($member['it_pool4']>0){
$my_pool_lv = $my_pool_lv.'<img src="./images/package-4.png" width="50" alt="package 1" />';
	$package_cnt += 1;
}	

if($member['it_pool5']>0){
$my_pool_lv = $my_pool_lv.'<img src="./images/package-5.png" width="50" alt="package 1" />';
	$package_cnt += 1;
}
if($member['it_GPU']>0){
$my_pool_lv = $my_pool_lv.'<img src="./images/package-gpu.png" width="50" alt="package 1" />';
	$package_cnt += 1;
}


$get_summary = "select * from soodang_calc where mb_id = '{$member['mb_id']}';";
$run_q = sql_query($get_summary);
$soodang_raw = sql_fetch_array($run_q);
/*echo $soodang_raw['mb_id'];
echo $soodang_raw['total_pinnacle'];
echo $soodang_raw['total_mining'];
echo $soodang_raw['pinnacle'];
echo $soodang_raw['prevpoint'];
echo $soodang_raw['withdrawal'];
echo $soodang_raw['mining_btc'];
echo $soodang_raw['now_balance'];
echo
*/
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


$get_summary = "select mb_id, total_pinnacle, total_mining, pinnacle, prevpoint, mining_btc, mining_btc2, mining_btc3, mining_btc4, mining_eth, withdrawal, now_balance, withdrawale
			from soodang_calc where mb_id = '{$member['mb_id']}';";

$run_q = sql_query($get_summary);
$soodang_raw = sql_fetch_array($run_q);
$btc_tot_payout = $soodang_raw['mining_btc'] +$soodang_raw['mining_btc2']+$soodang_raw['mining_btc3']+$soodang_raw['mining_btc4']+$soodang_raw['total_pinnacle'];
$btc_tot_mining = $soodang_raw['mining_btc'] +$soodang_raw['mining_btc2']+$soodang_raw['mining_btc3']+$soodang_raw['mining_btc4'];
$btc_tot_bonus_payout = $soodang_raw['total_pinnacle'];

$total_mining_sudang = $member['it_pool1_profit']+$member['it_pool2_profit']+$member['it_pool3_profit']+$member['it_pool4_profit'];
$eth_wallet = $member['it_poolg_profit'];
$btc_wallet = $total_mining_sudang+$member[mb_balance];

$total_mining_sudang = $member['it_pool1_profit']+$member['it_pool2_profit']+$member['it_pool3_profit']+$member['it_pool4_profit'];
$eth_wallet = $member['it_poolg_profit'];
$btc_wallet = $total_mining_sudang+$member[mb_balance];

/*
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, "https://api.blockchain.info/charts/market-price?timespan=30days&format=json");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec ($ch);
curl_close ($ch);
$obj = json_decode($result);
*/

$sql = "select * from pinna_mining_day4 order by day desc limit 7";
$list = sql_query($sql);
$day_arr = array();
$eth_arr = array();
$btc_arr = array();
for($i=7; $row = sql_fetch_array($list); $i--){
//$day_arr[$i] = '$row[day]';
array_push($day_arr, $row['day']);
array_push($btc_arr, $row['btcrate']);
array_push($eth_arr, $row['etcrate']);

};
for($i=30; $row = sql_fetch_array($list); $i--){
array_push($day_arr_sales, $row['day']);
}

$day_arr = array_reverse($day_arr);
$btc_arr = array_reverse($btc_arr);
$eth_arr = array_reverse($eth_arr);



$sql_recom = "select mb_open_date, count( from g5_member where recommend = '".$member['mb_id']."' group by mb_open_date order by mb_open_date desc;";
$list_recom = sql_query($sql_recom);
for($i=1; $row_recom = sql_fetch_array($list_recom); $i++){
//$day_arr[$i] = '$row[day]';
array_push($day_arr, $row_recom['mb_open_date']);    
array_push($btc_arr, $row_recom['btcrate']);

}

$today = date("Y-m-d");
$enroll_date = array();
for($j=1;$j<8;$j++){

$target_day = strtotime("$today - 1 days");
$today = date( "Y-m-d", $target_day);
array_push($enroll_date,  date( "Y-m-d", $target_day));
}

/*추천인 누적 매출*/

$referrer_sql = "select day,noo from bnoo2 where mb_id ='".$member['mb_id']."' ORDER BY day desc limit 1";
$referrer_result = sql_fetch($referrer_sql);
$referrer_sales = $referrer_result['noo'];
if(!$referrer_sales){
echo $referrer_sales = 0;
}else{
$referrer_sales = Number_format($referrer_sales,3);
}


$sponsor_sql = "select day,noo from noo2 where mb_id ='".$member['mb_id']."' ORDER BY day desc limit 1";
$sponsor_result = sql_fetch($sponsor_sql);
$sponsor_sales = $sponsor_result['noo'];
if(!$sponsor_sales){
echo $sponsor_sales = 0;
}else{
$sponsor_sales = number_format($sponsor_sales,3);
}
?>


<!-- HTML -->

<!DOCTYPE html>
<html>

<head>
<?include_once('common_head.php')?>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>-->

<link rel="stylesheet" href="css/style.css">

<script>
// 도넛 차트
/*
var randomScalingFactor = function () {
  return Math.round(Math.random() * 100);
};
window.chartColors = {
  red: 'rgb(255, 99, 132)',
  orange: 'rgb(255, 159, 64)',
  gold: 'rgb(255, 205, 00)',
  green: 'rgb(75, 192, 192)',
  blue: 'rgb(54, 162, 235)',
  purple: 'rgb(153, 102, 255)',
  grey: 'rgb(201, 203, 207)'
};

var da1 = "<?=$btc_wallet?>";
var da2 = "<?=$eth_wallet?>";
var myBalancesConfig = {
  type: 'doughnut',
  data: {
	datasets: [{
	  data: [
		da1,
		da2
	  ],
	  backgroundColor: [
		window.chartColors.gold,
		window.chartColors.purple,
	  ],
	  label: 'Dataset 1'
	}],
	labels: [
	  'BTC',
	  'ETH'
	]
  },
  options: {
	responsive: true,
	legend: {
	  position: 'top',
	},
	title: {
	  display: false,
	  text: 'Chart.js Doughnut Chart'
	},
	animation: {
	  animateScale: true,
	  animateRotate: true
	}
  }
};
*/
// bar 차트 
/*
var MONTHS = JSON.parse('<? echo json_encode($day_arr);?>');

var priceChartsConfig = {
  type: 'line',
  data: {
	labels: MONTHS,
	datasets: [{
	  label: 'BTC Price',
	  backgroundColor: window.chartColors.red,
	  borderColor: window.chartColors.red,
	  data: JSON.parse('<?=json_encode($btc_arr);?>'),
	  fill: false,
	}, {
	  label: 'ETC Price',
	  fill: false,
	  backgroundColor: window.chartColors.blue,
	  borderColor: window.chartColors.blue,
	  data: JSON.parse('<? echo json_encode($eth_arr);?>'),
	}]
  },
  options: {
	responsive: true,
	title: {
	  display: false,
	  text: 'Chart.js Line Chart'
	},
	tooltips: {
	  mode: 'index',
	  intersect: false,
	},
	hover: {
	  mode: 'nearest',
	  intersect: true
	},
	scales: {
	  xAxes: [{
		display: true,
		scaleLabel: {
		  display: false,
		  labelString: 'Month'
		}
	  }],
	  yAxes: [{
		display: true,
		scaleLabel: {
		  display: false,
		  labelString: 'Value'
		}
	  }]
	}
  }
};
*/
// SalesVolumeCharts 차트 

var MONTHS = JSON.parse('<? echo json_encode($day_arr);?>');
/*
var salesVolumeChartsConfig = {
  type: 'line',
  data: {
	labels: MONTHS,
	datasets: [{
	  label: 'Sales Volume ($)',
	  backgroundColor: window.chartColors.gold,
	  borderColor: window.chartColors.gold,
	  data: JSON.parse('<?=json_encode($btc_arr);?>'),
	  fill: false,
	}]
  },
  options: {
	responsive: true,
	title: {
	  display: false,
	  text: 'Chart.js Line Chart'
	},
	tooltips: {
	  mode: 'index',
	  intersect: false,
	},
	hover: {
	  mode: 'nearest',
	  intersect: true
	},
	scales: {
	  xAxes: [{
		display: true,
		scaleLabel: {
		  display: false,
		  labelString: 'Month'
		}
	  }],
	  yAxes: [{
		display: true,
		scaleLabel: {
		  display: false,
		  labelString: 'Value'
		}
	  }]
	}
  }
};

$(function () {

  var myBalances = document.getElementById('myBalances').getContext('2d');
  window.myDoughnut = new Chart(myBalances, myBalancesConfig);

  var priceCharts = document.getElementById('priceCharts').getContext('2d');
  window.myLine = new Chart(priceCharts, priceChartsConfig);

  var salesVolumeCharts = document.getElementById('salesVolumeCharts').getContext('2d');
  window.myLine = new Chart(salesVolumeCharts, salesVolumeChartsConfig);
});
*/
</script>

</head>

<!-- BODY -->

<body>

<?include_once('mypage_head.php')?>

<div class="main-container">
<div id="body-wrapper" class="dashboard-wrapper">
  <div class="overview-container">
	<!--XX  <h2 class="gray section-title" data-i18n="dashboard.overview">Overview</h2> -->

	<div class="overview-row">

	  <!-- //BALANCE -->
	  <section class="wallet">
		<div class="wallet_inner">


		  <h2 class="greeting"><img src="./images/<?echo $member_lvimg;?>" class="user-dropdown-rank"
			  alt="<?echo $member_lvString?>"> <span>
			  <?echo $member['mb_id']?> </span> 
			 <!--xx greeting <span data-i18n="dashboard.greeting"> 님 안녕하세요</span> -->
			</h2>

		  <h3 class="balance">  <span data-i18n="dashboard.totalbal">Total Balance</span> <span class="total_balance">
			  <?=$EOS_TOTAL?> </span> EOS</h3>

		  <div class="coin_list">
			<div class="coin_img">
			  <img src="./images/eos_logo_c.png" alt="eos_symbol">
			  <p class="coin_name">EOS</p>
			</div>
			<div class="eos_balance">
			  <p> <?=$EOS_TOTAL?> EOS</p>
			</div>
		  </div>

		  <div class="coin_list">
			<p class="eos_up_name">UPSTAIRS</p>
			<div class="eos_up_balance">
			  <p> <?=$EOS_UPSTAIR;?> EOS</p>
			</div>
		  </div>


		</div>
	  </section>
	  <!-- //BALANCE -->


	  <style>
		.incard {
		  width: 100%;
		  height: 3.5em;
		  border-radius: 3px;
		  text-align: center;
		  border-radius: 5px;
		}

		.incard dt,
		.incard dd {
		  min-height: 3em;
		  line-height: 3em;
		  display: inline-block;
		  border-radius: 5px;
		}

		.incard dt {
		  float: left;
		  clear: both;
		  font-weight: 400;
		  width: 60%;
		  letter-spacing: -1px;
		  background: #f1f1f1;
		  background: rgba(255, 255, 255, 0.6);
		}

		.incard dd {
		  width: 40%;
		  font-weight: 800;
		  background: white;
		  border-left: 3px solid #113a56;
		}
	  </style>
	  <!--데이터 정리용 -->
	  <div class="overview-card my_info shadow last-in-row">


		<!--xx   <tr>
								<th>등급</th>
								<td>
									<img src="./images/<?echo $member_lvimg;?>" class="user-dropdown-rank"
										alt="<?echo $member_lvString?>">
								</td>
							</tr>
							-->

		<div class="incard">
		  <dt data-i18n="dashboard.referrer">Referrer</dt>
		  <dd>
			<?echo $member['mb_recommend'] ? $member['mb_recommend']:'-'; ?>
		  </dd>
		</div>
		<div class="incard">
		  <dt data-i18n="dashboard.sponsor">My Sponsor</dt>
		  <dd>
			<?echo $member['mb_brecommend'] ? $member['mb_brecommend'] : '-'; ?>
		  </dd>
		</div>

		<div class="incard">
		  <dt data-i18n="dashboard.referrer_count">Total Referrer</dt>
		  <dd>
			<!--<?echo $recom_rst['recom_cnt'];?>-->
			<?=$Total_Referrer?>
		  </dd>
		</div>
		<div class="incard">
		  <dt data-i18n="dashboard.sponsor_count">Total Sponsor</dt>
		  <dd>
			<!--<?echo $now_member['mb_b_child'];?>-->
			<?=$Total_Sponsor?>
		  </dd>
		</div>

		<div class="incard">
		  <dt data-i18n="dashboard.total_my_money">Total My Sales</dt>
		  <dd><?=$EOS_UPSTAIR?></dd>
		</div>
		<div class="incard">
		  <dt data-i18n="dashboard.total_referrer_money">Total Referrer Sales</dt>
		  <dd><?=$referrer_sales?></dd>
		</div>
		<div class="incard">
		  <dt data-i18n="dashboard.total_sponsor_money">Total Sponsor Sales</dt>
		  <dd><?=$sponsor_sales?></dd>
		</div>
		<div class="incard">
		  <dt data-i18n="dashboard.total_bonus">Total Bonus</dt>
		  <dd><?=$EOS_BENEFIT_TOTAL?></dd>
		</div>
		<div class="incard">
		  <dt data-i18n="dashboard.for_out">for OUT( 100% )</dt>
		  <dd><?=$EOS_OUT?>%</dd>
		</div>

	  </div>

	  <!--xx
				<div class="overview-card shadow">
					<img src="./images/total_sales.png" width="50" alt="total sales">
					<h5 class="blue"> $
						<?echo number_format($rst_noo[noo])?>
					</h5>
					<h6 class="gray" data-i18n="dashboard.volume">Total Sales Volume</h6>
				</div>
-->
	  <!--xx
				<div class="overview-card shadow">
					<img src="./images/eth_hash.png" width="40" alt="eth hash">
					<h5 class="blue">
						<?echo $mining_pw[pool_gpu_hashp]?> MH/s</h5>
					<h6 class="gray" data-i18n="dashboard.ethHash">Total ETH Hashpower</h6>
				</div>
-->
	  <!--xx
				<div class="overview-card shadow">
					<img src="./images/total_sales.png" width="50" alt="total sales">
					<h5 class="blue">$
						<?echo number_format($rst_thirty[thirty])?>
					</h5>
					<h6 class="gray" data-i18n="dashboard.volume30"></h6>
				</div>

				<div class="overview-card shadow last-in-row">
					<img src="./images/bonus_earned.png" width="50" alt="bonus earned">
					<h5 class="blue">
						<?echo number_format($total_bonus+$total_bonus2)?>
						EOS
					</h5>
					<h6 class="gray" data-i18n="dashboard.bonus">Total Bonus Earned</h6>
				</div>

-->

	</div>
	<!-- xx
			<div class="my-balances-container shadow" style="float:left;">
				<h3 data-i18n="dashboard.balance">
					My Balances
				</h3>
			  
				<div style="width:350px;margin:0 auto;">
					<canvas id="myBalances"></canvas>
				</div>
				<br>
			</div>
-->
	<!-- xx
			<div class="price-chart-container shadow " style="float:right;">
				<h3 data-i18n="dashboard.chart">
					Price Charts
				</h3>
			   
				<div style="width:450px;margin:0 auto;margin-top:65px;">
					<canvas id="priceCharts"></canvas>
				</div>
			</div>
-->
  </div>
  <!-- 
		<div class="my-business-status-container">
			<h2 class="gray section-title m_b_s" data-i18n="dashboard.status">My Business Status</h2>
			<div class="business-status-row">
				<div class="card shadow business-status-card">
					<div class="card-body">
						<img src="./images/sponsor.png" width="50" alt="my sponsor" /> <br>
						<span class="card-info blue">
							<?echo $member[mb_recommend]?></span> <br>
						<span class="gray" data-i18n="dashboard.sponsor">My Sponsor</span>
					</div>
				</div>
				<div class="card shadow business-status-card">
					<div class="card-body">
						<div class="package-img-container">
							<? echo $my_pool_lv?>
						</div>
						<span class="card-info blue">
							<?echo $package_arry[$package_cnt]?></span> <br>
						<span class="gray" data-i18n="dashboard.packages">My Packages</span>
					</div>
				</div>
				<div class="card shadow business-status-card last-in-row">
					<div class="card-body">
						<img src="./images/binary_placement.png" width="50" alt="binary placement"> <br>
						<span class="card-info blue">
							<?echo $member[mb_brecommend]?></span> <br>
						<span class="gray" data-i18n="dashboard.binary">Binary Placement</span>
					</div>
				</div>
			</div>

			<div class="business-status-row">
				<div class="card shadow business-status-card">
					<div class="card-body">
						<img src="./images/btc_payout.png" width="50" alt="btc payout"> <br>
						<span class="card-info blue">
							<?=$btc_tot_bonus_payout?> </span> <br>
						<span class="gray" data-i18n="dashboard.bonusBtc">Total Bonus BTC Payout</span>
					</div>
				</div>
				<div class="card shadow business-status-card">
					<div class="card-body">
						<img src="./images/btc_mining_payout.png" width="50" alt="btc mining payout"> <br>
						<span class="card-info blue">
							<?=$btc_tot_mining ?></span> <br>
						<span class="gray" data-i18n="dashboard.miningPayout">Total BTC Mining Payout</span>
					</div>
				</div>
				<div class="card shadow business-status-card last-in-row">
					<div class="card-body">
						<img src="./images/btc_withdrawn.png" width="50" alt="btc withdrawn"> <br>
						<span class="card-info blue">
							<?=$soodang_raw['withdrawal']?></span> <br>
						<span class="gray" data-i18n="dashboard.Withdrawal">Total BTC Withdrawal</span>
					</div>
				</div>
			</div>

			<div class="business-status-row">
				<!--div class="card shadow business-status-card">
					<div class="card-body">
						<img src="./images/eth_payout.png" width="50" alt="eth payout"> <br>
						<span class="card-info blue">
							<?=$soodang_raw['mining_eth']?></span> <br>
						<span class="gray" data-i18n="dashboard.ethPayout">Total Bonus ETH Payout</span>
					</div>
				</div-->
  <!--
				<div class="card shadow business-status-card">
					<div class="card-body">
						<img src="./images/eth_mining_payout.png" width="50" alt="eth mining payout"> <br>
						<span class="card-info blue">
							<?=$soodang_raw['mining_eth']?></span> <br>
						<span class="gray" data-i18n="dashboard.ethMining">Total ETH Mining Payout</span>
					</div>
				</div>
				<div class="card shadow business-status-card last-in-row">
					<div class="card-body">
						<img src="./images/eth_withdrawn.png" width="50" alt="eth withdrawn"> <br>
						<span class="card-info blue">
							<?=$soodang_raw['withdrawale']?></span> <br>
						<span class="gray" data-i18n="dashboard.ethWithdrawal">Total ETH Withdrawal</span>
					</div>
				</div>

			</div>
		</div>
-->
</div>
</div>




</body>

</html>
