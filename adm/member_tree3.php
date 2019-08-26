<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('./inc.member.class.php');


auth_check($auth[$sub_menu], 'r');


if ($_GET[go]=="Y"){
	goto_url("member_tree.php#org_start");
	exit;
}
// ************************





if ($gubun=="B"){
	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";
}else{
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";
}

$token = get_token();

$sql  = "select count(*) as cnt from g5_member";
$mrow = sql_fetch($sql);

$sql = "select * from g5_member_class_chk where mb_id='".$member[mb_id]."' and  cc_date='".date("Y-m-d",time())."' order by cc_no desc";
$row = sql_fetch($sql);

if ($mrow[cnt]>$row[cc_usr] || !$row[cc_no] || $_GET["reset"]){

	$sql = "delete from g5_member_class where mb_id='".$member[mb_id]."'";
	sql_query($sql);

	get_recommend_down($member[mb_id],$member[mb_id],'11');

	$sql  = " select * from g5_member_class where mb_id='{$member[mb_id]}' order by c_class asc";	
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		$row2 = sql_fetch("select count(c_class) as cnt from g5_member_class where  mb_id='".$member[mb_id]."' and c_class like '".$row[c_class]."%'");
		$sql = "update g5_member set mb_child='".$row2[cnt]."' where mb_id='".$row[c_id]."'";
		sql_query($sql);
	}

	$sql = "insert into g5_member_class_chk set mb_id='".$member[mb_id]."',cc_date='".date("Y-m-d",time())."',cc_usr='".$mrow[cnt]."'";
	sql_query($sql);

}

if ($mb_org_num){
	if ($mb_org_num>8) $mb_org_num = 8;
	$sql = "update g5_member set mb_org_num='".$mb_org_num."' where mb_id='".$member[mb_id]."'";
	sql_query($sql);	
	$member[mb_org_num] = $mb_org_num;
}


$sql = "select * from g5_member_bclass_chk where mb_id='".$member[mb_id]."' and  cc_date='".date("Y-m-d",time())."' order by cc_no desc";
$row = sql_fetch($sql);

if ($mrow[cnt]>$row[cc_usr] || !$row[cc_no] || $_GET["reset"]){

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

	$sql = "insert into g5_member_bclass_chk set mb_id='".$member[mb_id]."',cc_date='".date("Y-m-d",time())."',cc_usr='".$mrow[cnt]."'";
	sql_query($sql);


	if ($_GET["reset"]){
		goto_url("member_tree.php?gubun=".$gubun."&sfl=".$sfl."&stx=".$stx."&gubun=".$gubun);
		exit;		
	}
}


$qstr.='&fr_date='.$fr_date.'&to_date='.$to_date.'&starter='.$starter.'&partner='.$partner.'&team='.$team.'&bonbu='.$bonbu.'&chongpan='.$chongpan;

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';


$g5['title'] = '조직도(트리)';
include_once ('./admin.head.php');

/*
if (strstr($sfl, "mb_id"))
    $mb_id = $stx;
else
    $mb_id = "";
    */


?>
<style type="text/css">
	.btn_menu {padding:5px;border:1px solid #ced9de;background:rgb(246,249,250);cursor:pointer}
</style>
<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<link rel="stylesheet" href="/js/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="/js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/jquery-ui.min.js"></script>
<script>
    $.datepicker.regional["ko"] = {
        closeText: "close",
        prevText: "이전달",
        nextText: "다음달",
        currentText: "오늘",
        monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
        monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
        dayNames: ["일","월","화","수","목","금","토"],
        dayNamesShort: ["일","월","화","수","목","금","토"],
        dayNamesMin: ["일","월","화","수","목","금","토"],
        weekHeader: "Wk",
        dateFormat: "yymmdd",
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: true,
        yearSuffix: ""
    };
	$.datepicker.setDefaults($.datepicker.regional["ko"]);

</script>
<div style="padding:0px 0px 0px 10px;">
	<a name="org_start"></a>
	<div style="float:left">
	<input type="button" class="btn_menu" value="검색메뉴닫기" onclick="btn_menu2()">
	<input type="button" class="btn_menu" value="전체 조직도보기" onclick="location.href='member_tree.php?go=Y'">
	<input type="button" class="btn_menu" style="background:#fadfca" value="신규회원등록" onclick="open_register()">
	<input type="button" class="btn_menu" style="background:#fadfca" value="조직도 인쇄" onclick="btn_print()">
	</div>
	<div style="float:right;padding-right:10px">
	<input type="button" class="btn_menu" value="조직도 재구성" onclick="btn_org()">
	</div>
</div>
<div style="padding-top:10px;clear:both"></div>
<div id="div_left" style="width:15%;float:left;min-height:670px;border:">
<?
if (!$fr_date) $fr_date = Date("Y-m-d", time()-60*60*24*365);
if (!$to_date) $to_date = Date("Y-m-d", time());
?>
	<div style="margin-left:10px;padding:5px 5px 5px 5px;border:1px solid #d9d9d9;height:683px">
		<form name="sForm2" id="sForm2" method="get" action="member_tree.php">
		<input type="hidden" name="now_id" id="now_id" value="<?=$now_id?>">
		<table>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px">
				<div style="float:left">
				<b>표시인원</b>
				</div>
				<div style="float:right">
				<input type="text" id="mb_org_num"  name="mb_org_num" value="<?php echo $member[mb_org_num]; ?>" class="frm_input" style="text-align:center" size="3" maxlength="3"> 단계 &nbsp;
				</div>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="20" style="padding:10px 10px 10px 10px" align=center>
				<input type="radio" id="gubun" name="gubun" onclick="document.sForm2.submit();" value=""<?if ($gubun=="") echo " checked"?>> 추천인
				<input type="radio" id="gubun" name="gubun" onclick="document.sForm2.submit();" value="B"<?if ($gubun=="B") echo " checked"?>> 바이너리레그

				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px"><b>매출기간</b></td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding:10px 10px 10px 10px" align=center>
				<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input"  style="text-align:center;width:90px" maxlength="10"> ~
				<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" style="text-align:center;width:90px" maxlength="10">

				</td>
			</tr>

			<tr>
				<td bgcolor="#f2f5f9" height="30" align="center">
				<input type="submit"  class="btn_submit" style="padding:5px" value="적 용">
				</td>
			</tr>
		</table>
		</form>
		<div id="div_member"></div>
		<form name="sForm" id="sForm" method="post" style="padding-top:10px" onsubmit="return false;">
		<input type="hidden" name="gubun" value="<?=$gubun?>">
		<table>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px"><b>회원검색</b></td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding:10px 10px 10px 10px">
				
				<select name="sfl" id="sfl">
				    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>
					<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>이름</option>
				</select>
				<div style="padding-top:5px">
				<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
				<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class="required frm_input" style="width:100%;" onkeypress="event.keyCode==13?btn_search():''">
				</div>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" align="center">
				<input type="button" onclick="btn_search();" class="btn_submit" style="padding:5px" value="검 색">
				</td>
			</tr>
		</table>
		</form>

		<div id="div_result" style="margin-top:5px;overflow-y: auto;height:418px">

		</div>
	</div>
</div>
<?

if ($now_id){
	$go_id = $now_id;
}else{
	$go_id = $member[mb_id];
}
if ($member[mb_org_num]){
	$max_org_num = $member[mb_org_num];
}else{
	$max_org_num = 4;
}

$sql       = "select c.c_id,c.c_class from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.c_id='$go_id'";
$srow      = sql_fetch($sql);
$my_depth  = strlen($srow['c_class']);
$max_depth = ($my_depth+($max_org_num*2));

?>
<div id="div_right" style="width:85%;float:left;min-height:500px">
		<div class="zTreeDemoBackground left" style="min-height:573px;margin:0px 10px 0px 10px;border:1px solid #d9d9d9;">
			<ul id="treeDemo" class="ztree"></ul>
		</div>
		<SCRIPT type="text/javascript">
			<!--
			var setting = {
				view: {
					nameIsHTML: true
				},
				data: {
					simpleData: {
						enable: true
					}
				}
			};
			var zNodes =[
		<?
		//업데이트유무 확인
		$sql = "select * from ".$class_name."_chk where cc_date='".date("Y-m-d",time())."' order by cc_no desc";
		$mrow = sql_fetch($sql);

		$sql = "select c.c_id,c.c_class,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,(select pool_level from g5_member where mb_id=c.c_id) as pool_level,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select count(*) from g5_member where mb_recommend=c.c_id) as c_child,(select mb_b_child from g5_member where mb_id=c.c_id) as b_child,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='L' limit 1) as b_recomm,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='R' limit 1) as b_recomm2,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$go_id}' and length(c.c_class)<".$max_depth." order by c.c_class";

		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			if (strlen($row[c_class])==2){
				$parent_id = 0;
			}else{
				$parent_id = substr($row[c_class],0,strlen($row[c_class])-2);
			}
			$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id='".$row[c_id]."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
			$row2 = sql_fetch($sql);

			$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_id<>'".$row[c_id]."' and c_class like '".$row[c_class]."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
			$row3 = sql_fetch($sql);

			//이전 30일
			$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_id<>'".$row[c_id]."' and c_class like '".$row[c_class]."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
			$row5 = sql_fetch($sql);

			//바이너리 왼쪽 오늘 매출

			if ($row[b_recomm]){
				$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row[b_recomm]."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
				$row6 = sql_fetch($sql);

				$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$row[b_recomm]."'";
				$row8 = sql_fetch($sql);

				$row6['tpv'] += $row8['tpv'];
			}else{
				$row6['tpv'] = 0;
			}

			//바이너리 오른쪽 오늘 매출
			if ($row[b_recomm2]){
				$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row[b_recomm2]."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
				$row7 = sql_fetch($sql);

				$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$row[b_recomm2]."'";
				$row9 = sql_fetch($sql);
				$row7['tpv'] += $row9['tpv'];
			}else{
				$row7['tpv'] = 0;
			}

			$mb_my_sales=$row2['tpv'];
			$mb_habu_sum=$row3['tpv'];

			if($mb_my_sales==''){ $mb_my_sales=0; }
			if($mb_habu_sum==''){$mb_habu_sum=0;}

			if ($mrow[cc_run]==0){  //업데이트가 안되었으면
				$sql  = "update g5_member set mb_my_sales=".$mb_my_sales." , mb_habu_sum=".$mb_habu_sum."   where mb_id='".$row[c_id]."'";
				sql_query($sql);
			}

			if (!$row['b_child']) $row['b_child']=1;
			//if (!$row['c_child']) $row['c_child']=1;

		?>


				{ id:"<?=$row[c_class]?>", pId:"<?=$parent_id?>", name:"<img src='/img/<?=$row[mb_level]?>.gif' width=12 align=absmiddle> <img src='/img/pool/<?=$row[pool_level]?>.gif' width=12 align=absmiddle> [<?=get_member_label($row[mb_level])?>-<?=(strlen($row[c_class])/2)-1?>-<?=($row[c_child])?>-<?=($row[b_child]-1)?>] <?=$row[c_name]?> (<?=$row[c_id]?>)  <img src='img/dot.gif'> 누적매출 <?=number_format($row3[tpv]/$order_split)?> <img src='img/dot.gif'> 30일매출 <?=number_format($row5[tpv]/$order_split)?>  <img src='img/dot.gif'> 바이너리레그매출 <?=number_format($row6[tpv]/$order_split)?> - <?=number_format($row7[tpv]/$order_split)?> ", open:true, click:false},
		<?
		}
		//업데이트 완료 
		if ($mrow[cc_run]==0){
			$sql = "update ".$class_name."_chk set cc_run=1 where cc_no='{$mrow[cc_no]}'";
			sql_query($sql);
		}
		?>
			];

			$(document).ready(function(){
				$.fn.zTree.init($("#treeDemo"), setting, zNodes);
			});

			//-->
		</SCRIPT>
</div>

<style type="text/css">

.ztree li a:hover {text-decoration:none; background-color: #FAD7E0;}

</style>
<script type="text/javascript">
<!--

$(document).ready(function(){
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	<?if ($stx && $sfl){?>
		btn_search();
	<?}?>
});
function open_register(){
	window.open('/shop/recommend_register.php?gp=at&now_id='+$("#now_id").val(), 'set_register', 'width=600, height=500, resizable=no, scrollbars=no, left=0, top=0');
}
function btn_print(){

	var html = $('#treeDemo');

	var strHtml = '<!doctype html><html lang="ko"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="imagetoolbar" content="no" /><title></title><link rel="stylesheet" type="text/css" media="all" href="/js/zTreeStyle.css"></';
	strHtml += 'head><body style="padding:0px;margin:0px;"><div class="zTreeDemoBackground left"><ul id="treeDemo" class="ztree"><!--body--></ul></div></body></html>';
	var strContent = html.html();
	var objWindow = window.open('', 'print', 'width=640, height=800, resizable=yes, scrollbars=yes, left=0, top=0');
	if(objWindow)
	{
		 var strSource = strHtml;
		 strSource  = strSource.replace(/\<\!\-\-body\-\-\>/gi, strContent);

		 objWindow.document.open();
		 objWindow.document.write(strSource);
		 objWindow.document.close();

		 setTimeout(function(){ objWindow.print(); }, 500);
	}

}
function btn_menu2(){
	if($("#div_left").css("display") == "none"){ 
		$("#div_left").show();
		$("#div_right").css("width","85%");
	} else { 
		$("#div_left").hide(); 
		$("#div_right").css("width","100%");
	} 
}
function btn_search(){
	if($("#stx").val() == ""){ 
		//alert("검색어를 입력해주세요.");
		$("#stx").focus();
	}else{
		$.post("ajax_get_tree_member.php", $("#sForm").serialize(),function(data){
			$("#div_result").html(data);
		});
	}
}
function go_member(go_id){
	$("#now_id").val(go_id);
	$.get("ajax_get_up_member.php?gubun=<?=$gubun?>&go_id="+go_id, function (data) {

		data = $.trim(data);
		temp = data.split("|");

		data2 = "<table style='width:100%'>";
		data2 += "			<tr>";
		data2 += "				<td bgcolor='#f9f9f9' height='30' style='padding-left:10px'><b>상위 회원</b></td>";
		data2 += "			</tr>";
		for(i=(temp.length-1);i>=0;i--){
			data2 += temp[i];
		}
		
		data2 += "</table>";

		$('#div_member').html(data2);

		$.get("ajax_get_tree_load.php?fr_date=<?=$fr_date?>&to_date=<?=$to_date?>&go_id="+go_id, function (data) {
			$('#div_right').html(data);
		});
	});
}
function btn_org(){
	if (confirm("조직도를 재구성 하시겠습니까?")){
		location.href="member_tree.php?reset=1&sfl=<?=$sfl?>&stx=<?=$stx?>";
	}
}
//-->
</script>

<?php
include_once ('./admin.tail.php');

?>