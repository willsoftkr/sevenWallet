<?php include '../common.php';?>
<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

			<section class="con90_wrap deposit_wrap">
				<?include './btc_inc_section.php';?>
						<div>
							<ul class="money_chage clear_fix mc_bit">
								<li>
									<p>입금할 금액</p>
									<input type="text" placeholder="0.018">
									<span>BTC</span>
								</li>
								<li>=</li>
								<li>
									<p class="font_red">모두 입금하기</p>
									<input type="text" placeholder="0.00">
									<span>USD</span>
								</li>
							</ul>
						</div>
							
			</section>
			
			<div class="btn_block_btm_wrap">
				<input type="button" value="입금" class="btn_basic" id="dep_btc">
				<p class="font_red text_center mt10">경고 : 이 주문은 취소가 불가능합니다</p>
			</div>
			
		<div class="gnb_dim"></div>

		<?php include '_include/popup.php'; ?>
	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_deposit.png' alt='아이콘'> 비트코인 입금");
			$('#wrapper').css("background","#fff");
		});
	</script>



</body></html>
