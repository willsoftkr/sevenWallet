<?php
$sub_menu = "600080";
include_once('./_common.php');

$g5['title'] = "전환 매출 설정";

include_once(G5_ADMIN_PATH.'/admin.head.php');

$sql = "select * from coin_cost";
$row = sql_fetch($sql);
?>

<link rel="stylesheet" href="/adm/css/switch.css">

<style type="text/css">
	xmp {font-family: 'Noto Sans KR', sans-serif;font-size:12px;}
	input[type="radio"] {}
	input[type="radio"] + label{color:#999;}
	input[type="radio"]:checked + label {color:#e50000;font-weight:bold;font-size:14px;}
	table.regTb {width:100%;table-layout:fixed;border-collapse:collapse;}
	table.regTb {border-top:solid 1px #777;}
	table.regTb th,
	table.regTb td {padding:4px 0;border-bottom:solid 1px #ddd;line-height:28px;font-size:12px;}
	table.regTb th {font-weight:normal;font-family:"nngdb";font-size:12px;color:#444;background-color:#f5f5f5;}
	table.regTb td {padding-left:10px;}
	table.regTb input[type="text"],
	table.regTb input[type="password"] {padding:0;padding-left:8px;height:23px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb textarea {padding:0;padding-left:8px;line-height:23px;border:solid 1px #ccc;background-color:#f9f9f9;}
	table.regTb label {cursor:pointer;}
	table.regTb input[type="radio"] {}
	table.regTb input[type="radio"] + label{color:#999;}
	table.regTb input[type="radio"]:checked + label {color:#e50000;font-weight:bold;}
	span.help {font-size:11px;font-weight:normal;color:rgba(38,103,184,1);}


	.btn_confirm {position:fixed;width:80px;right:10px;top:50%;z-index:9999;}
	.btn_confirm input[type="submit"] {display:block;width:100%;height:45px;line-height:45px;background-color:rgba(230,0,68,0.6);cursor:pointer;border:none;border-radius:5px;}
	.btn_confirm input[type="submit"]:hover {background-color:rgba(230,0,68,1);}
	
	.reload_coin_rate{float:right;margin-right:20px;background:blueviolet; padding:0px 20px;color:white;}
</style>
 
<div class="adminWrp">
<form name="site" method="post" action="./config_price.proc.php" onsubmit="return frmnewwin_check(this);" enctype="multipart/form-data" style="margin:0px;">

	<table cellspacing="0" cellpadding="0" border="0" class="regTb">
        <colgroup>
            <th></th>
			<th>자동 시세 비율 (20분간격 업데이트)	</th>
			<th>수동 설정 사용유무</th>
			<th>수동 설정 비율값</th>
        </colgroup>

        <tbody>
			<tr>
				<th>1 btc / per dollar </th>
				<td><? echo $row['btc_cost'];?> <a class="reload_coin_rate" href="/coin_rate_curl.php?url=/adm/config_price.php">갱신하기</a></td>	
				<td> <p style="padding:0;"><input type="checkbox" id="btc_use" name="btc_use" class="nw_with" <?if($row['btc_manual_use'] == 'Y') {echo "checked";}?> value=''/>
				<label for="btc_use" style=""><span class="ui"></span><span class="nw_with_txt">사용 설정</span></label></p></td>
				<td><input type="text" name="btc_cost" value="<? echo $row['btc_manual_cost'];?>" style="width:80%;"/></td>
			</tr>
			<tr>
				<th>1 v7 / per dollor </th>
				<td><? echo $row['v7_cost'];?></td>	
				<td > <p style="padding:0;"><input type="checkbox" id="v7_use" name="v7_use" class="nw_with" <?if($row['v7_manual_use'] == 'Y') {echo "checked";}?> value=''/>
				<label for="v7_use" style=""><span class="ui"></span><span class="nw_with_txt">사용 설정</span></label></p></td>
				<td><input type="text" name="v7_cost" value="<? echo $row['v7_manual_cost'];?>" style="width:80%;"/></td>
			</tr>
		
        </tbody>
    </table>
	<div class="btn_confirm">
		<input type="submit" name="submit" class="btn_sumit" value="저장하기" />
	</div><!-- // btn_confirm // -->
</form>
</div><!-- // adminWrp // -->


<script>

$(document).ready(function(){
	$('.nw_with').on('click',function(){

		if($(this).is(":checked")){

			$(this).parent().find('.nw_with_txt').html('사용함');
		}else{
			$(this).parent().find('.nw_with_txt').html('사용안함');
		}
	});
});


function frmnewwin_check(f)
{
    errmsg = "";
    errfld = "";
	
	if ($('input[name=btc_use]').is(":checked")) {
		$('#btc_use').val('Y');
	}else{
		$('#btc_use').val('N');
	}
	
	f.btc_use = $('#btc_use').value;
	

	if ($('input[name=v7_use]').is(":checked")) {
		$('#v7_use').val('Y');
		f.v7_use = $('#v7_use').val();
	}else{
		console.log("NNN");
		f.v7_use = "N";
	}

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    return true;
}

</script>

<?
include_once ('./admin.tail.php');
?>
