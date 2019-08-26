<?php
include_once('./_common.php');

if($_POST[status]=='N'){
	$get_row = "Select * from pinna_btc_trans where uid  = ".$_POST[uid];
	$slamdunk = sql_query($get_row);
	$ret = sql_fetch_array($slamdunk);
	$mb_id = $ret['mb_id'];
	$amt = $ret['amt']+1;
	$sql1 = "update g5_member set lkc_coin_num = round(lkc_coin_num+".$amt.",8) where mb_id='".$mb_id."'";
	sql_query($sql1);
}

$sql = " update pinna_btc_trans set status = '".$_POST[status]."' ";
$sql .= ", update_dt = now() ";
$sql .= " where uid = '".$_POST[uid]."' ";
$obj = new stdClass();
$obj->result = sql_query($sql);
$obj->status = $_POST[status];
echo json_encode($obj);
// goto_url('./config_price.php');
//send_mail($_POST[uid], $config['cf_admin_email_name'], $config['cf_admin_email']);
function send_mail($uid, $cf_admin_email_name, $cf_admin_email){
	$i_mem = "Select * from pinna_btc_trans where uid  = ".$uid;
	$m_ret = sql_fetch($i_mem);
	$get_mem = " select mb_no, mb_id, mb_name, mb_nick, mb_email, mb_datetime from g5_member where mb_id = '".$m_ret[mb_id]."' ";
	$row  = sql_fetch($get_mem);

	$subject = 'You received '.$m_ret[amt].' Lookie';
	$content = '<p><br><img src="http://www.pinnaclemining.net/data/editor/1806/be8e2577a42b7c75e36242e02cc48598_1528857537_284.png" title="be8e2577a42b7c75e36242e02cc48598_1528857537_284.png"><br style="clear:both;"><span style="font-size: 11pt;">&nbsp;</span></p><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Hi </span><span style="font-size:13pt;font-family:Raleway;color:#ff0000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">'.$row['mb_id'].'</span><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">,</span></p><p class="MsoNormal"><b style="font-weight:normal;" id="docs-internal-guid-64b179a6-7fff-1c01-81dc-19c56011e8cf"><br></b></p><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Your request for </span><span style="font-size:13pt;font-family:Raleway;color:#ff0000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">'.$m_ret['amt'].'</span><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;"> Etherium has been paid.</span></p><p class="MsoNormal"><b style="font-weight:normal;"><br></b></p><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">For details about this transaction, log in to your account and click on “Crypto Wallets.”</span></p><p class="MsoNormal"><b style="font-weight:normal;"><br></b></p><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Thank you,</span></p><p class="MsoNormal"></p><p dir="ltr" style="line-height:1.38;margin-top:0pt;margin-bottom:0pt;"><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;">Pinnacle Support</span></p><div><span style="font-size:13pt;font-family:Raleway;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;"><br></span></div>';

	mailer($cf_admin_email_name, 'noreply@pinnaclemining.net', $row['mb_email'] , $subject, $content, 1);
}
?>
