<?php
	
	include_once('./_common.php');
	include_once('../adm/inc.member.class.php');

	if($_GET['input_id']){

		$sql = "select mb_id from {$g5['member_table']}  where mb_id like '%".$_GET['input_id']."%' And mb_id !=  '". $member['mb_id']."' " ;

		//print_R($sql );
		//테스트 $sql = "select mb_id from g5_member_190619  where mb_id like '%".$_GET['mb_id']."%'";
		$result = sql_query($sql);

		$rows = array();

		while($r = mysqli_fetch_assoc($result)) {
			$rows[] = $r;
		}
		print json_encode($rows);
	}else{
		print "[]";
	}

?>