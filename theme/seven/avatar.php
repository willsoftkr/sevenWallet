<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 

if($_GET['recom_referral']){
	$recom_sql = "select mb_id from g5_member where mb_no = '{$_GET['recom_referral']}'";
	$recom_result = sql_fetch($recom_sql);

	$mb_recommend = $recom_result['mb_id'];
}

function timeshift($time){
	return date("d/m/Y ",strtotime($time));
}

$mb_id = $member['mb_id'];
$avatar_no = '1';

$avatar_cnt_sql = "select count(*) as cnt from avatar_savings where mb_id = '{$mb_id}' order by create_date desc limit 0,1";
$avatar_cnt_result = sql_fetch($avatar_cnt_sql);
$av_cnt = $avatar_cnt_result['cnt'];

$av_latest_result = 

$avatar_sql = "select * from avatar_savings where mb_id = '{$mb_id}' order by create_date desc";
$avatar_info = sql_query($avatar_sql);
?>

			<input type="hidden" name="mode" id="mode" value="w">

			<section class="avatar_wrap">
				<div class="ava_1st clear_fix">
					<span data-i18n="avatar.적립 목표">Savings target :</span>
					<select name="saving_target" id="saving_target">
						<option value="3000">$3000</option>
						<option value="4000">$4000</option>
						<option value="5000">$5000</option>
						<option value="6000">$6000</option>
						<option value="7000">$7000</option>
						<option value="8000">$8000</option>
						<option value="9000">$9000</option>
						<option value="10000">$10000</option>
					</select>
					<p class="f_right"><span data-i18n="avatar.적립금">Total savings</span> : <strong>$2,000</strong></p>
				</div>

				<hr>

				<div class="ava_select_wrap v_center">
					<!-- <p class="ava_title"><img src="_images/set_icon.gif" alt="이미지"> 적립 설정</p> -->
					<div class="img_select">
						<img src="<?=G5_THEME_URL?>/_images/ava_big_icon.png" alt="이미지">
						<b data-i18n="avatar.아바타 적금">Avatar Savings</b>
						<select name="saving_rate" id="saving_rate">
							<option value="10">10%</option>
							<option value="20">20%</option>
							<option value="30">30%</option>
							<option value="40">40%</option>
							<option value="50">50%</option>
							<option value="60">60%</option>
							<option value="70">70%</option>
							<option value="80">80%</option>
							<option value="90">90%</option>
							<option value="100">100%</option>
						</select>
						<p class="font_white" data-i18n="avatar.적립비율">Savings Rate</p>
					</div>
					<!--<input type="button" value="Save change" class="btn_basic ava_pop_open pop_open" data-i18n="[value]avartar.설정 저장">-->
					<input type="button" value="Save change" id="save_change" class="btn_basic"  data-i18n="[value]avartar.설정 저장">
				</div>

				<hr>

				<div class="ava_history_wrap v_center">
					<p class="ava_title"><img src="<?=G5_THEME_URL?>/_images/ava_icon.gif" alt="이미지" data-i18n="avatar.아바타 생성 기록"> Avatar Creation list</p>
					<ul class="clear_fix">
						<?
							while( $row = sql_fetch_array($avatar_info)){
						?>
							<?if($row['status'] == '1'){?>
								<li>
									<p>Avatar <?=$row['avatar_no']?>: </p>
									<p><span data-i18n="avatar.유저네임">Username</span> : <?=$row['avatar_id']?></p>
									<p><span data-i18n="avatar.생성일">Created on</span> : <?= timeshift($row['create_date'])?></p>
								</li>
							<?}else{?>
								<li>
								<p>Avatar <?=$row['avatar_no']?>: </p>
								<p><span data-i18n="avatar.누적금액">Saving</span> : <?=$row['current_saving']?></p>
							</li>
							<?}?>
						<?}?>
						<!--

						
							<li>
								<p>Avatar 1: </p>
								<p><span data-i18n="avatar.누적금액">Saving</span> : <?=$row['current_saving']?></p>
							</li>
						
						<li>
							<p>Avatar 2: </p>
							<p>유저네임: mynameyehuQO_1</p>
							<p>생성일: 8/7/2019</p>
						</li>
						<li>
							<p>Avatar 3: </p>
							<p>유저네임: mynameyehuQO_1</p>
							<p>생성일: 8/7/2019</p>
						</li>
						<li>
							<p>Avatar 4: </p>
							<p>유저네임: mynameyehuQO_1</p>
							<p>생성일: 8/7/2019</p>
						</li>
						<li>
							<p>Avatar 5: </p>
							<p>유저네임: mynameyehuQO_1</p>
							<p>생성일: 8/7/2019</p>
						</li>
						<li>
							<p>Avatar 6: </p>
							<p>유저네임: mynameyehuQO_1</p>
							<p>생성일: 8/7/2019</p>
						</li>
						<li>
							<p>Avatar 7: </p>
							<p>유저네임: mynameyehuQO_1</p>
							<p>생성일: 8/7/2019</p>
						</li>
						<li>
							<p>Avatar 8: </p>
							<p>유저네임: mynameyehuQO_1</p>
							<p>생성일: 8/7/2019</p>
						</li>
						-->

					</ul>
				</div>
			</section>
	
	
	<div class="gnb_dim"></div>

	<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_avatar.png' alt='아이콘'> <span data-i18n='title.아바타 적금'>Avatar Savings Account</span>");
			$('#wrapper').css("background","#fff");
		});
		function avatar_submit(f)
		{
			/*
			if (!is_checked("chk[]")) {
				alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
				return false;
			}
			*/
			return true;
		}

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


		$('#save_change').on('click', function(e){
			/*
			if(!$('#reg_mb_hp').val()){
				commonModal('Mobile authentication','<p>Please enter your Mobile Number</p>',80);
				return;
			}
			*/

			console.log('saving_avatar');

			var mb_id = "<?=$mb_id?>";
			var avatar_no = "<?=$avatar_no?>";
			var avatar_target = $('#saving_target').val();
			var avatar_rate = $('#saving_rate').val();
			var mode = $('#mode').val();

			$.ajax({
				url: '/util/avatar_saving.php',
				type: 'post',
				async: false,
				data: {
					"mb_id": mb_id,
					"avatar_no": avatar_no,
					"avatar_target" : avatar_target,
					"avatar_rate" : avatar_rate,
					"mode" : mode
				},
				dataType: 'json',
				success: function(result) {
					console.log(result.result);
					if(result.result == 'success'){
						dimShow();
						purchaseModal('Avatar setting','<p>Avatar setting change complete</p>','success');
						
					}else{
						purchaseModal('Avatar setting','<p>Check and retry</p>','failed');
					}
				},
				error: function(e){
					console.log(e);
				}
			});
		});
	</script>


<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>

