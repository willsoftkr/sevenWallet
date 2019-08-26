<?php
include_once('./_common.php');

function get_remind($p_date){
	$now = new DateTime();
	$birthday = new DateTime($p_date);
	$diff = $now->diff($birthday);
	return 1000 - $diff->days - 10;
}

$mining_pw_sql = "SELECT * FROM pina_mb_hashpower where mb_id='".$member[mb_id]."'";

$pool_sql = "SELECT c.*, substring(ct_time,1,10) as p_date FROM g5_shop_cart c LEFT JOIN g5_shop_order o ON c.od_id = o.od_id 
								 WHERE c.mb_id = '".$member[mb_id]."' AND o.od_status IN ( '입금', '강제입금') ORDER BY c.it_name ASC ";
$hash_arr = array();

$package = array('1527096045' => 'package 1', '1527096041' => 'package 2', '1527096037' => 'package 3', '1527096030' => 'package 4', '1526013457' => 'package 5',  'VVIP112500'=>'VVIP 1','VVIP225000'=>'VVIP 2' , 'VVIP337500'=>'VVIP 3', 'VVIP450000'=>'VVIP 4', '1515148167' => 'package GPU') ;

$package_name = array('1527096045' => 'package1', '1527096041' => 'package2', '1527096037' => 'package3', '1527096030' => 'package4', '1526013457' => 'package5',  'VVIP112500'=>'VVIP1','VVIP225000'=>'VVIP 2' , 'VVIP337500'=>'VVIP 3', 'VVIP450000'=>'VVIP 4', '1515148167' => 'package-gpu') ;

$repurchase = array('1527096045' => 'p1_repurchase', '1527096041' => 'p2_repurchase', '1527096037' => 'p3_repurchase', '1527096030' => 'p4_repurchase', '1526013457' => 'package 5',  'VVIP112500'=>'VVIP 1','VVIP225000'=>'VVIP 2' , 'VVIP337500'=>'VVIP 3', 'VVIP450000'=>'VVIP 4', '1515148167' => 'pg_repurchase') ;

$item_no = array('1527096045' => 'item1', '1527096041' => 'item2', '1527096037' => 'item3', '1527096030' => 'item4', '1526013457' => 'item5',  'VVIP112500'=>'VVIP 1','VVIP225000'=>'VVIP 2' , 'VVIP337500'=>'VVIP 3', 'VVIP450000'=>'VVIP 4', '1515148167' => 'item_gpu') ;


$pool_list = sql_query($pool_sql);
$mining_pw = sql_fetch($mining_pw_sql );
$hash_arr = array();
$I=0;

if($mining_pw[pool1_hashp])
{
	
}
if($mining_pw[pool2_hashp])
{
	$hash_arr[$I++] = $mining_pw[p2_repurchase];
}
if($mining_pw[pool3_hashp])
{
	$hash_arr[$I++] = $mining_pw[p3_repurchase];
}
if($mining_pw[pool4_hashp])
{
	$hash_arr[$I++] = $mining_pw[p4_repurchase];
}

$set_init = "SELECT mb_id, sum(re_purchase_pool1), sum(re_purchase_pool2), sum(re_purchase_pool3), sum(re_purchase_pool4), sum(re_purchase_pool5), 
sum(re_purchase_poolg) FROM `pina_mining_profit` WHERE 1 and mb_id='".$member[mb_id]."' ";
sql_fetch($set_init);

$get_now = "select * from pina_mb_hashpower where mb_id = '".$member[mb_id]."' ";
$rst_now_hash = sql_fetch($get_now);
$hash = $rst_now_hash[pool1_hashp];
$hash2 = $rst_now_hash[pool2_hashp];
$hash3 = $rst_now_hash[pool3_hashp];
$hash4 = $rst_now_hash[pool4_hashp];
$hash5 = $rst_now_hash[pool5_hashp];
$hashg = $rst_now_hash[pool_gpu_hashp];
$get_list = "SELECT profit_date, mb_id, re_purchase_pool1, re_purchase_pool2, re_purchase_pool3, re_purchase_pool4, re_purchase_pool5, 
re_purchase_poolg FROM `pina_mining_profit` WHERE 1 and mb_id='".$member[mb_id]."' and profit_date >='2018-12-05' order by profit_date desc";


$next_hash = 0;
?>
<!DOCTYPE html>
<html>
<head>
		<?include_once('common_head.php')?>
		<link rel="stylesheet" href="css/hash_power/style.css?v=20190107">

	<script>
		$(function() {
			$('.package-panels .packages li').on('click', function() {
				$('.package-panels .packages li.active').removeClass('active');
				$(this).addClass('active');

				var packageToShow = $(this).attr('rel');
				
				$('.package-panels .package-panel.active').fadeOut(300, function() {
					$(this).removeClass('active');

					$('#' + packageToShow).fadeIn(300, function() {
						$(this).addClass('active');
					});
				});

			});
			$('#save_btn').on('click', function() {
				alert($("#item1 option:selected").val());
				$.ajax({
					type: "POST",
					url: "hash_repurchace.u.php",
					cache: false,
					async: false,
					dataType: "text",
					data:  {
						repool1 : $("#item1 option:selected").val(),
						repool2 : $("#item2 option:selected").val(),
						repool3 : $("#item3 option:selected").val(),
						repool4 : $("#item4 option:selected").val(),
						repool5 : $("#item5 option:selected").val(),
						repool6 : $("#item6 option:selected").val(),
						repool7 : $("#itemvvip option:selected").val(),
						repool_gpu : $("#item_gpu option:selected").val()
					},
					success: function(data) {
							//alert(data);
						$('#exampleModalCenter').modal('show');
						
					}
				});
			});

		});
	</script>
</head>

<body>

	<?include_once('mypage_head.php')?>
	<div class="main-container">		
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray">Hash Power</h2>
			<div class="hash-power-container shadow">
				<div class="total-hashpower">
					<div class="hash-total">
						<span class="gray">Total BTC Hashpower:</span> <span class="blue"><?echo $mining_pw[pool1_hashp]+$mining_pw[pool2_hashp]+$mining_pw[pool3_hashp]+$mining_pw[pool4_hashp]+$mining_pw[pool5_hashp]?> GH/s</span>
					</div>
					<div class="hash-total">
						<span class="gray">Total ETH Hashpower:</span> <span class="blue"><?echo $mining_pw[pool_gpu_hashp]?> MH/s</span>
					</div>
				</div>	
				<hr>
				<div class="package-panels">
					<div class="package-container">
						<ul class="packages">
						<form>
						<?while($row_p = sql_fetch_array($pool_list)) {
							if($row_p[it_id]=='1527096053') {}else{?>
							<li rel="<?echo $package_name[$row_p[it_id]]?>" class="active">
								<div class="package">
									<img src="./images/btc_logo.png" width="40" alt="btc icon"><br>
									<span> <?echo $package[$row_p[it_id]]; ?></span><br>
									<span class="remaining-days">[ <?echo get_remind($row_p['p_date']); ?> days remaining ]</span>
								</div>
								<div class="adjust-repurchase-rate">
									<select class="custom-select" id="<?echo $item_no[$row_p[it_id]]?>">
							
										<?for($i=1;$i<=10; $i++){
												if($i*10 == $mining_pw[ $repurchase[$row_p[it_id]]]){?>
													<option selected value="<?echo $i*10;?>"> <?echo $i*10;?>%</option>
												<?}else{?>
												<option value="<?echo $i*10;?>"><?echo $i*10;?>%</option>
											<?}?>
										<?}?>

									</select>
									<p><em>Repurchase Rate</em></p>
								</div>
							</li>

							<?}}?>
						</form>
						</ul>
					</div>
					<button type="button" class="save-button" id="save_btn">Save Changes</button>
					<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLongTitle">HASH POWER</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<i class="far fa-check-circle blue"></i>
									<h5>Your settings have been successfully saved.</h5>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>


					<div id="package1" class="package-panel active">
						<!--div class="date-filter">
							<input type="text" placeholder="Date range from">
							<input type="text" placeholder="Date range to">
							<button>Search</button>
						</div-->
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Date</th>

									<th>Initial Hashpower</th>
									<th>Added Hash Power</th>
									<th>Package 1 Total Hash Power</th>
								</tr>
							</thead>
							<tbody>
							<?$rest_list = sql_query($get_list);
							while($row= sql_fetch_array($rest_list)){

								?>
								<tr>
									<th scope="row"><?=substr($row['profit_date'],0,10)?></th>

									<td><? echo $hash-  $row[re_purchase_pool1] ;  ?> GH/s</td>
									<td><?= number_format(round($row[re_purchase_pool1],2),2)?> GH/s</td>
									<td><span><?echo number_format(round($hash,2),2); $hash = $hash -  $row[re_purchase_pool1];?> GH/s</span></td>
								</tr>
								<?}?>
							</tbody>
						</table>
						<div class="pagination-container">
							<nav aria-label="...">
								<ul class="pagination pagination justify-content-center">

								</ul>  
							</nav>					
						</div>
			<div class="page-search-container">
					
			</div>  
					</div>

					<div id="package2" class="package-panel">

						<table class="table table-hover">
							<thead>
								<tr>
									<th>Date</th>

									<th>Initial Hashpower</th>
									<th>Added Hash Power</th>
									<th>Package 2 Total Hash Power</th>
								</tr>
							</thead>
							<tbody>
								<?$rest_list = sql_query($get_list);
								while($row= sql_fetch_array($rest_list)){

									?>
								<tr>
									<th scope="row"><?=substr($row['profit_date'],0,10)?></th>

									<td><? echo $hash2-  $row[re_purchase_pool2]; ?> GH/s</td>
									<td><?=$row[re_purchase_pool2]?> GH/s</td>
									<td><span><?echo $hash2;$hash2 = $hash2 -  $row[re_purchase_pool2] ; ?> GH/s</span></td>
								</tr>
									<?}?>
							</tbody>
						</table>
						<div class="pagination-container">
							<nav aria-label="...">

							</nav>					
						</div>
						<div class="page-search-container">
				
						</div>
					</div>

					<div id="package3" class="package-panel">

						<table class="table table-hover">
							<thead>
								<tr>
									<th>Date</th>

									<th>Initial Hashpower</th>
									<th>Added Hash Power</th>
									<th>Package 3 Total Hash Power</th>
								</tr>
							</thead>
							<tbody>
							<?$rest_list = sql_query($get_list);
							while($row= sql_fetch_array($rest_list)){

								?>
								<tr>
									<th scope="row"><?=substr($row['profit_date'],0,10)?></th>

									<td><? echo $hash3-  $row[re_purchase_pool3]; $hash3 = $hash3 -  $row[re_purchase_pool3] ?> GH/s</td>
									<td><?=$row[re_purchase_pool3]?> GH/s</td>
									<td><span><?echo $hash3 ?> GH/s</span></td>
								</tr>
								<?}?>
							</tbody>
						</table>
						<div class="pagination-container">
							<nav aria-label="...">

							</nav>					
						</div>
						<div class="page-search-container">
			
						</div>
					</div>

					<div id="package4" class="package-panel">

						<table class="table table-hover">
							<thead>
								<tr>
									<th>Date</th>

									<th>Initial Hashpower</th>
									<th>Added Hash Power</th>
									<th>Package 4 Total Hash Power</th>
								</tr>
							</thead>
							<tbody>
								<?$rest_list = sql_query($get_list);
							while($row= sql_fetch_array($rest_list)){
							 ?>
								<tr>
									<th scope="row"><?=substr($row['profit_date'],0,10)?></th>

									<td><? echo $hash4-  $row[re_purchase_pool4];  ?> GH/s</td>
									<td><?=$row[re_purchase_pool3]?> GH/s</td>
									<td><span><?echo $hash4 ; $hash4 = $hash4 -  $row[re_purchase_pool4]?> GH/s</span></td>
								</tr>
							<?}?>
							</tbody>
						</table>
						<div class="pagination-container">
							<nav aria-label="...">

							</nav>					
						</div>
						<div class="page-search-container">
					
						</div>
					</div>

					<div id="package5" class="package-panel">

						<table class="table table-hover">
							<thead>
								<tr>
									<th>Date</th>

									<th>Initial Hashpower</th>
									<th>Added Hash Power</th>
									<th>Package 5 Total Hash Power</th>
								</tr>
							</thead>
							<tbody>
								<?$rest_list = sql_query($get_list);
							while($row= sql_fetch_array($rest_list)){
							 ?>
								<tr>
									<th scope="row"><?=substr($row['profit_date'],0,10)?></th>

									<td><? echo $hash5 -  $row[re_purchase_pool5] ;  ?> GH/s</td>
									<td><?=$row[re_purchase_pool5]?> GH/s</td>
									<td><span><?echo $hash5; $hash5 = $hash5 -  $row[re_purchase_pool5] ;?> GH/s</span></td>
								</tr>
							<?}?>
							</tbody>
						</table>
						<div class="pagination-container">
							<nav aria-label="...">
								<ul class="pagination pagination justify-content-center">

								</ul>
							</nav>					
						</div>
						<div class="page-search-container">
				
						</div>
					</div>

					<div id="package-gpu" class="package-panel">

						<table class="table table-hover">
							<thead>
								<tr>
									<th>Date</th>

									<th>Initial Hashpower</th>
									<th>Added Hash Power</th>
									<th>GPU Total Hash Power</th>
								</tr>
							</thead>
							<tbody>
								<?$rest_list = sql_query($get_list);
							while($row= sql_fetch_array($rest_list)){
							 ?>
								<tr>
									<th scope="row"><?=substr($row['profit_date'],0,10)?></th>

									<td><? echo $hashg -  $row[re_purchase_poolg]; ?> MH/s</td>
									<td><?=$row[re_purchase_poolg]?> MH/s</td>
									<td><span><?echo $hashg; $hashg = $hashg  -  $row[re_purchase_poolg]?> MH/s</span></td>
								</tr>
							<?}?>
							</tbody>
						</table>
						<div class="pagination-container">
							<nav aria-label="...">
								<ul class="pagination pagination justify-content-center">

								</ul>
							</nav>					
						</div>
						<div class="page-search-container">
			
						</div>
					</div>
				</div>
			</div>				
		</div>
	</div>
</body>
</html>