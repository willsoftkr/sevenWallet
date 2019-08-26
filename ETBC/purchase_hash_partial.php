<?php
include_once('./_common.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?include_once('common_head.php')?>

	<link rel="stylesheet" href="css/purchase_partial_hash_btc/style.css">
	<script>
		$(function() {

			var slider = document.getElementById('hash-range');
			var currentHashValue = document.getElementById('current-hash-value');
			var output = document.getElementById('selected-hash-value');
			var totalHashValue = document.getElementById('total-hash-value');
			var output2 = document.getElementById('output2');
			var dollarValue = document.getElementById('dollar-value');
			var repurchaseRate = document.getElementById('variable-repurchase-rate');

			totalHashValue.innerHTML = parseFloat(slider.value) + parseFloat(currentHashValue.innerHTML);

			dollarValue.innerHTML = '1,000';
			repurchaseRate.innerHTML = '50%';

			slider.oninput = function() {
			output.innerHTML = this.value;
			output2.innerHTML = Math.round((parseFloat(this.value) + parseFloat(currentHashValue.innerHTML)) * 1000).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			totalHashValue.innerHTML = parseFloat(this.value) + parseFloat(currentHashValue.innerHTML);
			dollarValue.innerHTML = parseInt(parseFloat(output.innerHTML) * 294.12).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

			if (parseFloat(output.innerHTML) < 10.2) {
				repurchaseRate.innerHTML = '50%';
			} else if (parseFloat(output.innerHTML) < 17) {
				repurchaseRate.innerHTML = '40%';
			} else if (parseFloat(output.innerHTML) < 40.8) {
				repurchaseRate.innerHTML = '30%';
			} else {
				repurchaseRate.innerHTML = '20%';
			}
			}

			output.innerHTML = slider.value;

			output2.innerHTML = (parseFloat(totalHashValue.innerHTML) * 1000).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

		});
	</script>
	
</head>
<body>
	<?include_once('mypage_head.php')?>


	<div class="main-container">		
		<div class="big-container-wrapper">
			<h2 class="gray purchase-partial-hash-title">Purchase Partial Hash</h2>
			<select class="custom-select crypto-select">
			  <option selected>Bitcoin (BTC)</option>
			  <option value="1">Ethereum (ETH)</option>
			</select>
			<div class="purchase-partial-hash-container shadow">

				<div class="partial-hash-calc-container">

					<div class="current-hash-power">
						<img src="./images/current_hash_power.png" alt="current hash power">
						<span class="hash-number"><span id="current-hash-value">204 <span class="thsmall">TH/s</span></span></span><br>
						<span class="gray">Current Hash Power</span>
					</div>

					<i class="fas fa-plus"></i>

					<div class="selected-hash-power">
						<img src="./images/selected_hash_power.png" alt="selected hash power">
						<span class="hash-number"><span id="selected-hash-value"></span> <span class="thsmall">TH/s</span></span><br>
						<span class="gray">Selected Hash Power</span>
					</div>

					<i class="fas fa-equals"></i>

					<div class="total-hash-power">
						<img src="./images/total_hash_power.png" alt="total hash power">
						<span class="hash-number blue"><span id="total-hash-value"></span> <span class="thsmall">TH/s</span></span><br>
						<span class="blue">Total Hash Power</span>
					</div>

				</div>

				<div class="hash-selector-container">
					<div class="slidecontainer">
						<div class="top-ticks">
							<p class="caption">TH/s</p>
							<span class="tick tick-3_4">3.4</span>
							<span class="tick tick-10_2">10.2</span>
							<span class="tick tick-17">17</span>
							<span class="tick tick-23_8">23.8</span>
							<span class="tick tick-30_6">30.6</span>
							<span class="tick tick-37_4">37.4</span>
							<span class="tick tick-44_2">44.2</span>
							<span class="tick tick-51">51</span>
							<span class="tick tick-57_8">57.8</span>
							<span class="tick tick-64_6">64.6</span>
							<span class="tick tick-71_4">71.4</span>
							<span class="tick tick-78_2">78.2</span>
							<span class="tick tick-85">85</span>
							<span class="tick tick-91_8">91.8</span>
							<span class="tick tick-98_6">98.6</span>
							<span class="tick tick-105_4">105.4</span>
							<span class="tick tick-112_2">112.2</span>
							<span class="tick tick-119">119</span>
							<span class="tick tick-125_8">125.8</span>
							<span class="tick tick-132_6">132.6</span>
							<span class="tick tick-139_4">139.4</span>
							<span class="tick tick-146_2">146.2</span>
							<span class="tick tick-153">153</span>
							<span class="tick tick-159_8">159.8</span>
							<span class="tick tick-166_6">166.6</span>
						</div>
						

					  <input type="range" min="3.4" max="170" value="3.4" step="3.40" class="slider" id="hash-range"><br>

					  <div class="bot-ticks">
						  <span class="tick tick-6_8">6.8</span>
						  <span class="tick tick-13_6">13.6</span>
						  <span class="tick tick-20_4">20.4</span>
						  <span class="tick tick-27_2">27.2</span>
						  <span class="tick tick-34">34</span>
						  <span class="tick tick-40_8">40.8</span>
						  <span class="tick tick-47_6">47.6</span>
						  <span class="tick tick-54_4">54.4</span>
						  <span class="tick tick-61_2">61.2</span>
						  <span class="tick tick-68">68</span>
						  <span class="tick tick-74_8">74.8</span>
						  <span class="tick tick-81_6">81.6</span>
						  <span class="tick tick-88_4">88.4</span>
						  <span class="tick tick-95_2">95.2</span>
						  <span class="tick tick-102">102</span>
						  <span class="tick tick-108_8">108.8</span>
						  <span class="tick tick-115_6">115.6</span>
						  <span class="tick tick-122_4">122.4</span>
						  <span class="tick tick-129_2">129.2</span>
						  <span class="tick tick-136">136</span>
						  <span class="tick tick-142_8">142.8</span>
						  <span class="tick tick-149_6">149.6</span>
						  <span class="tick tick-156_4">156.4</span>
						  <span class="tick tick-163_2">163.2</span>
						  <span class="tick tick-170">170</span>			  	
					  </div>
					</div>
				</div>

				<div class="hash-stats">
					<div class="stat-container">
						<img src="./images/repurchase_rate.png" alt="repurchase rate" /><br>
						<span class="blue stat-container-big" id="variable-repurchase-rate"></span><br>
						<span class="gray"> Repurchase Rate</span>
					</div>

					<div class="stat-container">
						<img src="./images/new_total_hash_rate.png" alt="total hash rate" /><br>
						<span class="blue stat-container-big">
						<span id="output2"></span> GH/s</span><br>
						<span class="gray">New Total Hash Rate</span>
					</div>

					<div class="stat-container">
						<img src="./images/mining_contract.png" alt="mining contract" /><br>
						<span class="blue stat-container-big">1,000 Days</span><br>
						<span class="gray">Mining Contract</span>
					</div>
					
				</div>

				<div class="partial-hash-checkout-container">
					<div class="exchange-rate">
						<span class="dollar-exchange">USD : <span id="dollar-value"></span></span> <i class="fas fa-exchange-alt"></i> <span class="btc-exchange">BTC : 0.12345678</span>
					</div>
					<button type="button" class="btn btn-primary select-payment-button" data-toggle="modal" data-target="#paymentMethod">Select Payment</button>
					<div class="modal fade" id="paymentMethod" tabindex="-1" role="dialog" aria-labelledby="paymentMethodModalCenterTitle" aria-hidden="true">
					  <div class="modal-dialog modal-dialog-centered" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title" id="paymentMethodModalLongTitle">SELECT PAYMENT METHOD</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					        <div id="btcPayment" class="payment-method">
					        	<img src="./images/btc_logo.png" alt="bitcoin">
					        	BITCOIN
					        </div>
					        <div id="ethPayment" class="payment-method">
					        	<img src="./images/eth_logo.png" alt="ethereum">
					        	ETHEREUM
					        </div>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					        <button type="button" class="btn btn-primary">Checkout</button>
					      </div>
					    </div>
					  </div>
					</div>
				</div>

			</div>				
		</div>
	</div>


</body>
</html>
