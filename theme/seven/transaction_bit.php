<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 
include_once(G5_THEME_PATH.'/_include/wallet.php'); 

$order_sql = "select * from g5_shop_order where mb_id = '".$member['mb_id']."'";
$order_result = sql_query($order_sql); // 입금내역

$withrwal_sql = "select * from withdrawal_request where mb_id = '".$member['mb_id']."'";
$withrwal_result = sql_query($withrwal_sql); // 출금내역

$pack_sql = "select * from g5_shop_cart where mb_id = '{ member['mb_id'] }'";
$pack_result = sql_query($pack_sql); // 팩구매내역

?>


<?
	/*날짜선택 기본값 지정*/
	if (empty($fr_date)) {$fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-3 month"));}
	if (empty($to_date)) $to_date = G5_TIME_YMD;


	/*수당로그계산*/
	$qstr = "stx=".$stx."&fr_date=".$fr_date."&amp;to_date=".$to_date;
	$query_string = $qstr ? '?'.$qstr : '';
	
	if (empty($stx)) $stx = 'Deposited';  // 수당로그 기본값 

	if($stx == "Received" ){ 
		$sql_common ="FROM wallet_income_transfer WHERE date(createdAt)";
		$sql_order_type = "createdAt";
	}
	else if($stx == "B Pack" ){ 
		$sql_common ="FROM g5_shop_cart WHERE it_sc_type = '10' AND date(ct_time)";
		$sql_order_type = "ct_time";

	}else if ($stx == "Purchase"){
		$sql_common ="FROM g5_shop_cart WHERE date(ct_time)";
		$sql_order_type = "ct_time";

	}else if($stx == "Deposited"){
		$sql_common ="FROM g5_shop_order WHERE date(od_time)";
		$sql_order_type = "od_time";
		
	}else if($stx == "Withdrawal"){
		$sql_common ="FROM withdrawal_request WHERE date(create_dt)";
		$sql_order_type = "create_dt";

	}else if ($stx == "Bonus"){
		$sql_common ="FROM soodang_pay WHERE date(day)";
		$sql_order_type = "ct_time";

	}else{

	}

	$sql_search .= " between '{$fr_date}' and '{$to_date}' ";
	$sql_search .= "AND mb_id = '{$member['mb_id']}' ";
	
	$sql = " select count(*) as cnt
			{$sql_common}
			{$sql_search} ";

	//print_R($sql);
	
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

	
	function shift_txt($st){
		switch($st){
			case "N" :
				return '불가/거부';
			case "S" :
				return '불가/거부';
			case "Y" :
				return '출금완료';
			case "R" :
				return '보류(대기)';
			default	:
				return '보류(대기)';
		}
	}
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

			<section class="con90_wrap">
				<div class="color_block bit_block">
						<a href="transaction_bit.php">
							<div class="clear_fix">
								<strong class="f_left">Bitcoin</strong>
								<b class="f_right"><?=$btc_account?> BTC</b>
							</div>
							<div class="clear_fix">
								<span class="f_left">= <?=$btc_cost?> USD</span>
								<p class="f_right"><?= $btc_rate?>USD</p>
							</div>
						</a>
					</div>

					
				<form name="fsearch" id="fsearch" action="/wallet/transaction_bit.php" method="GET">
				<input type="hidden" name="stx" id="stx" value="">
					
				<!-- SEARCH -->
				<div class="search-container">
					<input type="text" id="fr_date" name="fr_date" data-i18n="[placeholder]order.fromDate" placeholder="Date range from" value=<?=$fr_date?> />
					<input type="text" id="to_date" name="to_date" data-i18n="[placeholder]order.toDate" placeholder="Date range to" value=<?=$to_date?> />
					<span class="filter_btn" onclick="search_submit();"><i class="fas fa-search"></i></span>
				</div>
				<!-- //SEARCH -->

				<!-- 탭 -->
				<ul class="tabs four">
					<li class="bonus_tab <?nav_active('Received')?>" data-tab="tab_1" data-category="Received"><p data-i18n="wallet.입금">Received</p></li>
					<li class="bonus_tab <?nav_active('Deposited')?>" data-tab="tab_2" data-category="Deposited"><p data-i18n="wallet.매출">Deposited</p></li>
					<li class="bonus_tab <?nav_active('Withdrawal')?>" data-tab="tab_3" data-category="Withdrawal"><p data-i18n="wallet.출금">Withdrawal</p></li>
					<li class="bonus_tab <?nav_active('Purchase')?>" data-tab="tab_4" data-category="Purchase"><p data-i18n="wallet.구매">Purchase</p></li>
					
				</ul>
				<!-- //탭 -->
				
				<ul class="trans_history">
					<?while( $row = sql_fetch_array($result)){?>

						<!-- 입금 -->
						<?if($stx == 'Received'){?>	
						<li>
							<div>
								<span><?=timeshift($row['createdAt'])?></span>
								<span class="f_right font_orange">+ <?=Number_format($row['token']/100000000,8)?> BTC </span>
							</div>
							<div>
								<span class="font_orange" data-i18n='purchase.지갑입금'>Wallet Deposit</span>  
								<span class="f_right" >$ <?= shift_doller($row['token']/100000000 * $btc_cost_num)?></span>
							</div>
						</li>
						
						<!-- 매출 -->
						<?}else if($stx == 'Deposited'){?>
						<li>
							<div>
								<span><?=timeshift($row['od_time'])?></span>
								<span class="f_right font_orange">- <?=Number_format($row['od_cart_price'],8)?> BTC &#47;- $<?=Number_format($row['upstair'],2)?></span>
							</div>
							<div>
								<span class="font_orange" data-i18n='wallet.Deposited'>입금</span>
							</div>
						</li>

						<!-- 출금 -->
						<?}else if($stx == 'Withdrawal'){?>
							
							<li>
							<div>
								<span><?=timeshift($row['update_dt'])?></span>
								<span class="f_right font_orange">- <?=Number_format($row['amt'],8)?> BTC &#47;- $<?=Number_format($row['amt_usd'],2)?></span>
								
							</div>
							
							<div>
								<span class="font_orange" data-i18n='wallet.<?=shift_txt($row['status'])?>'> / processed</span>
							</div>
						</li>

						<!-- 수당 -->
						<?}else if($stx == 'Purchase'){?>
							<li>
							<div>
								<span><?=timeshift($row['ct_time'])?></span>
								<span class="f_right font_orange">- <?=Number_format($row['cp_price'],8)?> BTC </span>
							</div>
							
							<div>
								<span class="font_orange"><?=$row['it_name']?> Purchase</span>
							</div>
						</li>

						<?}?>
					<?}?>

				</ul>
			</section>

		<div class="bottom_menu_wrap">
				<ul class="bottom_menu clear_fix bottom_menu3">
				<!--
					<li>
						<a href="send_bit.php">
							<img src="<?=G5_THEME_URL?>/_images/btm_menu_send.png" alt="아이콘">
							<p>보내기</p>
						</a>
					</li>
				-->

					<li style="width:33%;">
						<a href="income_bit.php">
							<img src="<?=G5_THEME_URL?>/_images/btm_menu_receive.png" alt="아이콘">
							<p data-i18n='wallet.입금'>Received</p>
						</a>
					</li>
				
					<li style="width:33%;">
						<a href="deposit_bit.php">
							<img src="<?=G5_THEME_URL?>/_images/btm_menu_deposit.gif" alt="아이콘">
							<p data-i18n='wallet.매출'>Deposited</p>
						</a>
					</li>
				
					<li style="width:33%;">
						<a href="send_bit.php">
						<img src="<?=G5_THEME_URL?>/_images/btm_menu_send.png"  alt="아이콘">
							<p data-i18n='wallet.출금'>Withdrawal</p>
						</a>
					</li>
				</ul>
			</div>
			</form>

		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_transaction.png' alt='아이콘'> <span data-i18n='title.코인 거래 내역'>Transaction History</span>");
		});
	</script>

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>