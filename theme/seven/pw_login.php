<? include_once(G5_THEME_PATH.'/_include/head.php'); ?>

<style>
	.adm_title{background:#f9a62e;color:white;padding:5px 30px;font-size:1.2em; border-radius:25px;}
</style>

<script type="text/javascript">
	function flogin_submit(){
		$('form[name=flogin]').submit();
	}
</script>

	<section id="wrapper" class="bg_white">
		<div class="v_center">
			<div class="login_wrap">
				<div class="logo_login_div">
					<img src="<?=G5_THEME_URL?>/_images/login_logo.gif" alt="v7 wallet logo">
					<?if(strpos($url,'adm')){echo "<br><span class='adm_title'>For Administrator</span>";}?>
				</div>


				<form name="flogin" action="<?php echo $login_action_url ?>" method="post">
					  <input type="hidden" id="url" name="url" value="<?=$url?>">
					<div>
						<label for="u_name"><span data-i18n="login.유저네임">Username</span></label>
						<input type="text" name="mb_id" id="u_name" />
					</div>
					<div>
						<label for="u_pw"><span data-i18n="login.비밀번호">Password</span></label>
						<input type="password" name="mb_password" id="u_pw" />
					</div>

				
					<div class="find_pw_div">
						<input type="button" value="Login" class="btn_basic_block" onclick="flogin_submit();" >
					
						<!--<a href="<?=G5_THEME_URL?>/forgot_password.php">비밀번호 찾기</a>-->
					</div>
					<!--
					<a href="index.php" class="fp_img_a">
						<img class="fp_img" src="<?=G5_THEME_URL?>/_images/login_fingerprint.png" alt="지문">
					</a>
					-->
					<?if(!strpos($url,'adm')){?>
					<div class="login_btn_bottom">
						<a href="/bbs/register_form.php" class="btn_basic_block btn_navy"><span data-i18n="login.신규 회원 등록하기">Create new account</span></a>
						<!-- <a href="">지갑 복구하기</a> -->
						<a href="mailto:cs@v7wallet.com" class="support_a">Contact Support</a>
					</div>
					<?}?>
				</form>
			</div>

		</div>
	</section>


<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>
