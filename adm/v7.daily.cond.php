<?php
$sub_menu = "600990";
include_once('./_common.php');
include_once ('./admin.head.php');
auth_check($auth[$sub_menu], 'r');
$token = get_token();

$sql = "select * from eos_daily_paid  order by idx";

$list = sql_query($sql);

?>

<style>
	table {width:100%;}
	table td { height:30px;}
	.btn_ly{text-align:center;}
	hr{height:1px;float:left;width:60%;display:block;background:#333;margin:20px 0;}
	.btn_confirm.btn_submit:hover{background:black !important;}
</style>
<form name="allowance" id="allowance" method="post" action="./update_daily_cond.php">
<input type="hidden" name="type" value="daily">
<div class="tbl_head02 tbl_wrap">
    <table style="width:60%">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
	<p> Daily Bonus Condition </p>
    <tr>
        <th scope="col" width="20">No</th>
        <th scope="col" width="40">회원등급</th>	
		<th scope="col" width="40">COLOR</th>
		<th scope="col" width="100">데일리수당비율</th>
		<th scope="col" width="100">1단계매칭</th>
		<th scope="col" width="100">2~10단계매칭</th>
	</tr>

<?while($row=sql_fetch_array($list)){?>
	<tr>
	<td style="text-align:center"><input type="hidden" name="idx[]" value="<?=$row['idx']?>"><?=$row['idx']?></td>
	<td style="text-align:center"><?=$row['eos_grade']?></td>
	<td style="text-align:center;background: <?=$row['grade_txt']?>;color:black;"><?=$row['grade_txt']?></td>
	<td style="text-align:center"><input name="rate[<?=$row['idx']?>]"  value="<?=$row['eos_per']?>"></input></td>
	<td style="text-align:center"><input name="recom_1[<?=$row['idx']?>]"  value="<?=$row['recom_1']?>"></input></td>
	<td style="text-align:center"><input name="recom_2[<?=$row['idx']?>]"  value="<?=$row['recom_2']?>"></input></td>
	</tr>
<?}?>

	<tr>
	<td colspan=6 height="100px" style="padding:50px 0" class="btn_ly">
		
			<input  style="align:center;padding:10px 30px;background:cornflowerblue;" type="submit" class="btn btn_confirm btn_submit" value="저장하기" id="com_send"></input>
	
	</td>
	</tr>
</table>
</div>
</form>
<!--
<?
$team_rate = "select * from eos_team_paid  ";
$rate_rst = sql_fetch($team_rate);
?>

</form>
<div class="tbl_head02 tbl_wrap">
<form name="allowance" id="allowance" method="post" action="./update_eos_daily_cond.php">
<input type="hidden" name="type" value="team">
<p> EOS Team Bonus Condition </p>
 Team Bonus Rate : <input name="rate"  value="<?=$rate_rst['rate']?>"></input>
<input  style="align:center" type="submit" value="저장하기">
</form>
-->

<!--
<hr> 
<?
$recom_rate = "select recom_history,
   MAX(IF(`recom_grade` = 1, recom_per, NULL)) '1m',
   MAX(IF(`recom_grade` = 2, recom_per, NULL)) '2m',
   MAX(IF(`recom_grade` = 3, recom_per, NULL)) '3m',
   MAX(IF(`recom_grade` = 4, recom_per, NULL)) '4m',
	MAX(IF(`recom_grade` = 5, recom_per, NULL)) '5m',
	MAX(IF(`recom_grade` = 6, recom_per, NULL)) '6m',
	MAX(IF(`recom_grade` = 7, recom_per, NULL)) '7m',
	MAX(IF(`recom_grade` = 8, recom_per, NULL)) '8m',
	MAX(IF(`recom_grade` = 9, recom_per, NULL)) '9m',
	MAX(IF(`recom_grade` = 10, recom_per, NULL)) '10m',
	
	MAX(IF(`recom_grade` = 10, recom_per, NULL)) 'rate'
			   
from eos_daily_immediate 

group BY recom_history";

$rate_rst = sql_query($recom_rate);
?>

<div class="tbl_head02 tbl_wrap" style="clear:both">
<form name="allowance" id="allowance" method="post" action="./update_daily_cond.php">
<input type="hidden" name="idx[]" value="<?=$row['idx']?>">
    <table style="width:60%">
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
	<p> Recommend Bonus Condition </p>
    <tr>
        <th scope="col" width="100">Grade</th>
		<th scope="col" width="100">1</th>	  
		<th scope="col" width="100">2</th>	  
		<th scope="col" width="100">3</th>	  
		<th scope="col" width="100">4</th>	  
		<th scope="col" width="100">5</th>	  
		<th scope="col" width="100">6</th>	  
		<th scope="col" width="100">7</th>	  
		<th scope="col" width="100">8</th>	  
		<th scope="col" width="100">9</th>	
		<th scope="col" width="100">10</th>	
		<th scope="col" width="100" style="letter-spacing:1px;">rate(%)</th>	
	</tr>

<?while($row=sql_fetch_array($rate_rst)){?>
	<tr>
	<td style="text-align:center"><?=$row['recom_history']?></td>
	<td style="text-align:center"><?=$row['1m']?></td>
	<td style="text-align:center"><?=$row['2m']?></td>
	<td style="text-align:center"><?=$row['3m']?></td>
	<td style="text-align:center"><?=$row['4m']?></td>
	<td style="text-align:center"><?=$row['5m']?></td>
	<td style="text-align:center"><?=$row['6m']?></td>
	<td style="text-align:center"><?=$row['7m']?></td>
	<td style="text-align:center"><?=$row['8m']?></td>
	<td style="text-align:center"><?=$row['9m']?></td>
	<td style="text-align:center"><?=$row['10m']?></td>

	<td style="text-align:center"><input name="rate[<?=$row['recom_history']?>]" style="text-align:center"value="<?=$row['rate']?>"></input></td>
	</tr>
<?}?>

	<tr>
	<td colspan=12 height="100px"  class="btn_ly">
		<input  style="align:center;padding:10px 30px;background:cornflowerblue;" type="submit" class="btn btn_confirm btn_submit" value="저장하기" id="com_send"></input>
	</td>
	</tr>
</table>

</form>
-->
</div>