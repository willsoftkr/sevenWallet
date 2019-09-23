<?php
include_once('./_common.php');

$mb_id = $_POST['mb_id'];
$avatar_target =$_POST['avatar_target'];
$avatar_rate = $_POST['avatar_rate'];
$avatar_no = $_POST['avatar_no'];
$mode = $_POST['mode'];

if($_GET['debug']){
    $mb_id = 'coolrunning';
    $avatar_no = '1';
    $avatar_target = '3000';
    $avatar_rate= '10';
    $mode ="w";
}

$now_date = date('Y-m-d H:i:s');
$avatar_id = $mb_id."_BT".$avatar_no;


if($mode == 'w'){
    $sql = "insert avatar_savings set
            mb_id             = '".$mb_id."'
            , avatar_no     = '".$avatar_no."'
            , avatar_id     = '".$avatar_id."'
            , saving_target = '".$avatar_target."'
            , saving_rate           = '".$avatar_rate."'
            , current_saving   = '0'
            , status         = '1'
            , create_date    = '".$now_date."'";

    if($_GET['debug']){   
        $rst = $sql;
        print_R($rst);
    }else{
        $rst = sql_query($sql, false);
        $save_hist = 'ok';
    }
   


    if($rst){//오더 테이블 기록이 이상 없을 시에
        echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => $sql)));
    }
    else{
        echo (json_encode(array("result" => "failed",  "code" => "0001", "sql" => $save_hist)));
    }
}

?>