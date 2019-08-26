<?
include_once('/home/sdevftv/html/common.php');
include_once('../lib/otphp/lib/otphp.php');
include_once('/data/sdevftv/html/lib/mailer.lib.php');

$coin_sql = "SELECT * FROM coin_cost";
$coin_rst = sql_fetch($coin_sql);
$cf_admin_email_name = $config['cf_admin_email_name'];
$from_date = date("Y-m-d",time() - (3600*24 *6));
$to_date  = date("Y-m-d",time());

//1회성.
$from_date = '2019-03-03';
$to_date  = '2019-03-07';

echo $from_date."<br>";
echo $to_date."<br>";

$today = date("Y-m-d",time() );

$sel_allownace_cond = "allowance_name in ('Direct Sponsor','Indirect Sponsor','Binary','Binary Matching','Star Share') and";
$get_cond = "select mb_id, sum(benefit) as benefit from soodang_pay where ".$sel_allownace_cond." day>='".$from_date."' and day<='".$to_date."' group by mb_id" ;
echo $get_cond;
$list = sql_query($get_cond);

while($row = sql_fetch_array($list)){
	$avatar_rate = 0;
	$member = get_member($row['mb_id'], 'avatar_rate');
	$avatar_rate = $member['avatar_rate'];
	$package_biggest = sql_fetch("
			SELECT * FROM g5_shop_item 
			WHERE it_price = (SELECT max(ct_price) 
			FROM 
				g5_shop_cart c LEFT JOIN g5_shop_order o ON c.od_id = o.od_id 
			WHERE 
				c.mb_id = '".$row['mb_id']."' AND o.od_status IN ( '입금', '강제입금') ) and it_id not like 'VVIP%' and it_name not like '%GPU%' limit 1");
	
	$exchange_rate = $coin_rst['btc_cost'];
	$allowance_name = 'Contribution'; //쉐어 보너스 차감.
	$rec = 'Contribution for share bonus';
	$rec_adm = '세어 보너스 적립';
	$save_benefit  = $row['benefit']*0.95 * (100-$avatar_rate)/100; //5%세이브 
	
	$save_benefit_avatar = $row['benefit']*0.95 * $avatar_rate/100; //5%세이브 

	$add_balance = "update g5_member set mb_balance = round( mb_balance + ".$save_benefit.",8), it_avatar_profit = round( it_avatar_profit + ".$save_benefit_avatar.",8) where mb_id='".$row['mb_id']."'";
	
	//sql_query($add_balance);
	echo "<br>";
	echo $add_balance;

	$save_shared = " insert soodang_pay set day='".$today."'";
	$save_shared .= ", mb_id 	= '".$row['mb_id']."'";
	$save_shared .= ", mb_name = '".$mbname."'";
	$save_shared .= ", allowance_name = '".$allowance_name."'";
	$save_shared .= ", benefit	 =  round(".$row['benefit']*0.05.",8)" ;
	$save_shared .= ", benefit_usd = round(".$row['benefit']* 0.05 * $exchange_rate.",8)";
	$save_shared .= ", exchange_rate =  ".$exchange_rate;
	$save_shared .= ", rec = '".$rec."'";
	$save_shared .= ", rec_adm	= '".$rec_adm."'";

	//sql_query($save_shared);
	echo "<br>";
	echo $save_shared;

	$allowance_name = 'Avatar'; //.
	$rec = 'Saved Avatar Bonus';
	$rec_adm = 'Avatar Bonus 적립';
	$save_avatar = " insert soodang_pay set day='".$today."'";
	$save_avatar .= ", mb_id 	= '".$row['mb_id']."'";
	$save_avatar .= ", mb_name = '".$mbname."'";
	$save_avatar .= ", allowance_name = '".$allowance_name."'";
	$save_avatar .= ", benefit	 =  round(".$save_benefit_avatar.",8)";
	$save_avatar .= ", benefit_usd = round(".$save_benefit_avatar * $exchange_rate.",2)";
	$save_avatar .= ", exchange_rate =  ".$exchange_rate;
	$save_avatar .= ", rec = '".$rec."'";
	$save_avatar .= ", rec_adm	= '".$rec_adm."'";
	$save_avatar .= ", avatar_rate = ".$avatar_rate;
	
	//sql_query($save_avatar);
	echo "<br>";
	echo $save_avatar;

	$get_mb = sql_fetch("select * from g5_member where mb_id='".$row['mb_id']."'");	
	if($package_biggest['it_price']<=$get_mb['it_avatar_profit']* $exchange_rate){
		//아이디를 생성해준다.
		create_avatar($get_mb);
		//pinna_avatar_purchase 구매 이력을 저장한다.
		$set_avatar_list = "insert pinna_avatar_purchase set ";
		$set_avatar_list = "mb_id					= '".$avatar_id."'";
		$set_avatar_list = ", package_name	= '".$package_biggest['it_name']."'";
		$set_avatar_list = ", spend_btc			= ".$package_biggest['it_price']/$exchage_rate;
		$set_avatar_list = ", create_date			= '".date("Y-m-d H:i:s",time())."'";
		$set_avatar_list = ", origin_mb_no		= '".$get_mb['mb_no']."'";
		$set_avatar_list = ", origin_mb_id		= '".$get_mb['mb_id']."'";
		
		echo "<br>";
		sql_query($set_avatar_list);
		echo $set_avatar_list;

		//아이디를 구매한 금액을 차감해준다.
		$purchase_btc = $package_biggest['it_price']/$exchage_rate;
		$set_avatar_balance = "update g5_member set  it_avatar_profit = round( it_avatar_profit - ".$purchase_btc.",8) where mb_id='".$row['mb_id']."'";
			
		echo "<br>";
		//sql_query($set_avatar_balance);	
		echo $set_avatar_balance;


	}
}
function create_avatar($get_mb){
	$avatar_cnt = sql_fetch("select count(*) as cnt from pinna_avatar_purchase where mb_id='".$get_mb['mb_id']."'");
	$avatar_no = $avatar_cnt['cnt']+1;
	$avatar_id = $row['mb_id'].'_'.$avatar_no;
	$mb_password    = trim($_POST['mb_password']);
	$mb_password_re = trim($_POST['mb_password_re']);
	$mb_name        = trim($_POST['mb_name']);
	$mb_nick        = trim($_POST['mb_nick']);
	$mb_email       = trim($_POST['mb_email']);
	$gp             = trim($_POST['gp']);
	$mb_name        = clean_xss_tags($mb_name);
	$mb_email       = get_email_address($mb_email);
	$mb_homepage    = clean_xss_tags($mb_homepage);
	$mb_tel         = clean_xss_tags($mb_tel);
	$last_name        = trim($_POST['last_name']);
	$first_name       = trim($_POST['first_name']);
	$mb_mprecommend       = trim($_POST['mb_mprecommend']);
	$nation_number    = trim($_POST['nation_number']);
	$Base32 = new Base32();
	$encoded = $Base32->encode(str_pad($avatar_id, 20 , "!&%"));

	$sql_otp = ", otp_key = '{$encoded}'";
	$sql_otp .= ", otp_flag = 'Y'";
	$sql = " insert into g5_member
				set mb_id = '".$avatar_id."',
					 mb_password = '".$get_mb['mb_password']."',
					 mb_name = '".$get_mb['mb_name']."',
					 mb_nick = '".$get_mb['mb_nick']."',
					 mb_nick_date = '".G5_TIME_YMD."',
					 mb_email = '".$get_mb['mb_email']."',
					 mb_hp = '".$get_mb['mb_hp']."',
					 mb_today_login = '".G5_TIME_YMDHIS."',
					 mb_datetime = '".G5_TIME_YMDHIS."',
					 mb_ip = '{$_SERVER['REMOTE_ADDR']}',
					 mb_recommend = '".$get_mb['mb_id']."',
					 mb_login_ip = '{$_SERVER['REMOTE_ADDR']}',
					 mb_mailling = '{$mb_mailling}',
					 mb_sms = '{$mb_sms}',
					 mb_open = '{$mb_open}',
					 mb_open_date = '".G5_TIME_YMD."',
					 last_name = '',
					 first_name = '',
					 nation_number = '".$get_mb['nation_number']."'
					 {$sql_otp}
					  ";
	//sql_query($sql);
	echo "<br>";
	echo $sql;

	/*$mb_id : 아바타 상위 아이디 */
	/*$avatar_id : 아바타 아이디 */
	/*$create_date : 아바타 생성날짜 */
	/*$mail_addr : 아바타 메일 주소 (상위 아이디 메일 주소와 동일) */
	sending_mail($cf_admin_email_name, $get_mb['mb_id'],$avatar_id,G5_TIME_YMD,$get_mb['mb_email']);
}
function sending_mail($cf_admin_email_name, $mb_id, $avatar_id, $create_date,$mail_addr){
	$subject = 'Your Avatar Account ';
	$content = '<p></p><p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">Hi </span><span style="font-size:13.0pt;line-height:115%;font-family:&quot;맑은 고딕&quot;; mso-bidi-font-family:&quot;맑은 고딕&quot;">유저네임</span><span lang="EN" style="font-size:13.0pt; line-height:115%;font-family:Raleway;mso-fareast-font-family:Raleway; mso-bidi-font-family:Raleway"><o:p></o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway"><o:p>&nbsp;</o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">Congratulations!!<o:p></o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">We
	are so glad to inform you that your new avatar account with following username has been successfully created.<o:p></o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%;  font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway"><o:p>&nbsp;</o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">Date of creation: 09.05.18<span style="mso-spacerun:yes">&nbsp; </span><o:p></o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">Username: TonyStark_1 <o:p></o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway"><o:p>&nbsp;</o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">Click here to place your avatar in the binary tree in order to complete the process. </span><span lang="EN" style="font-size:13.0pt;line-height:115%;font-family:Raleway;
	mso-bidi-font-family:Raleway">You will be automatically signed in. <o:p></o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway"><o:p>&nbsp;</o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">Keep it up,<o:p></o:p></span></p>
	<p class="MsoNormal"><span lang="EN" style="font-size:13.0pt;line-height:115%; font-family:Raleway;mso-fareast-font-family:Raleway;mso-bidi-font-family:Raleway">FIJI Support<o:p></o:p></span></p><br><p></p>';
	mailer($cf_admin_email_name, 'noreply@FIJImining.net', $mail_addr , $subject, $content, 1);
}
?>

