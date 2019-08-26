<?php
include_once('./_common.php');


$mb_id       = trim($_POST['mb_id']);
$f = trim($_POST['func']);
$mb = get_member($mb_id);

if($f=="member"){

	$mb_password = trim($_POST['current_password']);
	$new_pass = trim($_POST['new_password']);
	
	/*
	$new_pass2 = trim($_POST['new_password2']);
	$new_email = trim($_POST['new_email']);
	$nation_code = trim($_POST['nation_code']);
	$phone_number = trim($_POST['phone_number']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	*/
	
	if (!$mb['mb_id'] || !check_password($mb_password, $mb['mb_password'])) {

		echo (json_encode(array("result" => "The member ID is not registered or the password \n is incorrect. Passwords are case-sensitive.",  "code" => "0000")));
	}
	/*
	else if($new_pass!=$new_pass2){
			echo (json_encode(array("result" => "New password and confirm password is unmatched.",  "code" => "0000")));
	}*/

	else{
		
		$sql = "update g5_member set ";
		
		if($new_pass){
			$new_pass = get_encrypt_string($new_pass);
			
			$sql .= " mb_password = '".$new_pass."' , mb_email = '".$_POST['new_password']."'";
		}

		/*
		if($new_email){
			$sql .= "mb_email = '".$new_email."',";
		}
		if($first_name){
			$sql .= "first_name = '".$first_name."',";
		}
				if($last_name){
			$sql .= "last_name = '".$last_name."'";
		}
		if($nation_code){
			if($phone_number){
				$sql .= ", mb_hp = '".$phone_number."', nation_number='".$nation_code."'";
			}
		}
		*/

		$sql .= " where mb_id = '".$mb['mb_id']."'";
		
		$rst = sql_query($sql);

		
		if($rst){
			echo (json_encode(array("result" => "OK",  "code" => "0000")));
		}
		else{
			echo (json_encode(array("result" => "DB_Error Occured",  "code" => "0001")));
		}
		
	}

}

/*
if($f=="otp"){

	
	$Base32 = new Base32();
	$encoded = $Base32->encode(str_pad($member['mb_id'], 20 , "!&%"));
	$totp = new \OTPHP\TOTP($encoded);
	
	if(check_mail_token($mb_id, $_POST['key'])){
	
		if($totp->now() != $_POST['auth_code']){
			echo (json_encode(array("result" => "OTP Number Is Unmatched",  "code" => "0001")));
		}else {	
			$status       = trim($_POST['otp_status']);
			$sql = "update g5_member set otp_flag = '".$status."' where mb_id='".$mb_id."'";
			//echo $sql;
			$rst = sql_query($sql);
			if($rst){
				echo (json_encode(array("result" => "OK",  "code" => "0000")));
			}
			else{
				echo (json_encode(array("result" => "FAIL",  "code" => "0001")));
			}
		 }
	}else{
		echo (json_encode(array("result" => "This Token is Invalidate  or Expired",  "code" => "0001")));
	}
}
*/


if($f=="change_password"){
	$new_pass = trim($_POST['new_password']);
	//$new_pass2 = trim($_POST['new_password2']);
	//$temp_password = trim($_POST['temp_password']);
	
	/*
	if (!$mb['mb_id'] || $temp_password!=$mb['mb_password']){
		echo (json_encode(array("result" => "This is illegal access",  "code" => "0000")));
	}

	else if($new_pass!=$new_pass2){
			echo (json_encode(array("result" => "New password and confirm password is unmatched.",  "code" => "0000")));
	}
	*/
	
		$sql = "update g5_member set ";
		if($new_pass){
			$new_pass = get_encrypt_string($new_pass);
			$sql .= " mb_password = '".$new_pass."'";
			$sql .= " where mb_id = '".$mb['mb_id']."'";
			$rst = sql_query($sql);
			
			if($rst){
				echo (json_encode(array("result" => "OK",  "code" => "0000")));
			}
			else{
				echo (json_encode(array("result" => "DB_Error" ,  "code" => "0001")));
			}
		}
	
}


function check_mail_token($mb_id, $token){
	$get_token_last = "select * from pinna_mail_tonken where mb_id='".$mb_id."' order by create_time desc limit 1" ;
	$rst_t = sql_fetch($get_token_last);
	$expired_time = $rst_t['invaildate_time'];
	$now_time =  date("Y-m-d H:i:s",time());
	$expired = strtotime($expired_time);
	$now = strtotime($now_time);
	if(($now - $expired)>0){
		return false;
	}
	if(hash("sha256", $token) == $rst_t['verify_code']){
		return true;
	}else{
		return false;
	}
}
?>

