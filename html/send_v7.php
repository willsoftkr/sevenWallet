<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

		<section class="con90_wrap send_etc_wrap">
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

			<form action="">

				<ul class="send_con">
					<li>
						<div>
							<div>
								<span class="send_title">수신인 지갑주소</span>
								<img class="scan_qr f_right" src="_images/wallet_addr_v7.gif" alt="주소 확인하기">
							</div>
							<p class="font_gray mt10">지갑 주소를 입력하거나 스캔하십시오</p>
						</div>
					</li>
					<li>
						<div>
							<div>
								<span class="send_title">전송할 코인 갯수</span>
								<a href="" class="f_right all_send">지갑 잔고 다 보내기</a>
							<!-- 	<small class="f_right font_sky">잔고 부족합니다.</small> -->
							</div>
							<ul class="money_chage clear_fix mc_v7">
								<li>
									<input type="text" placeholder="0.018">
									<span>V7</span>
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
							<span class="send_title">개스 한도</span>
							<p class="f_right"><span class="font_gray">0.00245</span> Gas</p>
						</div>
					</li>
					<li>
						<div>
							<span class="send_title">개스 가격</span>
							<p class="f_right"><span class="font_gray">0.00245</span> Gwei</p>
						</div>
					</li>
					<li>
						<div>
							<span class="send_title">인출 합계</span>
							<p class="f_right">0.00245 V7</p>
						</div>
					</li>
				</ul>

			</form>

		</section>

		<div class="btn2_btm_wrap">
			<input type="reset" value="취소" class="btn_basic" onClick="history.back(-1);">
			<input type="button" value="보내기" class="btn_basic send_tran_open pop_open">
		</div>

		<?php include '_include/popup.php'; ?>

		
		<div class="gnb_dim"></div>
	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_send.png' alt='아이콘'> V7 보내기");
			$('#wrapper').css("background","#fff");
		});
	</script>



</body></html>
