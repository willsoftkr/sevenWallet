

<?
/*
include_once("./_common.php");
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

include_once(G5_PATH.'/head.php');
*/
// curl 사용
$coin  = 'tbtc';
$wallet_id = "5d528b3dafc469b8039658e5e7cdad1b";
$adressorid = "2NEk3zhFxmtcgjwEMLi57cBG5CDppA4j86j";

/*
$post_data["param1key"] = "param1value";
$post_data["param2key"] = "param2value";
$data = array(
    'test' => 'test'
);
*/

//$url = 'https://test.bitgo.com/api/v2/'.$coin.'/wallet/'.$mb_id.'/address'; //접속할 url 입력
//$url = 'http://localhost:3080/api/v2/'.$coin.'/wallet/'.$mb_id.'/address'; //접속할 url 입력

$Enter = $coin."/wallet/".$wallet_id."/address/".$adressorid;
//$Enter = "user/".$wallet_id;

$BITGO_EXPRESS_HOST= 'localhost:3000/merchant/';
$url = "http://".$BITGO_EXPRESS_HOST."/api/v2/".$Enter;
echo "<br>". $url ."<br><br>";


$access_token_value = "v2x81f70886ea9a3062f76e44b9f9c397911658131a0442975033313332800d3cf9";
$header_data[] = 'Authorization: Bearer '.$access_token_value;

$ch = curl_init(); //curl 초기화
curl_setopt($ch, CURLOPT_URL, $url); //URL 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //요청결과 문자열 반환
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
curl_setopt ($ch, CURLOPT_POSTFIELDS, $post_data); //POST로 보낼 데이터 지정하기

curl_setopt($ch, CURLOPT_POST, true); 

$response = curl_exec ($ch);

//$data = json_decode($response);
//print_r($response);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);    
 
$body_json = json_decode($body, true);
print_r($body_json);

curl_close($ch);
*/
//include_once(G5_THEME_PATH.$_SERVER['PHP_SELF']);

?>