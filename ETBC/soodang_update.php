<?
include_once('/home/sdevftv/html/common.php');//테스트 서버용 경로
//include_once('/data/sdevftv/html/common.php'); //실서버용 경로

$sqlmid = "select mb_id from g5_member order by mb_no";
$retmid = sql_query($sqlmid);
while($row=sql_fetch_array($retmid)){
$mid = $row['mb_id'];
	$chkex = "select mb_id from soodang_calc where mb_id ='$mid'";
	 $r = sql_fetch($chkex);
	if(!$r){
		$insql = "INSERT INTO soodang_calc( no, mb_id, total_pinnacle, total_mining, pinnacle, prevpoint, mining_btc, mining_eth, withdrawal, withd_cnt, now_balance ) value(
 '', '$mid',0,0,0,0,0,0,0,0,0)  ";
	 sql_query($insql);
	}
	
}

$resetp = "update soodang_calc set pinnacle = 0";
sql_query($resetp);

$sql = "select mb_id, round(sum(benefit),8) as soodang from soodang_pay where allowance_name in ('Binary','Binary Matching','Direct Sponsor','Indirect Sponsor') group by mb_id";
$ret = sql_query($sql);

while($row=sql_fetch_array($ret)){
	$benefit = $row['soodang'];
	$id = $row['mb_id'];
	$up_soo1 = "update soodang_calc set pinnacle={$benefit} where mb_id='{$id}'";
	$up_soo2 = "update soodang_calc set total_pinnacle={$benefit} where mb_id='{$id}'";
	sql_query($up_soo1);
	sql_query($up_soo2);
}

$reset = "update soodang_calc set mining_btc = 0";
sql_query($reset);
$sql2 = "select mb_id, round(sum(benefit),8) as soodang from soodang_pay where allowance_name LIKE 'mining payout%' AND rec LIKE '%pool1%' group by mb_id";

$ret2 = sql_query($sql2);

while($row11=sql_fetch_array($ret2)){
	$benefit = $row11['soodang'];
	$id = $row11['mb_id'];
	$up_soo11 = "update soodang_calc set mining_btc={$benefit} where mb_id='{$id}'";
	sql_query($up_soo11);
}

$reset = "update soodang_calc set mining_btc2 = 0";
sql_query($reset);
$sql22 = "select mb_id, round(sum(benefit),8) as soodang from soodang_pay where allowance_name LIKE 'mining payout%' AND rec LIKE '%pool2%' group by mb_id";

$ret22 = sql_query($sql22);

while($row22=sql_fetch_array($ret22)){
	$benefit = $row22['soodang'];
	$id = $row22['mb_id'];
	$up_soo22 = "update soodang_calc set mining_btc2={$benefit} where mb_id='{$id}'";
	sql_query($up_soo22);
}

$reset = "update soodang_calc set mining_btc3 = 0";
sql_query($reset);
echo $sql33 = "select mb_id, round(sum(benefit),8) as soodang from soodang_pay where allowance_name LIKE 'mining payout%' AND rec LIKE '%pool3%' group by mb_id";

$ret33 = sql_query($sql33);

while($row33=sql_fetch_array($ret33)){
	$benefit = $row33['soodang'];
	$id = $row33['mb_id'];
echo	$up_soo33 = "update soodang_calc set mining_btc3={$benefit} where mb_id='{$id}'";
echo "<br>";
	sql_query($up_soo33);
}

$reset = "update soodang_calc set mining_btc4 = 0";
sql_query($reset);

$sql44 = "select mb_id, round(sum(benefit),8) as soodang from soodang_pay where allowance_name LIKE 'mining payout%' AND rec LIKE '%pool4%' group by mb_id";
$ret44 = sql_query($sql44);
while($row44=sql_fetch_array($ret44)){
	$benefit = $row44['soodang'];
	$id = $row44['mb_id'];
	$up_soo44 = "update soodang_calc set mining_btc4={$benefit} where mb_id='{$id}'";
	sql_query($up_soo44);
}

$member = "select mb_id from g5_member order by mb_no";
$mg = sql_query($member);
while($mbr=sql_fetch_array($mg)){
	$id = $mbr['mb_id'];
	echo $total_m = "update soodang_calc set total_mining=round(mining_btc+mining_btc2+mining_btc3+mining_btc4,8) where mb_id='{$id}'";
	sql_query($total_m);
}

$reset = "update soodang_calc set withdrawal = 0";
sql_query($reset);
$sql3 = "select id, count(id) as cnt, round(sum(amt),8) as amt from withdrawal_request where status in('Y','R') group by id";
$ret3 = sql_query($sql3);

while($row3=sql_fetch_array($ret3)){
	$id = $row3['id'];
	$cnt = $row3['cnt'];
	$withd = $row3['amt']+$cnt*0.002;
	$up_soo2 = "update soodang_calc set withdrawal={$withd}, withd_cnt={$cnt} where mb_id='{$id}'";
	sql_query($up_soo2);
}

$calc_m1 = "update soodang_calc set pinnacle = round(pinnacle - withdrawal,8)";
sql_query($calc_m1);

$sql = "update soodang_calc set now_balance = round(pinnacle,8);";
sql_query($sql);

//이더리움 합계 계산
$sql2 = "SELECT mb_id, round( sum( benefit ) , 8 ) AS soodang FROM soodang_pay WHERE allowance_name LIKE 'mining payout%' AND rec LIKE '%Gpu%' group by mb_id";
$ret2 = sql_query($sql2);

while($row2=sql_fetch_array($ret2)){
	$benefit = $row2['soodang'];
	$id = $row2['mb_id'];
	$up_soo1 = "update soodang_calc set mining_eth={$benefit} where mb_id='{$id}'";
	$up_memeth = "update g5_member set it_pool5_profit = {$benefit} where mb_id='{$id}'";
	sql_query($up_soo1);
	sql_query($up_memeth);
}


$up_bal = "SELECT mb_id, total_mining, now_balance, mining_btc,mining_btc2,mining_btc3,mining_btc4, mining_eth FROM soodang_calc"; 
$bal_ret = sql_query($up_bal);
while($brow = sql_fetch_array($bal_ret)){
	$mid = $brow['mb_id'] ;
	$tot_m = $brow['total_mining'];
	$pool1p = $brow['mining_btc'];
	$pool2p = $brow['mining_btc2'];
	$pool3p = $brow['mining_btc3'];
	$pool4p = $brow['mining_btc4'];
	$poolethp = $brow['mining_eth'];
	$nb = $brow['now_balance'];
	
	$temp = 0;
	if($nb<0 && (($tot_m+$nb)>=0)){ 
		$temp = $pool1p + $nb;		
		if($temp > 0){
			$pool1p = round($temp,8);
			$nb=0;
		}
		else{
			$temp = $temp+$pool2p;
			if($temp>0){
				$pool2p = round($temp,8);
				$nb = 0; $pool1p=0;
			}
			else{
				$temp = $temp+$pool3p;
				if($temp>0){
					$pool3p = round($temp,8);
					$nb = 0; $pool1p=0; $pool2p=0;
				}
				else{
					$temp = $temp+$pool4p;
					if($temp>0){
						$nb = 0; $pool1p=0; $pool2p=0; $pool3p=0;
						$pool4p= round($temp,8);
					}
					else{
						$pool1p=0; $pool2p=0; $pool3p=0; $pool4p=0;
						$nb = round($temp,8);
					}
				}
			}
		}
	}
	
	$calc = "update soodang_calc set now_balance=$nb, mining_btc=$pool1p, mining_btc2 = $pool2p, mining_btc3 = $pool3p, mining_btc4 = $pool4p where mb_id='$mid'";
	sql_query($calc);
	//$balup = "update g5_member set mb_balance={$nb}, it_pool1_profit = {$pool1p}, it_pool2_profit = {$pool2p}, it_pool3_profit = {$pool3p}, it_pool4_profit = {$pool4p}, it_pool5_profit = {$poolethp} where mb_id= '{$mid}'";
	//sql_query($balup);
}

?>
