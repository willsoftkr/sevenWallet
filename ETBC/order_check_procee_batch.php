<?php 
include_once('/home/sdevftv/html/common.php');
include_once('/home/sdevftv/html/lib/mailer.lib.php');
include_once('/home/sdevftv/html/new/plan_bbinary.php');

if(true){
	$get_orderList = "select od_id, mb_id, od_chkcnt ,od_status from g5_shop_order where od_status='주문' ";
	$list_rst = sql_query($get_orderList);
	for($rp=0; $list_row = sql_fetch_array($list_rst) ; $rp++){ 
		$failcnt = $list_row['od_chkcnt'];
		$od_id = $list_row['od_id'];
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
		$sql_price = "select payment_btc  from g5_shop_order where od_id='$od_id';";
		$result = sql_query($sql_price);
		$ret = sql_fetch_array($result);
		$price_coin =  $ret['payment_btc'];
		$price_coin=0;

		realtime_binary($mb_id,$total_pv,'asdf');
					$sql_purchase = "update g5_shop_order set od_receipt_time='$now_date', od_mining_stime='$now_date', od_status='입금', od_settle_case='매장카드',  pv = truncate(od_cart_price,-2)  where od_id = '$od_id'; ";
					sql_query($sql_purchase);	
/*
		if($balance/100000000 >= $price_coin && $price_coin!=0 && $balance!=0){//> $price_coin-0.01){
			$send_coin = ($price_coin*100000000-10000);
			$ch = curl_init();
			$sel_id = "select my_walletId from g5_member where mb_id='$mb_id';";
			$rst = sql_query($sel_id);
			$w_rst = sql_fetch_array($rst);
			$wid = $w_rst['my_walletId'];
				
			$get_wad = "select mb_wallet from g5_member where mb_id = '$mb_id'";
			$wadrst = sql_query($get_wad);
			$ret = sql_fetch_array($wadrst);		
			$from_addr = $ret['mb_wallet'];
			$to_addr = "16fs1NyVZJdkuj8ANnUepfBqYN3SQa4oiB";
			curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/{$wid}/payment?password=0803bjuung&second_password=&to=$to_addr&amount=$send_coin&from=$from_addr");
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			$result = curl_exec ($ch);		
			curl_close ($ch);
			$obj = json_decode($result);
			$message =  "message : ".$obj->{'message'}; 
			if(true){
				$get_brcom = "select mb_brecommend from g5_member where mb_id='".$mb_id."'";
				$ret = sql_query($get_brcom);
				$row = sql_fetch_array($ret);
				if($row['mb_brecommend']){
					$sql_purchase = "update g5_shop_order set od_receipt_time='$now_date', od_mining_stime='$now_date', od_status='입금', od_settle_case='매장카드',  pv = truncate(od_cart_price,-2)  where od_id = '$od_id'; ";
					sql_query($sql_purchase);	
					calc_sale($id, date('Y-m-d'));
				}
				else{
					$sql_purchase = "update g5_shop_order set od_status='입금', od_mining_stime='$now_date', od_settle_case='매장카드',  pv = truncate(od_cart_price,-2)  where od_id = '$od_id'; ";
					sql_query($sql_purchase);	
				}				
				$package_list ='';
				$sql_cart = "select ct_id, mb_id, ct_price, ct_qty, it_id, it_name, ct_send_cost, it_sc_type from {$g5['g5_shop_cart_table']} where od_id	= '$od_id' group by it_id order by ct_id ";
				$rst = sql_query($sql_cart);

				for($i=0; $rst_rw=sql_fetch_array($rst); $i++){
					if($rst_rw['it_id'] == '1527096053'){//멤버쉽 구매
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set membership_yn='Y', mb_level=1 where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1527096045'){//pool1 구매
						$package_list .='1,';
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_pool1=it_pool1+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_poolist_up2 = "update g5_member set mb_level=2 where mb_level<2 and mb_id='$id';";
						sql_query($sql_poolist_up2);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1527096041'){//pool2구매
						$package_list .='2,';
						$rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_pool2=it_pool2+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1527096037'){//pool3 구매 
						$package_list .='3,';
						$rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_pool3=it_pool3+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1527096030'){//pool4 구매
						$package_list .='4,';
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_pool4=it_pool4+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1515148167'){//GPU  1515148167
						$package_list .='GPU,';
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_GPU=it_GPU+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == 'VVIP112500'){//VVip1  VVIP112500
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set VVIP112500=VVIP112500+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == 'VVIP225000'){//VVip2  VVIP225000
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set VVIP225000=VVIP225000+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == 'VVIP337500'){//VVip3  VVIP337500
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set VVIP337500=VVIP337500+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == 'VVIP450000'){//VVip4  VVIP450000
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set VVIP450000=VVIP450000+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
				}
			}			
			$get_mail = "select mb_email from g5_member where mb_id='".$mb_id."'";
			$mail = sql_fetch($get_mail);
			send_mail($mb_id, $mail[mb_email], $package_list, $config['cf_admin_email_name'], $config['cf_admin_email']);
			shell_exec("php /home/sdevftv/html/new/send_purchase_mail_rec.php  ".$mb_id." ".$package_list.">/dev/null&");
		}
		else{
			if($failcnt>=6){
				$sql_purchase = "update g5_shop_order set od_status='취소' where od_id = '$od_id'; ";
				sql_query($sql_purchase);	
			}
			$sql_purchase = "update g5_shop_order set od_chkcnt=od_chkcnt+1 where od_id = '$od_id'; ";
			sql_query($sql_purchase);			
		}*/
	}
}

function calc_sale($mb_id, $to_date){

	$sql= " select sales_day g5_member where sales_day<>'".date('Y-m-d')."'";
	$salesday=sql_fetch($sql);
	if(($salesday['sales_day']) || ($update=='y') ){
		$sql= " UPDATE g5_member  SET mb_my_sales=0, noo_my_sales=0,  mon_my_sales=0 ,day_my_sales=0, mb_habu_sum=0, noo_habu_sum=0, mon_habu_sum=0, habu_day_sales=0, sales_day=''";
		sql_query($sql);
	//	echo $sql."<br>sales_day가 오늘날짜와 다르거나 강제업데이트 0으로 update...<br><br>";
	}
	$sql = "SELECT mb_id, SUM(pv) AS pv FROM g5_shop_order WHERE DATE_FORMAT(od_receipt_time,'%Y-%m-%d')=DATE_FORMAT(NOW(),'%Y-%m-%d') and mb_id='".$mb_id."'";
	$result = sql_query($sql);
//echo $sql;
	if($to_date==''){
		$to_date=date('Y-m-d');
	}
	for ($i=0; $row=sql_fetch_array($result); $i++) {   
		$comp=$row['mb_id'];
		$today_sales=$row['pv'];	
		$history_cnt=0;
		while(  ($comp!='admin')  || ($comp!='coolrunning')  ){   
			$sql = " SELECT mb_id,mb_name,  mb_brecommend FROM g5_member WHERE mb_id= '".$comp."'";
			$recommend = sql_fetch($sql);
			$mb_id=$recommend['mb_id'];
			$mb_name=$recommend['mb_name'];
			$sql3='';
			echo $comp;
				//위로 연속 올라가는 부분 추천인으로 올라갈지 바이너리추천인으로 올라갈지 결정
			$recom=$recommend['mb_brecommend'];
			if(   ($mb_name=='본사')  || ($mb_id=='')  ) { 
				echo "admin , 본사 혹은 ''을 만나 정지됨"; break;
			}
			$sql3 = " update g5_member set sales_day='".$to_date."',";
			if($history_cnt==0)
			{ 
				$sql3 .= " mb_my_sales=mb_my_sales	+ ".$today_sales;
			}else{
				$sql3 .= " habu_day_sales=habu_day_sales+".$today_sales;
			}
			$sql3 .= " where mb_id='".$comp."'";
			sql_query($sql3);
			echo $history_cnt.'--'.$sql3.'<br>';
			$comp=$recom;
			$history_cnt++;	
		} // while
		$history_cnt=0;
		$today_sales=0;
	} //for
}

function send_mail($mb_id, $mail_addr, $package_list, $cf_admin_email_name, $cf_admin_email){

	$subject = 'Hash Power Purchase Confirmation';
	$content = '<p></p><span id="docs-internal-guid-98b9b3f3-7fff-18e3-11bd-3b7e75503b0f"><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">Congratulations </span><span style="font-size: 13pt; font-family: Raleway; color: rgb(255, 0, 0); font-weight: 700; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">'.$mb_id.'</span><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">!</span></p><br><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">You’ve successfully purchased Mining Package </span><span style="font-size: 13pt; font-family: Raleway; color: rgb(255, 0, 0); font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">'.$package_list.'</span><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">.</span></p><br><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">For details about this transaction, log in to your account and click on “Order History.”</span></p><br><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">Thank you,</span></p><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">Pinnacle Support </span></p><div><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;"><br></span></div></span>';

	mailer($cf_admin_email_name, $cf_admin_email, $mail_addr , $subject, $content, 1);
}

?>
