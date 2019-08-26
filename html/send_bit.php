<?php include '../common.php';?>
<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

			<section class="con90_wrap">
				<?include './btc_inc_section.php';?>				
				<ul class="send_con">
					<li>
						<div>
							<div>
								<span class="send_title">수신인 지갑주소</span>
								<img class="scan_qr f_right" src="_images/wallet_addr.gif" alt="주소 확인하기">
							</div>
							<p class="font_gray mt10">지갑 주소를 입력하거나 스캔하십시오</p>
						</div>
					</li>
					<li>
						<div>
							<div class="clear_fix">
								<span class="send_title">전송할 코인 갯수</span>
								<a href="" class="f_right all_send">지갑 잔고 다 보내기</a>
								<!-- <small class="f_right font_sky">잔고가 부족합니다.</small> -->
							</div>
							<ul class="money_chage clear_fix">
								<li>
									<input type="text" placeholder="0.018">
									<span>BTC</span>
								</li>
								<li>=</li>
								<li>
									<input type="text" placeholder="$0.00">
									<span>USD</span>
								</li>
							</ul>
						</div>
					</li>
					<li>
						<div>
							<span class="send_title">수수료</span>
							<p class="f_right"><span class="font_gray">0.00245</span> BTC</p>
						</div>
					</li>
					<li>
						<div>
							<span class="send_title">인출합계</span>
							<p class="f_right"><span class="font_gray">0.00245</span> BTC</p>
						</div>
					</li>
				</ul>
			</section>
			
			<div class="btn_block_btm_wrap">
				<input type="button" value="보내기" class="btn_basic send_tran_open pop_open">
				<!-- <input type="button" value="보내기" class="btn_basic" onClick="location.href='send_chk.php'"> -->
			</div>

		<div class="gnb_dim"></div>
		<?php include '_include/popup.php'; ?>
	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_send.png' alt='아이콘'> 비트코인 보내기");
			$('#wrapper').css("background","#fff");
		});
	</script>



</body></html>
