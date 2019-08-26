<!doctype html>
<html lang="ko">
<head>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.35/dist/web3.min.js"></script>
<script src="/new/js/ethereumjs-tx-1.3.3.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.js"></script>

<script>
	var addr = '';
	var order_id = '';
	var balance = '';

	function check_deposit(addr, order_id) {
		
		web3 = new Web3(new Web3.providers.HttpProvider("https://mainnet.infura.io/"));
		web3.eth.getBalance(addr).then(res => {    
			balance = res;
			$.ajax({
				type: "POST",
				url: "order_progress_eth.php",
				cache: false,
				async: false,
				dataType: "json",
				data:  {
					balance : balance,
					order_id : order_id
				},
				success: function(data) {
						
				}
			});

		});
	}

</script>
<?php 
include_once('/home/sdevftv/html/common.php');
include_once('/home/sdevftv/html/lib/mailer.lib.php');
if(true){
	
	$get_orderList = "select od_id, mb_id, od_chkcnt ,od_status from g5_shop_order where od_status='주문' ";
	$list_rst = sql_query($get_orderList);
	for($rp=0; $list_row = sql_fetch_array($list_rst) ; $rp++){ 
		$failcnt = $list_row['od_chkcnt'];
		$od_id = $list_row['od_id'];
		$sql = " select mb_id, ct_price, ct_qty, it_id, it_name, ct_send_cost, it_sc_type from {$g5['g5_shop_cart_table']} where od_id = '$od_id' group by it_id order by ct_id ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++){
			$total_price = $total_price + $row['ct_price']* $row['ct_qty'];
				$mb_id = $row['mb_id'];
			if($row['ct_price']!=99)
				$total_pv = $total_pv + $row['ct_price']* $row['ct_qty'];
		}
		$now_date = date("Y-m-d H:i:s",time()); 
		$ch = curl_init();

		$sel_id = "select eth_addr, eth_key from g5_member where mb_id='$mb_id';";
		$rst = sql_query($sel_id);
		$w_rst = sql_fetch_array($rst);	
		$eth_wallet = $w_rst['eth_addr'];
		$private_key = $w_rst['eth_key'];

		echo ("<script language=javascript> check_deposit('$eth_wallet','$od_id');</script>");

		
	}
}
?>
</head>
</html>
