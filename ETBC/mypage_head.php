<?

	/*서비스점검중*/
	$sql = " select * from maintenance";
	$nw = sql_fetch($sql);
	
	if($nw['nw_use'] == 'Y' && $is_admin !='super'){
		$_SESSION['ss_mb_id']=0;
		goto_url('http://etbc.willsoft.kr');
	}
	/*서비스점검중*/

	if (!$is_member) goto_url(G5_BBS_URL."/login.php?msg=notmember");
	
	$now_file = basename($_SERVER['PHP_SELF']); 
	$now_url =  'http://'.$http_host = $_SERVER['HTTP_HOST'];

	if($member['mb_level']==10){
		$member_lvimg='0eos.png';
		$member_lvString="S Star";
	}
	else if($member['mb_level']==9){
		$member_lvimg='0eos.png';
		$member_lvString="Manager";
	}
	else if($member['mb_level']==8){
		$member_lvimg='0eos.png';
		$member_lvString="6 Star";
	}
	else if($member['mb_level']==7){
		$member_lvimg='0eos.png';
		$member_lvString="5 Star";
	}
	else if($member['mb_level']==6){
		$member_lvimg='0eos.png';
		$member_lvString="4 Star";
	}
	else if($member['mb_level']==5){
		$member_lvimg='0eos.png';
		$member_lvString="3 Star";
	}
	else if($member['mb_level']==4){
		$member_lvimg='0eos.png';
		$member_lvString="2 Star";
	}
	else if($member['mb_level']==3){
		$member_lvimg='3eos.png';
		$member_lvString="1 Star";
	}
	else if($member['mb_level']==2){
		$member_lvimg='2eos.png';
		$member_lvString="0 Star";
	}
	else if($member['mb_level']==1){
		$member_lvimg='1eos.png';
		$member_lvString="Miner";
	}
	else{
		$member_lvimg='0eos.png';
		$member_lvString="fresh Man";
	}

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

	$total_mining_sudang = $member['it_pool1_profit']+$member['it_pool2_profit']+$member['it_pool3_profit']+$member['it_pool4_profit'];
	$eth_wallet = $member['it_poolg_profit']; 
	$btc_wallet = $total_mining_sudang+$member[mb_balance];

	$rwc_num	= $member['rwc_coin_num'];
	$rwc_addr	= $member['rwc_wallet'];

	$lkc_num	= $member['lkc_coin_num'];
	$lkc_addr	= $member['lkc_wallet'];

	$eth_total = $eth_wallet  * $coin_dollor['eth_cost'];
	$btc_total = $btc_wallet * $coin_dollor['btc_cost'];
	$lkc_total = $rwc_num * $coin_dollor['lookie_cost'];
	$rwc_total = $lkc_num * $coin_dollor['rock_cost'];
	$total_capital = $eth_total+$btc_total+$lkc_total+$rwc_total;
	
	$total_eos = $member['mb_save_point'];//EOS 수신 내역 
	$upstair_eos = $member['mb_deposit_point'];

	$sql = "SELECT wr_subject, wr_id, wr_1 FROM g5_write_notice order by wr_id desc";
	$notice = sql_fetch($sql);
	$subject = $notice['wr_subject'];
	$hide_yn = $notice['wr_1'];

	$mbid = $member['mb_id'];
	$sql = "select count(mb_recommend) as enroll from g5_member where mb_recommend='$mbid'";
	//echo $sql;
	$ret = sql_fetch($sql);
	$enroll = sql_fetch_array($ret);
	$cont = $ret['enroll'];

	$placement = $member['mb_brecommend'];

	$url = 'https://openapi.naver.com/v1/util/shorturl';
	$data = array('url' => G5_URL.'/bbs/register_form.php?mb_recommend='.$member['mb_id']);

	
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded; charset=UTF-8\r\nX-Naver-Client-Id: aJiJYg0QgdK51Z6r1shK\r\nX-Naver-Client-Secret: BCZfn4JdKf",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { }
	// var_dump($result);

	$noti = sql_fetch("select * from g5_write_notice order by wr_id desc limit 1");

	$sql_coin = "select * from coin_cost";
	$coin = sql_fetch($sql_coin);

	$notReadCnt = sql_fetch("select count(*) as cnt from g5_write_notice where wr_id not in (select wr_id from read_notice where mb_no = {$member['mb_no']})")['cnt'];


	/*확정추가 */

	$math_sql = "select  sum(mb_save_point + mb_balance + mb_shift_amt + mb_deposit_calc) as total from g5_member where mb_id = '".$member['mb_id']."'";
	$math_total = sql_fetch($math_sql);

	$math_percent_sql = "select  sum(mb_balance / mb_deposit_point) *20 as percent from g5_member where mb_id =  '".$member['mb_id']."'";
	$math_percent = sql_fetch($math_percent_sql);


	$EOS_BENEFIT_TOTAL = number_format($member['mb_balance'],3); // 수당 
	
	$EOS_TOTAL =  number_format($math_total['total'],3);  //합계잔고  //합계잔고
	if($EOS_TOTAL < 0){
		$EOS_TOTAL = 0;
	}

	$EOS_UPSTAIR = number_format($member['mb_deposit_point'],3); // 매출
	
	if($EOS_BENEFIT_TOTAL != 0 ){
		
		$EOS_OUT = number_format($math_percent['percent'],1);
		if($EOS_OUT > 100){
			$EOS_OUT = 100;
		}
	}else{
		$EOS_OUT = 0;
	}
	$EOS_UPSTAIR_ACC = number_format($member['mb_deposit_acc'],3);

?>
<script>
	
	var clocks; 

	//var dt = moment().tz("Asia/Singapore").hours(24).minutes(0).second(0);
	//var countDownDate = dt.valueOf();
	var clockInterV;
	$(document).ready(function(){
	
		$(".menu-dropdown").click(function(){
			$(this).toggleClass('is_open');
			$(this).next().slideToggle();
		});
		/*
		$.i18n.init({ 
			resGetPath: '/locales/my/__lng__.json', 
			load: 'unspecific', 
			fallbackLng: false, 
			lng: 'eng' 
		}, function (t){ 
			$('body').i18n(); 
		}); 

		$('#lang').on('change', function(e) {
			i18n.setLng($(this).val(), function(){ 
				$('body').i18n(); 
			}); 
			localStorage.setItem('myLang',$(this).val());
		});

		if(localStorage.getItem('myLang') != "undefined" && localStorage.getItem('myLang') != "null"){
			$('#lang').val(localStorage.getItem('myLang')).change();
		}
		*/
		var ww = document.body.clientWidth;
		if (ww >= 1000) {
			$('#side-menu').addClass('side-menu-open');
			$('#body-wrapper').addClass('nav-body-shift');
		} else if (ww < 1000) {
			$('#side-menu').removeClass('side-menu-open');
			$('#body-wrapper').removeClass('nav-body-shift');
		}
		
		/*
		var menu_dropdown = document.getElementsByClassName('menu-dropdown');

		for (var i = 0; i < menu_dropdown.length; i++) {
			menu_dropdown[i].onclick = function() {
				this.classList.toggle('is_open');

				var menu_item = this.nextElementSibling;

				if (menu_item.style.maxHeight) {
					menu_item.style.maxHeight = null;
				} else {
					menu_item.style.maxHeight = menu_item.scrollHeight + "px";
				}
			}
		}
		*/

	
		
		//clocks = document.getElementsByClassName("clock");

		// Update every minute:
		//clockInterV = setInterval(updateClocks, 1000);
		//updateClocks();

		/* Wallet link*/
    /*
		$('#fiji-wallet').on('click',function(e){
			location.href="crypto_wallet.php";
		});
		$('#mining-wallet').on('click',function(e){
			location.href="crypto_wallet_eth.php";
		});
    */
		/* Wallet link*/

	});

/* Time counter */

	function updateClocks() {
		var now = moment().tz("America/Los_Angeles").valueOf();
		var distance = countDownDate - now;

		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);

		clocks[0].textContent = hours + " : "+ minutes + " : " + seconds;
		clocks[1].textContent = hours + " : "+ minutes + " : " + seconds;
		if (distance < 0) {
			clearInterval(clockInterV);
			clocks[0].textContent = "EXPIRED";
			clocks[1].textContent = "EXPIRED";
		}
	}

/* time counter */


	function recommendRegister(){
		// var specs = "left=10,top=10,width=500,height=690";
		// specs += ",toolbar=no,menubar=no,status=no,scrollbars=yes,resizable=no";
		// window.open("./enrollmember.php?now_id=<?=$member['mb_id']?>", "recommend_register", specs);

		location.href="/bbs/logout.php?url=/bbs/register_form.php?mb_recommend=<?=$member['mb_id']?>";
	}

	
/* side menu */ 
	function toggleSideMenu() {
		document.getElementById('side-menu').classList.toggle('side-menu-open');
		document.getElementById('side-menu').classList.toggle('shadow');

		if (document.body.clientWidth >= 1000) {
			document.getElementById('body-wrapper').classList.toggle('nav-body-shift');
		}
	}
/* side menu */ 

/*
	var token = JSON.parse('<?php echo $result;?>');
	function copyURL(){
		//$('#url').val("http://www.fijimining.net/bbs/register_form.php?mb_recommend=<? echo $member['mb_id'];?>").show();
		$('#url').val(token.result.url).show();
		document.getElementById("url").select();
		document.execCommand("copy");
		commonModal('Copied',$('#copyMsg').text());
	}
*/

/* modal */
	function commonModal(title, htmlBody, bodyHeight){
		$('#commonModal').modal('show');
		$('#commonModal .modal-header .modal-title').html(title);
		$('#commonModal .modal-body').html(htmlBody);
		if(bodyHeight){
			$('#commonModal .modal-body').css('height',bodyHeight+'px');
		} 
		$('#closeModal').focus();
	}
/* modal */
</script>
<?include_once('./test_server.php')?>

	<div class="top-nav-one">
		<i class="fas fa-bars" onclick="toggleSideMenu()"></i>
		<div class="logo-container">
			<a href="/ETBC/dashboard.php">
				<img src="./images/logo_164_34.png" alt="" class="top-logo-icon"><span class="top-logo-text"><!--EOS TEAM BLOCK CHIAN--></span>
			</a>
        </div>
        <!-- xx
		<div class="welcome_username">
			<span class="welcome" data-i18n="nav.welcome">Welcome</span> <?echo $member['mb_id']?>
        </div>
-->
<!-- xx
		<div class="recent-notice">
			<?if($hide_yn!='hide'){?>
			<a href="/new/fiji_news.php?bo_table=notice&open=true" ><?=$noti['wr_subject']?></a>
			<?}?>
        </div>
            -->
		<?if ($member[mb_level]>=2 && $is_member) { ?>
		<a href="#" onclick="recommendRegister();" style="color:#fff;">
			<div class="referral-enroll" data-i18n="nav.enrollment">Enrollment</div>
		</a>
		<?}?>


		<div class="user-drop-down-section">

			<div class="lang-sel">
				<select class="custom-select" id="lang">
          <option value="eng" selected>English</option>
          <!--xx 0619_moon
          <option value="kor" selected >한국어</option>
					<option value="chn">中文</option>
          <option value="jpn">日本語</option>
    -->
				</select>
			</div>

<!-- xx
			<div class="dropdown">
				<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-bell"></i>
					<?
						if($notReadCnt != '0'){
							echo "<span id='notReadCnt' class='badge badge-secondary'>".$notReadCnt."</span>";
						}
					?>
                </button>
                
				<div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="dropdownMenuButton">
					<a class="dropdown-item" href="/new/fiji_news.php?bo_table=notice&open=true" ><?=$noti['wr_subject']?></a>
				</div>
            </div>
                    -->
            <!-- xx
			<div class="dropdown">
				<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img src="./images/<?echo $member_lvimg;?>" class="user-dropdown-rank" alt="<?echo $member_lvString?>">
				</button>
				<div class="dropdown-menu dropdown-menu-right shadow" aria-labelledby="dropdownMenuButton">
					<span class="username"><?echo $member['mb_id']?></span>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="./manage_profile.php" data-i18n="aside.profile">Manage Profile</a>
					<a class="dropdown-item" href="./order_history.php" data-i18n="aside.order">Order History</a>
					<a class="dropdown-item" href="./support_center.php" data-i18n="aside.center">Support Center</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/bbs/logout.php" data-i18n="nav.out">Sign Out</a>
				</div>
            </div>
                    -->
		</div>
    </div>
    
    <!-- xx
	<div class="top-nav-two">
		<div class="payout-countdown nav-two-line-height">
			<span class="nav-two-big blue clock" data-timezone="America/New_York">18 : 29 : 31</span> <br>
			<span class="gray" data-i18n="nav.next_payout" >Next Payout</span>
		</div>

		<div class="price-and-wallets">
			<div class="btc-price" >
				<span class="gray" data-i18n="nav.bitcoin_price" >Bitcoin Price</span> <br>
				<span class="nav-two-big nav-two-line-height blue">
					$ <?=$coin_dollor['btc_cost']?>
				</span>
			</div>

			<div class="fiji-wallet" id="fiji-wallet" >
				<span class="gray" data-i18n="nav.btc_wallet" >BTC Wallet</span> <br>
				<img src="./images/btc_logo.png" class="nav-two-btc-logo" alt="btc logo"> <span class="nav-two-big nav-two-line-height"><?=$btc_wallet?></span>
			</div>

			<div class="mining-wallet" id="mining-wallet" >
				<span class="gray" data-i18n="nav.eth_wallet" >ETH Wallet</span> <br>&nbsp;
				<img src="./images/eth_logo.png" class="nav-two-eth-logo" alt="btc logo"> <span class="nav-two-big nav-two-line-height"><?=round($eth_wallet,8)?></span>
			</div>
			<div class="mining-wallet" id="mining-wallet" >
				<span class="gray" data-i18n="nav.avatar_wallet" >Avatar Wallet</span> <br>&nbsp;
				<img src="./images/userplus3.png" alt="btc logo" style="width:30px;"> <span class="nav-two-big nav-two-line-height">$ </span>
			</div>
		</div>
                    -->
        <!-- MAX-WIDTH: 880PX VIEWPORTS -->
<!--XX 기존 상단바 -->        
        <!-- xx
		<div class="dropdown mw-880 countdown-dropdown">
			<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<img src="./images/countdown_clock.png" class="mw-880-img" alt="countdown clock">
			</button>
			<div class="dropdown-menu dropdown-menu shadow payout-countdown-dropdown" aria-labelledby="dropdownMenuButton">
				<span class="username next-payout" data-i18n="nav.next_payout" >Next Payout</span>
				<div class="dropdown-divider"></div>
				<span class="nav-two-big blue clock" data-timezone="America/New_York">12 : 34 : 56</span>
			</div>
		</div>

		<div class="dropdown mw-880 btc-usd-dropdown">
			<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<img src="./images/btc_usd.png" alt="btc usd price">
			</button>
			<div class="dropdown-menu dropdown-menu shadow payout-countdown-dropdown" aria-labelledby="dropdownMenuButton">
				<span class="username next-payout" data-i18n="nav.bitcoin_price" >Bitcoin Price</span>
				<div class="dropdown-divider"></div>
				<span class="username blue dropdown-info">$<?=$coin_dollor['btc_cost']?></span>
			</div>
		</div>

		<div class="dropdown mw-880 fiji-wallet-dropdown">
			<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<img src="./images/fiji_wallet.png" alt="fiji wallet">
			</button>
			<div class="dropdown-menu dropdown-menu shadow payout-countdown-dropdown wallet-resize" aria-labelledby="dropdownMenuButton">
				<span class="username next-payout" data-i18n="nav.btc_wallet" >BTC Wallet</span>
				<div class="dropdown-divider"></div>
				<span class="username blue dropdown-info"><?=$btc_wallet?> BTC </span>
			</div>
		</div>

		<div class="dropdown mw-880 mining-wallet-dropdown">
			<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<img src="./images/mining_wallet.png" alt="fiji wallet">
			</button>
			<div class="dropdown-menu dropdown-menu shadow payout-countdown-dropdown wallet-resize" aria-labelledby="dropdownMenuButton">
				<span class="username next-payout" data-i18n="nav.eth_wallet" >ETH Wallet</span>
				<div class="dropdown-divider"></div>

				<span class="username blue dropdown-info"><?=$eth_wallet?>ETH</span>
			</div>
		</div>
	</div>
                    -->
<!--XX 기존 상단바 -->

	<div id="side-menu" class="side-nav">
		<ul class="link_list">
			<li class="side-nav-link">
				<a href="dashboard.php"><i class="fas fa-home"></i> <span data-i18n="aside.dashboard" >Dashboard</span></a>
			</li>
			<li class="side-nav-link">
				<a href="manage_profile.php"><i class="fas fa-user"></i> <span data-i18n="aside.profile" >Manage Profile</span></a>
			</li>

			<ul class="sub-link-list">
				<li class="side-nav-sub-link">
					<a>
						<i class="fas fa-user"></i> <span data-i18n="aside.profile" >Manage Profile</span>
					</a>
        </li>
        </ul>

        <li class="side-nav-link">
				<a href="crypto_wallet_eos.php"><i class="fas fa-wallet"></i> <span>My EOS Wallet</span></a>
            </li>
      
			<li class="side-nav-link">
				<a href="bonus_earnings.php">
					<i class="fas fa-coins"></i> <span>My EOS Bonus</span>
				</a>
			</li>

      <li class="side-nav-link">
				<a href="upstairs.php"><i class="fas fa-thumbs-up"></i> <span>ETBC Upstairs</span></a>
      </li>
      

      <li class="side-nav-link menu-dropdown">
				<a>
					<i class="fas fa-handshake"></i> <span data-i18n="aside.teaminfo" >Team Information</span>
				</a>
			</li>

			<ul class="sub-link-list" style="max-height:fit-content;display:none;">
			
				<li class="side-nav-sub-link">
					<a href="level_structure.php">
						<i class="fas fa-sitemap"></i> <span data-i18n="aside.levelStructure" >Level Structure</span>
					</a>
				</li>
			
				<li class="side-nav-sub-link">
					<a href="binary_tree.php">
						<i class="fas fa-tree"></i> <span data-i18n="aside.btree" >Binary Tree</span>
					</a>
				</li>
			</ul>

      <li class="side-nav-link">
				<a href="etbc_news.php?bo_table=notice">
					<i class="fas fa-bullhorn"></i> <span> <!--data-i18n="aside.news"-->ETBC News</span>
				</a>
			</li>

			<li class="side-nav-link">
				<a href="support_center.php">
					<i class="fas fa-life-ring"></i> <span data-i18n="aside.center" >Support Center</span>
				</a>
			</li>

      <li class="side-nav-link">
				<a href="/bbs/logout.php">
        <i class="fas fa-sign-out-alt"></i> <span>Log Out</span>
        </a>
			</li>
     
		</ul>
    </div>


<!--xx 기존 내비바 -->
				<!-- 
					<li class="side-nav-sub-link">
					<a href="automatic_upgrade.php">
						<i class="fas fa-sort-amount-up"></i> <span data-i18n="aside.upgrade" >Automatic Upgrade</span>
					</a>
				</li>
                -->
                <!--xx
				<li class="side-nav-sub-link">
					<a href="earnings_report.php">
						<i class="fas fa-file-invoice-dollar"></i> <span data-i18n="aside.report" >Earnings Report</span>
					</a>
                </li>
                    -->
            <!--
			<li class="side-nav-link menu-dropdown">
				<a>
					<i class="fas fa-donate"></i> <span >Mining Earnings</span>
				</a>
			</li>
			<ul class="sub-link-list">
				<li class="side-nav-sub-link">
					<a href="mining_btc.php">
					<!-- <a href="javascript:commonModal('Infomation','Coming soon.',80);"> --><!--xx
						<i class="fab fa-bitcoin"></i> <span >Mining BTC</span>
					</a>
				</li>
				<li class="side-nav-sub-link">
					<a href="mining_eth.php">
					<!-- <a href="javascript:commonModal('Infomation','Coming soon.',80);"> --><!--xx
						<i class="fab fa-ethereum"></i> <span >Mining ETH</span>
					</a>
				</li>
				<li class="side-nav-sub-link">
					<a href="order_history.php">
						<i class="fas fa-history"></i> <span data-i18n="aside.order" >Order History</span>
					</a>
				</li>
            </ul>
                    -->
<!--xx
			<li class="side-nav-link">
				<a href="hash_power.php">
					<i class="fas fa-bolt"></i> <span data-i18n="aside.hash" >Hash Power</span>
				</a>
            </li>
                    -->
<!--xx
			<ul class="sub-link-list">
				<li class="side-nav-sub-link">
					<a href="crypto_wallet.php">
						<img src="../img/btc_mining_wallet1.png" style="width:20px;margin:0 10px 0 56px;">
						<span >BTC Mining Wallet</span>
					</a>
				</li>
				<li class="side-nav-sub-link">
					<a href="crypto_wallet2.php">
						<img src="../img/btc_mining_wallet1.png" style="width:20px;margin:0 10px 0 56px;">
						<span >BTC Bonus Wallet</span>
					</a>
				</li>
				<li class="side-nav-sub-link">
					<a href="crypto_wallet_eth.php">
						<img src="../img/eth_mining_wallet1.png" style="width:20px;margin:0 10px 0 56px;">
						<span data-i18n="aside.ethWallet" >ETH Wallet</span>
					</a>
				</li>
				<li class="side-nav-sub-link">
					<a href="crypto_wallet_rock.php">
						<img src="../img/rwd_wallet.png" style="width:20px;margin:0 10px 0 56px;">
						<span data-i18n="aside.rockwood" > Wallet</span>
					</a>
				</li>
				<li class="side-nav-sub-link">
					<a href="crypto_wallet_lookie.php">
						<img src="../img/lkei_wallet.png" style="width:20px;margin:0 10px 0 56px;">
						<span data-i18n="aside.rookie" > Wallet</span>
					</a>
				</li>
				<li class="side-nav-sub-link">
					<a href="avatar_wallet.php">
						<img src="../img/avatar_wallet1.png" style="width:20px;margin:0 10px 0 56px;">
						<span data-i18n="aside.avatar" > Wallet</span>
					</a>
				</li>
				
            </ul>
                    -->
                    <!--xx
			<li class="side-nav-link">
				<a href="purchase_hash_full.php">
					<i class="fas fa-shopping-cart"></i> <span data-i18n="aside.purchase" >Purchase Hash</span>
				</a>
			</li>
                    -->
                    <!--xx
			<li class="side-nav-link menu-dropdown" >
				<a><i class="fas fa-user-friends"></i> <span data-i18n="aside.link" >Referral Link</span></a>
			</li>
			<ul class="sub-link-list">
				<li class="side-nav-sub-link" >
					<a class="con_link">
						<input type="text" id="url" placeholder="Click to see the link" data-i18n="[placeholder]aside.click"/>
						<i class="fas fa-clone" onclick="copyURL();" style="margin: 0px !important;"></i>
						<span style="display:none;" id="copyMsg" data-i18n="aside.copyMsg" >Your referral link has been copied to the clipboard.</span>
					</a>
				</li>
			</ul>
			<li class="side-nav-link">
				<a href="bonus_plan.php">
					<i class="fas fa-hand-holding-usd"></i> <span data-i18n="aside.plan" >Bonus Plan</span>
				</a>
			</li>
 -->
<!--
			<li class="side-nav-link">
				<a href="faq.php">
					<i class="fas fa-question"></i> <span data-i18n="aside.faq" >FAQs</span>
				</a>
            </li>
                    -->
			<!-- <li class="side-nav-link">
				<a href="rockwoodcoin.php">
					<i class="fas fa-coins"></i> <span data-i18n="aside.rockwood" >Rockwood Coin</span>
				</a>
			</li> -->
			<!-- <li class="side-nav-link">
				<a href="rookiecoin.php">
					<i class="fas fa-coins"></i> <span data-i18n="aside.rookie" >Rookie Coin</span>
				</a>
      </li> -->
 <!--xx 기존 내비바 --> 




    <!--xx
	<div class="inner counter after" style="display:none;">

		<div class="flot-left p-counter">
			<b>Payout Counter : <span class="clock" data-timezone="America/New_York" style="width: 120px;text-align: right;">18 : 29 : 31</span></b>
		</div>

		<div class="flot-right mw">

			<span>1 BTC : $ <?=$coin['btc_cost']?> </span> &nbsp;&nbsp;&nbsp;&nbsp;
		<?if($member['mb_level']!=10){?>
			<span class="ms-box">Message : <img src="img/adm/message_icon.png" alt="" /> <b><?=$total_count?></b></span>
		<?}?>
			<ul>
				<li class="fiji">fiji Wallet : <span><?=$member['mb_balance'];?> BTC </span></li>
				<li>Mining Wallet : <span> <?=$total_mining_sudang?> BTC   &nbsp;&nbsp;&nbsp; <?=$total_mining_sudang2?> ETH </span></li>
			</ul>
		</div>
    </div>
        -->

<!--xx MODAL -->
	<div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="saveSettingsCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" ></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary " data-dismiss="modal" id="closeModal" >Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" id="confirmModal">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			<p>"Do you want to add member?"</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary save">Save changes</button>
			</div>
			</div>
		</div>
  </div>
 <!--xx MODAL -->

</div>
