<?php
include_once('./_common.php');
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
	
	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>

</head>
<body>
<?php
	//include_once('./mypage_head.php');
	//include_once('./mypage_left.php');
?>
	<div id="content">
<?php

if (!$is_member)
    goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));

// 테마에 mypage.php 있으면 include
if(defined('G5_THEME_SHOP_PATH')) {
    $theme_mypage_file = G5_THEME_SHOP_PATH.'/mypage.php';
    if(is_file($theme_mypage_file)) {
        include_once($theme_mypage_file);
        return;
        unset($theme_mypage_file);
    }
}

$g5['title'] = $member['mb_name'].'님 마이페이지';
//include_once('./_head.php');

include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

if ($gubun=="B"){
	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";
}else{
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";
}


// 쿠폰
$cp_count = 0;
$sql = " select cp_id
            from {$g5['g5_shop_coupon_table']}
            where mb_id IN ( '{$member['mb_id']}', '전체회원' )
              and cp_start <= '".G5_TIME_YMD."'
              and cp_end >= '".G5_TIME_YMD."' ";
$res = sql_query($sql);

for($k=0; $cp=sql_fetch_array($res); $k++) {
    if(!is_used_coupon($member['mb_id'], $cp['cp_id']))
        $cp_count++;
}


if ($_GET[go]=="Y"){
	goto_url("binary_tree.php?gubun=".$gubun."#org_start");
	exit;
}
?>
<!-- <p class="blk" style="height:60px;"></p> -->
<div class="pk_page">
<style type="text/css">
.pk_page {font-size:14px;}
span.btn,
a.btn {display:inline-block;*display:inline;*zoom:1;height:33px;line-height:33px;padding:0 15px;border-radius:3px;background-color:#1DC2BB;color:#fff;}
.infoBx {border:solid 2px rgba(39,48,62,0.4);border-radius:8px;margin-bottom:30px;}
.infoBx h3 {line-height:40px;font-size:15px;padding-left:20px;border-bottom:solid 1px rgba(0,0,0,0.1);background-color:rgba(39,48,62,0.05);}
.infoBx ul {margin:15px;}
.infoBx ul li {display:inline-block;*display:inline;*zoom:1;width:33%;line-height:40px;font-size:14px;color:#777;border-bottom:solid 1px #fff;}
.infoBx ul li.prc {color:rgba(59,105,178,1);}
.infoBx ul li span {display:inline-block;*display:inline;*zoom:1;color:#000;padding-left:20px;width:100px;background-color:rgba(39,48,62,0.05);margin-right:20px;}
</style>

<?
include_once('../new/inc.member.class.php');



$sql  = "select count(*) as cnt from g5_member";
$mrow = sql_fetch($sql);

$sql = "select * from g5_member_class_chk where mb_id='".$member[mb_id]."' and  cc_date='".date("Y-m-d",time())."' order by cc_no desc";
$row = sql_fetch($sql);

if ($mrow[cnt]>$row[cc_usr] || !$row[cc_no] || $_GET["reset"]){

	make_habu('');

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

$sql = "select * from g5_member_bclass_chk where mb_id='".$member[mb_id]."' and  cc_date='".date("Y-m-d",time())."' order by cc_no desc";
$row = sql_fetch($sql);

if ($mrow[cnt]>$row[cc_usr] || !$row[cc_no] || $_GET["reset"]){

	make_habu('B');

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
		goto_url("binary_tree.php?gubun=".$gubun."&sfl=".$sfl."&stx=".$stx."&gubun=".$gubun);
		exit;		
	}
}

if ($mb_org_num){
	if ($mb_org_num>8) $mb_org_num = 8;
	$sql = "update g5_member set mb_org_num='".$mb_org_num."' where mb_id='".$member[mb_id]."'";
	sql_query($sql);	
	$member[mb_org_num] = $mb_org_num;
}


?>


<style type="text/css">
	#div_right table { border:0px }
	.btn_menu {padding:5px;border:1px solid #ced9de;background:rgb(246,249,250);cursor:pointer}
</style>


<div style="padding:0px 0px 0px 10px;">
	<a name="org_start"></a>
	<div style="float:left">
		<input type="button" class="btn_menu" style="color:#636363;margin-top: 10px;" value="Close Menu" onclick="btn_menu()">
		<input type="button" class="btn_menu" style="color:red;margin-top: 10px;" value="Show Entire Binary" onclick="location.href='binary_tree.php?gubun=<?=$gubun?>&go=Y'">
	</div>
	<div style="float:right;padding-right:10px;margin-top: 10px;">
	<button type='button' id='zoomOut' class='zoom2-btn'>Zoom Out</button>
	<button type='button' id='zoomIn' class='zoom-btn'>Zoom In</button>
	<button type="button" class="my-class" onclick="clickExportButton();">Print Tree</button>

	<input type="button"  class="my-class" value="Refresh Tree" onclick="btn_org()">
	</div>
</div>
<div style="padding-top:10px;clear:both"></div>
<div id="div_left" style="width:15%;float:left;min-height:710px;border:">
	<div style="margin-left:10px;padding:5px 5px 5px 5px;border:1px solid #d9d9d9;height:100%">


<?
if (!$fr_date) $fr_date = Date("Y-m-d", time()-60*60*24*365);
if (!$to_date) $to_date = Date("Y-m-d", time());
?>



		<form name="sForm2" id="sForm2" method="get" action="binary_tree.php">
		<input type="hidden" name="now_id" id="now_id" value="<?=$now_id?>">

		<table style="width:100%;color:#636363;">
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px;">
					<div style="float:left">
						<strong>Show</strong>
					</div>
					<div style="float:right;">
						<input type="text" id="mb_org_num"  name="mb_org_num" value="<?php echo $member[mb_org_num]; ?>" class="frm_input" style="width:40px;text-align:center" size="3" maxlength="3"> Generation &nbsp;
					</div>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="20" style="padding:10px 10px 10px 10px" align=left>
				<input type="radio" id="gubun" name="gubun" onclick="document.sForm2.submit();" value=""<?if ($gubun=="") echo " checked"?>> Sponsor Leg <br>
				<input type="radio" id="gubun" name="gubun" onclick="document.sForm2.submit();" value="B"<?if ($gubun=="B") echo " checked"?>>  Binary Leg

				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px"><b>Select date to search</b></td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding:10px 10px 10px 10px" align=center>
				<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input" style="width:100%" size="10" maxlength="10"> ~
				<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" style="width:100%" size="10" maxlength="10">

				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" align="center">
				<input type="submit"  class="btn_submit" style="padding:5px" value="Apply">
				</td>
			</tr>
		</table>
		</form>
		<div id="div_member"></div>
		<form name="sForm" id="sForm" method="post" style="padding-top:10px" onsubmit="return false;">
		<input type="hidden" name="gubun" value="<?=$gubun?>">
		<table style="width:100%;color:#636363;">
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding-left:10px"><b>Membership Search</b></td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" style="padding:10px 10px 10px 10px">
				
				<select name="sfl" id="sfl">
				    <option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>Username</option>
					<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>Name</option>
					</select>
				<div style="padding-top:5px">
				<label for="stx" class="sound_only">Keword</label>
				<input type="text" name="stx" value="<?php echo $stx ?>" id="stx"  class="required frm_input" style="width:100%;" onkeypress="event.keyCode==13?btn_search():''">
				</div>
				</td>
			</tr>
			<tr>
				<td bgcolor="#f2f5f9" height="30" align="center">
				<input type="button" onclick="btn_search();" class="btn_submit" style="padding:5px" value="Search">
				</td>
			</tr>
		</table>
		</form>

		<div id="div_result" style="margin-top:5px;overflow-y: auto;height:418px">

		</div>
	</div>
</div>


  <link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
  
  <link rel="stylesheet" href="/adm/css/font-awesome.min.css">
  
  <link rel="stylesheet" href="/new/jquery.orgchart.css">
  <script type="text/javascript" src="/new/jquery.orgchart.js"></script>

  <script type="text/javascript" src="/adm/js/bluebird.min.js"></script>
  <script type="text/javascript" src="/adm/js/html2canvas.min.js"></script>
  <script type="text/javascript" src="/adm/js/jspdf.min.js"></script>
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
<style type="text/css">

.orgChart{

}

#chart-container {
margin:0px 10px 0px 10px;
min-height:573px;
border:1px solid #d9d9d9;
text-align:center !important;
  position: relative;
  display: inline-block;
  width: calc(100% - 24px);
  overflow: auto;
  text-align: center;
}

.orgchart .node {
  box-sizing: border-box;
  display: inline-block;
  position: relative;
  margin: 0;
  padding: 3px;
  /* height:143px; */
  text-align: center;
  width: 130px;
}

.orgchart .node .title {
	background:#fff;
	border:2px solid rgb(95, 95, 95);
	color:#000;
	height:150px;
	font-weight:normal;
	line-height:15px;
	padding-top:5px;
	cursor:pointer;
}
.orgchart .node .title .symbol{
	display:none;
}
.orgchart .node .title .dec{
	font-size: 11px;
	line-height: 11px;
	/* margin-top:5px; */
}
.orgchart .node .title .dec.p{
	margin-top:5px;
}

.orgchart .node .title .mb {
	margin: 3px 0;
	word-break: break-all;
	white-space: normal;
	font-weight: bold;
	font-size: 14px;
	line-height: 11px;
	padding: 0;
	color: orange;
}
</style>
<style type="text/css">
.zoom-btn {
  display: inline-block;
  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  touch-action: manipulation;
  cursor: pointer;
  user-select: none;
  color: #fff;
  background-color: #364fa0;
  border: 1px solid transparent;
  border-color: #364fa0;
  border-radius: 4px;
}
.zoom2-btn {
  display: inline-block;
  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  touch-action: manipulation;
  cursor: pointer;
  user-select: none;
  color: #fff;
  background-color: #364fa0;
  border: 1px solid transparent;
  border-color: #364fa0;
  border-radius: 4px;
}
.my-class {
  display: inline-block;

  padding: 6px 12px;
  margin-bottom: 0;
  font-size: 14px;
  font-weight: 400;
  line-height: 1.42857143;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  touch-action: manipulation;
  cursor: pointer;
  user-select: none;
  color: #fff;
  background-color: #5cb85c;
  border: 1px solid transparent;
  border-color: #4cae4c;
  border-radius: 4px;
}
</style>
<style type="text/css">
.oc-export-btn {
    display: none;
}
</style>
<script type="text/javascript">
<!--
	function clickExportButton(){
		 $(".oc-export-btn").click();
	}
//-->
</script>
<script type="text/javascript">
<!--

$(document).ready(function(){
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	<?if ($stx && $sfl){?>
		btn_search();
	<?}?>
});
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
		//$('#div_member').html(data);
		$.get("ajax_get_org_load.php?gubun=<?=$gubun?>&fr_date=<?=$fr_date?>&to_date=<?=$to_date?>&go_id="+go_id, function (data) {
			$('#div_right').html(data);
		});
	});

/*

*/
}
function set_member(set_id,set_type){
	window.open('recommend_set.php?set_id='+set_id+'&set_type='+set_type, 'set_recomm', 'width=520, height=500, resizable=no, scrollbars=yes, left=0, top=0');
}
function open_register(){
	window.open('recommend_register.php?gp=mo&now_id='+$("#now_id").val(), 'set_register', 'width=600, height=500, resizable=no, scrollbars=no, left=0, top=0');
}

function edit_member(edit_id){
	go_member(edit_id);
}
function btn_print(){

	var html = $('#chart-container');

	var strHtml = '<!doctype html><html lang="ko"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta http-equiv="imagetoolbar" content="no" /><title></title><link rel="stylesheet" type="text/css" media="all" href="jquery.orgchart.css"><link rel="stylesheet" type="text/css" media="all" href="chart.css"></';
	strHtml += 'head><body style="padding:0px;margin:0px;"><div id="chart-container" class="orgChart"><!--body--></div></body></html>';
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
function btn_menu(){
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

function btn_org(){
	if (confirm("조직도를 재구성 하시겠습니까?")){
		location.href="binary_tree.php?reset=1&sfl=<?=$sfl?>&stx=<?=$stx?>&gubun=<?=$gubun?>";
	}
}
//-->
</script>
<div id="div_right" style="width:85%;float:left;min-height:500px">
<!--  -->

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
$org_num     = 0;

$sql = "select c.c_id,c.c_class,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,(select pool_level from g5_member where mb_id=c.c_id) as pool_level,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select count(*) from g5_member where mb_recommend=c.c_id) as c_child,(select mb_b_child from g5_member where mb_id=c.c_id) as b_child,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='L') as b_recomm,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='R') as b_recomm2,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child, (select it_pool1 from g5_member where mb_id=c.c_id) as it_pool1, (select it_pool2 from g5_member where mb_id=c.c_id) as it_pool2, (select it_pool3 from g5_member where mb_id=c.c_id) as it_pool3, (select it_pool4 from g5_member where mb_id=c.c_id) as it_pool4, (select it_GPU from g5_member where mb_id=c.c_id) as it_GPU from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.c_id='$go_id'";


//echo $sql;
$srow = sql_fetch($sql);

$my_depth = strlen($srow['c_class']);


if ($order_proc==1){
	$sql  = "select today as tpv from ".$ngubun."today where mb_id='".$srow[c_id]."'";
	$row2 = sql_fetch($sql);

	$sql  = "select noo as tpv from ".$ngubun."noo where mb_id='".$srow[c_id]."'";
	$row3 = sql_fetch($sql);

	$sql  = "select thirty as tpv from ".$ngubun."thirty where mb_id='".$srow[c_id]."'";
	$row5 = sql_fetch($sql);
}else{

	$sql  = "select sum(od_receipt_price) as tprice,sum(pv) as tpv from g5_shop_order where mb_id='".$srow[c_id]."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
	$row2 = sql_fetch($sql);

	$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member[mb_id]."' and c_class like '".$srow[c_class]."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
	$row3 = sql_fetch($sql);
	// and c_id<>'".$srow[c_id]."'

	//이전 30일
	$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member[mb_id]."' and c_class like '".$srow[c_class]."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
	$row5 = sql_fetch($sql);
	// and c_id<>'".$srow[c_id]."'
}

if ($srow[b_recomm]){
	//$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$srow[b_recomm]."' and od_receipt_time between '".$to_date." 00:00:00' and '".$to_date." 23:59:59'";
    
	$sql  = "select (mb_my_sales+habu_day_sales) as tpv from g5_member where mb_id ='".$srow[b_recomm]."' and sales_day='".date("Y-m-d")."'";
	$row6 = sql_fetch($sql);
	if (!$row6['tpv']) $row6['tpv'] = 0;

	$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$srow[b_recomm]."'";
	$row8 = sql_fetch($sql);

	$row6['tpv'] += $row8['tpv'];
}else{
	$row6['tpv'] = 0;
}

//바이너리 오른쪽 오늘 매출
if ($srow[b_recomm2]){
//	$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$srow[b_recomm2]."' and od_receipt_time between '".$to_date." 00:00:00' and '".$to_date." 23:59:59'";

$sql  = "select (mb_my_sales+habu_day_sales) as tpv from g5_member where mb_id ='".$srow[b_recomm2]."' and sales_day='".date("Y-m-d")."'";
	$row7 = sql_fetch($sql);
	if (!$row7['tpv']) $row7['tpv'] = 0;

	$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$srow[b_recomm2]."'";
	$row9 = sql_fetch($sql);
	$row7['tpv'] += $row9['tpv'];
}else{
	$row7['tpv'] = 0;
}

$sql    = "select c_class from ".$class_name." where mb_id='".$member[mb_id]."' and c_id='".$go_id."'";
$row4   = sql_fetch($sql);
$mdepth = (strlen($row4[c_class])/2);

if (!$srow['b_child']) $srow['b_child']=1;
//if (!$srow['c_child']) $srow['c_child']=1;
?>
		<ul id="org" style="display:none;"  >
			<li>
				<?=(strlen($srow[c_class])/2)-1?>-<?=($srow[c_child])?>-<?=($srow[b_child]-1)?>|<?=get_member_label($srow[mb_level])?>|<?=$srow[c_id]?>|<?=$srow[c_name]?>|<?=number_format($row3[tpv]/$order_split)?>|<?=number_format($row5[tpv]/$order_split)?>|<?=$srow[mb_level]?>|<?=number_format($row6[tpv]/$order_split)?>|<?=number_format($row7[tpv]/$order_split)?>|<?=$srow[it_pool1]?>|<?=$srow[it_pool2]?>|<?=$srow[it_pool3]?>|<?=$srow[it_pool4]?>|<?=$srow[it_GPU]?>|<?=(strlen($srow[c_class])/2)-1?>|<?=($srow[c_child])?>|<?=($srow[b_child]-1)?>|<?=$gubun?>
<?
			get_org_down($srow);
?>
			</li>
<?
?>
	</ul>

    <div id="chart-container" class="orgChart"></div>
    <script>
    $(function() {
      $('#chart-container').orgchart({
        'data' : $('#org'),
		 'zoom': false
		});

		var $container = $('#chart-container');
		
		var $chart = $('.orgchart');
		$chart.css('transform', "scale(1,1)");
		var div = $chart.css('transform');
		var values = div.split('(')[1];
		values = values.split(')')[0];
		values = values.split(',');
		var a = values[0];
		var b = values[1];
		var currentZoom = Math.sqrt(a*a + b*b);
		var zoomval = .8;
		$container.scrollLeft(($container[0].scrollWidth - $container.width())/2);
		var my_num = 0;

		// zoom buttons	
		$('#zoomIn').on('click', function () {
			my_num++;
			zoomval = currentZoom += 0.1;
			$chart.css("transform",'matrix('+zoomval+', 0, 0, '+zoomval+', 0 ,'+((my_num)*45)+')');    
			$container.scrollLeft(($container[0].scrollWidth - $container.width())/2);
		});

		$('#zoomOut').on('click', function () {
			zoomval = currentZoom -= 0.1;
			my_num--;
			$chart.css("transform",'matrix('+zoomval+', 0, 0, '+zoomval+', 0 ,'+((my_num)*45)+')');    
			$container.scrollLeft(($container[0].scrollWidth - $container.width())/2);

		});
    });
    </script>


</div>


</div><!-- // pk_page -->

<!-- 마이페이지 시작 { -->
<div id="smb_my">

    <!-- 회원정보 개요 시작 { -->
    <section id="smb_my_ov">
        <h2>회원정보 개요</h2>

        <div id="smb_my_act" style="display:none;">
            <ul>
                <li><a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=member_leave.php" onclick="return member_leave();" class="btn02">회원탈퇴</a></li>
            </ul>
        </div>
    </section>
    <!-- } 회원정보 개요 끝 -->

</div>

<script>
$(function() {
    $(".win_coupon").click(function() {
        var new_win = window.open($(this).attr("href"), "win_coupon", "left=100,top=100,width=700, height=600, scrollbars=1");
        new_win.focus();
        return false;
    });
});

function member_leave()
{
    return confirm('정말 회원에서 탈퇴 하시겠습니까?')
}
</script>
<!-- } 마이페이지 끝 -->
	</div>
<?php
//	include_once('./mypage_footer.php')
?>
</html>