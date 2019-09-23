<?php
$sub_menu = "600300";
include_once('./_common.php');

//auth_check($auth[$sub_menu], 'r');

$v7_cost = number_format(get_coin_cost('v7'),2);



$benefit = "SELECT allowance_name FROM soodang_pay WHERE (1) GROUP BY allowance_name ";
$rrr = sql_query($benefit);

$allowcnt=0;
for ($i=0; $allowance_name=sql_fetch_array($rrr); $i++) {   
	$nnn="allowance_chk".$i;
	$html.= "<input type='checkbox' name='".$nnn."' id='".$nnn."'";
	
	if($$nnn!=''){
		$html.=" checked='true' ";
	}		

	$html.=" value='".$allowance_name['allowance_name']."'>".$allowance_name['allowance_name']."&nbsp;&nbsp;";


	if(${"allowance_chk".$i}!=''){
		if($allowcnt==0){
			$sql_search .= " and ( (allowance_name='".${"allowance_chk".$i}."')";
		}else{
			$sql_search .= "  or ( allowance_name='".${"allowance_chk".$i}."' )";
		}

		
			$qstr.='&'.$nnn.'='.$allowance_name['allowance_name'];
		
		$allowcnt++;

	}
}

if ($allowcnt>0) $sql_search .= ")";


$token = get_token();

$fr_date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $fr_date);
$to_date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $to_date);

$qstr.='&fr_date='.$fr_date.'&to_date='.$to_date.'&chkc='.$chkc.'&chkm='.$chkm.'&chkr='.$chkr.'&chkd='.$chkd.'&chke='.$chke.'&chki='.$chki;
$qstr.='&diviradio='.$diviradio.'&r='.$r;
$qstr.='&stx='.$stx.'&sfl='.$sfl;
$qstr.='&aaa='.$aaa;

$sql_common = " from soodang_pay where (1) ";

if(!$fr_date){
	$fr_date=date("Y-m-d");
	$to_date=$fr_date;
	
}

if(($allowance_name) ){
	$sql_search .= " and (";
		if($chkc){
		$sql_search .= " allowance_name='".$allowance_name."'";
		}
 $sql_search .= " )";
 
}/*else if($dv_gubun){
	 $sql_search .= " and dv_gubun='".$dv_gubun."'";
}
*/

if($_GET['start_dt']){
	$sql_search .= " and day >= '".$_GET['start_dt']."'";
	$qstr .= "&start_dt=".$_GET['start_dt'];
}
if($_GET['end_dt']){
	$sql_search .= " and day <= '".$_GET['end_dt']."'";
	$qstr .= "&end_dt=".$_GET['end_dt'];
}

if ($stx) {
    $sql_search .= " and ( ";
	if(($sfl=='mb_id') || ($sfl=='mb_id')){
            $sql_search .= " ({$sfl} = '{$stx}') ";
          
	}else{
            $sql_search .= " ({$sfl} like '%{$stx}%') ";
          
    }
    $sql_search .= " ) ";
}

$sql = " select count(*) as cnt
            {$sql_common}
            {$sql_search}
            {$sql_order} ";



$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함


$sql_order='order by day desc';


$sql = "select * 
		{$sql_common}
        {$sql_search}
        {$sql_order}
        limit {$from_record}, {$rows} ";


$result = sql_query($sql);


$send_sql = $sql;
$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '수당계산 및 수당리스트';
include_once ('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$colspan = 16;


 ?>   
 


<div class="local_ov01 local_ov">

	<?
	/*
    if (isset($mb['mb_id']) && $mb['mb_id']) {
    	  $row2 = sql_fetch(" select sum(dv_money) as sum_dividend_money ,  sum(dv_tax) as sum_dividend_tax,  sum(dv_money-dv_tax) as sum_dividend from dividend where dv_paid<>'R' and dv_paid<>'S' and mb_id='{$mb['mb_id']}' {$sql_search} ");
         echo '&nbsp;전체 합계:  총액('.number_format($row2['sum_dividend_money']).'원)  / 세금('.number_format($row2['sum_dividend_tax']).'원)  / 실 지급금('.number_format($row2['sum_dividend']).'원)';
    } else {
        $row2 = sql_fetch(" select sum(dv_money) as sum_dividend_money,  sum(dv_tax) as sum_dividend_tax,  sum(dv_money-dv_tax) as sum_dividend from dividend where dv_paid<>'R' and dv_paid<>'S' {$sql_search}");
        
        echo '&nbsp;전체 합계:  총액('.number_format($row2['sum_dividend_money']).'원)  / 세금('.number_format($row2['sum_dividend_tax']).'원)  / 실 지급금('.number_format($row2['sum_dividend']).'원)';
    }*/



	$ym=date('Y-01-01');

    ?>

	<!--누적 시작일자
		<input type="text" id="noo_start_day"  name="noo_start_day" value="<?=$ym?>" class="frm_input" size="12" maxlength="10"> &nbsp;|&nbsp;
	-->
		수당계산 기준일자
       <!-- <input type="text" name="fr_date" value="<?php if($fr_date){echo $fr_date; }else{echo date("Ymd");} ?>" id="fr_date" required class="required frm_input" size="13" maxlength="10">
        ~-->
        <label for="to_date" class="sound_only">기간 종료일</label>
        <input type="text" name="to_date" value="<?php if($to_date){echo $to_date; }else{echo date("Ymd");} ?>" id="to_date" required class="required frm_input" size="13" maxlength="10"> 
		&nbsp;
			<!--<input type="checkbox" name="save_noo_mon" id="save_noo_mon" value="1">매출정산+즉시계산--> 
			( PV<input type="radio" name="price" id="pv" value='pv' checked='true'><!--&nbsp;|&nbsp; BV<input type="radio" name="price" id="bv" value='bv' >&nbsp;|&nbsp;판매가<input type="radio" name="price" id="receipt" value='receipt'>&nbsp; -->)&nbsp;&nbsp;
            <!--<input type="checkbox" name="save_noo_mon" id="save_benefit" value="1">정산된 매출로 수당계산만&nbsp;&nbsp;&nbsp; -->

	<style>
		.benefit{color:white;border:0;padding: 5px 15px;height:40px;}
		.benefit.day{background:cornflowerblue}
		.benefit.upstair{background:steelblue}
		.benefit.recom{background:slateblue}
		.benefit.qpack{background:dodgerblue}
		.benefit.level{background:slategray}
		.benefit.bpack{background:teal}
		.benefit.black{background:black}
		.benefit.red{background:red}
		.benefit.hotpink{background:hotpink}
		.benefit:hover{background:black;}
	</style>

	<input type="submit" name="act_button" value=" 업스테어 "  class="frm_input benefit upstair" onclick="go_calc(5);">
	<input type="submit" name="act_button" value=" 일일 수당 지급 "  class="frm_input benefit day" onclick="go_calc(0);">
	<input type="submit" name="act_button" value=" 10X10 지급"  class="frm_input benefit recom" onclick="go_calc(1);">
	<input type="submit" name="act_button" value=" 레벨승급"  class="frm_input benefit level" onclick="go_calc(2);">
	<input type="submit" name="act_button" value=" 무한 매칭 지급 " class="frm_input benefit bpack" onclick="go_calc(3);">
	<input type="submit" name="act_button" value=" B팩 수당 지급"  class="frm_input benefit qpack" onclick="go_calc(4);">
	<input type="submit" name="act_button" value=" 전체수당지급"  class="frm_input benefit black" onclick="go_calc(6);">
	<input type="submit" name="act_button" value=" 어제까지자동지급"  class="frm_input benefit red" onclick="go_calc(7);">

	<!--
	<input type="submit" name="act_button" value=" 바이너리 보너스 "  class="frm_input" onclick="go_calc(2);">
	<input type="submit" name="act_button" value=" 바이너리 매칭 "  class="frm_input" onclick="go_calc(3);">
	<input type="submit" name="act_button" value=" EOS 팀 보너스 "  class="frm_input" onclick="go_calc(4);">
	<input type="submit" name="act_button" value=" EOS 후원 추천 수당 "  class="frm_input" onclick="go_calc(5);">
	-->

</div>



<style>
	.local_ov01{margin-bottom:0;}
	.sysbtn{background:mintcream;border-bottom:1px solid #ccc;display:block;width:100%;height:20px;top:10px; text-align:right;padding:10px; margin-bottom:15px;padding-top:15px; }
	.sysbtn .btn{margin:10px 0;padding:10px 15px;background:orange;font-size:11px;}
	.sysbtn .btn.btn2{background:orangered}
	.sysbtn .btn.btn3{background:pink}
	.sysbtn .btn:hover{background:black;color:white;text-decoration: none;}
</style>

<div class="sysbtn">
	<a href="./member_grade.php" class="btn btn2" >멤버 등급 수동 갱신</a>
	<a href="#" class="btn btn" onclick="clear_db('balance');">멤버 수당,V7,매출전환,level 초기화(출금,전환 제외)</a>
	<a href="#" class="btn btn3" onclick="clear_db('amt');">멤버 출금, 전환 내역 초기화</a>
	<!--<a href="#" class="btn btn3" onclick="clear_db('pack_order');">B팩,Q팩 구매 DB 초기화</a>-->
	<a href="#" class="btn btn2" onclick="clear_db('soodang');">수당 전체 DB 초기화</a>
</div>




<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">
	<label for="sfl" class="sound_only">검색대상</label>
	<select name="sfl" id="sfl">
		<option value="mb_id"<?php echo get_selected($_GET['sfl'], "mb_id"); ?>>회원아이디</option>>
		<option value="mb_name"<?php echo get_selected($_GET['sfl'], "mb_name"); ?>>회원이름</option>
	</select>

	<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
	<input type="text" name="stx" value="<?php echo $stx ?>" id="stx" class="frm_input">
	검색 기간 : <input type="text" name="start_dt" id="start_dt" placeholder="From" class="frm_input" value="<?=$_GET['start_dt']?>" /> 
	~ <input type="text" name="end_dt" id="end_dt" placeholder="To" class="frm_input" value="<?=$_GET['end_dt']?>"/>
	
	<?
	echo $html;
	?>

	<input type="submit" class="btn_submit" value="검색"/>
	<input type="button" class="btn_submit" value="엑셀" onclick="document.location.href='benefit_list_excel_out.php?<?echo $qstr?>'" />	
	<br/>
</form>

<form name="benefitlist" id="benefitlist">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="<?php echo $token ?>">
<div class="local_ov01 local_ov">
    <?php echo $listall ?>
    전체 <?php echo number_format($total_count) ?> 건 
</div>
<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
		<th scope="col">수당날짜</th>
		<th scope="col">회원아이디</a></th>
		<th scope="col">회원레벨</a></th>
        <th scope="col">수당이름</th>
        <th scope="col">추천인</th>     

        <th scope="col">발생수당 (USD)</a></th>	
		 <th scope="col">발생수당V7 </a></th>	
        <th scope="col">수당근거</a></th>				
    </tr>
    </thead>
    <tbody>

	<?php
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'bg'.($i%2);
		$soodang = $row['benefit'];
		$doodang2 = $row['benefit_usd'];
	?>

    <tr class="<?php echo $bg; ?>">
		<td width='100'><? echo $row['day'];?></td>
		<td class="td_num"><?php echo get_text($row['mb_id']); ?></td>
		<td width='100'><?= get_member_level($row['mb_level'])?></td>
		<td width='100'><?php echo get_text($row['allowance_name']); ?></td>
        <td width="150" ><?php echo get_text($row['mb_recommend']); ?></td>
		<td width="150" align='center'><?php echo Number_format($soodang,2)  ?></td>
		<td width="150" align='center'><?php echo Number_format($soodang/$v7_cost,2)  ?></td>
        <td width="500"><?php echo ($row['rec_adm']) ?></td>
    </tr>

    <?php
    }

    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>


</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>



    <div class="btn_confirm01 btn_confirm">
    	<? if($what=='u') { ?>  <input type="submit" id="submit" value="수정" class="btn_submit"> <? } else{  ?> <input type="submit" id="submit" value="등록" class="btn_submit">   <? } ?>
    </div>

    </form>

</section>


<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/common.js"></script>


<script>
	
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
	$("#start_dt, #end_dt").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

var str='';
function go_calc(n)
{


	if(document.getElementById("pv").checked==true){

		str=str+'&price=pv';
			
	}else if(document.getElementById("bv").checked==true){
		str=str+'&price=bv';
	}else{
		str=str+'&price=receipt';
	}

	var day_point = document.getElementById("to_date").value;

	str=str+'&to_date='+document.getElementById("to_date").value;
	str=str+'&fr_date='+document.getElementById("to_date").value;
/*
	if(document.getElementById("sales_yn_chk").checked==true){
		str=str+'&sales_yn_chk=1';
	}
*/

/*
	if(document.getElementById("iwol_yn_chk").checked==true){
		str=str+'&iwol_yn_chk=1';
	}
*/
	
	
	switch(n){
		case 0: 
			location.href='eos.daily.pay.php?'+str;         //일일수당
			break;
		case 1: 
			location.href='eos.benefit.immediate.php?'+str;// 추천수당
			break;
		case 2: 
			location.href='eos.member.level.php?'+str;// 멤버승급
			break;

		case 3: 
			location.href='eos.qpack.php?'+str;// Q팩
			break;
		case 4:
			location.href='eos.bpack.php?'+str;// B팩
			break;
		case 5: 
			location.href='eos.upstair.php?'+str;         //임시 해당일 업스테어
			break;
		case 6: 
			location.href='eos.all.php?'+str;         //전체수당지급
			break;
		case 7: 
			location.href='eos.auto.php?'+str;         //전체수당지급
			break;
		case 8: 
			location.href='eos.Bpack_auto.php?'+str;         //전체수당지급
			break;
	}
	
}
</script>
<?php
include_once ('./admin.tail.php');
?>
