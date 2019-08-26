<?php 
include_once('/home/sdevftv/html/common.php');
include_once('/home/sdevftv/html/lib/mailer.lib.php');

//member table에서 1스타 진급한 인원을 검색.
$today = date("Y-m-d",time() - 3600*24); 
$get_1star = "select mb_id, mb_recommend, mb_name, rank_day,  mb_email  from g5_member where mb_level=3 and rank_day ='".$today."'";

$ret = sql_query($get_1star);
for($i=0; $list = sql_fetch_array($ret); $i++){
	send_rank_mail($list[mb_id], $list[mb_email], 3, $config['cf_admin_email_name'], $config['cf_admin_email']);

	$direct_rec = "select mb_email from g5_member where mb_id='".$list[mb_recommend]."'";
	$drec_ret = sql_fetch($direct_rec);
	//직추천 자의 메일 발송
	send_rank_directrmail($list[mb_id], $drec_ret[mb_email], 3, $config['cf_admin_email_name'], $config['cf_admin_email'],$list[mb_recommend]);

	//간접 추천 10대 메일 발송
	$j = 1; $rec_id = $list[mb_recommend];
	while($j <11 &&  $rec_id!='Coolrunning'){
		$indirect_rec = "select mb_recommend from g5_member where mb_id='".$rec_id."' ";
		$indirect_ret = sql_fetch($indirect_rec);
		$rec_id = $indirect_ret[mb_recommend];

		$get_indirect_rec_mailaddr = "select mb_email from g5_member where mb_id='".$indirect_ret[mb_recommend]."'";
		$get_rec_mail = sql_fetch($get_indirect_rec_mailaddr);
		
		send_rank_indirect_rec_mail($list[mb_id], $get_rec_mail[mb_email], 3, $config['cf_admin_email_name'], $config['cf_admin_email'],$rec_id ,$j);
	}

}
//rank table에서 2스타 이상 진급한 인원을 검색.
$get_high_star = "SELECT r.mb_id, m.mb_email, m.mb_recommend, r.rank FROM rank r left join g5_member as m on r.mb_id = m.mb_id WHERE r.rank_day  ='".$today."'";

$ret = sql_query($get_high_star);
for($i=0; $list = sql_fetch_array($ret); $i++){
	send_rank_mail($list[mb_id], $list[mb_email], $list[rank], $config['cf_admin_email_name'], $config['cf_admin_email']);

	$direct_rec = "select mb_email from g5_member where mb_id='".$list[mb_recommend]."'";
	$drec_ret = sql_fetch($direct_rec);
	//직추천 자의 메일 발송
	send_rank_directrmail($list[mb_id], $drec_ret[mb_email], $list[rank], $config['cf_admin_email_name'], $config['cf_admin_email'],$list[mb_recommend]);

	//간접 추천 10대 메일 발송
	$j = 1; $rec_id = $list[mb_recommend];
	while($j <11 &&  $rec_id!='Coolrunning'){
		$indirect_rec = "select mb_recommend from g5_member where mb_id='".$rec_id."' ";
		$indirect_ret = sql_fetch($indirect_rec);
		$rec_id = $indirect_ret[mb_recommend];

		$get_indirect_rec_mailaddr = "select mb_email from g5_member where mb_id='".$indirect_ret[mb_recommend]."'";
		$get_rec_mail = sql_fetch($get_indirect_rec_mailaddr);
		
		send_rank_indirect_rec_mail($list[mb_id], $get_rec_mail[mb_email], $list[rank], $config['cf_admin_email_name'], $config['cf_admin_email'],$rec_id ,$j);
	}

}

function send_rank_mail($mb_id,  $mail_addr, $level, $cf_admin_email_name, $cf_admin_email){
	$star = $level-2;
	$subject = 'Rank Promotion';
	$content = '<b id="docs-internal-guid-2146752f-7fff-d53b-0768-e993ece1da20" style="font-weight: normal;"><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Congratulations </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$mb_id.'</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">!</span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">You’ve been promoted to </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$star.'</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> Star. </span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Keep up your earnings and referrals to level up more and earn even greater bonus rewards.</span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Best,</span></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">FIJI Support </span></p></b><br class="Apple-interchange-newline">';
	mailer($cf_admin_email_name, 'noreply@FIJImining.net', $mail_addr , $subject, $content, 1);

}
function send_rank_directrmail($mb_id,  $mail_addr, $level, $cf_admin_email_name, $cf_admin_email, $rec){
	$star = $level-2;
	$subject = 'Referral Rank Promotion';
	$content = '<b id="docs-internal-guid-a0300bab-7fff-bd5a-a185-32ef0256673c" style="font-weight: normal;"><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Congratulations </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$rec.'</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">!</span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Your referred member </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 700; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$mb_id.'</span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> </span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">has been promoted to </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$star.'</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> Star.</span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Best,</span></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">FIJI Mining</span></p></b><br class="Apple-interchange-newline">';
	mailer($cf_admin_email_name, 'noreply@FIJImining.net', $mail_addr , $subject, $content, 1);

}


function send_rank_indirect_rec_mail($mb_id, $mail_addr, $level, $cf_admin_email_name, $cf_admin_email, $rec, $gap){
	$star = $level - 2;
	$gap = $gap+1;
	$subject = 'Downline Rank Promotion';
	$content = '<b id="docs-internal-guid-c6481c27-7fff-fe49-ebd6-3774604ac151" style="font-weight: normal;"><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Congratulations </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$rec.'</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">!</span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 700; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$mb_id.'</span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> </span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">(level </span><span style="color: rgb(255, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">'.$gap.'</span><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;"> downline) has been promoted to '.$star.' Star. </span></p><br><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">Best,</span></p><p style="line-height: 1.38; margin-top: 0pt; margin-bottom: 0pt;" dir="ltr"><span style="color: rgb(0, 0, 0); font-family: Raleway; font-size: 13pt; font-style: normal; font-variant: normal; font-weight: 400; text-decoration: none; vertical-align: baseline; white-space: pre-wrap; background-color: transparent;">FIJI Mining</span></p></b><br class="Apple-interchange-newline">';
	mailer($cf_admin_email_name, 'noreply@FIJImining.net', $mail_addr , $subject, $content, 1);

}
?>