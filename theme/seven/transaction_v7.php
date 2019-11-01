<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 

include_once(G5_THEME_PATH.'/_include/wallet.php'); 

$order_sql = "select * from g5_shop_order where mb_id = '".$member['mb_id']."'";
$order_result = sql_query($order_sql);

//$order_list = sql_fetch($order_sql);
?>

<?
	/*날짜선택 기본값 지정*/
	if (empty($fr_date)) {$fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-3 month"));}
	if (empty($to_date)) $to_date = G5_TIME_YMD;


	/*수당로그계산*/
	$qstr = "stx=".$stx."&fr_date=".$fr_date."&amp;to_date=".$to_date;
	$query_string = $qstr ? '?'.$qstr : '';
	
	if (empty($stx)) $stx = 'exchage';  // 수당로그 기본값 
	
	if($stx == "exchange" ){ 
		$sql_common ="FROM g5_shop_change WHERE date(od_time)";
		$sql_order_type = "od_time";
	}else {
		$sql_common ="FROM soodang_pay WHERE allowance_name = '$stx' AND day";
		$sql_order_type = "day";
	}

	$sql_search .= "between '{$fr_date}' and '{$to_date}' ";
	$sql_search .= "AND mb_id = '{$member['mb_id']}' ";
	
	$sql = " select count(*) as cnt
			{$sql_common}
			{$sql_search} ";
	//print_r($sql);

	$row = sql_fetch($sql);
	$total_count = $row['cnt']; 

	$rows = 20; //한페이지 목록수
	$total_page  = ceil($total_count / $rows);
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지
	$from_record = ($page - 1) * $rows; // 시작 열

	$sql = " select *
			{$sql_common}
			{$sql_search}
			order by {$sql_order_type} desc
			limit {$from_record}, {$rows} ";
	$result = sql_query($sql);

	//print_R($sql );
?>


<script type="text/javascript">
		var mb_balance = '<?=$member["mb_balance"]?>';

		$(document).ready(function () {
			
			/*상단 분류 탭*/
			$('ul.tabs li').click(function () {
				search_submit($(this).attr('data-category'));
			});

			/*날짜선택 피커*/
			 $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
		})

		
		function search_submit(act = null)
		{
			var f = document.fsearch;
			f.stx.value = act;
			f.submit();
		}
	</script>

	
	
	<link rel="stylesheet" href="<?=G5_THEME_URL?>/_common/css/utils.css">

			<section class="con90_wrap" style="padding-bottom:80px">

				<div class="color_block v7_block">
						<a href="transaction_v7.php">
							<div class="clear_fix">
								<strong class="f_left">V7</strong>
								<b class="f_right"><?=$v7_account?> V7</b>
							</div>
							<div class="clear_fix">
								<span class="f_left">= <?=$v7_cost?> USD</span>
								<p class="f_right"><?=$v7_rate?> USD</p>
							</div>
						</a>
					</div>
				
				<form name="fsearch" id="fsearch" action="/wallet/transaction_v7.php" method="GET">
				<input type="hidden" name="stx" id="stx" value="">
					
				<!-- SEARCH -->
				<div class="search-container">
					<input type="text" id="fr_date" name="fr_date" data-i18n="[placeholder]order.fromDate" placeholder="Date range from" value=<?=$fr_date?> />
					<input type="text" id="to_date" name="to_date" data-i18n="[placeholder]order.toDate" placeholder="Date range to" value=<?=$to_date?> />
					<span class="filter_btn" onclick="search_submit();"><i class="fas fa-search"></i></span>
				</div>
				<!-- //SEARCH -->

				<style>
					.tabs li p{line-height:40px;}
				</style>
				<!-- 탭 -->
				<ul class="tabs five">
					<li class="bonus_tab all <?nav_active('exchange')?>" data-tab="tab_1" data-category="exchange"><p data-i18n="wallet.exchange">Exchange</p></li>
					<!--<li class="bonus_tab <?nav_active('Sales')?>" data-tab="tab_2" data-category="Sales"><p data-i18n="wallet.Sales">Sales</p>(<?=$total_count?>)</li>-->

					
					<li class="bonus_tab <?nav_active('daily payout')?>" data-tab="tab_2" data-category="daily payout"><p data-i18n="wallet.daily payout">Daily payout</p></li>
					<li class="bonus_tab <?nav_active('10x10 Matching')?>" data-tab="tab_3" data-category="10x10 Matching"><p data-i18n="wallet.10x10 Matching">10x10 M</p></li>
					<li class="bonus_tab <?nav_active('Infinite matching')?>" data-tab="tab_4" data-category="Infinite matching"><p data-i18n="wallet.Infinite matching">Infinite M</p></li>
					<li class="bonus_tab <?nav_active('Binary')?>" data-tab="tab_5" data-category="Binary"><p data-i18n="wallet.Binary">Binary</p></li>
				
				</ul>
				<!-- //탭 -->



				<ul class="trans_history">
				<!--
					<li>
						<div>
							<span>5/30/2019 &#64; 24:15</span>
							<span class="f_right">01.23456789 BTC &#47; $29.950.00</span>
						</div>
						<div class="font_purple">
							<span>환전:</span>
							<span class="f_right">1526.52 RWD @ 1.52 RWD</span>
						</div>
					</li>
					<li>
						<div>
							<span>5/30/2019 &#64; 24:15</span>
							<span class="f_right">01.23456789 BTC &#47; $29.950.00</span>
						</div>
						<div class="font_green">
							<span>환전:</span>
							<span class="f_right">2415.4526 ETH @ 0.4526 ETH</span>
						</div>
					</li>
					<li>
						<div>
							<span>5/30/2019 &#64; 24:15</span>
							<span class="f_right">01.23456789 BTC &#47; $29.950.00</span>
						</div>
						<div class="font_orange">
							<span>환전:</span>
							<span class="f_right">0.1563 BTC @ 0.00257 BTC</span>
						</div>
					</li>
					<li>
						<div>
							<span>5/30/2019 &#64; 24:15</span>
							<span class="f_right">01.23456789 BTC &#47; $29.950.00</span>
						</div>
						<div class="font_red">
							<span>전송:</span>
							<span class="f_right">3Pitg1drUTj6DQTqdQXCz9H2bevXh7tiMi</span>
						</div>
					</li>
					
					<li>
						<div>
							<span>5/30/2019 &#64; 24:15</span>
							<span class="f_right">01.23456789 BTC &#47; $29.950.00</span>
						</div>
						<div class="font_blue">
							<span>수신:</span>
							<span class="f_right">3Pitg1drUTj6DQTqdQXCz9H2bevXh7tiMi</span>
						</div>
					</li>
					-->

					<?while( $row = sql_fetch_array($result) ){?>
						<?if($stx == 'exchange'){?>	
						<li>
							<div>
								<span><?=timeshift($row['od_time'])?></span>
								<div class="f_right font_blue">- <?=Number_format($row['amount'])?> v7 &#47; $ <?=shift_doller($row['amount']*$v7_cost)?> </span></div>
							</div>
							<div>
								<span class="font_orange">v7 > btc</span>  
								<span class="f_right" ><span class="font_orange">+ <?=Number_format($row['exchange'],8)?> btc</span></span>
							</div>
						</li>

						<?}else{?>

						<li>
							<div>
								<span><?=timeshift($row['day'])?></span>
								
								<span class="f_right"><?=$sum?> <i>V7</i></span> 
								
							</div>
							<div class="font_<?if($row['allowance_name'] == 'daily payout'){ echo "blue";}else{echo "green";}?>">
								<span><?=$row['allowance_name']?></span>
								<span class="f_right"> + <?=shift_doller($row['benefit']/$v7_cost)?> <i>V7</i> <!--<span style="font-size:14px;">($ <?=shift_doller($row['benefit'])?>)</span>--></span>
							</div>
						</li>
						<?}?>
					<?}?>
				</ul>
				<?php
					$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
					echo $pagelist;
				?>
			</section>

		<div class="bottom_menu_wrap">
				<ul class="bottom_menu clear_fix bottom_menu3">
				<!--
					<li>
						<a href="send_v7.php">
							<img src="_images/btm_menu_send.png" alt="아이콘">
							<p>보내기</p>
						</a>
					</li>
					<li>
						<a href="receive_v7.php">
							<img src="_images/btm_menu_receive.png" alt="아이콘">
							<p>받기</p>
						</a>
					</li>
					-->	
					<li style="width:100%;">
						<a href="javascript:void(0);" class="pop_open">
							<img src="<?=G5_THEME_URL?>/_images/btm_menu_exchange.png" alt="아이콘">
							<p data-i18n="wallet.환전하기">Exchange</p>
						</a>
					</li>
					
				</ul>
			</div>

			
			<div class="pop_wrap exc_pop_wrap">
				<p class="pop_title" data-i18n="wallet환전할 코인">Select coin to exchange</p>
				<ul>
					<li>
						<a href="/wallet/exchange_bit.php">
							<img src="<?=G5_THEME_URL?>/_images/bit_round.gif" alt="아이콘">
							Bitcoin
						</a>
					</li>
					<!--
					<li>
						<a href="exchange_eth.php">
							<img src="_images/eth_round.gif" alt="아이콘">
							이더리움
						</a>
					</li>
					<li>
						<a href="exchange_rock.php">
							<img src="_images/rock_round.gif" alt="아이콘">
							락우드
						</a>
					</li>
					<li>
						<a href="exchange_look.php">
							<img src="_images/look_round.gif" alt="아이콘">
							루키
						</a>
					</li>
					-->
				</ul>
				<p class="pop_close_wrap">
					<a href="javascript:void(0);" class="pop_close">Cancle</a>
				</p>
			</div>
			
			</form>

					
		<div class="gnb_dim"></div>

	</section>




	<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_transaction.png' alt='아이콘'> <span data-i18n='title.코인 거래 내역'>Transaction History</span>");

			$(".pop_open").click(function(){
				$(".exc_pop_wrap").css("display","block");
			});
		});
	</script>

	
	
	

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>
