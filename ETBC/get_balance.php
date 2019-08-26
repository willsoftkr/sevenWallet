<?

include_once('/home/sdevftv/html/common.php');

$getord = "SELECT ord.od_id, m.mb_id, m.mb_wallet, m.my_walletId, m.it_pool1, m.it_pool2, m.it_pool3, m.it_pool4, m.it_gpu
FROM g5_shop_order AS ord, g5_member m
WHERE 1 
AND ord.od_status
IN (
 '입금', '강제입금'
)
AND ord.mb_id = m.mb_id
AND substring( od_receipt_time, 1, 10 ) > '2018-06-27' 
limit 1901,200";

$ret = sql_query($getord);
echo $getord;
for ( $i=0; $list = sql_fetch_array($ret); $i++){

		$wid = $list['my_walletId'];
		$ch = curl_init();
	
		curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/$wid/balance?password=0803bjuung");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		//접속할 URL 주소
		$result = curl_exec ($ch);
		curl_close ($ch);
		$obj = json_decode($result);
		$balance =  $obj->{'balance'};
		
		echo $list[od_id].', member id : '.$list[mb_id].' 지갑 주소 : '.$list[mb_wallet].' 잔액 : '.$balance.' Pool1 보유 내역 : '.$list[it_pool1].' Pool2 보유 내역 : '.$list[it_pool2].' Pool3 보유 내역  '.$list[it_pool3].' Pool4 보유 내역 '.$list[it_pool4].' GPU 보유 내역 '.$list[it_gpu].'<br>';
		//if($i==100)break;
}

?>