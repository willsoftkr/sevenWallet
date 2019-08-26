<?php
include_once('./_common.php');	

	$sql = " select * from maintenance";
	$nw = sql_fetch($sql);
	
	if($nw['nw_upstair'] == 'Y'){
		$nw_upstair = 'Y';
	}else{
		$nw_upstair = 'N';
	}
?>

<!DOCTYPE html>
<html>

<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/style.css">
</head>


 <style>
	.account{background:lightslategrey;margin-bottom:1em;}
	.wallet{}
	.account_w{background:steelblue;}
	.trade{padding:30px 15px;background:steelblue;}
	hr{width:95%;border-top-style:dashed}
	.roundbox{}
	.up_gage_box{background:teal;width:100%;margin:0 auto;}
	.guide_txt{margin:0;padding:0;font-size:14px;line-height:13px;text-align:right;color:white;margin-right:15px;margin-top:5px;font-weight:400;}
	.btnOut2{min-height:60px;width:70%}
	.modal .user.selected{background:#f9a62e;border:1px solid #f9a62e;color:black;font-weight:600}
	.pg_page, .pg_current{color:white;}
			.pg_current{color:black}
	</style>

<body>

  <?include_once('mypage_head.php')?>

  <?
  /*
	if($nw_upstair == 'Y'){
		include_once("./index_pop.php");
	}
	*/
	?>

	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<div class="crypto-wallets-container">
				<h2 class="gray">BALANCE</h2>
				<div class="wallets-left-container">

					<!-- //BALANCE -->
					<section class="wallet">
						<div class="wallet_inner">

							<h3 class="balance"><span data-i18n="up.totalbalance">Total Balance</span>  <span class="total_balance">
							   <?=$EOS_TOTAL?> </span> EOS</h3>

							  <div class="coin_list">
								<div class="coin_img">
								  <img src="./images/eos_logo_c.png" alt="">
								  <p class="coin_name">EOS</p>
								</div>
								<div class="eos_balance">
								  <p> <?=$EOS_TOTAL?> EOS</p>
								</div>
							  </div>

							  <div class="coin_list">
								<p class="eos_up_name">UPSTAIRS</p>
								<div class="eos_up_balance">
								  <p> <?=$EOS_UPSTAIR;?> EOS</p>
								</div>
							  </div>

						</div>
					</section>
					<!-- //BALANCE -->
					
					
	  <!-- GAUGE2 -->
	
	  <div class="up_gage_box">
		<h2>My Bonus For OUT</h2>
		<h3><?=$EOS_OUT?>%</h3>
		<div class="progress2 progress-moved">
		<div class="progress-bar2" >
		</div> 
		</div>
		<div class="gage_legend">
		  <span>0%</span>
		  <span>50%</span>
		  <span>100%</span>
		</div>  
		
		<?if($_SERVER['REMOTE_ADDR'] == "221.151.4.195"){
			/*
			echo "upstar :". $EOS_UPSTAIR;
			echo "<br> EOS_OUT :". $EOS_OUT;
			*/
		?>
	
		<?if( $EOS_UPSTAIR == '1,000.000' && $EOS_OUT >= 100 ){?>
			<div style="margin:1em 0;"><button id="reset_btn" class="btnOut2">Upstair Reset</button></div>
		<?}?>

		<?}?>
	  </div> 

	  <script>
	  $(function(){
		  var uw = <?=$EOS_OUT?>;	
		  var uwp = uw ;
		  var bcolor = '#318bc8';

		  if(uwp > 0 && uwp < 30){
				bcolor= "#22a7f0";
		  }else if (uwp >= 30 && uwp < 55){
				
				bcolor= "#f9a62e";
		  }else if (uwp >= 55 && uwp < 70){
				
				bcolor= "#5333ed";
		  }else if (uwp >= 70 && uwp < 100){
				bcolor= "#ff6600";

		  }else if (uwp == 100){
				bcolor= "#f62459";
		  }

		  $(".progress-bar2").css( {"width" : uwp+"%", "background-color" : bcolor }); 
		});
		</script>
	<!-- //GAUGE2 -->

					<!-- 	Upstairs -->
					<h2 class="gray" style="margin-top:30px;">UPSTAIRS</h2>
					<section class="trade">
						<div class="trade_inner">
							<div class="coin_img">
								<img src="./images/eos_logo_c.png" alt="">
								<p class="coin_name">EOS</p>
							</div>
							<div class="trade_info">
								<input type="text" id="trade_money_1" class="trade_money" placeholder="0" min=5 onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">EOS
							</div>
						</div>
						<!--<p class="guide_txt" style="">It's only available in five units</p>-->

						<div class="trade_arrow">
							<i class="fas fa-angle-double-down"></i>
						</div>

						<div class="trade_inner">
							<div class="coin_img">
								<img src="./images/eos_logo_c.png" alt="">
								<p class="coin_name">UPSTAIRS</p>
							</div>
							<div class="trade_info">
								<input type="text" id="trade_money_2" class="trade_money" placeholder="0" min=5> EOS
							</div>
						</div>
     
        	<!-- SUBMIT_BTN -->
				<div class="submit">
					<button id="exchange" class="btnOut2" >UPSTAIRS</button>
				</div>
				<!-- //SUBMIT_BTN -->
				
				</section>

<?
	/*날짜선택 기본값 지정*/
	if (empty($fr_date)) {$fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-3 month"));}
	if (empty($to_date)) {$to_date =  date("Y-m-d", strtotime(date("Y-m-d")."+1 day"));}

	/*날짜계산*/
	$qstr = "stx=".$stx."&fr_date=".$fr_date."&amp;to_date=".$to_date;
	$query_string = $qstr ? '?'.$qstr : '';

	$sql_common ="FROM g5_shop_order";
	$sql_search = " WHERE mb_id = '{$member[mb_id]}' ";
	$sql_search .= " AND od_receipt_time between '{$fr_date}' and '{$to_date}' ";
	
	$sql = " select count(*) as cnt
			{$sql_common}
			{$sql_search} ";
	//print_r($sql);
	$row = sql_fetch($sql);
	$total_count = $row['cnt']; 

	$rows = 20; //한페이지 목록수
	$total_page  = ceil($total_count / $rows);
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지
	$from_record = ($page - 1) * $rows; // 시작 열

	$sql = " select *
			{$sql_common}
			{$sql_search}
			order by od_receipt_time desc
			limit {$from_record}, {$rows} ";
	$result = sql_query($sql);

	//print_R($sql );

?>

				
		 <!-- UPSTAIRS HISTORY -->
		 <section class="history_box">
          <h3 class="hist_tit">Upstairs History</h3>
		
		  <?while( $row = sql_fetch_array($result) ){?>
          <div class="hist_con">
            <div class="hist_con_row1">
              <div class="row1_left">
                <span class="hist_name">Upstairs</span><br>
                <span class="hist_date"><?=$row['od_receipt_time']?></span>
              </div>
              <div class="row1_right">
                <span class="hist_value"><strong><?=$row['pv']?></strong> EOS</span>
              </div>
            </div>
		 </div>
		 <?}?>

		 <?php
			$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
			echo $pagelist;
		 ?>
			
		
        </section>

</div>
<!-- 	// Upstairs -->

      
       

	


   
 
		<!-- // 스폰서 추가 190619_soo-->
		<?
			$countbre = get_brecommend($member['mb_id']);
			if(!$countbre && $member['mb_deposit_point'] != 0){
		?>

		<script type="text/javascript">
		$( document ).ready(function(){
			

			$('#btnSearch2').click(function () {
				getUser('#reg_mb_brecommend','#sponsor');
			});
			

			/*추천인 후원인 찾기*/
			function getUser(etarget,type){
				console.log('sponsor');

				var target = etarget;
				var target_type = type;
				var target_modal = target_type + ' .modal-body .user';
				//console.log($(target).val() + " / "+ target_type);

				$.ajax({
					type:'GET',
					url:'level_structure_search.php',
					async: false,
					dataType: 'json',
					data: {
						input_id : $(target).val()
					} ,
					success: function(data){
						var list = (data);
						console.log(data);

						if(list.length > 0){

							$(target_type).modal('show');

							var vHtml = $('<div>');
							$.each(list, function( index, obj ) {
								
								vHtml.append($('<div>').addClass('user').html(obj.mb_id));
							});

							$(target_type + ' .modal-body').html(vHtml.html());
						}else {
							//alert('MEMBER NOT FOUND');
							commonModal('Notice','MEMBER NOT FOUND',80);
						}
					}

					});

				$(document).on('click',target_modal,function(e) {
					$(target_modal).removeClass('selected');
					$(this).addClass('selected');
				});

				$('.btnSave').on('click',function(e) {
					$(target).val( $( target_modal + '.selected').html());
					$('#register_btn').addClass('view');
					$(target_type).modal('hide');
				});

			}
		});

		function sponsor_confirm(e){
			// Vaild Check 추가 할것!	
			var f = $('#Sponsorform');
			
			console.log(f.val())

			if (typeof(f.mb_brecommend) != "undefined" && f.mb_recommend.value) {
				if (f.mb_id.value == f.mb_recommend.value) {
					commonModal('check recommend','<strong>please retry.</strong>',80);
					f.mb_recommend.focus();
					return false;
				}
			}

			this.submit();
		}
  
</script>
      
        <div class="roundbox">
		 <h2 class="sponsor_tit"> Sponsor Register</h2>

			<div class="sponsor_input">
				<form id="Sponsorform" name="Sponsorform" action="./sponsor_proc.php" method="post" enctype="multipart/form-data" >
				<div>
				<input type="hidden"  name="link" value="<?=$_SERVER['PHP_SELF']?>" />
				<input class="input-search" value="<?php echo $mb_brecommend ?>" type="text" name="mb_brecommend" id="reg_mb_brecommend"  placeholder="Sponsor's Username" data-i18n="[placeholder]register.Sponsor"  required>
				<button class="search-button" type="button" data-i18n="register.search" id="btnSearch2" >Search</button>
				
				<button class="search-button send_btn" id="register_btn" onclick="sponsor_confirm(this);"> Register Sponsor</button>
				</div>
				</form>
			</div>

	
	</div>
  <?}?>
</div>

			</div>
		</div>
	</div>
  </div>
  







  <div class="modal fade" id="sponsor" tabindex="-1" role="dialog" aria-labelledby="sponsorModalLongTitle" aria-hidden="true">
	<div class="modal-dialog" role="document">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title" id="sponsorModalLongTitle">Select Sponsor's Username</h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		  </button>
		</div>
		<div class="modal-body">
		  <div class="user">
		  </div>

		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-secondary" data-dismiss="modal" >Close</button>
		  <button type="button" class="btn btn-primary btnSave">Save</button>
		</div>
	  </div>
	</div>
  </div>



	<div class="modal fade" id="ethereumAddressModalCenter" tabindex="-1" role="dialog"
		aria-labelledby="ethereumAddressModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ethereumAddressModalLongTitle">EOS WALLET</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your wallet address has been saved.</h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
<!--
	<div class="modal fade" id="withdrawBitcoin" tabindex="-1" role="dialog"
		aria-labelledby="withdrawBitcoinModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="withdrawBitcoinModalLongTitle">EOS Withdrawal</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your EOS has been successfully withdrawn.</h4>
					<p>Please allow up to 2 hours for the transaction to complete.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="transferBitcoin" tabindex="-1" role="dialog"
		aria-labelledby="transferBitcoinModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="transferBitcoinModalLongTitle">EOS Transfer</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<i class="far fa-check-circle"></i>
					<h4>Your EOS has been successfully transferred.</h4>
					<p>Please allow up to 2 hours for the transaction to complete.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	-->
  </body>

	<script>

		$(function () {
			var mb_block = Number("<?=$member['mb_block']?>");
			var search = $('#recipient').val();
			var mb_id = "<?=$member['mb_id']?>";
			var upstair_acc = "<?=$EOS_UPSTAIR_ACC?>";
			var upstair_eos = "<?=$EOS_UPSTAIR?>";
			
			
			

			// 업스테어 금액입력하면 동비율로 입력
			$('#trade_money_1').on('keyup',function(e){				
				$('#trade_money_2').val(Number($('#trade_money_1').val()));
			});
			
			// 업스테어 금액이 1000 넘으면안되고
			$('#trade_money_1').change(function(){		
				if($('#trade_money_1').val() > 1000){
						commonModal('check input amount','<strong> The amount cannot exceed 1,000.</strong>',80);
				}
			});



			$('#reset_btn').on('click', function(){
			 
				$.ajax({
						type: "POST",
						url: "./upstairs_reset.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							"mb_id" : mb_id,
							"amount" : upstair_eos,
							"upstair_acc" : upstair_acc
						},
						success: function(data) {
							commonModal('Complete Upstair','<strong> Complete Upstair reset. Available upstairs now!</strong>',80);
							location.reload();
						},
						error:function(e){

						}
					});
			});
			
			

			$('#exchange').on('click', function(){
				//console.log("upstair click");


				var balance = "<?=$EOS_TOTAL ?>";
				var eos_out = "<?=$EOS_OUT ?>";
				var eos_upstair = "<?=$member['mb_deposit_point'] ?>";
				
				var input_val = $('#trade_money_1').val();
				var calc_point = Number(eos_upstair) + Number(input_val);
				var nw_upstair = '<?=$nw_upstair?>';
				
				if(nw_upstair == 'Y'){
					//commonModal('service not available','<strong> the service will be avaiable shortly.</strong>',80);
					commonModal('<strong>Not available right now</strong>', '<i class="fas fa-exclamation-triangle red"></i><h4>Try again later</h4>');
					return false;
				}
				
				// 업스테어 금액이 0이면안되고
				if($('#trade_money_1').val()<=0){
					commonModal('check input amount','<strong> Please check the input amount.</strong>',80);
						return false;
				}else 	if($('#trade_money_1').val() > 1000){
						commonModal('check input amount','<strong> The amount cannot exceed 1,000.</strong>',80);
						return false;
				}
				
				// 업스테어  + 입력금액 합계가 1000 넘으면 안되고
				if(calc_point > 1000){
						commonModal('check input amount','<strong> The total points cannot exceed 1000.</strong>',80);
						return false;
				}

				if(eos_upstair >= 1000 && eos_out >= 500 ){
					commonModal(' You achieved 500% Upstairs','<strong> Please reset button for Upstair.</strong>',80);
					return false;
				}

				if(Number(balance)<$('#trade_money_1').val()){// 업스테어 금액이 토탈 잔고를 넘으면 안되고
					commonModal('check your balance (EOS)','<strong> Not enough balance (EOS).</strong>',80);	
					return false;
				}else{
				
					var save_eos = Number($('#trade_money_1').val()); // 업스테어 신청금액
					
					$.ajax({
						type: "POST",
						url: "upstairs_proc.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							"save_eos": save_eos,
							"mb_id" : mb_id,
							"coin_symbol" : "EOS"
						},
						success: function(data) {
							commonModal('Congratulation! Complete Deposit EOS','<strong> Congratulation! Complete Deposit EOS.</strong>',80);	
							$('#closeModal').on('click', function(){
								location.reload();
							});
							
						},
						error:function(e){
							commonModal('Error!','<strong> Please check retry.</strong>',80);	
						}
					});
				}
			});
		});

</script>
</html>