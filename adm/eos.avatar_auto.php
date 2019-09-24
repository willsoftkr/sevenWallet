<?php
$sub_menu = "600300";
include_once('./_common.php');

$now_date_time = date('Y-m-d H:i:s');
$avatar_no = 1;
$avatar_target ='3000';
$avatar_rate ='10';
$status = '0';

$mem_sql = "select mb_id from g5_member";
$mem_result = sql_query($mem_sql);

$lengths =  sql_num_rows($mem_result);


$i = 1;
while( $row = sql_fetch_array($mem_result)){

$mb_id = $row['mb_id'];
$char = generateRandomCharString(2);
$avatar_id = $mb_id."_".$char.$avatar_no;


$avatar_sql = "INSERT avatar_savings set
mb_id             = '".$mb_id."'
, avatar_no     = '".$avatar_no."'
, avatar_id     = '".$avatar_id."'
, saving_target = '".$avatar_target."'
, saving_rate           = '".$avatar_rate."'
, current_saving   = '0'
, status         = '{$status}'
, setting_date    = '".$now_date_time."'
, update_date    = '".$now_date_time."'
, avatar_character    = '".$char."' ";

//echo "<br>";
//echo $i;
//print_r($avatar_sql);
sql_query($avatar_sql);

if($i == $lengths){
    ended();
}

$i++; 
}

function ended(){
    ob_clean();
    echo (json_encode(array("result" => "success",  "code" => "0010", "sql" => "$lengths avatar created complete")));
}




function generateRandomCharString($length = 2) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


?>