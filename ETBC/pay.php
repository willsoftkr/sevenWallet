<?php
include_once('../common.php');  

require_once(dirname(__DIR__) . '/blockchain/vendor/autoload.php');
$date =  date("Y-m-d H:i:s",time());
$sql = " select ct_price, ct_qty, it_id, it_name, ct_send_cost, it_sc_type from {$g5['g5_shop_cart_table']} where od_id = '$od_id' group by it_id order by ct_id ";
$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++){
	$total_price = $total_price + $row['ct_price']* $row['ct_qty'];
	if($row['ct_price']!=99)
		$total_pv = $total_pv + $row['ct_price']* $row['ct_qty'];
}

$ginbtc = "select payment_btc, mb_id from g5_shop_order where od_id=$od_id;";
$inret = sql_query($ginbtc);
$invoice_btc = sql_fetch_array($inret);

if($invoice_btc['payment_btc']){
	$price_coin = $invoice_btc['payment_btc'];
}
else {
	$sql_price = "select btc_cost from coin_cost";
	$result = sql_query($sql_price);
	$ret = sql_fetch_array($result);
	$exchange_rate24 =  $ret['btc_cost'];
	$price_coin = 1/$exchange_rate24 *$total_price;
	$price_coin = round($price_coin, 8);
	$sql_payment = "update g5_shop_order set payment_btc = '$price_coin' where od_id= '$od_id'";
	sql_query($sql_payment);
}

$ord_id = $invoice_btc['mb_id'];
$up_pord = "update g5_shop_order set od_status='취소' where od_status='주문' and mb_id='{$ord_id}' and od_id <> '$od_id'";
sql_query($up_pord);


if(!$member['mb_wallet']){
	$api_code = 'd25934ac-4ce4-4942-bf52-293f54c44315';
	if(file_exists('code.txt')) {
		$api_code = trim(file_get_contents('code.txt'));
	}
	$Blockchain = new \Blockchain\Blockchain($api_code);
	$Blockchain->setServiceUrl('http://localhost:3000');
	$wallet = $Blockchain->Create->create('0803bjuung'); //운영자님이 패스워드를 변경 하시기 바랍니다. //Please change the password to create wallet.
	$address = $wallet->{'address'};
	$guid = $wallet->{'guid'};
    $sql = "update g5_member set  mb_wallet = '{$address}', my_walletId='{$guid}' where mb_id='{$member['mb_id']}'";	
	$ret = sql_query($sql);
	$sqlwallet = "insert into mb_wallet set mb_id='{$member['mb_id']}', type=0, wlt_ad='$address', wlt_key='$guid', wlt_date='$date';";
	sql_query($sqlwallet);

}
else{
	$sql = "select mb_wallet, my_walletId from g5_member where mb_id ='{$member['mb_id']}'";
	$ret = sql_fetch($sql);
	$address  = $ret['mb_wallet'];
	$key = $ret['my_walletId'];
	$date =  date("Y-m-d H:i:s",time());
	$sql = "INSERT INTO mb_wallet SET  mb_id='{$member['mb_id']}', type=0, wlt_ad='$address', wlt_key='$key', wlt_date='$date'";
	sql_query($sql);


}	
//$action = $_POST["action"];
//if($action=='chkbalance'){
if(false){
	$now_date = date("Y-m-d H:i:s",time()); 
	$ch = curl_init();
	$wid = $member['my_walletId'];
	curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/$wid/balance?password=0803bjuung");
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	//접속할 URL 주소
	$result = curl_exec ($ch);
	curl_close ($ch);
	$obj = json_decode($result);
//	$balance =  $obj->{'balance'}; 	
//	$price_coin = $_POST["price_coin"];

	if($price_coin==0){
		goto_url(G5_SHOP_URL.'/cart.php');
	}

	if($balance/100000000 >= $price_coin && $price_coin!=0 && $balance!=0){//> $price_coin-0.01){
		$ordId = $_POST['od_id'];		
		$send_coin = $price_coin*100000000-10000;
		$send_coin = floor($send_coin);
		$ch = curl_init();
		$wid = $member['my_walletId'];
		$from_addr = $member['mb_wallet'];
//		$to_addr = "1PiZiBbuWNPRY1V9Qep3qtNqXszH59rd2v";
//		$to_addr = "1Dsci6Zw7KwVHnKcrPKxXtnKVGa1KR28xH";
		$to_addr = "1Logat13CYHY8g5wUiuBeBmmfvUobJ5rox";
		curl_setopt($ch,CURLOPT_URL, "http://localhost:3000/merchant/{$wid}/payment?password=0803bjuung&second_password=&to=$to_addr&amount=$send_coin&from=$from_addr");
		//echo "http://localhost:3000/merchant/{$wid}/payment?password=0803bjuung&second_password=&to=$to_addr&amount=$send_coin&from=$from_addr";
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec ($ch);		
		curl_close ($ch);
		$obj = json_decode($result);
		$message =  "message : ".$obj->{'message'}; 


		if(true){
			$get_brcom = "select mb_brecommend from g5_member where mb_id='".$member['mb_id']."'";
			$ret = sql_query($get_brcom);
			$row = sql_fetch($ret);
			if($row['mb_brecommend']){
				$sql_purchase = "update g5_shop_order set od_receipt_time='$now_date', od_status='입금', od_settle_case='매장카드',  pv = truncate(od_cart_price,-2)  where od_id = '$ordId'; ";
			}
			else{
				$sql_purchase = "update g5_shop_order set od_status='입금', od_settle_case='매장카드',  pv = truncate(od_cart_price,-2)  where od_id = '$ordId'; ";
			}
			sql_query($sql_purchase);	
			$sql_cart = "select ct_id, mb_id, ct_price, ct_qty, it_id, it_name, ct_send_cost, it_sc_type from {$g5['g5_shop_cart_table']} where od_id	= '$ordId' group by it_id order by ct_id ";
			$rst = sql_query($sql_cart);
			//echo $sql_cart;
			for($i=0; $rst_rw=sql_fetch_array($rst); $i++){
			$ct_id = $rst_rw['ct_id'];
			if($rst_rw['it_id'] == '1527096053'){//멤버쉽 구매
				$id = $rst_rw['mb_id'];  
				$qty = $rst_rw['ct_qty'];
				$sql_poolist_up = "update g5_member set membership_yn='Y', mb_level=1 where mb_id='$id';";
				sql_query($sql_poolist_up);
				$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
				sql_query($sql_chgst);
			}
			else if($rst_rw['it_id'] == '1527096045'){//pool1 구매
				$id = $rst_rw['mb_id'];  
				$qty = $rst_rw['ct_qty'];

				$sql_poolist_up = "update g5_member set it_pool1=it_pool1+$qty where mb_id='$id';";
				sql_query($sql_poolist_up);

				$sql_poolist_up2 = "update g5_member set mb_level=2 where mb_level<2 and mb_id='$id';";
				sql_query($sql_poolist_up2);

				$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
				sql_query($sql_chgst);
			}
			else if($rst_rw['it_id'] == '1527096041'){//pool2구매
				$id = $rst_rw['mb_id'];  
				$qty = $rst_rw['ct_qty'];
				$sql_poolist_up = "update g5_member set it_pool2=it_pool2+$qty where mb_id='$id';";
				sql_query($sql_poolist_up);
				$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
				sql_query($sql_chgst);
			}
			else if($rst_rw['it_id'] == '1527096037'){//pool3 구매 
				$id = $rst_rw['mb_id'];  
				$qty = $rst_rw['ct_qty'];
				$sql_poolist_up = "update g5_member set it_pool3=it_pool3+$qty where mb_id='$id';";
				sql_query($sql_poolist_up);
				$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
				sql_query($sql_chgst);
			}
			else if($rst_rw['it_id'] == '1527096030'){//pool4 구매
				$id = $rst_rw['mb_id'];  
				$qty = $rst_rw['ct_qty'];
				$sql_poolist_up = "update g5_member set it_pool4=it_pool4+$qty where mb_id='$id';";
				sql_query($sql_poolist_up);
				$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
				sql_query($sql_chgst);
			}
			else if($rst_rw['it_id'] == '1515148167'){//GPU
				$id = $rst_rw['mb_id'];  
				$qty = $rst_rw['ct_qty'];
				$sql_poolist_up = "update g5_member set it_GPU=it_GPU+$qty where mb_id='$id';";
				sql_query($sql_poolist_up);
				$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
				sql_query($sql_chgst);
			}
		}
			goto_url('/new/dashboard.php');
			///echo "ok".$message;
		}
		else{
			alert("Payment is not progress.....".$message);
			//echo $message;
		}

	}
	else{
		alert("balance is not enight");
	}
}
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
	<link rel="stylesheet" href="css/pay.css">

	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>

	<link rel="stylesheet" href="<?php echo G5_URL ?>/theme/basic/css/default_shop.css">
	<link rel="stylesheet" href="<?php echo G5_URL ?>/mobile/skin/member/basic/style.css">

	<script src="js/circles.js"></script>

	<script type="text/javascript">
		var minuteVal = 30;
		var secondVal = 00;
		var myCircle9;
		var myCircle10;
		var timeLeft = 60 * 30;
		var timerId;
			var od_id = '<?= $od_id ?>';

		$(function(){

			myCircle9 = Circles.create({
				id:         'circles-9',
				radius:     60,
				value:      minuteVal,
				maxValue:   30,
				width:      10,
				text:       function(value){return value;},
				colors:     ['#eee', '#56c9e9'],
				duration:   500,
				wrpClass:   'circles-wrp',
				textClass:  'circles-text'
			});
			myCircle10 = Circles.create({
				id:         'circles-10',
				radius:     60,
				value:      secondVal,
				maxValue:   60,
				width:      10,
				text:       function(value){return value;},
				colors:     ['#eee', '#ac60d0'],
				duration:   500,
				wrpClass:   'circles-wrp',
				textClass:  'circles-text'
			});
			
			timerId = setInterval(countdown, 1000);
			alert('If you leave this page, the transaction will be teminated immediately. Wait until "Payment successful" message pops up,if you made any purchase. \n지금 이 페이지를 벗어나면 구매가 즉각 종료됩니다. 구매를 하는 경우 "구매가 완료되었습니다"라는 메시지가 뜰때까지 기다리십시오.  ');
		});

		function countdown() {
			if (timeLeft == -1) {
				clearTimeout(timerId);
				doSomething();
			} else {
				myCircle9.update(Math.floor(timeLeft/60),0);
				myCircle10.update(timeLeft%60,0);
				timeLeft--;
			}
			if(timeLeft%10 == 0){
	
				$.ajax({
					url				: './check_order.php',
					data			: {
						ord_id		: od_id
					},
					type			: 'POST',
					dataType		: 'json',
					success		: function(result) {
						if(result.success == false) {
							alert(result.msg);
							return;
						}
						else if(result.data == "입금"){
							alert("Payment Successful. You will be proceeded to dashboard.");
							document.location.href="./dashboard.php";
						}
					}
				});
			}
		}
	</script>
	<link rel="stylesheet" href="css/orderhistory/style.css">
		<?include_once('common_head.php')?>
		
</head>
<body>


	<form action ="./pay.php" method="POST">
		<input type="hidden" name="price_coin" value=<?=$price_coin?>>
		<input type="hidden" name="od_id" value="<?=$od_id?>">
		<input type="hidden" name="action" value="chkbalance">
		<?include_once('mypage_head.php')?>
		<div id="content">
		
		<p class="line1">Do NOT close this page before Payment Received confirmation</p>
		<div class="block0">
			<p>Select the method of the payment</p>
			<a href="#" class="bit"><img src='img/bitcoin.png' /></a>
			<!--a href="#" class="ether"><img src='img/ethereum.png' /></a-->
		</div>
		<div style="width:300px;margin:0 auto;">
			<div id="circles-9" style="float:left;margin-right: 50px;">
			</div>
			<div id="circles-10" style="float:left;">
			</div>
		</div>
		<div class="key">
			<p><strong>Invoice for Username: <span><?=$member['mb_name']?></span></strong></p>
			<p><a href="#">Pay with Bitcoin</a></p>
			<p>Total amount to pay</p>
			<p><strong><?=round($price_coin,8)?></strong> BTC</p>
			<p>Send Bitcoin to</p>
			<p><strong> <?=$address?> </strong></p>
		</div>
		<div class="qr">
			<p>Or scan this QR code to send Bitcoin</p>
			<img id="qrImg" src="https://chart.googleapis.com/chart?chs=200x200&chld=L|2&cht=qr&chl=bitcoin:<?=$address?>?amount=<?=round($price_coin,8)?>">
		</div>
	</div>
	</form>
	<?//include_once('mypage_footer.php')?>
</body>
</html>
