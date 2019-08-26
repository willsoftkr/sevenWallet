<?include_once "../../common.php";
$item_price = array(1 => 3400, 10200, 17000, 40800, 80);
echo $member['mb_level'];

$sql_common = " from {$g5['member_table']} where mb_id ='admin'";

$sql = " select * {$sql_common} ";
$result = sql_query($sql);

// for ($i=0; $row=sql_fetch_array($result); $i++) {
$i=1;
			//접속할 URL 주소
while($row=sql_fetch_array($result)){

	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch,CURLOPT_URL,"http://whattomine.com/coins/1.json?utf8=%E2%9C%93&hr=".$item_price[$i]."&p=0&fee=0.0&cost=0&hcost=0.0&commit=Calc");
	$ret = curl_exec ($ch);
	$obj = json_decode($ret);
	$day_rev =  str_replace("$", "", $obj->{'revenue'}); 
	echo $day_rev;
	echo $ret;
	curl_close($ch);


	?> <br><?
}

  ?>
