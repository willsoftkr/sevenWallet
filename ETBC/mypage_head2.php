<?
	$sql_coin = "select * from coin_cost";
	$coin_dollor = sql_fetch($sql_coin);

	$qa_sql = "select count(*) as cnt from g5_qa_content where mb_id='{$member['mb_id']}' and qa_1='N';";

	$row = sql_fetch($qa_sql);
	if($row)
	$total_count = $row['cnt'];
	else
	$total_count = 0;

	//echo "membership_yn".$member['membership_yn'];
	$item  = array(1 => $member['it_pool1'], $member['it_pool2'], $member['it_pool3'],$member['it_pool4'],$member['it_GPU']);
	$item_price = array(1 => 3400, 10200, 17000, 40800, 80);
	//$1.15 - 0.0001$ * 3400gh/s = 1.15 - 0.34 = 0.81$ 
	$total_mining_sudang = $member['it_pool1_profit']+$member['it_pool2_profit']+$member['it_pool3_profit']+$member['it_pool4_profit'];
	$total_mining_sudang2 = $member['it_pool5_profit'];


	$sql = "SELECT wr_subject, wr_id FROM g5_write_notice order by wr_id desc";
	$notice = sql_fetch($sql);
	$subject = $notice['wr_subject'];
	
	if($member['mb_level']==10){
		$member_lvimg='civilian';
		$member_lvString="Super Star";
	}
	else if($member['mb_level']==9){
		$member_lvimg='civilian';
		$member_lvString="Manager";
	}
	else if($member['mb_level']==8){
		$member_lvimg='6star';
		$member_lvString="6 Star";
	}
	else if($member['mb_level']==7){
		$member_lvimg='5star';
		$member_lvString="5 Star";
	}
	else if($member['mb_level']==6){
		$member_lvimg='4star';
		$member_lvString="4 Star";
	}
	else if($member['mb_level']==5){
		$member_lvimg='3star';
		$member_lvString="3 Star";
	}
	else if($member['mb_level']==4){
		$member_lvimg='2star';
		$member_lvString="2 Star";
	}
	else if($member['mb_level']==3){
		$member_lvimg='1star';
		$member_lvString="1 Star";
	}
	else if($member['mb_level']==2){
		$member_lv='civilian';
		$member_lvString="0 Star";
	}
	else if($member['mb_level']==1){
		$member_lv='civilian';
		$member_lvString="Miner";
	}

?>
	<div class="top-nav-one">					
			<a href="/"><div class="logo-container"><img src="images/logo.png" alt="pinnacle logo" class="top-logo-icon"><span class="top-logo-text">FIJI Mining</span></div></a>

			<div class="user-drop-down-section">				

				<div class="lang-sel">
					<select class="custom-select">
					  <option selected="">English</option>
					  <!-- <option value="1">Korean</option> -->
					</select>
		  	</div>

		  	<div class="notification-bell">
		  		<a href="#"><i class="fas fa-bell"></i></a>
		  	</div>

		  	<div class="dropdown">
				  <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				    <img src="images/<?=$member_lvimg?>.png" class="user-dropdown-rank" alt="<?=$member_lvString?>"><?=$member['first_name'].' '.$member['last_name']?>
				  </button>
				  <div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="dropdownMenuButton">
				    <a class="dropdown-item" href="<?php echo G5_URL; ?>/bbs/member_confirm.php?url=register_form.php">Manage Profile</a>
				    <a class="dropdown-item" href="./order_history.php">Order History</a>
				    <a class="dropdown-item" href="./qalist.php">Support Center</a>
				    <div class="dropdown-divider"></div>
				    <a class="dropdown-item" href="/bbs/logout.php">Sign Out</a>
				  </div>
				</div>
			</div>
	</div>
  		

	<div class="top-nav-two">
		<div class="payout-countdown nav-two-line-height">
			<span class="nav-two-big blue clock" data-timezone="America/New_York">18 : 29 : 31</span>
			<br>
			<span class="gray">Next Payout</span>
		</div>

		<div class="promotion nav-two-line-height">
			<a href="/bbs/board.php?bo_table=notice&wr_id=12"><span class="blue nav-two-big"><strong>New Promotion!</strong></span></a> 
			<br>
			<span class="gray">Announcement</span>
		</div>

		<div class="price-and-wallets">

				<?if($member['mb_level']!=10){?>
					<span class="ms-box">Message : <img src="img/adm/message_icon.png" alt="" /> <b><?=$total_count?></b></span>
				<?}?>

			<div class="btc-price">
				<span class="gray">Bitcoin Price</span> <br>
				<span class="nav-two-big nav-two-line-height blue"><?=$coin_dollor['btc_cost']?></span>
			</div>

			<div class="pinnacle-wallet">
				<span class="gray">FIJI Wallet</span> <br>
				<img src="images/btc_logo.png" class="nav-two-btc-logo" alt="btc logo"> <span class="nav-two-big nav-two-line-height"><?=$member['mb_balance'];?> BTC</span>
			</div>

			<div class="mining-wallet">
				<span class="gray">Mining Wallet</span> <br>
				<img src="images/btc_logo.png" class="nav-two-btc-logo" alt="btc logo"> <span class="nav-two-big nav-two-line-height"><?=$total_mining_sudang?> BTC</span>
				&nbsp 
				<img src="images/eth_logo.png" class="nav-two-eth-logo" alt="btc logo"> <span class="nav-two-big nav-two-line-height"><?=$total_mining_sudang2?> ETH </span>
			</div>
		</div>
	</div>

<script src="js/moment.js"></script>
<script src="js/moment-timezone.js"></script>
<script src="js/moment-timezone-with-data-2012-2022.js"></script>
<script>
	function lpad(s, padLength, padString){
		while(s.length < padLength)
			s = padString + s;
		return s;
	}

	var clocks = document.getElementsByClassName("clock");

	var dt= moment().tz("America/Los_Angeles").hours(24).minutes(0).second(0);

	var countDownDate = dt.valueOf();

	function updateClocks() {
		var now = moment().tz("America/Los_Angeles").valueOf();
		var distance = countDownDate - now;

		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		clocks[0].textContent = lpad(String(hours), 2, 0) + " : "+ lpad(String(minutes), 2, 0) + " : " + lpad(String(seconds), 2, 0);
		if (distance < 0) {
			clearInterval(x);
			clocks[0].textContent = "EXPIRED";
		}
	}

	// Update every minute:
	var x = setInterval(updateClocks, 1000);
	updateClocks();

	function recommendRegister(){
		var specs = "left=10,top=10,width=500,height=800";
		specs += ",toolbar=no,menubar=no,status=no,scrollbars=no,resizable=no";
		window.open("/shop/recommend_register.php?now_id=<?=$member['mb_id']?>", "recommend_register", specs);
	}


</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

