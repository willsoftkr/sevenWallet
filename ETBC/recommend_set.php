<?php
$sub_menu = '100100';
include_once('./_common.php');

$g5['title'] = "바이너리레그 셋팅";
include_once('../shop/head_popup.php');

function calc_sale($mb_id, $to_date){

	$sql= " select sales_day g5_member where sales_day<>'".date('Y-m-d')."'";
	$salesday=sql_fetch($sql);
	if(($salesday['sales_day']) || ($update=='y') ){
		$sql= " UPDATE g5_member  SET mb_my_sales=0, noo_my_sales=0,  mon_my_sales=0 ,day_my_sales=0, mb_habu_sum=0, noo_habu_sum=0, mon_habu_sum=0, habu_day_sales=0, sales_day=''";
		sql_query($sql);
		echo $sql."<br>sales_day가 오늘날짜와 다르거나 강제업데이트 0으로 update...<br><br>";
	}
	$sql = "SELECT mb_id, SUM(pv) AS pv FROM g5_shop_order WHERE DATE_FORMAT(od_receipt_time,'%Y-%m-%d')='$to_date' and mb_id='".$mb_id."'";
	$result = sql_query($sql);
	//echo $sql;
	if($to_date==''){
		$to_date=date('Y-m-d');
	}
	for ($i=0; $row=sql_fetch_array($result); $i++) {   
		$comp=$row['mb_id'];
		$today_sales=$row['pv'];	
		$history_cnt=0;
		while(  ($comp!='admin')  || ($comp!='coolrunning')  ){   
				$sql = " SELECT mb_id,mb_name,  mb_brecommend FROM g5_member WHERE mb_id= '".$comp."'";
				$recommend = sql_fetch($sql);
				$mb_id=$recommend['mb_id'];
				$mb_name=$recommend['mb_name'];
				$sql3='';
				//echo $comp;
					//위로 연속 올라가는 부분 추천인으로 올라갈지 바이너리추천인으로 올라갈지 결정
					$recom=$recommend['mb_brecommend'];
					if(   ($mb_name=='본사')  || ($mb_id=='')  ) { 
						echo "admin , 본사 혹은 ''을 만나 정지됨"; break;
					}
					$sql3 = " update g5_member set sales_day='".$to_date."',";
					if($history_cnt==0)
					{ 
						$sql3 .= " mb_my_sales=mb_my_sales	+ ".$today_sales;
					}else{
						$sql3 .= " habu_day_sales=habu_day_sales+".$today_sales;
					}
					$sql3 .= " where mb_id='".$comp."'";
					sql_query($sql3);
					//echo $history_cnt.'--'.$sql3.'<br>';
					$comp=$recom;
					$history_cnt++;	
			} // while
			$history_cnt=0;
			$today_sales=0;
	} //for
}

$sql = "select * from g5_member where mb_id='".$set_id."'";
$usr=sql_fetch($sql);
	
if ($w=="S"){
	$now_date = date("Y-m-d H:i:s",time()); 
	include_once('../adm/inc.member.class.php');

	if ($del_id){
		$sql = "update g5_member set mb_brecommend='',mb_lr=0,mb_brecommend_type='' where mb_id='".$del_id."'";
		sql_query($sql);
	}else{
		if ($set_type=="R"){
			$sql = "update g5_member set mb_brecommend='".$set_id."',mb_lr=2, mb_brecommend_type='".$set_type."',mb_bre_time = '".$now_date."' where mb_id='".$recommend_id."'";
		}else{
			$sql = "update g5_member set mb_brecommend='".$set_id."',mb_lr=1, mb_brecommend_type='".$set_type."',mb_bre_time = '".$now_date."' where mb_id='".$recommend_id."'";
		}

		echo $up_order = "update g5_shop_order set od_receipt_time='".$now_date."' where mb_id = '".$recommend_id."' and od_status ='입금'";		
		sql_query($up_order);
		sql_query($sql);
		calc_sale($recommend_id, date('Y-m-d'));
	}
	
	


	$gubun     = "B";

	$sql = "delete from g5_member_bclass where mb_id='".$member[mb_id]."'";
	sql_query($sql);

	get_brecommend_down($member[mb_id],$member[mb_id],'11');

	$sql  = " select * from g5_member_bclass where mb_id='{$member[mb_id]}' order by c_class asc";	
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		$row2 = sql_fetch("select count(c_class) as cnt from g5_member_bclass where  mb_id='".$member[mb_id]."' and c_class like '".$row[c_class]."%'");
		$sql = "update g5_member set mb_b_child='".$row2[cnt]."' where mb_id='".$row[c_id]."'";
		sql_query($sql);
	}

?>
<script type="text/javascript">
<!--
	opener.document.sForm2.submit();
	self.close();
//-->
</script>
<?php
	//exit;

}
?>
<script type="text/javascript">
<!--
function set_recommend(mb_id){
	document.sForm.recommend_id.value = mb_id;
	document.sForm.submit();
}
function del_recommend(mb_id){
	document.sForm.del_id.value = mb_id;
	document.sForm.submit();
}
//-->
</script>
<style type="text/css">
	th {font-size:12px !important}
	td {font-size:12px}
</style>
<div style="padding:20px 10px 20px 10px">
   
   
		<div style="border:2px solid #d7d7d7;padding:10px;margin-bottom:10px;width:88%;margin-left:17px">
		상위 회원 아이디 : <b><?=$set_id?> (<?=$usr['mb_name']?>)</b>
		</div>



		<form name="sForm" id="sForm" action="recommend_set.php" method="post">
		<input type="hidden" name="w" value="S">
		<input type="hidden" name="set_id" value="<?php echo $set_id?>">
		<input type="hidden" name="set_type" value="<?php echo $set_type?>">
		<input type="hidden" name="now_id" value="<?php echo $now_id?>">
		<input type="hidden" name="recommend_id" value="">
		<input type="hidden" name="del_id" value="">
    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption>신규가입회원</caption>
        <thead>
        <tr>
            <th width="30%" scope="col">회원아이디</th>
            <th width="20%" scope="col">이름</th>
            <th width="25%" scope="col">바이너리레그</th>
            <th width="25%" scope="col">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
<?

		$sql = "select * from g5_member as m where mb_recommend='".$member['mb_id']."' and mb_level>=2 and mb_brecommend='' order by mb_datetime desc";


		$result = sql_query($sql);
		$group_id = "";
		$c_cnt    = 0;
		for ($j=0; $row=sql_fetch_array($result); $j++) {
?>
            <tr>
            <td style="text-align:center"><?=$row['mb_id']?></td>
            <td style="text-align:center"><?=$row['mb_name']?></td>
            <td style="text-align:center"><?=$row['mb_brecommend']?></td>
            <td style="text-align:center">
			<input type="button" onclick="set_recommend('<?=$row['mb_id']?>')" style="padding:4px 8px 4px 8px;border:0px;background:#364fa0;color:#ffffff;cursor:pointer" value="선택">
			<?if ($row['mb_brecommend']){?><input type="button" onclick="del_recommend('<?=$row['mb_id']?>')" style="display:inline-block;padding:3px 7px 3px 7px;border:1px solid #cccccc;background:#fafafa;color:#000000;text-decoration:none;vertical-align:middle;cursor:pointer" value="취소"><?}?>
			</td>
			</tr>
<?
		}
?>
		</tbody>
		</table>
	</div>




		<div align=center style="padding:30px 0px 30px 0px">
	
		 <input type="button" value="close" onclick="self.close();" style="display:inline-block;padding:3px 7px 3px 7px;border:1px solid #3b3c3f;background:#4b545e;color:#ffffff;text-decoration:none;vertical-align:middle;cursor:pointer">
	
		</div>

	</form>
</div>

