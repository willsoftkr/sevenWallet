<?php 
	include '../common.php';
	require_once('/home/pinnacle/blockchain/vendor/autoload.php');
	
?>
<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>
<?if(!$member[mb_id])
 header("Location:./pw_login.php");
 ?>

<?if(!$member['mb_btcwallet_addr']){
	
	$api_code = 'd25934ac-4ce4-4942-bf52-293f54c44315';
	$Blockchain = new \Blockchain\Blockchain($api_code);
	$Blockchain->setServiceUrl('http://localhost:3000');
	$wallet = $Blockchain->Create->create($member[mb_id]); //운영자님이 패스워드를 변경 하시기 바랍니다. //Please change the password to create wallet.
	$address = $wallet->{'address'};
	$guid = $wallet->{'guid'};
	$sql = "update g5_member set  mb_btcwallet_addr = '{$address}', mb_btcwallet_id='{$guid}' where mb_id='{$member['mb_id']}'";	
	$ret = sql_query($sql);
	$sqlwallet = "insert into mb_wallet set mb_id='{$member['mb_id']}', type=0, wlt_ad='$address', wlt_key='$guid', wlt_date='$date';";
	sql_query($sqlwallet);

}
else{
	$sql = "select mb_btcwallet_addr, mb_btcwallet_id from g5_member where mb_id ='{$member['mb_id']}'";
	$ret = sql_fetch($sql);
	$address  = $ret['mb_btcwallet_addr'];
	$key = $ret['mb_btcwallet_id'];
	$date =  date("Y-m-d H:i:s",time());
}	
?>

<script src="/js/qrcode.js"></script>
<script type="text/javascript">
	$(function(){
		$('#qr_code').empty();
		new QRCode(document.getElementById("qr_code"), {
		text: "BTC:" + '<?=$address?>' + "?amount=100",
		width: 300,
		height: 300,
		colorDark : "#000000",
		colorLight : "#ffffff",
		correctLevel : QRCode.CorrectLevel.H
		});	
	});
</script>

			<section class="con90_wrap">
				<?include './btc_inc_section.php';?>
				
				<div class="qr_wrap mc_bit" id='qr_code' >
					<!-- <img src="_images/qr_img.gif" alt="큐알"> -->
						
					<!-- <input type="text" placeholder="받을 코인 숫자 입력"> -->
				</div>		
				<p>Input Address : <?=$address?></p>
			</section>

		<?php include '_include/popup.php'; ?>
		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_receive.png' alt='아이콘'> 비트코인 받기");
			$('#wrapper').css("background","#fff");
		});
	</script>



</body></html>
