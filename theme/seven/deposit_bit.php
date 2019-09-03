<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 
//print_r($member);
$math_sql = "select  sum(mb_btc_account + mb_btc_calc + mb_btc_amt) as total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);

$btc_account = number_format($math_total['total'],8);
$btc_cost = get_coin_cost('btc');
$v7_cost = get_coin_cost('v7');

function deposit_result($val){
	return Number_format(get_coin_cost('btc')*$val, 2);
}
?>
			<section class="con90_wrap deposit_wrap">

				<div class="color_block bit_block">
					<div class="clear_fix">
						<strong class="f_left">Bitcoin Account</strong>
						<b class="f_right">1 btc per <?=$btc_cost?> USD</b>
					</div>

					<div class="clear_fix">
						<span class="f_left">   <?=$btc_account?> BTC</span>
						<!--<p class="f_right">  / <?=$v7_cost?> BTC</p>-->
					</div>
				</div>

						<div>
							<ul class="money_chage clear_fix mc_bit">
								<li>
									<p data-i18n="wallet.Amount to deposit">입금할 금액</p>
									<input type="number" id="amount" placeholder="<?=$btc_account?>" min="0" max="<?=$btc_account?>"  >
									<span>BTC</span>
								</li>
								<li>=</li>
								<li>
									<p class="font_red" data-i18n="wallet.All deposit">모두 입금하기</p>
									<input type="number" id="upstair" placeholder="<?=deposit_result($btc_account);?>" disabled >
									<span>USD</span>
								</li>
							</ul>
						</div>
							
			</section>
			
			<div class="btn_block_btm_wrap">
				<input type="button" value="입금" id="exchange"class="btn_basic" id="dep_btc" data-i18n="[value]wallet.Deposited">
				<p class="font_red text_center mt10" data-i18n="wallet.warning.A">경고 : 이 주문은 취소가 불가능합니다</p>
			</div>
			
		<div class="gnb_dim"></div>
	</section>



	<script>
			
			var btc_account = "<?=$btc_account?>";
			var btc_cost = "<?=$btc_cost?>";

			function deposit_result(val){
				return Number(btc_cost*val).toFixed(2);
			}

			$('#amount').on('change',function(){
				//console.log(this.value +" / "+ btc_account);

				if(this.value > btc_account){
					commonModal('check input amount','<strong> out of the maximum amount. </strong>',80);
					return false;
				}
				var rate = this.value;
				$('#upstair').val(deposit_result(rate));
			});

			
			$('#exchange').on('click', function(){
				var amount = $('#amount').val();
				var upstair = $('#upstair').val();
				var mb_id = "<?=$member['mb_id']?>";

				console.log(amount +" / "+ btc_account);

				if(amount > btc_account){
					commonModal('check input amount','<strong> out of the maximum amount. </strong>',80);
					return false;
				}
				else{
					
					$.ajax({
						type: "POST",
						url: "/util/upstairs_proc.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							"account" : btc_account,
							"amount" : amount,
							"upstair": upstair,
							"coin_cost" : btc_cost,
							"mb_id" : mb_id,
							"coin_symbol" : "btc"
						},
						success: function(data) {
							
							commonModal('Congratulation! Complete Deposit','<strong> Congratulation! Complete Deposit.</strong>',80);	
							$('#closeModal').on('click', function(){
								location.reload();
							});
							
							
						},
						error:function(e){
							commonModal('Error!','<strong> Please check retry.</strong>',80);	
						}
					});
					
				}
			});
		


		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_deposit.png' alt='아이콘'><span data-i18n='title.Deposit with Bitcoin'> 비트코인 입금</span>");
			$('#wrapper').css("background","#fff");
		});
	</script>


<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>
