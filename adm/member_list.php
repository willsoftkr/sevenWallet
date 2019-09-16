<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['member_table']} ";

$sql_search = " where (1) ";
if ($stx) {
	$sql_search .= " and ( ";
	switch ($sfl) {
		case 'mb_point' :
			$sql_search .= " ({$sfl} >= '{$stx}') ";
			break;
		case 'mb_level' :
			$sql_search .= " ({$sfl} = '{$stx}') ";
			break;
		case 'mb_tel' :
		case 'mb_hp' :
			$sql_search .= " ({$sfl} like '%{$stx}') ";
			break;
		default :
			$sql_search .= " ({$sfl} like '{$stx}%') ";
			break;
	}
	$sql_search .= " ) ";
}

if($_GET['block']){
	$sql_search .= " and mb_block = 1 ";
}

if ($is_admin != 'super')
	$sql_search .= " and mb_level <= '{$member['mb_level']}' ";

if (!$sst) {
	$sst = "mb_datetime";
	$sod = "desc";
}


$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];

$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 탈퇴회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_leave_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$leave_count = $row['cnt'];

// 차단회원수
$sql = " select count(*) as cnt {$sql_common} {$sql_search} and mb_intercept_date <> '' {$sql_order} ";
$row = sql_fetch($sql);
$intercept_count = $row['cnt'];

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '회원관리';
include_once('./admin.head.php');

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$colspan = 20;

/*회원등급*/
$grade = "SELECT grade, count( grade ) as cnt FROM g5_member GROUP BY grade order by grade";
$get_lc = sql_query($grade);

/*지급차단*/
$blockRec = sql_fetch("select count(mb_block) as cnt from g5_member where mb_block = 1");

function out_check($val){
	$EOS_OUT_CALC = $val;
	
	if($EOS_OUT_CALC > 100){
		$class = 'over';
	}else{
		$class = '';
	}
	return "<span class=".$class.">".number_format($EOS_OUT_CALC)." % </span>";
}

?>

<div class="local_ov01 local_ov">
	<?php echo $listall ?>
	총회원수 <?php echo number_format($total_count) ?>명 중,
	<a href="?sst=mb_intercept_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>">
	차단 <?php echo number_format($intercept_count) ?></a>명,
	<a href="?sst=mb_leave_date&amp;sod=desc&amp;sfl=<?php echo $sfl ?>&amp;stx=<?php echo $stx ?>">탈퇴 <?php echo number_format($leave_count) ?></a>명,
	<a href="?block=1">
		지급차단 <?php echo number_format($blockRec['cnt']) ?>명
	</a>
</div>

<form id="fsearch" name="fsearch" class="local_sch01 local_sch" method="get">

<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
	<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
	<option value="mb_nick"<?php echo get_selected($_GET['sfl'], "mb_nick"); ?>>닉네임</option>
	<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
	<option value="mb_level"<?php echo get_selected($_GET['sfl'], "mb_level"); ?>>권한</option>
	<option value="mb_email"<?php echo get_selected($_GET['sfl'], "mb_email"); ?>>E-MAIL</option>
	<option value="mb_tel"<?php echo get_selected($_GET['sfl'], "mb_tel"); ?>>전화번호</option>
	<option value="mb_hp"<?php echo get_selected($_GET['sfl'], "mb_hp"); ?>>휴대폰번호</option>
	<option value="mb_point"<?php echo get_selected($_GET['sfl'], "mb_point"); ?>>PV</option>
	<option value="mb_datetime"<?php echo get_selected($_GET['sfl'], "mb_datetime"); ?>>가입일시</option>
	<option value="mb_ip"<?php echo get_selected($_GET['sfl'], "mb_ip"); ?>>IP</option>
	<option value="mb_recommend"<?php echo get_selected($_GET['sfl'], "mb_recommend"); ?>>추천인</option>
	<option value="mb_wallet"<?php echo get_selected($_GET['sfl'], "mb_wallet"); ?>>지갑</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" required class="required frm_input">
<input type="submit" class="btn_submit" value="검색">

</form>

<!--
<div class="local_desc01 local_desc">
	<p>
		회원자료 삭제 시 다른 회원이 기존 회원아이디를 사용하지 못하도록 회원아이디, 이름, 닉네임은 삭제하지 않고 영구 보관합니다.
	</p>
</div>
-->
<style>
	#member_depth{background:lightskyblue}
	#member_depth:hover{background:black; color:white;}
</style>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add">
	<a href="./member_table_depth.php" id="member_depth">회원추천관계갱신</a>
	<a href="./member_form.php" id="member_add">회원직접추가</a>
</div>
<?php } ?>
<div style="padding: 20px;font-size:15px;">
<?while($l_row = sql_fetch_array($get_lc)){
	
	if($l_row['grade']==3){
	echo $start." Green : ".$l_row['cnt']."명 | ";
	}
	else if($l_row['grade']==2){
		echo $start." Yellow: ".$l_row['cnt']."명 | ";
	}
	else if($l_row['grade']==1){
		echo "Red : ".$l_row['cnt']."명 | ";
	}
	else if($l_row['grade']==0){
		echo "Black : ".$l_row['cnt']."명 | ";
	}
}?>
</div>


<style>
	.eos_total{width:5%;_background:turquoise !important}
	.eos_aa{width:7%;background:lavender !important}
	.eos_bb{width:7%;background:lavender !important}
	.eos_bb.eos_out{width:7%;background:deepskyblue !important}
	.eos_bb.eos_benefit{width:7%;background:gold !important}
	.td_name{width:8%}
	.td_mail{width:10%;}
	.td_mbgrade{text-align:center}
	.td_mbgrade select{min-width:60px;padding:3px 5px }
	
	.tbl_head02 tbody td{padding:5px;}
	.over{color:red;}

	.btc_color{background:#ff9b22 url(../../_images/bit_w.png) !important}
	.eth_color{background:#3edc89 url(../../_images/ether_w.png) !important}
	.rwd_color{background:#a172c8 url(../../_images/rock_w.png) !important}
	.v7_color{background:#07b5e5 url(../../_images/v7_w.png) !important}
	
	.td_mbstat{text-align:right;padding-right:10px !important;font-size:12px;}
	.td_acount{min-width:100px;text-align:right;padding-right:3px;}
</style>

<form name="fmemberlist" id="fmemberlist" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);" method="post">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">

<div class="tbl_head02 tbl_wrap">
	<table>
	<caption><?php echo $g5['title']; ?> 목록</caption>
	<colgroup>
		<col width="40"/><col/><col/><col width="50" /><col width="50" /><col width="50" /><col width="50" /><col width="50" /><col width="50" /><col width="120" /><col width="120" /><col width="160" /><col width="120" /><col width="100" />
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2" id="mb_list_chk">
			<label for="chkall" class="sound_only">회원 전체</label>
			<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
		</th>
		<th scope="col" rowspan="2" id="mb_list_id" class="td_name"><?php echo subject_sort_link('mb_id') ?>아이디</a></th>
		<!--<th scope="col" rowspan="2"  id="mb_list_cert"><?php echo subject_sort_link('mb_certify', '', 'desc') ?>메일인증확인</a></th>-->
		
		<!--<th scope="col" rowspan="2" id="mb_list_mobile" class="td_mail">메일주소</th>-->
		
		<th scope="col" id="mb_list_auth"  class="eos_total v7_color" rowspan="2">V7 </th>
		<th scope="col" id="mb_list_auth"  class="eos_total btc_color"  style="width:70px;" rowspan="2">BTC </th>
		<!--
		<th scope="col" id="mb_list_auth"  class="eos_total eth_color" rowspan="2">ETH </th>
		<th scope="col" id="mb_list_auth"  class="eos_total rwd_color" rowspan="2">RWD </th>
		-->
		<th scope="col" id="mb_list_auth"  class="eos_aa" rowspan="2">입금합계</th>
		<th scope="col" id="mb_list_auth3" class="eos_aa"  rowspan="2">출금합계</th>
		<th scope="col" id="mb_list_auth3" class="eos_aa"  rowspan="2">예치금전환</th>

		<th scope="col" id="mb_list_auth2" class="eos_bb eos_benefit"  rowspan="2"> BENEFIT<br>( 총 발생수당 )</th>
		<th scope="col" id="mb_list_auth2" class="eos_bb"  rowspan="2">현재 예치금</th>
		<!--<th scope="col" id="mb_list_auth2" class="eos_bb"  rowspan="2">UPConversion <br> ( 전환 업스테어 )</th>-->
		<!--
		<th scope="col" id="mb_list_auth2" class="eos_bb eos_out"  rowspan="2">수당/업스테어<br>(500%)</th>
		<th scope="col" id="mb_list_auth2" class="eos_bb"  rowspan="2" style="background:aliceblue !important">UPSTAIR ACC <br> ( 누적 업스테어 )</th>
		-->


		<th scope="col" id="mb_list_authcheck" rowspan="2"><?php echo subject_sort_link('grade', '', 'desc') ?>회원등급/직급</a></th>
		<th scope="col" id="mb_list_levelcheck" rowspan="2"><?php echo subject_sort_link('mb_level', '', 'desc') ?>회원등급/직급</a></th>
		
		<th scope="col" id="mb_list_member"><?php echo subject_sort_link('mb_today_login', '', 'desc') ?>최종접속</a></th>
		<th scope="col" rowspan="3" id="mb_list_mng">관리</th>
	</tr>

	<tr>
		<!--<th scope="col" id="mb_list_mailc"><?php echo subject_sort_link('mb_email_certify', '', 'desc') ?>메일<br>인증</a></th>-->
		
		<th scope="col" id="mb_list_join"><?php echo subject_sort_link('mb_datetime', '', 'desc') ?>가입일</a></th>
	</tr>

	</thead>
	<tbody>

	<!--EOS TOTAL = Deposit + Conversion(-)  + Benefit + UPConversion(-)<br><br>-->

	<?php
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		// 접근가능한 그룹수
		$sql2 = " select count(*) as cnt from {$g5['group_member_table']} where mb_id = '{$row['mb_id']}' ";
		$row2 = sql_fetch($sql2);
		$mining_btc_bal = $row['it_pool1_profit']+$row['it_pool2_profit']+$row['it_pool3_profit']+$row['it_pool4_profit'];
		$mining_eth_bal = $row['it_pool5_profit'];
		$pinnacle_bal = $row['mb_balance'];
		$group = '';
		if ($row2['cnt'])
			$group = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">'.$row2['cnt'].'</a>';

		if ($is_admin == 'group') {
			$s_mod = '';
		} else {
			$s_mod = '<a href="./member_form.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'">수정</a>';
			$s_mod_binary = '<a href="./modify_binary.php?'.$qstr.'&amp;w=u&amp;mb_id='.$row['mb_id'].'">바이너리 수정</a>';
			
		}
		$s_grp = '<a href="./boardgroupmember_form.php?mb_id='.$row['mb_id'].'">그룹</a>';

		$leave_date = $row['mb_leave_date'] ? $row['mb_leave_date'] : date('Ymd', G5_SERVER_TIME);
		$intercept_date = $row['mb_intercept_date'] ? $row['mb_intercept_date'] : date('Ymd', G5_SERVER_TIME);

		$mb_nick = get_sideview($row['mb_id'], get_text($row['mb_nick']), $row['mb_email'], $row['mb_homepage']);

		$mb_id = $row['mb_id'];
		$leave_msg = '';
		$intercept_msg = '';
		$intercept_title = '';
		if ($row['mb_leave_date']) {
			$mb_id = $mb_id;
			$leave_msg = '<span class="mb_leave_msg">탈퇴함</span>';
		}
		else if ($row['mb_intercept_date']) {
			$mb_id = $mb_id;
			$intercept_msg = '<span class="mb_intercept_msg">차단됨</span>';
			$intercept_title = '차단해제';
		}
		if ($intercept_title == '')
			$intercept_title = '차단하기';

		$address = $row['mb_zip1'] ? print_address($row['mb_addr1'], $row['mb_addr2'], $row['mb_addr3'], $row['mb_addr_jibeon']) : '';

		$bg = 'bg'.($i%2);

		switch($row['mb_certify']) {
			case 'hp':
				$mb_certify_case = '휴대폰';
				$mb_certify_val = 'hp';
				break;
			case 'ipin':
				$mb_certify_case = '아이핀';
				$mb_certify_val = '';
				break;
			case 'admin':
				$mb_certify_case = '관리자';
				$mb_certify_val = 'admin';
				break;
			default:
				$mb_certify_case = '&nbsp;';
				$mb_certify_val = 'admin';
				break;
		}

		
		/*확정추가 */
		$math_sql = "select  sum(mb_btc_account + mb_btc_calc + mb_btc_amt ) as btc_total, mb_v7_account from g5_member where mb_id = '".$row['mb_id']."'";
		$math_total = sql_fetch($math_sql);

		$BENEFIT_TOTAL = number_format($row['mb_balance'],2); // 수당 합계
		$BTC_TOTAL =  number_format($math_total['btc_total'],8);  //BTC 합계잔고
		$v7_TOTAL =  number_format($math_total['mb_v7_account'],2);  // V7 합계잔고

		$EOS_UPSTAIR = number_format($row['mb_deposit_point'],2); // 매출 합계
	?>

	<tr class="<?php echo $bg; ?>">
		<td headers="mb_list_chk" class="td_chk" rowspan="2">
			<input type="hidden" name="mb_id[<?php echo $i ?>]" value="<?php echo $row['mb_id'] ?>" id="mb_id_<?php echo $i ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($row['mb_name']); ?> <?php echo get_text($row['mb_nick']); ?>님</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
		</td>
		<td headers="mb_list_id" rowspan="2" class="td_name sv_use" style="text-align:center !imporatant">

		<a href="./member_form.php?<?=$qstr?>&amp;w=u&amp;mb_id=<?=$mb_id?>"><?php echo $mb_id ?></a></td>

		<!-- <td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['mb_name']); ?></td> -->
		<!--<td headers="mb_list_name" class="td_mbname"><?php echo get_text($row['first_name']); ?></td>-->
		<!--
		<td headers="mb_list_otp" class="td_chk">
			<label for="otp_flag<?php echo $i; ?>" class="sound_only">OTP인증</label>
			<input type="checkbox" name="otp_flag[<?php echo $i; ?>]" <?php echo $row['otp_flag'] == 'Y' ?'checked':''; ?> value="Y" id="otp_flag<?php echo $i; ?>">
		</td>
		<td headers="mb_list_mailc" class="td_chk"><?php echo preg_match('/[1-9]/', $row['mb_email_certify'])?'<span class="txt_true">Yes</span>':'<span class="txt_false">No</span>'; ?></td>
		<td headers="mb_list_open" class="td_chk">
			<label for="mb_open_<?php echo $i; ?>" class="sound_only">정보공개</label>
			<input type="checkbox" name="mb_open[<?php echo $i; ?>]" <?php echo $row['mb_open']?'checked':''; ?> value="1" id="mb_open_<?php echo $i; ?>">
		</td>
		-->
		<!--
		<td headers="mb_list_mailr" rowspan="2"class="td_chk">
			<label for="mb_mailling_<?php echo $i; ?>" class="sound_only">메일수신</label>
			<input type="checkbox"  name="mb_mailling[<?php echo $i; ?>]" <?php echo $row['mb_mailling']?'checked':''; ?> value="1" id="mb_mailling_<?php echo $i; ?>">
		</td>
		-->
		<!--
		<td headers="mb_list_sms" class="td_chk">
			<label for="mb_sms_<?php echo $i; ?>" class="sound_only">SMS수신</label>
			<input type="checkbox" name="mb_sms[<?php echo $i; ?>]" <?php echo $row['mb_sms']?'checked':''; ?> value="1" id="mb_sms_<?php echo $i; ?>">
		</td>
	   <td headers="mb_list_adultc" class="td_chk">
			<label for="mb_adult_<?php echo $i; ?>" class="sound_only">성인인증</label>
			<input type="checkbox" name="mb_adult[<?php echo $i; ?>]" <?php echo $row['mb_adult']?'checked':''; ?> value="1" id="mb_adult_<?php echo $i; ?>">
		</td> 
		<td headers="mb_list_deny" class="td_chk">
			<?php if(empty($row['mb_leave_date'])){ ?>
			<input type="checkbox" name="mb_intercept_date[<?php echo $i; ?>]" <?php echo $row['mb_intercept_date']?'checked':''; ?> value="<?php echo $intercept_date ?>" id="mb_intercept_date_<?php echo $i ?>" title="<?php echo $intercept_title ?>">
			<label for="mb_intercept_date_<?php echo $i; ?>" class="sound_only">접근차단</label>
			<?php } ?>
		</td>
		// -->
		
		<!--//메일주소-->
		<!--<td headers="mb_list_mobile" rowspan="2" class="td_tel"><?php echo get_text($row['mb_email']); ?></td>-->
	
		


		<!--<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><?= $EOS_TOTAL?></td>-->
		
		<td headers="mb_list_auth" class="td_acount" rowspan="2">
			<strong><?=$v7_TOTAL?></strong> <!--V7 잔고-->
		</td>

		<td headers="mb_list_auth" class="td_acount" rowspan="2">
			<strong><?=$BTC_TOTAL?></strong> <!--BTC 잔고-->
		</td>

		<!--
		<td headers="mb_list_auth" class="td_mbstat" rowspan="2">
			<strong><?= number_format($row['mb_eth_account'],3)?></strong> 
		</td>
		<td headers="mb_list_auth" class="td_mbstat" rowspan="2">
			<strong><?= number_format($row['mb_rwd_account'],3)?></strong> 
		</td>
		-->

		<td headers="mb_list_auth btc" class="td_mbstat" rowspan="2"><?= number_format($row['mb_btc_account'],8)?> <!--입금합계--></td>
		<td headers="mb_list_auth btc" class="td_mbstat" rowspan="2"><?= number_format($row['mb_btc_calc'],8) ?> <!--출금합계--></td>
		<td headers="mb_list_auth btc" class="td_mbstat" rowspan="2"><?= number_format($row['mb_btc_amt'],8) ?> <!--예치금전환--></td>


		<td headers="mb_list_auth" class="td_mbstat" rowspan="2">
			<strong><?= $BENEFIT_TOTAL?> </strong><!--수당잔고-->
		</td>

		<td headers="mb_list_auth" class="td_mbstat" rowspan="2"><strong><?= $EOS_UPSTAIR ?> </strong><!--예치금--><br></td>
		
		<!--
		<td headers="mb_list_auth" class="td_mbstat" rowspan="2">
		<?
		if($EOS_OUT > 400){
			echo "<span style=color:red;font-weight:600>".$EOS_OUT."</span>";
		}else{
			echo $EOS_OUT;
		}
		?> 
		</td>
		-->
		<!--<td headers="mb_list_auth" class="td_mbstat" rowspan="2" ><?= $EOS_UPSTAIR_ACC ?></td>-->


		<td headers="mb_list_member" class="td_mbgrade" rowspan="2">
		<!--
			<?php
			if ($leave_msg || $intercept_msg) echo $leave_msg.' '.$intercept_msg;
			else echo "정상 / ";
			?>
		-->
			
			<div><?php echo get_grade_select("grade[$i]", 0, $member['grade'], $row['grade']) ?></div>
			
		</td>
		<td headers="mb_list_member" class="td_mbgrade" rowspan="2">
			<div><?php echo get_level_select("mb_level[$i]", 0, $member['mb_level'], $row['mb_level']) ?></div>
		</td>

		<td headers="mb_list_lastcall" class="td_date"><?php echo substr($row['mb_today_login'],2,8); ?></td>
		<!--<td headers="mb_list_grp" rowspan="1" class="td_numsmall"><?php echo $group ?></td>-->
		<td headers="mb_list_mng" rowspan="2" class="td_mngsmall"><?php echo $s_mod ?> <?php echo $s_grp ?></br> <?php echo $s_mod_binary ?></td>
		
	</tr>
	<tr class="<?php echo $bg; ?>">
		<!-- <td headers="mb_list_nick" class="td_name sv_use"><div><?php echo $mb_nick ?></div></td> -->
		<!--<td headers="mb_list_nick" class="td_name sv_use"><div><?php echo get_text($row['last_name']); ?></div></td>-->
			<!--<td headers="mb_list_cert" colspan="6" class="td_mbcert">-->
			<!-- <input type="radio" name="mb_certify[<?php echo $i; ?>]" value="ipin" id="mb_certify_ipin_<?php echo $i; ?>" <?php echo $row['mb_certify']=='ipin'?'checked':''; ?>>
			<label for="mb_certify_ipin_<?php echo $i; ?>">아이핀</label>
			<input type="radio" name="mb_certify[<?php echo $i; ?>]" value="hp" id="mb_certify_hp_<?php echo $i; ?>" <?php echo $row['mb_certify']=='hp'?'checked':''; ?>>
			<label for="mb_certify_hp_<?php echo $i; ?>">휴대폰</label> 
			
			P1 <?=$row['it_pool1']?>개 / P2 - <?=$row['it_pool2']?>개 / P3 - <?=$row['it_pool3']?>개 / P4 - <?=$row['it_pool4']?>개 / G - <?=$row['it_GPU']?>개
			</td>-->
		

		<!--<td headers="mb_list_tel" class="td_tel"><?php echo get_text($row['mb_tel']); ?></td>
		<td></td>-->
		<!-- // <td headers="mb_list_point" class="td_num"><a href="point_list.php?sfl=mb_id&amp;stx=<?php echo $row['mb_id'] ?>"><?php echo number_format($row['mb_point']) ?></a></td> // -->
		<td headers="mb_list_join" class="td_date"><?php echo substr($row['mb_datetime'],2,8); ?></td>
		<!--
		<td>
			<a href="https://www.blockchain.com/ko/btc/address/<?php echo $row['mb_wallet'] ?>" target="_balnk">
				<?php echo $row['mb_wallet'] ?>
			</a>
		</td>
		-->
	</tr>

	<?php
	}
	if ($i == 0)
		echo "<tr><td colspan=\"".$colspan."\" class=\"empty_table\">자료가 없습니다.</td></tr>";
	?>
	</tbody>
	</table>
</div>

<div class="btn_list01 btn_list">
	<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
	<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page='); ?>

<script>
function fmemberlist_submit(f)
{
	if (!is_checked("chk[]")) {
		alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택삭제") {
		if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
			return false;
		}
	}
	return true;
}


// 엑셀 다운로드
$('#excel_btn').on("click", function () {
	
	var s_date = $('#s_date').val();
	var e_date = $('#e_date').val();
	//var idx_num = $('.select-btn').val();
	var idx_num = '';
	var ck_box = true;
	$('.ckbox').each(function(){
		if( $(this).prop('checked') ){
			if( ck_box == true ){
				ck_box = false;
				idx_num += $(this).val();
			}else{
				idx_num += '_'+$(this).val();
			}
		}
	})
	//console.log("/excel/metal.php?s_date="+s_date+"&e_date="+e_date+"&idx_num="+idx_num+"&idx=<?=$idx?>");

	window.open("/excel/metal.php?s_date="+s_date+"&e_date="+e_date+"&idx_num="+idx_num+"&idx=<?=$idx?>");
});
</script>

<?php
include_once ('./admin.tail.php');
?>
