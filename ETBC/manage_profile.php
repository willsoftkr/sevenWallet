<?php
include_once('./_common.php');
include_once('../lib/otphp/lib/otphp.php');


?>

<!doctype html>
<html lang="ko">

<head>
  <?include_once('common_head.php')?>
	
  <?
		/*
		if($member[otp_flag]=='Y') {
			$status = 'Enabled';
			$sel_stat1 = '';
			$sel_stat2 = 'selected';
		}
		else {
			$status = 'Disabled';
			$sel_stat2 = '';
			$sel_stat1 = 'selected';
		}
		*/
	
		$status = 'Enabled';
		$sel_stat1 = '';
		$sel_stat2 = 'selected';

		$Base32 = new Base32();
		$encoded = $Base32->encode(str_pad($member['mb_id'], 20 , "!&%"));
		//$totp = new \OTPHP\TOTP($encoded);
		
		echo $encoded;
	?>

  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.js" type="text/javascript"></script>

  <script>
    $(function () {
      //비번 메일 수정 버튼 클릭시

      $('#member_save').on('click', function () {
		
		console.log( $('#vCode').val().trim() );
		console.log(key);
			if(key == sha256( $('#vCode').val().trim()) ){
								$('.verifyContainer').hide();
				$('#reg_mb_email').css('background-color','#ccc').prop('readonly', true);

				// 메일 인증 성공
					$.ajax({
					  type: "POST",
					  url: "manage_profile_proc.php",
					  cache: false,
					  async: false,
					  dataType: "json",
					  data: {
						current_password: $('#current_password').val(),
						func: "member",
						mb_id: $('#mb_id').val(),
						//new_password: $('#new_password').val(),
						//new_password2: $('#new_password2').val()
						new_password: $('#reg_mb_email').val()
					  },
					  success: function (data) {
						
						if (data.result == "OK") {
						  $('#profileSave').modal('show');
						  
						} else {
						  alert(data.result);
						  commonModal('Error', data.result, 100);
						}
					  }
					});
				
			}else{
				commonModal('Do not match','<p>Email verification code is incorrect. Please enter the correct code</p>',80);	
			}
      });

	  
		/*메일 인증코드 발송*/
		$('#sendMail').on('click', function(e){
			if(!$('#reg_mb_email').val()){
				commonModal('Mail authentication','<p>Please enter your mail</p>',80);
				return;
			}

			console.log("mail : " + $('#reg_mb_email').val())

			$.ajax({
				url: '/bbs/register.mail.verify.php',
				type: 'GET',
				async: false,
				data: {
					"mb_email": $('#reg_mb_email').val()
				},
				dataType: 'json',
				success: function(result) {
					console.log(result);
					key = result.key;
					console.log(key);

					commonModal('Mail authentication','<p>Send a authentication code to your mail.</p>',80);
				},
				error: function(e){
					//console.log(e);
				}
			});

			$('#vCode').addClass('active');
		});


		$('#verify').on('click', function(e){
			console.log( $('#vCode').val().trim() );
			if(key == sha256( $('#vCode').val().trim()) ){
				// 메일 인증 성공
				$('.verifyContainer').hide();
				$('#reg_mb_email').css('background-color','#ccc').prop('readonly', true);;
				
			}else{
				commonModal('Do not match','<p>Email verification code is incorrect. Please enter the correct code</p>',80);
			}
		});
 });
  </script>
</head>

<body>
  <?include_once('mypage_head.php')?>

<style>
	.username-container{background:lightslategrey;}
</style>

  <div class="main-container">

    <div id="body-wrapper" class="manage-profile-container">

      <div class="username-container shadow">
        <h2 class="username_tit">Change Password</h2>
        <!--xx  
      <h3 class="mem_id"><?echo $member[mb_id]?></h3>
  -->
        <!--form autocomplete="off" class="manage-profile-form" action="./manage_profile_proc.php" method="post"-->
        <input value="<?=$member[mb_id]?>" type="hidden" name="mb_id" id="mb_id" />
        <input value="member" type="hidden" name="mfunc" id="mfunc" />
        <div class="manage-profile-left">
          <!--xx
						<input value="<?=$member[first_name]?>" type="text" name="first_name" id="first_name" placeholder="First Name" autocomplete="off" class="no-sub" data-i18n="[placeholder]manage.fName" />
						<input value="<?=$member[last_name]?>" type="text" name="last_name" id="last_name" placeholder="Last Name" autocomplete="off" class="no-sub" data-i18n="[placeholder]manage.lName" />
						<br>
						<input value="<?=$member[mb_email]?>" type="email" name="current_email" placeholder="Current Email" data-i18n="[placeholder]manage.cEmail" autocomplete="off" class="no-sub" />

						<input type="email" name="new_email" id="new_email" placeholder="New Email" autocomplete="off" data-i18n="[placeholder]manage.nEmail" class="no-sub" />
						<br>
                        
						<select class="custom-select country-phone" id="nation_code">
							<option value="">--</option>
						<option value="001">U.S.A - 001</option>
						<option value="082" selected="">Korea - 082</option>
						<option value="081">Japan - 081</option>
						<option value="084">Vietnam - 084</option>
						<option value="086">China - 086</option>
						<option value="061">Australia - 061</option>
						</select>

						<input value="<?=$member[mb_hp]?>" type="phone" name="phone" placeholder="Phone" autocomplete="off" class="no-sub phone-input" id="phone_number" data-i18n="[placeholder]manage.phone" />
                        -->
	<style>
		#vCode{display:none; font-size:12px;}
		#vCode.active{display:block;    margin: 30px auto 20px;font-size: 16px; border-bottom: 2px solid #f9a62e;}
		input[type='input']{font-size:0.8em;color:white;background:transparent;}
		input[type='text']{background:transparent;color:white;}
		input[type='password']{font-size:0.8em;}

		.pass_rule{font-size:0.8em; color:rgba(255,255,255,0.7);display:block;background:rgba(255,255,255,0.1);border-radius:10px; padding:10px;font-weight:400;margin-top:10px;}
		//save-button{background:cornflowerblue;border-radius:15px;color:white;}

		input::-webkit-input-placeholder {
		  color: white;
		}
		input:-ms-input-placeholder {
		}
		
		.current_password{color:white;width:100% !important;padding-left:5px; color:white;}
		.new_password{background:transparent;width:100% !important; padding-left:5px; color:white;}

		.send_mail{margin:10px 0;     background: cornflowerblue;font-size: 12px;border: 0;color: white; cursor: pointer; padding: 10px; width: 66%; border-radius: 30px;}
		.send_mail:hover { background: #f9a62e;}
	</style>
          <input type="text" id="current_password" class="current_password" name="current_password" placeholder="Current Password"
            class="sub-input" data-i18n="[placeholder]manage.cPass" required style="color:midnightblue;"/>
          <br>
			
			<!--
			<input type="password" id="new_password" name="new_password" placeholder="New Password" class="no-sub"
            data-i18n="[placeholder]manage.nPass"  min="8" maxlength="20"  required/>
			-->
			
				<div>
					<input type="text" name="mb_email" class="new_password" placeholder="New E-mail Adress"  value="" id="reg_mb_email" required class="input-search" maxlength="100" oninvalid="this.setCustomValidity('Enter E-mail Here')" oninput="this.setCustomValidity('')">
					<span><button class="send_mail" type="button" id="sendMail" >Send confirm code for NEW E-mail </button></span>

					<div class="verifyContainer">
						<input type="text" name="vCode" placeholder=" Input Password Confirm" id="vCode" required class="input-search" maxlength="10"  >
					</div>

				</div>
			<!--
			<div class="google-auth-bottom">
                    <div class="security-code" >
						<a href="#" data-toggle="modal" data-target="#emailSecurityCode">
						<span class="blue email-security-code"><i id="verification_email" class="fas fa-paper-plane"></i> Email Security Code </span>
       

												<div class="modal fade" id="sendMail" tabindex="-1" role="dialog" aria-labelledby="emailSecurityCodeModalCenterTitle" aria-hidden="true">
												  <div class="modal-dialog modal-dialog-centered" role="document">
													<div class="modal-content">

													  <div class="modal-header">
														<h5 class="modal-title" id="emailSecurityCodeModalLongTitle">SECURITY CODE</h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														  <span aria-hidden="true">&times;</span>
														</button>
													  </div>

													  <div class="modal-body">
														<i class="far fa-check-circle blue"></i>
														<h4>Security code has been emailed.</h4>
														<p>Please check your Spam folder if you do not receive your code within a five minutes.</p>
													  </div>

													  <div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
													  </div>

													</div>
												  </div>
												</div>

						<input class="security-code-input" type="text"  id="token_str" name="google auth code" placeholder="Enter Security Code" />
					</div>
			</div>
			-->
	
			<!--
            <input type="password" id="new_password2" name="new_password" placeholder="Confirm New Password"
            class="no-sub" data-i18n="[placeholder]manage.cnPass" min="8" maxlength="20"  required/>
			-->

			

          <button id="member_save" class="btn save-button" 
            data-i18n="manage.save">Save Changes</button>

			<span class="pass_rule">New password E-mail Adress</span>
        </div>

      </div>

			<!--	
			<div class="manage-profile-right">

				<p class="blue" data-i18n="[html]manage.txt1">
					Current password is required to save changes
				</p>
				<p class="purple password-requirements">
					<span data-i18n="[html]manage.txt2" >New password must be at least 8 characters and must include:</span>
					<ul class="purple">
						<li data-i18n="manage.rule1">- one lower case letter</li>
						<li data-i18n="manage.rule2">- one upper case letter</li>
						<li data-i18n="manage.rule3">- one number</li>
					</ul>
				</p>
			</div>
			-->



      <div class="google-auth-container shadow">

			<h2 class="tfa_tit">Authentication</h2>
				<!--
				<span class="red"><?echo $status?></span>  
				<select class="custom-select google-auth-select"  name="otp_status" id="otp_status">
					<option value="N" <?echo $sel_stat1 ?> data-i18n="manage.disable" >Disabled</option>
					<option value="Y" <?echo $sel_stat2 ?> data-i18n="manage.enable" >Enabled</option>
				</select>
				<br>


				<div class="google-auth-top">
					<div class="google-auth-top-text">
						<p data-i18n="[html]manage.text1" >
							Please scan the QR code with your <a href="https://play.google.com/store/apps/details?id=com.authy.authy" class="blue" target="_blank">	
Authy 2-Factor Authentication</a> to
							create a secure link between your device and Fiji Mining.
							After you scan the QR code, you will see a 6-digit code on your
							device that you will need to enter below.
						</p>


						<p data-i18n="[html]manage.text2" >
							<span class="red">NOTE:</span> After you connect your device, you will need to enter the code
							each time you access your account.  
						</p>
	
					</div>

					-->
				 <!--
				 <div class="google-auth-top-qr" id="qrcode">
					<img id="qrImg" style="margin:0 auto;" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=100x100&chld=M|0&cht=qr&chl=otpauth://totp/ETBC(<?=$member['mb_id']?>)%3Fsecret%3D<?=$encoded?>" />
				 </div>					
				-->
				

        <!-- 2fa input -->
        <div class="two-fa">
  		  <a href="https://play.google.com/store/apps/details?id=com.authy.authy" class="blue btn" target="_blank">Download Authentication app</a>
        <p class="tfa_code">Your 2FA Code</p>
		<p class="tfa_key" id="tfa_key"><?=$encoded?>	</p>
          <!--<input class="otp-input" type="text" placeholder="Enter OTP Code" id="auth_code"
            data-i18n="[placeholder]manage.otp" />-->
        </div>
        <!-- 2fa input -->

        <!-- 2fa copy btn -->
        <button class="btn tfa-button" id="tfa_copy" onclick="copyToClipboard('#tfa_key')">Copy Auth Code</button>
      </div>
      <!-- 2fa copy btn -->



    </div>
  </div>
  </div>
<!-- //MAIN_CONTAINER -->


  <!-- MODAL -->
  <div class="modal fade" id="profileSave" tabindex="-1" role="dialog" aria-labelledby="profileSaveModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profileSaveModalLongTitle">Profile Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <i class="far fa-check-circle blue"></i>
          <h4>Your settings have been successfully saved.</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload();">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- //MODAL -->
  <!-- MODAL -->
  <div class="modal fade" id="securitySave" tabindex="-1" role="dialog" aria-labelledby="securitySaveodalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="securitySaveModalLongTitle">Security Settings</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <i class="far fa-check-circle blue"></i>
          <h4>Your settings have been successfully saved.</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!-- //MODAL -->

<!--xx Copy clipboad -->
<script>
          function copyToClipboard(element) {
            alert("Your 2FA Code is copied!");
		  var $temp = $("<input>");
		  $("body").append($temp);
		  $temp.val($(element).text()).select();
		  document.execCommand("copy");
		  $temp.remove();
}
 </script>

</body>

</html>