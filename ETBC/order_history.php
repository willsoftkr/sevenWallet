<?php
include_once('./_common.php');

	$sql = "Select count(*) as cnt from ".$g5['g5_shop_order_table']." where mb_id ='".$member[mb_id]."' and od_status in('입금','강제입금','재구매')";
	if($od_settle_case){
		$sql .= " and od_settle_case ='".$od_settle_case."'";
	}
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
	$total_count2 = $row['cnt'];

	$rows = 10;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = "SELECT ord.* , pbr.* FROM g5_shop_order ord LEFT JOIN pinna_btc_rate pbr ON SUBSTRING( ord.od_receipt_time, 1, 10 ) = pbr.day WHERE ord.mb_id =  '".$member[mb_id]."' AND ord.od_status IN ('입금',  '강제입금',  '재구매')";

	if($od_settle_case){
		$sql .= " and od_settle_case ='".$od_settle_case."'";
	}
	$sql .= " order by od_receipt_time desc ";
	$sql .= " limit {$from_record}, {$rows} ";
	$list = sql_query($sql);

	$sql_hash = "SELECT * from pina_mb_hashpower where mb_id='".$member[mb_id]."'";
	$hash_row = sql_fetch($sql_hash);

	$total_repurchase = "select sum(od_cart_price) as rehap from g5_shop_order where od_status ='재구매' and mb_id ='".$member[mb_id]."'";
	$re_rst = sql_fetch($total_repurchase);

	$total_btc_hash = "select pool1_hashp + pool2_hashp+pool3_hashp+pool4_hashp+pool5_hashp as hap from pina_mb_hashpower where mb_id='".$member[mb_id]."'";
	$btc_hash = sql_fetch($total_btc_hash);
	$total_eth_hash = "select pool_gpu_hashp from pina_mb_hashpower where mb_id='".$member[mb_id]."'";
	$eth_hash = sql_fetch($total_eth_hash);
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/orderhistory/style.css">

	<script>
		$(function() {
			var poolPackage = document.getElementsByClassName('pool-package');

			$('.pool-package').on('click', function() {
				$(this).addClass('active-package').siblings().removeClass('active-package');
			});

			$( "#toDate" ).datepicker();
			$( "#fromDate" ).datepicker();

			$('#search_btn').on('click',function(){
				
			});
		});
	</script>

</head>
<body>

	<?include_once('mypage_head.php')?>

	<div class="main-container">

		<div id="body-wrapper" class="big-container-wrapper">
			<div class="title-container">
				<h2 class="gray order-history-title">Order History</h2> <span class="gray">( <?echo $total_count2?> Total )</span>
				<div class="date-filter">
					<input type="text"  id="fromDate" placeholder="Date range from">
					<input type="text" id="toDate" placeholder="Date range to">
					<button id="search_btn">Search</button>
				</div>
			</div>
			<div class="order-history-container shadow">
					<div class="pool-package-container">
					<div id="pool1" class="pool-package active-package">
					<a href='./order_history.php?od_settle_case=P1'>	<img src="./images/package_1.png" width="50" alt="package 1" /></a>
						<br>
						<?echo $hash_row[pool1_hashp];?> GH/s
					</div>
					<div id="pool2" class="pool-package">
						<a href='./order_history.php?od_settle_case=P2'> <img src="./images/package_2.png" width="50" alt="package 2" /></a>
						<br>
						<?echo $hash_row[pool2_hashp];?> GH/s
					</div>
					<div id="pool3" class="pool-package">
						<a href='./order_history.php?od_settle_case=P3'> <img src="./images/package_3.png" width="50" alt="package 3" /></a>
						<br>
						<?echo $hash_row[pool3_hashp];?> GH/s
					</div>
					<div id="pool4" class="pool-package">
						<a href='./order_history.php?od_settle_case=P4'> <img src="./images/package_4.png" width="50" alt="package 4" /></a>
						<br>
						<?echo $hash_row[pool4_hashp];?> GH/s
					</div>
					<div id="gpu" class="pool-package">
						<a href='./order_history.php?od_settle_case=GPU'> <img src="./images/package_gpu.png" width="50" alt="package gpu" /></a>
						<br>
						<?echo $hash_row[pool_gpu_hashp];?> MH/s
					</div>
				</div>

				<div class="order-history-table-container">
					<table class="table table-hover">
					  <tr>
					    <th>
					    	Invoice
					    </th>
					    <th>
					    	Date
					  	</th>
					    <th>
					    	Ordered
					    </th>
					    <th>
					    	Paid BTC
					    </th>
					    <th>
					    	Paid for
					    </th>
					  </tr>
					  <tr>
					  <?for($i=0; $row = sql_fetch_array($list);$i++){?>
					    <td><?echo $row[od_id];?></td>
					    <td><?echo substr($row[od_receipt_time],0,10);?></td>
					    <td>$ <?echo $row[od_cart_price];?></td>
					    <td><?echo sprintf("%.8f",$row[od_cart_price]/$row[btc_rate]);?></td>
						
						<?if($row[od_status]=='재구매'){?>
						    <td>Pool Repurchase</td>
						<?}else{?>
						    <td>Pool Purchase</td>
						<?}?>
					  </tr>
					  <?}?>
					</table>
				</div>

				<div class="order-history-stats-container">
					<div class="single-stat-container">
						<img src="./images/total_repurchases.png" width="55" alt="total repurchases" />
						<br>
						<span class="big-blue-stat-font">$<?echo round($re_rst[rehap],2)?></span>
						<br>
						<span class="gray">Total Repurchases</span>
					</div>

					<div class="single-stat-container">
						<img src="./images/current_hash_power.png" width="55" alt="total hash power" />
						<br>
						<span class="big-blue-stat-font"><?echo $btc_hash[hap]?>GH/S <?echo $eth_hash[pool_gpu_hashp]?>MH/S</span>
						<br>
						<span class="gray">Total Hash Power</span>
					</div>

					<div class="single-stat-container">
						<img src="./images/total_earned.png" width="55" alt="total earned" />
						<br>
						<span class="big-blue-stat-font">Preparing...</span>
						<br>
						<span class="gray">Total Earned</span>
					</div>
				</div>

				<div class="pagination-container">

				<?php
					$pagelist = get_paging_new($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'od_settle_case='.$od_settle_case.'&amp;page=');
					if ($pagelist) {
						echo $pagelist;
					}
				?>
				</div>

			  <div class="page-search-container">
				  <input id="page_chk" class="search-input" type="number" placeholder="Page">
				  <button id="page_search">Search</button>
			  </div>
			</div>
		</div>

	</div>
</body>
</html>
