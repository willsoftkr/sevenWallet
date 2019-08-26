<?php
include_once('./_common.php');
?>
<?
	$sql_coin = "select * from coin_cost";
	$row = sql_fetch($sql_coin);

	$sql_hash_power = "select * from pina_mb_hashpower where mb_id = '".$member['mb_id']."'";
	$row_hp = sql_fetch($sql_hash_power);
	$current_hp = $row_hp['pool1_hashp']+$row_hp['pool2_hashp']+$row_hp['pool3_hashp']+$row_hp['pool4_hashp'];

	$current_hp_eth = $row_hp['pool5_hashp'];
?>
<!DOCTYPE html>
<html >
<head>
	<?include_once('common_head.php')?>

	<link rel="stylesheet" href="css/purchase_full_hash/style.css">
	<script>
		var btc = "<?=$row['btc_cost']?>";
		var eth = "<?=$row['eth_cost']?>";
		var mb_block = Number("<?=$member['mb_block']?>");
		var current_hp = "<?=$current_hp?>";
		var current_hp_eth = "<?=$current_hp_eth?>";
		$(function() {
			// 화면 관련
			var alterClass = function() {
				var ww = document.body.clientWidth;
				if (ww < 795) {
					$('.purchase-full-hash-table').addClass('table-responsive');
				} else if (ww >= 796) {
					$('.purchase-full-hash-table').removeClass('table-responsive');
				};
			};
			$(window).resize(function(){
				alterClass();
			});
			alterClass();

			// 데이터 관련
			calcTotal();

			$('.ct_qty1').spinner({ min: 0, max: 1, step: 1 });
			$('.ct_qty:not([id^="VVIP"])').spinner({ min: 0, max: 40, step: 1 });

			$( ".ct_qty1,.ct_qty" ).on('change keyup paste click',function(e) {
				calcTotal();
			});

			$('.ui-spinner-button').on('click',function(e) {
				calcTotal();
			});
			$('.payment-method').on('click',function(e) {
				$('.payment-method').removeClass('selected-payment');
				$(this).addClass('selected-payment');
				$('#payMethod').val($('.payment-method.selected-payment').attr('id'));
			});
			$( ".clear" ).on('click',function(e) {
				$(this).parents('tr').find('.ct_qty').val(0);
				calcTotal();
			});
			// vvip package
			$('#vvipRange').on('input', function() {
				$("#selectedAmount" ).html($(this).val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, ","));
				
				calcTotal();
			});
			// 추천인 검색 이벤트
			$('#btnSearch').on('click',function(e) {
				getUser();
			});
			$(document).on('click','#referral .modal-body .user',function(e) {
				$('#referral .modal-body .user').removeClass('selected');
				$(this).addClass('selected');
			});
			$('#btnSave').on('click',function(e) {
				$('#searchId').val($('#referral .modal-body .user.selected').html());
				$('#referral').modal('hide');
			});
			$('#searchId').on('keydown',function(e) {
				if(e.keyCode == 13){//키가 13이면 실행 (엔터는 13)
					e.preventDefault();
					getUser();
				}
			});

			$('#generateInvoice').on('click',function(e) {
				if(mb_block){
					commonModal('<strong>Contact Administrator</strong>','<i class="fas fa-exclamation-triangle red"></i><h4>You were blocked. Contact Administrator.</h4>');
				}else{
					$('[name=fitem]').submit();
				}
			});
		});

		function calcTotal(){

			var tot = 0;
			var tot_hash = 0;
			var tot_hash_eth = 0;

			// vvip 관련 설정
			$('[id^="VVIP"]').val(0);
			$('#VVIP'+ $('#vvipRange').val()).val(1);

			if($('.ct_qty1').length > 0) tot += Number($('.ct_qty1').val()) * Number($('.ct_qty1').attr('price')); // 멤버쉽
			$.each($('input.ct_qty'),function(idx){
				tot += Number($(this).val()) *  Number($(this).attr('price'));

				if($(this).attr('name').indexOf('1515148167') > 0){ // 이더리움
					tot_hash_eth += Number($(this).val()) *  Number($(this).attr('hp'));
				}else{ // 비트코인
					tot_hash += Number($(this).val()) *  Number($(this).attr('hp'));
				}
			});
			// console.log(tot);
			$('.subtotal').html('$ ' + numeral(tot).format('0,0'));
			$('#usdBtc').html(numeral(tot).format('0,0'));
			$('#usdEth').html(numeral(tot).format('0,0'));
			$('#btcExchange').html('BTC : ' + Number(tot / btc).toFixed(8));
			$('#ethExchange').html('ETH : ' + Number(tot / eth).toFixed(8));
			
			$('.selected-hash-power-container .hash-number.bit').html('+ ' + numeral(tot_hash).format('0,0') + ' GH/s');
			$('.selected-hash-power-container .hash-number.eth').html('+ ' + numeral(tot_hash_eth).format('0,0') + ' MH/s');
			$('#btcCurrentHash').html(numeral(Number(current_hp)).format('0,0'));
			$('#btcSelectedHash').html(numeral(Number(tot_hash)).format('0,0'));
			$('#btcTotalHash').html(numeral(Number(tot_hash) + Number(current_hp)).format('0,0'));
			$('#ethCurrentHash').html(numeral(Number(current_hp_eth)).format('0,0'));
			$('#ethSelectedHash').html(numeral(Number(tot_hash_eth)).format('0,0'));
			$('#ethTotalHash').html(numeral(Number(tot_hash_eth) + Number(current_hp_eth)).format('0,0'));
		}

		// 사용자 팝업
		function getUser(){
			$.ajax({
				type:'GET',
				url:'purchase_hash_full.user.php',
				data: {
					mb_id : $('#searchId').val()
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
						$('.search-result').hide();
					}else {
						$('.search-result').show();
					}
				}
			});
		}
	</script>
	
</head>
<body>
<?include_once('mypage_head.php')?>

<?php
	// 보관기간이 지난 상품 삭제
	cart_item_clean();
	// cart id 설정
	set_cart_id($sw_direct);
	$s_cart_id = get_session('ss_cart_id');
	// 선택필드 초기화
	$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
	sql_query($sql);
?>
<form name="fitem" method="post" action="/new/cartupdate.php" >
	<input type="hidden" name="sw_direct" value="1">
	<input type="hidden" name="payMethod" id="payMethod" value="btcPayment">
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			
			<h2 class="gray" data-i18n="purchase.title" >Purchase Full Hash</h2>
			<div class="purchase-full-hash-container shadow">
				<table class="table purchase-full-hash-table">
					<thead>
						<tr>
							<th data-i18n="purchase.package" >Package</th>
							<th data-i18n="purchase.quantity" >Quantity</th>
							<th data-i18n="purchase.price" >Price / Package</th>
							<th data-i18n="purchase.reset" >Reset</th>
						</tr>						
					</thead>
					<tbody>
<?php

	$tot_point = 0;
	$tot_sell_price = 0;

	// $s_cart_id 로 현재 장바구니 자료 쿼리
	$sql = " select a.it_id,
					a.it_name,
					a.it_basic,
					a.it_price,
					a.it_cust_price
			   from {$g5['g5_shop_item_table']} a ";
	if($member['mb_level'] >= 2){
		// $sql .= " where it_id not in ('1527096053', 'VVIP450000', 'VVIP337500', 'VVIP112500', 'VVIP225000') ";
		$sql .= " where it_id not in ('1527096053') ";
	}
	$sql .= " order by a.it_order ";
	$result = sql_query($sql,true);

	$it_send_cost = 0;

	$sum_qty = 0;

	for ($i=0; $row=sql_fetch_array($result); $i++)
	{

		$a1 = '<strong style="color:#1f4e79;">';
		$a2 = '</strong>';
		$image_url = get_it_imageurl($row['it_id']);

		//$it_name = $a1 . stripslashes($row['it_name']) . $a2;
		$it_name = stripslashes($row['it_name']);
		$it_options = print_item_options($row['it_id'], $s_cart_id);

		$sql_coin = "select * from coin_cost";
		$row_coin = sql_fetch($sql_coin);
		if(strpos($row['it_id'],'VVIP') === false) {
?>
						<tr>
							<td>
								<div class="btc-logo-container-2">
									<img src="<?php echo $image_url; ?>" class="purchase-bitcoin-icon" alt="btc icon" />	
									<?=strpos($row['it_id'],'VVIP')?>
								</div>
								
								<div class="package-description">
									<input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
									<span class="product-name"><?php echo $it_name.$mod_options; ?></span><br>
									<!-- <span class="gray"><?php echo $row['it_basic']?></span> -->
								</div>
							</td>
							<td style="vertical-align: middle;">
								<?if($row['it_id'] == '1527096053' ) { ?>
									<input class="ct_qty1" price="<? echo $row['it_price'];?>" hp="<? echo $row['it_cust_price'];?>" name="ct_qty[<?php echo $row['it_id']; ?>][]" value="0" maxlength="2" />
								<?} else {?>
									<input class="ct_qty" price="<? echo $row['it_price'];?>" hp="<? echo $row['it_cust_price'];?>" name="ct_qty[<?php echo $row['it_id']; ?>][]" value="0" maxlength="2" />
								<?} ?>
							</td>
							<td style="vertical-align: middle;">
								<span class="product-name price">$<?php echo number_format($row['it_price']); ?></span>
							</td>
							<td style="vertical-align: middle;">
								
								<div class="clear"><i class="fas fa-times"></i></div>
							</td>
						</tr>
<?php
		}else{
?>
			<input type="hidden" name="it_id[<?php echo $i; ?>]" value="<?php echo $row['it_id']; ?>">
			<input type="hidden" id="<?php echo $row['it_id']; ?>" name="ct_qty[<?php echo $row['it_id']; ?>][]" price="<? echo $row['it_price'];?>" hp="<? echo $row['it_cust_price'];?>" class="ct_qty" value="" />
<?php
		}
	} // for 끝
?>
						
					</tbody>
				</table>
				
				<!-- <?=$default['de_pg_service']?> -->
				<h5 class="search-result red" style="display:none;">MEMBER NOT FOUND</h5>
				<div class="input-group mb-3 member-search">
					<input type="text" class="form-control" placeholder="Recipient's Username" aria-label="Username" aria-describedby="basic-addon1" value="<?=$member['mb_id']?>" name="searchId" id="searchId" data-i18n="[placeholder]purchase.recipient"  />
					<button class="btn btn-outline-primary" type="button" id="btnSearch"  data-i18n="purchase.search">Search</button>
				</div>

				<div class="total-hash-container">
					<div class="hash-power-calc-container">
						<div class="total-hash-power-container">
							<strong class="hash-desc" >
								BTC
							</strong>
						</div>
						<div class="total-hash-power-container">
							<div class="hash-desc" >
								Current Hash
							</div>
							<div class="hash-amount">
								<span class="hash-number"><span id="btcCurrentHash">0</span> GH/s</span>
							</div>
						</div>
						<div class="total-hash-power-container">
							<div class="hash-desc" >
								Selected Hash
							</div>
							<div class="hash-amount">
								<span class="hash-number">+ <span id="btcSelectedHash">0</span> GH/s</span>
							</div>
						</div>
						<div class="total-hash-power-container">
							<div class="hash-desc" data-i18n="purchase.btc">
								Total Hash
							</div>
							<div class="hash-amount">
								<span class="hash-number"><span id="btcTotalHash">0</span> GH/s</span>
							</div>
						</div>
					</div>

					<div class="hash-power-calc-container">
						<div class="total-hash-power-container">
							<strong class="hash-desc" >
								ETH
							</strong>
						</div>
						<div class="total-hash-power-container">
							<div class="hash-desc" >
								Current Hash
							</div>
							<div class="hash-amount">
								<span class="hash-number"><span id="ethCurrentHash">0</span> MH/s</span>
							</div>
						</div>
						<div class="total-hash-power-container">
							<div class="hash-desc" >
								Selected Hash
							</div>
							<div class="hash-amount">
								<span class="hash-number">+ <span id="ethSelectedHash">0</span> MH/s</span>
							</div>
						</div>
						<div class="total-hash-power-container">
							<div class="hash-desc" data-i18n="purchase.eth">
								Total Hash
							</div>
							<div class="hash-amount">
								<span class="hash-number"><span id="ethTotalHash">0</span> MH/s</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="checkout-container">
					<div class="checkout-left">
						<h4 data-i18n="purchase.select">Select Method of Payment</h4>
						<div id="walletPayment" class="payment-method">
							<img src="images/logo.png" alt="pinnacle" ><br>
							<span data-i18n="purchase.balance" >BALANCE</span>
						</div>
						<div id="btcPayment" class="payment-method selected-payment">
							<img src="images/btc_logo.png" alt="bitcoin" data-i18n="purchase.bitcoin"><br>
							<span data-i18n="purchase.bitcoin" >BITCOIN</span>
						</div>
						<div id="ethPayment" class="payment-method">
							<img src="images/eth_logo.png" alt="ethereum">
							ETHEREUM
						</div>
					</div>

					<div class="checkout-right">
						<span class="gray" data-i18n="purchase.subtotal">Subtotal</span>: <span class="subtotal">$<span id="calcSubtotal">0</span></span><br>
						<input type="button" class="btn btn-primary checkout-button" value="Generate Invoice" data-i18n="[value]purchase.generate" id="generateInvoice" />
						<br>

						<span class="gray exchange-rate"><span class="usd-exchange">USD : <span id="usdBtc">0</span></span> <i class="fas fa-exchange-alt"></i> <span class="btc-exchange" id="btcExchange" >BTC : 0.1234567</span></span><br>
						<span class="gray exchange-rate"><span class="usd-exchange">USD : <span id="usdEth"></span></span> <i class="fas fa-exchange-alt"></i> <span class="btc-exchange" id="ethExchange" >ETH : 0.1234567</span></span>
					</div>
					
				</div>

				<div style="clear:both;"></div>
			</div>				
		</div>
	</div>
</form>
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
