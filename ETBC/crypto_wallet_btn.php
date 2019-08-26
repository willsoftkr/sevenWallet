<?php
include_once('./_common.php');
?>
<?

	$sql_btc = "select * from coin_cost";
	$btc = sql_fetch($sql_btc); // 시세

	$sql = "select mb_wallet, mb_balance from g5_member where mb_id ='{$member['mb_id']}'";
	$ret = sql_fetch($sql);
	$address  = $ret['mb_wallet']; // 지갑 주소
	

?>
<!DOCTYPE html>
<html lang="en">
<head>
		<?include_once('common_head.php')?>

		<link rel="stylesheet" href="css/crypto_wallets/style.css">

		<script>

		var mb_balance = '<?=$member['mb_balance']?>';
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

				$('#btn_btc').on('click', function() {
					
					$.ajax({
						type: "POST",
						url: "wallet_addr.u.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							coin : 'btc',
							btc_my_wallet : $('#btc_my_wallet').val()
						},
						success: function(data) {
							$('#bitcoinAddressModalCenter').modal('show');
							$
						}
					});
				});
				$('#btn_eth').on('click', function() {
					$.ajax({
						type: "POST",
						url: "wallet_addr.u.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							coin : 'eth',
							eth_my_wallet : $('#eth_my_wallet').val()
						},
						success: function(data) {
							$('#ethereumAddressModalCenter').modal('show');
						}
					});
				});

				$('#withd_btc').on('keyup',function(e){

					var btc = '<?=$btc['btc_cost']?>'; // 1비트코인의 환전 금액
					
					$('#withd_usd').val(Number($('#withd_btc').val()) * Number(btc));
				});

				$('#trans_btc').on('keyup',function(e){

					var btc = '<?=$btc['btc_cost']?>'; // 1비트코인의 환전 금액
					
					$('#trans_usd').val(Number($('#trans_btc').val()) * Number(btc));
				});				
				// 밸런스 가 음수면 출금 불가능 처리
				if(mb_balance < 0){
					$('#withdraw form').prop('action', '');
					$('#money').prop('disabled', true);
					$('#withdraw [name=addr]').prop('disabled', true);
					$('#withdraw [name=auth_code]').prop('disabled', true);
				}
			});
		</script>
	
</head>
<body>
<?include_once('mypage_head.php')?>
	<div class="main-container">		
		<div class="big-container-wrapper">
			<div class="crypto-wallets-container">
				<h2 class="gray">Crypto Wallets</h2>
				<div class="wallets-left-container">

					<div class="total-value-container">
						<span class="value-big">
							$ <?echo round($btc_wallet*$btc['btc_cost'],2);?></span><br>
						<span class="value-small">
							Total Value
						</span>
					</div>

	
					<div class="wallet-selector-container">

						<div class="bitcoin-wallet active">
							<div class="bitcoin-wallet-logo">
								<img src="./images/btc_logo.png">
							</div>
							<div class="bitcoin-wallet-current-price">
								<span class="blue">BTC</span><br>
								<span class="gray">$ <?=$coin_dollor['btc_cost']?></span>
							</div>
							<div class="bitcoin-wallet-holding-amount">
								<span class="blue">$ <?=round($coin_dollor['btc_cost']*$tot_btcbal,6)?></span><br>
								<span class="gray"><?=$tot_btcbal?> BTC</span>
							</div>
							<div class="bitcoin-wallet-pct-change">
								<span class="green">변동치% <i class="fas fa-long-arrow-alt-up"></i></span>
							</div>
							<div class="input-group mb-3 wallet-address-container">
								<input type="text" class="form-control" placeholder="BTC Wallet Address" aria-label="BTC Wallet Address" aria-describedby="basic-addon2" id="btc_my_wallet" value="<?echo $member['btc_my_wallet'];?>">
								<div class="input-group-append">
									<button class="btn btn-outline-primary save-button" type="button" id="btn_btc" >Save</button>

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
								</div>
							</div>
						</div>
						
						<div class="ethereum-wallet" >
							<div class="ethereum-wallet-logo">
								<img src="./images/eth_logo.png">
							</div>
							<div class="ethereum-wallet-current-price">
								<span class="blue">ETH</span><br>
								<span class="gray">$ <?echo $btc['eth_cost']?></span>
							</div>
							<div class="ethereum-wallet-holding-amount">
								<span class="blue">$<?echo $btc['eth_cost']*$member[it_pool5_profit]?></span><br>
								<span class="gray"><?echo $member[it_pool5_profit];?></span>
							</div>
							<div class="ethereum-wallet-pct-change">
								<span class="red">+8.56% <i class="fas fa-long-arrow-alt-down"></i></span>
							</div>
							<div class="input-group mb-3 wallet-address-container">
								<input type="text" class="form-control" placeholder="ETH Wallet Address" aria-label="ETH Wallet Address" aria-describedby="basic-addon2" id="eth_my_wallet" value="<?echo $member['eth_my_wallet'];?>">
								<div class="input-group-append">
									<button class="btn btn-outline-primary save-button" type="button" id="btn_eth">Save</button>

									<div class="modal fade" id="ethereumAddressModalCenter" tabindex="-1" role="dialog" aria-labelledby="ethereumAddressModalCenterTitle" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="ethereumAddressModalLongTitle">ETHEREUM WALLET</h5>
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
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="wallets-right-container">
					<div class="transaction-panels">
						<ul class="tabs">
							<li rel="transfer" class="active">Transfer</li>
							<li rel="withdraw">Withdraw</li>
							<li rel="deposit">Deposit</li>
						</ul>

						<div id="transfer" class="panel active">
							
							<form class="transfer-form">
								<div class="transfer-recipient-username-container">
									<i class="fas fa-user-circle transfer-user-icon"></i> 
									<input type="text" name="recipient username" placeholder="Recipient username">
									<div id="ajax_rcm_search" class="search-btn-container"><button class="search-button btn btn-primary" type="button">Search</button></div>
								</div>

								<div class="btc-usd-exchange-container">
									<div class="crypto-exchange-price">
										BTC : <input type="text" placeholder="0.000000" id="trans_btc" name="trans_btc">
									</div>

									<i class="fas fa-exchange-alt"></i>

									<div class="crypto-exchange-price">
										USD : <input type="text" placeholder="00.00" id="trans_usd" name="trans_usd">										
									</div>
								</div>

								<div class="otp-auth-code-container">
									<i class="fas fa-key"></i>
									<input type="text" name="otp auth code" placeholder="OTP Authorization Code">
								</div>

								<div class="send-button-container">
									<button type="button" class="btn btn-primary form-send-button" data-toggle="modal" data-target="#transferBitcoin">Send Bitcoin</button>
									<div class="modal fade" id="transferBitcoin" tabindex="-1" role="dialog" aria-labelledby="transferBitcoinModalCenterTitle" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="transferBitcoinModalLongTitle">Bitcoin Transfer</h5>
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
									<br>
									Withdrawal fee: 0.002 BTC<br>
									<span class="gray">(Minimum Withdrawal 0.02 BTC)</span>
								</div>
							</form>
						</div>

						<div id="withdraw" class="panel">
							<form class="transfer-form">
								<div class="transfer-withdrawal-address">
									<div class="withdrawal-address">
										<p><? echo $member[btc_my_wallet];?></p>
									</div>
									<p class="gray">Withdrawal Address</p>
								</div>

								<div class="btc-usd-exchange-container">
									<div class="crypto-exchange-price">
										BTC : <input type="text" placeholder="0.000000" id="withd_btc" name="withd_btc">
									</div>
									<i class="fas fa-exchange-alt"></i>									
									<div class="crypto-exchange-price">
										USD : <input type="text" placeholder="00.00" id="withd_usd" name="withd_usd">										
									</div>
								</div>

								<div class="otp-auth-code-container">
									<i class="fas fa-key"></i>
									<input type="text" name="otp auth code" placeholder="OTP Authorization Code">
								</div>

								<div class="send-button-container">
									<button type="button" class="btn btn-primary form-send-button" data-toggle="modal" data-target="#withdrawBitcoin">Withdraw Bitcoin</button>
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
									<br>
									Withdrawal fee: 0.002 BTC<br>
									<span class="gray">(Minimum Withdrawal 0.002 BTC)</span>
								</div>
							</form>
						</div>

						<div id="deposit" class="panel">
							
							<div>
								<span class="gray">Bitcoin Wallet Address</span>
								3FkenCiXpSLqD8L79intRNXUgjRoH9sjXa
								<img src="./images/sample_qr.png" width="200">
							</div>

						</div>
					</div>					
				</div>
			</div>	
<?
	$map = array(); 
	$map['R'] = 'Request';
	$map['Y'] = 'Approval';
	$map['S'] = 'Wait';
	$map['N'] = 'Disapproval';

	$sql = " select count(*) as cnt from withdrawal_request A inner join g5_member M on A.id = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."'";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 5;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = "select * from withdrawal_request A inner join g5_member M on A.id = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."'";
	$sql .= " order by create_dt desc ";
	$sql .= " limit {$from_record}, {$rows} ";
	$list = sql_query($sql);
?>
			<div class="transaction-history-table-container">
				<div class="transaction-history-header">				
					<h2 class="transaction-history-title gray">Transaction History</h2>
					<span class="gray">( <?=$total_count?> Total )</span>
					<input class="date-range from" type="text" placeholder="Date range from" id="fromDate">
					<input class="date-range to" type="text" placeholder="Date range to" id="toDate">
					<select class="custom-select transaction-history-select">
						<option selected>All</option>
						<option value="1">Transfer</option>
						<option value="2">Withdrawal</option>
						<option value="3">Mining</option>
						<option value="4">Deposit</option>
					</select>
					<i class="fas fa-search gray"></i>
				</div>
				<table class="table table-hover table-responsive">
					<colgroup>
						<col style="width:16%;"/>
						<col style="width:auto;"/>
						<col style="width:auto;"/>
						<col style="width:8%;"/>
						<col style="width:8%;"/>
						<col style="width:8%;"/>
						<col style="width:8%;"/>
					</colgroup>
					<thead>
					<tr>
						<th>Date</th>
						<th>Details</th>
						<th>Wallet Address</th>
						<th>Type</th>
						<th>Amount</th>
						<th>USD</th>
						<th>Status</th>
					</thead>
					<tbody>
					</tr>
						<?for ($i=0; $row=sql_fetch_array($list); $i++) {?>
						<tr>
							<td><?=$row['create_dt']?></td>
							<td>상세 메세지 미구현</td>
							<td>
								<a href="#" onclick="window.open('https://blockchain.info/address/<?=$row[addr]?>','width=800, height=500');">
								<?=$row['addr']?>
								</a>
							</td>
							<td>타입필드</td>
							<td><?=$row['amt']?> BTC</td>
							<td>$ <?=$row['amt'] ?></td>
							<td class="<?=$map[$row['status']]?>"><?=$map[$row['status']]?></td>
						</tr>
					<?}?>
				</table>

				<?php
					$pagelist = get_paging_new($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
					if ($pagelist) {
						echo $pagelist;
					}
				?>
				<div class="page-search-container">
					<input class="search-input" type="number" placeholder="Page">
					<button>Search</button>			  	
				</div>
			</div>

		</div>
	</div>

</body>
</html>
