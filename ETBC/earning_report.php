<?php
include_once('./_common.php');
//echo "membership_yn".$member['membership_yn'];
$item  = array(1 => $member['it_pool1'], $member['it_pool2'], $member['it_pool3'],$member['it_pool4'],$member['it_GPU']);
$prifits  = array(1 => $member['it_pool1_profit'], $member['it_pool2_profit'], $member['it_pool3_profit'],$member['it_pool4_profit'],$member['it_pool5_profit']);
$item_price = array(1 => 3400, 10200, 17000, 40800, 80);
//$1.15 - 0.0001$ * 3400gh/s = 1.15 - 0.34 = 0.81$ 
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
	<link rel="stylesheet" href="css/earning_report.css">
	
	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>

	<link rel="stylesheet" href="<?php echo G5_URL; ?>/theme/basic/css/default_shop.css">
	<link rel="stylesheet" href="<?php echo G5_URL; ?>/mobile/skin/member/basic/style.css">
</head>
<body>
	<?include_once('mypage_head.php')?>
	<?include_once('mypage_left.php')?>

	<div id="content">
		<ul class="ibox_list">
		<?php
		$myArr = array(
			1 => 50,
			2 => 50,
			3 => 40,
			4 => 30,
			5 => 30
		);

$sql_price = "select *  from coin_cost";
$result = sql_query($sql_price);
$ret = sql_fetch_array($result);
$bit_gh =  $ret['gh_btc'];
$eth_mh = $ret['mh_eth'];
$bit_cost = $ret['btc_cost'];
$eth_cost = $ret['mh_cost'];

		for( $i=1; $i<5; $i++){

		if ($item[$i]>0 ){
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,"http://whattomine.com/coins/1.json?utf8=%E2%9C%93&hr=".$item_price[$i]."&p=0&fee=0.0&cost=0&hcost=0.0&commit=Calculate");
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
			//접속할 URL 주소
			$result = curl_exec ($ch);
			curl_close ($ch);
			$obj = json_decode($result);
			$day_rev =  str_replace("$", "", $obj->{'revenue'}); 

			//echo "day_rev".$day_rev."   ". $item_price[$i];
			$exchange_rate24 = $obj->{'exchange_rate24'};
			$estimated_rewards = $obj->{'estimated_rewards'};

			$tot_hs = $item_price[$i] * $item[$i];
			$rtot_hs = $item_price[$i] * $item[$i] + $repurcase_hs;

			$mining_earning = $tot_hs*$bit_gh;
				//1/$exchange_rate*$earning_point;//마이닝 수당
			$tot_mining_earning = $mining_earning * $member['profit_days'];//마이닝 수당

//			$mining_earning = sprintf('%0.8f', $mining_earning); // 520 -> 520.00
//			$tot_mining_earning = sprintf('%0.8f', $tot_mining_earning);
//			$1.15 - 0.0001$ * 3400gh/s = 1.15 - 0.34 = 0.81$
			$tot_mining_earning = $prifits[$i];
?>
			<li class="ibox float-e-margins">
				<div class="ibox-title">
					<h5 style="font-size:16px; margin-top:3px; margin-left:5px;">Mining Pool #<?=$i?></h5>
					<div style="float:left; padding-right:5px;">
						
					</div>
					<span class="label label-white pull-right">Pool <?=$i?></span>

				</div>
				<div class="ibox-content">
					<strong style="font-size:16px">Initial Hash: <?=$tot_hs?> (<?=$item[$i]?> share)</strong>
					<br> Total Hash : 
					<strong style="color:#f79221"><?=$rtot_hs?> GH/s</strong>
					<br> Re-purchased Hash: 
					<strong style="color:#035708"> <?=$repurcase_hs?></strong>
					<br> 
					<strong>Total Mining earnings</strong>
					<br> Total:  <?=$tot_mining_earning?> BTC
					<strong style="color:#F80409"></strong>
					<br> YesterDay : <?=$mining_earning?> BTC
				</div>

				<div class="ibox-title" align="center">

					Re-purchase Percent =
					<strong style="color:#036"><?echo $myArr[$i];?></strong>%
				</div>
				<div class="ibox-content">
					<div align="center">
						<form action="" method="POST">

							<select name="change_reinvestment_value">
								<option value="<?echo $myArr[$i];?>" selected><?echo $myArr[$i];?>%</option>
							</select>
							<input type="submit" name="submit" class="btn btn-sm btn-primary" value="Change Pool">
						</form>
					</div>

					<br>

					<table width="100%" border="0" cellpadding="3" cellspacing="4" class="f13">
						<tbody>
							<tr>
								<td style="border-bottom: solid 1px #999">
									<strong>Shares</strong>
								</td>
								<td style="border-bottom: solid 1px #999">
									<strong>Date Paid</strong>
								</td>
								<td style="border-bottom: solid 1px #999">
									<strong>Expires</strong>
								</td>
							</tr>
							<tr>
								<!--td style="padding-left:5px;">1</td>
								<td>11-26-2014</td>
								<td class="text-danger">expired</td-->
							</tr>
						</tbody>
					</table>
				</div>
			</li>
			<?}?>

	    <?}
			if ($item[5]>0 ){
			$tot_hs = $item_price[5] * $item[5];
			$repurcase_hs = 0;
			$rtot_hs = $item_price[5] * $item[5] + $repurcase_hs;
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,"http://whattomine.com/coins/151.json?utf8=%E2%9C%93&hr=80.0&p=0&fee=0.0&cost=0&hcost=0.0&commit=Calculate");
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 

			$mining_earning = $tot_hs*$eth_mh;
			$tot_mining_earning = $mining_earning * $member['profit_days'];//마이닝 수당
				
			$tot_mining_earning = $prifits[$i]; 
			?>
			<li class="ibox float-e-margins">
				<div class="ibox-title">
					<h5 style="font-size:16px; margin-top:3px; margin-left:5px;">Mining Pool #GPU</h5>
					<div style="float:left; padding-right:5px;">
						
					</div>
					<span class="label label-white pull-right">GPU</span>

				</div>
				<div class="ibox-content">
					<strong style="font-size:16px">Initial Hash: <?=$tot_hs?> mh/s(<?=$item[5]?> share)</strong>
					<br> Total Hash : 
					<strong style="color:#f79221"><?=$rtot_hs?> MH/s</strong>
					<br>
					Re-purchased Hash: 
					<strong style="color:#035708"> <?=$repurcase_hs?></strong>
					<br>
					<strong>Total Mining earnings</strong>
					<br> Total : <?=$tot_mining_earning?>ETH

					<strong style="color:#F80409"></strong>
					<br> YesterDay : <?=$mining_earning?>ETH
				</div>

				<div class="ibox-title" align="center">

					Re-purchase Percent =
					<strong style="color:#036"><?echo $myArr[$i];?></strong>%
				</div>
				<div class="ibox-content">
					<div align="center">
						<form action="" method="POST">

							<select name="change_reinvestment_value">
								<option value="<?echo $myArr[$i];?>" selected><?echo $myArr[$i];?>%</option>
							</select>
							<input type="submit" name="submit" class="btn btn-sm btn-primary" value="Change Pool">
						</form>
					</div>

					<br>

					<table width="100%" border="0" cellpadding="3" cellspacing="4" class="f13">
						<tbody>
							<tr>
								<td style="border-bottom: solid 1px #999">
									<strong>Shares</strong>
								</td>
								<td style="border-bottom: solid 1px #999">
									<strong>Date Paid</strong>
								</td>
								<td style="border-bottom: solid 1px #999">
									<strong>Expires</strong>
								</td>
							</tr>
							<tr>
								<!--td style="padding-left:5px;">1</td>
								<td>11-26-2014</td>
								<td class="text-danger">expired</td-->
							</tr>
						</tbody>
					</table>
				</div>
			</li>
			<?}?>
		</ul>
	</div>
	
	<?include_once('mypage_footer.php')?>
</body>
</html>
