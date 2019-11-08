<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 

if($_GET['recom_referral'])
	$recom_sql = "select mb_id from g5_member where mb_no = '{$_GET['recom_referral']}'";
	$recom_result = sql_fetch($recom_sql);

	$mb_recommend = $recom_result['mb_id'];
?>


<script type="text/javascript">
	var captcha;
	var key;
	var verify = false;
	var recommned = "<?=$mb_recommend?>";
	var recommend_search = false;
	
	if(recommned){
		recommend_search = true;
	}
	//console.log(recommend_search);

$(function(){

	/*초기설정*/
	$('.agreement_ly').hide();
	$('.verify_phone').hide();
	$('#verify_txt').hide();

	$('#nation_number').on('change',function(e){
		if(['1','81','82'].indexOf($(this).val()) !== -1 ){
			// sms 인증 사용
			//$('.verify_phone').show();
			         //TestClass를 NoClass로 변경한다.			
		}else{
			$('.verify_phone').hide();
		}
	});
	
	/*이메일 체크*/
	 validateEmail = function (email) {
		var email = email;
		var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;

		if (email == '' || !re.test(email)) {
			alert("올바른 이메일 주소를 입력하세요")
			return false;
		}
	}


	$('#btnSave').on('click',function(e) {
		$('#reg_mb_recommend').val($('#referral .modal-body .user.selected').html());
		$('#referral').modal('hide');
	});


	$('#sendSms').on('click', function(e){
		if(!$('#reg_mb_hp').val()){
			commonModal('Mobile authentication','<p>Please enter your Mobile Number</p>',80);
			return;
		}
		var reg_mb_hp = + ($('#reg_mb_hp').val().replace(/-/gi,''));
		$.ajax({
			url: '/bbs/register.sms.verify.php',
			type: 'post',
			async: false,
			data: {
				"nation_no": $('#nation_number').val(),
				"mb_hp": reg_mb_hp
			},
			dataType: 'json',
			success: function(result) {
				// console.log(result);
				smsKey = result.key;
				commonModal('SMS authentication','<p>Sent a authentication code to your Mobile.</p>',80);
			},
			error: function(e){
				console.log(e);
			}
		});
	});


	$('#sendMail').on('click', function(e){
		//console.log('sendmail');
		if(!$('#reg_mb_email').val()){
			commonModal('Mail authentication','<p>Please enter your mail</p>',80);
			return;
		}
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
				
			commonModal('Mail authentication','<p>Sent a authentication code to your mail.</p>',80);
			},
			error: function(e){
				console.log(e);
			}
		});
	});

	$('#vCode').on('change', function(e){
		console.log( $('#vCode').val().trim() );
		if(key == sha256( $('#vCode').val().trim()) ){
			// 메일 인증 성공
			console.log( "verify OK" );
			verify = true;
			$('#verify_txt').show();
			$('#reg_mb_email').css('background-color','#ccc').prop('readonly', true);;
			
		}else{
			commonModal('Do not match','<p>Email verification code is incorrect. Please enter the correct code</p>',80);
		}
	});

	$(document).on('click','#referral .modal-body .user',function(e) {
		$('#referral .modal-body .user').removeClass('selected');
		$(this).addClass('selected');
	});

	$('#reg_mb_password').on('keyup',function(e){
		
		chkPwd_1($('#reg_mb_password').val());
	});

	$('#reg_tr_password').on('keyup',function(e){
		
		chkPwd_2($('#reg_tr_password').val());
	});
});

function chkPwd_1(str){
	var pw = str;
	var num = pw.search(/[0-9]/g);
	var eng = pw.search(/[a-z]/ig);
	var eng_large = pw.search(/[A-Z]/ig);
	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);
	if(pw.length < 8 || pw.length > 20)
		$("#pm_1").attr('class','x_li'); 		
	else
		$("#pm_1").attr('class','o_li'); 		

	if( eng < 0  )		
		$("#pm_2").attr('class','x_li'); 		
	else
		$("#pm_2").attr('class','o_li'); 		


	if(num < 0 )		
		$("#pm_3").attr('class','x_li'); 		
	else
		$("#pm_3").attr('class','o_li'); 		

	if(spe < 0 )
		$("#pm_4").attr('class','x_li'); 		
	else
		$("#pm_4").attr('class','o_li'); 		

	return true;
}

function chkPwd_2(str){
	console.log('chkPwd_2');

	var pw = str;
	var num = pw.search(/[0-9]/g);
	var eng = pw.search(/[a-z]/ig);
	var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

	if(pw.length < 8 || pw.length > 20)
		$("#pt_1").attr('class','x_li'); 		
	else
		$("#pt_1").attr('class','o_li'); 		

	if( eng < 0 )		
		$("#pt_2").attr('class','x_li'); 		
	else
		$("#pt_2").attr('class','o_li'); 		

	if(num < 0 )		
		$("#pt_3").attr('class','x_li'); 		
	else
		$("#pt_3").attr('class','o_li'); 		

	if(spe < 0 )
		$("#pt_4").attr('class','x_li'); 		
	else
		$("#pt_4").attr('class','o_li'); 		

	return true;
}


/*추천인등록*/
	function getUser(etarget,type){
	var target = etarget;
	if(type  == 1){
		var target_type = "#referral";
	}else{
		var target_type = "#sponsor";
	}
	//console.log($(target).val());

	$.ajax({
			type:'POST',
			url:'/bbs/ajax.recommend.user.php',
			data: {
				mb_id : $(target).val()
			} ,
			success: function(data){
				var list = JSON.parse(data);
				if(list.length > 0){
					$(target_type).modal('show');
					var vHtml = $('<div>');
					$.each(list, function( index, obj ) {
						vHtml.append($('<div>').addClass('user').html(obj.mb_id));
					});

					$(target_type + ' .modal-body').html(vHtml.html());
				}else {
					
					commonModal('Notice','MEMBER NOT FOUND',80);
				}
			}
		});

		$(document).on('click','.modal-body .user',function(e) {
			//console.log($( target + ' .modal-body .user'));
			$( target + ' .modal-body .user').removeClass('selected');
			$(this).addClass('selected');
		});

		$('#btnSave').on('click',function(e) {
			recommend_search = true;
			console.log("click "+ recommend_search);

			$(target).val( $( target_type + ' .modal-body .user.selected').html());
			$(target_type).modal('hide');
		});
	} ///*추천인등록*/



	// submit 최종 폼체크
	function fregisterform_submit(){
		var f = $('#fregisterform')[0];
		//console.log(recommend_search);
		/*
		if(key != sha256($('#vCode').val())){
		 	commonModal('Do not match','<p>Please enter the correct code</p>',80);
		 	return false;
		}
		*/

		if (f.mb_password.value != f.mb_password_re.value) {
			commonModal('check password','<strong>비밀번호가 같지 않습니다.</strong>',80);
			
			f.mb_password_re.focus();
			return false;
		}
		if (f.mb_password.value.length > 0) {
			if (f.mb_password_re.value.length < 3) {
				commonModal('check password','<strong>비밀번호를 3글자 이상 입력하십시오.</strong>',80);
				
				f.mb_password_re.focus();
				return false;
			}
		}

		if(verify == false){
			commonModal('check e-mail verifiy','<strong>Enter the verification code to verify your email address</strong>',80);
			return false;
		}
		

		if (f.mb_recommend.value =='' || f.mb_recommend.value =='undefined') {
			commonModal('check recommend','<strong>check recommend.</strong>',80);
			return false;
		}
		if(!recommend_search){
			commonModal('Please check recommend search Button','<strong>Please check recommend search Button and choose recommend.</strong>',80);
			return false;
		}

		if (f.mb_id.value == f.mb_recommend.value) {
			commonModal('check recommend','<strong> can not recommend self. </strong>',80);
			f.mb_recommend.focus();
			return false;
		}

		if(!$('#agree').prop('checked')){
			commonModal('check the policy agreement!!','<strong>check the policy agreement!!</strong>',80);
			return false;
		}

		f.submit();

	}



/*이용약관*/
function agreementModal(title ){
	$('#agreement').modal('show');
	$('#agreement .modal-header .modal-title').html(title);
	$('#agreement .modal-body').load('<?=G5_THEME_URL?>/policy.html');
	$('#agreement .modal-body').css('height','auto');
	$('#closeModal').focus();
}
$(document).on('click','.agreeement_show',function(e) {
	agreementModal('agreement');

	$('#agreement .yes')	.on('click',function(e) {
		$('.agreement_ly').show();
		$('.agreement_btn').hide();
		$('#agree').attr("checked", true);
	});
});

$(document).on('click','#agree',function(e) {
	$('.agreement_btn').show();
});

</script>

<style>
	.agreement_btn{text-align:center;}
		.agreement_btn button{}
	#nation_number{height:40px;width:120px;font-size:0.9em;}
	hr{border-top:2px solid rgba(0,0,0,0.1)}
	.btn{padding:5px 20px;}
</style>


	<section id="wrapper" class="bg_white">
		<div class="v_center">
			<div class="enroll_wrap">

				<form id="fregisterform" name="fregisterform" action="/bbs/register_form_update.php" method="post" enctype="multipart/form-data" autocomplete="off">
				<div>
					<div>
						<input type="text" minlength="4"  name="mb_id"  id="reg_mb_id"  placeholder="Username (4 ~ 10 letters. No special character allowed)" data-i18n='[placeholder]register.유저네임 (4~ 10자리, 특수기호 사용불가)'/>
					</div>
					<ul class="clear_fix pw_ul">
						<li>
							<input type="password" name="mb_password" id="reg_mb_password"  minlength="8" maxlength="20" placeholder="Login Password" data-i18n='[placeholder]register.로그인 비밀번호'/>
							<input type="password" name="mb_password_re" id="reg_mb_password_re" minlength="8" maxlength="20" placeholder="Confirm login password" data-i18n='[placeholder]register.비밀번호 확인'/>
							
							<strong ><span data-i18n='register.강도 높은 비밀번호 설정 조건' >Your password must contain</span></strong>
							<ul>
								<li class="x_li" id="pm_1" data-i18n='register.8자 이상 20자 이하' >8 to 20 characters long</li>
								<li class="x_li" id="pm_2" data-i18n='register.영문 대문자와 소문자 조합' >Capital & Small Letter</li>
								<li class="x_li" id="pm_3" data-i18n='register.숫자' >Digits</li>
								<li class="x_li" id="pm_4" data-i18n='register.특수 기호' >Special Characters</li>
							</ul>
						</li>
						<li>
							<input type="password" minlength="8" maxlength="20" id="reg_tr_password" name="reg_tr_password" placeholder="Transaction Password" data-i18n='[placeholder]register.트랜잭션 비밀번호'/>
							<input type="password" minlength="8" maxlength="20" id="reg_tr_password_re" name="reg_tr_password_re" placeholder="Confirm transaction password" data-i18n='[placeholder]register.트랜잭션 비밀번호 확인'/>
							
							<strong ><span data-i18n='register.강도 높은 비밀번호 설정 조건'>Your password must contain</span></strong>
							<ul>
								<li class="x_li" id="pt_1" data-i18n='register.8자 이상 20자 이하' >8 to 20 characters long</li>
								<li class="x_li" id="pt_2" data-i18n='register.영문 대문자와 소문자 조합' >Capital & Small Letter</li>
								<li class="x_li" id="pt_3" data-i18n='register.숫자'>Digits</li>
								<li class="x_li" id="pt_4" data-i18n='register.특수 기호' >Special Characters</li>
							</ul>
						</li>
					</ul>
				</div>
				
				
				<div class="check_appear">
					<p class="check_appear_title"><span data-i18n='register.개인 정보와 인증 (KYC 요령)'>Personal Information & Authentication </span>
					<!--<small class="f_right font_red kyc_pop_btn pop_open">KYC 요령</small>--></p>
					<input type="text" name="first_name" placeholder="First Name (Must match the legal name on file)" data-i18n='[placeholder]register.이름 (신분증에 기록된 이름과 동일해야 함)'/>
					<input type="text" name="last_name" placeholder="Last Name (Must match the legal name on file)" data-i18n='[placeholder]register.성 (신분증에 기록된 이름과 동일해야 함)'/>
					
					<style>
						.file_wr{
							_border: 1px solid #ccc;
							_background: #fff;
							border: 1px solid #757575;
    						background: #f5f7f9;
							color: #000;
							display:inline-block;
							vertical-align: middle;
							border-radius: 3px;
							padding: 5px;
							height: 40px;
							margin: 0;
							width:70%;
						}
						.lb_icon {
							position: relative;
							top: 0px;
							left: 0px;
							border-radius: 3px 0 0 3px;
							height: 34px;
							line-height: 34px;
							width: 36px;
							background: #eee;
							text-align: center;
							color: #888;
						}
						.frm_file{width:70%;}
						.frm_confirm{
							float:left;
							display: inline-block;
							color: #006df3;
							font-size: inherit;
							line-height: normal;
							vertical-align: middle;
							cursor: pointer;
							border-radius: .25em;
							background: url(<?=G5_THEME_URL?>/_images/upload_icon.gif) no-repeat left center;
							background-size: 25px;
							padding-left: 30px;
							line-height: 38px;
							border:0;
							float: right;
						}
						
					</style>
					<!--
					<div class="clear_fix id_file_wrap">
						
						<p data-i18n="신분증을 든 사진 업로드">Upload photo with ID</p>
						
						<div class="file_wr write_div">
							<label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i></label>
							<input type="file" name="bf_file[]" id="ex_filename"  class="frm_file ">
						</div>
						<button type="button" class="frm_confirm" id="frm_confirm"  data-i18n="파일업로드">File upload</button>
						
						
						<div class="filebox"> 
							<input class="upload-name" value="파일선택" disabled="disabled">
							<label for="ex_filename">파일업로드</label>
							<input type="file" id="ex_filename" class="upload-hidden">
						</div>
						
					</div>
						
					<p class="text_right font_green mb20" data-i18n="업로드 성공">Upload successful</p>
					
 					<p class="text_right font_red mb20" data-i18n="업로드 실패">Upload failed</p> -->
						
				
					<input type="email" name="mb_email" id="reg_mb_email" onChange="validateEmail(this.value);" placeholder="Email address" data-i18n='[placeholder]register.이메일 주소'/>

					<div class="clear_fix ecode_div">
						<div class="sendbtn">
						<a href="javascript:void(0);" class="btn" id="sendMail">
							<img src="<?=G5_THEME_URL?>/_images/email_send_icon.gif" alt="이메일코드">
							<span data-i18n="register.이메일인증번호">Email authentication code</span>
						</a>
						</div>

						<input type="text" name="vCode" placeholder="Enter Email Authtication Code" id="vCode" required class="input-search" maxlength="10" data-i18n='[placeholder]register.이메일 승인 번호' >
						
						<p id="verify_txt" class="text_right font_green mb20" data-i18n="register.인증 완료">verification complete</p>
					</div>
<hr>
					<div class="div46">
					<select id="nation_number" name="nation_number" required >
						<option value="country" data-i18n="register.select" >Select Country</option>
						<option value="1">001 - USA</option>
						<option value="61">061 - Australia</option>
						<option value="81">081 - Japan</option>
						<option value="82">082 - Korea</option>
						<option value="84">084 - Vietnam</option>
						<option value="86">086 - China</option>
					</select>

<!--<input type="text" placeholder="거주 국가 선택"/> -->
						<input type="text" name="mb_hp"  id="reg_mb_hp"  pattern="[09]*" placeholder="Phone number" data-i18n='[placeholder]register.전화번호'/>
					</div>

					<div class="clear_fix ecode_div">
					<div class="verify_phone">
						<input type="text" placeholder="Enter Phone Authtication Code"/>
						<a href="javascript:void(0)" class=""  id="sendSms">
							<img src="<?=G5_THEME_URL?>/_images/email_send_icon.gif" alt="이메일코드">
							Enter Phone Authtication Code
						</a>
						</div>
					</div>
	
					<div class="btn_input_wrap mb10">
						<input type="text" name="mb_recommend" id="reg_mb_recommend"  value="<?=$mb_recommend ?>" placeholder="Referrers Username"  required data-i18n='[placeholder]register.추천인 유저네임'/>
						<a href="javascript:getUser('#reg_mb_recommend',1);" class="search_result_btn" data-i18n='register.추천인 검색'>Search Referrer</a>
					</div>
					<!--
					<div class="btn_input_wrap mb10">
						<input type="text" pattern="[09]*" placeholder="센터번호"/>
						<a href="javascript:void(0);" class="search_result_btn pop_open">센터 검색</a>
					</div>
					-->
					
					<hr>	
					<div class="agreement_btn"> <button type="button" class="agreeement_show btn"><span data-i18n='register.회원가입 약관보기'>Read Terms and Conditions</span></button></div>

					<div class="mb20 agreement_ly">
						<div class="checkbox_wrap"><input type="checkbox" id="agree" class="checkbox"><label for="agree"></label></div>
						<span data-i18n='register.본인은 약관을 읽었으며 이에 동의합니다. 본인은 V7사업을 완전히 이해 하였으며 반품이나 반환이 불가능한 것을 알고 있습니다. 추천인과 후원인의 변경 또한 불가능한 것을 알고 있으며 이에 동의 합니다.'>
            I have read and agreed to the Terms and Conditions. I fully understood the V7 business and I know the NO REFUND policy. I know the change of sponsor and upline is NOT POSSIBLE.</span>
					</div>
					
<!-- 					<div style="height:100px; text-align: center; background:#eee;">
						캡챠영역
					</div> -->
					
					<div class="btn2_wrap">
						<!-- <input class="btn_basic mt20" type="button" value="취소" onClick="history.back(-1);">
						<input class="btn_basic mt20" type="button" value="신규 회원 등록하기" onClick="location.href='dashboard.php'"> -->
						
						<input class="btn_basic mt20 enroll_cancel_pop_open pop_open" type="button" value="Cancel" data-i18n='[value]register.취소'>
						<input class="btn_basic mt20" type="button" onclick="fregisterform_submit();" value="Enroll new member" data-i18n='[value]register.신규 회원 등록하기'>
					</div>
				</div>
					
				</form>
			</div>

		</div>

	</section>
	
	
	<div class="gnb_dim"></div>
	

	<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_enroll.png' alt='아이콘'> <span data-i18n='title.신규 회원등록'>Create a new account</span>");
			$('#wrapper').css("background","#fff");

			$('#frm_confirm').on('click',function(){
				
				var file_data = $('#ex_filename').prop('files')[0];
				var form_data = new FormData();
				
				form_data.append('w', '');
				form_data.append('wr_subject', $('#reg_mb_id').val());
				form_data.append('bf_file', file_data);
				console.log(form_data);

				$.ajax({
						
						url: '/util/file_upload.php',
						dataType: 'json',
						cache: false,
						contentType: false,
						processData: false,
						type: "POST",
						data:form_data,
						success: function(data) {
							if(data.result =='success'){
								console.log('success');
							}
							else{
								dialogModal('Error!','<strong>'+ data.sql +'</strong>','failed');	
							}
						},
						error:function(e){
							dialogModal('Error!','<strong> Please check retry.</strong>','failed');	
						}
					});
			});
		});


	</script>


<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>





