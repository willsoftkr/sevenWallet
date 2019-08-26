<?
/* API CODe */
$api_code="c5cc6fc8-46db-426c-b512-0eb2334f4d7a";
$wallet_id = "4df6b0fa-4d21-4b3c-93fc-2b9140f28757";
$address = "1LAr2MxKnnHauk2oHoo4xPQJx6L5pT5huH";

/* 회원 아이디 */
$wallet1 = "b8b76c35-9429-4e40-9420-1ae8166af08b";
$address1 = "1Ga2d9G5eCmh8Ei2Ym8c6vctsz2yDwH8hF";
$label = "sooWallet2";

$wallet2 = "xpub6EEcenojqyn5x7SHmcUFT3KuiSi9Lxz2AaGQ1y18USBpf6auPfiwf2dWCjUE3rKKfJ6M9V34kFsHCu1w2WyNfsNNuAfq8uD8JmXbTXA7RrJ";

$main_password = "!@zx235689";
$firstpassword="!@zx235689";

$postdata = http_build_query(
		array(
			'password' => '12341234',
			'label' => 'Wallet1',
			'api_code' => $guid
		)
	);

$opts = array('http' => 
	array (
		'method' => 'POST',
		'header' => 'Content-type: application/xwww-form-urlencoded',
		'content' => $postdata
	)
);


//$json_get_url = "http://localhost:3000/api/v2/create?label=sooWallet&password=".$main_password."&api_code=".$guid;
//$json_url = "http://localhost:3000/merchant/$wallet1/balance?password=$main_password&api_code=$api_code"; 

$create = "http://localhost:3000/merchant/$wallet1/accounts/create?label=$label&password=$main_password&api_code=$api_code";
$active =   "http://localhost:3000/merchant/$wallet1/enableHD?password=$main_password&api_code=$api_code";
$account = "http://localhost:3000/merchant/$wallet1/accounts?password=$main_password&api_code=$api_code";
$lists = "http://localhost:3000/merchant/$wallet1/list?password=$main_password&api_code=$api_code";
$getting = "http://localhost:3000/merchant/$wallet1/address_balance?password=$main_password&address=$address1";

$json_url = $getting;

echo $json_url ."<br><br>";

$json_data = file_get_contents($json_url, false, stream_context_create($opts));

$json_feed = json_decode($json_data);

print_r($json_feed);

/*
$message = $json_feed->message;
$txid = $json_feed->tx_hash;
*/


function get_Balace(){ 
	$json_url = "http://localhost:3000/merchant/$wallet1/balance?password=$main_password&api_code=$api_code"; 
	$json_data = file_get_contents($json_url,false, stream_context_create($opts));
	$json_feed = json_decode($json_data);

	return $json_feed->balance;
}

function createWallet(){
	$json_url = "http://localhost:3000/merchant/$wallet1/balance?password=$main_password&api_code=$api_code"; 
	$json_data = file_get_contents($json_url,false, stream_context_create($opts));
	$json_feed = json_decode($json_data);

	return $json_feed->balance;
}


?>