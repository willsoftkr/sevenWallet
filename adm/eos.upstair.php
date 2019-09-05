<?php

include_once ('./_common.php');

if ($to_date){
	$to_date       = $to_date;
}else{
	$to_date    = date('Y-m-d');
}


$upstair_sql = "select *, date(od_time) from g5_shop_order where date(od_time) = '{$to_date }}'";
$upstair_result = sql_query($upstair_sql);



while( $row = sql_fetch_array($upstair_result) ){
    if($row > 0)
    {
        $memeber_id = $row['mb_id'];
        $mb_upstair = $row['upstair'];

        $member_sql = "update g5_member set mb_deposit_point = mb_deposit_point + {$mb_upstair}, mb_deposit_acc = mb_deposit_acc + {$mb_upstair} where mb_id = '{$memeber_id}'";
        $member_result = sql_query($member_sql);

        print_r("<br> * ".$member_sql."<br>");

    }
}

?>



