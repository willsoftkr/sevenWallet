<?php 

include_once('/home/sdevftv/html/common.php');
$gmember = "select mb_id from g5_member where mb_level =3";
$rmember = sql_query($gmember);

for($i =0 ; $row = sql_fetch_array($rmember); $i++){
	$sql = "SELECT count( mb_brecommend ) as rcom , count( mb_recommend ) as brcom FROM g5_member WHERE mb_recommend = '".$row['mb_id']."'"; 
	$rst = sql_fetch($sql);
	$rcom = $rst['rcom'];
	$brcom = $rst['brcom'];
	if($rcom<=1 || $brcom <= 1){
		echo $set_lv = "update g5_member set mb_level=2 where it_pool1>=1 and mb_id='".$row['mb_id']."'";
		sql_query($set_lv);
	}
	echo 'Rcom : '.$rcom.' brcom : '.$brcom.' MB_ID : '.$row['mb_id'];
	echo "<br>";
}

?>
