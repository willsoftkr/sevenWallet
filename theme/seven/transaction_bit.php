<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 

include_once(G5_THEME_PATH.'/_include/wallet.php'); 

$order_sql = "select * from g5_shop_order where mb_id = '".$member['mb_id']."'";
$order_result = sql_query($order_sql);

//$order_list = sql_fetch($order_sql);
?>

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
				
				<ul class="trans_history">
				<!--
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
					<?while( $row = sql_fetch_array($order_result) ){?>
					<li>
						<div>
							<span><?=timeshift($row['od_time'])?></span>
							<span class="f_right font_orange"><?=Number_format($row['od_cart_price'],8)?> BTC &#47; $<?=Number_format($row['upstair'],2)?></span>
						</div>
						<div>
							<span class="font_orange" data-i18n='wallet.Deposited'>입금</span>
						</div>
					</li>
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
					<li>
						<a href="receive_bit.php">
							<img src="<?=G5_THEME_URL?>/_images/btm_menu_receive.png" alt="아이콘">
							<p>받기</p>
						</a>
					</li>
				-->
					<li style="width:100%;">
						<a href="deposit_bit.php">
							<img src="<?=G5_THEME_URL?>/_images/btm_menu_deposit.gif" alt="아이콘">
							<p data-i18n='wallet.Deposited'>입금</p>
						</a>
					</li>
				</ul>
			</div>
			
		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_transaction.png' alt='아이콘'> <span data-i18n='title.Transaction History'>코인 거래 내역</span>");
		});
	</script>

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>