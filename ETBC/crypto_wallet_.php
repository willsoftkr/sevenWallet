<?php
include_once('./_common.php');
?>
<?

	$sql_btc = "select * from coin_cost";
	$btc = sql_fetch($sql_btc); // 시세

	$sql = "select mb_wallet from g5_member where mb_id ='{$member['mb_id']}'";
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

				$('#money').on('keyup',function(e){
					$('[name=amt]').val((Number($(this).val())+ 0.002).toFixed(8));
					$('#total').val((Number($(this).val()) + 0.002).toFixed(8));
					var btc = '<?=$btc['btc_cost']?>'; // 1비트코인의 환전 금액
					$('#usd').val(Number($('#total').val()) * Number(btc));
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
							$달러환산합계
						</span><br>
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
						</div>

						<div class="ethereum-wallet">
							<div class="ethereum-wallet-logo">
								<img src="./images/eth_logo.png">
							</div>
							<div class="ethereum-wallet-current-price">
								<span class="blue">ETH</span><br>
								<span class="gray">$ 0(시세)</span>
							</div>
							<div class="ethereum-wallet-holding-amount">
								<span class="blue">$ 환전금액</span><br>
								<span class="gray"><?=$total_mining_sudang2?> ETH</span>
							</div>
							<div class="ethereum-wallet-pct-change">
								<span class="red">변동치% <i class="fas fa-long-arrow-alt-down"></i></span>
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
						<!-- 송금 -->
						<div id="transfer" class="panel active">
							
							<form class="transfer-form">
								<div class="transfer-recipient-username-container">
									<i class="fas fa-user-circle transfer-user-icon"></i> 
									<input type="text" name="recipient username" placeholder="Recipient username">
								</div>

								<div class="transfer-recipient-wallet-address">
									<i class="fab fa-telegram-plane transfer-send-icon"></i>
									<input type="text" name="addr" placeholder="Recipient wallet address">
								</div>

								<div class="btc-usd-exchange-container">
									<div class="crypto-exchange-price">
										BTC : <input type="text" placeholder="0.000000" name="btc input">
									</div>

									<i class="fas fa-exchange-alt"></i>

									<div class="crypto-exchange-price">
										USD : <input type="text" placeholder="00.00" name="btc input">										
									</div>
								</div>

								<div class="otp-auth-code-container">
									<i class="fas fa-key"></i>
									<input type="text" name="otp auth code" placeholder="OTP Authorization Code">
								</div>

								<div class="send-button-container">
									<button type="submit" class="btn btn-primary form-send-button">Send Bitcoin</button><br>
									Withdrawal fee: 0.002 BTC<br>
									<span class="gray">(Minimum Withdrawal 0.02 BTC)</span>
								</div>
							</form>
						</div>
						<!-- 출금 withdraw -->
						<div id="withdraw" class="panel transfer-form">
							<form action ="./receipt.i.php" method="POST">
								<div class="transfer-withdrawal-address">
									<i class="fas fa-wallet transfer-withdrawal-icon"></i>
									<input type="text" name="addr" placeholder="Withdrawal address" required>
								</div>
								<div class="btc-usd-exchange-container" >
									<div class="crypto-exchange-price withdraw">
										<input type="text" id="money" class="num" placeholder="0.022000" maxlength="12" style="width:430px" required> BTC
									</div>
									<div class="crypto-exchange-price ">
										<input type="text" id="total" class="num" placeholder="0.0" readonly="readonly" style="width:190px;"> BTC
										<input type="hidden" name="amt" />
									</div>
									<div class="crypto-exchange-price ">
										<i class="fas fa-exchange-alt"></i>
										$ <input type="text" placeholder="0.0" id="usd" class="num" readonly="readonly" style="width:160px;">
									</div>
								</div>

								<div class="otp-auth-code-container">
									<i class="fas fa-key"></i>
									<input type="text" name="auth_code" placeholder="OTP Authorization Code" required>
								</div>

								<div class="send-button-container">
									<button type="submit" class="btn btn-primary form-send-button">Withdraw Bitcoin</button><br>
									Withdrawal fee: 0.002 BTC<br>
									<span class="gray">(Minimum Withdrawal 0.02 BTC)</span>
								</div>
							</form>
						</div>
						<!-- 입금 -->
						<div id="deposit" class="panel">
							
							<div>
								<span class="gray">FIJI Wallet Address</span>
								<?=$address;?>
								<img src="./images/sample_qr.png" width="200">
								qr코드 구현안됨
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
					<select class="custom-select transaction-history-select">
					  <option selected>All</option>
					  <option value="1">Transfer</option>
					  <option value="2">Withdrawal</option>
					  <option value="3">Deposit</option>
					</select>
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
						</tr>
					</thead>
					<tbody>
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
					</tbody>
				</table>
				
				<?php
					$pagelist = get_paging_new($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
					if ($pagelist) {
						echo $pagelist;
					}
				?>
			</div>

		</div>
	</div>


</body>
</html>
