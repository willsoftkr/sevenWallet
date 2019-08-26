<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/automatic_pool_package_upgrade/style.css">
	<link rel="stylesheet" href="css/new_publishing_top.css">

	<script>
		$( document ).ready(function() {
			var sel_val;
			$("#enable").on("click", function (e) {
			 var myimage = $('.status-header');
				myimage.attr('style','background-color:#6c6cff');
				$("h4").text("Automatic Mining Package Upgrade is enable ");

				sel_val = "enable";
			});
			$("#disable").on("click", function (e) {
			 var myimage = $('.status-header');
				$("h4").text("Automatic Mining Package Upgrade is disable ");
				myimage.attr('style','background-color:#ff6c6c');
				sel_val = "disable";

			});
			$('#save_buttun').on('click', function() {
				$.ajax({
					type: "POST",
					url: "automatic_upgrade_proc.php",
					cache: false,
					async: false,
					dataType: "json",
					data:  {
						auto_status : sel_val
					},
					success: function(data) {
						$('#saveSettings').modal('show');
					}
				});
			});
		});

	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<?
	

		$get_sel = "select mb_autopack from g5_member where mb_id='".$member[mb_id]."'";
		$rst =  sql_fetch($get_sel);
		if($rst[mb_autopack]=='Y'){
			$css_style = "style='background-color:#6c6cff'";
			$chk1 = "checked";
			$chk2 = "";
			$status = "Enable";
		}
		else{
			$css_style = "style='background-color:#ff6c6c'";
			$chk2 = "checked";
			$chk1 = "";
			$status = "Disabled";
		}

		
	?>

	<div class="main-container">
		
		<div class="big-container-wrapper">
			<h2 class="gray auto-">Automatic Mining Package Upgrade</h2>
			<div class="pool-package-container shadow">
				<div class="status-header" <?echo $css_style?> >

					<h4>Automatic Mining Package Upgrade is <?=$status?></h4>					
				</div>

				<div class="check-boxes">
					<fieldset>
					  <div>
					    <input type="radio" id="enable" name="package" value="enable" <?echo $chk1?>/>
					    <label for="enable">Yes, automatically upgrade to the next available mining package level</label>
					  </div>
					  <div>
					    <input type="radio" id="disable" name="package" value="disable"  <?echo $chk2?> />
					    <label for="disable">No, do not automatically upgrade to the next available mining package level</label>
					  </div>
					  <div class="save-button-div">					  	
					  	<button type="button" class="btn btn-primary save-button"  id="save_buttun">Save Changes</button>
					  	<div class="modal fade" id="saveSettings" tabindex="-1" role="dialog" aria-labelledby="saveSettingsCenterTitle" aria-hidden="true">
					  	  <div class="modal-dialog modal-dialog-centered" role="document">
					  	    <div class="modal-content">
					  	      <div class="modal-header">
					  	        <h5 class="modal-title" id="exampleModalLongTitle">Automatic Mining Package Upgrade</h5>
					  	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  	          <span aria-hidden="true">&times;</span>
					  	        </button>
					  	      </div>
					  	      <div class="modal-body">
					  	        <i class="far fa-check-circle blue"></i>
					  	        <h4>Your settings have been successfully saved.</h4>
					  	      </div>
					  	      <div class="modal-footer">
					  	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					  	      </div>
					  	    </div>
					  	  </div>
					  	</div>
					  </div>
					</fieldset>
				</div>	
			</div>

			<div class="warning-container shadow">
				<div class="warning-icon">
					<i class="fas fa-exclamation-circle warning-sign"></i> <br>
					<span>WARNING</span>

				</div>
				<p>
					Enabling this feature will automatically upgrade your account to the next available mining package level only if the available funds are present in your Fiji Wallet. We recommend that you do not enable this feature unless you are absolutely sure you would like this feature turned on. 
				</p>
			</div>
		</div>

	</div>

</body>
</html>
