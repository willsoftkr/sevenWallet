<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

		<section class="v_center1 exchange_wrap">
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
			<img src="_images/exchange_arrow.gif" alt="변환">
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


			<form action="">
				<ul class="money_chage clear_fix mc_eth">
					<li>
						<p>환전할 금액</p>
						<input type="text" placeholder="0.018">
						<span>ETH</span>
					</li>
					<li>=</li>
					<li>
						<p class="font_red">전부 환전하기</p>
						<input type="text" placeholder="0.00">
						<span>USD</span>
					</li>
				</ul>

				<ul class="send_con">
					<li class="clear_fix">
						<span class="send_title">환전 수수료</span>
						<p class="f_right"><span class="font_gray">0.00245</span> ETH</p>
					</li>
					<li class="clear_fix">
						<span class="send_title">합계</span>
						<p class="f_right">0.00245 ETH</p>
					</li>
				</ul>

			</form>

		</section>

		<div class="btn_block_btm_wrap">
			<input type="button" value="환전하기" class="btn_basic exchange_ok_pop_open pop_open">
			<p>경고 : 이 주문은 취소가 불가능합니다</p>
		</div>

		<?php include '_include/popup.php'; ?>

		<div class="gnb_dim"></div>
	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_exchange.png' alt='아이콘'> 이더리움으로 환전하기");
		});
	</script>



</body></html>
