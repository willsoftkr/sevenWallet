<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

			<section class="con90_wrap">
				<div class="color_block eth_block">
					<div class="clear_fix">
						<strong class="f_left">Ethereum</strong>
						<b class="f_right">12,234.52 USD</b>
					</div>
					<div class="clear_fix">
						<span class="f_left">=7885.52 USD</span>
						<p class="f_right">1.85521485 ETH</p>
					</div>
				</div>
				
				<ul class="trans_history">
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
					<li>
						<div>
							<span>5/30/2019 &#64; 24:15</span>
							<span class="f_right font_green">01.23456789 ETH &#47; $29.950.00</span>
						</div>
						<div>
							<span class="font_green">입금</span>
						</div>
					</li>
				</ul>
			</section>

		<div class="bottom_menu_wrap">
				<ul class="bottom_menu clear_fix bottom_menu3">
					<li>
						<a href="send_eth.php">
							<img src="_images/btm_menu_send.png" alt="아이콘">
							<p>보내기</p>
						</a>
					</li>
					<li>
						<a href="receive_eth.php">
							<img src="_images/btm_menu_receive.png" alt="아이콘">
							<p>받기</p>
						</a>
					</li>
					<li>
						<a href="deposit_eth.php">
							<img src="_images/btm_menu_deposit.gif" alt="아이콘">
							<p>입금</p>
						</a>
					</li>
				</ul>
			</div>
			
		<?php include '_include/popup.php'; ?>
		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_transaction.png' alt='아이콘'> 코인 거래 내역");
		});
	</script>



</body></html>
