<?php
include_once('./_common.php');
include_once('../lib/otphp/lib/otphp.php');

/* OTP 인증 시작
$Base32 = new Base32();
$encoded = $Base32->encode(str_pad($member['mb_id'], 20 , "!&%"));

$totp = new \OTPHP\TOTP($encoded);

if($member['otp_flag'] != 'Y'){
	goto_url('./receipt.php?msg=회원 정보 수정 페이지에서 OTP를 등록해주시기 바랍니다.');
	exit;
}
if($totp->now() != $_POST[auth_code]){
	goto_url('./receipt.php?msg=OTP 인증번호가 다릅니다.');
	exit;
}
*/
//OTP 인증 끝

function minusWallet($mb_id, $amt){
	if($amt > 0.022){
		$fields = 'mb_balance, it_pool1_profit, it_pool2_profit, it_pool3_profit, it_pool4_profit';
		$sql = " select $fields from g5_member where mb_id = TRIM('$mb_id') ";
		$member = sql_fetch($sql);

		$pinWallet = $member['mb_balance'];
		$mining1 = $member['it_pool1_profit'];
		$mining2 = $member['it_pool2_profit'];
		$mining3 = $member['it_pool3_profit'];
		$mining4 = $member['it_pool4_profit'];

		$deductionAmt = $amt;
//10 - 8
		if($deductionAmt > 0 && $pinWallet > 0){
			$temp = $deductionAmt - $pinWallet;
			$pinWallet = max(0,round($pinWallet - $deductionAmt,8));
			$deductionAmt = $temp;
		}
//2 - 1
		if($deductionAmt > 0 && $mining1 > 0){
			$temp = $deductionAmt - $mining1;
			$mining1 = max(round($mining1 - $deductionAmt,8),0);
			$deductionAmt = $temp;
		}
//1-1
		if($deductionAmt > 0 && $mining2 > 0){
			$temp = $deductionAmt - $mining2;
			$mining2 = max(round($mining2 - $deductionAmt,8),0);
			$deductionAmt = $temp;
		}
		if($deductionAmt > 0 && $mining3 > 0){
			$temp = $deductionAmt - $mining3;
			$mining3 = max(round($mining3 - $deductionAmt,8),0);
			$deductionAmt = $temp;
		}
		if($deductionAmt > 0 && $mining4 > 0){
			$temp = $deductionAmt - $mining4;
			$mining4 = max(round($mining4 - $deductionAmt,8),0);
			$deductionAmt = $temp;
		}
		
		//echo $deductionAmt; 
		if($deductionAmt > 0){ // 모든 지갑에서 차감을 했는데 minus 금액 남은 경우 실패 처리
			return false;
		}else{ // 차감 성공 - 지갑 데이터 업데이트 , history 쌓기. 
			// mb_balance, it_pool1_profit, it_pool2_profit, it_pool3_profit, it_pool4_profit
			$sql = " update g5_member set ";
			$sql .= " mb_balance = '".$pinWallet."', ";
			$sql .= " it_pool1_profit = '".$mining1."', ";
			$sql .= " it_pool2_profit = '".$mining2."', ";
			$sql .= " it_pool3_profit = '".$mining3."', ";
			$sql .= " it_pool4_profit = '".$mining4."' ";
			$sql .= " where mb_id = TRIM('$mb_id') ";
			sql_query($sql);
			//echo $sql;
			return true;
		}
	}else{
		return false;
	}
}

// 수수료 + 금액 
// 차감 함수가 0이 아닌 값을 반환 하였을때 아래 로직 실행
if(minusWallet($member['mb_id'], $_POST[amt])){
	$sql = " INSERT INTO withdrawal_request (id, addr, amt, create_dt, status) values (";
	$sql .= "'".$member['mb_id']."', ";
	$sql .= "'".$_POST[addr]."', ";
	$sql .= "'".($_POST[amt]-0.002)."', ";
	$sql .= "'".date("Y-m-d H:i:s",time())."', ";
	$sql .= "'R') ";
	sql_query($sql);

	goto_url('./crypto_wallet.php?msg=출금요청완료.');
}else{
	goto_url('./crypto_wallet.php?msg=지갑에 금액이 없거나, 최소 금액 미만 입니다.');
}

//goto_url('./receipt.php');
?>
