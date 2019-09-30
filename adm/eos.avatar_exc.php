<?php

include_once ('./_common.php');

ob_start();

if ($to_date){
	$to_date       = $to_date;
}else{
	$to_date    = date('Y-m-d');
}

$now_date_time = date('Y-m-d H:i:s');
$now_date = date('Y-m-d');

echo "기준일 : ".$now_date." <br><br>";

$v7_cost = number_format(get_coin_cost('v7'),2);

$avatar_sql = "select * from avatar_savings where status != 1 ";
$avatar_result = sql_query($avatar_sql);

while( $row = sql_fetch_array($avatar_result)){
    if($row > 0)
    {
        $mb_id = $row['mb_id'];

        $av_rate = ($row['saving_rate']/100);
        $av_target = $row['saving_target'];
        $idx = $row['idx'];
        $current_saving = $row['current_saving'];

        $mem_today_sql = "SELECT mb_id, mb_no, SUM(benefit) AS total_benefit FROM soodang_pay WHERE mb_id = '{$mb_id}' AND DAY = '{$to_date}'";
        $mem_today= sql_fetch($mem_today_sql);

        $mem_today_benefit = $mem_today['total_benefit'];



        // 아바타 정보 
                echo "<br>".$mb_id;
                echo "<br> **"." Avatar : ".$av_target." / ".$av_rate."% / now : <strong>". $current_saving. "</strong> <br> >> 오늘총수당 : <span class='red'>";
                // 적금액
                print_R($mem_today_benefit);
                echo "</span>  || 아바타 적금 : <span class='blue'> +";
    
        $avatar_fund = round(($mem_today_benefit * $av_rate),2);
        $avatar_fund_v7 = $avatar_fund/$v7_cost;

                print_R( $avatar_fund);
                echo "</span>" ;

                echo "  || mb_v7_account :: <span class='red'> -".$avatar_fund_v7."</span> | mb_balance :: <span class='red'> -".$avatar_fund."</span>";




        // 수당에서 출금
        $mem_update_sql = "update g5_member set mb_balance = round((mb_balance - '{$avatar_fund}'),2) ,  mb_v7_account = round((mb_v7_account - '{$avatar_fund_v7}'),2) where mb_id ='{$mb_id}' ";
               
        $result1 = sql_query($mem_update_sql);

        //echo "<br> ";
        //print_r($mem_update_sql);



        // 수당내역 로그
        $allowance_name = "avatar";
        $rec = "Avatar Account income";

        $soodang_log_sql = " insert soodang_pay set day='".$to_date."'";
        $soodang_log_sql .= " ,mb_id		= '".$mb_id."'";
        $soodang_log_sql .= " ,mb_no		= '0'";
		$soodang_log_sql .= " ,mb_name		= '0'";
		$soodang_log_sql .= " ,allowance_name		= '".$allowance_name."'";
        $soodang_log_sql .= " ,benefit		=  '-".$avatar_fund."'";
        $soodang_log_sql .= " ,benefit_usd		=  '-".$avatar_fund."'";
        $soodang_log_sql .= " ,avatar_rate		=  '".$av_rate."'";
		$soodang_log_sql .= " ,rec		= '".$rec."'";
        $soodang_log_sql .= " ,rec_adm	= '".$rec."'";
        $soodang_log_sql .= " ,datetime	= '{$now_date_time}' " ;
               
        $result2 = sql_query($soodang_log_sql);

        //echo "<br>";
        //print_r($soodang_log_sql);


        // 아바타 업데이트
        $total_saving = $current_saving + $avatar_fund;

               

        $avatar_sql = "UPDATE avatar_savings set
        current_saving = '".$total_saving."'
        where idx = '{$idx}'";
        
                    //echo "<br>";
                    //print_r($avatar_sql);
        $result3 = sql_query($avatar_sql);
        

        // 아바타 풀 = 계정생성
        if($total_saving >=  $av_target){

            echo "<br> >>> 적금 초과 :: ";
            print_r($total_saving." >= ". $av_target);

            avatar_add($idx, $mb_id);
        }

        echo "<br>";
    }
}


// 아바타 계정 생성
function avatar_add($idx, $mb_id){
    global $now_date_time;
    global $now_date;

    $mb_avatar_sql = "select * FROM g5_member AS A INNER JOIN avatar_savings AS B ON A.mb_id = B.mb_id WHERE A.mb_id = '{$mb_id}' AND B.idx = '{$idx}'";
    $result = sql_fetch($mb_avatar_sql);

    $result_memo = " 아바타생성 ".$result['avatar_no'];

    //print_r($result['current_saving']."/".$result['saving_target']."/".$result['status']);

    $depth_sql = "SELECT mb_no as recom_no, depth+1 as mb_depth FROM g5_member WHERE mb_id ='{$mb_id}'";
    $depth_result = sql_fetch($depth_sql);
    $depth = $depth_result['mb_depth'];

        $member_add_sql = "insert g5_member set
        mb_id             = '".$result['avatar_id']."'
        , mb_recommend     = '".$mb_id."'
        , mb_recommend_no     = '".$result['mb_no']."'
        , mb_deposit_point = '".$result['current_saving']."'
        , mb_deposit_acc = '".$result['current_saving']."'
        , mb_password = '".$result['mb_password']."'
        , mb_nick_date = '".$now_date."'
        , depth = '".$depth."'
        , mb_email = '".$result['mb_email']."'
        , mb_hp = '".$result['mb_hp']."'
        , mb_datetime = '".$now_date_time."'
        , mb_email_certify = '".$now_date_time."'
        , mb_email_certify2 = '".$result['mb_email_certify2']."'
        , mb_open_date = '".$now_date."'
        , last_name = '".$result['last_name']."'
        , first_name = '".$result['first_name']."'
        , nation_number = '".$result['nation_number']."'
        , mb_memo           = '". $result_memo."'";

        echo "<br>";
        //print_r($mb_avatar_sql);
        echo "  >>>> 아바타 멤버 자동 생성 ::  <span class='blue'>".$result['avatar_id']."</span>";
        //print_r($member_add_sql);
        
        $mem_create = sql_query($member_add_sql, false);

        if($mem_create){
            $update_sql = "UPDATE avatar_savings set
            create_date = '".$now_date_time."'
            , status           = '1'
            , update_date    = '".$now_date_time."'
            where idx = '{$idx}'";

                echo "<br> >>>>> 아바타적금 업데이트 :: ".$result['avatar_id']." || status = 1";
                //print_r($update_sql);

            sql_query( $update_sql, false);
            

            // 다음 아바타 생성
            $avatar_no = $result['avatar_no'] + 1;
            $avatar_id = $mb_id.'_'.$result['avatar_character'].$avatar_no;

            $avatar_add_sql = "INSERT avatar_savings set
            mb_id             = '".$mb_id."'
            , avatar_no     = '".$avatar_no."'
            , avatar_id     = '".$avatar_id."'
            , saving_target = '3000'
            , saving_rate           = '10'
            , current_saving   = '0'
            , status         = '0'
            , setting_date    = '".$now_date_time."'
            , update_date    = '".$now_date_time."'
            , avatar_character    = '{$result['avatar_character']}' ";

                echo "<br> >>>>>>> 신규 아바타적금 생성 :: <span class='blue'>".$avatar_id."</span>";
                //print_r($avatar_add_sql);

            sql_query($avatar_add_sql);
    }else{
        echo "<br><span class='red'> 아바타계정생성 오류 발생</span>";
    }
}
?>

<style>
	.red{color:red; font-weight:600}
	.blue{color:blue; font-weight:600}
</style>

<?
$html = ob_get_contents();
//ob_end_flush();

$myfile = fopen(G5_PATH.'/data/log/avatar/avatar_'.$to_date.'.html', "w");
file_put_contents(G5_PATH.'/data/log/avatar/avatar_'.$to_date.'.html', ob_get_contents());
?>


