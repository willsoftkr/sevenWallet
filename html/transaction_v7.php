<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

			<section class="con90_wrap">
				<div class="color_block v7_block">
					<div class="clear_fix">
						<strong class="f_left">V7</strong>
						<b class="f_right">12,234.52 USD</b>
					</div>
					<div class="clear_fix">
						<span class="f_left">=7885.52 USD</span>
						<p class="f_right">1.85521485 V7</p>
					</div>
				</div>
				
				<ul class="trans_history">
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
				</ul>
			</section>

		<div class="bottom_menu_wrap">
				<ul class="bottom_menu clear_fix bottom_menu3">
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
					<li>
						<a href="javascript:void(0);" class="pop_open">
							<img src="_images/btm_menu_exchange.png" alt="아이콘">
							<p>환전</p>
						</a>
					</li>
				</ul>
			</div>


			<div class="pop_wrap exc_pop_wrap">
				<p class="pop_title">환전할 코인을 선택하세요</p>
				<ul>
					<li>
						<a href="exchange_bit.php">
							<img src="_images/bit_round.gif" alt="아이콘">
							비트코인
						</a>
					</li>
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
				</ul>
				<p class="pop_close_wrap">
					<a href="javascript:void(0);" class="pop_close">취소</a>
				</p>
			</div>


		
		
		<?php include '_include/popup.php'; ?>
		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_transaction.png' alt='아이콘'> 코인 거래 내역");
			$(".pop_open").click(function(){
				$(".exc_pop_wrap").css("display","block");
			});
		});
	</script>



</body></html>
