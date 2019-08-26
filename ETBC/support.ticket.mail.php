<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$idx = $argv[1];
$mb_id = $argv[2];
$mb_email = $argv[3];
$lang = $argv[4];

// 메일 전송시작
$content = "Dear {이름},<br>
<br>
Thanks for contacting us!<br>
<br>
Your new support ticket has been created and someone will respond shortly.<br>
<br>
Ticket ID# {티켓번호}<br>
Date: {월일년}<br>
<br>
Please login and click on “Support Center” to manage this ticket.<br>
<br>
Sincerely,<br>
FIJI Support";
$content = preg_replace("/{이름}/", $mb_id, $content);
$content = preg_replace("/{티켓번호}/", $idx, $content);
$content = preg_replace("/{월일년}/", (new \DateTime())->format('d-m-Y'), $content);
mailer('FIJImining', 'noreply@FIJImining.net', $mb_email, 'Support Ticket Confirmation', $content, 1); // 문의자 에게 

// 언어별 관리자가 다름
$mailMap = array('kor' => 'korea@FIJImining.net', 'chn' => 'china@FIJImining.net', 'jpn' => 'japan@FIJImining.net', 'eng' => 'ticket@FIJImining.net'); 

mailer('FIJImining', 'noreply@FIJImining.net', $mailMap[$lang], 'Support Ticket Confirmation', $content, 1); // 관리자 에게 

?>
