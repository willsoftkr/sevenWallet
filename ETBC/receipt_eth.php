<?php
include_once('../common.php');  

$sql = "select mb_wallet from g5_member where mb_id ='{$member['mb_id']}'";
$ret = sql_fetch($sql);
$address  = $ret['mb_wallet'];

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
	<link rel="stylesheet" href="css/receipt.css">

	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>

</head>
<body>
	<?include_once('mypage_head.php')?>
	<?include_once('mypage_left.php')?>
	<form action ="./receipt_eth.i.php" method="POST">
		<input type="hidden" name="price_coin" value=<?=$price_coin?>>
		<input type="hidden" name="od_id" value="<?=$od_id?>">
		<input type="hidden" name="action" value="chkbalance">
		<div id="content">
			<p class="line1">My Ethereum Wallet</p>
			
			<!-- <div class="block0">
				<p>Select the method of the payment</p>
				<a href="#" class="bit"><img src="img/bitcoin.png"></a>
			</div> -->
			<div class="key">
				<p><strong>Username: <span><?=$member['mb_id']?></span></strong></p>
				<p><a href="javascript:void(0);">Pay with Ethereum</a></p>
				<p>Ethereum Mining Wallet : <strong><?=$eth_wallet;?> ETH </strong></p>

			</div>
			<div class="qr">

				<table>
					<colgroup>
						<col style="width:50%;">
						<col style="width:50%;">
					</colgroup>
					<tbody>
						<tr>
							<th>Withdrawal address: </th>
							<? if($eth_wallet < 0.11){ ?>
							<td><input type="text" name="addr" placeholder="" value="" disabled/></td>
							<?} 	else {?>
							<td><input type="text" name="addr" placeholder="ex) 18r6qxJEubdBosX.." value="" /></td>
							<? }?>
						</tr>
						<tr>
							<th>Amount to withdraw: </th>
							<? if($eth_wallet < 0.11){ ?>
							<td><input type="text"  id="money" placeholder="Balance is not enough" value="" disabled/></td>
							<?} 	else {?>
							<td><input type="text" id="money" placeholder="ex) 0.1" value="" /></td>
							<? }?>
						</tr>
                        <tr>
							<th>Total Amount</th>
							<td><span id="total">0</span> ETH <input type="hidden" name="amt" /></td>
						</tr>
						<tr>
							<th>OTP Auth Code</th>
							<? if($eth_wallet < 0.11){ ?>
							<td><input type="text" name="auth_code" maxlength="6" placeholder="OTP code" value="" disabled/></td>
							<?} 	else {?>
							<td><input type="text" name="auth_code" maxlength="6" placeholder="" value="" /></td>
							<? }?>
						</tr>
						<tr>
							<td colspan="2"><input type="submit" name="submit" class="btn" value="Transfer Request" /></td>
						</tr>
					</tbody>
				</table>
                <p style="text-align:left; font-size:13px;margin-left:10px;">
                    Remittance Charge : <strong>0.01 ETH</strong> <br>
                    Minimum Amount : <strong>0.1 ETH</strong>
                </p>
			</div>

			<table cellspacing="0" cellpadding="0" border="0" class="regTb">
				<colgroup>
					<col style="width:auto;"/>
					<col style="width:160px;"/>
					<col style="width:160px;"/>
					<col style="width:160px;"/>
					<col style="width:160px;"/>
				</colgroup>
				<thead>
					<th>Withdrawal address</th>
					<th>Amount to withdraw</th>
					<th>Requested</th>
					<th>Status</th>
					<th>Approved</th>
				</thead>
				<tbody>
<?

	$sql = " select count(*) as cnt from withdrawal_request_eth A inner join g5_member M on A.mb_id = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."'";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 5;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = "select * from withdrawal_request_eth A inner join g5_member M on A.mb_id = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."'";
	$sql .= " order by create_dt desc ";
	$sql .= " limit {$from_record}, {$rows} ";
	//$list = sql_query($sql);

	
?>
				<?for ($i=0; $row=sql_fetch_array($list); $i++) {?>
					<tr>
						<td>
							<a href="#" onclick="window.open('https://blockchain.info/address/<?=$row[addr]?>','width=800, height=500');">
								<strong><?=$row[addr]?></strong>
							</a>
						</td>
						<td><?=$row[amt]?></td>
						<td class="mid"><?=$row[create_dt]?></td>
						<td class="mid">
							<?=$row[status] == 'R' ? 'Request':'';?>
							<?=$row[status] == 'Y' ? 'Approval':'';?>
							<?=$row[status] == 'S' ? 'Wait':'';?>
							<?=$row[status] == 'N' ? 'Disapproval':'';?>
						</td >
						<td class="mid"><?=$row[update_dt]?></td>
					</tr>
				<?}?>
				</tbody>
			</table>
			
	<?php
		$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
		if ($pagelist) {
			echo $pagelist;
		}
	?>
		</div>
	</form>
	
	<?include_once('mypage_footer.php')?>
</body>
</html>
<script>
	$('#money').on('keyup',function(e){
		$('[name=amt]').val((Number($(this).val()) + 0.01).toFixed(8));
		$('#total').html((Number($(this).val()) + 0.01).toFixed(8));
	});
</script>

<?
    if($_GET[msg]){
        echo "<script>alert('".$_GET[msg]."')</script>";
    }
?>
