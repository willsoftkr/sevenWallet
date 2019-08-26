<?
include_once('./_common.php');
$exe_type	= trim($_POST['kind']);   //코인 종류
$mb_id		= trim($_POST['mb_id']); //
$rec_wallet	= trim($_POST['wallet_addr']);
if($rec_wallet==''){
	echo  (json_encode(array("result" => "there is not wallet address",  "code" => "0000")));
}
else {

	If($exe_type=='btc'){	
		$balance=0;
		if(!check_btc_addr($rec_wallet)){
			echo  (json_encode(array("result" => "this is not validate",  "code" => "0000")));
		}
		$ch = curl_init();
		$sel_id = "select my_walletId, mb_wallet from g5_member where mb_id='$mb_id';";
		$rst = sql_query($sel_id);
		$w_rst = sql_fetch_array($rst);
		$wid = $w_rst['my_walletId'];
		curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/$wid/balance?password=0803bjuung"); //잔고 조회
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		//접속할 URL 주소
		$result = curl_exec ($ch);
		curl_close ($ch);
		$obj = json_decode($result);
		$balance =  $obj->{'balance'}; 	
		if($balance >4000){
			$send_coin = ($balance-4000);
			$ch = curl_init();
			$from_addr = $w_rst['mb_wallet'];
			curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/{$wid}/payment?password=0803bjuung&second_password=&to=$rec_wallet&amount=$send_coin&from=$from_addr"); // 출금
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			$result = curl_exec ($ch);		
			curl_close ($ch);
			$obj = json_decode($result);
			$message =  "message : ".$obj->{'message'}; 
			echo  (json_encode(array("result" => "complete withdrwal",  "code" => "0000")));
		}else{
			echo  (json_encode(array("result" => "No Blance",  "code" => "0000")));
		}
	}
	else if($exe_type=='eth'){
		$get_memberinfo = "select mb_brecommend, eth_addr, eth_key from g5_member where mb_id='".$mb_id."'";
		$member_info = sql_fetch($get_memberinfo);
		$from_addr = $member_info['eth_addr'];
		$to_addr = $rec_wallet; //피나클 이더 지갑 주소
		$private_key = substr($member_info['eth_key'],2,64);

		$ch_balance = curl_init(); //잔액 조회
		curl_setopt($ch_balance,CURLOPT_URL, "http://202.239.44.110:8888/?from=$from_addr");
		curl_setopt ($ch_balance, CURLOPT_RETURNTRANSFER, 1); 
		$balance = curl_exec ($ch_balance);
		curl_close ($ch_balance);

		$now_balance = $balance/1000000000000000000; //WEI에서 이더로 변환
		if($now_balance >=0.0005){
			$transfer_fee = 0.000441;
			$transfer_eth = $now_balance -  $transfer_fee;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL, "http://202.239.44.110:7777/?private_key=$private_key-$to_addr-$transfer_eth-$from_addr");
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			//접속할 URL 주소
			$result = curl_exec ($ch);
			curl_close ($ch);		
			echo  (json_encode(array("result" => "complete withdrwal",  "code" => "0000")));
		}
		else{
			echo  (json_encode(array("result" => "No Blance",  "code" => "0000")));
		}
	}
}
function check_btc_addr($addr){
	$url = 'https://www.blockchain.com/ko/btc/address/'.$addr; 
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
?>

