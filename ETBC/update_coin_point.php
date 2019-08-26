<?php 
include_once('/var/www/html/etbc/common.php');

$amount = $_POST['amt'];

$amount = str_replace(" EOS","",$amount);

$mb_id = $_POST['mb_id'];
$timestamp = $_POST['timestamp'];
$coin_symbol = $_POST['coin_symbol'];

$date = date('Y-m-d H:i:s');

$save_hist = "insert eos_coin_transfer_hist set idx ='', mb_id='".$mb_id."', transfer_amount=".$amount.", transfer_date = '".$date."', timestamp = '".$timestamp."',transfer_status='Y', coin_symbol='".$coin_symbol."'";
$result = sql_query($save_hist);

if($result){
	$up_point = "update g5_member set mb_save_point = mb_save_point +  ".$amount." where mb_id ='".$mb_id."'";
	sql_query($up_point);
	echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $save_hist)));
}
else{
	echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
}

?>