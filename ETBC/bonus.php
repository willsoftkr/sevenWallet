<?php
include_once('./_common.php');

if($_GET['coin'] == 'ETH'){
	$cls = 'eth';
	$coin = 'ETH';
}else{
	$cls = 'bit';
	$coin = 'BTC';
}
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8" />
	<title>FIJI MINING</title>
	
	<meta property="og:type" content="article" />
	<meta property="og:title" content="FIJI" />
	<meta property="og:url" content="" />
	<meta property="og:description" content="." />
	<meta property="og:site_name" content="" />
	<meta property="og:image" content="FIJI Mining Have" />
	<meta property="og:image:width" content="800" />
	<meta property="og:image:height" content="400" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- css연결 -->
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/adm.css">
	<link rel="stylesheet" href="css/bonus.css">
	
	<!-- jQuery 연결 -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/adm.js"></script>

<script>
$(function(){
	$('.con_menu .menu').on('click',function(e){
		location.href = location.pathname + "?menu=" + $(this).attr('type') + "&coin=" + "<?=$coin;?>"+ "&startDate=" + "<?=$_GET['startDate'];?>" + "&endDate=" + "<?=$_GET['endDate'];?>";
	});
	$('.tabs .btn').on('click',function(e){
		location.href = location.pathname + "?coin=" + $(this).attr('coin');
	});
	$("#startDate, #endDate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	$('#btnSearch').on('click',function(e){
		location.href = location.pathname + "?menu=" + "<?=$_GET['menu'];?>" + "&coin=" + "<?=$coin;?>"+ "&startDate=" + $('#startDate').val() + "&endDate=" + $('#endDate').val();
	});
});

</script>
</head>
<body>
<?php
	include_once('./mypage_head.php');
	include_once('./mypage_left.php');

	if($coin == 'ETH'){
		$cls = 'eth';
	}else{
		$cls = 'bit';
	}

	if($_GET['menu']){
		$sql_search = " and allowance_name = '".$_GET['menu']."'";
	}
	if($coin == 'ETH'){
		$sql_search .= " and rec like '%ETH%' ";
	}else{
		$sql_search .= " and rec like '%BTC%' ";
	}

	if($_GET['startDate']){
		$sql_search .= " and day >= '".$_GET['startDate']."' ";
	}

	if($_GET['endDate']){
		$sql_search .= " and day <= '".$_GET['endDate']."' ";
	}

	$sql = " select count(*) as cnt from soodang_pay  ";
	$sql .= " WHERE mb_id = '".$member['mb_id']."' $sql_search";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];
	$rows = 12;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함
?>
	<div id="content" >
		<h3>Bonus History</h3>
		<div class="tabs <?=$cls;?>">
			<div class="btn" coin="BTC"><span>Bitcoin</span></div>
			<div class="btn" coin="ETH"><span>Ethereum</sapn></div>
			<div ></div>
		</div>
		<div class="infoBx">
			<div class="con_avg <?=$cls;?>">
				<div class="box">
					<div class="up">Balance</div>
					<div class="bottom">0.0000000 BTC</div>
				</div>
				<div class="box">
					<div class="up">Last 7 Days</div>
					<div class="bottom">0.0000000 BTC</div>
				</div>
				<div class="box">
					<div class="up">Last 7 Days</div>
					<div class="bottom">0.0000000 BTC</div>
				</div>
				<div class="box center">
					<div class="up">Mining Balance</div>
					<div class="bottom">0.0000000 BTC</div>
				</div>
				<div class="box">
					<div class="up">Mining 7 Days</div>
					<div class="bottom">0.0000000 BTC</div>
				</div>
				<div class="box">
					<div class="up">Re-purchased</div>
					<div class="bottom">0.0000000 BTC</div>
				</div>
				<div class="box">
					<div class="up">Total Hash Power</div>
					<div class="bottom">0.0000000 GH/s</div>
				</div>
			</div>
			<div class="con_menu">Menu: 
				<?

					$benefit = "SELECT allowance_name FROM soodang_pay GROUP BY allowance_name ORDER BY DAY DESC";
					$rrr = sql_query($benefit);
					if(!$_GET['menu']){
						$html .= "<span class='menu on' type=''>All</span>";
					}else{
						$html .= "<span class='menu' type=''>All</span>";
					}
					for ($i=0; $allowance_name=sql_fetch_array($rrr); $i++) { 
						if($_GET['menu'] == $allowance_name['allowance_name']) {
							$html .= "|<span class='menu on' type='".$allowance_name['allowance_name']."'>";
						}else{
							$html .= "|<span class='menu' type='".$allowance_name['allowance_name']."'>";
						}
						
						$html .= $allowance_name['allowance_name'];
						$html .= "</span>";
					}

					echo $html;
				?>
			</div>
			<div class="con_search">
				
				<input type="text" name="startDate" id="startDate"placeholder="Filter date from" value="<?=$_GET['startDate']?>"/>
				<input type="text" name="endDate" id="endDate" placeholder="Filter date to" value="<?=$_GET['endDate']?>"/>
				<span id="btnSearch">Search</span>
				<span style="float:right;margin-top:10px;">Total : <?=$total_count;?></span>
			</div>

				<table cellspacing="0" cellpadding="0" border="0" class="regTb">
					<colgroup>
						<col style="width:100px;"/>
						<col style="width:150px;"/>
						<col style="width:100px;"/>
						<col style="width:100px;"/>
						<col style="width:100px;"/>
						<col style="width:auto;"/>
					</colgroup>
					<thead>

						<tr>
							<th>Date</th>
							<th>Bonus Type</th>
							<th><?=$coin;?></th>
							<th>USD</th>
							<th><?=$coin;?>/USD </th>
							<th>Tansaction Details</th>
						</tr>
					</thead>
					<tbody>
					<?php

					$sql = " select 
								date_format(day, '%Y-%m-%d') as day, 
								allowance_name, mb_recommend, 
								benefit, benefit_usd, exchange_rate, rec 
							from soodang_pay where mb_id = '{$member['mb_id']}' $sql_search
							order by day desc";
					$sql .= " limit {$from_record}, {$rows} ";
					$result = sql_query($sql);

					for ($i=0; $row=sql_fetch_array($result); $i++)
					{
				
					?>

					<tr>
						
						<td class="td_num" align='center'><?php echo ($row['day']); ?></td>
						<td class="td_num" align='center'><?php echo ($row['allowance_name']); ?></td>
						<td class="td_numbig <?=$cls;?>" align='center'><?php echo $row['benefit']; ?></td>
						<td class="td_num" align='center'><?php echo '$ '.$row['benefit_usd']; ?></td>
						<td class="td_num" align='center'><?php echo '$ '.$row['exchange_rate']; ?></td>
						<td width='500' align='center'><?php echo $row['rec']; ?></td>

					</tr>

					<?php
					}

					if ($i == 0)
						echo '<tr><td colspan="6" class="empty_table mid">No record to show.</td></tr>';
					?>
					</tbody>
				</table>
			<?php
				$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;menu='.$_GET['menu'].'&amp;coin='.$coin.'&amp;menu='.$_GET['menu'].'&amp;startDate='.$_GET['startDate'].'&amp;endDate='.$_GET['endDate'].'&amp;page=');
				if ($pagelist) {
					echo $pagelist;
				}
			?>
		</div><!-- // infoBx -->
	</div>
<?php
	include_once('./mypage_footer.php')
?>
</html>
