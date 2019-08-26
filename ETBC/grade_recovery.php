<?php 

include_once('/home/sdevftv/html/common.php');
echo $gmember = "select mb_id from g5_member  where mb_level=3";
$rmember = sql_query($gmember);

for($i =0 ; $row = sql_fetch_array($rmember); $i++){
	 $get_rmember = "select count(mb_recommend) as cont1 from g5_member where mb_recommend='".$row[mb_id]."'";
	
	 $get_brmember = "select count(mb_brecommend) as cont2 from g5_member where mb_brecommend='".$row[mb_id]."'";
	
	$cond1 = sql_query($get_rmember);
	$cond2 = sql_query($get_brmember);
	
	$rest1 = sql_fetch_array($cond1);
	$rest2 = sql_fetch_array($cond2);
	 $row[mb_id].$rest1['cont1'].'/'.$rest2['cont2'];
	echo "<br>";

	if($rest1['cont1']<=1 || $rest2['cont2']<=1){
		echo $up_lv="update g5_member set mb_level = 2 where mb_id='".$row[mb_id]."'";
			echo "<br>";
		sql_query($up_lv);
	}

}

?>
 