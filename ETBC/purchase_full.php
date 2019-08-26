<?php
include_once('./_common.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./css/purchase_full/style.css">

	<title>FIJI MINING | PURCHASE FULL HASH</title>
</head>
<body>

<?php include_once('./mypage_head.php');?>
	<div class="main-container">		
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray">Purchase Full Hash</h2>
			<div class="purchase-full-hash-container shadow">
				<table class="table purchase-full-hash-table">
					<thead>
						<tr>
							<th>Package</th>
							<th>Quantity</th>
							<th>Price / Package</th>
							<th>Reset</th>
						</tr>						
					</thead>

					<tr>
						<td>
							<div class="btc-logo-container membership-container">
								<img src="./images/logo.png" class="membership-img" alt="pinnacle">							
							</div>
							<div class="package-description membership-desc">
								<span class="product-name">Membership</span><br>
								<span class="gray">Lifetime</span>
							</div>
						</td>
						<td>
							<input id="membership" type="number" placeholder="0" min="0" name="quantity">
						</td>
						<td>
							<span class="product-name price">$99</span>
						</td>
						<td>
							<i id="membershipReset" class="fas fa-times"></i>
						</td>
					</tr>

					<tr>
						<td>
							<div class="btc-logo-container">
								<img src="./images/btc_logo.png" class="purchase-bitcoin-icon" alt="btc icon" />								
							</div>
							<div class="package-description">
								<span class="product-name">Bitcoin Mining Package 1</span><br>
								<span class="gray">4,500 GH/s hash power</span>
							</div>
						</td>
						<td>
							<input id="packageOne" type="number" placeholder="0" min="0" name="quantity">
						</td>
						<td>
							<span class="product-name price">$1,000</span>
						</td>
						<td>
							<i id="packageOneReset" class="fas fa-times"></i>
						</td>
					</tr>

					<tr>
						<td>
							<div class="btc-logo-container">
								<img src="./images/btc_logo.png" class="purchase-bitcoin-icon" alt="btc icon" />								
							</div>
							<div class="package-description">
								<span class="product-name">Bitcoin Mining Package 2</span><br>
								<span class="gray">13,500 GH/s hash power</span>
							</div>
						</td>
						<td>
							<input id="packageTwo" type="number" placeholder="0" min="0" name="quantity">
						</td>
						<td>
							<span class="product-name price">$3,000</span>
						</td>
						<td>
							<i id="packageTwoReset" class="fas fa-times"></i>
						</td>
					</tr>

					<tr>
						<td>
							<div class="btc-logo-container">
								<img src="./images/btc_logo.png" class="purchase-bitcoin-icon-big-package" alt="btc icon" />								
							</div>
							<div class="package-description">
								<span class="product-name">Bitcoin Mining Package 3</span><br>
								<span class="gray">22,500 GH/s hash power</span>
							</div>
						</td>
						<td>
							<input id="packageThree" type="number" placeholder="0" min="0" name="quantity">
						</td>
						<td>
							<span class="product-name price">$5,000</span>
						</td>
						<td>
							<i id="packageThreeReset" class="fas fa-times"></i>
						</td>
					</tr>

					<tr>
						<td>
							<div class="btc-logo-container">
								<img src="./images/btc_logo.png" alt="btc icon" />								
							</div>
							<div class="package-description">
								<span class="product-name">Bitcoin Mining Package 4</span><br>
								<span class="gray">54,000 GH/s hash power</span>
							</div>
						</td>
						<td>
							<input id="packageFour" type="number" placeholder="0" min="0" name="quantity">
						</td>
						<td>
							<span class="product-name price">$12,000</span>
						</td>
						<td>
							<i id="packageFourReset" class="fas fa-times"></i>
						</td>
					</tr>

					<tr>
						<td>
							<div class="btc-logo-container">
								<img src="./images/btc_logo.png" alt="btc icon" />								
							</div>
							<div class="package-description">
								<span class="product-name">Bitcoin Mining Package 5</span><br>
								<span class="gray">112,500 GH/s hash power</span>
							</div>
						</td>
						<td>
							<input id="packageFive" type="number" placeholder="0" min="0" name="quantity">
						</td>
						<td>
							<span class="product-name price">$25,000</span>
						</td>
						<td>
							<i id="packageFiveReset" class="fas fa-times"></i>
						</td>
					</tr>

					<tr>
						<td>
							<div class="eth-logo-container">
								<img src="./images/eth_logo.png" class="eth-logo" alt="eth icon" />								
							</div>
							<div class="package-description eth-pack-des">
								<span class="product-name">Ethereum Mining (GPU)</span><br>
								<span class="gray">80 MH/s hash power</span>
							</div>
						</td>
						<td>
							<input id="packageGPU" type="number" placeholder="0" min="0" name="quantity">
						</td>
						<td>
							<span class="product-name price">$3,000</span>
						</td>
						<td>
							<i id="packageGPUReset" class="fas fa-times"></i>
						</td>
					</tr>
				</table>

				<div class="partial-container">
					<h4 class="blue">Add Hashrate (VVIP Package)</h4>
					<h5>GH/s</h5>
					<div class="hash-numbers">
						<span class="hash0 gray">0</span>
						<span class="hash1 gray">112,500</span>
						<span class="hash2 gray">225,000</span>
						<span class="hash3 gray">337,500</span>
						<span class="hash4 gray">450,000</span>
					</div>
					<input type="range" min="0" max="450000" value="0" step="112500" class="slider" id="hash-range">
					<div class="partial-price">
						<span class="price0 gray">0</span>
						<span class="price1 gray">$25,000</span>
						<span class="price2 gray">$50,000</span>
						<span class="price3 gray">$75,000</span>
						<span class="price4 gray">$100,000</span>
					</div>

					<div class="hash-stats">
						<div class="stat-container">
							<img src="./images/repurchase_rate.png" alt="repurchase rate" /><br>
							<span class="blue stat-container-big" id="variable-repurchase-rate">20%</span>
							<br>
							<span class="gray">Repurchase Rate</span>
						</div>

						<div class="stat-container">
							<img src="./images/new_total_hash_rate.png" alt="total hash rate" /><br>
							<span class="blue stat-container-big">
							<span id="selectedAmount">0</span> GH/s</span><br>
							<span class="gray">Custom Hash Rate</span>
						</div>

						<div class="stat-container">
							<img src="./images/mining_contract.png" alt="mining contract" /><br>
							<span class="blue stat-container-big">1,000 Days</span><br>
							<span class="gray">Mining Contract</span>
						</div>
						
					</div>
				</div>

				<h5 class="search-result red">MEMBER NOT FOUND</h5>
				<div class="input-group mb-3 member-search">
				  <input type="text" class="form-control" placeholder="Recipient's Username" aria-label="Username" aria-describedby="basic-addon1">
				  <button class="btn btn-outline-primary" type="button">Search</button>
				</div>

				<div class="total-hash-container">
					<div class="hash-power-calc-container">
						<div class="total-hash-power-container">
							<div class="hash-desc">
								BTC Total Hash Power:
							</div>
							<div class="hash-amount">
								<span class="hash-number"><span id="btcSelectedHash">0</span> GH/s</span>
							</div>
						</div>
					</div>

					<div class="hash-power-calc-container">
						<div class="total-hash-power-container">
							<div class="hash-desc">
								ETH Total Hash Power:
							</div>
							<div class="hash-amount">
								<span class="hash-number"><span id="ethSelectedHash">0</span> MH/s</span>
							</div>
						</div>
					</div>
				</div>

				

				<div class="checkout-container">
					<div class="checkout-left">
						<h4>Select Method of Payment</h4>
						<div id="walletPayment" class="payment-method">
							<img src="./images/logo.png" alt="pinnacle">
							BALANCE
						</div>
						<div id="btcPayment" class="payment-method">
							<img src="./images/btc_logo.png" alt="bitcoin">
							BITCOIN
						</div>
						<div id="ethPayment" class="payment-method">
							<img src="./images/eth_logo.png" alt="ethereum">
							ETHEREUM
						</div>
					</div>

					<div class="checkout-right">
						<span class="gray">Subtotal: </span> <span class="subtotal">$<span id="calcSubtotal">0</span></span><br>
						<button type="button" class="btn btn-primary checkout-button">Generate Invoice</button>
						<br>

						<span class="gray exchange-rate"><span class="usd-exchange">USD : <span id="usdBtc">0</span></span> <i class="fas fa-exchange-alt"></i> <span class="btc-exchange">BTC : 0.1234567</span></span><br>
						<span class="gray exchange-rate"><span class="usd-exchange">USD : <span id="usdEth"></span></span> <i class="fas fa-exchange-alt"></i> <span class="btc-exchange">ETH : 0.1234567</span></span>
					</div>
					
				</div>

			</div>				
		</div>
	</div>

	
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript" src="./js/dashboard/script.js"></script>
</body>
</html>