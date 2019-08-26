<?php
include_once('./_common.php');
include_once('../lib/otphp/lib/otphp.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

//debug 테스트 190621_soo

/*$debug = true;*/
/*
$_POST['func'] =  'withdraw_eos';
$_POST['auth_code'] =  '476690';
$_POST['mb_id'] =  'copy5285m';
$_POST['wallet_addr'] = 'address';
$_POST['wallet_addr'] = 'address';
$_POST['auth_mail_code']: 'ok';
$_POST['amt'] = '0.1';
*/


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
$mail_token	= trim($_POST['auth_mail_code']);
$amt			= trim($_POST['amt']);
$amt_cal = $amt*0.95;

$math_sql = "select  sum(mb_save_point + mb_balance + mb_shift_amt + mb_deposit_calc) as total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);

$EOS_BENEFIT_TOTAL = number_format($member['mb_balance'],3); // 수당 

$EOS_TOTAL =  number_format($math_total['total'],3);  //합계잔고  //합계잔고
if($EOS_TOTAL < 0){
	$EOS_TOTAL = 0;
}

$mb_total_eos = $EOS_TOTAL;
$mb_after_eos = $EOS_TOTAL - $amt; //처리결과

$Base32 = new Base32();
$encoded = $Base32->encode(str_pad($member['mb_id'], 20 , "!&%"));
$totp = new \OTPHP\TOTP($encoded);

if($debug){ echo "<br>now auth :".$totp->now()." totp :".$_POST['auth_code'];}


if($f=="withdraw_eos"){
	if( $amt < 0.1) {
		echo (json_encode(array("result" => "Input correct EOS value",  "code" => "0002")));
	}

	else if($amt % 5 != 0){
		echo (json_encode(array("result" => "Please Check Upstair input It's only available in five units",  "code" => "0006")));
	}

	
	else if($mail_token != 'ok'){
		if(!$debug && $totp->now() != $_POST['auth_code']){
			echo (json_encode(array("result" => "OTP code does not match",  "code" => "0001")));
		}
	}
	
	else if(!minusWallet_eos($mb_id, $amt, $debug) ){
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
		$proc_receipt .=  ", mb_pbal = ".$EOS_TOTAL;
		$proc_receipt .=  ", mb_abal = ".$mb_after_eos;
		$proc_receipt .=  ", create_dt ='".$now_date."'";    //현재 
		$proc_receipt .=  ", amt = ".$amt_cal; // 출금신청금액
		$proc_receipt .=  ", addr= '".$wallet_addr."'";
		$proc_receipt .=  ", status = 'R'";

		
		$rst = sql_query($proc_receipt);

		/*전환금액 업데이트*/
		$amt_total = $member['mb_shift_amt'] + (-1*$amt);
		$amt_query = "UPDATE g5_member set mb_shift_amt = '$amt_total' where mb_id = TRIM('$mb_id') ";
		$amt_result = sql_query($amt_query);
	
			
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
		$mining_e = $EOS_TOTAL;
		
	
		if($mining_e - $amt >= 0){

			//echo "<br> STEP 3 >>";
			/* 직접계산*/
			/*
			$minus_result = $member['mb_save_point'] - $amt;
			$sql = " update g5_member set ";

			if($minus_result < 0){
				$minus_result2 =  $member['mb_balance'] + $minus_result; // 음수  - +
				$sql .= " mb_save_point = 0 ";
				$sql .= ", mb_balance = '".$minus_result2."' ";
				
			}else{
				$sql .= " mb_save_point = '".$minus_result."' ";
			}
			*/
			/*
			$sql = " update g5_member set ";
			$sql .= "mb_cal_point = '".$amt."' ";

			$sql .= " where mb_id = TRIM('$mb_id') ";
			

			if($debug){
				print_r( "<br><br> minusWallet_eos Query :: ".$sql."<br><br>"); 
			}else{
				sql_query($sql);
			}
			*/
			return true;
			
		}
		else{return false;}

	}else{return false;}
}
