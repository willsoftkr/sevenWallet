	<div class="side-nav">
		<ul class="link_list">
	    <a href="dashboard.php">
	    	<li class="side-nav-link">
	      		<i class="fas fa-home"></i> Dashboard
	    	</li>
	    </a>

    	<li class="side-nav-link menu-dropdown">
      		<i class="fas fa-address-card"></i> Membership
    	</li>
	    
      	<ul class="sub-link-list">
	      	<a href="<?php echo G5_URL; ?>/bbs/member_confirm.php?url=register_form.php">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-user"></i> Manage Profile
	      		</li>
	      	</a>
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-sort-amount-up"></i> Automatic Pool Upgrade
	      		</li>
	      	</a>
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-file-invoice-dollar"></i> Earnings Report
	      		</li>
	      	</a>
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-history"></i> Order History
	      		</li>
	      	</a>
	      </ul>

	    <a href="#">
	    	<li class="side-nav-link snl_height_fix">
		      <i class="fas fa-coins"></i> Bonus Earnings
		    </li>
		  </a>
    
    	<li class="side-nav-link menu-dropdown snl_height_fix">
	      <i class="fas fa-handshake"></i> Team Information
	    </li>
	  
		<ul class="sub-link-list">
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-sitemap"></i> Level Structure
	      		</li>
	      	</a>
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-tree"></i> Binary Tree
	      		</li>
	      	</a>
		</ul>

		<a href="#">
			<li class="side-nav-link">
				<i class="fas fa-wallet"></i> Crypto Wallets
			</li>
		</a>

	    <li class="side-nav-link menu-dropdown">
	      <i class="fas fa-shopping-cart"></i> Purchase Hash
	    </li>

	    	<ul class="sub-link-list">
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-star"></i> Full
	      		</li>
	      	</a>
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-star-half-alt"></i> Partial
	      		</li>
	      	</a>
	      </ul>

	    <li class="side-nav-link menu-dropdown">
	      <i class="fas fa-user-friends"></i> Referral Link
	    </li>

	    	<ul class="sub-link-list">
	      	<li class="side-nav-sub-link">
	      		<i class="fas fa-clone"></i> myReferralLink1234
	      	</li>
	      </ul>

	    <br>
	    <br>
	    <br>

	    <a href="#">
	    	<li class="side-nav-link">
		      <i class="fas fa-hand-holding-usd"></i> Bonus Plan
		    </li>
    	</a>

	    <li class="side-nav-link menu-dropdown">
	      <i class="fas fa-bullhorn"></i> Pinnacle News
	    </li>

	    	<ul class="sub-link-list">
	      	<a href="#">
	      		<li class="side-nav-sub-link">
	      			<i class="fas fa-exclamation-circle"></i> 2018 Announcement
	      		</li>
	      	</a>
	      </ul>

	   	<a href="#">
		    <li class="side-nav-link">
		      <i class="fas fa-question"></i> FAQs
		    </li>
		  </a>

		  <a href="#">
		    <li class="side-nav-link">
		      <i class="fas fa-life-ring"></i> Support Center
		    </li>
		  </a>
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
	
?>
	
<script>
	var token = <? echo $result;?>;
	function copyURL(){
		$('#url').val(token.result.url).show();
		document.getElementById("url").select();
		document.execCommand("copy");
	}

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
</script>

