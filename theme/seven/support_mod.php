<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

		<div class="dash_contents">
			<section class="support_wrap">
				<div id="tabs">
					<ul>
						<li>
							<a href="#tabs-1">
								<span>새 티켓 열기</span>
							</a>
						</li>
						<li>
							<a href="#tabs-2">
								<span>액티브 티켓</span>
							</a>
						</li>
						<li>
							<a href="#tabs-3">
								<span>종료된 티켓</span>
							</a>
						</li>
					</ul>



					<div id="tabs-1">
						<div class="round_div">
							<label for="q_cate">문의 주제</label>
							<select name="" id="q_cate">
								<option value="">통합문의</option>
								<option value="">해킹관련</option>
								<option value="">수당관련</option>
								<option value="">지갑관련</option>
								<option value="">계정관련</option>
							</select>
						</div>
						<div class="round_div">
							<label for="q_title">제목</label>
							<input type="text" id="q_title">
						</div>
						<div class="round_div">
							<label for="q_con">내용</label>
							<textarea name="" id="q_con"></textarea>
						</div>
						<p class="mb10">5MB미만 jpg, png, pdf 파일만 첨부 가능합니다.</p>
						<div class="filebox"> 
							<input class="upload-name" value="파일선택" disabled="disabled">
							<label for="ex_filename">파일업로드</label>
							<input type="file" id="ex_filename" class="upload-hidden">
						</div>
						<div class="text_right">
							<input class="btn_basic support_ok_pop_open pop_open" type="button" value="티켓 보내기">
						</div>
						<p class="sup_exp">5MB 이상의 jpg, png, pdf 파일은 cs@v7wallet.com으로 보내십시오.</p>
					<!-- 	<p class="sup_exp">jpg, png, pdf files larger than 5 megabytes send to <a href="mailto:cs@v7wallet.com">cs@v7wallet.com</a></p> -->
					</div>


					<div id="tabs-2">
						<ul class="ticket_ul">
							<li class="qa_title">
								<p>
									<strong>&#91;탈퇴 관련&#93;</strong>
									탈퇴관련 문의사항 입니다
								</p>
								<span>23:08 PM</span>
							</li>
							<li>
								<div class="qa_answer">
									<div>
										답변 테스트입니다.
									</div>
									<p class="text_right">- 담당자(23:12 PM)</p>
								</div>
								<div class="qa_ques">
									<div>
										서포트센터 테스트입니다.
									</div>
									<p class="text_right">-ygg331 (23:08 PM)</p>
									<p class="qa_file">Picture1.png</p>
								</div>
							</li>
						</ul>

						<div class="round_div">
							<input type="text" placeholder="메세지">
							<input type="button" value="보내기">
							<input type="button" value="닫기">
						</div>
						<div>
							<p class="mb10">5mb미만 jpg, png 파일만 첨부 가능합니다.</p>
						</div>
						<div class="filebox"> 
							<input class="upload-name" value="파일선택" disabled="disabled">
							<label for="ex_filename">파일업로드</label>
							<input type="file" id="ex_filename" class="upload-hidden">
						</div>
						<p class="sup_exp">5MB 이상의 jpg, png, pdf 파일은 cs@v7wallet.com으로 보내십시오.</p>
					</div>


					<div id="tabs-3">
						<ul class="ticket_ul">
							<li class="qa_title">
								<p>
									<strong>&#91;탈퇴 관련&#93;</strong>
									탈퇴관련 문의사항 입니다
								</p>
								<span>23:08 PM</span>
							</li>
							<li>
								<div class="qa_answer">
									<div>
										답변 테스트입니다.
									</div>
									<p class="text_right">- 담당자(23:12 PM)</p>
								</div>
								<div class="qa_ques">
									<div>
										서포트센터 테스트입니다.
									</div>
									<p class="text_right">-ygg331 (23:08 PM)</p>
									<p class="qa_file">Picture1.png</p>
								</div>
							</li>
						</ul>
					</div>

				</div>
			</section>
		</div>

		
	<?php include '_include/popup.php'; ?>

		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_support.png' alt='아이콘'> 서포트 센터");
			$("#tabs").tabs();
		});
	</script>



</body></html>
