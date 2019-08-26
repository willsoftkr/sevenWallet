<?php
include_once('./_common.php');

function get_remind($p_date){
	$now = new DateTime();
	$birthday = new DateTime($p_date);
	$diff = $now->diff($birthday);
	return max(1000 - $diff->days , 0);
}

$pool_sql = "SELECT c.*, substring(ct_time,1,10) as p_date FROM g5_shop_cart c LEFT JOIN g5_shop_order o ON c.od_id = o.od_id 
				WHERE c.mb_id = '".$member[mb_id]."' AND o.od_status IN ( '입금', '강제입금') AND c.it_id <> '1515148167' 
					ORDER BY c.ct_price, c.ct_time ASC ";

$package = array('1527096045' => 'package 1', '1527096041' => 'package 2', '1527096037' => 'package 3', '1527096030' => 'package 4', '1526013457' => 'package 5', 'POOL6' => 'package 6', 'POOL7' => 'package 7', 'POOL8' => 'package 8');

$pool_list = sql_query($pool_sql);

$tot_mine = sql_fetch("select sum(benefit) as tot_mine, sum(benefit_usd) as tot_mine_usd from pinna_soodang_mining_pay where mb_id = '{$member[mb_id]}' and allowance_name = 'mining payout (BTC)'");
$iwol = sql_fetch("select sum(benefit) as iwol_btc, sum(benefit_usd) as iwol_usd from soodang_pay where allowance_name='mining payout (btc)' and mb_id='".$member['mb_id']."'");
$rollover_btc = $iwol['iwol_btc'];
$rollover_usd = $iwol['iwol_usd'];
echo "select sum(benefit) as iwol_btc, sum(benefit_usd) as iwol_usd from soodang_pay where allowance_name='mining payout (btc)' and mb_id='".$member['mb_id']."'";
?>
<!doctype html>
<html lang="ko">

<head>
	<?include_once('common_head.php')?>

	<link rel="stylesheet" href="css/mining_btc/style.css?v=20181212">

	<script>
		var packageMap = {
			'1527096045' : 'p1',
			'1527096041' : 'p2',
			'1527096037' : 'p3',
			'1527096030' : 'p4',
			'1526013457' : 'p5',
			'POOL6' : 'p6',
			'POOL7' : 'p7',
			'POOL8' : 'p8'
		};

		function setList(category, pool_count, restPaginnation){
			$('#fsearch [name=category]').val(category);
			$('#fsearch [name=pool_count]').val(pool_count);
			$.ajax({
				type:'GET',
				url:'mining.list.php',
				data: $('#fsearch').serialize() ,
				success: function(data){
					var result = JSON.parse(data);
					// console.log(result);
					var vHtml = $('<div>');
					$.each(result.list, function( index, obj ) {
						var row = $('#clone tr').clone();
						row.find('.date').text(obj.date);
						row.find('.mineDay').text(obj.profit_days);
						row.find('.usd').text('$ ' + obj.benefit_usd);
						row.find('.btc').text(obj.benefit);
						row.find('.exc').text('$ ' + obj.exchange_rate);
						row.find('.balance').text(obj.pool_balance + ' BTC');

						vHtml.append(row);
					});

					$('#' + category + '-table tbody').html(vHtml.html());
					$('#total').val(result.totalPage);
					// $('.gray.total').html('('+result.totalCnt+' Total)');

					if(restPaginnation){
						setPagination(category, pool_count);
						return true;
					}
				},
				error: function(e){
					console.log(e);
				}
			});
		}

		function setPagination(category, pool_count){
			$('#' + category + '-pagination').twbsPagination('destroy');
			if(Number($('#total').val()) > 0){
				$('#' + category + '-pagination').twbsPagination({
					totalPages: Number($('#total').val()),
					visiblePages: 10,
					first: 'Start',
					prev: '<',
					next: '>',
					last: 'End',
					onPageClick: function (event, page) {
						$('#fsearch [name=page]').val(page);
						setList(category, pool_count);
					}
				});
			}
		}

		function setCycle(remainday, pool_count, rel){
			// ui 처리
			$('.package-container .packages li').removeClass('active');
			$('.cycles').hide();
			$('.cycles').removeClass(function(i, c){
				return (c.match (/(^|\s)p\S+/g) || []).join(' ');
			}) // p로 시작하는 클래스 삭제
			$('.cycles').addClass(packageMap[rel]); // pool 스타일 변경 
			$('.cycles .cycle').removeClass('paid').removeClass('ongoing');
			$('.cycles .cycle .big').text(0);

			// 날짜 처리
			var i = 0;
			while(remainday >= 0){
				$('.cycles .cycle').eq(i).find('.big').text(Math.min(200,remainday));
				if(remainday >= 200){
					$('.cycles .cycle').eq(i).addClass('paid');
				}else{
					$('.cycles .cycle').eq(i).addClass('ongoing');
				}
				remainday -= 200;	
				i++;
			}

			// data 처리
			$.ajax({
				type:'GET',
				url:'mining.cycle.php',
				data: {
					"pool_count": pool_count,
					"category": "btc"
				},
				dataType: 'json',
				success: function(data){
					console.log(data);
					$('.cycles .cycle .pay').empty();
					$('.cycles .cycle .pay').each( function( index, obj ) {
						var mine = +data['mine_cycle' + index]; // btc
						var fee = +data['fee_cycle' + index]; // btc
						var tot = mine + fee; 
						var mine_usd = +data['mine_usd_cycle' + index]; // usd
						var fee_usd = +data['fee_usd_cycle' + index]; // usd
						var tot_usd = mine_usd + fee_usd; 
						// console.log(mine_usd);
						$('#clone2 p').eq(0).html('Mining: $ ' +  tot_usd.toFixed(2) + '<br>(' + tot.toFixed(8) + ' BTC)'); //지급량 2$/6$ ...
						$('#clone2 p').eq(1).html('Fee: $ ' + fee_usd.toFixed(2) + '<br>(' + fee.toFixed(8) + ' BTC)');		//수수료
						$('#clone2 p').eq(2).html('Total: $ ' + mine_usd.toFixed(2) + '<br>(' + mine.toFixed(8) + ' BTC)'); //지급량-수수료 total
						$(this).append($('#clone2').html());
						// console.log(obj);
					});
					$('.cycles').fadeIn(100);
				},
				error: function(e){
					console.log(e);
				}
			});
		}

		$(function() {
			// setList('btc',true); // Mining History
			$('.package-container .packages li').on('click', function() {
				var remainday = 1000 - $(this).attr('remainday');
				var pool_count = $(this).attr('pool_count');
				var rel = $(this).attr('rel')
				$(this).addClass('active');
				
				setCycle(remainday, pool_count, rel);
				setList('btc', pool_count, true);
			});
			$('.package-container .packages li').eq(0).trigger('click');
			$( "#toDate" ).datepicker({ dateFormat: 'dd.mm.yy' });
			$( "#fromDate" ).datepicker({ dateFormat: 'dd.mm.yy' });
		});
	</script>
</head>

<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			
			<!-- 목록  -->
			<div class="bonus-history-title-container">
				<h2 class="gray " data-i18n="mining.title_btc" >Bitcoin Mining Summary</h2>
			</div>
			<input type="hidden" id="total" value="">
			<div class="bonus-history-container shadow">
				<div class="total-hashpower">
					<div class="hash-total">
						<span class="gray">Total BTC Mined :</span> <span class="blue"><?=round($tot_mine['tot_mine'],8);?> BTC ($ <?=round($tot_mine['tot_mine_usd'],2);?>)</span>
						<span class="gray">Total Roll over :</span> <span class="blue"><?=round($rollover_btc,8);?> BTC ($ <?=round($rollover_usd,2);?>)</span>

					</div>
				</div>
				<div class="total-hashpower">
					<div class="hash-total">
						<span class="gray" data-i18n="mining.rollorverdes">* Sum of mining earnings before March 3, 2019.</span> 
					
					</div>
				</div>
				<hr>
				<div class="package-container">
					<ul class="packages">
					<?
					$idx = 1;
					while($row_p = sql_fetch_array($pool_list)) {
						if($row_p[it_id] != '1527096053') { 
					?>
						<li rel="<?echo $row_p[it_id]?>" class="active" remainday="<?echo get_remind($row_p['p_date']); ?>" pool_count="<?=$idx++;?>">
							<div class="package">
								<img src="./images/btc_logo.png" width="40" alt="btc icon"><br>
								<span> <?echo $package[$row_p[it_id]]; ?></span><br>
								<span class="remaining-days">[ <?echo get_remind($row_p['p_date']); ?> <span data-i18n="hash.remain" >days remaining</span> ]</span>
							</div>
						</li>
					<?	
						}
					}
					?>
					</ul>
				</div>
				<div class="row cycles" style="display:none;">
					<div class="col">
						<div class="cycle">
							<p>Cycle 1 </p>
							<p><span class="big">0</span>/200 Days</p>
							<div class="pay"></div>
						</div>
					</div>
					<div class="col ">
						<div class="cycle">
							<p>Cycle 2 </p>
							<p><span class="big">0</span>/200 Days</p>
							<div class="pay"></div>
						</div>
					</div>
					<div class="col ">
						<div class="cycle">
							<p>Cycle 3 </p>
							<p><span class="big">0</span>/200 Days</p>
							<div class="pay"></div>
						</div>
					</div>
					<div class="col ">
						<div class="cycle">
							<p>Cycle 4 </p>
							<p><span class="big">0</span>/200 Days</p>
							<div class="pay"></div>
						</div>
					</div>
					<div class="col ">
						<div class="cycle">
							<p>Cycle 5 </p>
							<p><span class="big">0</span>/200 Days</p>
							<div class="pay"></div>
						</div>
					</div>
				</div>
				<form name="fsearch" id="fsearch" action="bonus_earnings.php" method="get">
					
					<!-- 검색바 -->
					<input type="hidden" name="page" value="1">
					<input type="hidden" name="category" >
					<input type="hidden" name="pool_count" >

					<div class="search-container">
						<input type="text" id="fromDate" name="fromDate" data-i18n="[placeholder]order.fromDate" placeholder="Date range from" />
						<input type="text" id="toDate" name="toDate" data-i18n="[placeholder]order.toDate" placeholder="Date range to" />
						&nbsp;&nbsp;&nbsp;
						<span class="filter_btn" onclick="setList($('[name=category]').val(),$('[name=pool_count]').val(),true);"><i class="fas fa-search"></i> <span data-i18n="order.search">Search</span></span>
					</div>

				</form>
				<hr>
				<!-- 비트코인 탭 -->
				<div id="btc-table" class="bonus-history-table-container active">
					<table class="table table-hover table-responsive">
						<colgroup>
							<col style="width:10%;">
							<col style="width:15%;">
							<col style="width:20%;">
							<col style="width:20%;">
							<col style="width:15%;">
							<col style="width:20%;">
						</colgroup>
						<thead>
							<tr>
								<th data-i18n="mining.th1" >Date</th>
								<th data-i18n="mining.th2" >Mining Days</th>
								<th data-i18n="mining.th3" >Daily Earning USD</th>
								<th data-i18n="mining.th4" >Daily Earning BTC</th>
								<th data-i18n="mining.th5" >BTC Price</th>
								<th data-i18n="mining.th6" >Balance</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<!-- 비트코인 페이징 -->
					<div class="pagination-container">
						<ul id="btc-pagination" class="pagination justify-content-center"></ul>
					</div>

				</div>
			</div>
		</div>
	</div>
	<table id="clone" style="display:none;">
		<tbody>
			<tr>
				<td class="date"></td>
				<td class="mineDay"></td>
				<td class="usd"></td>
				<td class="btc"></td>
				<td class="exc"></td>
				<td class="balance"></td>
			</tr>
		</tbody>
	</table>
	<div id="clone2" style="display:none;">
		<p></p>
		<p></p>
		<p></p>
	</div>
</body>
</html>
