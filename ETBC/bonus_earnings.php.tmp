<?
	// 공통 인클루드 정리 - 작업필요
	include_once('./_common.php'); 
	include_once('./common_head.php');
	include_once('mypage_head.php');
?>

<?
	/*날짜선택 기본값 지정*/
	if (empty($fr_date)) {$fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-1 month"));}
	if (empty($to_date)) $to_date = G5_TIME_YMD;

	/*수당로그계산*/
	$qstr = "stx=".$stx."&fr_date=".$fr_date."&amp;to_date=".$to_date;
	$query_string = $qstr ? '?'.$qstr : '';
	
	if (empty($stx)) $stx = 'daily payout';  // 수당로그 기본값 

	$sql_common ="FROM soodang_pay";
	$sql_search = " WHERE allowance_name = '$stx' ";
	$sql_search .= " AND day between '{$fr_date}' and '{$to_date}' ";
	$sql_search .= "AND mb_id = '{$member[mb_id]}' ";
	
	$sql = " select count(*) as cnt
			{$sql_common}
			{$sql_search} ";
	//print_r($sql);
	$row = sql_fetch($sql);
	$total_count = $row['cnt']; 

	$rows = 30; //한페이지 목록수
	$total_page  = ceil($total_count / $rows);
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지
	$from_record = ($page - 1) * $rows; // 시작 열

	$sql = " select *
			{$sql_common}
			{$sql_search}
			order by day desc
			limit {$from_record}, {$rows} ";
	$result = sql_query($sql);

	//print_R($sql );

	function nav_active($val){
		global $stx;
		
		if($val == $stx) echo "active";

	}
	
	function string_explode($val ){
		$stringArray = explode("member",$val);
		$string1= "<span class='tx1'>".$stringArray[0]." member</span>";
		$string2 = "<span class='tx2'>".$stringArray[1]."</span>";
		return $string1.$string2;
	}	
?>

	<script type="text/javascript">
		

		var mb_balance = '<?=$member["mb_balance"]?>';

		$(document).ready(function () {
			
			/*상단 분류 탭*/
			$('ul.tabs li').click(function () {
				//var tab_id = $(this).attr('data-tab');
				//$('ul.tabs li').removeClass('active');
				//$('.tab-content').removeClass('active');
				//$(this).addClass('active');
				//$("#" + tab_id).addClass('active');
				///console.log( $(this).attr('data-category'));
				search_submit($(this).attr('data-category'));
			});

			/*날짜선택 피커*/
		
			 $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
		})

		
		function search_submit(act = null)
		{
			var f = document.fsearch;
			f.stx.value = act;
			f.submit();
		}
	</script>

	<style>
		
		.pg_wrap{text-align:center;}
		.pg_page, .pg_current{border:1px solid #ccc; padding:5px 10px;color:black}
		.pg_current{background:#f1f1f1}
		.pg_start, .pg_end{display: inline-block !important;
			position: absolute;
			top: 0;
			left: 0;
			margin: 0 !important;
			padding: 0 !important;
			width: 1px !important;
			height: 1px !important;
			font-size: 0;
			line-height: 0;
			border: 0 !important;
			overflow: hidden !important;
		}
		.bonus_tab{cursor:pointer;}
		.bonus_tab p{line-height:13px;}
		#eos-table tbody{width:100%;font-size:14px;}
		#eos-table tbody td{vertical-align:middle;padding:0.5em;}
		#eos-table thead th{text-align:center}
		.tx1{font-size:12px;letter-spacing:-1px;}
		.tx2{display:block;font-size:14px;font-weight:600;}
	</style>
	
	<link rel="stylesheet" href="./css/style.css?v=20181212">
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">

			<!-- 목록  -->
			<div class="bonus-history-title-container">
				<h2 class="gray bonus-history-title" data-i18n="bonus.title2">Bonus Earnings</h2>
				<h5 class="gray total">( <?=$total_count ?> Total )</h5>
			</div>

			
			<div class="bonus-history-container shadow">

				<form name="fsearch" id="fsearch" action="./bonus_earnings.php" method="GET">
					<input type="hidden" name="stx" id="stx" value="">
				

				<!-- 탭 -->
				<ul class="tabs">
					<li class="bonus_tab <?nav_active('daily payout')?>" data-tab="tab_1" data-category="daily payout"><p data-i18n="bonus.tab_1">Daily payout</p></li>
          <li class="bonus_tab <?nav_active('Role Down Recom')?>" data-tab="tab_2" data-category="Role Down Recom"><p data-i18n="bonus.tab_2">Role<br>Down</p></li>          
					<li class="bonus_tab <?nav_active('Binary')?>" data-tab="tab_3" data-category="Binary"><p data-i18n="bonus.tab_3">Binary</p></li>
          <li class="bonus_tab <?nav_active('binary Sponsor')?>" data-tab="tab_4" data-category="binary Sponsor"><p data-i18n="bonus.tab_4">Role<br>Up</p></li>
					<li class="bonus_tab <?nav_active('team')?>" data-tab="tab_5" data-category="team"><p data-i18n="bonus.tab_5">Team</p></li>
				</ul>
				<!-- //탭 -->


				<!-- SEARCH -->
				<div class="search-container">
					<input type="text" id="fr_date" name="fr_date" data-i18n="[placeholder]order.fromDate" placeholder="Date range from" value=<?=$fr_date?> />
					<input type="text" id="to_date" name="to_date" data-i18n="[placeholder]order.toDate" placeholder="Date range to" value=<?=$to_date?> />
					<span class="filter_btn" onclick="search_submit();"><i class="fas fa-search"></i></span>
				</div>
				<!-- //SEARCH -->


				<!-- 보너스 테이블 -->
					<div id="tab_1" class="tab-content active">
						<div id="eos-table" class="bonus-history-table-container active">
							<table class="table table-hover table-responsive">
								<colgroup>
									<col style="width:5%;">
									<col style="width:20%;">
									<col style="width:75%;">
								</colgroup>
								<thead>
									<tr>
										<th>Date</th>
										<th style="width:100px;text-align:right;">EOS</th>
										<th>Subject</th>
									</tr>
								</thead>
								<tbody id="body_content_1">
									<?
									while( $row = sql_fetch_array($result) ){?>
										<tr>
											<td style="font-size:12px;"><?=$row['day']?></td>
											<td style="font-weight:600;text-align:right;width:100px"><?=$row['benefit']?></td>
											<td style="padding-right:0;padding-left:10%">
											<div style="width:100%;letter-spacing:-1px;white-space:normal;">
													<?
														if($stx == "binary Sponsor")
															echo string_explode($row['rec']);
														else{
															echo $row['rec'];
														}
													?>
											</div>
											</td>
										</tr>
									<?}?>
								</tbody>
							</table>
						</div>
					</div>
						<?php
							$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
							echo $pagelist;
						?>
				</form>
			</div>
	</div> <!--// body-wrapper-->
</div><!--// main-container-->
	
<?include_once('./common_tail.php');?>