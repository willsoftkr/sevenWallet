<?
include_once("./_common.php");

/*BTC 코인시세 20분마다 가져오기*/
$url = 'http://whattomine.com/coins/1.json';
if ($argc > 1){
    $url = $argv[1];
}
$ch=curl_init();
// user credencial

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch, CURLOPT_VERBOSE, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


$response = curl_exec($ch);

/*
curl_close($ch);
var_dump($response);
*/

//$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header_size = 0;
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);    
 
$body_json = json_decode($body, true);

$sql = " update coin_cost set 
btc_cost = '{$body_json['exchange_rate']}'" ;
sql_query($sql);

if($_GET['url']){
	alert('현재시세를 반영했습니다.');
	goto_url('/adm/config_price.php');
}else{
	print_r($body_json['exchange_rate']);
}
?>
