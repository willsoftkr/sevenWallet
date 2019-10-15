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
        
        $info_sql = "select mb_id, mb_deposit_point from g5_member where mb_id = '{$memeber_id}' ";
        $info_result = sql_fetch($info_sql);
        $info_result['mb_deposit_point'];

        $save_p = $mb_upstair + $info_result['mb_deposit_point'];

        if($save_p>=1 && $save_p<500){
			$grade = 0;
		}
		else if($save_p>=500 && $save_p<3000){
			$grade = 1;
		}
		else if($save_p>=3000 && $save_p<10000){
			$grade = 2;
		}
		else if($save_p>=10000){
			$grade = 3;
        }
        
        if($memeber_id != 'coolrunning'){
            $member_sql = "update g5_member set mb_deposit_point = mb_deposit_point + {$mb_upstair}, mb_deposit_acc = '{$save_p}', grade = '{$grade}' where mb_id = '{$memeber_id}'";
        }
        $member_result = sql_query($member_sql);

        print_r("<br> * ".$member_sql."<br>");

    }
}

?>



