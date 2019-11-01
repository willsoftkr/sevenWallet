<?php
include_once('./_common.php');
//print_R($_POST);
ob_clean();

function shift_hp($val){
	return preg_replace("/[^0-9]/","",$val);
}

if(empty($_POST)){
    //alert('Not Availabled. Please retry');
    echo (json_encode(array("result" => "error",  "code" => "0001", "sql" => 'please check retry.')));
    return false;
}


$category = $_POST['category'];

if($category == 'email'){

    if($member['mb_email'] != $_POST['email1']){
        //alert('The current email address is incorrect <br> Check current email.');
        echo (json_encode(array("result" => "error",  "code" => "0002", "sql" => 'Current email address does not match.')));
        return false;
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

    $mb_hp = shift_hp($member['mb_hp']);
    $post_hp = shift_hp($_POST['hp_num']);
    
    if($mb_hp != $post_hp){
        echo (json_encode(array("result" => "error",  "code" => "0002", "sql" => 'Current phone number does not match.')));
        return false;
    }else{
        $hp_up_sql = "UPDATE g5_member set mb_hp = '{$post_hp}', nation_number = '{$_POST['new_nation_num']}' where mb_id = '{$member['mb_id']}' ";
        $hp_up_result= sql_query($hp_up_sql);
        echo (json_encode(array("result" => "success",  "code" => "0000", "sql" => 'change phone number')));
    }
}


?>