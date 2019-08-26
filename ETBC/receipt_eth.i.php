<?php
include_once('./_common.php');
include_once('../lib/otphp/lib/otphp.php');

// OTP 인증 시작
$Base32 = new Base32();
$encoded = $Base32->encode(str_pad($member['mb_id'], 20 , "!&%"));

/*$totp = new \OTPHP\TOTP($encoded);

if($member['otp_flag'] != 'Y'){
    goto_url('./receipt.php?msg=회원 정보 수정 페이지에서 OTP를 등록해주시기 바랍니다.');
    exit;
}
if($totp->now() != $_POST[auth_code]){
    goto_url('./receipt.php?msg=OTP 인증번호가 다릅니다.');
    exit;
}*/
//OTP 인증 끝

function minusWallet($mb_id, $amt){
    if($amt >= 0.11){
		$amt = $amt;
        $fields = 'it_pool5_profit';
        $sql = " select $fields from g5_member where mb_id = TRIM('$mb_id') ";
        $member = sql_fetch($sql);

        $mining5 = $member['it_pool5_profit'];

        $deductionAmt = $amt;


        if($mining5 - $amt >= 0){
            $temp = $mining5 - $amt;
            $mining5 = round($temp,8);

            $sql = " update g5_member set ";
            $sql .= " it_pool5_profit = '".$mining5."' ";
            $sql .= " where mb_id = TRIM('$mb_id') ";
			sql_query($sql);

			return true;
        }
		else
			return false;
    }else{
        return false;
    }
}

// 수수료 + 금액 
// 차감 함수가 0이 아닌 값을 반환 하였을때 아래 로직 실행
$total_ebonus = "select sum(benefit) as ehap from soodang_pay where 1=1 and allowance_name = 'mining payout (ETH)' and mb_id ='".$member['mb_id']."'";
$rst = sql_fetch($total_ebonus);

$total_wid = "select round(sum(amt),8) as whap from withdrawal_request_eth where mb_id ='".$member['mb_id']."'";
$rst2 = sql_fetch($total_wid);

if(minusWallet($member['mb_id'], $_POST[amt])){
    $sql = " INSERT INTO withdrawal_request_eth (mb_id, addr, amt, create_dt, status, total_mining, total_wid) values (";
    $sql .= "'".$member['mb_id']."', ";
    $sql .= "'".$_POST[addr]."', ";
    $sql .= "'".($_POST[amt]-0.01)."', ";
    $sql .= "'".date("Y-m-d H:i:s",time())."', ";
    $sql .= "'R', ";
    $sql .= $rst[ehap].", ";
	$sql .= $rst2[whap].")";
    sql_query($sql);

    goto_url('./receipt_eth.php?msg=출금요청완료.');
}else{
    goto_url('./receipt_eth.php?msg=지갑에 금액이 없거나, 최소 금액 미만 입니다.');
}

	goto_url('./receipt_eth.php');
?>
