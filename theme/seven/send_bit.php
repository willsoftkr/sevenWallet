<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php');
include_once(G5_THEME_PATH.'/_include/wallet.php'); 
//print_r($member);


$sql = " select * from maintenance";
$nw = sql_fetch($sql);

if($nw['nw_with'] == 'Y'){
	$nw_with = 'Y';
}else{
	$nw_with = 'N';
}

if($nw_with == 'Y'){
	include_once(G5_PATH.'/service_pop.php');
}

?>

			<section class="con90_wrap">
				<div class="color_block bit_block">
					<a href="/wallet/transaction_bit.php">
						<div class="clear_fix">
							<strong class="f_left">Bitcoin</strong>
							<b class="f_right"><?=$btc_account?> BTC</b>
						</div>
						<div class="clear_fix">
							<span class="f_left">= <?=$btc_cost?> USD</span>
							<p class="f_right"><?= $btc_rate?>USD</p>
						</div>
					</a>
				</div>

				<ul class="send_con">
					
					<li>
						<div>
							<div>
								<span class="send_title" data-i18n="wallet.수신인 지갑주소">Recipient's wallet address</span>
								<!--<img class="scan_qr f_right" src="<?=G5_THEME_URL?>/_images/wallet_addr.gif" alt="주소 확인하기">-->
							</div>
							<p class="font_gray mt10" data-i18n="wallet.비트코인 지갑 주소를 입력하거나 스캔하십시오">지갑 주소를 입력하거나 스캔하십시오</p>
							<input type="text" id="wallet_address" placeholder="address">
						</div>
					</li>
					
					
					<li>
						<div>
							<div class="clear_fix">
								<span class="send_title" data-i18n="wallet.전송할 코인 갯수">Amount to send</span>
								<a href="" class="f_right all_send" id="all_account_send" data-i18n="wallet.지갑 잔고 다 보내기"> Use maximum</a>
								<!-- <small class="f_right font_sky">잔고가 부족합니다.</small> -->
							</div>
							<ul class="money_chage clear_fix">
								<li>
									<input type="text" id="amount" placeholder="<?=$btc_account?>">
									<span>BTC</span>
								</li>
								<li>=</li>
								<li>
									<input type="text" id="amount_left" placeholder="$0.00">
									<span>USD</span>
								</li>
							</ul>
						</div>
					</li>
					<li>
						<div>
							<span class="send_title" data-i18n="wallet.수수료">Fee</span>
							<p class="f_right"><span class="font_gray" id="amount_fee">0</span> btc</p>
						</div>
					</li>
					<li>
						<div>
							<span class="send_title" data-i18n="wallet.인출합계">Total withdrawal</span>
							<p class="f_right"><span class="font_gray" id="amount_total">0</span> btc</p>
						</div>
					</li>
					<input type="hidden" id="amount_total_usd" name ="amount_total_usd"  val="">
				</ul>
			</section>
			
			<div class="btn_block_btm_wrap">
				<!--<input type="button" value="보내기" class="btn_basic send_tran_open pop_open">-->
				<!-- <input type="button" value="보내기" class="btn_basic" onClick="location.href='send_chk.php'"> -->
				<input type="button" value="Send" class="btn_basic" id="withdrawal" data-i18n='[value]wallet.전송하기'>
			</div>

		<div class="gnb_dim"></div>
	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_send.png' alt='아이콘'> <span data-i18n='title.비트코인 보내기'>Send Bitcoin</span>");
			$('#wrapper').css("background","#fff");
		});

		$('.all_send').click(function (event) {
			event.preventDefault();
		});
			
			var btc_account = Number("<?=$btc_account_num?>").toFixed(8);
			var max_btc_aacount = Number(btc_account*1.01).toFixed(8);
			var btc_cost = Number("<?=$btc_cost_num?>");
			var valid = false;
			//var btc_cost_1 = 10000.00;
			
			$('#amount').on('change',function(){
				
				console.log(this.value +" / "+ btc_account);
				
				
				if(this.value > btc_account*1.01){
					commonModal('check input amount','<strong> out of the maximum amount. </strong>',80);
					return false;
				}

				var rate = this.value;
				var btcusd = rate * btc_cost; //달러 환산

				var amount = Number(rate).toFixed(2); //total
				var amount_left = Number( (btcusd)*1).toFixed(2); //exchage
				var amount_fee = Number( (rate)*0.01).toFixed(8); // fee
				var amount_total = Number( (rate)*1.01).toFixed(8); // exchage+fee
				

				//console.log(v7usd+" | "+ shifted);

				$('#amount_left').val(amount_left );
				$('#amount_fee').html(amount_fee);
				$('#amount_total').html(amount_total);
				$('#amount_total_usd').val(amount_total_usd);
				valid = true;
			});


			$('#all_account_send').on('click', function(){
				var maxbtc = btc_account*0.99;
				var btcusd = (maxbtc*0.99) * btc_cost; //달러 환산

				var amount = Number(maxbtc).toFixed(8); //total
				var amount_left = Number(btcusd).toFixed(2); //exchage
				var amount_fee = Number((btc_account)*0.01).toFixed(8); // fee
				var amount_total = Number(btc_account).toFixed(8); //exchage+fee

				//console.log(v7usd+" | "+ shifted);
				$('#amount').val(amount);
				$('#amount_left').val(amount_left );
				$('#amount_fee').html(amount_fee);
				$('#amount_total').html(amount_total);
				$('#amount_total_usd').val(amount_total_usd);
				valid = true;
			});

			
			$('#withdrawal').on('click', function(){
				var amount = $('#amount').val();
				var amount_left = $('#amount_left').val();
				var amount_fee = $('#amount_fee').text();
				var amount_total = $('#amount_total').text();
				var mb_id = "<?=$member['mb_id']?>";
				var address = $('#wallet_address').val();;

				console.log( address + " / "+ amount+ " / "+ btc_account*1.01 + " / "+ btc_cost);
				
				/* 시세미달로 제한
				if(btc_cost < 11000){
					commonModal('Service not able','<strong> The exchange is not possible when the BTC price is below $11,000. </strong>',80);
					return false;
				}
				*/

				if(address=='' || address == null ){
					commonModal('check input address','<strong> Please check retry. </strong>',80);
					return false;
				}

				if(amount > (btc_account*1.01)){
					commonModal('check input amount','<strong> out of the maximum amount. </strong>',80);
					return false;
					
				}else if(amount < 0.04){
					commonModal('check input amount','<strong> The minimum possible quantity is 0.04 btc or more. </strong>',80);
					return false;
				}else if(amount > 0.5){
					commonModal('check input amount','<strong> The maximum possible quantity is 0.5 btc. </strong>',80);
					return false;
				}


				if(!valid){
					commonModal('check input amount','<strong> input amount. </strong>',80);
					return false;
				}

				else{
					
					$.ajax({
						type: "POST",
						url: "/util/withdrawal_proc.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							"mb_id" : mb_id,
							"account" : btc_account,
							"amount" : amount,
							"amount_left": amount_left,
							"fee": amount_fee,
							"amount_total": amount_total,
							"coin_cost" : btc_cost,
							"source" : "btc",
							"coin" : "btc",
							"type" : "withdrawal",
							"address" : address
						},
						success: function(data) {
							if(data.result == 'success'){
								purchaseModal('Complete send request','<strong> Complete Send/withdrawal.</strong>','success');
								//succsessModal('Complete send request','<strong> Complete Send/withdrawal.</strong>',80);	
								$('#modal_return_url').on('click', function(){
									location.reload();
								});

								$('#purchaseModal').on('click', function(){
									location.reload();
								});
							}else{
								commonModal('Error!','<strong> Please check retry.</strong>',80);	
							}
						},
						error:function(e){
							commonModal('Error!','<strong> Please check retry.</strong>',80);	
						}
						
					});
					
				}
			});
		
	</script>


<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>
