<?php

include_once('/home/sdevftv/html/common.php');

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,"http://whattomine.com/coins/1.json?utf8=%E2%9C%93&hr=1&p=0&fee=0.0&cost=0&hcost=0.0&commit=Calculate");
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
//접속할 URL 주소
$result = curl_exec ($ch);
curl_close ($ch);
$obj = json_decode($result);
echo $exchange_rate = $obj->{'exchange_rate'};

$today = date("Y-m-d H:i:s");
$log_txt = $today." ".$erate." ".$exchange_rate."\n";
$log_dir = "/home/sdevftv/html/new/Logs";   
$log_file = fopen($log_dir."/log.txt", "a");  
fwrite($log_file, $log_txt."\n");  
fclose($log_file);  


$ch3 = curl_init();
curl_setopt($ch3, CURLOPT_URL,"https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=USD");
curl_setopt ($ch3, CURLOPT_RETURNTRANSFER, 1); 
$result = curl_exec ($ch3);
curl_close ($ch3);
$obj = json_decode($result);
$etherate = str_replace("$", "", $obj->{'USD'});  //1이더당 $가격.

$erate = "update  coin_cost set btc_cost = $exchange_rate, eth_cost = $etherate ;";
sql_query($erate);
?>
