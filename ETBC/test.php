<?
	include_once('/home/sdevftv/html/common.php');
	include_once('./plan_bbinary.php');
	realtime_binary('rjw8500',1000000,'asdf');
/*$today = date("Y-m-d",time() - 3600*24);
echo $today;

$rwc_sender = "select * from {$pinna['rwc_wallet']} where mb_id='rose'";
echo $rwc_sender;

	
	$get_cond = "select * from pinna_mining_cond";
	$list = sql_query($get_cond);

	while($row = sql_fetch_array($list)){
		$payed_amt = array();
		$manage_fee = array();
		$payed_amt[$row['pool_name']] = $row['payed_amt']; 
		$manage_fee[$row['pool_name']] = $row['manage_fee'];

		echo '..'.$payed_amt[$row['pool_name']];
	}

/*	$sel_id = "select mb_id, mb_wallet, my_walletId from g5_member where mb_wallet <> '' and mb_wallet is not null ";
	$rst = sql_query($sel_id);

	while($w_rst = sql_fetch_array($rst)){		
	$wid = $w_rst['my_walletId'];
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/$wid/balance?password=0803bjuung");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	//접속할 URL 주소
	$result = curl_exec ($ch);
	curl_close ($ch);
	$obj = json_decode($result);
	$balance =  $obj->{'balance'};
	echo $w_rst['mb_wallet'].' / '.$w_rst['my_walletId'].' balance : '.$balance.' id : '.$w_rst['mb_id'].'<br>';
	}*/
?>

