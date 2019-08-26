<?
include_once('./admin.head.php');
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

if (empty($fr_date)) $fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-7 day"));
if (empty($to_date)) $to_date = G5_TIME_YMD;

$qstr = "fr_date=".$fr_date."&amp;to_date=".$to_date."&amp;to_id=".$fr_id;
$query_string = $qstr ? '?'.$qstr : '';
?>

<style>
	.sch_last{display:inline-block;}
	.btn_submit{width:100px;margin-left:20px;}
	.black_btn{background:#333 !important; border:1px solid black !important; color:white;}
</style>

<form name="fvisit" id="fvisit" class="local_sch02 local_sch" method="get">
<div class="sch_last">
    <strong>기간별검색</strong>
    <input type="text" name="fr_date" value="<?php echo $fr_date ?>" id="fr_date" class="frm_input" size="15" style="width:120px" maxlength="10">
    <label for="fr_date" class="sound_only">시작일</label>
    ~
    <input type="text" name="to_date" value="<?php echo $to_date ?>" id="to_date" class="frm_input" size="15" style="width:120px" maxlength="10">
    <label for="to_date" class="sound_only">종료일</label>
    
</div>

<div class="sch_last" style="margin-left:20px">
    <strong>멤버아이디</strong>
    <input type="text" name="fr_id" value="<?php echo $fr_id ?>" id="fr_id" class="frm_input" size="15" style="width:120px" maxlength="10">
    <label for="fr_id" class="sound_only">회원아이디</label>
</div>
<input type="submit" value="검색" class="btn_submit">
</form>

<ul class="anchor">
    <li><a href="./adm.eos.incom.enable.php<?php echo $query_string ?>">멤버 입금 항목</a></li>
    <li><a href="./adm.eos.incom.php<?php echo $query_string ?>">전체 입금 항목</a></li>
	<li style="float:right"><a href="https://bloks.io/account/eosblockteam" target="_blank" class="btn black_btn"> bloks.io 사이트확인</a></li>
</ul>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});

function fvisit_submit(act)
{
    var f = document.fvisit;
    f.action = act;
    f.submit();
}
</script>
