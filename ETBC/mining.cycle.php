<?php
include_once('./_common.php');

$sql = "
select 
	ifnull(sum(if(profit_days between 1 and 200, benefit, 0)),0) as mine_cycle0, 
	ifnull(sum(if(profit_days between 201 and 400, benefit, 0)),0) as mine_cycle1, 
	ifnull(sum(if(profit_days between 401 and 600, benefit, 0)),0) as mine_cycle2, 
	ifnull(sum(if(profit_days between 601 and 800, benefit, 0)),0) as mine_cycle3, 
	ifnull(sum(if(profit_days between 801 and 1000, benefit, 0)),0) as mine_cycle4,
	ifnull(sum(if(profit_days between 1 and 200, fee_forBTC, 0)),0) as fee_cycle0, 
	ifnull(sum(if(profit_days between 201 and 400, fee_forBTC, 0)),0) as fee_cycle1, 
	ifnull(sum(if(profit_days between 401 and 600, fee_forBTC, 0)),0) as fee_cycle2, 
	ifnull(sum(if(profit_days between 601 and 800, fee_forBTC, 0)),0) as fee_cycle3, 
	ifnull(sum(if(profit_days between 801 and 1000, fee_forBTC, 0)),0) as fee_cycle4,
	ifnull(sum(if(profit_days between 1 and 200, benefit_usd, 0)),0) as mine_usd_cycle0, 
	ifnull(sum(if(profit_days between 201 and 400, benefit_usd, 0)),0) as mine_usd_cycle1, 
	ifnull(sum(if(profit_days between 401 and 600, benefit_usd, 0)),0) as mine_usd_cycle2, 
	ifnull(sum(if(profit_days between 601 and 800, benefit_usd, 0)),0) as mine_usd_cycle3, 
	ifnull(sum(if(profit_days between 801 and 1000, benefit_usd, 0)),0) as mine_usd_cycle4,
	ifnull(sum(if(profit_days between 1 and 200, fee, 0)),0) as fee_usd_cycle0, 
	ifnull(sum(if(profit_days between 201 and 400, fee, 0)),0) as fee_usd_cycle1, 
	ifnull(sum(if(profit_days between 401 and 600, fee, 0)),0) as fee_usd_cycle2, 
	ifnull(sum(if(profit_days between 601 and 800, fee, 0)),0) as fee_usd_cycle3, 
	ifnull(sum(if(profit_days between 801 and 1000, fee, 0)),0) as fee_usd_cycle4
from pinna_soodang_mining_pay
	 ";

if($_GET['category'] == 'eth') {
	$condition .=  " where mb_id = '{$member[mb_id]}' and pool_count = $_GET[pool_count] and allowance_name = 'mining payout (ETH)' ";
}else {
	$condition .=  " where mb_id = '{$member[mb_id]}' and pool_count = $_GET[pool_count] and allowance_name = 'mining payout (BTC)' ";
}

print json_encode(sql_fetch($sql.$condition));
?>
