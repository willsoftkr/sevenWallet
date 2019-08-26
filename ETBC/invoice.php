<?php
include_once('./_common.php');

require_once(dirname(__DIR__) . '/blockchain/vendor/autoload.php');
$date =  date("Y-m-d H:i:s",time());
$sql = " select ct_price, ct_qty, it_id, it_name, ct_send_cost, it_sc_type from {$g5['g5_shop_cart_table']} where od_id = '$od_id' group by it_id order by ct_id ";
$result = sql_query($sql);
$total_my_btc = $member[mb_balance] + $member[it_pool1_profit] + $member[it_pool2_profit] + $member[it_pool3_profit] + $member[it_pool4_profit]+$member[it_pool5_profit];
for($i=0; $row=sql_fetch_array($result); $i++){
	$total_price = $total_price + $row['ct_price']* $row['ct_qty'];
	if($row['ct_price']!=99)
		$total_pv = $total_pv + $row['ct_price']* $row['ct_qty'];
}

$invoice_btc = sql_fetch("select mb_id, payment_btc, payment_eth, mb_id from g5_shop_order where od_id={$od_id}");
$purchase_for = $invoice_btc['mb_id'];
$btc_fee = 0.0002;
$eth_fee = 0.000441;
if($invoice_btc['payment_btc'] && $invoice_btc['payment_eth']){
	$price_coin = $invoice_btc['payment_btc'];
	$price_eth = $invoice_btc['payment_eth'];
}else {
	$coin_cost = sql_fetch("select btc_cost, eth_cost from coin_cost");
	$exchange_rate24 =  $coin_cost['btc_cost'];
	$eth_rate24 =  $coin_cost['eth_cost'];
	$price_coin = round(1/$exchange_rate24 * $total_price , 8)+$btc_fee;
	$price_eth = round(1/$eth_rate24 * $total_price , 8)+$eth_fee;
	sql_query("update g5_shop_order set payment_btc = '{$price_coin}', payment_eth = '{$price_eth}' where od_id= '{$od_id}'");
	// sql_query("update g5_shop_order set payment_btc = '{$price_coin}' where od_id= '{$od_id}'");
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
// 결제 관련 소스 인데, 코드 남기는 목적으로 false 처리함.
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
<!DOCTYPE html>
<html>
<head>
	<?include_once('common_head.php')?>

	<link rel="stylesheet" type="text/css" href="./css/invoice/style.css">

	<script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.35/dist/web3.min.js"></script>

	<script type="text/javascript">
		var minuteVal = 20;
		var secondVal = 00;
		var timeLeft = 60 * 20;
		var timerId;
		var od_id = '<?= $od_id ?>';
		var payMethod = '<?=$_GET['payMethod'] ?>';

		$(function(){
			$('.tab-panels .tabs li').on('click', function() {

				$('[class*=invoice-amounts]').hide().removeClass('active');
				if ($(this).attr('rel') === 'bitcoinPanel') {
					$('.invoice-amounts-btc').addClass('active').fadeIn(300);
				} else if ($(this).attr('rel') === 'hashmaxxPanel') {
					$('.invoice-amounts-hm').addClass('active').fadeIn(300);
				} else if ($(this).attr('rel') === 'ethereumPanel') {
					$('.invoice-amounts-eth').addClass('active').fadeIn(300);
				}

				$('.tab-panels .tabs li.active').removeClass('active')
				$(this).addClass('active')
				var panelToShow = $(this).attr('rel')
				$('.tab-panels .panel.active').hide().removeClass('active');
				$('#' + panelToShow).fadeIn(300, function() {
					$(this).addClass('active')
				})


			});

			$('#user_btc_pay').on('click', function() {
				$.ajax({
					type: "POST",
					url: "purchase_user_btc.u.php",
					cache: false,
					async: false,
					dataType: "json",
					data:  {
						mb_id : $("#mb_id").val(),
						od_id : $("#purchase_id").val()
							//회원 아이디, 금액, 주문 번호, 
					},
					success: function(data) {
						alert(data.result);
					}
				});				
			});
			
			timerId = setInterval(countdown, 1000);
			//alert('If you leave this page, the transaction will be teminated immediately. Wait until "Payment successful" message pops up,if you made any purchase. \n지금 이 페이지를 벗어나면 구매가 즉각 종료됩니다. 구매를 하는 경우 "구매가 완료되었습니다"라는 메시지가 뜰때까지 기다리십시오.  ');
            commonModal('Notice','If you leave this page, the transaction will be teminated immediately. Wait until "Payment successful" message pops up,if you made any purchase.'+'<br>'+'지금 이 페이지를 벗어나면 구매가 즉각 종료됩니다. 구매를 하는 경우 "구매가 완료되었습니다"라는 메시지가 뜰때까지 기다리십시오.',200);

			if(payMethod == 'walletPayment'){
				$('.tab-panels .tabs li[rel=hashmaxxPanel]').trigger('click');
			}else if(payMethod == 'btcPayment'){
				$('.tab-panels .tabs li[rel=bitcoinPanel]').trigger('click');
			}else if(payMethod == 'ethPayment'){
				$('.tab-panels .tabs li[rel=ethereumPanel]').trigger('click');
			}
			 
			var web3 = new Web3(new Web3.providers.HttpProvider("https://mainnet.infura.io/"));
			// 이더리움 지갑 생성.
			var acc = web3.eth.accounts.create();

			$.ajax({
				type: "POST",
				url: "invoice.eth.php",
				data: {
					"address" : acc.address,
					"privateKey" : acc.privateKey
				},
				success: function(data){
					$('#ethQrCode').empty();
					new QRCode(document.getElementById("ethQrCode"), {
						text: "ethereum:" + data.eth_addr + "?amount=<?=round($price_coin,8)?>",
						width: 125,
						height: 125,
						colorDark : "#000000",
						colorLight : "#ffffff",
						correctLevel : QRCode.CorrectLevel.H
					});
					$('#ethAddr').html(data.eth_addr);
				},
				dataType: "json"
			});

			new QRCode(document.getElementById("qrImg"), {
				text: "bitcoin:<?=$address?>?amount=<?=round($price_coin,8)?>",
				width: 125,
				height: 125,
				colorDark : "#000000",
				colorLight : "#ffffff",
				correctLevel : QRCode.CorrectLevel.H
			});
		});

		function countdown() {
			if (timeLeft == -1) {
				clearTimeout(timerId);
				// doSomething();
				commonModal('Expired','Payment time expired');
			} else {

					var count = Math.floor(timeLeft/60);
					var count2 = timeLeft%60;

					$(".remain_time").html(count);
					$(".remain_time2").html(count2);
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
</head>
<body>
	<?include_once('mypage_head.php')?>
	<input  type="hidden" id="purchase_id" value="<?echo $od_id?>" disabled />
	<input  type="hidden" id="mb_id" value="<?echo $member[mb_id]?>" disabled />
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
	<div class="invoice">
		<div class="header">
			<div class="invoice-number">
				INVOICE NO.<br>
				<span ><?echo $od_id?></span>
			</div>
		</div>
		<hr>

		<div class="invoice-amounts-hm active">
			<div class="section border-right">
				<span class="gray">Invoice Total</span>
				<h3><strong><?echo $price_coin?> BTC </strong></h3>
			</div>
			<div class="section">
				<span>This is a purchase for</span>
				<h3 class="blue"><strong><?echo $purchase_for?></strong></h3>
			</div>
		</div>

		<div class="invoice-amounts-btc">
			<div class="section border-right">
				<span class="gray">Invoice Total</span>
				<h3><strong><?echo $price_coin?>  BTC</strong></h3>
			</div>
			<div class="section border-right">
				<span>This is a purchase for</span>
				<h3 class="blue"><strong><?echo $purchase_for?></strong></h3>
			</div>
			<div class="section">
				<span>Rate expires in...</span>
				<h2 class="blue underline"><strong class="remain_time">20</strong>:<strong class="remain_time2">20 </strong></h2>
			</div>
		</div>

		<div class="invoice-amounts-eth">
			<div class="section border-right">
				<span class="gray">Amount to Pay</span>
				<h3><strong><?=$price_eth?> ETH</strong></h3>
			</div>
			<div class="section border-right">
				<span>This is a purchase for</span>
				<h3 class="blue"><strong><?echo $purchase_for?></strong></h3>
			</div>
			<div class="section">
				<span>Rate expires in...</span>
				<h2 class="blue underline"><strong class="remain_time">20</strong>:<strong class="remain_time2">00</strong></h2>
			</div>
		</div>

		<hr>

		<div class="bottom-container">
			<div class="payment-information">
			<div class="tab-panels">
				<ul class="tabs">
					<li class="active" rel="hashmaxxPanel"><img src="./images/logo.png"  style="width:14px;" > Pay from Wallet</li>
					<li rel="bitcoinPanel"><img src="./images/btc_logo.png" style="width:20px;"  > Pay with Bitcoin</li>
					<li rel="ethereumPanel"><img src="./images/eth_logo.png" style="width:12px;" > Pay with Ethereum</li>
				</ul>
				<div id="hashmaxxPanel" class="panel active">
					<div class="payment-instructions">
						<span class="gray">Please pay the following amount</span>
						<br>
						<span class="big"><strong><?echo $price_coin?> BTC</strong></span>
						<br>
						<br>
					</div>
					<div class="total">
						<div class="total-box">
							<span class="gray">Invoice Total</span>
							<span class="total-right"><?echo $price_coin?> BTC</span>
						</div>
						<div class="total-box">
							<span><strong>Total Balance</strong></span>
							<span class="total-right"><strong><?echo $total_my_btc?> BTC</strong></span>
						</div>
					</div>
					<button id="user_btc_pay">Pay Invoice</button>
				</div>
				<div id="bitcoinPanel" class="panel">
					<h5>BTC Payment Information</h5>
					<div class="qr" id="qrImg" >
						<!-- <img id="qrImg" src="https://api.qrserver.com/v1/create-qr-code/?size=125x125&data=bitcoin:<?=$address?>?amount=<?=round($price_coin,8)?>"> -->
						
					</div>
					<div class="payment-instructions">
						<span class="gray">Please pay the following amount</span>
						<br>
						<span class="big"><strong><?echo $price_coin?>  BTC</strong></span>
						<br>
						<br>
						<span class="gray">Send to Bitcoin address</span>
						<br>
						<span class="big blue address"><strong><?=$address?></strong></span>
					</div>
					<div class="total">
						<div class="total-box">
							<span class="gray">Invoice Total</span>
							<span class="total-right"><?echo $price_coin?> BTC</span>
						</div>
						<div class="total-box">
							<span><strong>Total Balance</strong></span>
							<span class="total-right"><strong><?echo $total_my_btc?> BTC</strong></span>
						</div>
					</div>
				</div>
				<div id="ethereumPanel" class="panel">
					<h5>ETH Payment Information</h5>
					<div class="qr" id="ethQrCode">
						<!-- <img id="ethQrCode" src="" style="width:125px;"> -->
					</div>
					<div class="payment-instructions">
						<span class="gray">Please pay the following amount</span>
						<br>
						<span class="big"><strong><?=$price_eth?> ETH</strong></span>
						<br>
						<br>
						<span class="gray">Send to Ethereum address</span>
						<br>
						<span class="big blue address"><strong id="ethAddr"></strong></span>
					</div>
					<div class="total">
						<div class="total-box">
							<span class="gray">Invoice Total</span>
							<span class="total-right"><?=$price_eth?> ETH</span>
						</div>
						<div class="total-box">
							<span><strong>Total Balance</strong></span>
							<span class="total-right"><strong> ETH</strong></span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?
			$sql_cart = "select ct_id, mb_id, ct_price, ct_qty, it_id, it_name, ct_send_cost, it_sc_type from {$g5['g5_shop_cart_table']} where od_id	= '$od_id' group by it_id order by ct_id ";
			$rst = sql_query($sql_cart);

		?>
		<div class="member-information">
			<div class="member">
				<h3 id="mb_id"><?echo $member[mb_id] ?></h3>
				<span class="gray"><?echo $member[mb_email]?></span>			
			</div>
			<div class="packages table-responsive">
				<table class="table">
				  <thead>
					<tr>
					  <th scope="col">Product</th>
					  <th scope="col">Quantity</th>
					  <th scope="col">Price</th>
					</tr>
				  </thead>
				  <tbody>
					<?for($i; $row= sql_fetch_array($rst); $i++){?>
					<tr>
						<th><?echo $row[it_name];?></th>
						<td><?echo $row[ct_qty]; ?></td>
						<td><?echo $row[ct_price]; ?></td>
					</tr>
					<?}?>

				  </tbody>
				</table>
			</div>
			<div class="thanks">
				<span class="gray">Thank you</span>
			</div>
		</div>	
	</div>
	</div>
	</div>
<!-- <script type="text/javascript" src="./js/invoice/script.js"></script> -->
</body>
</html>