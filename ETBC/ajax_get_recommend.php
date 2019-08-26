<?php

include_once('./_common.php');
$search_id = $_POST['search_id'];

$sql = "select mb_id from {$g5['member_table']}  where mb_id like '".$search_id."%'"; 

$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
?>	
	<div class="user"><?=$row[mb_id]?> </div>
<?
}
?>