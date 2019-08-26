<?php
include_once('./_common.php');
include_once('../lib/otphp/lib/otphp.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$dataArr = $_POST['send_array'];
$wallet_addr =  $_POST['wallet_addr'];
$wallet_id =  $_POST['wallet_id'];
$wallet_pw =  $_POST['wallet_pw'];
$admin_content = "";
if($wallet_addr=='' || $wallet_id=='' || $wallet_pw==''){
	echo "Not enough wallet information to send eos";
	die;
}
for($i=0; $i<count($dataArr); $i++){
	$get_addr = "select * from pinna_eos_trans where uid='".$dataArr[$i]."'";
	$rst = sql_fetch($get_addr);
	if(check_eos_addr($rst[addr])){
		$to_addr = $rst['addr'];
		$from_addr = $wallet_addr; //관리자 지갑 주소
		$send_coin = $rst[amt]; //출금 금액
		$wid = $wallet_id; //관리자 지갑 아이디
		$password = $wallet_pw; //관리자 지갑 비밀번호
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/{$wid}/payment?password=$password&second_password=&to=$to_addr&amount=$send_coin&from=$from_addr");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec ($ch);		
		curl_close ($ch);
		$obj = json_decode($result);
		$message =  $obj->{'message'}; 
		echo 'message = '.$message;
		if($message!='' && $message!=null){
			send_mail($rst['mb_id'], $config['cf_admin_email_name'], $config['cf_admin_email'],  $rst[amt], 'eos');
			update_status($dataArr[$i],$rst['mb_id']);
			$admin_conten .=  $rst['addr']."으로 송금 완료"."\n";

		}
		else{
			$admin_conten .=  $rst['addr']."으로 송금 실패"."\n";
		}

	}
	else {
		$admin_conten .=  $rst['addr']."이 주소는 유효하지 않는 주소"."\n";
	}
	
	
}
//send_admin_email($admin_conten, $config['cf_admin_email_name'], $config['cf_admin_email']);//전송 완료시에 메일 발송 넣기 
//echo $admin_conten;
function check_eos_addr($addr){
	$url = 'https://www.blockchain.com/ko/eos/address/'.$addr; 
	$ch = curl_init($url);  //curl 초기화
	curl_setopt($ch, CURLOPT_HEADER, true);    // header 값 출력 옵션
	curl_setopt($ch, CURLOPT_NOBODY, true);    // we don＇t need body (body는 필요 없음)
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // 파일 스트림의 형태로 얻어진 정보를 반환한다
	curl_setopt($ch, CURLOPT_TIMEOUT,10); //10초 안에 결과를 얻어 오면 실행 아니면 실패
	$output = curl_exec($ch); //실행
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //header code getting (200/404/500 etc)
	curl_close($ch); //핸들 close
	if($httpcode==200) // 판단 – code가 200이면 성공 500이면 실패.
	{
		return true;
	}
	else {
		return false;
	}
}
function get_email($mb_id){
	$sql = "select mb_email from g5_member where mb_id ='".$mb_id."'";
	$rst = sql_fetch($sql);
	return $rst[mb_email];
}
function update_status($uid, $mb_id){
	$sql = "update pinna_eos_trans set status = 'Y' where uid = ".$uid;
	//메일 발송 넣기
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
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">For details about this transaction, log in to Pinnacle Mining and click on "Crypto Wallets."</span></p><br>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr">';
	$content .= '<span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Sincerely, </span></p>';
	$content .= '<p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Pinnacle Support</span></p></b>
	<br class="Apple-interchange-newline">';

	mailer($cf_admin_email_name, 'noreply@pinnaclemining.net', $row['mb_email'] , $subject, $content, 1);

}
function send_admin_email( $admin_conten, $cf_admin_email_name, $cf_admin_email){
	$subject = "Pending Withdrawal Request";	
	$get_mem = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from g5_member where mb_id = 'Coolrunning' ";
	$row  = sql_fetch($get_mem);

	$content .=$admin_conten;

	mailer($cf_admin_email_name, 'noreply@pinnaclemining.net', $row[mb_email] , $subject, $content, 1);
}
?>
