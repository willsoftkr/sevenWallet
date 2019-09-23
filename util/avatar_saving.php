<?php
include_once('./_common.php');

$create_auth = false;

$mb_id = $_POST['mb_id'];
$avatar_no = $_POST['avatar_no'];
$avatar_target =$_POST['avatar_target'];
$avatar_rate = $_POST['avatar_rate'];
$mode = $_POST['mode'];

if($_GET['debug']){
    $mb_id = 'coolrunning';
    $avatar_no = '1';
    $avatar_target = '4000';
    $avatar_rate= '30';
    $mode ='w';
}

$now_date = date('Y-m-d H:i:s');
//$avatar_id = $mb_id."_BT".$avatar_no;

$avatar_sql = "select * from avatar_savings where mb_id = '{$mb_id}' order by create_date desc limit 0,1";
$av = sql_fetch($avatar_sql);
//print_r($av);
//echo "<br>";


if($av){
    if($av['status'] == '1'){
        $create_auth = true;
        $avatar_no = $av['avatar_no'] + 1;
        $avatar_id = $mb_id.$av['char'].$avatar_no;
    }else{
        $mode ='u';
        $idx = $av['idx'];
    }
}else{
    $char = generateRandomCharString(2);
    $avatar_id = $mb_id.$char.$avatar_no;
}



// 아바타 정보 신규생성
if($mode == 'w'){
    $status = '0';
    // 아바타 세팅 저장
    $sql = "INSERT avatar_savings set
            mb_id             = '".$mb_id."'
            , avatar_no     = '".$avatar_no."'
            , avatar_id     = '".$avatar_id."'
            , saving_target = '".$avatar_target."'
            , saving_rate           = '".$avatar_rate."'
            , current_saving   = '0'
            , status         = '{$status}'
            , setting_date    = '".$now_date."'
            , update_date    = '".$now_date."'
            , avatar_character    = '".$char."' ";

    if($_GET['debug']){   
        print_r('신규생성');
        echo "<br>";
        print_R($sql);
    }else{
        $rst = sql_query($sql, false);
        if($rst){
            echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $sql)));
        }
        else{
            echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
        }
    }
}

// 아바타 정보 업데이트
else if($mode == 'u'){
    

    $sql = "UPDATE avatar_savings set
            saving_target = '".$avatar_target."'
            , saving_rate           = '".$avatar_rate."'
            , update_date    = '".$now_date."'
            where idx = '{$idx}'";

    if($_GET['debug']){   
        print_r('업데이트');
        echo "<br>";
        print_R($sql);
    }else{
        $rst = sql_query($sql, false);
        if($rst){
            echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $sql)));
        }
        else{
            echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
        }
    }
}

function generateRandomCharString($length = 3) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


if($mode == 'c'){
    if($avatar_target >= $av['current_saving'] && $av['status'] == 1)
    {
        $member_add_sql = "insert g5_member set
        mb_id             = '".$avatar_id."'
        , mb_recommend     = '".$avatar_no."'
        , avatar_id     = '".$avatar_id."'
        , saving_target = '".$avatar_target."'
        , saving_rate           = '".$avatar_rate."'
        , current_saving   = '0'
        , status         = '1'
        , create_date    = '".$now_date."'";
    }
    print_r($member_add_sql);

    //$mem_create = sql_query($member_add_sql, false);

}

?>