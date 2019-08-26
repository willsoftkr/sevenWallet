<?include '../common.php'?>
<?include '_include/head.php'; ?>

<?$login_action_url = G5_HTTPS_BBS_URL."/login_check.php"; ?>
<script type="text/javascript">
	function flogin_submit(){
		$('form[name=flogin]').submit();
	}
</script>
<body>
	<section id="wrapper" class="bg_white">
		<div class="v_center">
			<div class="login_wrap">
				<div class="logo_login_div">
					<img src="_images/login_logo.gif" alt="v7 wallet logo">
				</div>

				
				<form name="flogin" action="<?php echo $login_action_url ?>" method="post">
					<div>
						<label for="u_name">유저네임</label>
						<input type="text" name="mb_id" id="u_name" />
					</div>
					<div>
						<label for="u_pw">비밀번호</label>
						<input type="password" name="mb_password" id="u_pw" />
					</div>
					<div class="find_pw_div">
						<input type="button" value="Login" class="btn_basic_block" onclick="flogin_submit();" >
						<!--onClick="location.href='dashboard.php'"-->
						<a href="forgot_password.php">비밀번호 찾기</a>
					</div>
					<a href="index.php" class="fp_img_a">
						<img class="fp_img" src="_images/login_fingerprint.png" alt="지문">
					</a>
					<div class="login_btn_bottom">
						<a href="enroll.php" class="btn_basic_block btn_navy">신규 회원 등록하기</a>
						<!-- <a href="">지갑 복구하기</a> -->
						<a href="mailto:cs@v7wallet.com" class="support_a">Contact Support</a>
					</div>
				</form>
			</div>

		</div>
	</section>
</body>
</html>
