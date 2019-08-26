<?php
	include_once('./_common.php');

	if($_POST['mb_id']){
		$sth = sql_query("select mb_id from {$g5['member_table']}  where mb_id like '%".$_POST['mb_id']."%' and grade >= 1");
		$rows = array();
		while($r = mysqli_fetch_assoc($sth)) {
			$rows[] = $r;
		}
		print json_encode($rows);
	}else{
		print "[]";
	}

?>