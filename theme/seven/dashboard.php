<?
	include_once('./_common.php');
	include_once(G5_THEME_PATH.'/_include/gnb.php'); 
	//print_r($member);
	
	include_once(G5_THEME_PATH.'/_include/wallet.php'); 
	include_once(G5_PATH.'/lib/shop.lib.php'); 
	include_once(G5_THEME_PATH.'/_include/shop.php'); 

	//매출액
	$mysales = $member['mb_deposit_point'];

	// 공지사항
	$notice_sql = "select * from g5_write_notice where wr_1 = '1' order by wr_datetime desc";
	$notice_sql_query = sql_query($notice_sql);
	$notice_result_num = sql_num_rows($notice_sql_query);
	
?>

<!-- bxslider -->
	<link rel="stylesheet" href="<?=G5_THEME_URL?>/_common/css/jquery.bxslider.css">
	<script src="<?=G5_THEME_URL?>/_common/js/jquery.bxslider.min.js"></script>

		<div class="v_center dash_contents">

			
			
				<section class="dash_news">
				<?if($notice_result_num > 0){ ?>
					<h5>
						<span class="title" data-i18n='dashboard.공지사항' >Notification</span> 
						
						<img class="close_news f_right" src="<?=G5_THEME_URL?>/_images/close_round.gif" alt="공지사항 닫기">
						
						<button class="close_today f_right" > 
							<i class="fas fa-times-circle"></i>
							<span data-i18n="dashboard.오늘하루 열지않기"> TODAY CLOSE</span>
						</button> 
					</h5>

					<?while( $row = sql_fetch_array($notice_sql_query) ){ ?>
					<div>
						<span><?=$row['wr_content']?></span>
					</div>
					<?}?>

				<?}?>
				</section>
				
				<button class="notice_open f_right" > 
					<i class="fas fa-exclamation-circle"></i>
					<span data-i18n="dashboard.공지사항"> Notification</span>
				</button>

			<section class="dash_wallet">
				<h5 data-i18n="dashboard.현재 지갑 잔고 상황">Wallet Balances</h5>
				<div>
					<div>
						<canvas id="myChart"></canvas>
					</div>
					<ul class="clear_fix">
						<li class="bit">
							<a href="<?=G5_WALLET_PATH?>/transaction_bit.php">
								<strong>Bitcoin</strong>
								<p>&#36;<?=$btc_rate?></p>
								<div class="Balance"><?=$btc_account?> <span class="currency">BTC</span></div>
							</a>
						</li>
						
						<li class="eth">
							<!--<a href="<?=G5_THEME_URL?>/transaction_eth.php">-->
							<a href="javascript:serviceModal();">
								<strong>Ether</strong>
								<!--<p>&nbsp&#36;<?=$eth_rate?></p>-->
								<p>&nbsp</p>
								<div class="Balance"><?=$eth_account?> <span class="currency">ETH</span></div>
							</a>
						</li>
						<li class="roc">
							<!--<a href="<?=G5_THEME_URL?>/transaction_rock.php">-->
							<a href="javascript:serviceModal();">
								<strong>Rockwood</strong>
								<!--<p>&#36;<?=$rwd_rate?></p>-->
								<p>&nbsp</p>
								<div class="Balance"><?=$rwd_account?> <span class="currency">RWD</span></div>
							</a>
						</li>
						<li class="look">
							<!--<a href="<?=G5_THEME_URL?>/transaction_look.php">-->
							<a href="javascript:serviceModal();">
								<strong>Lukiu</strong>
								<!--<p>&#36;<?=$lok_rate?></p>-->
								<p>&nbsp</p>
								<div class="Balance"><?=$rwd_account?> <span class="currency">LKU</span></div>
							</a>
						</li>
						
						<li class="v7">
							<a href="<?=G5_WALLET_PATH?>/transaction_v7.php">
								<strong>V7</strong>
								<p>&#36;<?=$v7_rate?></p>
								<div class="Balance"><?=$v7_account?> <span class="currency">V7</span></div>
							</a>
						</li>
					</ul>
				</div>
			</section>
			
			
			<section class="dash_price">
				<h5 data-i18n="dashboard.코인 가격 차트">Price charts</h5>
				<div>
					<img src="<?=G5_THEME_URL?>/_images/bit_round.gif" alt="BITCOIN" class="currency_icon"><span>BITCOIN PRICE</span>
					<strong>&#36;<?=$btc_cost?> </strong>
					<!--<p><img src="<?=G5_THEME_URL?>/_images/bit_round.gif" alt="BITCOIN"> SEE CHARTS</p>-->
				</div>
				
				<!--
				<div>
					<span>ETHER PRICE</span>
					<strong>&#36;186.24</strong>
					<p><img src="<?=G5_THEME_URL?>/_images/eth_round.gif" alt="ETHER"> SEE CHARTS</p>
				</div>

				<div>
					<span>ROCKWOOD COIN PRICE</span>
					<strong>&#36;315.54</strong>
					<p><img src="<?=G5_THEME_URL?>/_images/rock_round.gif" alt="ROCKWOOD"> SEE CHARTS</p>
				</div>

				<div>
					<span>LOOKEI PRICE</span>
					<strong>&#36;0.0054</strong>
					<p><img src="<?=G5_THEME_URL?>/_images/look_round.gif" alt="LOOKIE"> SEE CHARTS</p>
				</div>
				-->
				<div>
					<img src="<?=G5_THEME_URL?>/_images/v7_round.gif" alt="v7" class="currency_icon"><span>V7 TOKEN PRICE</span>
					<strong>&#36;<?=$v7_cost?></strong>
					<!--<p><img src="<?=G5_THEME_URL?>/_images/v7_round.gif" alt="v7토큰"> SEE CHARTS</p>-->
				</div>
				
			</section>
			

			<section class="dash_business">
				<h5  data-i18n="dashboard.비즈니스 현황">Business Status</h5>
				<div class="mystatus">
					<p><?=$member['mb_id']?></p>
					<div class='gradelevel'>
						<!--<li><span><img src="/img/package-<?=$member['grade']?>.png"></span> <span class="right"> <?=$member['grade']?> grade </span></li>-->
						<li><span><img src="/img/<?=$member['mb_level']?>star.png"></span> <span class="right"> V<?=$member['mb_level']?> level</span></li>
					</div>
					<hr>
					<div class='packs'>
					
						<? 
						$b_valid = item_valid($member['mb_id'],10);
						if($b_valid){?>
							<li><a href="<?=G5_URL?>/page.php?id=purchase_order_end&stx=b"><?=get_it_image(packImg($b_valid['it_pool1']), 50, 50);?></a> <span class="right"><?=shift_date($b_valid['it_pool1_profit'])?></span></li>
						<?}?>
						
						<?
						$q_valid = item_valid($member['mb_id'],20);
						if($q_valid){?>
							<li><a href="<?=G5_URL?>/page.php?id=purchase_order_end&stx=q"><?=get_it_image(packImg($q_valid['it_pool2']), 50, 50);?></a> <span class="right"><?=shift_date($q_valid['it_pool2_profit'])?></span></li>
						<?}?>
					</div>
					
				</div>

				<div>
					<img src="<?=G5_THEME_URL?>/_images/busi1.gif" alt="아이콘">
					<p><?=$member['mb_recommend']?></p>
					<span data-i18n="dashboard.나의 추천인">My Sponsor</span>
				</div>
				<!--
				<div>
					<img src="<?=G5_THEME_URL?>/_images/busi2.gif" alt="아이콘">
					<p><?=$member['mb_brecommend']?></p>
					<span data-i18n="dashboard.후원인">Binary Placement</span>
				</div>
				
				<div>
					<img src="<?=G5_THEME_URL?>/_images/busi3.gif" alt="아이콘">
					<p>100BTC / 100ETH</p>
					<span>입금한 금액</span>
				</div>
				-->
				<div>
					<img src="<?=G5_THEME_URL?>/_images/busi4.gif" alt="아이콘">
					<p><?=shift_doller($mysales)?> $ </p>
					<span data-i18n="dashboard.나의 매출액">My Sales</span>
				</div>

				<div>
					<img src="<?=G5_THEME_URL?>/_images/busi4.gif" alt="아이콘">
					<p><?=$v7_account?> V7 / <br> $ <?=$v7_rate?></p>
					<span data-i18n="dashboard.나의 소득">My Earnings</span>
				</div>

				<!--
				<div>
					<img src="<?=G5_THEME_URL?>/_images/busi5.gif" alt="아이콘">
					<p>$1,500,000</p>
					<span>산하 매출액</span>
				</div>
				<div>
					<img src="<?=G5_THEME_URL?>/_images/busi6.gif" alt="아이콘">
					<p>1 Created / $2900 Saved</p>
					<span>아바타 갯수와 적금잔고</span>
				</div>
				-->
			</section>

		</div>

		<!--
		<div class="bx_wrap">
			<p>x</p>
			<div class="bxslider">
				<div>
					<p>BITCOIN PRICE</p>
					<div>
						<span>$7,745.87</span>
						<small>&#9650; 2.28%</small>
					</div>
					<img src="<?=G5_THEME_URL?>/_images/graph.jpg">
				</div>
				<div>
					<p>ETHER PRICE</p>
					<div>
						<span>$7,745.87</span>
						<small>&#9650; 2.28%</small>
					</div>
					<img src="<?=G5_THEME_URL?>/_images/graph.jpg">
				</div>
			</div>
		</div>
		-->
		

		
		<div class="gnb_dim"></div>

	</section>




	<!-- 차트	-->
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

	<script>
		var btc_total = "<?=$btc_rate_num?>";
		var v7_total = "<?=$v7_rate_num?>";
		var eth_total = "<?=$eth_rate_num?>";
		var rwd_total = "<?=$rwd_rate_num?>";
		var lok_total = "<?=$lok_rate_num?>";

		window.onload = function() {
			var ctx = document.getElementById("myChart");
			var doughnutChart = new Chart(ctx, {
				type: 'doughnut',
				data: {
					labels: ["Bitcoin", "V7", "Ethereum", "Rockwood","Lukiu"],
					//labels: ["Bitcoin", "Ethereum", "Rockwood", "Lookie","V7"],
					datasets: [{
						backgroundColor: ["#ff9b22","#07b5e5","#473bcb","#3edc89","#fd8def"],
						data: [btc_total, v7_total, eth_total, rwd_total, lok_total]
						//backgroundColor: ["#ff9b22", "#473bcb", "#3edc89", "#fd8def", "#07b5e5"],
						//data: [95, 2, 5, 1, 3]
					}]
				},
				options: {
					legend: {
						display: false
					}
				}
			});
		}
		
	</script>
	

	<script>
		$(function(){
			
			$('.dash_price p').click(function(){
				$('.bx_wrap').css("visibility","visible");
				$('.dim').css("display","block");
				$('body').css({"overflow":"hidden","height":"100%"});
			});
			$('.bx_wrap>p').click(function(){
				$('.bx_wrap').css("visibility","hidden");
				$('.dim').css("display","none");
				$('body').css({"overflow":"auto","height":"inherit"});
			});
			// bxslider
			$('.bxslider').bxSlider({
				autoControls: true,
				stopAutoOnClick: true,
				pager: true,
				responsive:true
			});

			
			
			var notice_open = getCookie('notice');

			if(notice_open == '1'){
				$('.dash_news').css("display","none");
			}else{
				$('.dash_news').css("display","block");
			}

			// 공지사항 닫기
			$('.close_news').click(function(){
				$('.dash_news').css("display","none");
				$('.notice_open').css("display","block");
			});

			$('.close_today').click(function(){
				setCookie('notice', '1', 1); 
				$('.dash_news').css("display","none");
				$('.notice_open').css("display","block");
			});
			

			$('.notice_open').click(function(){
				$('.dash_news').css("display","block");
				$(this).css("display","none");
			});

		});
	</script>



<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>

