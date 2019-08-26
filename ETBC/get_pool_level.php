<?
include_once('/home/sdevftv/html/common.php');//테스트 서버용 경로

//include_once('/data/sdevftv/html/common.php');
$sql = "select * from g5_member";

$run = sql_query($sql);


while($member = sql_fetch_array($run)){
$my_pool_lv=0;
	if($member['it_pool4']>0){
		$my_pool_lv = 12000*$member['it_pool4'];
	}
	if($member['it_pool3']>0){
		$my_pool_lv = $my_pool_lv+(5000*$member['it_pool3']);
	}	
	 if($member['it_pool2']>0){
		$my_pool_lv = $my_pool_lv+(3000*$member['it_pool2']);
	}	

	 if($member['it_pool1']>0){
		$my_pool_lv = $my_pool_lv+(1000*$member['it_pool1']);
	}

	//if($member['it_GPU']>0){
	//	$my_pool_lv = $my_pool_lv +($member['it_GPU']*3000);
	//}

	$mb_id = $member['mb_id'];
	$lv_up = "update g5_member set pool_level = $my_pool_lv where mb_id='$mb_id'";
	sql_query($lv_up);
}

$sql2 = "select * from g5_shop_cart";
$cart_run = sql_query($sql2);
while($cart = sql_fetch_array($cart_run)){
	if($cart['it_id']=='1515148167'){
		$ordid = $cart['od_id'];
		echo $chanPv = "update g5_shop_order set pv=pv-3000 where od_id='$ordid'";
		echo "<br>";
		sql_query($chanPv);
	}
}
?>
