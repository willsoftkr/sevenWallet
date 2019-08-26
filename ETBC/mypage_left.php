<?

if($member['mb_level']==10){
	$member_lvimg='star_star.png';
	$member_lvString="Super Star";
}
else if($member['mb_level']==9){
	$member_lvimg='star_star.png';
	$member_lvString="Manager";
}
else if($member['mb_level']==8){
	$member_lvimg='star_six.png';
	$member_lvString="6 Star";
}
else if($member['mb_level']==7){
	$member_lvimg='star_five.png';
	$member_lvString="5 Star";
}
else if($member['mb_level']==6){
	$member_lvimg='star_four.png';
	$member_lvString="4 Star";
}
else if($member['mb_level']==5){
	$member_lvimg='star_three.png';
	$member_lvString="3 Star";
}
else if($member['mb_level']==4){
	$member_lvimg='star_two.png';
	$member_lvString="2 Star";
}
else if($member['mb_level']==3){
	$member_lvimg='star_one.png';
	$member_lvString="1 Star";
}
else if($member['mb_level']==2){
	$member_lv='star_star.png';
	$member_lvString="0 Star";
}
else if($member['mb_level']==1){
	$member_lv='star_star.png';
	$member_lvString="Miner";
}


?>
	<div class="side-bar">
		<div class="Rank star01">
			<img src="img/adm/<?echo $member_lvimg?>" alt="" />
			Your Rank is <span><?=$member_lvString?></span>
		</div>
		
		<ul class="drop_menu">
			<li class="menu01">
				<a href="dashboard.php"><img src="img/adm/icon01.png" alt="" /> 22Dashboard</a>
			</li>
			
			<li class="menu02 hovering">
				<a !href=""><img src="img/adm/icon02.png" alt="" /> Membership</a>
				<i class="plus"></i>
				<i class="minus"></i>
			</li>
			<ul class="sub_menu">
				<li><a href="<?php echo G5_URL; ?>/bbs/member_confirm.php?url=register_form.php">Manage Profile</a></li>
				<? if($member['mb_level'] > 1){?>
					<!-- <li><a href="">Automatic Pool Upgrade</a></li> -->
					<!--li><a href="./earning_report.php">Earnings Report</a></li-->
					<li><a href="./order_history.php">Order History</a></li> 
				<?}?>
			</ul>
			
			<!-- <li class="menu03">
				<a href="bonus.php"><img src="img/adm/icon03.png" alt="" />Bonus History</a>
			</li> -->
			
			<li class="menu04 hovering">
				<a !href=""><img src="img/adm/icon04.png" alt="" /> Team Informaion</a>
				<i class="plus"></i>
				<i class="minus"></i>
			</li>
			<ul class="sub_menu" >

				<li ><a href="level_structure.php">Level Structure View</a></li>
				<? if($member['mb_level'] > 1){?>
					<li ><a href="binary_tree.php">Binary Tree View</a></li>
				<?}?>
			</ul>
			
			<? if($member['mb_level'] > 1){?>
			<li class="menu05 hovering">
				<a !href=""><img src="img/adm/icon05.png" alt="" /> Crypto Wallets</a>
				<i class="plus"></i>
				<i class="minus"></i>
			</li>
			<ul class="sub_menu">
			
				<li><a href="receipt.php">Bitcoin Wallet</a></li>
				<li><a href="receipt_eth.php">Ethereum Wallet</a></li>
				<!--li><a href="">Bitcon Cash Wallet</a></li>
				<li><a href="">Monero Wallet</a></li>-->
			</ul>
			<?}?>
			
			
			<li class="menu06 hovering">
				<a !href=""><img src="img/adm/icon06.png" alt="" /> Purchase Hash</a>
				<i class="plus"></i>
				<i class="minus"></i>
			</li>
			
			<ul class="sub_menu">
				<li><a href="buy.php">Buy Full Hash</a></li>
				<li><a href="">Buy Partial Hash</a></li>
			</ul>
			 
			<li class="menu07 hovering">
				<a !href=""><img src="img/adm/icon07.png" alt="" /> FIJI News</a>
				<i class="plus"></i>
				<i class="minus"></i>
			</li>
			<ul class="sub_menu">
				<li><a href="./board.php?bo_table=notice">2018 Announcement</a></li>
			</ul>
			
			<li class="menu08">
				<a href="./qalist.php"><img src="img/adm/icon08.png" alt="" /> Support Center</a>
				
            </li>
            <!--xx
			<li class="menu09">
				<a href="./faq.php?fm_id=2"><img src="img/adm/icon09.png" alt="" /> FAQ's</a>
				
            </li>
            -->
            <!-- xx
			<li class="menu10">
				<a !href=""><img src="img/adm/icon10.png" alt="" /> Referrer's URL</a>
				<span onclick="copyURL()">Copy</span><br>
				<input type="text" id="url" style="width:100%;display:none;" />
			</li>
            -->
			<? if($member['is_marketer'] == 'Y'){?>
				<li class="menu11">
					<a href="dashboard_marketer.php"><img src="img/adm/icon01.png" alt="" />MP Dashboard</a>
				</li>
			<?}?>
		</ul>
	</div>


<?php
	$url = 'https://openapi.naver.com/v1/util/shorturl';
	$data = array('url' => G5_URL.'/bbs/register_form.php?mb_recommend='.$member['mb_id']);
	
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\nX-Naver-Client-Id: aJiJYg0QgdK51Z6r1shK\r\nX-Naver-Client-Secret: BCZfn4JdKf",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { /* Handle error */ }
	
	// var_dump($result);
?>
	
	<script>
		var token = <? echo $result;?>;
		function copyURL(){
			//$('#url').val("http://www.pinnaclemining.net/bbs/register_form.php?mb_recommend=<? echo $member['mb_id'];?>").show();
			$('#url').val(token.result.url).show();
			document.getElementById("url").select();
			document.execCommand("copy");
		}
	</script>

