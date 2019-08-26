<?php
include_once('../common.php');

?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8" />
	<title>FIJI MINING</title>
	
	<meta property="og:type" content="article" />
	<meta property="og:title" content="FIJI" />
	<meta property="og:url" content="" />
	<meta property="og:description" content="." />
	<meta property="og:site_name" content="" />
	<meta property="og:image" content="FIJI Mining Have" />
	<meta property="og:image:width" content="800" />
	<meta property="og:image:height" content="400" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- css연결 -->
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/adm.css">
	<link rel="stylesheet" href="css/purchase_membership.css">

	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>

	<link rel="stylesheet" href="<?php echo G5_URL ?>/theme/basic/css/default_shop.css">
	<link rel="stylesheet" href="<?php echo G5_URL ?>/mobile/skin/member/basic/style.css">

</head>
<body>
	<?include_once('mypage_head.php')?>
	<?include_once('mypage_left.php')?>

	<div id="content">
		<p class="line0">Purchase Membership</p>
		<p class="line1">In order to enjoy full features of FIJI Mining, you have to sign up for the $99 membership package by clicking the link below. </p>
		<p class="line2">When clicking the button below it will create a Bitcoin invoice for you. Which means you will have 10 minutes to pay the invoice in Bitcoin or Ethereum. You can either send Bitcoin to the one time address provided or you can scan the QR code and pay using a mobile device. <span class="red">If you do NOT purchase the membership within 3 days, your account will be permanently deleted from the system.</span></p>
		<p class="line3">FIJI Membership includes:</p>
		<p class="list"> - Digital Wallet to store and transfer Bitcoin</p>  
		<p class="list"> - Full access to our exclusive Bitcoin mining pools</p>
		<p class="list"> - Full access to our GPU Mining Pools to mine Ethereum and other altcoins.</p>
		<p class="list">  - Earn bonus through our referral program</p>
		<p class="list">  - Member exclusive promotions</p>
		<p class="list">  - Benefits from next projects</p>
		<p class="line4">We ONLY Accept Bitcoin </p>
		<p class="line5">If you don't have a Bitcoin or Ethereum wallet to pay for your membership <a href="#"><strong>click here</strong></a> for help on setting up a wallet. </p>
		<p class="line6">If you already have an existing Bitcoin or Ethereum wallet and you know how to use it then click the button below and follow the instructions to upgrade. You must be prepared to pay your invoice for $99 USD worth of Bitcoin.</p>

		<div class="container_btn">
			<a href="#" class="btn" >Purchase Membership</a>
		</div>
	</div>
	
	<?include_once('mypage_footer.php')?>
</body>
</html>
