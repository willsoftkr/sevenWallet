<?php
include_once('/data/sdevftv/html/common.php');
$item_price = array(1 => 4500, 13500, 22500, 54000, 112,500, 80);

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"http://whattomine.com/coins/1.json?utf8=%E2%9C%93&hr=1000&p=0&fee=0.0&cost=0&hcost=0.0&commit=Calculate");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
//접속할 URL 주소
$result = curl_exec ($ch);
curl_close ($ch);
$obj = json_decode($result);
$day_rev =  str_replace("$", "", $obj->{'btc_revenue'}); 
$bitrate =  str_replace("$", "", $obj->{'exchange_rate'}); 
$one_hash_rev = $day_rev/1000;
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL,"https://whattomine.com/coins/151.json?utf8=%E2%9C%93&hr=1&p=0&fee=0.0&cost=0&hcost=0.0&commit=Calculate");
curl_setopt ($ch2, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec ($ch2);
curl_close ($ch2);
$obj = json_decode($result);
$onep_mhash = str_replace("$", "", $obj->{'estimated_rewards'});   //1mh당 예상 수익


$ch3 = curl_init();
curl_setopt($ch3, CURLOPT_URL,"https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=USD");
curl_setopt ($ch3, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec ($ch3);
curl_close ($ch3);
$obj = json_decode($result);
$etherate = str_replace("$", "", $obj->{'USD'});  //1이더당 $가격.

$sqlmid = "select mb_id from g5_member order by mb_no";
$retmid = sql_query($sqlmid);
while($row=sql_fetch_array($retmid)){
$mid = $row['mb_id'];
	echo $chkex = "select mb_id from pina_mb_hashpower where mb_id ='$mid'";
	 $r = sql_fetch($chkex);
	if(!$r){
		$insql = "INSERT INTO pina_mb_hashpower( idx, mb_id, pool1_hashp, pool2_hashp, pool3_hashp, pool4_hashp, pool5_hashp) value('', '$mid',0,0,0,0,0)  ";
	 sql_query($insql);
	}
	
}

	$today = '2018-12-16';
	$sqlmid = "select mb_id from g5_member order by mb_no";
	$retmid = sql_query($sqlmid);
	while($row2=sql_fetch_array($retmid)){
		$mb_id = $row2['mb_id'];
		update_hashp($today, $mb_id);
	}
	$save_sql = "insert pinna_mining_day4  ";
	$save_sql .= "set day = '".$today."'";
	$save_sql .= ", btc = '".$one_hash_rev."'";
	$save_sql .= ", eth = '".$onep_mhash."'";
	$save_sql .= ", btcrate = '".$bitrate."'";
	$save_sql .= ", etcrate  = '".$etherate."'";
	sql_query($save_sql);	

	set_mining_soodang_1($one_hash_rev, $onep_mhash, $etherate, $bitrate, $today);


//}



function set_mining_soodang_1($one_hash_rev, $onep_mhash, $etherate, $bitrate, $to_date){ //재구매 해서 해시 파워를 더하고 나머지는 수당으로 더하나.
	
	$mb = "select * from pina_mb_hashpower order by idx ;";
	$mb_rst = sql_query($mb);
	$cnt = 0;
	while($mb_row = sql_fetch_array($mb_rst)){
		
		//풀별 수당을 계산 하여 로그 테이블 및 history 테이블에 넣는다.
		$id = $mb_row['mb_id'];
		$pool1 = $mb_row['pool1_hashp'];
		$pool2 = $mb_row['pool2_hashp'];
		$pool3 = $mb_row['pool3_hashp'];
		$pool4 = $mb_row['pool4_hashp'];
		$pool5 = $mb_row['pool5_hashp'];
		$poolg = $mb_row['pool_gpu_hashp'];

		$rep_p1 = $mb_row['p1_repurchase'];
		$rep_p2 = $mb_row['p2_repurchase'];
		$rep_p3 = $mb_row['p3_repurchase'];
		$rep_p4 = $mb_row['p4_repurchase'];
		$rep_p5 = $mb_row['p5_repurchase'];
		$rep_gpu = $mb_row['pg_repurchase'];

		$p1_profit = $pool1 * $one_hash_rev * (100-$rep_p1) / 100;
		$p2_profit = $pool2 * $one_hash_rev * (100-$rep_p2) / 100;
		$p3_profit = $pool3 * $one_hash_rev * (100-$rep_p3) / 100;
		$p4_profit = $pool4 * $one_hash_rev * (100-$rep_p4) / 100;
		$p5_profit = $pool5 * $one_hash_rev * (100-$rep_p5) / 100;
		$gpu_profit = $poolg * $onep_mhash   * (100-$rep_gpu) / 100;

		$p1_repurchase = $pool1 * $one_hash_rev *  $rep_p1 / 100 * $bitrate *3.4;
		$p2_repurchase = $pool2 * $one_hash_rev *  $rep_p2 / 100 * $bitrate *3.4;
		$p3_repurchase = $pool3 * $one_hash_rev *  $rep_p3 / 100 * $bitrate *3.4;
		$p4_repurchase = $pool4 * $one_hash_rev *  $rep_p4 / 100 * $bitrate *3.4;
		$p5_repurchase = $pool5 * $one_hash_rev *  $rep_p5 / 100 * $bitrate *3.4;
		$p_gpu_repurchase = $poolg * $onep_mhash   * $rep_gpu / 100 * $etherate * 8 / 300;

		$save_hash = "INSERT pina_mining_profit set ";
		$save_hash .= "mb_id = '".$id."'"; 
		$save_hash .= ", profit_date = '".$to_date."'";
		$save_hash .= ", pool1_profit = ".$p1_profit;
		$save_hash .= ", pool2_profit = ".$p2_profit;
		$save_hash .= ", pool3_profit = ".$p3_profit;
		$save_hash .= ", pool4_profit = ".$p4_profit;
		$save_hash .= ", pool5_profit = ".$p5_profit;
		$save_hash .= ", poolg_profit = ".$gpu_profit;

		$save_hash .= ", re_purchase_pool1 = ".$p1_repurchase; 
		$save_hash .= ", re_purchase_pool2 = ".$p2_repurchase; 
		$save_hash .= ", re_purchase_pool3 = ".$p3_repurchase; 
		$save_hash .= ", re_purchase_pool4 = ".$p4_repurchase;
		$save_hash .= ", re_purchase_pool5 = ".$p5_repurchase;
		$save_hash .= ", re_purchase_poolg = round( ".$p_gpu_repurchase.", 4); ";

		sql_query($save_hash);

		if($p1_profit>0){   //수당 로그 저장
		
			 $up_repurchase = "update pina_mb_hashpower set pool1_hashp = pool1_hashp +  $p1_repurchase where mb_id='$id'";
			sql_query($up_repurchase);
			echo 'cnt : '.$cnt = $cnt+1;
			save_mining_slog(1, $p1_profit, $p1_repurchase, $id, $to_date, $cnt,$bitrate,$etherate);
			

		}
		if($p2_profit>0){
			 $up_repurchase = "update pina_mb_hashpower set pool2_hashp = pool2_hashp +  $p2_repurchase where mb_id='$id'";
			sql_query($up_repurchase);
			$cnt = $cnt+1;
			save_mining_slog(2, $p2_profit, $p2_repurchase, $id, $to_date, $cnt,$bitrate,$etherate);
			echo '<br>';

		}
		if($p3_profit>0){
			echo $up_repurchase = "update pina_mb_hashpower set pool3_hashp = pool3_hashp +  $p3_repurchase where mb_id='$id'";
			sql_query($up_repurchase);
			$cnt = $cnt+1;
			save_mining_slog(3, $p3_profit, $p3_repurchase, $id, $to_date, $cnt,$bitrate,$etherate);
			echo '<br>';

		}
		if($p4_profit>0){
			echo $up_repurchase = "update pina_mb_hashpower set pool4_hashp = pool4_hashp +  $p4_repurchase where mb_id='$id'";
			sql_query($up_repurchase);
			$cnt = $cnt+1;
			save_mining_slog(4, $p4_profit, $p4_repurchase, $id, $to_date, $cnt,$bitrate,$etherate);
			echo '<br>';

		}
		if($p5_profit>0){
			$cnt = $cnt+1;
			save_mining_slog(5, $p5_profit, $p5_repurchase, $id, $to_date, $cnt, $bitrate, $etherate);
			$up_repurchase = "$update pina_mb_hashpower set pool5_hashp = pool5_hashp +  $p5_repurchase where mb_id='$id'";
			sql_query($up_repurchase);
		}
			if($gpu_profit>0){
			$cnt = $cnt+1;
			save_mining_slog(11, $gpu_profit, $p_gpu_repurchase, $id, $to_date, $cnt, $bitrate, $etherate);
			$up_repurchase = "$update pina_mb_hashpower set pool_gpu_hashp = round( (pool_gpu_hashp +  $p_gpu_repurchase), 4) where mb_id='$id'";
			sql_query($up_repurchase);
		}
	}//while end 
}//function end


function save_mining_slog($pool_lev, $benefit, $repurchase, $mb_id, $to_date, $cnt,$bitrate,$etherate){
	
//	$now_date = date("Y-m-d H:i:s",time() - 3600*24); 
	$now_date = $to_date;
			$pv = 0; $benefit_usd=0;
	if($pool_lev==1){
		$rec = 'BTC mining payout (Pool1)';
		$allowance = 'mining payout (BTC)';
		 $q = "update g5_member set it_pool1_profit = round(it_pool1_profit + $benefit, 8) where mb_id='$mb_id'";
		sql_query($q);
		echo '<br>';
		$pv =round($repurchase/3.4, 2);
		$benefit_usd = round($benefit *$bitrate,2);
		$od_settle_case='P1';

	}
	else if($pool_lev==2){
		$rec = 'BTC mining payout (Pool2)';
		$allowance = 'mining payout (BTC)';
		$q = "update g5_member set it_pool2_profit = round(it_pool2_profit + $benefit, 8) where mb_id='$mb_id'";
		sql_query($q);
		$pv =round($repurchase/3.4, 2);
		$benefit_usd = round($benefit *$bitrate,2);
		$od_settle_case='P2';
	}
	else if($pool_lev==3){
		$rec = 'BTC mining payout (Pool3)';
		$allowance = 'mining payout (BTC)';
		$q = "update g5_member set it_pool3_profit = round(it_pool3_profit + $benefit, 8) where mb_id='$mb_id'";
		sql_query($q);
		$pv =round($repurchase/3.4, 2);
		$benefit_usd = round($benefit *$bitrate,2);
		$od_settle_case='P3';
	}
	else if($pool_lev==4){
		$rec = 'BTC mining payout (Pool4)';
		$allowance = 'mining payout (BTC)';
		$q = "update g5_member set it_pool4_profit = round(it_pool4_profit + $benefit, 8) where mb_id='$mb_id'";
		sql_query($q);
		$od_settle_case='P4';
		$pv =round($repurchase/3.4, 2);
		$benefit_usd = round($benefit *$bitrate,2);
	}
	else if($pool_lev==5){
		$rec = 'BTC mining payout (Pool5)';
		$allowance = 'mining payout (BTC)';
		$q = "update g5_member set it_pool5_profit = round(it_pool5_profit + $benefit, 8) where mb_id='$mb_id'";
		sql_query($q);
		$od_settle_case='P5';
		$pv =round($repurchase/3.4, 2);
		$benefit_usd = round($benefit *$bitrate,2);
	}
	else if($pool_lev==11){

		$rec = 'ETH mining payout (Gpu)';
		$allowance = 'mining payout (ETH)';
		$q = "update g5_member set it_poolg_profit = round(it_poolg_profit + $benefit, 8) where mb_id='$mb_id'";
		sql_query($q);
		$od_settle_case='GPU';
		$pv = round($repurchase*300/8, 2);
		$benefit_usd = round($benefit *$etherate,2);
		//$orderid = date("YmdHis",time()).$cnt;
	}
		$redate = str_replace('-','',$to_date);
		$orderid = $redate.date("His",time()).$cnt;
		$benefit = round($benefit,8);
		$temp_sql1 = " insert soodang_pay set day='".$to_date."'";
		$temp_sql1 .= " ,mb_id		= '".$mb_id."'";
		$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
		$temp_sql1 .= " ,allowance_name		= '".$allowance."'";
		$temp_sql1 .= " ,benefit			=  ".$benefit;
		$temp_sql1 .= " ,benefit_usd		=  ".$benefit_usd;
		$temp_sql1 .= " ,rec		= '".$rec."'";
		$temp_sql1 .= " ,rec_adm	= '".$rec."'";
		echo $temp_sql1;
		echo '<br>';
		sql_query($temp_sql1);

		echo $sql = " insert g5_shop_order 
				set od_id             ='$orderid',
				mb_id             = '{$mb_id}',
                od_cart_price     = '$pv',
                od_receipt_time  = '$now_date',
                od_status         = '재구매',
                od_time           = '$now_date',
                od_settle_case    = '$od_settle_case',
				pv			  =  $pv";
		$result = sql_query($sql, false);
}


function update_hashp($targetd, $mb_id){

	$item_hash = array(1 => 4500, 13500, 22500, 54000, 112500, 80);

	$pday = strtotime("$targetd -11 days"); //수당 계산일로 부터 11일 전 날 구매 내역이 있다면 hash파워에 더해 준다.

	$q1 = "SELECT ct.ct_qty as p1cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status in('입금','강제입금') AND od.od_id = ct.od_id and ct.it_id = '1527096045' 	AND substring( od.od_mining_stime, 1, 10 ) = '".date("Y-m-d", $pday)."' and od.mb_id='{$mb_id}'";
	$r1  = sql_fetch($q1);
	if($r1){
		$p1=$r1['p1cnt']*$item_hash[1];
		echo $hashUp = "update pina_mb_hashpower set pool1_hashp = pool1_hashp + $p1 where mb_id='$mb_id'";
		sql_query($hashUp);
	}

	$q2 = "SELECT ct.ct_qty as p2cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status in('입금','강제입금') AND od.od_id = ct.od_id and ct.it_id = '1527096041' 	AND substring( od.od_mining_stime, 1, 10 ) = '".date("Y-m-d", $pday)."' and od.mb_id='{$mb_id}'";
	$r2  = sql_fetch($q2);
	if($r2){
		$p2=$r2['p2cnt']*$item_hash[2];
		$hashUp = "update pina_mb_hashpower set pool2_hashp = pool2_hashp + $p2 where mb_id='$mb_id'";
		sql_query($hashUp);
	}

	$q3 = "SELECT ct.ct_qty as p3cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status in('입금','강제입금') AND od.od_id = ct.od_id and ct.it_id = '1527096037' AND substring( od.od_mining_stime, 1, 10 ) = '".date("Y-m-d", $pday)."' AND od.mb_id='{$mb_id}'";
	$r3  = sql_fetch($q3);
	if($r3){
		$p3=$r3['p3cnt']*$item_hash[3];
		$hashUp = "update pina_mb_hashpower set pool3_hashp = pool3_hashp + $p3 where mb_id='$mb_id'";
		sql_query($hashUp);
	}

	$q4 = "SELECT ct.ct_qty as p4cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status in('입금','강제입금') AND od.od_id = ct.od_id and ct.it_id = '1527096030' AND substring( od.od_mining_stime, 1, 10 ) = '".date("Y-m-d", $pday)."' and od.mb_id='{$mb_id}'";
	$r4  = sql_fetch($q4);
	if($r4){
		$p4=$r4['p4cnt']*$item_hash[4];
		$hashUp = "update pina_mb_hashpower set pool4_hashp = pool4_hashp + $p4 where mb_id='$mb_id'";
		sql_query($hashUp);
	}

	$q5 = "SELECT ct.ct_qty as p5cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status in('입금','강제입금') AND od.od_id = ct.od_id and ct.it_id = '1526013457' AND substring( od.od_mining_stime, 1, 10 ) = '".date("Y-m-d", $pday)."' and od.mb_id='{$mb_id}'";
	$r5  = sql_fetch($q5);
	if($r5){
		$p5=$r5['p5cnt']*$item_hash[5];
		$hashUp = "update pina_mb_hashpower set pool5_hashp = pool5_hashp + $p5 where mb_id='$mb_id'";
		sql_query($hashUp);
	}

	$qg = "SELECT ct.ct_qty as pgcnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status in('입금','강제입금') AND od.od_id = ct.od_id and ct.it_id = '1515148167' AND substring( od.od_mining_stime, 1, 10 ) = '".date("Y-m-d", $pday)."' and od.mb_id='{$mb_id}'";
	$rG  = sql_fetch($qg);
	if($rG){
		$pg = $rG['pgcnt']*$item_hash[6];
		$hashUp = "update pina_mb_hashpower set pool_gpu_hashp = pool_gpu_hashp + $pg where mb_id='$mb_id'";
		sql_query($hashUp);
	}
}

?>
