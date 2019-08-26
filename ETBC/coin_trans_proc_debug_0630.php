<?php
include_once('./_common.php');
include_once('../lib/otphp/lib/otphp.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

//debug 테스트 190621_soo

$debug = true;
$_POST['func'] =  'withdraw_eos';
$_POST['auth_code'] =  '476690';
$_POST['auth_mail_code'] ='ok';
$_POST['mb_id'] =  'test5';
$_POST['wallet_addr'] = 'address';
$_POST['wallet_addr'] = 'address';
$_POST['amt'] = '0.1';


//$rev_id			= trim($_POST['receiver']);



	//$rec			= get_member($rev_id);
	/*
	$mb_total_btc = $mb['mb_balance']+$mb['it_pool1_profit']+$mb['it_pool2_profit']+$mb['it_pool3_profit']+$mb['it_pool4_profit']+$mb['it_pool5_profit'];
	$rec_total_btc = $rec['mb_balance']+$rec['it_pool1_profit']+$rec['it_pool2_profit']+$rec['it_pool3_profit']+$rec['it_pool4_profit']+$rec['it_pool5_profit'];

	$mb_total_eth = $mb['it_poolg_profit'];
	$rec_total_eth = $rec['it_poolg_profit'];
	*/

	/*
		$mb_total_rwc = $mb['rwc_coin_num'];
		$rec_total_rwc = $rec['rwc_coin_num'];

		$mb_total_lkc = $mb['lkc_coin_num'];
		$rec_total_lkc = $rec['lkc_coin_num'];
	*/



	// 190621_soo
	// 타임스탬프 재설정 done.
	// 서버 시간 동기화 done.
	// 복호화 authy 코드변경 
	
	
	
	/*
	$sql = "select mb_id from g5_member where mb_id='".$rev_id."'";
	$result = sql_fetch($sql);
	*/

date_default_timezone_set('Asia/Singapore');
$f				= trim($_POST['func']);	
$mb_id			= trim($_POST['mb_id']);   
$wallet_addr	= trim($_POST['wallet_addr']);
$wallet_addr_memo	= trim($_POST['wallet_addr_memo']);
$token			= trim($_POST['auth_code']);
$amt			= trim($_POST['amt']);


$mb_total_eos = $member['mb_balance'] + $member['mb_save_point'] + $member['mb_shift_amt'];
$mb_after_eos = $mb_total_eos - $amt -0.01; //처리결과

echo date("Y-m-d H:i:s",time());

$Base32 = new Base32();
$encoded = $Base32->encode(str_pad($member['mb_id'], 20 , "!&%"));
$totp = new \OTPHP\TOTP($encoded);

if($debug){ echo "<br>now auth :".$totp->now()." totp :".$_POST['auth_code'];}


if($f=="withdraw_eos"){
	if( $amt < 0.1) {
		echo (json_encode(array("result" => "Input correct EOS value",  "code" => "0002")));
	}

	if($_POST['auth_mail_code'] != 'ok'){
		
		if(!$debug && $totp->now() != $_POST['auth_code']){
			echo (json_encode(array("result" => "OTP code does not match",  "code" => "0001")));
		}
	}
	
	else if(!minusWallet_eos($mb_id, $amt+0.01, $debug) ){
		echo (json_encode(array("result" => "Not enougth your balance",  "code" => "0003")));
	}
	else if(!$wallet_addr){
		echo (json_encode(array("result" => "Please Input Your EOS Wallet Address",  "code" => "0005")));
	}
	else {

		//eos 출금 처리
		//echo " <br> STEP 4 >>";

		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_receipt = "INSERT INTO  pinna_eos_trans set  ";
		$proc_receipt .=  "mb_id ='".$mb_id."'";
		$proc_receipt .=  ", addrmemo ='".$wallet_addr_memo."'";
		$proc_receipt .=  ", type = 2";
		$proc_receipt .=  ", mb_pbal = ".$mb_total_eos;
		$proc_receipt .=  ", mb_abal = ".$mb_after_eos;
		$proc_receipt .=  ", create_dt ='".$now_date."'";    //현재 
		$proc_receipt .=  ", amt = ".$amt; // 출금신청금액
		$proc_receipt .=  ", addr= '".$wallet_addr."'";
		$proc_receipt .=  ", status = 'R'";

		if($debug){
			print_R( "<br>proc_receipt Query ::  ".$proc_receipt."<br><br>"); 
		}else{
			$rst = sql_query($proc_receipt);
		}
		
		if($rst){
			//send_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'ETH');
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));
		}
	}
}


function minusWallet_eos($mb_id, $amt, $debug = false){

	if($amt > 0){
		//echo " <br> STEP 2 >>";
		$fields = 'mb_save_point, mb_balance';
		$sql = " select $fields from g5_member where mb_id = TRIM('$mb_id') ";
		$member = sql_fetch($sql);
		$mining_e = $member['mb_save_point'] + $member['mb_balance'];

		
		if($mining_e - $amt >= 0){
			//echo "<br> STEP 3 >>";
			$minus_result = $member['mb_save_point'] - $amt;



			$sql = " update g5_member set ";

			if($minus_result < 0){
				$minus_result2 =  $member['mb_balance'] + $minus_result; // 음수  - +
				$sql .= " mb_save_point = 0 ";
				$sql .= ", mb_balance = '".$minus_result2."' ";
			}else{
				$sql .= " mb_save_point = '".$minus_result."' ";
			}
			$sql .= " where mb_id = TRIM('$mb_id') ";
			
			if($debug){
				print_r( "<br><br> minusWallet_eos Query :: ".$sql."<br><br>"); 
			}else{
				sql_query($sql);
			}

			return true;
		}

		else{return false;}
	}else{return false;}
}

/*
if($f=="send_btc"){
	if($mb['mb_id']==$rec['mb_id'] ){
		echo (json_encode(array("result" => "Cannot transfer BTC to same username",  "code" => "0002")));
	}
	else if(!$result[mb_id]){
		echo (json_encode(array("result" => "Not exist username",  "code" => "0004")));
	}
	else if($totp->now() != $_POST['auth_code']){
		echo (json_encode(array("result" => "OTP code does not match",  "code" => "0001")));
	}
	else if(!minusWallet($mb['mb_id'], $amt)){
		echo (json_encode(array("result" => "Not nougth your balance",  "code" => "0003")));
	}

	else {
		//btc 송금 처리
		$mb_after_btc = $mb_total_btc - $amt;
		$rec_after_btc = $rec_total_btc + $amt;
		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_trans = "INSERT INTO  pinna_btc_trans set  ";
		$proc_trans .=  "mb_id ='".$mb_id."'";
		$proc_trans .=  ", recipient ='".$rev_id."'";
		$proc_trans .=  ", type = 1";
		$proc_trans .=  ", mb_pbal = ".$mb_total_btc;
		$proc_trans .=  ", rec_pbal =".$rec_total_btc;
		$proc_trans .=  ", mb_abal = ".$mb_after_btc;
		$proc_trans .=  ", rec_abal =".$rec_after_btc;
		$proc_trans .=  ", create_dt ='".$now_date."'";    //현재 시점
		$proc_trans .=  ", amt = ".$amt;
		$proc_trans .=  ", status = 'Y'";
		$rst = sql_query($proc_trans);
		if($rst){
			$update_recv = "update g5_member set mb_balance= round(mb_balance+".$amt.",8) where mb_id ='".$rev_id."'";
			$rec_rst = sql_query($update_recv);
			send_transfer_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'BTC', $rev_id);
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));
		}
	}

}
*/
/*
if($f=="withdraw_btc"){
	if( $amt < 0.02) {
		echo (json_encode(array("result" => "Input correct BTC value",  "code" => "0002")));
	}
	else if(!check_mail_token($mb_id, $token)){
		echo (json_encode(array("result" => "Email verification code does not match",  "code" => "0001")));
	}
	else if($totp->now() != $_POST['auth_code']){
		echo (json_encode(array("result" => "OTP code does not match",  "code" => "0001")));
	}
	else if(!minusWallet($mb['mb_id'], $amt+0.002) ){
		echo (json_encode(array("result" => "Not enougth your balance",  "code" => "0003")));
	}
	else if(!$wallet_addr){
		echo (json_encode(array("result" => "Please Input Your BTC Wallet Address",  "code" => "0005")));
	}
	else {
		//btc 출금 처리
		$mb_after_btc = $mb_total_btc - $amt-0.002;
		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_receipt = "INSERT INTO  pinna_btc_trans set  ";
		$proc_receipt .=  "mb_id ='".$mb_id."'";
		$proc_receipt .=  ", recipient ='".$mb_id."'";
		$proc_receipt .=  ", type = 2";
		$proc_receipt .=  ", mb_pbal = ".$mb_total_btc;
		$proc_receipt .=  ", mb_abal = ".$mb_after_btc;
		$proc_receipt .=  ", create_dt ='".$now_date."'";    //현재 시점
		$proc_receipt .=  ", amt = ".$amt;
		$proc_receipt .=  ", status = 'R'";
		$proc_receipt .=  ", addr= '".$wallet_addr."'";
		$rst = sql_query($proc_receipt);
		if($rst){
			send_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'BTC');
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));
		}
	}
}
*/
/*
else if($f=="send_eth"){

	if($totp->now() != $_POST['auth_code']){
		echo (json_encode(array("result" => "OTP code does not match",  "code" => "0001")));
	}
	else if($mb['mb_id']==$rec['mb_id'] ){
		echo (json_encode(array("result" => "Cannot transfer ETH to same username",  "code" => "0002")));
	}
	else if(!minusWallet_e($mb['mb_id'], $amt)){
		echo (json_encode(array("result" => "Not nougth your balance",  "code" => "0003")));
	}
	else if(!$result[mb_id]){
		echo (json_encode(array("result" => "Not exist username",  "code" => "0004")));
	}
	else {
		//eth 송금 처리
		$mb_after_eth = $mb_total_eth - $amt;
		$rec_after_eth = $rec_total_eth + $amt;

		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_trans = "INSERT INTO  pinna_btc_trans set  ";
		$proc_trans .=  "mb_id ='".$mb_id."'";
		$proc_trans .=  ", recipient ='".$rev_id."'";
		$proc_trans .=  ", type = 3";
		$proc_trans .=  ", mb_pbal = ".$mb_total_eth;
		$proc_trans .=  ", rec_pbal =".$rec_total_eth;
		$proc_trans .=  ", mb_abal = ".$mb_after_eth;
		$proc_trans .=  ", rec_abal =".$rec_after_eth;
		$proc_trans .=  ", create_dt ='".$now_date."'";    //현재 시점
		$proc_trans .=  ", amt = ".$amt;
		$proc_trans .=  ", status = 'Y'";

		$rst = sql_query($proc_trans);

		if($rst){
			$update_recv = "update g5_member set it_poolg_profit = round(it_poolg_profit+".$amt.",8) where mb_id ='".$rev_id."'";
			$rec_rst = sql_query($update_recv);
			send_transfer_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'ETH', $rev_id);
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));
		}
	}

}
*/


/*
else if($f=="send_rwc"){

if($mb['mb_id']==$rec['mb_id'] ){
		echo (json_encode(array("result" => "Cannot transfer RWC to same username",  "code" => "0002")));
	}
	else if(!minusWallet_r($mb['mb_id'], $amt)){
		echo (json_encode(array("result" => "Not nougth your balance",  "code" => "0003")));
	}
	else if(!$result[mb_id]){
		echo (json_encode(array("result" => "Not exist username",  "code" => "0004")));
	}
	else {
		//rwc 송금 처리
		$mb_after_rwc = $mb_total_rwc - $amt;
		$rec_after_rwc = $rec_total_rwc + $amt;

		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_trans = "INSERT INTO  pinna_btc_trans set  ";
		$proc_trans .=  "mb_id ='".$mb_id."'";
		$proc_trans .=  ", recipient ='".$rev_id."'";
		$proc_trans .=  ", type = 5";
		$proc_trans .=  ", mb_pbal = ".$mb_total_rwc;
		$proc_trans .=  ", rec_pbal =".$rec_total_rwc;
		$proc_trans .=  ", mb_abal = ".$mb_after_rwc;
		$proc_trans .=  ", rec_abal =".$rec_after_rwc;
		$proc_trans .=  ", create_dt ='".$now_date."'";    //현재 시점
		$proc_trans .=  ", amt = ".$amt;
		$proc_trans .=  ", status = 'Y'";

		$rst = sql_query($proc_trans);

		if($rst){
			$update_recv = "update {$g5['member_table']} set rwc_coin_num = round(rwc_coin_num+".$amt.",8) where mb_id ='".$rev_id."'";
			$rec_rst = sql_query($update_recv);
			//send_transfer_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'RWC', $rev_id);
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));
		}
	}

}
else if($f=="withd_rwc"){
	if( $amt < 10) {
		echo (json_encode(array("result" => "Input correct RWC value",  "code" => "0002")));
	}

	else if(!minusWallet_r($mb['mb_id'], $amt + 1) ){
		echo (json_encode(array("result" => "Not enougth your balance",  "code" => "0003")));
	}
	else if(!$wallet_addr){
		echo (json_encode(array("result" => "Please Input Your RWC Wallet Address",  "code" => "0005")));
	}
	else {
		//rwc 출금 처리
		$mb_after_rwc = $mb_total_rwc - $amt;
		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_receipt = "INSERT INTO  pinna_btc_trans set  ";
		$proc_receipt .=  "mb_id ='".$mb_id."'";
		$proc_receipt .=  ", recipient ='".$mb_id."'";
		$proc_receipt .=  ", type = 6";
		$proc_receipt .=  ", mb_pbal = ".$mb_total_rwc;
		$proc_receipt .=  ", mb_abal = ".$mb_after_rwc;
		$proc_receipt .=  ", create_dt ='".$now_date."'";    //현재 시점
		$proc_receipt .=  ", amt = ".$amt;
		$proc_receipt .=  ", addr= '".$wallet_addr."'";
		$proc_receipt .=  ", status = 'R'";

		$rst = sql_query($proc_receipt);

		if($rst){
			send_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'ETH');
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));

		}
	}

}
else if($f=="send_lkc"){
	if($mb['mb_id']==$rec['mb_id'] ){
		echo (json_encode(array("result" => "Cannot transfer LKC to same username",  "code" => "0002")));
	}
	else if(!minusWallet_l($mb['mb_id'], $amt)){
		echo (json_encode(array("result" => "Not nougth your balance",  "code" => "0003")));
	}
	else if(!$result[mb_id]){
		echo (json_encode(array("result" => "Not exist username",  "code" => "0004")));
	}
	else {

		//rwc 송금 처리
		$mb_after_lkc = $mb_total_lkc - $amt;
		$rec_after_lkc = $rec_total_lkc + $amt;

		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_trans = "INSERT INTO  pinna_btc_trans set  ";
		$proc_trans .=  "mb_id ='".$mb_id."'";
		$proc_trans .=  ", recipient ='".$rev_id."'";
		$proc_trans .=  ", type = 7";
		$proc_trans .=  ", mb_pbal = ".$mb_total_lkc;
		$proc_trans .=  ", rec_pbal =".$rec_total_lkc;
		$proc_trans .=  ", mb_abal = ".$mb_after_lkc;
		$proc_trans .=  ", rec_abal =".$rec_after_lkc;
		$proc_trans .=  ", create_dt ='".$now_date."'";    //현재 시점
		$proc_trans .=  ", amt = ".$amt;
		$proc_trans .=  ", status = 'Y'";

		$rst = sql_query($proc_trans);

		if($rst){
			$update_recv = "update {$g5['member_table']} set lkc_coin_num = round(lkc_coin_num+".$amt.",8) where mb_id ='".$rev_id."'";
			$rec_rst = sql_query($update_recv);
			//send_transfer_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'RWC', $rev_id);
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));
		}
	}

}
else if($f=="withd_lkc"){
	if( $amt < 10) {
		echo (json_encode(array("result" => "Input correct LKC value",  "code" => "0002")));
	}

	else if(!minusWallet_l($mb['mb_id'], $amt + 1) ){
		echo (json_encode(array("result" => "Not enougth your balance",  "code" => "0003")));
	}
	else if(!$wallet_addr){
		echo (json_encode(array("result" => "Please Input Your LKC Wallet Address",  "code" => "0005")));
	}
	else {
		//rwc 출금 처리
		$mb_after_lkc = $mb_total_lkc - $amt;
		$now_date = date("Y-m-d H:i:s",time()); 
		$proc_receipt = "INSERT INTO  pinna_btc_trans set  ";
		$proc_receipt .=  "mb_id ='".$mb_id."'";
		$proc_receipt .=  ", recipient ='".$mb_id."'";
		$proc_receipt .=  ", type = 8";
		$proc_receipt .=  ", mb_pbal = ".$mb_total_lkc;
		$proc_receipt .=  ", mb_abal = ".$mb_after_lkc;
		$proc_receipt .=  ", create_dt ='".$now_date."'";    //현재 시점
		$proc_receipt .=  ", amt = ".$amt;
		$proc_receipt .=  ", addr= '".$wallet_addr."'";
		$proc_receipt .=  ", status = 'R'";

		$rst = sql_query($proc_receipt);

		if($rst){
			send_mail($mb_id, $config['cf_admin_email_name'], $config['cf_admin_email'], $amt, 'ETH');
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else
		{
			echo (json_encode(array("result" => "ERR!!",  "code" => "0001")));

		}
	}

}
*/
/*
function minusWallet_r($mb_id, $amt){
	if($amt > 0){
		$sql = " select rwc_coin_num from g5_member where mb_id = TRIM('$mb_id') ";
		$rst = sql_fetch($sql);
		$mining_r = $rst['rwc_coin_num'];
		
		if($mining_r - $amt >= 0){
			$minus_r = $mining_r - $amt;
			$sql = " update g5_member set ";
			$sql .= " rwc_coin_num = ".$minus_r;
			$sql .= " where mb_id = TRIM('$mb_id') ";
			sql_query($sql);
			return true;
		}
		else{
			return false;
		}
	}
}
function minusWallet_l($mb_id, $amt){
	if($amt > 0){
		$sql = " select lkc_coin_num from g5_member where mb_id = TRIM('$mb_id') ";
		$rst = sql_fetch($sql);
		$mining_r = $rst['lkc_coin_num'];
		
		if($mining_r - $amt >= 0){
			$minus_r = $mining_r - $amt;
			$sql = " update g5_member set ";
			$sql .= " lkc_coin_num = ".$minus_r;
			$sql .= " where mb_id = TRIM('$mb_id') ";
			sql_query($sql);
			return true;
		}
		else{
			return false;
		}
	}
}
function minusWallet_e($mb_id, $amt){
	if($amt > 0){
		$fields = 'it_poolg_profit';
		$sql = " select $fields from g5_member where mb_id = TRIM('$mb_id') ";
		$member = sql_fetch($sql);
		$mining_e = $member['it_poolg_profit'];
		
		if($mining_e - $amt >= 0){
			$minus_e = $mining_e - $amt;
			$sql = " update g5_member set ";
			$sql .= " it_poolg_profit = '".$minus_e."' ";
			$sql .= " where mb_id = TRIM('$mb_id') ";
			sql_query($sql);
			return true;
		}
		else{
			return false;
		}
	}
}
*/
/*
function minusWallet($mb_id, $amt){
	if($amt >= 0){
		$fields = 'mb_balance, it_pool1_profit, it_pool2_profit, it_pool3_profit, it_pool4_profit';
		$sql = " select $fields from g5_member where mb_id = TRIM('$mb_id') ";
		$member = sql_fetch($sql);

		$pinWallet = $member['mb_balance'];
		$mining1 = $member['it_pool1_profit'];
		$mining2 = $member['it_pool2_profit'];
		$mining3 = $member['it_pool3_profit'];
		$mining4 = $member['it_pool4_profit'];

		$deductionAmt = $amt;
		if($deductionAmt > 0 && $pinWallet > 0){
			$temp = $deductionAmt - $pinWallet;
			$pinWallet = max(0,round($pinWallet - $deductionAmt,8));
			$deductionAmt = $temp;
		}

		if($deductionAmt > 0 && $mining1 > 0){
			$temp = $deductionAmt - $mining1;
			$mining1 = max(round($mining1 - $deductionAmt,8),0);
			$deductionAmt = $temp;
		}

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
			return true;
		}
	}else{
		return false;
	}

}
function send_mail($mb_id, $cf_admin_email_name, $cf_admin_email, $amt, $type){
	$subject = "Pending Withdrawal Request";	
	$get_mem = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from g5_member where mb_id = '".$mb_id."' ";
	$row  = sql_fetch($get_mem);

	$content .= '<b id="docs-internal-guid-7b188e7e-7fff-bbe7-134c-31dbd33d666a" style="font-weight: normal;">';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Hi </span>';
	$content .= '<span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$mb_id.'</span>';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">,</span></p><br>';

	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">We received a request from your account to withdraw </span>';
	$content .= '<span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 700; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$amt.'</span>';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> '.$type.'</span></p><br>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';

	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Date of Request: </span>';
	$content .= '<span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 700; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.date('m.d.Y',time()).'</span></p>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Username:  </span>';
	$content .= '<span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 700; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$row['mb_name'].'</span></p><br>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Your payment will be sent to the  '.$type.' address on file within 48 hours.</span></p><br>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">For details about this transaction, log in to EOS TEAM BLOCK CHAIN and click on "Crypto Wallets."</span></p><br>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Sincerely, </span></p>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">ETBC Support</span></p></b>
	<br class="Apple-interchange-newline">';

	mailer($cf_admin_email_name, 'noreply@goldentreeglobal.io', $row['mb_email'] , $subject, $content, 1);

}


function send_transfer_mail($mb_id, $cf_admin_email_name, $cf_admin_email, $amt, $type, $recv){
	$subject = "Successful Transfer Request";	
	$get_mem = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from g5_member where mb_id = '".$mb_id."' ";
	$row  = sql_fetch($get_mem);

	$content .= '<p><span style="font-size: 11pt;"><b style="font-weight: normal;"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Hi </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Tony</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">,</span></b></span><p><b style="font-weight: normal;"><br>&nbsp;</b></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><b style="font-weight: normal;"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">You have successfully sent</span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> '.$amt.'</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> '.$type.' to the following ETBC member: </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 700; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$recv.' </span></b></p><p><b style="font-weight: normal;"><br>&nbsp;</b></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><b style="font-weight: normal;"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">If you did not make this transfer request, please contact us immediately at support@goldentreeglobal.io.</span></b></p><p><b style="font-weight: normal;"><br>&nbsp;</b></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><b style="font-weight: normal;"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">This transaction was sent on </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.date('m.d.Y',time()).'</span></b></p><p><b style="font-weight: normal;"><br>&nbsp;</b></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><b style="font-weight: normal;"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">For more details, log in to your account and click on “Crypto Wallets.”</span></b></p><p><b style="font-weight: normal;"><br>&nbsp;</b></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><b style="font-weight: normal;"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Sincerely,</span></b></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><b style="font-weight: normal;"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">ETBC Support</span></b></p><p><b style="font-weight: normal;"><br>&nbsp;</b></p><p>&nbsp;</p><p></p><p>&nbsp;</p>';

	mailer($cf_admin_email_name, 'noreply@goldentreeglobal.io', $row['mb_email'] , $subject, $content, 1);

}

function check_mail_token($mb_id, $token){
	$get_token_last = "select * from pinna_mail_tonken where mb_id='".$mb_id."' order by create_time desc limit 1" ;
	$rst_t = sql_fetch($get_token_last);
	$expired_time = $rst_t['invaildate_time'];
	$now_time =  date("Y-m-d H:i:s",time());
	$expired = strtotime($expired_time);
	$now = strtotime($now_time);
	if(($now - $expired)>0){
		return false;
	}
	if(hash("sha256", $token) == $rst_t['verify_code']){
		return true;
	}else{
		return false;
	}
}
?>
*/