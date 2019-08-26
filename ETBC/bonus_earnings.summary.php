<?php
	include_once('./_common.php');

	$result = new stdClass();
	
	if($_GET['category'] == 'btc'){ // 비트코인

		$sql = "select 
			IFNULL(round(sum(it_pool1_profit + it_pool2_profit + it_pool3_profit + it_pool4_profit + it_pool5_profit + mb_balance),8),'0.00000000') as balance
		from g5_member where mb_id = '{$member[mb_id]}'";
		
		$result -> balance = sql_fetch($sql)['balance'];

		$sql = " select IFNULL(round(sum(benefit),8),'0.00000000') as sum from soodang_pay WHERE day > DATE_ADD(now(), INTERVAL -7 day) and mb_id = '".$member['mb_id']."' and allowance_name <> 'mining payout (ETH)' ";
		$result -> last7 = sql_fetch($sql)['sum'];

		$sql = " select IFNULL(round(sum(benefit),8),'0.00000000') as sum from soodang_pay WHERE day > DATE_ADD(now(), INTERVAL -1 month) and mb_id = '".$member['mb_id']."' and allowance_name  <> 'mining payout (ETH)' ";
		$result -> last30 = sql_fetch($sql)['sum'];
		
		$result -> miningBalance = '0.00000000'; 

		$sql = "select IFNULL(round(sum(pv),2),'0.00') as rehap from g5_shop_order where od_status ='재구매' and mb_id ='{$member[mb_id]}' ";
		$result -> totalRepurchasedAmount = sql_fetch($sql)['rehap'];

		$sql = "select IFNULL(round(sum(benefit),8),'0.00000000') as sum 
		from soodang_pay where mb_id = 'rose' and allowance_name <> 'mining payout (ETH)'";

		$result -> totalBonusPaid = sql_fetch($sql)['sum']; 
	}else if($_GET['category'] == 'eth'){ // 이더리움 (현재 사용 안함 - 20190107)
		$result -> balance = '0.00000000';

		$sql = " select IFNULL(round(sum(benefit),8),'0.00000000') as sum from soodang_pay WHERE day > DATE_ADD(now(), INTERVAL -7 day) and mb_id = '".$member['mb_id']."' and allowance_name = 'mining payout (ETH)' ";
		$result -> last7 = sql_fetch($sql)['sum'];

		$sql = " select IFNULL(round(sum(benefit),8),'0.00000000') as sum from soodang_pay WHERE day > DATE_ADD(now(), INTERVAL -1 month) and mb_id = '".$member['mb_id']."' and allowance_name = 'mining payout (ETH)' ";
		$result -> last30 = sql_fetch($sql)['sum'];

		$result -> miningBalance = '0.00000000';
		$result -> totalRepurchasedAmount = '0.00000000';
		$result -> totalBonusPaid = '0.00000000';
	}

	print json_encode($result);
?>