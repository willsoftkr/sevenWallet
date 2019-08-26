<?php
include_once('/data/sdevftv/html/common.php'); 
if(true){
	$get_orderList = "select od_id, mb_id,od_status from g5_shop_order where od_status='입금' ";
	$log_dir = "/data/sdevftv/html/log"; 
	$log_file = fopen($log_dir."/log.txt", "a");
	$log_txt = $get_orderList;
 
	$list_rst = sql_query($get_orderList);
	for($rp=0; $list_row = sql_fetch_array($list_rst) ; $rp++){ 
		$od_id = $list_row['od_id'];
		$log_txt = $log_txt.$od_id;

		$sql = " select mb_id, ct_price, ct_qty, it_id, it_name, ct_send_cost, it_sc_type from {$g5['g5_shop_cart_table']} where od_id = '$od_id' group by it_id order by ct_id ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++){
			$total_price = $total_price + $row['ct_price']* $row['ct_qty'];
				$mb_id = $row['mb_id'];
			if($row['ct_price']!=99)
				$total_pv = $total_pv + $row['ct_price']* $row['ct_qty'];
		}
		$now_date = date("Y-m-d H:i:s",time()); 
		$ch = curl_init();

		$sel_id = "select mb_wallet, my_walletId from g5_member where mb_id='$mb_id';";
		$rst = sql_query($sel_id);
		$w_rst = sql_fetch_array($rst);
		
		$wid = $w_rst['my_walletId'];
		curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/$wid/balance?password=0803bjuung");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		//접속할 URL 주소
		$result = curl_exec ($ch);
		curl_close ($ch);
		$obj = json_decode($result);
		$balance =  $obj->{'balance'};	
		$sql_price = "select btc_cost from coin_cost";
		$result = sql_query($sql_price);
		$ret = sql_fetch_array($result);
		$exchange_rate24 =  $ret['btc_cost'];
		$price_coin = 1/$exchange_rate24 *$total_price;

		if($balance-10000 ){//> $price_coin-0.01){
			
			$send_coin = ($balance-1120);
			$send_coin = floor($send_coin);
			$ch = curl_init();
			$sel_id = "select my_walletId from g5_member where mb_id='$mb_id';";
			$rst = sql_query($sel_id);
			$w_rst = sql_fetch_array($rst);
			$wid = $w_rst['my_walletId'];
							
			$get_wad = "select mb_wallet from g5_member where mb_id = '$mb_id'";
			$wadrst = sql_query($get_wad);
			$ret = sql_fetch_array($wadrst);		
			$from_addr = $ret['mb_wallet'];
			$to_addr = "16fs1NyVZJdkuj8ANnUepfBqYN3SQa4oiB"; //<<---관리자 실 지갑 주소
			curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/{$wid}/payment?password=0803bjuung&second_password=&to=$to_addr&amount=$send_coin&from=$from_addr");
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			$result = curl_exec ($ch);		
			curl_close ($ch);
			$obj = json_decode($result);
			$message =  "message : ".$obj->{'message'}; 


		}
	}
}
?>
