<?php
$sub_menu = "600300";
include_once('./_common.php');

$sql = "select * from {$g5['member_table']} where  mb_id in (select c_id from g5_member_class where mb_id='".$member['mb_id']."')  ";

if ($binary_seach) {
	$sql .= " and mb_id like '{$binary_seach}%' ";
}
$sql .= " order by mb_name";

$result = sql_query($sql);
?>
		<table style="width:100%">
			<tr>
				<td bgcolor="#f9f9f9" height="30" style="padding-left:10px"><b>RESEULT</b></td>
			</tr>
<?
for ($i=0; $row=sql_fetch_array($result); $i++) {
?>
			<tr>
				<td bgcolor="#f9f9f9"  style="padding:10px 0px 10px 10px">
				<span style="cursor:pointer" onclick="go_member('<?=$row[mb_id]?>')"><?=$row[mb_name]?> (<?=$row[mb_id]?>)</span>
				</td>
			</tr>
<?
 }
	if ($i == 0)
		echo "<tr><td height=30 align=center>Not exist matching member.</td></tr>";
?>
		</table>