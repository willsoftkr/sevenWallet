<?php include_once('./_common.php');?>
<?

if ($now_id){
	$main_id = $now_id;
}else{
	$main_id = $member['mb_id'];
}


$sql = "select count(*) as cnt from g5_member where mb_brecommend='{$main_id}'";
$row = sql_fetch($sql);

if ($row['cnt']==2){ //PASS

}else if ($row['cnt']==1){ //PASS
	$sql  = "select * from g5_member where mb_brecommend='{$main_id}'";
	$row2 = sql_fetch($sql);
	if ($row2['mb_brecommend_type']=="L"){
		$brecommend_type = "R";		
	}else{
		$brecommend_type = "L";		
	}
	$brecommend      = $main_id;

}else{ //없으면
	$brecommend = $main_id;
	$brecommend_type = "L";		
}

?>
<!DOCTYPE html>
<html>
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" type="text/css" href="css/enrollmember.css">
	
	<script>
		// submit 최종 폼체크
		function fregisterform_submit(f)
		{
			// 회원아이디 검사
			if (f.w.value == "") {
				var msg = reg_mb_id_check();
				if (msg) {
					alert(msg);
					f.mb_id.select();
					return false;
				}
			}
			if (f.w.value == "") {
				if (f.mb_password.value.length < 3) {
					alert("비밀번호를 3글자 이상 입력하십시오.");
					f.mb_password.focus();
					return false;
				}
			}

			if (f.mb_password.value != f.mb_password_re.value) {
				alert("비밀번호가 같지 않습니다.");
				f.mb_password_re.focus();
				return false;
			}

			if (f.mb_password.value.length > 0) {
				if (f.mb_password_re.value.length < 3) {
					alert("비밀번호를 3글자 이상 입력하십시오.");
					f.mb_password_re.focus();
					return false;
				}
			}

			// E-mail 검사
			if ((f.w.value == "") || (f.w.value == "u" && f.mb_email.defaultValue != f.mb_email.value)) {
				var msg = reg_mb_email_check();
				if (msg) {
					alert(msg);
					f.reg_mb_email.select();
					return false;
				}
			}
			return true;
		}

		$.i18n.init({ 
			resGetPath: '/locales/my/__lng__.json', 
			load: 'unspecific', 
			fallbackLng: false, 
			lng: 'eng' 
		}, function (t){ 
			$('body').i18n(); 
		}); 

		$(document).ready(function(){
			if(localStorage.getItem('myLang') || localStorage.getItem('myLang') == 'eng'){
				i18n.setLng(localStorage.getItem('myLang'), function(){ 
					$('body').i18n(); 
				}); 
			}
		});
	</script>
</head>
</body>
<form class="shadow" id="fregisterform" name="fregisterform" action="../bbs/register_form_update.php" onsubmit="return fregisterform_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="w" value="">
	<input type="hidden" name="wx" value="Y">
	<input type="hidden" name="url" value="">
	<input type="hidden" name="agree" value="">
	<input type="hidden" name="agree2" value="">
	<input type="hidden" name="cert_type" value="">
	<input type="hidden" name="cert_no" value="">
	<input type="hidden" name="mb_sex" value=""> 
	<h2 class="blue" data-i18n="enroll.title">Enroll Member</h2>
	<input type="text" name="mb_id" value="" id="reg_mb_id" oninvalid="this.setCustomValidity('Enter Username Here')" oninput="this.setCustomValidity('')" required="" placeholder="Username" data-i18n="[placeholder]enroll.username" >
	<input type="password" name="mb_password" id="reg_mb_password" required="" class="frm_input required" minlength="3" maxlength="20" oninvalid="this.setCustomValidity('Enter Password Here')" oninput="this.setCustomValidity('')" placeholder="Password" data-i18n="[placeholder]enroll.pass" >
	<input type="password"  name="mb_password_re" id="reg_mb_password_re" required="" class="frm_input required" oninvalid="this.setCustomValidity('Enter Password Here')" oninput="this.setCustomValidity('')" minlength="3" maxlength="20" placeholder="Verify Password" data-i18n="[placeholder]enroll.pass2" >
	<input type="text" id="first_name" name="first_name" value="" required="" class="frm_input required " size="10" oninvalid="this.setCustomValidity('Enter First Name Here')" oninput="this.setCustomValidity('')" placeholder="First Name" data-i18n="[placeholder]enroll.fName" >
	<input type="text" id="last_name" name="last_name" value="" required="" class="frm_input required " size="10" oninvalid="this.setCustomValidity('Enter Last Name Here')" oninput="this.setCustomValidity('')" placeholder="Last Name" data-i18n="[placeholder]enroll.lName" >
	<input type="text" name="mb_email" placeholder="Email" value="" id="reg_mb_email" required="" class="frm_input email required" size="70" maxlength="100" oninvalid="this.setCustomValidity('Enter Email Here')" oninput="this.setCustomValidity('')" data-i18n="[placeholder]enroll.email" >
	<select id="nation_number" name="nation_number" required="">
		<option value="country" data-i18n="enroll.country" >Select Country</option>
		<option value="001">USA - 001</option>
		<option value="061">Australia - 061</option>
		<option value="081">Japan - 081</option>
		<option value="082">Korea - 082</option>
		<option value="084">Vietnam - 084</option>
		<option value="086">China - 086</option>
	</select>
	<input class="mobile-input" type="text" name="mb_hp" value="" id="reg_mb_hp" required="" maxlength="20" placeholder="Mobile Number" data-i18n="[placeholder]enroll.mobile" >
	<span data-i18n="enroll.ref" >Referrer's Username</span>
	<input class="input-search" value="<?php echo $now_id ?>" type="text" name="mb_recommend" id="reg_mb_recommend"  placeholder="Referrer's Username" data-i18n="[placeholder]enroll.ref" <?php echo $required ?> >

	<label for="chkCondition2" data-i18n="[html]enroll.chk" >
		<input type="checkbox" id="chkCondition2" class="checkbox"> I have read and agree to the <span class="blue tas" data-toggle="modal" data-target="#tac">Terms and Conditions </span>  I fully understood the FIJI business and I know the NO REFUND policy. 
	</label>

	<button class="submit-button" data-i18n="enroll.newAcc" >CREATE NEW ACCOUNT</button>
</form>

</body>
</html>