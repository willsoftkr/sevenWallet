<?php
include_once('./_common.php');
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
	
	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>
</head>
<body>
<?php
include_once('./mypage_head.php');
include_once('./mypage_left.php');
?>
<?
	$sql_coin = "select * from coin_cost";
	$row = sql_fetch($sql_coin);
	$btc_cost = $row['btc_cost'];
?>

<div id="content">
<section class="csection csec10">
	<div class="inner">
		<div class="content">
	<?if($member['mb_level'] < 3){?>
		<form name="fitem" method="post" action="/shop/cartupdate.php" onsubmit="return fitem_submit(this);">
			<input type="hidden" name="it_id[]" value='1527096053' >
			<input type="hidden" name="it_id[]" value='1527096045' >
			<input type="hidden" name="sw_direct">
			<input type="hidden" name="ct_qty[1527096053][]" value="1" id="ct_qty_1" >
			<input type="hidden" name="ct_qty[1527096045][]" value="1" id="ct_qty_1" >
			<input type="hidden" name="url">
			<div class="pool-info-col" data-aos="fade-left">
				<div class="title-sec">
					<img class="title-img" src="/theme/basic/img/membership_pool1.png">
				</div>
				<div class="pool-slide">
					<div class="pool-title">
						Lifetime 
					</div>
					<div class="pool-middle">
						Membership
					</div>
					<div class="pool-bottom">
						+ Pool 1 3,400 GH/S
					</div>
					<div class="pool-price">
					$1099 
					</div>
					<div class="pool-bit">
						<?=round(1099/$btc_cost,8)?> BTC
					</div>
					<div class="pool-button">
						<input  class="btn pool-buy-btn skyblue-button" type="submit" onclick="document.pressed=this.value;" value="Add to Cart"  id="sit_btn_cart">
					</div>
				</div>
			</div>
		</form >
	<?}?>
	<?if($member['mb_level'] > 2){?>
		<form name="fitem" method="post" action="/shop/cartupdate.php" onsubmit="return fitem_submit(this);">
			<input type="hidden" name="it_id[]" value='1527096045' >
			<input type="hidden" name="ct_qty[1527096045][]" value="1" id="ct_qty_1" >
			<input type="hidden" name="sw_direct">
			<div class="pool-info-col" data-aos="fade-left">
				<div class="title-sec">
					<img class="title-img" src="/theme/basic/img/pool_1.png">
				</div>
				<div class="pool-slide">
					<div class="pool-title">
						Bitcoin
					</div>
					<div class="pool-middle">
						Lifetime mining
					</div>
					<div class="pool-bottom">
						3,400 GH/S
					</div>
					<div class="pool-price">
						$1,000
					</div>
					<div class="pool-bit">
						<?=round(1000/$btc_cost,8)?> BTC
					</div>
					<div class="pool-button">
						<input  class="btn pool-buy-btn skyblue-button" type="submit" onclick="document.pressed=this.value;" value="Add to Cart"  id="sit_btn_cart">
					</div>
				</div>
			</div> <!--- END COL -->
		</form>
	<?}?>
<form name="fitem" method="post" action="/shop/cartupdate.php" onsubmit="return fitem_submit(this);">
<input type="hidden" name="it_id[]" value='1527096041' >
<input type="hidden" name="ct_qty[1527096041][]" value="1" id="ct_qty_1" >
<input type="hidden" name="sw_direct">
	<!--START COL -->
	<div class="pool-info-col" data-aos="fade-left">
		<div class="title-sec">
			<img class="title-img" src="/theme/basic/img/pool_2.png">
		</div>
		<div class="pool-slide">
			<div class="pool-title">
				Bitcoin
			</div>
			<div class="pool-middle">
				Lifetime mining
			</div>
			<div class="pool-bottom">
				10,200 GH/S
			</div>
			<div class="pool-price">
				$3,000
			</div>
			<div class="pool-bit">
				<?=round(3000/$btc_cost,8)?> BTC
			</div>
			<div class="pool-button">
			<input  class="btn pool-buy-btn skyblue-button" type="submit" onclick="document.pressed=this.value;" value="Add to Cart"  id="sit_btn_cart">
			</div>
		</div>
	</div> <!--- END COL -->
</form>
<? $it_id_member=1527096037; $it['it_buy_min_qty']=1; ?>
<form name="fitem" method="post" action="/shop/cartupdate.php" onsubmit="return fitem_submit(this);">
	<input type="hidden" name="it_id[]" value='1527096037' >
	<input type="hidden" name="sw_direct">
	<input type="hidden" name="ct_qty[1527096037][]" value="1" >
					<!--START COL -->
					<div class="pool-info-col" data-aos="fade-left">
						<div class="title-sec">
							<img class="title-img" src="/theme/basic/img/pool_3.png">
						</div>
						<div class="pool-slide">
							<div class="pool-title">
								Bitcoin
							</div>
							<div class="pool-middle">
								Lifetime mining
							</div>
							<div class="pool-bottom">
								17,000 GH/S <span style="color:red;">+1%</span>
							</div>
							<div class="pool-price">
								$5,000
							</div>
							<div class="pool-bit"> 
								<?=round(5000/$btc_cost,8)?> BTC
							</div>
							<div class="pool-button">
			<input  class="btn pool-buy-btn skyblue-button" type="submit" onclick="document.pressed=this.value;" value="Add to Cart"  id="sit_btn_cart">
							</div>
						</div>
					</div> <!--- END COL -->
					</form>

<form name="fitem" method="post" action="/shop/cartupdate.php" onsubmit="return fitem_submit(this);">
<input type="hidden" name="it_id[]" value='1527096030' >
<input type="hidden" name="ct_qty[1527096030][]" value="1" >
<input type="hidden" name="sw_direct">
	<!--START COL -->
	<div class="pool-info-col" data-aos="fade-left">
		<div class="title-sec">
			<img class="title-img" src="/theme/basic/img/pool_4.png">
		</div>
		<div class="pool-slide">
		<div class="pool-title">
			Bitcoin
		</div>
		<div class="pool-middle">
			Lifetime mining
		</div>
		<div class="pool-bottom">
			40,800 GH/S <span style="color:red;">+2%</span>
		</div>
		<div class="pool-price">
			$12,000
		</div>
		<div class="pool-bit">
			<?=round(12000/$btc_cost,8)?> BTC
		</div>
		<div class="pool-button">
		<input  class="btn pool-buy-btn skyblue-button" type="submit" onclick="document.pressed=this.value;" value="Add to Cart"  id="sit_btn_cart">
	</div>
	</div>
	</div> <!--- END COL -->
</form>

<form name="fitem" method="post" action="/shop/cartupdate.php" onsubmit="return fitem_submit(this);">
<input type="hidden" name="it_id[]" value='1515148167' >
<input type="hidden" name="ct_qty[1515148167][]" value="1" >
<input type="hidden" name="sw_direct">
					<!--START COL -->
					<div class="pool-info-col" data-aos="fade-left">
						<div class="title-sec">
							<img class="title-img" src="/theme/basic/img/pool_5.png">
						</div>
						<div class="pool-slide">
							<div class="pool-title">
								Ethereum
							</div>
							<div class="pool-middle">
								Lifetime mining
							</div>
							<div class="pool-bottom">
								80 MH/S
							</div>
							<div class="pool-price">
								$3,000
							</div>
							<div class="pool-bit">
								<?=round(3000/$btc_cost,8)?> BTC
							</div>
							<div class="pool-button">
								<input  class="btn pool-buy-btn skyblue-button" type="submit" onclick="document.pressed=this.value;" value="Add to Cart"  id="sit_btn_cart">
							</div>
						</div>
					</div> <!--- END COL -->
				</div><!--- END ROW -->
			</div><!--- END CONTAINER -->
		</section>
	</div>
</form>
	<?include_once('mypage_footer.php')?>
</body>
</html>
<script>
// 바로구매, 장바구니 폼 전송
function fitem_submit(f)
{
    if (document.pressed == "Add to Cart") {
        f.sw_direct.value = 0;

    } else { // 바로구매
        f.sw_direct.value = 1;
    }

    return true;
}

</script>