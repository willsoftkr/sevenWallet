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
				
				<div class="qr_wrap mc_eth">
					<img src="_images/qr_img.gif" alt="큐알">
					<p>0xa500b1A87D80cf6d704Ad6b37Baf06BCF5f884FF</p>
					<input type="text" placeholder="받을 코인 숫자 입력">
				</div>		
			</section>

		<?php include '_include/popup.php'; ?>
		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_receive.png' alt='아이콘'> 이더리움 받기");
			$('#wrapper').css("background","#fff");
		});
	</script>



</body></html>
