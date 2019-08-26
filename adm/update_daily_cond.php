<?
include_once('./_common.php');

if($_POST['type']=='daily'){

	for($i = 0 ; $i < 4; $i ++){
	$rate[$i] = $_POST['rate'][$i];
	
	$up_rate = "update eos_daily_paid set 
	eos_per = $rate[$i] 
	,recom_1 = $recom_1[$i]
	,recom_2 = $recom_2[$i]
	where idx= '$i' ";
	//print_r($up_rate );
	sql_query($up_rate);
	}
	alert('complete save.');
	goto_url('/adm/v7.daily.cond.php');
}
else{
	
	for($i = 1 ; $i < 11; $i ++){
		$rate[$i] = $_POST['rate'][$i];
		$up_trate = "update eos_daily_immediate set recom_per = $rate[$i] where recom_history = '$i' ";
		sql_query($up_trate);
	}
	//print_r($up_trate);
	alert('complete save.');
	goto_url('/adm/v7.daily.cond.php');
}
?>