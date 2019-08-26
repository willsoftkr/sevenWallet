<?php
include_once('./_common.php');
$sql_btc = "select * from coin_cost";
$cost_btc_js = sql_fetch($sql_btc); // 시세
$sql = "select * from pinna_mining_day4 order by day desc limit 7";
$list = sql_query($sql);
$day_arr = array();
$eth_arr = array();
$btc_arr = array();
$lkc_arr = array();
$rwc_arr = array();
for($i=7; $row = sql_fetch_array($list); $i--){
	//$day_arr[$i] = '$row[day]';
	array_push($day_arr, $row['day']);
	array_push($btc_arr, $row['btcrate']);
	array_push($lkc_arr, $row['lkcrate']);
	array_push($rwc_arr, $row['rwcrate']);

}
$day_arr = array_reverse($day_arr);
$btc_arr = array_reverse($btc_arr);
$eth_arr = array_reverse($eth_arr);
$lkc_arr = array_reverse($lkc_arr);
?>
<!DOCTYPE html>
<html >
<head>
	<?include_once('common_head.php')?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
	<link rel="stylesheet" href="css/crypto_wallets/style.css">

	<script>
		var mb_block = Number('<?=$member['mb_block']?>');
		var mb_balance = '<?=$member['mb_balance']?>';
		var mb_id = '<?=$member['mb_id']?>';
		$(function() {

			$('.transaction-panels .tabs li').on('click', function() {
				var $panel = $(this).closest('.transaction-panels');

				$panel.find('.tabs li').removeClass('active');
				$(this).addClass('active');

				var panelToShow = $(this).attr('rel');

				$panel.find('.panel.active').fadeOut(200, showNextPanel);

				function showNextPanel() {
					$(this).removeClass('active');

					$('#' + panelToShow).fadeIn(200, function() {
						$(this).addClass('active');
					});
				}
			});

			$( "#toDate" ).datepicker();
			$( "#fromDate" ).datepicker();

			$('#transfer_search').on('click', function (e) {
				var word = $('#recipient').val();
				if(word == "") {
					commonModal('Error','Please enter recipient username.',80);
					return;
				}
				getUser(word);
			});
			$(document).on('click','#referral .modal-body .user',function(e) {
				$('#referral .modal-body .user').removeClass('selected');
				$(this).addClass('selected');
			});
			$('#btnSave').on('click',function(e) {
				$('#recipient').val($('#referral .modal-body .user.selected').html());
				$('#referral').modal('hide');
			});

			$('#framewrp').click(function () {
				$(this).hide();
			});


			$('#btn_rwc').on('click', function() {
				$.ajax({
					type: "POST",
					url: "wallet_addr.u.php",
					cache: false,
					async: false,
					dataType: "json",
					data:  {
						coin : 'rwc',
						rwc_my_wallet : $('#rwc_my_wallet').val()
					},
					success: function(data) {
						$('#bitcoinAddressModalCenter').modal('show');
						location.replace("./crypto_wallet_rock.php");
					}
				});
			});
			
			//rwc transfer to another member
			$('#send_rwc').on('click', function() {
				if(!mb_block){
					$.ajax({
						type: "POST",
						url: "coin_trans_proc.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							func : 'send_rwc',
							auth_code : $('#otp_code').val(),
							receiver : $('#recipient').val(),
							mb_id : mb_id,
							amt : $('#trans_rwc').val()
						},
						success: function(data) {
							if(data.result=="OK"){
								$('#transferBitcoin').modal('show');
								location.replace("./crypto_wallet_rock.php");
							}else{
								commonModal('Error',data.result,80);
							}
						}
					});
				}else{
					commonModal('<strong>Contact Administrator</strong>','<i class="fas fa-exclamation-triangle red"></i><h4>You were blocked. Contact Administrator.</h4>');
				}
			});
			//bit withdraw my balnace
			$('#rwc_withd_btn').on('click', function() {
				if(!mb_block){
					$.ajax({
						type: "POST",
						url: "coin_trans_proc.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							func			: 'withd_rwc',
							wallet_addr : $('#withdrawal-address').val(),
							auth_code	: $('#otp_auth_with').val(),
							mb_id			: mb_id,
							amt			: $('#withd_btc').val()
						},
						success: function(data) {
								if(data.result=="OK"){
									$('#withdrawBitcoin').modal('show');
									location.replace("./crypto_wallet_rock.php");
								}
								else
									commonModal('Error',data.result,80);
						}
					});
				}else{
					commonModal('<strong>Contact Administrator</strong>','<i class="fas fa-exclamation-triangle red"></i><h4>You were blocked. Contact Administrator.</h4>');
				}
			});

			$('#withd_btc').on('keyup',function(e){
				var rwc = '<?=$cost_btc_js['rock_cost']?>'; // 1RWC의 환전 금액
				$('#withd_usd').val(Number($('#withd_btc').val()) * Number(rwc));
			});

			$('#trans_rwc').on('keyup',function(e){

				var rwc = '<?=$cost_btc_js['rock_cost']?>'; // 1RWC의 환전 금액

				$('#trans_usd').val(Number($('#trans_rwc').val()) * Number(rwc));
			});

			// 밸런스 가 음수면 출금 불가능 처리
			if(mb_balance < 0){
				$('#withdraw form').prop('action', '');
				$('#money').prop('disabled', true);
				$('#withdraw [name=addr]').prop('disabled', true);
				$('#withdraw [name=auth_code]').prop('disabled', true);
			}

			var priceCharts = document.getElementById('priceCharts').getContext('2d');
			window.myLine = new Chart(priceCharts, priceChartsConfig);
		}); // ready close

		// 도넛 차트
		var randomScalingFactor = function() {
			return Math.round(Math.random() * 100);
		};

		window.chartColors = {
			red: 'rgb(255, 99, 132)',
			orange: 'rgb(255, 159, 64)',
			yellow: 'rgb(255, 205, 86)',
			green: 'rgb(75, 192, 192)',
			blue: 'rgb(54, 162, 235)',
			purple: 'rgb(153, 102, 255)',
			grey: 'rgb(201, 203, 207)'
		};
		// bar 차트

		var MONTHS = JSON.parse('<? echo json_encode($day_arr);?>');

		var priceChartsConfig = {
			type: 'line',
			data: {
				labels: MONTHS,
				datasets: [{
					label: 'RWC',
					backgroundColor: window.chartColors.red,
					borderColor: window.chartColors.red,
					data: JSON.parse('<? echo json_encode($rwc_arr);?>'),
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
		function getUser(word){
			$.ajax({
				type:'GET',
				url:'purchase_hash_full.user.php',
				data: {
					mb_id : word
				} ,
				success: function(data){
					var list = JSON.parse(data);
					if(list.length > 0){
						$('#referral').modal('show');
						var vHtml = $('<div>');
						$.each(list, function( index, obj ) {
							vHtml.append($('<div>').addClass('user').html(obj.mb_id));
						});
						$('#referral .modal-body').html(vHtml.html());
					}else {
						commonModal('No results','No results were found for your search',80);
					}
				}
			});
		}

	</script>

</head>
<body>
	<?include_once('mypage_head.php')?>
	<div id="framewrp">
		<iframe name='framer' id="framer" frameborder="0"></iframe>
	</div><!-- // framewrp -->
<?

$sql_btc = "select * from coin_cost";
$coin_cost = sql_fetch($sql_btc); // 시세

$sql = "select mb_wallet, mb_balance from g5_member where mb_id ='{$member['mb_id']}'";
$ret = sql_fetch($sql);
$address  = $ret['mb_wallet']; // 지갑 주소

?>
	<div class="main-container">
		<div id="body-wrapper"class="big-container-wrapper">
			<div class="crypto-wallets-container">
				<h2 class="gray" data-i18n="walletRWC.title" >RWC Wallet</h2>
				<div class="wallets-left-container">

					<div class="total-value-container">
						<span class="value-big">
							$ <?echo round($total_capital ,2);?></span><br>
						<span class="value-small" data-i18n="wallet.total" >
							Total Value
						</span>
					</div>

					<div class="wallet-selector-container">
						<div class="bitcoin-wallet active">
							<div class="bitcoin-wallet-logo">
								<img src="./images/rwc_logo.png">
							</div>
							<div class="bitcoin-wallet-current-price">
								<span class="blue">RWC</span><br>
								<span class="gray">$ <?=$coin_dollor['rock_cost']?></span>
							</div>
							<div class="bitcoin-wallet-holding-amount">
								<span class="blue">$ <?=round($coin_dollor['rock_cost']*$rwc_num,2)?></span><br>
								<span class="gray"><?=$rwc_num?> RWC</span>
							</div>
							<div class="bitcoin-wallet-pct-change">
								<span class="green"></span>
							</div>
							<div class="input-group mb-3 wallet-address-container"><!--출금 주소 변경-->
								<input type="text" class="form-control" placeholder="RWC Wallet Address" aria-label="RWC Wallet Address" aria-describedby="basic-addon2" id="rwc_my_wallet" value="<?echo $rwc_addr;?>" data-i18n="[placeholder]walletRWC.addr" >
								<div class="input-group-append">
									<button class="btn btn-outline-primary save-button" type="button" id="btn_rwc" data-i18n="wallet.save" >Save</button>
									
								</div>
							</div>
						</div>
						<div class="price-chart-container-wallet shadow" >
							<h5  style="text-align:center" data-i18n="wallet.chart">
								RWC 7 Days Price Charts
							</h5>
							<!-- <img src="https://via.placeholder.com/400x300"> <br>
							(Place price chart here ^) -->
							<div style="width:400px;margin:0 auto;" >
								<canvas id="priceCharts" ></canvas>
							</div>
						</div>
					</div>
				</div>

				<div class="wallets-right-container">
					<div class="transaction-panels">
						<ul class="tabs">
							<li rel="transfer" class="active" data-i18n="wallet.transfer" >Transfer</li>
							<li rel="withdraw" data-i18n="wallet.withdraw" >Withdraw</li>
							<!--li rel="deposit">Deposit</li-->
						</ul>

						<div id="transfer" class="panel active">

							<form class="transfer-form">
								<div class="transfer-recipient-username-container">
									<i class="fas fa-user-circle transfer-user-icon"></i>

									<input type="text" name="recipient" id="recipient" placeholder="Recipient username" data-i18n="[placeholder]wallet.recipient" />
									<div id="ajax_rcm_search" class="search-btn-container"><button id="transfer_search" class="search-button btn btn-primary" type="button" data-i18n="wallet.search" >Search</button></div>
								</div>

								<div class="btc-usd-exchange-container">
									<div class="crypto-exchange-price">
										RWC : <input type="text" placeholder="0" id="trans_rwc" name="trans_rwc">
									</div>

									<i class="fas fa-exchange-alt"></i>

									<div class="crypto-exchange-price">
										USD : <input type="text" placeholder="00.00" id="trans_usd" name="trans_usd">
									</div>
								</div>

								<div class="otp-auth-code-container">
									<i class="fas fa-key"></i>
									<input type="text" id="otp_code" data-i18n="[placeholder]wallet.otpCode" name="otp_code" placeholder="OTP Authorization Code" required >
								</div>

								<div class="send-button-container">

									<button type="button" class="btn btn-primary form-send-button" data-i18n="walletRWC.send" id="send_rwc">Send Bitcoin</button>
									
									<br>
									<br>
									<span class="gray"></span>
								</div>
							</form>
						</div>
						<input type="hidden" value="<? echo $rwc_addr;?>" id="withdrawal-address"/>
						<div id="withdraw" class="panel">
							<form class="transfer-form">
								<div class="transfer-withdrawal-address">
									<div class="withdrawal-address">
										<p  ><? echo $rwc_addr;?></p>
									</div>
									<p class="gray" data-i18n="wallet.withdrawAddr" >Withdrawal Address</p>
								</div>

								<div class="btc-usd-exchange-container">
									<div class="crypto-exchange-price">
										RWC : <input type="text" placeholder="0" id="withd_btc" name="withd_btc">
									</div>
									<i class="fas fa-exchange-alt"></i>
									<div class="crypto-exchange-price">
										USD : <input type="text" placeholder="00.00" id="withd_usd" name="withd_usd">
									</div>
								</div>

								<div class="otp-auth-code-container">
									<i class="fas fa-key"></i>
									<input type="text" id="otp_auth_with" name="otp_auth_with" placeholder="OTP Authorization Code" data-i18n="[placeholder]wallet.otpCode">
								</div>

								<div class="send-button-container">
								<!--data-toggle="modal" data-target="#withdrawBitcoin"-->
									<button type="button" class="btn btn-primary form-send-button" id="rwc_withd_btn" data-i18n="walletRWC.withdrawRockWood">Withdraw Bitcoin</button>
									
									<br>
									<span data-i18n="wallet.withdrawlFee" >Withdrawal fee</span>: 1 RWC<br>
									<span class="gray">(<span data-i18n="wallet.minWithdrawal" >Minimum Withdrawal</span> 10 RWC)</span>
								</div>
							</form>
						</div>

						<div id="deposit" class="panel">

							<div>
								<span class="gray" data-i18n="wallet.addr" >Bitcoin Wallet Address</span>
								3FkenCiXpSLqD8L79intRNXUgjRoH9sjXa
								<img src="./images/sample_qr.png" width="200">
							</div>

						</div>
					</div>
				</div>
			</div>
<?
	$map = array();
	$map['R'] = 'Requested';
	$map['Y'] = 'Approved';
	$map['S'] = 'Wait';
	$map['N'] = 'Rejected ';

	$type = array();
	$type['5'] ='Transfer';
	$type['6'] = 'Withdraw';

	$sql = " select count(*) as cnt from pinna_btc_trans A inner join g5_member M on A.mb_id = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."' and type in(5, 6)";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 10;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = "select * from pinna_btc_trans A inner join g5_member M on A.mb_id = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."' and  type in(5, 6)";
	$sql .= " order by create_dt desc ";
	$sql .= " limit {$from_record}, {$rows} ";
	$list = sql_query($sql);
//type / {amount} RWC to {username} <-- transfer from A to B
// withdrawal of {amount} RWC
?>
			<div class="transaction-history-table-container">
				<div class="transaction-history-header">
					<h2 class="transaction-history-title gray" data-i18n="wallet.transactionHistory" >Transaction History</h2>
					<span class="gray">( <?=$total_count?> Total )</span>
					<input class="date-range from" type="text" placeholder="Date range from" data-i18n="[placeholder]wallet.dateRangeFrom" id="fromDate" />
					<input class="date-range to" type="text" placeholder="Date range to" data-i18n="[placeholder]wallet.dateRangeTo" id="toDate" />
					<select class="custom-select transaction-history-select">
						<option value="" data-i18n="wallet.all" selected >All</option>
						<option value="1" data-i18n="wallet.transfer">Transfer</option>
						<option value="2" data-i18n="wallet.withdraw">Withdraw</option>
						<!--option value="3">Mining</option>
						<option value="4">Deposit</option-->
					</select>
					<i class="fas fa-search gray" id="range_sel"></i>
				</div>
				<table class="table table-hover table-responsive">
					<colgroup>
						<col style="width:10%;"/>
						<col style="width:auto;"/>
						<col style="width:auto;"/>
						<col style="width:8%;"/>
						<col style="width:10%;"/>
						<col style="width:10%;"/>
						<col style="width:8%;"/>
					</colgroup>
					<thead>
						<tr>
							<th data-i18n="wallet.date">Date</th>
							<th data-i18n="wallet.details">Details</th>
							<th data-i18n="wallet.walletAddr">Wallet Address</th>
							<th data-i18n="wallet.type">Type</th>
							<th >RWC</th> <!--data-i18n="wallet.amount"-->
							<th data-i18n="wallet.usd">USD</th>
							<th data-i18n="wallet.status">Status</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $row=sql_fetch_array($list); $i++) {?>
							<tr>
								<td><?=substr($row['create_dt'],0,10)?></td>

								<td>
									<?if($row['type']==5){echo "Transferred ".$row[amt]." RWC to ".$row[recipient];}
									else if($row['type']==6){echo "withdrawal of ".$row[amt]." RWC";}?>
								</td>
								<td>
									<a href="#" onclick="window.open('https://blockchain.info/address/<?=$row[addr]?>','width=800, height=500');">
									<?=$row['addr']?>
									</a>
								</td>
								<td>
									<?echo $type[$row['type']]; ?>
								</td>
								<td><?=$row['amt']?> RWC</td>
								<td>$ <?echo $row['amt']*$cost_btc_js[btc_cost] ?></td>
								<td class="<?=$map[$row['status']]?>"><?=$map[$row['status']]?></td>
							</tr>
						<?}?>
					</tbody>
				</table>

				<?php
					$pagelist = get_paging_new($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
					if ($pagelist) {
						echo $pagelist;
					}
				?>
				<div class="page-search-container">
					<input class="search-input" type="number" placeholder="Page">
					<button data-i18n="wallet.search">Search</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="transferBitcoin" tabindex="-1" role="dialog" aria-labelledby="transferBitcoinModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="transferBitcoinModalLongTitle" data-i18n="wallet.sendBit" >Bitcoin Transfer</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your Bitcoin has been successfully transferred.</h4>
					<p>Please allow up to 2 hours for the transaction to complete.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="withdrawBitcoin" tabindex="-1" role="dialog" aria-labelledby="withdrawBitcoinModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="withdrawBitcoinModalLongTitle">Bitcoin Withdrawal</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your Bitcoin has been successfully withdrawn.</h4>
					<p>Please allow up to 2 hours for the transaction to complete.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="bitcoinAddressModalCenter" tabindex="-1" role="dialog" aria-labelledby="bitcoinAddressModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="bitcoinAddressModalLongTitle">BITCOIN WALLET</h5>
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
	<!-- Modal -->
	<div class="modal fade" id="referral" tabindex="-1" role="dialog" aria-labelledby="referralModalLongTitle" aria-hidden="true">
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
