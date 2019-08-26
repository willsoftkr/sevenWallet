	<footer>
		<div class="inner after">
			<p class="phra flot-left">Fiji Mining is here to bring crytocurrent mining accessible to everyone.</p>
			<ul class="flot-left">
				<? if ($is_admin) { ?>
					<li ><a href="/adm" target="_blank">ADMIN</a></li>
				<? } ?>
				<? if ($is_member) { ?>
					<li ><a href="<?php echo G5_URL; ?>/shop/compensation.php">BONUS PLAN</a></li>
					<li class="menu-kind"><a href="<?php echo G5_URL; ?>/bbs/member_confirm.php?url=register_form.php">MY OFFICE</a></li>
				<? } ?>
				<? if ($member['mb_level'] > 4) { // 2 star 이상 마케터 신청가능 ?> 
					<li ><a href="<?php echo G5_URL; ?>/new/marketer.php">REQUEST MP</a></li>
				<? } ?>
				
			</ul>
			<p class="copy flot-right">
				copyright 2018｜All Right Reserved.
			</p>
		</div>
		
	</footer>