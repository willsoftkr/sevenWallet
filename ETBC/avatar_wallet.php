<?php
include_once('./_common.php');

$soodang_raw = sql_fetch("select * from soodang_calc where mb_id = '{$member['mb_id']}';");
$btc_tot_mining = $soodang_raw['mining_btc'] +$soodang_raw['mining_btc2']+$soodang_raw['mining_btc3']+$soodang_raw['mining_btc4'];

$cost = sql_fetch("select * from coin_cost");

$list_avatar = sql_query("select * from avatar");

$package_biggest = sql_fetch("select * from g5_shop_item 
where it_price = (
	SELECT max(ct_price) FROM g5_shop_cart c 
		LEFT JOIN g5_shop_order o ON c.od_id = o.od_id 
		 WHERE c.mb_id = 'coolrunning' AND o.od_status IN ( '입금', '강제입금')
) and it_id not like 'VVIP%' and it_name not like '%GPU%' limit 1");

?>
<!doctype html>
<html lang="ko">

<head>
	<?include_once('common_head.php')?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

	<link rel="stylesheet" href="css/avatar_wallet/style.css?v=20181212">

	<script>
		var avatar_rate = Number('<?=$member['avatar_rate'];?>');

		// 리스트형태 목록 불러오기 
		function setList(category, restPaginnation){
			$('#fsearch [name=category]').val(category);
			$.ajax({
				type:'GET',
				url:'avatar_wallet.list.php',
				data: $('#fsearch').serialize() ,
				success: function(data){
					// console.log(data);
					var result = JSON.parse(data);
					// console.log(result);
					var vHtml = $('<div>');
					$.each(result.list, function( index, obj ) {
						var row = $('#clone tr').clone();
						row.find('.date').text(obj.date);
						row.find('.ava').text(obj.avatar_rate + ' %');
						row.find('.usd').text('$ ' + obj.benefit_usd);
						row.find('.bene').text(obj.benefit);
						row.find('.exc').text('$ ' +obj.exchange_rate);

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

		// 페이징 처리
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
		
		// 아바타 리스트
		function setAvList(){
			$.ajax({
				type: 'GET',
				url: 'avatar_wallet.av.php',
				data: {},
				dataType: 'json',
				success: function(data){
					// console.log(data);
					var vHtml = $('<div>');
					$.each(data, function( index, obj ) {
						var av = $('#clone2 .avatar').clone();
						av.find('.name').text('Avatar ' + (index+1));
						av.find('.username').text(obj.mb_id);
						av.find('.created').text(obj.create_date);
						av.find('.spent').text(obj.spend_btc + ' BTC');
						vHtml.append(av);
					});
					$('#list-avatar').html(vHtml);
				},
				error: function(e){
					console.log(e);
				}
			});
		}

		$(function() {
			setAvList();
			setList('btc',true); // Mining History
			$('.package-container .packages li').on('click', function() {
				$('.package-container .packages li').removeClass('active');
				$(this).addClass('active');
				$('.cycles .cycle .fr').text($(this).find('span').eq(0).text());
				$('.cycles').hide().fadeIn(100);
			});
			$( "#toDate" ).datepicker({ dateFormat: 'dd.mm.yy' });
			$( "#fromDate" ).datepicker({ dateFormat: 'dd.mm.yy' });

			$('#btnSave').on('click', function(e) {
				$.ajax({
					type:'POST',
					url:'avatar_wallet.u.php',
					data: {
						avatar_rate : $('#avatar_rate').val()
					},
					dataType: 'json',
					success: function(data){
						// console.log(data);
						$('#saveSuc').show().delay(500).fadeOut();
					},
					error: function(e){
						console.log(e);
					}
				});
				$('#avatar_rate').val(); // 멤버에 저장
			});
			$('#avatar_rate').val(avatar_rate); // 멤버에 저장
		});
	</script>
</head>

<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			
			<!-- 목록  -->
			<div class="bonus-history-title-container">
				<h2 class="gray " data-i18n="avatar.title" >Avatar Wallet Status</h2>
			</div>
			<input type="hidden" id="total" value="">
			<div class="bonus-history-container shadow">
				<div class="total-hashpower">
					<div class="hash-total">
						<span class="gray" data-i18n="avatar.target" >Your target:</span> <span class="blue"><?=str_replace('Bitcoin Mining ', '', $package_biggest['it_name'])?> ($ <?=$package_biggest['it_price']?>)</span>
					</div>
					<div class="hash-total">
						<span class="gray" data-i18n="avatar.ach" >Achieved:</span> <span class="blue"><?=round($member['it_avatar_profit'] * $cost['btc_cost'] / $package_biggest['it_price'] * 100, 2) ?> %</span>
					</div>
				</div>
				<div class="total-hashpower">
					<div class="hash-total">
						<span class="gray" data-i18n="avatar.to_usd" >Total savings (USD):</span> <span class="blue">$ <?=round($member['it_avatar_profit'] * $cost['btc_cost'], 2)?></span>
					</div>
					<div class="hash-total">
						<span class="gray" data-i18n="avatar.to_btc" >Total savings (BTC):</span> <span class="blue"><?=$member['it_avatar_profit']?></span>
					</div>
				</div>
				<hr>
				<div class="hash-total">
					<h3 class="gray" data-i18n="avatar.list_avatar" >Your avatar list</h3>
				</div>
				<div class="total-hashpower" id="list-avatar" >
					
				</div>
				<hr>
				<div class="package-container">
					<ul class="packages">
						<li rel="package1" class="active">
							<div class="package">
								<img src="images/userplus2.png" width="80" alt="avatar icon"><br>
								<span data-i18n="avatar.savings" >Avatar Savings</span>
							</div>
							<div class="adjust-repurchase-rate">
								<select class="custom-select" id="avatar_rate">
									<option value="0" selected >0%</option>
									<option value="25" >25%</option>
									<option value="50" >50%</option>
									<option value="75" >75%</option>
									<option value="100" >100%</option>
								</select>
								<p><em data-i18n="avatar.rate">Savings Rate</em></p>
							</div>
						</li>
					</ul>
					<button type="button" class="save-button" id="btnSave" data-i18n="avatar.save" >Save Change</button>
					<p id="saveSuc">Sucess</p>
				</div>
				
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
				<hr>
				<!-- 비트코인 탭 -->
				<div id="btc-table" class="bonus-history-table-container active">
					<table class="table table-hover ">
						<colgroup>
							<col style="width:20%;">
							<col style="width:20%;">
							<col style="width:20%;">
							<col style="width:20%;">
							<col style="width:20%;">
						</colgroup>
						<thead>
							<tr>
								<th data-i18n="avatar.th1" >Date</th>
								<th data-i18n="avatar.th2" >Saving Rate</th>
								<th data-i18n="avatar.th3" >Saved USD</th>
								<th data-i18n="avatar.th4" >Saved BTC</th>
								<th data-i18n="avatar.th5" >Current Balance</th>
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
				<td class="ava"></td>
				<td class="usd"></td>
				<td class="blue bene"></td>
				<td class="blue exc"></td>
			</tr>
		</tbody>
	</table>
	<div id="clone2" style="display:none;">
		<div class="hash-total avatar">
			<p class="name" ><span data-i18n="avatar.avatar" >Avatar</span> 1</p>
			<img src="images/userplus.png" style="width:110px;" class="img" >
			<div class="txt">
				<p class="gray" data-i18n="avatar.username" >Username:</p>
				<p ><span class="username">myavatar1</span></p>
				<p class="gray"><span data-i18n="avatar.created">Created:</span> <span class="created">3/15/2019</span></p>
				<p class="gray"><span data-i18n="avatar.spent">Spent:</span> <span class="spent">0.292522BTC</span></p>
			</div>
		</div>
	</div>
</body>
</html>
