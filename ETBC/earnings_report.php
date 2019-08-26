<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/earnings_report/style.css">

	<script>
		$(function() {
			
		});
	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">		
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray" data-i18n="report.title">Earnings Report</h2>
			<div class="earnings-report-container shadow">
				<div class="container-header">
					<h4 data-i18n="report.subTitle" >Annual Income Reports</h4>
				</div>
				<div class="year-row">
					<div class="year" data-i18n="report.report">
						2018 Income Report
					</div>
					<div class="download-button-div">
						<button class="btn btn-primary download-button" data-i18n="report.download">Download</button>
					</div>				
				</div>
			</div>				
		</div>
	</div>
</body>
</html>
