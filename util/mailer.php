<?php
include_once('./_common.php');

$to = "soo@willsoft.kr";
$subject = "V7 테스트 메일 발송";
$contents = "PHP mail()함수를 이용한 메일 발송 테스트";
$headers = "From: test@v7wallet.com\r\n";


mail($to, $subject, $contents, $headers);
?>