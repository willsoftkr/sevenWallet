<?php
include_once('./_common.php');

	
?>

<!DOCTYPE html>
<html>

<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/upstairs/style.css">

	<script>
		$(function () {
			var mb_block = Number('<?=$member[mb_block]?>');
			var search = $('#recipient').val();
			var mb_id = '<?=$member[mb_id]?>';
			$("#toDate").datepicker();
			$("#fromDate").datepicker();

			//bit transfer to another member
			$('#send_eth').on('click', function () {
				if (!mb_block) {
					$.ajax({
						type: "POST",
						url: "coin_trans_proc.php",
						cache: false,
						async: false,
						dataType: "json",
						data: {
							func: 'send_eth',
							auth_code: $('#otp_code').val(),
							receiver: $('#recipient').val(),
							mb_id: mb_id,
							amt: $('#trans_eth').val()
						},
						success: function (data) {
							//alert(data.result);
							commonModal('Error', data.result, 80);
							if (data.result == "OK") {
								$('#transferBitcoin').modal('show');
							}
						}
					});
				} else {
					commonModal('<strong>Contact Administrator</strong>',
						'<i class="fas fa-exclamation-triangle red"></i><h4>You were blocked. Contact Administrator.</h4>');
				}
			});



			$('#withd_eth').on('keyup', function (e) {

				var eth = '<?=$btc['
				eth_cost ']?>'; // 1비트코인의 환전 금액

				$('#withd_usd').val(Number($('#withd_eth').val()) * Number(eth));
			});

			$('#trans_eth').on('keyup', function (e) {

				var eth = '<?=$btc['
				eth_cost ']?>'; // 1이더 코인의 환전 금액

				$('#trans_usd').val(Number($('#trans_eth').val()) * Number(eth));
			});

			$('#btn_eth').on('click', function () {
				$.ajax({
					type: "POST",
					url: "wallet_addr.u.php",
					cache: false,
					async: false,
					dataType: "json",
					data: {
						coin: 'eth',
						eth_my_wallet: $('#eth_my_wallet').val()
					},
					success: function (data) {
						$('#ethereumAddressModalCenter').modal('show');
					}
				});
			});

			$('#transfer_search').on('click', function (e) {
				var word = $('#recipient').val();
				if (word == "") {
					commonModal('Error', 'Please enter recipient username.', 80);
					return;
				}
				getUser(word);
			});

			$('#recipient').on('keydown', function (e) {
				if (e.keyCode == '13') {
					getUser($(this).val());
				}
			});
			$(document).on('click', '#referral .modal-body .user', function (e) {
				$('#referral .modal-body .user').removeClass('selected');
				$(this).addClass('selected');
			});
			$('#btnSave').on('click', function (e) {
				$('#recipient').val($('#referral .modal-body .user.selected').html());
				$('#referral').modal('hide');
			});

			var priceCharts = document.getElementById('priceCharts').getContext('2d');
			window.myLine = new Chart(priceCharts, priceChartsConfig);
		});

		window.chartColors = {
			red: 'rgb(255, 99, 132)',
			orange: 'rgb(255, 159, 64)',
			gold: 'rgb(255, 205, 00)',
			green: 'rgb(75, 192, 192)',
			blue: 'rgb(54, 162, 235)',
			purple: 'rgb(153, 102, 255)',
			grey: 'rgb(201, 203, 207)'
		};

		var MONTHS = JSON.parse('<? echo json_encode($day_arr);?>');

		var priceChartsConfig = {
			type: 'line',
			data: {
				labels: MONTHS,
				datasets: [{
					label: 'ETH',
					backgroundColor: window.chartColors.blue,
					borderColor: window.chartColors.blue,
					data: JSON.parse('<? echo json_encode($eth_arr);?>'),
					fill: false,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: false,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'Days'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'Price'
						}
					}]
				}
			}
		};

		// 사용자 팝업
		function getUser(word) {
			$.ajax({
				type: 'GET',
				url: 'purchase_hash_full.user.php',
				data: {
					mb_id: word
				},
				success: function (data) {
					var list = JSON.parse(data);
					if (list.length > 0) {
						$('#referral').modal('show');
						var vHtml = $('<div>');
						$.each(list, function (index, obj) {
							vHtml.append($('<div>').addClass('user').html(obj.mb_id));
						});
						$('#referral .modal-body').html(vHtml.html());
					} else {
						commonModal('No results', 'No results were found for your search', 80);
					}
				}
			});
		}
	</script>
</head>

<body>

	<?include_once('mypage_head.php')?>
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<div class="crypto-wallets-container">
				<h2 class="gray">BALANCE</h2>
				<div class="wallets-left-container">
					<!-- //BALANCE -->
					<section class="wallet">
						<div class="wallet_inner">

							<h3 class="balance">총 잔고 <span class="total_balance">
									<?echo round($total_capital ,2);?> </span> EOS 입니다.</h3>

							<div class="coin_list">
								<div class="coin_img">
									<img src="./images/eos_logo_c.png" alt="eos_symbol">
									<p class="coin_name">EOS</p>
								</div>
								<div class="coin_balance">
									<p class="gtt_balance"> <?=$btc_wallet?> EOS</p>
									<p class="gtt_won_balance"> \ 0 </p>
								</div>
							</div>

							<div class="coin_list">
								<div class="coin_img">
									<img src="./images/eos_logo_c.png" alt="grade_img">
									<p class="coin_name">Upstairs</p>
								</div>
								<div class="coin_balance">
									<p> <?=$member['mb_balance'];?> EOS</p>
									<p> \ 0</p>
								</div>
							</div>
						</div>
					</section>
					<!-- //BALANCE -->

					<!-- 	Upstairs -->
					<h2 class="gray">Upstairs</h2>
					<section class="trade">
						<div class="trade_inner">
							<div class="coin_img">
								<img src="./images/eos_logo_c.png" alt="eos_symbol">
								<p class="coin_name">EOS</p>
							</div>
							<div class="trade_info">
								<input type="text" id="trade_money_2" class="trade_money" placeholder="0">EOS
							</div>
						</div>

						<div class="trade_arrow">
							<i class="fas fa-angle-double-down"></i>
						</div>

						<div class="trade_inner">
							<div class="coin_img">
								<img src="./images/eos_logo_c.png" alt="eos_symbol">
								<p class="coin_name">Upstairs</p>
							</div>
							<div class="trade_info">
								<input type="text" id="trade_money_2" class="trade_money" placeholder="0">EOS
							</div>
						</div>
				</div>
				</section>
				<!-- 	// Upstairs -->

				<!-- SUBMIT_BTN -->
				<div class="submit">
					<button id="exchange" class="btnOut2">Upstairs</button>
					<button id="exchange" class="btnCancle" onclick="exitApp()">Cancle</button>
				</div>
				<!-- //SUBMIT_BTN -->
			</div>
		</div>
	</div>
	</div>
	</div>



	<div class="modal fade" id="ethereumAddressModalCenter" tabindex="-1" role="dialog"
		aria-labelledby="ethereumAddressModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ethereumAddressModalLongTitle">EOS WALLET</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your wallet address has been saved.</h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="withdrawBitcoin" tabindex="-1" role="dialog"
		aria-labelledby="withdrawBitcoinModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="withdrawBitcoinModalLongTitle">EOS Withdrawal</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your EOS has been successfully withdrawn.</h4>
					<p>Please allow up to 2 hours for the transaction to complete.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="transferBitcoin" tabindex="-1" role="dialog"
		aria-labelledby="transferBitcoinModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="transferBitcoinModalLongTitle">EOS Transfer</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your EOS has been successfully transferred.</h4>
					<p>Please allow up to 2 hours for the transaction to complete.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="referral" tabindex="-1" role="dialog" aria-labelledby="referralModalLongTitle"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="referralModalLongTitle">Select Referrer's Username</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="user">
						Referrer1
					</div>
					<div class="user">
						Referrer2
					</div>
					<div class="user">
						Referrer3
					</div>
					<div class="user">
						Referrer4
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="btnSave">Save</button>
				</div>
			</div>
		</div>
	</div>
</body>

</html>