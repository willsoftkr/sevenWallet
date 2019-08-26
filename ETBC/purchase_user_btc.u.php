<?php
include_once('./_common.php');


$mb_id = $_POST['mb_id'];
$od_id = $_POST['od_id'];



	$sql = "select payment_btc from g5_shop_order where od_id ='".$od_id."'";
	$rst = sql_fetch($sql);
	$pay_btc = $rst[payment_btc];

	;

	if(  minusWallet($mb_id,$pay_btc) ) {

			$get_brcom = "select mb_brecommend from g5_member where mb_id='".$mb_id."'";
			$ret = sql_query($get_brcom);
			$row = sql_fetch_array($ret);
			if($row['mb_brecommend']){
				$sql_purchase = "update g5_shop_order set od_receipt_time='$now_date', od_mining_stime='$now_date', od_status='입금', od_settle_case='매장카드',  pv = truncate(od_cart_price,-2)  where od_id = '$od_id'; ";
				sql_query($sql_purchase);	
				calc_sale($mb_id, date('Y-m-d'));
			}
			else{
				$sql_purchase = "update g5_shop_order set od_status='입금', od_mining_stime='$now_date', od_settle_case='매장카드',  pv = truncate(od_cart_price,-2)  where od_id = '$od_id'; ";
				sql_query($sql_purchase);	
			}				
			
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
						$ct_id =  $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_pool2=it_pool2+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1527096037'){//pool3 구매 
						$ct_id =  $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_pool3=it_pool3+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1527096030'){//pool4 구매
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_pool4=it_pool4+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
					else if($rst_rw['it_id'] == '1515148167'){//GPU  1515148167
						$ct_id = $rst_rw['ct_id'];
						$id = $rst_rw['mb_id'];  
						$qty = $rst_rw['ct_qty'];
						$sql_poolist_up = "update g5_member set it_GPU=it_GPU+$qty where mb_id='$id';";
						sql_query($sql_poolist_up);
						$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
						sql_query($sql_chgst);
					}
				}

		echo (json_encode(array("result" => "purchase is completed",  "code" => "0000")));
	}else{
		echo(json_encode(array("result" => "Not enough your balance",  "code" => "0001")));
	}


function minusWallet($mb_id, $amt){
	if($amt > 0){
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
?>