
<?
#!/usr/local/bin/php -q

include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');
$mb_id =$argv[1];
$sql = "select * from g5_member where mb_id='".$mb_id."'";
$mb = sql_fetch($sql);
//send_mail($mb_id, $mb['mb_mail'], $package_list, $config['cf_admin_email_name'], $config['cf_admin_email']);

function send_mail($mb_id, $mail_addr, $package_list, $cf_admin_email_name, $cf_admin_email){

	$subject = 'Hash Power Purchase Confirmation';
	$content = '<p></p><span id="docs-internal-guid-98b9b3f3-7fff-18e3-11bd-3b7e75503b0f"><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">Congratulations </span><span style="font-size: 13pt; font-family: Raleway; color: rgb(255, 0, 0); font-weight: 700; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">'.$mb_id.'</span><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">!</span></p><br><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">You’ve successfully purchased Mining Package </span><span style="font-size: 13pt; font-family: Raleway; color: rgb(255, 0, 0); font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">'.$package_list.'</span><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">.</span></p><br><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">For details about this transaction, log in to your account and click on “Order History.”</span></p><br><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">Thank you,</span></p><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;">FIJI Support </span></p><div><span style="font-size: 13pt; font-family: Raleway; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;"><br></span></div></span>';
	mailer($cf_admin_email_name, $cf_admin_email, $mail_addr , $subject, $content, 1);

}

?>