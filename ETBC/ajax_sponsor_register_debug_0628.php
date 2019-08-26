<?
	include_once('./_common.php');
	include_once('../adm/inc.member.class.php');
	
	//$member_id = $member['mb_id'];
	
	$member_id =$_GET['id'];
	//$_GET['input_id'] =  '';
	$member_keyword = $_GET['input_id'];

	$data1 = array();
	$data2 = array();
	$data3 = array();
	$DB = array();

	
	function searchForId($array) {
	   global $member_keyword;

	   foreach ($array as $key => $val) {
		   if ( strpos($val,$member_keyword) > -1) {
			   return $val;
		   }
	   }
	}

	/*추천인 가져오기*/
	$sql = "select mb_recommend as id from g5_member where mb_id = '".$member_id."'" ;
	$result = sql_fetch($sql);
	$db_array = array('mb_id' =>$result['id']);
	array_push($DB, $db_array);

	//print_r($db_array."<br>");
	

	/*추천인을 추천한 사람 가져오기*/
	$sql  = "select mb_id from g5_member where mb_brecommend = '".$result['id']."'";
	$sql_qeury = sql_query($sql);

	while( $row = sql_fetch_array($sql_qeury) ){

		$db_array = array('mb_id' =>$row['mb_id']);
		array_push($data1, $db_array);
	

		$sql2  = "select mb_id from g5_member where mb_recommend = '".$row['mb_id']."'";
		$sql_qeury2 = sql_query($sql2);
		while( $row = sql_fetch_array($sql_qeury2) ){

			$db_array2 = array('mb_id' =>$row['mb_id']);
			array_push($data2, $db_array2);

			while( $row = sql_fetch_array($sql_qeury3) ){
				$db_array3 = array('mb_id' =>$row['mb_id']);
				array_push($data3, $db_array3);
			}
		}

	}
	
	
	$DB_result = array_merge($data1,$data2,$data3);
	//print_r($DB_result);
	

	if($_GET['input_id']){
		
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
