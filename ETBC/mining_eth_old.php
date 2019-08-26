<?php
include_once('./_common.php');

$soodang_raw = sql_fetch("select * from soodang_calc where mb_id = '{$member['mb_id']}';");
//$btc_tot_mining = $soodang_raw['mining_btc'] +$soodang_raw['mining_btc2']+$soodang_raw['mining_btc3']+$soodang_raw['mining_btc4'];

$sql = "
select a.day, a.eth_rate, a.eth_difficulty / 1000000000000 as eth_difficulty
from pinna_coin_rate a 
order by day desc limit 7
";

$sth = sql_query($sql);
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
	$rows[] = $r;
}

?>
<!doctype html>
<html lang="ko">

<head>
	<?include_once('common_head.php')?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

	<link rel="stylesheet" href="css/bonus_earnings/style.css?v=20181212">

	<script>

		var dataSet = JSON.parse('<?=json_encode($rows);?>');
		dataSet = _.sortBy(dataSet, [function(o) { return o.day; }]);

		$(function() {
			setList('btc',true); // Mining History

			var chart1 = document.getElementById('priceChart1').getContext('2d');
			var chart2 = document.getElementById('priceChart2').getContext('2d');
			window.priceChart1 = new Chart(chart1, priceChartsConfig1);
			window.priceChart2 = new Chart(chart2, priceChartsConfig2);

			// console.log(priceChart1);
			addData(priceChart1, _.map(dataSet,'day'), _.map(dataSet,'eth_rate'),'US $');
			addData(priceChart2, _.map(dataSet,'day'), _.map(dataSet,'eth_difficulty'), 'Difficulty');
		});

		var priceChartsConfig1 = {
			type: 'line',
			data: {
				//labels: _.map(dataSet,'day'), // x 축 string arr
				labels: [],
				datasets: [{
					label: 'ETH',
					backgroundColor: 'rgb(54, 162, 235)',
					borderColor: 'rgb(54, 162, 235)',
					// data: _.map(dataSet,'btc_rate'), // y축 number arr 
					data: [],
					fill: false,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: false,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: true,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true
					}],
					yAxes: [{
						display: true
					}]
				},
				elements: {
					line: {
						tension: 0
					}
				}
			}
		};

		var priceChartsConfig2 = {
			type: 'line',
			data: {
				//labels: _.map(dataSet,'day'), // x 축 string arr
				labels: [],
				datasets: [{
					label: 'ETH',
					backgroundColor: 'rgb(54, 162, 235)',
					borderColor: 'rgb(54, 162, 235)',
					// data: _.map(dataSet,'btc_rate'), // y축 number arr 
					data: [],
					fill: false,
				}]
			},
			options: {
				responsive: true,
				title: {
					display: false,
					text: 'Chart.js Line Chart'
				},
				tooltips: {
					mode: 'index',
					intersect: true,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true
					}],
					yAxes: [{
						display: true
					}]
				},
				elements: {
					line: {
						tension: 0
					}
				}
			}
		};

		function addData(chart, labelArr, dataArr, tit) {
			chart.data.labels = labelArr;
			chart.data.datasets[0].data = dataArr;
			if(tit) chart.data.datasets[0].label = tit;
			chart.update();
		}

		function setList(category, restPaginnation){
			$('#fsearch [name=category]').val(category);
			$.ajax({
				type:'GET',
				url:'mining.list.php',
				data: $('#fsearch').serialize() + '&category=eth' ,
				success: function(data){
					var result = JSON.parse(data);
					// console.log(result);
					var vHtml = $('<div>');
					$.each(result.list, function( index, obj ) {
						var row = $('#clone tr').clone();
						row.find('.date').text(obj.date);
						row.find('.allo').text(obj.allowance_name);
						row.find('.bene').text(obj.benefit);
						row.find('.rec').text(obj.rec);

						vHtml.append(row);
					});

					$('#' + category + '-table tbody').html(vHtml.html());
					$('#total').val(result.totalPage);
					// $('.gray.total').html('('+result.totalCnt+' Total)');

					if(restPaginnation){
						setPagination(category);
						return true;
					}
				},
				error : function(e){
					console.log(e);
				}
			});
		}

		function setPagination(category){
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
						setList(category);
					}
				});
			}
		}

	</script>
</head>

<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray" data-i18n="mining.title" >ETH Chart - Last 7 Days</h2>
			<div class="container">
				<div class="row">
					<div class="col">
						ETH/USD <br>
						<canvas id="priceChart1" ></canvas>
					</div>
					<div class="col">
						Ethereum Difficulty(x1,000,000,000,000) <br>
						<canvas id="priceChart2" ></canvas>
					</div>
				</div>
			</div>
			<!-- 목록  -->
			<div class="bonus-history-title-container">
				<h2 class="gray bonus-history-title" data-i18n="mining.title2" >ETH Mining History</h2>
				<h5 class="gray total" >( Total Mining ETH - <?=$soodang_raw['mining_eth'];?> )</h5>
			</div>
			<input type="hidden" id="total" value="">
			<div class="bonus-history-container shadow">
				<form name="fsearch" id="fsearch" action="bonus_earnings.php" method="get">
					
					<!-- 검색바 -->
					<input type="hidden" name="page" value="1">
					<input type="hidden" name="category" >

					<div class="search-container">
						<input type="text" id="fromDate" name="fromDate" data-i18n="[placeholder]order.fromDate" placeholder="Date range from" />
						<input type="text" id="toDate" name="toDate" data-i18n="[placeholder]order.toDate" placeholder="Date range to" />
						&nbsp;&nbsp;&nbsp;
						<span class="filter_btn" onclick="setList($('[name=category]').val(),true);"><i class="fas fa-search"></i> <span data-i18n="order.search">Search</span></span>
					</div>

				</form>

				<!-- 비트코인 탭 -->
				<div id="btc-table" class="bonus-history-table-container active">
					<table class="table table-hover table-responsive">
						<colgroup>
							<col style="width:15%;">
							<col style="width:25%;">
							<col style="width:15%;">
							<col style="width:45%;">
						</colgroup>
						<thead>
							<tr>
								<th data-i18n="order.date" >Date</th>
								<th data-i18n="mining.th2" >Bonus Type</th>
								<th data-i18n="mining.th3" >BTC</th>
								<th data-i18n="mining.th6" >Details</th>
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
				<td class="allo"></td>
				<td class="bene"></td>
				<td class="blue rec"></td>
			</tr>
		</tbody>
	</table>
</body>
</html>
