<?
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

// 인증키를 메일로 보내고 json 으로 단방향 암호화된 key 리턴 

header('Content-Type: application/json');

/*function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}*/
$randomStr = generateRandomString(5);

// 메일 보내기. 
$subject = '['.$config['cf_title'].'] Verify Email.';
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>EMAIL VERIFICATION</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	
</head>
<body style="">

	<div class="sign-up-container" style="padding: 30px;text-align: center;width: 650px;box-sizing: border-box;font-family: 'Raleway', sans-serif">
		<div class="form-brand" style="text-align: center;">
			<!-- <a href="http://www.pinnaclemining.net"><img src="http://202.239.44.110/new/images/logo.png" width="25" alt="pinnacle logo"> -->
		</div></a>
		<h1 class="blue" style="text-align: center;color: rgb(0, 121, 211);"><i class="far fa-envelope"></i> PLEASE VERIFY YOUR EMAIL</h1>
		<p style="    font-size: 18px;    line-height: 1.3;">
			<br>
			Verification for your 2factor authentication setting !<br>
			<br>
			Email Authentication Code : 
			<strong><? echo $randomStr;?></strong>
			<br>
			<br>
			Thank you,<br>
			Fiji Support
		</p>
	</div>

</body>
</html>
<?
$expired = date("Y-m-d H:i:s",time() + (3600*3));
$now  = date("Y-m-d H:i:s",time());

$save_token = "INSERT pinna_mail_tonken  set "; 
$save_token .= "mb_id = '".$_POST['mb_id']."'";
$save_token .= ", mb_email = '".$_POST['mb_email']."'";
$save_token .= ", verify_code = '".hash("sha256", $randomStr)."'";
$save_token .= ", create_time = '".$now."'";
$save_token .= ", invaildate_time = '".$expired."'";
sql_query($save_token);

$content = ob_get_contents();
ob_end_clean();
mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $_POST['mb_email'], $subject, $content, 1);

print json_encode(array("key" => hash("sha256", $randomStr), "sql" => $save_token));



?>