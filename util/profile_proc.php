<?php
include_once('./_common.php');
//print_R($_POST);
ob_clean();

if(empty($_POST)){
    //alert('Not Availabled. Please retry');
    echo (json_encode(array("result" => "error",  "code" => "0001", "sql" => 'please check retry.')));
}

$category = $_POST['category'];

if($category == 'email'){

    if($member['mb_email'] != $_POST['email1']){
        //alert('The current email address is incorrect <br> Check current email.');
        echo (json_encode(array("result" => "error",  "code" => "0002", "sql" => 'Current email address does not match.')));
        exit;
    }else{
        //$email_up_sql = "UPDATE g5_member set mb_password = password('".$_POST['email3']."') where mb_id = $member['mb_id']";
        $email_up_sql = "UPDATE g5_member set mb_email = '{$_POST['email3']}' where mb_id = '{$member['mb_id']}' ";
        $email_up_result= sql_query($email_up_sql);
        if($email_up_result){
            echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => 'change password')));
        }
    }
}

if($category == 'phone'){

    if($member['mb_email'] != $_POST['email1']){
        //alert('The current email address is incorrect <br> Check current email.');
        echo (json_encode(array("result" => "error",  "code" => "0002", "sql" => 'Current email address does not match.')));
        exit;
    }else{
        //$email_up_sql = "UPDATE g5_member set mb_password = password('".$_POST['email3']."') where mb_id = $member['mb_id']";
      

        //echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => 'change password')));
    }
}


?>