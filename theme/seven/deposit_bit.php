<?
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php'); 
include_once(G5_THEME_PATH.'/_include/wallet.php'); 


$sql = " select * from maintenance";
$nw = sql_fetch($sql);

if($nw['nw_upstair'] == 'Y'){
	$nw_upstair = 'Y';
}else{
	$nw_upstair = 'N';
}

if($nw_upstair == 'Y'){
	include_once(G5_PATH.'/service_pop.php');
}

//print_r($member);
/*
$math_sql = "select  sum(mb_btc_account + mb_btc_calc + mb_btc_amt) as total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);

$btc_account = number_format($math_total['total'],8);
$btc_cost = get_coin_cost('btc');
$v7_cost = get_coin_cost('v7');


function deposit_result($val){
	return Number_format(get_coin_cost('btc')*$val, 2);
}
*/
//echo $deposit_cost;

?>
<style>
	.total {margin:40px !important;padding-top:30px;border-top:1px solid #bbb}
</style>
			<section class="con90_wrap deposit_wrap">

				<div class="color_block bit_block">
					<div class="clear_fix">
						<strong class="f_left">Bitcoin Account</strong>
						<b class="f_right">$<?=$btc_cost?> / BTC</b>
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
									<a  id ="all_account_deposit"><p class="font_red"  data-i18n="wallet.All deposit">모두 입금하기</p></a>
									<input type="number" id="upstair" placeholder="0" disabled >
									<span>USD</span>
								</li>
							</ul>
						</div>

						<!--
						<div class="total">
							<span class="send_title" data-i18n="wallet.인출합계">Total Deposit</span>
							<p class="f_right"><span class="font_gray" id="amount_total">0</span> Sales/Point</p>
						</div>
						-->
						
	
</div>
							
			</section>
			
			<div class="btn_block_btm_wrap">
				<input type="button" value="입금" id="exchange"class="btn_basic" id="dep_btc" data-i18n="[value]wallet.Deposited">
				<p class="font_red text_center mt10" data-i18n="wallet.warning.A">경고 : 이 주문은 취소가 불가능합니다</p>
			</div>
			
		<div class="gnb_dim"></div>
	</section>



	<script>
			
			var btc_account = "<?=$btc_account_num?>";
			var btc_cost = "<?=$btc_cost_num?>";
			var deposit_cost = "<?=$deposit_cost?>";
			console.log(deposit_cost);
			
			function deposit_result(val){
				
				return Number(deposit_cost*val).toFixed(2);
			}
			
			function btc_result(val){
				return Number(btc_cost*val).toFixed(2);
			}

			$('#all_account_deposit').on('click', function(){
				console.log("max ::"+btc_account);
				$('#amount').val(btc_account);
				$('#upstair').val(deposit_result(btc_account));
			});

			$('#amount').on('change',function(){
				//console.log(this.value +" / "+ btc_account);

				if(this.value > Number(btc_account)){
					commonModal('check input amount','<strong> out of the maximum amount. </strong>',80);
					return false;
				}
				var rate = this.value;
				//$('#upstair').val(btc_result(rate));
				$('#upstair').val(deposit_result(rate));
				//$('#amount_total').text(deposit_result(rate));
			});

			
			$('#exchange').on('click', function(){
				var amount = $('#amount').val();
				//var upstair = $('#amount_total').text();

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
							"account" : Number(btc_account),
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
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_deposit.png' alt='아이콘'><span data-i18n='title.Deposit with Bitcoin' style='margin-left:5px;'> 비트코인 입금</span>");
			$('#wrapper').css("background","#fff");
		});
	</script>


<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>
