<script>
function dimShow() {
	$('.dim').css("display", "block");
	$('body').css({
		"overflow": "hidden",
		"height": "100%"
	});
};

function dimHide() {
	$('.dim').css("display", "none");
	$('body').css({
		"overflow": "auto",
		"height": "inherit"
	});
};

$(function() {



		 // ********** 팝업 기본 사항 ************
			 $('.pop_open').click(function(){
				$('.dim').css("display","block");
				$('body').css({"overflow":"hidden","height":"100%"});
			 });
			 $('.pop_close').click(function(){
				 $('.pop_wrap').css("display","none");
				$('.dim').css("display","none");
				$('body').css({"overflow":"auto","height":"inherit"});
			 });

// 참고사항  변경 팝업은 클래스 chage_email_pop을 활용하시길 권해드립니다
// pop_open으로 열리고 pop_close 닫힙니다. 모든 팝업은 pop_wrap로 감싸져 있으며 이미지가 있는 팝업은 notice_img_pop가 클래스로 있습니다.


		//딤처리
		function dimShow() {
			$('.dim').css("display", "block");
			$('body').css({
				"overflow": "hidden",
				"height": "100%"
			});
		};

		function dimHide() {
			$('.dim').css("display", "none");
			$('body').css({
				"overflow": "auto",
				"height": "inherit"
			});
		};


		$('.code_send').click(function() {
		//	$('.email_pop_wrap').css("display", "block");
		//	$('.email_pop_wrap div').text("이메일 인증 번호가 전송되었습니다.");
		});


	//	$('.code_text_send').click(function() {
	//		$('.email_pop_wrap').css("display", "block");
	//		$('.email_pop_wrap div').text("문자 인증 번호가 전송되었습니다.");
	//	});

		$('.search_result_btn').click(function() {
//			$('.search_result').css("display", "block");
			$('.search_result').css("display", "none");
		});


		//forgot_password
		$('.fg_pw_ok_pop_open').click(function() {
			$('.fg_pw_ok_pop').css("display", "block");
		});



		//enroll
		$('.kyc_pop_btn').click(function() {
			$('.kyc_pop').css("display", "block");
		});
		/*
		$('.enroll_ok_pop_open').click(function() {
			$('.enroll_ok_pop').css("display", "block");
		});
		*/

		/*회원가입완료*/
		function enroll_result(){ 
			console.log("enroll_ok");
			$('.enroll_ok_pop').css("display", "block");
		}

		$('.enroll_cancel_pop_open').click(function() {
			$('.enroll_cancel_pop').css("display", "block");
		});


		//send coin
		$('.send_tran_open').click(function(){
			$('.send_tran_pop').css("display","block");
		});
		$('.low_bal_pop_open').click(function(){
			$('.low_bal_pop').css("display","block");
		});
		$('.low_gas_pop_open').click(function(){
			$('.low_gas_pop').css("display","block");
		});


		//send_chk
		$('.send_coin_ok_open').click(function(){
			$('.send_coin_ok_pop').css("display","block");
		});

		//exchange 
		$('.exchange_ok_pop_open').click(function(){
			$('.exchange_ok_pop').css("display","block");
		});
		$('.exchange_cancel_pop_open').click(function(){
			$('.exchange_cancel_pop').css("display","block");
		});

		

		//avatar
		$('.ava_pop_open').click(function() {
			$('.ava_pop_wrap').css("display", "block");
		});

		//support
		$('.support_ok_pop_open').click(function() {
			$('.support_ok_pop').css("display", "block");
		});



	});

</script>


<div class="dim"></div>
<div class="loader">
	<p><img src="/img/loader.png"></p>
	<div class="comment"></div>
</div>



<div class="search_result pop_wrap">
	<strong>RESULT</strong>
	<ul>
		<li>rose</li>
		<li>rose777</li>
		<li>rose7</li>
		<li>rose8</li>
		<li>rose99</li>
		<li>roserose</li>
	</ul>
	<a class="search_result_close btn_basic pop_close" data-i18n='popup.창닫기'>Close</a>
</div>

<div class="pop_wrap email_pop_wrap notice_img_pop">
	<p class="pop_title"data-i18n='popup.인증번호 전송'>Email verification</p>
	<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="체크">
	<div data-i18n='popup.인증번호가 이메일로 전송되었습니다'>Security code sent to your email</div>
	<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
</div>


<!-- 비밀번호 찾기 -->
<div class="pop_wrap notice_img_pop fg_pw_ok_pop">
	<p class="pop_title" data-i18n='popup.비밀번호 재설정'>Change Password</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span data-i18n='popup.이메일이 전송되었습니다'>Security code sent to your email</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>


<!-- enroll -->
<div class="kyc_pop pop_wrap">
	<div>
		<b>신분증 확인을 위한 사진을 찍는 법</b> <br /><br />

		- 이름, 성별, 국적, 신분증 유효일자, 사진을 인식할 수 있어야 합니다. <br /><br />

		- 신분증에 적힌 글자들을 읽을 수 있는 선명한 사진이어야 합니다.<br /><br />

		- 신분증을 들고 찍어야 하며 사진에 손이 나와야 합니다.<br /><br />

		- 선글래스나 모자 등 안면을 가리는것들이 없어야 합니다.<br /><br />

		- 아래 그림과 같이 신분증 내용과 얼굴이 가리지 않게 찍어야 합니다.<br /><br />

		- 카메라를 정면에 놓고 찍으십시오. 삐딱하게 찍으면 인식이 안 됩니다.<br /><br />

		신분증과 얼굴을 나란히 찍으면 신분증 글씨가 작아서 판독이 안 될때가 
		있으니 신분증을 든 손을 카메라 쪽으로 밀어서 찍으시면 좋습니다.<br /><br />
	</div>
	<p>
		<img src="<?=G5_THEME_URL?>/_images/kyc_img.gif" alt="이미지">
		<a href="javascript:void(0);" class="btn_basic pop_close" data-i18n='popup.창닫기'>Close</a>
	</p>
</div>

<div class="kyc_eng_pop pop_wrap">
	<div>
		<strong>How to take your ID confirmation photo</strong> <br /><br />
		Make sure all parts of the ID are visible and not covered by your hands. Your whole face should also be visible.<br /><br />
		
		- Use a high quality photo and make sure that text and numbers on the ID are readable.<br /><br />
		- You must be holding the IS in your hands and your hands should be visible in the image.<br /><br />
		- You may not wear sunglasses, a hat, or anything that covers your facial features.<br /><br />
		- Do not cover parts of the ID, message, or your face
	</div>
	<p>
		<img src="<?=G5_THEME_URL?>/_images/kyc_img.gif" alt="이미지">
		<a href="javascript:void(0);" class="btn_basic pop_close" data-i18n='popup.창닫기'>Close</a>
	</p>
</div>




<div class="pop_wrap notice_img_pop enroll_ok_pop">
	<p class="pop_title" data-i18n='popup.신규 회원등록 완료'>new member signup</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span data-i18n='popup.신규회원등록이 완료되었습니다'>signup completed successfully.</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close"  data-i18n='popup.창닫기'>Close</a>
	</div>
</div>

<div class="pop_wrap notice_img_pop enroll_cancel_pop">
	<p class="pop_title">신규 회원등록 실패</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span>신규회원 등록에 실패하였습니다.</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>



<!-- avatar -->
<div class="pop_wrap notice_img_pop ava_pop_wrap">
	<p class="pop_title">설정 저장</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span>적립 비율이 성공적으로 변경되었습니다</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>

<!-- support -->
<div class="pop_wrap notice_img_pop support_ok_pop">
	<p class="pop_title">티켓 전송</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span>티켓이 성공적으로 전송되었습니다</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>



<!-- send coin -->
<div class="pop_wrap send_tran_pop">
	<p class="pop_title"  data-i18n='popup.트랜젝션 승인'>Confirm the transaction</p>	
	<div>
		<p data-i18n='popup.트랜잭션 비밀번호를 입력하여 송금을 확인합니다.'>Please enter the transaction password to verify the transaction</p>
		<input type="text" placeholder="Transaction Password">
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close" data-i18n='popup.취소'>Cancle</a>
		<input type="button" value="Approve" class="not_btn_style">
	</div>
</div>

<div class="pop_wrap notice_img_pop low_bal_pop">
	<p class="pop_title" data-i18n='popup.지갑 잔고 부족'>Insufficient Funds</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span data-i18n='popup.지갑에 잔고가 부족합니다.'>Not enough balance</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>

<div class="pop_wrap notice_img_pop low_gas_pop">
	<p class="pop_title" data-i18n='popup.지갑 잔고 부족'>Insufficient Funds</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span data-i18n='popup.지갑에 개스비가 부족합니다.'>Insufficient funds to pay gas</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>




<!-- send chk -->
<div class="pop_wrap notice_img_pop send_coin_ok_pop">
	<p class="pop_title" data-i18n='popup.지갑잔고 부족'>Insufficient Funds</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span data-i18n='popup.지갑에 잔고가 부족합니다.'>Not enough balance</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>



<!-- exchange_bit -->
<div class="pop_wrap notice_img_pop exchange_ok_pop">
	<p class="pop_title" data-i18n='popup.환전 성공'>Exchange completed</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span data-i18n='popup.환전이 완료되었습니다.'>Exchange completed successfully</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>

<div class="pop_wrap notice_img_pop exchange_cancel_pop">
	<p class="pop_title" data-i18n='popup.환전 실패'>Exchange failed</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span data-i18n='popup.환전에 실패했습니다. 다시 시도하세요'>Exchange failed. Try again.</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close" data-i18n='popup.창닫기'>Close</a>
	</div>
</div>



<!-- profile -->
<div class="finger_pop pop_wrap">
	<p class="pop_title" data-i18n='popup.지문으로 로그인 하기'>Login with fingerprint</p>	
	<div>
	<p data-i18n='popup.지문으로 더 빠르고 안전하게 지갑을 열 수 있습니다.'>Use your fingerprint for faster, easier access to your wallet. 	</p>
    <p data-i18n='popup.지문 `센`서에 손가락을 대십시오.'>Confirm fingerprint to continue</p>
		<img src="<?=G5_THEME_URL?>/_images/fingerprint.gif" alt="Touch sensor" class="finger_img">
		<p class="text_center font_sky" data-i18n='popup.지문 승인 완료'>Fingerprint recognized</p>
    
		<!-- <p class="text_center font_red"  data-i18n='popup.지문 승인 실패'>Fingerprint not recognized</p> -->
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close"  data-i18n='popup.취소'>Cancle</a>
	</div>
</div>

<div class="finger_pop1 pop_wrap notice_img_pop">
	<p class="pop_title"  data-i18n='popup.지문 인식 해제 경고'>Fingerprint login disable warning</p>	
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="경고">
	<p data-i18n='popup.지문 인식 기능을 해제합니다'>	Are you sure you want to disable fingerprint login?</p>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close"  data-i18n='popup.취소'>Cancel</a>
		<a href="javascript:void(0);" class="pop_close"  data-i18n='popup.네'>Yes</a>
	</div>
</div>

			<script>
			$(function() {
				 $("#f_chk").change(function(){
					if($("#f_chk").is(":checked")){
						$('.finger_pop').css("display","block");
						$('.dim').css("display","block");
						$('body').css({"overflow":"hidden","height":"100%"});
					}else{
						$('.finger_pop1').css("display","block");
						$('.dim').css("display","block");
						$('body').css({"overflow":"hidden","height":"100%"});
					}
				});
			});
			</script>
			<!-- 지문설정 끝 -->



			<!-- 트랜잭션 비밀번호 변경 -->
			<div class="pop_wrap chage_tpw_pop">
				<p class="pop_title font_red">경고</p>	
				<div>
					본인의 비밀번호는 블록체인에 공유되거나 서버에 저장되지 않습니다. 즉, 우리는 회원의 비밀번호를 알 수도 없고 초기화 시킬 수도 없습니다. 회원의 지갑을 복구하기 위한 유일한 방법은 백업 구절을 통한 방법입니다. 비밀번호 분실시 지갑을 복구할 수 있는 유일한 방법인 백업 구절을 꼭 안전한 장소에 보관하시기 바랍니다.
				</div>
				<div class="pop_close_wrap">
					<a href="javascript:void(0);" class="go_tpw1">계속</a>
				</div>
			</div>

			<div class="pop_wrap chage_tpw_pop1 input_pop_css">
				<form action="">
					<label for="" data-i18n='popup.사용중인 트랜잭션 비밀번호'>Current Transaction Password</label>
					<input type="password">
					<label for="" data-i18n='popup.새로운 트랜잭션 비밀번호'>New Transaction Passwor</label>
					<input type="password">
					<label for="" data-i18n='popup.새로운 트랜잭션 비밀번호 확인'>Confirm New Transaction Password</label>
					<input type="password">
					<div>
						<label for="" data-i18n='popup.보안코드 입력'>Enter the security code</label>
						<p class="code_btn code_btn_tpw"><img src="<?=G5_THEME_URL?>/_images/email_send_icon.gif" alt="이미지" data-i18n='popup.코드요청'>Request code</p>
					</div>
					<input type="text" style="margin-bottom:25px;">
					<div class="btn2_btm_wrap">
						<input type="button" value="닫기" class="cancel pop_close" >
						<input type="button" value="저장" class="save go_tpw3">
					</div>
				</form>
			</div>
			<div class="pop_wrap chage_tpw_pop2 notice_img_pop">
				<p class="pop_title" data-i18n='popup.인증번호 전송'>Email verification</p>
				<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="체크">
				<div data-i18n='popup.인증번호가 이메일로 전송되었습니다'>Security code sent to your email.</div>
				<a href="javascript:void(0);" class="back_tpw1 gray_close f_right" data-i18n='popup.창닫기'>Close</a>
			</div>
			<div class="pop_wrap chage_tpw_pop3 notice_img_pop">
				<p class="pop_title" data-i18n='popup.트랜잭션 비밀번호 변경'>Change transaction password</p>	
				<div>
					<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
					<p data-i18n='popup.변경이 성공적으로 완료되었습니다'>Change successfully completed.
				</div>
				<div class="pop_close_wrap">
					<a href="javascript:void(0);" class="pop_close" data-i18n='popup.창닫기'>Close</a>
				</div>
			</div>



		<!--  비밀번호 변경 -->
			<div class="pop_wrap chage_pw_pop">
				<p class="pop_title font_red">경고</p>	
				<div>
					본인의 비밀번호는 블록체인에 공유되거나 서버에 저장되지 않습니다. 즉, 우리는 회원의 비밀번호를 알 수도 없고 초기화 시킬 수도 없습니다. 회원의 지갑을 복구하기 위한 유일한 방법은 백업 구절을 통한 방법입니다. 비밀번호 분실시 지갑을 복구할 수 있는 유일한 방법인 백업 구절을 꼭 안전한 장소에 보관하시기 바랍니다.
				</div>
				<div class="pop_close_wrap">
					<a href="javascript:void(0);" class="go_ch_pw1">계속</a>
				</div>
			</div>
			<div class="pop_wrap chage_pw_pop1 input_pop_css">
				<form action="">
					<label for="" data-i18n='popup.사용중인 비밀번호'>Current login password</label>
					<input type="password">
					<label for="" data-i18n='popup.새로운 비밀번호'>New login password</label>
					<input type="password">
					<label for="" data-i18n='popup.새로운 비밀번호 확인'>Confirm new login password</label>
					<input type="password">
					<div>
						<label for="" data-i18n='popup.보안코드 입력'>Enter the security code</label>
						<p class="code_btn code_btn_pw"><img src="<?=G5_THEME_URL?>/_images/email_send_icon.gif" alt="이미지" data-i18n='popup.코드요청'>Request code</p>
					</div>
					<input type="text" style="margin-bottom:25px;">
					<div class="btn2_btm_wrap">
						<input type="button" value="Close" class="cancel pop_close" >
						<input type="button" value="Save" class="save go_ch_pw3">
					</div>
				</form>
			</div>
			<div class="pop_wrap chage_pw_pop2 notice_img_pop">
				<p class="pop_title">인증번호 전송</p>
				<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="체크">
				<div>인증번호가 이메일로 전송되었습니다.</div>
				<a href="javascript:void(0);" class="back_pw1 gray_close f_right">Close</a>
			</div>
			<div class="pop_wrap chage_pw_pop3 notice_img_pop">
				<p class="pop_title">비밀번호 변경</p>	
				<div>
					<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
					변경이 성공적으로 완료되었습니다.
				</div>
				<div class="pop_close_wrap">
					<a href="javascript:void(0);" class="pop_close">Close</a>
				</div>
			</div>

			<div class="pop_wrap opt_cancel_wrap notice_img_pop">
				<p class="pop_title">이중 보안 (2-Factor) 해제 경고</p>	
				<div>
					<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
					이중보안 기능을 해제하시겠습니까?
				</div>
				<div class="pop_close_wrap">
					<a href="javascript:void(0);" class="pop_close">취소</a>
					<a href="javascript:void(0);" class="pop_close">네</a>
				</div>
			</div>











<!-- 이메일 주소 변경 -->


			<div class="pop_wrap chage_email_pop input_pop_css">
				<form>
				<label for="" data-i18n='popup.사용중인 이메일 주소'>Current Email</label>
				<input type="text"  name="current_email" id="current_email" value="">
				<label for="" data-i18n='popup.새로운 이메일 주소'>New Email</label>
				<input type="text"  name="email_new" id="email_new" value="">
				<label for="" data-i18n='popup.새로운 이메일 주소 확인'>Confirm New Email</label>
				<input type="text"  name="email_new_re" id="email_new_re" value="">
				<div>
					<label for="" data-i18n='popup.보안코드 입력'>Enter the security code</label>
					<p class="code_btn code_btn_em"><img src="<?=G5_THEME_URL?>/_images/email_send_icon.gif" alt="이미지" data-i18n='popup.코드요청' >Request code</p>
				</div>
				<input type="text"   name="email_vaild_code" id="email_vaild_code" style="margin-bottom:25px;">
				
				
				<div class="btn2_btm_wrap">
					<input type="button" value="Cancle" class="cancel pop_close" >
					<input type="button" value="Save" class="save">
				</div>
				</form>
			</div>
			
			
			<div class="pop_wrap chage_email_pop2 notice_img_pop">
				<p class="pop_title" data-i18n='popup.이메일 주소 인증'>Email verification</p>
				<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="체크">
				<div data-i18n='popup.인증번호가 이메일로 전송되었습니다.'>Security code sent to your email</div>
				<a href="javascript:void(0);" class="back_em1 gray_close f_right" data-i18n='popup.창닫기'>Close</a>
			</div>

			<div class="pop_wrap chage_email_pop1 notice_img_pop">
				<p class="pop_title" data-i18n='popup.이메일 변경'>Change Email address</p>	
				<div>
					<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
				<p data-i18n='popup.변경이 성공적으로 완료되었습니다.'>	Change successfully saved</p>
				</div>
				<div class="pop_close_wrap">
					<a href="javascript:parent.location.reload();" class="pop_close" data-i18n='popup.창닫기'>Close</a>
				</div>
			</div>

	
	
	<script>
		$(function() {

			var sendcode = false;
			//console.log(email_sendcode);

			$('.email_pop_open').click(function(){
				$('.chage_email_pop').css("display","block");
			});
			
			$('.go_ch_em1').click(function(){
				$('.chage_email_pop').css("display","none");
				$('.chage_email_pop1').css("display","block");
			});
			

			/*인증코드 발송*/
			$('.code_btn_em').click(function(){
				//console.log('인증코드발송' + email_sendcode);
				/*
				$.ajax({
						type: "POST",
						url: "",
						dataType: "json",
						data:  {
							"email" : $('.chage_email_pop #email_new').val(),
							"key" : email_sendcode,
							
						},
						success: function(data) {
							if(data.result =='success'){
								$('.back_em1').click(function(){
									$('.chage_email_pop2').css("display","none");
								});
							sendcode = true;
							}
						},
						error:function(e){
							dialogModal('Error!','<strong> Please check retry.</strong>','failed');	
						}
					});
				*/
			});

				
			
			$('.chage_email_pop .save').click(function(){
				//$('.chage_email_pop2').css("display","none");
				//console.log( $('.chage_email_pop #current_email').val() );
				var email2 = $('.chage_email_pop #email_new').val();
				var email3 = $('.chage_email_pop #email_new_re').val();	
				var email_vaild_code = $('.chage_email_pop #email_vaild_code').val();	
				

				if(email_vaild_code != email_sendcode){
					console.log('인증키 확인 필요' + sendcode);
					return false;
					//dialogModal('Please check','<strong> sercurity code does not matched.</strong>','failed');	
				}


				if( email2 != email3){
					dialogModal('Please check','<strong> new email does not matched confirm new mail.</strong>','failed');	
					return false;	
				}

				$.ajax({
						type: "POST",
						url: "/util/profile_proc.php",
						dataType: "json",
						data:  {
							"email1" : $('.chage_email_pop #current_email').val(),
							"email2" : email2,
							"email3" : email3,
							"confirm" : email_vaild_code,
							"category" : "email"
						},
						success: function(data) {
							if(data.result =='success'){
								$('.chage_email_pop2').css("display","none");
								$('.chage_email_pop1').css("display","block");
							}
						},
						error:function(e){
							dialogModal('Error!','<strong> Please check retry.</strong>','failed');	
						}
					});

			});
		});
		
		</script>
<!-- 이메일 주소 변경 -->	





<!-- 전화번호 변경 -->
	<div class="pop_wrap num_pop_wrap input_pop_css">
			<form action="">
				<label for="" data-i18n="popup.사용중인 전화번호">Current phone number</label>
				<div class="num_pop_div clear_fix" style="margin-bottom:20px;">
					<input type="input" id="nation_num" value="" placeholder="Country" maxlength="3">
					<input type="input" id="hp_num" value="" placeholder="Phone Number(Number only)" >
				</div>

				<label for="" data-i18n="popup.새로운 전화번호">New phone number</label>
				<div class="num_pop_div clear_fix">
					<input type="input" id="new_nation_num" value="" placeholder="Country" maxlength="3">
					<input type="input" id="new_hp_num" value="" placeholder="Phone Number(Number only)">
				</div>
				
				<div class="btn2_btm_wrap" style="margin-top:40px;">
					<input type="button" value="Cancle" class="cancel pop_close" >
					<input type="button" value="Proceed" class="save proceed">
				</div>
				
			</form>
		</div>

		<!-- 변경완료 -->
		<div class="pop_wrap num2_pop_wrap notice_img_pop">
			<p class="pop_title" data-i18n="popup.전화번호 변경">Change Phone Number</p>	
			<div>
				<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
			<p data-i18n="popup.변경이 성공적으로 완료되었습니다.">Change successfully completed</p>
			</div>
			<div class="pop_close_wrap">
				<a href="javascript:void(0);" class="pop_close" data-i18n="popup.창닫기">Close</a>
			</div>
		</div>

	<script>
		$(function() {
			var sendnumcode = false;
			
			$('.num_pop_open').click(function(){
				$('.num_pop_wrap').css("display","block");
			});

			$('.num1_pop_close').click(function(){
				$('.num_pop_wrap').css("display","block");
				$('.num1_pop_wrap').css("display","none");
			});
			$('.go_num2').click(function(){
				$('.num_pop_wrap').css("display","none");
				$('.num2_pop_wrap').css("display","block");
			});
			$('.proceed').click(function(){
				
				var hp_num = $('.num_pop_wrap #hp_num').val();
				var new_nation_num = $('.num_pop_wrap #new_nation_num').val();
				var new_hp_num = $('.num_pop_wrap #hp_num').val();
				//console.log( hp_num );

				/*
				if(phone_vaild_code != phone_sendcode){
					console.log('인증키 확인 필요' + sendcode);
					return false;
					//dialogModal('Please check','<strong> sercurity code does not matched.</strong>','failed');	
				}


				if( email2 != email3){
					dialogModal('Please check','<strong> new email does not matched confirm new mail.</strong>','failed');	
					return false;	
				}
				*/

				$.ajax({
						type: "POST",
						url: "/util/profile_proc.php",
						dataType: "json",
						data:  {
							"hp_num" : hp_num,
							"new_nation_num" : new_nation_num,
							"new_hp_num" : new_hp_num,
							"category" : "phone"

						},
						success: function(data) {
							if(data.result =='success'){
								$('.num_pop_wrap').css("display","none");
								$('.num3_pop_wrap').css("display","block");
							}else{
								dialogModal('Please check','<strong>'+data.sql+'</strong>','failed');	
							}
						},
						error:function(e){
							dialogModal('Error!','<strong> Please check retry.</strong>','failed');	
						}
					});

			});
		});
		//전화번호 변경
	</script>





		<script>
			$(function() {
			//  비밀번호변경 
			  $('.ch_pw_open').click(function(){
					//$('.chage_pw_pop').css("display","block");
					$('.chage_pw_pop1').css("display","block");
				});
				$('.go_ch_pw1').click(function(){
					$('.chage_pw_pop').css("display","none");
					$('.chage_pw_pop1').css("display","block");
				});
				$('.code_btn_pw').click(function(){
					$('.chage_pw_pop2').css("display","block");
				});
				$('.back_pw1').click(function(){
					$('.chage_pw_pop2').css("display","none");
				});
				$('.go_ch_pw3').click(function(){
					$('.chage_pw_pop1').css("display","none");
					$('.chage_pw_pop3').css("display","block");
				});
				
				

			//  트랜잭션 비밀번호변경 
			  $('.ch_tpw_open').click(function(){
					//$('.chage_tpw_pop').css("display","block");
					$('.chage_tpw_pop1').css("display","block");
				});
				$('.go_tpw1').click(function(){
					$('.chage_tpw_pop').css("display","none");
					$('.chage_tpw_pop1').css("display","block");
				});
				$('.code_btn_tpw').click(function(){
					$('.chage_tpw_pop2').css("display","block");
				});
				$('.back_tpw1').click(function(){
					$('.chage_tpw_pop2').css("display","none");
				});
				$('.go_tpw3').click(function(){
					$('.chage_tpw_pop1').css("display","none");
					$('.chage_tpw_pop3').css("display","block");
				});

				
				if($("#o_chk").is(":checked")){
					$('.opt_wrap').css("display","none");
				};

				$("#o_chk").change(function(){
					if($("#o_chk").is(":checked")){
						$('.opt_wrap').css("display","none");
					}else{
						$('.opt_cancel_wrap').css("display","block");
						$('.dim').css("display","block");
						$('body').css({"overflow":"hidden","height":"100%"});
						$('.opt_wrap').css("display","block");
					}
				});
			


		});
	</script>






<!-- 추천인 링크 -->
<!--
<div class="pop_wrap notice_img_pop link_pop">
	<p class="pop_title"  data-i18n="popup.추천 링크">Referral link</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/comform_chk.gif" alt="이미지">
		<span  data-i18n="popup.추천 링크가 복사되었습니다.">Referral link copied to clipboard.</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close gray_close"  data-i18n="popup.창닫기">Close</a>
	</div>
</div>
-->

<script>
		$(function() {
		//send_chk
		$('.link_pop_open').click(function(){
			$('.link_pop').css("display","block");
		});
	});
</script>












<!-- 로그아웃 -->
<div class="pop_wrap notice_img_pop logout_pop" style="z-index:9999;">
	<p class="pop_title"  data-i18n="popup.로그 아웃">Logout</p>
	<div>
		<img src="<?=G5_THEME_URL?>/_images/notice_pop.gif" alt="이미지">
		<span  data-i18n="popup.로그아웃 하시겠습니까?">Are you sure to log out?</span>
	</div>
	<div class="pop_close_wrap">
		<a href="javascript:void(0);" class="pop_close" data-i18n="popup.취소">Cancel</a>
		<a href="/bbs/logout.php" data-i18n="popup.네">Yes</a>
	</div>
</div>

<script>
		$(function() {
		$('.logout_pop_open').click(function(){
			$('.logout_pop').css("display","block");
		});
	});
</script>

