<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 

include_once(G5_THEME_PATH.'/_include/wallet.php'); 
?>

		<div class="v_center dash_contents">

			<section class="wallet_wrap">
				<h5><span data-i18n='wallet.지갑 총 잔고'>Total wallet balance : </span> <span><?=$total_rate?> USD</span></h5>
				<div>
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

					<!--
					<div class="color_block eth_block">
						<a href="transaction_eth.php">
							<div class="clear_fix">
								<strong class="f_left">Ethereum</strong>
								<b class="f_right">12,234.52 USD</b>
							</div>
							<div class="clear_fix">
								<span class="f_left">=7885.52 USD</span>
								<p class="f_right">1.85521485 BTC</p>
							</div>
						</a>
					</div>
					<div class="color_block rock_block">
						<a href="transaction_rock.php">
							<div class="clear_fix">
								<strong class="f_left">Rockwood</strong>
								<b class="f_right">12,234.52 USD</b>
							</div>
							<div class="clear_fix">
								<span class="f_left">=7885.52 USD</span>
								<p class="f_right">1.85521485 BTC</p>
							</div>
						</a>
					</div>
					<div class="color_block look_block">
						<a href="transaction_look.php">
							<div class="clear_fix">
								<strong class="f_left">Lookei</strong>
								<b class="f_right">12,234.52 USD</b>
							</div>
							<div class="clear_fix">
								<span class="f_left">=7885.52 USD</span>
								<p class="f_right">1.85521485 BTC</p>
							</div>
						</a>
					</div>
					-->
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
							
				</div>
			</section>
		</div>
		
		<div class="gnb_dim"></div>
	</section>



	<script>
		$(function(){
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_wallet.png' alt='아이콘'> <span data-i18n='title.크립토 월렛'>Crypto Wallets</span>");
		});
	</script>

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>

