<?php
	
include_once('./_common.php');
	include_once('../adm/inc.member.class.php');
	
	$member_id = $member['mb_id'];
	$member_keyword = $_GET['input_id'];
	
	$data = array();
	$DB = array();
	
	/*검색 함수*/
	function searchForId($array) {
	   global $member_keyword;

	   foreach ($array as $key => $val) {
		   if ( strpos($val,$member_keyword) > -1) {
			   return $val;
		   }
	   }
	}
	
	/*상위 멤버 구해오기*/
	do{ 
		$sql = "select re_member.mb_recommend as DB from (select mb_recommend from g5_member where mb_id = '".$member_id."') AS re_member " ;
		$result = sql_fetch($sql);

		$db_array = array('mb_id' =>$result['DB']);
		array_push($DB, $db_array);

		$member_id = $result['DB'];
	}while($result != 0);
	
	$DB_result = array_splice($DB,0,-2);


	if($_GET['input_id']){
		
		/*멤버 lib에서 검색어 필터링*/
		$DB_filter_result = array_filter($DB_result, searchForId);
		
		$DB_shift_result = array();
		 foreach ($DB_filter_result as $val){
			array_push($DB_shift_result,$val);
		}

		print json_encode($DB_shift_result);
	}else{
		print json_encode($DB_result);
	}
?>