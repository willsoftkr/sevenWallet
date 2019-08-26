<?
	// 공통 인클루드 작업필요
	include_once('./_common.php'); 
	include_once('./common_head.php');

	/*날짜선택 기본값 지정*/
	if (empty($fr_date)) $fr_date = G5_TIME_YMD;
	if (empty($to_date)) $to_date = G5_TIME_YMD;
	
	/*날짜선택 기본값 지정*/
	//$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date;
	//$query_string = $qstr ? '?'.$qstr : '';

	$benefit = "SELECT * FROM soodang_pay WHERE allowance_name = '$gubun' AND mb_id = '$mb_id'";
	//$benefit .= $qstr;

	$result2 = sql_query($benefit);
	
    //print_r($result);
?>

	<link rel="stylesheet" href="css/bonus_earnings/style.css?v=20181212">

	<script type="text/javascript">
		Date.prototype.yyyymmdd = function (spliter) {
			var mm = this.getMonth() + 1; // getMonth() is zero-based
			var dd = this.getDate();
			return [this.getFullYear(), (mm > 9 ? '' : '0') + mm, (dd > 9 ? '' : '0') + dd].join(spliter);
		};

		var mb_balance = '<?=$member["mb_balance"]?>';

		$(document).ready(function () {
			
			/*상단 분류 탭*/
			$('ul.tabs li').click(function () {
				var tab_id = $(this).attr('data-tab');

				$('ul.tabs li').removeClass('active');
				$('.tab-content').removeClass('active');

				$(this).addClass('active');
				$("#" + tab_id).addClass('active');
			});

			/*날짜선택 피커*/
			$("#toDate").datepicker();
			$("#fromDate").datepicker();
		})
	</script>


	<?include_once('mypage_head.php')?>

	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">

			<!-- 목록  -->
			<div class="bonus-history-title-container">
				<h2 class="gray bonus-history-title" data-i18n="bonus.title2">Bonus Earnings</h2>
				<h5 class="gray total">( <?=$total_count;?> Total )</h5>
			</div>

			
			<div class="bonus-history-container shadow">

				<form name="fsearch" id="fsearch" action="./bonus_earnings.php" method="POST">
					<input type="hidden" id="total" value="">
					<input type="hidden" name="page" value="1">
					<input type="hidden" name="category">
				

				<!-- 탭 -->
				<ul class="tabs">
					<li class="bonus_tab active" data-tab="tab_1"><p data-i18n="bonus.tab_1">일배당<br>수당</p></li>
					<li class="bonus_tab" data-tab="tab_2"><p data-i18n="bonus.tab_2"> 추천매칭<br>수당</p></li>
					<li class="bonus_tab" data-tab="tab_3"><p data-i18n="bonus.tab_3">후원<br>수당</p></li>
					<li class="bonus_tab" data-tab="tab_4"><p data-i18n="bonus.tab_4">후원롤업<br>수당</p></li>
					<li class="bonus_tab" data-tab="tab_5"><p data-i18n="bonus.tab_5">팀매칭<br>수당</p></li>
				</ul>
				<!-- //탭 -->


				<!-- SEARCH -->
				<div class="search-container">
					<input type="text" id="fromDate" name="fromDate" data-i18n="[placeholder]order.fromDate" placeholder="Date range from" />
					<input type="text" id="toDate" name="toDate" data-i18n="[placeholder]order.toDate" placeholder="Date range to" />
					<span class="filter_btn" onclick="setList($('[name=category]').val(),true);"><i class="fas fa-search"></i></span>
				</div>
				<!-- //SEARCH -->

				<!-- 보너스 테이블 -->
					<div id="tab_1" class="tab-content active">
						<div id="btc-table" class="bonus-history-table-container active">
							<table class="table table-hover table-responsive">
								<colgroup>
									<col style="width:10%;">
									<col style="width:18%;">
									<col style="width:10%;">
								<col style="width:auto;">
								</colgroup>
								<thead>
									<tr>
										<th>Date</th>
										<th>EOS</th>
										<th data-i18n="bonus.th6">Subject</th>
									</tr>
								</thead>
								<tbody>
									<?
									$bonus_earnings = bonus_earnings('daily payout','');
										while( $row= sql_fetch_array($bonus_earnings) ){
									?>
										<tr>
										<td><?=$row['day']?></td>
										<td><?=$row['benefit']?></td>
										<td><?=$row['rec']?></td>
										</tr>
									<?}?>
								</tbody>
							</table>
						</div>
					</div>

					<div id="tab_2" class="tab-content">
						<div id="btc-table" class="bonus-history-table-container active">
						<table class="table table-hover table-responsive">
									<colgroup>
										<col style="width:10%;">
										<col style="width:18%;">
										<col style="width:10%;">
								<col style="width:auto;">
									</colgroup>
									<thead>
										<tr>
											<th>Date</th>
											<th>EOS</th>
											<th data-i18n="bonus.th6">Subject</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
						</div>
					</div>

					<div id="tab_3" class="tab-content">
						<div id="btc-table" class="bonus-history-table-container active">
							<table class="table table-hover table-responsive">
									<colgroup>
										<col style="width:10%;">
										<col style="width:18%;">
										<col style="width:10%;">
								<col style="width:auto;">
									</colgroup>
									<thead>
										<tr>
											<th>Date</th>
											<th>EOS</th>
											<th data-i18n="bonus.th6">Subject</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
						</div>
					</div>

					<div id="tab_4" class="tab-content">
						<div id="btc-table" class="bonus-history-table-container active">
						<table class="table table-hover table-responsive">
							<colgroup>
								<col style="width:10%;">
								<col style="width:18%;">
								<col style="width:10%;">
								<col style="width:auto;">
							</colgroup>
									<thead>
										<tr>
											<th>Date</th>
											<th>EOS</th>
											<th data-i18n="bonus.th6">Subject</th>
										</tr>
									</thead>
									<tbody>
									<?
									$bonus_earnings = bonus_earnings('Role Down Recom','');
										while( $row= sql_fetch_array($bonus_earnings) ){
									?>
										<tr>
										<td><?=$row['day']?></td>
										<td><?=$row['benefit']?></td>
										<td><?=$row['rec']?></td>
										</tr>
									<?}?>
									</tbody>
								</table>
						</div>
					</div>

					<div id="tab_5" class="tab-content">
						<div id="btc-table" class="bonus-history-table-container active">
						<table class="table table-hover table-responsive">
									<colgroup>
										<col style="width:10%;">
										<col style="width:18%;">
										<col style="width:10%;">
										<col style="width:auto;">
									</colgroup>
									<thead>
										<tr>
											<th>Date</th>
											<th>EOS</th>
											<th data-i18n="bonus.th6">Subject</th>
										</tr>
									</thead>
									<tbody>

									</tbody>
								</table>
							</div>
					</div>
				
				</form>
			</div>
	</div> <!--// body-wrapper-->
</div><!--// main-container-->
	
<?include_once('./common_tail.php');?>