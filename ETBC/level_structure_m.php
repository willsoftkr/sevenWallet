<?php
include_once('./_common.php');
?>
<?
    $noo_sales = "SELECT * FROM  `noo2` where mb_id ='".$member[mb_id]."' ORDER BY  `noo2`.`day` DESC ";
	$rst_noo = sql_fetch($noo_sales); 

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

$mbid = $member['mb_id'];
$sql = "select count(mb_recommend) as enroll from g5_member where mb_recommend='$mbid'";
//echo $sql;
$ret = sql_fetch($sql);
$enroll = sql_fetch_array($ret);
$cont = $ret['enroll'];

$placement = $member['mb_brecommend'];
$package_arry = array(1 => 'One Packages', 'Two Packages', 'Three Packages', 'Four Packages', 'Five Packages');
$package_cnt =0;
if($member['it_pool1']>0){
	$my_pool_lv = '<img src="./images/package_1.png" width="50" alt="package 1" />';
	$package_cnt += 1;
}
if($member['it_pool2']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_2.png" width="50" alt="package 1" />';
		$package_cnt += 1;
}	
if($member['it_pool3']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_3.png" width="50" alt="package 1" />';
		$package_cnt += 1;
}	

if($member['it_pool4']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_4.png" width="50" alt="package 1" />';
		$package_cnt += 1;
}	
if($member['it_GPU']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_gpu.png" width="50" alt="package 1" />';
		$package_cnt += 1;
}

?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/level_structure/style_m.css">

	<script>
		$(function() {

			var menu_dropdown = document.getElementsByClassName('lvl');

			for (var i = 0; i < menu_dropdown.length; i++) {
				menu_dropdown[i].onclick = function() {
					this.classList.toggle('lvl-is-open');

					var menu_item = this.nextElementSibling;

					if (menu_item.style.maxHeight) {
						menu_item.style.maxHeight = null;
					} else {
						menu_item.style.maxHeight = menu_item.scrollHeight + "px";
					}
				}
			}
		});
	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div id="overlay">
		<div id="text">
			<h2>Your browser is too small.</h2>
			<p>Level structure view works best on browsers that are at least 1235px wide.</p>
		</div>
	</div>

	<div class="main-container">		
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray level-structure-title">Level Structure</h2> <span class="gray">( 숫자 Total )</span>			
			
			
			

			<div class="lvl-container lvl-1">			
				<div class="lvl">						
					<img src='/images/".등급레벨숫자. ".png' class='pool' />
					<span class="lvl-username">[아이디]</span> 
					<span class="gray">( 보유회원수 )</span>	
					<span class="p_img"><? echo $my_pool_lv?></span>
					<span class="shortcuts">Tree:[ <a href="#">Placement </a> ]</span>								
				</div>
				<div class="lvl-info">
					<div class="profile">
						<h1>Profile</h1>
						<span class="info_tit">Enrolled : </span> <span class="blue">날짜</span> 
						<span class="info_tit">Sponsor : </span> <span class="blue"><?echo $member[mb_recommend]?></span>
						<span class="info_tit">Placement : </span> <span class="blue"><?echo $member[mb_brecommend]?></span>
						<span class="info_tit">Placed in Binary : </span> <span class="blue">날짜</span> 		
					</div>					
					<div class="sales">
						<h1>Sales</h1>
						<span class="info_tit">Total Sales : </span> <span class="blue">$<?echo number_format($rst_noo[noo])?></span> 
						<span class="info_tit">30-Day Sales : </span> <span class="blue">$30일</span>					
						<span class="info_tit">Personal Sales : </span> <span class="blue">$personal</span>						
					</div>
				</div>



					<div class="lvl-container lvl-2">			
						<div class="lvl">						
							<img src='/images/".등급레벨숫자. ".png' class='pool' />
							<span class="lvl-username">[아이디]</span> 
							<span class="gray">( 보유회원수 )</span>	
							<span class="p_img"><? echo $my_pool_lv?></span>
							<span class="shortcuts">Tree:[ <a href="#">Placement </a> ]</span>								
						</div>
						<div class="lvl-info">
							<div class="profile">
								<h1>Profile</h1>
								<span class="info_tit">Enrolled : </span> <span class="blue">날짜</span> 
								<span class="info_tit">Sponsor : </span> <span class="blue"><?echo $member[mb_recommend]?></span>
								<span class="info_tit">Placement : </span> <span class="blue"><?echo $member[mb_brecommend]?></span>
								<span class="info_tit">Placed in Binary : </span> <span class="blue">날짜</span> 		
							</div>					
							<div class="sales">
								<h1>Sales</h1>
								<span class="info_tit">Total Sales : </span> <span class="blue">$<?echo number_format($rst_noo[noo])?></span> 
								<span class="info_tit">30-Day Sales : </span> <span class="blue">$30일</span>					
								<span class="info_tit">Personal Sales : </span> <span class="blue">$personal</span>						
							</div>
				</div>




							<div class="lvl-container lvl-3">			
								<div class="lvl">						
									<img src='/images/".등급레벨숫자. ".png' class='pool' />
									<span class="lvl-username">[아이디]</span> 
									<span class="gray">( 보유회원수 )</span>	
									<span class="p_img"><? echo $my_pool_lv?></span>
									<span class="shortcuts">Tree:[ <a href="#">Placement </a> ]</span>								
								</div>
								<div class="lvl-info">
									<div class="profile">
										<h1>Profile</h1>
										<span class="info_tit">Enrolled : </span> <span class="blue">날짜</span> 
										<span class="info_tit">Sponsor : </span> <span class="blue"><?echo $member[mb_recommend]?></span>
										<span class="info_tit">Placement : </span> <span class="blue"><?echo $member[mb_brecommend]?></span>
										<span class="info_tit">Placed in Binary : </span> <span class="blue">날짜</span> 		
									</div>					
									<div class="sales">
										<h1>Sales</h1>
										<span class="info_tit">Total Sales : </span> <span class="blue">$<?echo number_format($rst_noo[noo])?></span> 
										<span class="info_tit">30-Day Sales : </span> <span class="blue">$30일</span>					
										<span class="info_tit">Personal Sales : </span> <span class="blue">$personal</span>						
									</div>
							</div>		
									<div class="lvl-container lvl-3">			
								<div class="lvl">						
									<img src='/images/".등급레벨숫자. ".png' class='pool' />
									<span class="lvl-username">[아이디]</span> 
									<span class="gray">( 보유회원수 )</span>	
									<span class="p_img"><? echo $my_pool_lv?></span>
									<span class="shortcuts">Tree:[ <a href="#">Placement </a> ]</span>								
								</div>
								<div class="lvl-info">
									<div class="profile">
										<h1>Profile</h1>
										<span class="info_tit">Enrolled : </span> <span class="blue">날짜</span> 
										<span class="info_tit">Sponsor : </span> <span class="blue"><?echo $member[mb_recommend]?></span>
										<span class="info_tit">Placement : </span> <span class="blue"><?echo $member[mb_brecommend]?></span>
										<span class="info_tit">Placed in Binary : </span> <span class="blue">날짜</span> 		
									</div>					
									<div class="sales">
										<h1>Sales</h1>
										<span class="info_tit">Total Sales : </span> <span class="blue">$<?echo number_format($rst_noo[noo])?></span> 
										<span class="info_tit">30-Day Sales : </span> <span class="blue">$30일</span>					
										<span class="info_tit">Personal Sales : </span> <span class="blue">$personal</span>						
									</div>
							</div>
			</div>
			</div>
			</div>







			<div class="lvl-container lvl-two">			
				<div class="lvl">
					<img src="images/5star.png" alt="5 star"> 
					<span class="lvl-username">CaptainAmerica</span> 
					<span class="gray">( 16 )</span>
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-three">			
				<div class="lvl">
					<img src="../images/4star.png" alt="4 star"> 
					<span class="lvl-username">Spiderman</span> 
					<span class="gray">( 16 )</span>
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-four">			
				<div class="lvl">
					<img src="../images/3star.png" alt="3 star"> 
					<span class="lvl-username">CaptainAmerican</span> 
					<span class="gray">( 16 )</span>
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-five">			
				<div class="lvl">
					<img src="../images/2star.png" alt="2 star"> 
					<span class="lvl-username">BlackPanther</span> 
					<span class="gray">( 16 )</span>			
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-six">			
				<div class="lvl">
					<img src="../images/1star.png" alt="1 star"> 
					<span class="lvl-username">DoctorStrange</span> 
					<span class="gray">( 16 )</span>			
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-seven">			
				<div class="lvl">
					<img src="../images/2star.png" alt="2 star"> 
					<span class="lvl-username">Thor</span> 
					<span class="gray">( 16 )</span>
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-eight">			
				<div class="lvl">
					<img src="../images/3star.png" alt="3 star"> 
					<span class="lvl-username">AntMan</span> 
					<span class="gray">( 16 )</span>
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-nine">			
				<div class="lvl">
					<img src="../images/4star.png" alt="4 star"> 
					<span class="lvl-username">Hulk</span> 
					<span class="gray">( 16 )</span>
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-ten">			
				<div class="lvl">
					<img src="../images/5star.png" alt="5 star"> 
					<span class="lvl-username">BlackWidow</span> 
					<span class="gray">( 16 )</span>
					<div class="shortcuts">Tree: 
						[ <a href="#">Sponsor </a> |
						<a href="#">Placement </a> ]
					</div>		</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Enrolled:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Placement:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span></span> 
					</div>
					<div class="card-info-middle">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Package 1:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 2:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 3:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">Package 4:</span> <span class="blue">1</span>, <span class="blue">Purchased: </span> <br>
						<span class="gray">GPU:</span> <span class="blue">0</span>, <span class="blue">Purchased: </span>
					</div>
					<div class="card-info-right">
						<!-- <span class="gray">Mining Pools</span> <br> -->
						<span class="gray">Total Sales:</span> <span class="blue">$9,999</span> <br>
						<span class="gray">30-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">7-Day Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Personal Sales:</span> <span class="blue">$8,888</span> <br>
						<span class="gray">Placed in Binary:</span> <span class="blue">Jan 01, 2018</span> 
					</div>
				</div>
			</div>




		</div>
	</div>

	
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="./script.js"></script>
</body>
</html>
