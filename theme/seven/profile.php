
<?php include '_include/gnb.php'; ?>

<?
/* 추천인정보 */

$refferal_sql = "select short_code from url_shorten where mb_no ='{$member['mb_no']}'";
$refferal_result = sql_fetch($refferal_sql);
$ref_url = G5_URL."/go/".$refferal_result['short_code'];

//echo $ref_url;

// 임의의수까지 숫자 랜덤
/*
function generate_code($length = 6) {   
	$numbers  = "0123456789";   
	//$svcTxSeqno = date("YmdHis");   
	$nmr_loops = 6;   
	while ($nmr_loops--) {   
		$svcTxSeqno .= $numbers[mt_rand(0, strlen($numbers))];   
	}   
	return $svcTxSeqno;   
}
*/

//길이만큼 영문숫자 랜덤코드 /lib/common.php
/*
function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
*/
$security_code= generateRandomString(6);
echo $security_code;
?>


	<script src="<?=G5_THEME_URL?>/_common/js/qrcode.js"></script>
	<script>
		const email_sendcode = "<?=$security_code?>";
		/* 링크카피 */
		
		function copyToClipboard(element) {
			
			purchaseModal("Referral link","Referral link copied to clipboard.",'success');
			
			console.log( $(element).val() );

			var $temp = $("<input>");
				$("body").append($temp);
				$temp.val( $(element).val() ).select();
				document.execCommand("copy");
				$temp.remove();
			}
		
		
		/* QR코드 */
		$(window).load(function(){
			$('#qrcode').empty();
			var url = "<?=$ref_url?>";

			new QRCode(document.getElementById("qrcode"), {
				text: url,
				width: 200,
				height: 200,
				colorDark : "#000000",
				colorLight : "#ffffff",
				correctLevel : QRCode.CorrectLevel.H
			});
		});


	</script>

			<section class="profile_wrap">
			
				<div class="prof_1st">
					<h5 data-i18n="profile.개인정보">Profile</h5>
					<div><span data-i18n="profile.유저네임">Username</span> : <?=$member['mb_id']?></div>
					<hr>
					<div>
						<span data-i18n="profile.이름">Name</span>: <?=$member['first_name']." ".$member['last_name']?>
						<p class="f_right">
							KYC <span class="font_red">실패</span>
<!--							KYC <span class="font_green">성공</span>-->
						</p>
					</div>
					<hr>
					<div class="clear_fix id_file_wrap">
						<span>신분증을 든 사진 업로드</span>
						<div class="filebox"> 
							<input class="upload-name" value="파일선택" disabled="disabled">
							<label for="ex_filename">파일업로드</label>
							<input type="file" id="ex_filename" class="upload-hidden">
						</div>
					</div>
					<p class="font_green">업로드 성공</p>
<!--					<p class="font_red">업로드 실패</p>-->
					<hr>
					<ul>
						<li><span data-i18n="profile.이메일">Email</span>: <?=$member['mb_email']?> <img src="<?=G5_THEME_URL?>/_images/okay_icon.gif" alt="인증됨" style="width:15px;"></li>
						<li><input type="button" value="Change" class="email_pop_open pop_open" data-i18n="[value]profile.변경"></li>
					</ul>
					<hr>
					<ul>
						<li><span data-i18n="profile.전화번호">Phone number</span>: <?=$member['mb_hp']?> <img src="<?=G5_THEME_URL?>/_images/x_icon.gif" alt="인증안됨" style="width:15px;"></li>
						<li><input type="button" value="Change"" class="num_pop_open pop_open" data-i18n="[value]profile.변경"></li>
					</ul>
					<hr>
					<ul>
						<li><span data-i18n="profile.바이너리 팩 (B 팩) 자동 재구매">B Pack auto-repurchase</span></li>
						<li>
							<div class="round_chkbox">
							  <input type="checkbox" id="b_chk" checked disabled>
							  <label for="b_chk"><span></span></label>
							</div>
						</li>
					</ul>
					<hr>
					<ul>
						<li><span data-i18n="profile.수당 팩 (Q 팩) 자동 재구매">Q Pack auto-repurchase</span></li>
						<li>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk" disabled>
							  <label for="q_chk"><span></span></label>
							</div>
						</li>
					</ul>
					
				</div>
				
				<div>
					<h5><span data-i18n="profile.보안설정">Security setting</span></h5>
					<ul>
						<li><span data-i18n="profile.로그인 비밀번호 변경">Change login password</span></li>
						<li><input type="button" value="change" class="ch_pw_open pop_open" data-i18n="[value]profile.변경"></li>
					</ul>
					<hr>
					<ul>
						<li><span data-i18n="profile.트랜젝션 비밀번호 변경">Change transaction password</span></li>
						<li><input type="button"  value="change" class="ch_tpw_open pop_open" data-i18n="[value]profile.변경"></li>
					</ul>
					<hr>
					<!--
					<ul>
						<li>지문으로 지갑열기</li>
						<li>
							<div class="round_chkbox">
							  <input type="checkbox" id="f_chk">
							  <label for="f_chk"><span></span></label>
							</div>
						</li>
					</ul>

					<hr>
					<ul>
						<li>
							이중보안 (2-Factor)
						</li>
						<li>
							<div class="round_chkbox">
							  <input type="checkbox" id="o_chk">
							  <label for="o_chk"><span></span></label>
							</div>
						</li>
					</ul>
					<div class="opt_wrap">
						<small>이중보안을 설정하여 지갑을 외부로부터 보다 안전하게 지킬 수 있습니다.</small>
						<img src="<?=G5_THEME_URL?>/_images/otp_qr.gif" alt="otp 이미지">
						<p>
							1. Authy App으로 QR 코드를 스캔합니다.<br>
							2. 화면에 나오는 6자리 숫자를 아래에 입력합니다.
						</p>
						<input type="text">
						<input type="button" value="코드 확인">
						앱이 없으시다면? <a href=" https://play.google.com/store/apps/details?id=com.authy.authy">여기서 다운 받으세요.</a>
					</div>
				</div>
				-->
				<div>
					<h5><span data-i18n="profile.추천인 정보">Referral</span></h5>
					<ul>
						<li><span data-i18n="profile.나의 추천 링크">My Referral Link</span></li>
						<li><input type="button" value="Copy" class="" data-i18n="[value]profile.복사" onclick="copyToClipboard('#ref_link')"></li>
						<li><input type="hidden" name="ref_link" id="ref_link" value="<?=$ref_url?>"/></li>
					</ul>
					<hr>
					<ul>
						<li><span data-i18n="profile.링크 QR 코드"> My QR Code:</span></li>
						<!--<li><input type="button" value="Share" data-i18n="[value]profile.공유"></li>-->
					</ul>

					<div class="google-auth-top-qr" id="qrcode"></div>
				</div>
				
				<div class="gnb_dim"></div>

		</section>

		<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_setting.png' alt='아이콘'>  <span data-i18n='title.개인정보와 보안설정'>Purchase Packs</span>");
			$('#wrapper').css("background","#fff");
		});
		</script>

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>