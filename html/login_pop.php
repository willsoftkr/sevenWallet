<?php include '_include/head.php'; ?>

<style>
.dim{display: block;}
.pop_wrap{display: block;}
</style>

<body>
	<section id="wrapper" class="bg_white">
		<div class="v_center">
			<div class="login_wrap">
				<div class="logo_login_div">
					<img src="_images/login_logo.gif" alt="v7 wallet logo">
				</div>

				<form action="">
					<div class="l_fp_div">
						<img src="_images/login_fingerprint.png" alt="지문이미지">
						지문으로 로그인
					</div>
					<div>
						<a href="pw_login.php" class="font_deepblue">비밀번호로 로그인</a>
					</div>
					<div>
						<a href="enroll.php" class="btn_basic_block btn_navy">신규 회원 등록하기</a>
						<a href="">지갑 복구하기</a>
						<a href="mailto:cs@v7wallet.com" class="support_a">Contact Support</a>
					</div>
				</form>
			</div>

		</div>

		<div class="pop_wrap opt_cancel_wrap notice_img_pop">
			<p class="pop_title">지문으로 로그인 <img class="pop_close" src="_images/close_x.gif" alt="팝업창 닫기"></p>	
			<div>
				<img src="_images/notice_pop.gif" alt="이미지">
				지문인식에 실패하였습니다.<br>
				비밀번호로 로그인 하십시오.
			</div>
			<div class="pop_close_wrap">
				<a href="javascript:void(0);" class="pop_close gray_close">Close</a>
			</div>
		</div>

		<div class="dim"></div>

	</section>
</body>
</html>





