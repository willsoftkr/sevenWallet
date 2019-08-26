<?php
	include_once('./_common.php');
	include_once('../adm/inc.member.class.php');
	
	//$member_id = $member['mb_id'];
	
	$member_id =$_GET['id'];
	//$_GET['input_id'] =  '';
	$member_keyword = $_GET['input_id'];

	$data1 = array();
	$data2 = array();
	$DB = array();

	
	function searchForId($array) {
	   global $member_keyword;

	   foreach ($array as $key => $val) {
		   if ( strpos($val,$member_keyword) > -1) {
			   return $val;
		   }
	   }
	}

	$sql = "select mb_recommend as id from g5_member where mb_id = '".$member_id."'" ;
	$result = sql_fetch($sql);

	$db_array = array('mb_id' =>$result['id']);
	array_push($DB, $db_array);

//print_r($db_array."<br>");

	$sql  = "select mb_id from g5_member where mb_recommend = '".$result['id']."'";
	$sql_qeury = sql_query($sql);

	while( $row = sql_fetch_array($sql_qeury) ){

		$db_array = array('mb_id' =>$row['mb_id']);

//print_r($row['mb_id']." | ");

		array_push($data1, $db_array);

		/*
			do{ 
				//$sql = "select re_member.mb_brecommend as DB from (select mb_brecommend from g5_member where mb_id = '".$sql_result['id']."') AS re_member " ;

				$sql = "select mb_id as DB from g5_member where mb_brecommend = '".$result_id."'";
				//$result = sql_fetch($sql);

				$db_array = array('mb_id' =>$result['DB']);
				//array_push($data, $result['DB']);
				array_push($DB, $db_array);

				$result_id = $result['DB'];
			}while($result != 0);
			
			$DB_result = array_splice($DB,0,-2);
		*/

		//$DB_result = array_filter($DB);
		//print_r($DB_result);
		//print_r( print json_encode($DB_result));
		//print_r($row['mb_id']);

		
		$sql2  = "select mb_id from g5_member where mb_recommend = '".$row['mb_id']."'";

		$sql_qeury2 = sql_query($sql2);
		
		while( $row = sql_fetch_array($sql_qeury2) ){

			
			$db_array2 = array('mb_id' =>$row['mb_id']);
			array_push($data2, $db_array2);
		}

			
/*
		do{ 
			$sql = "select mb_brecommend as DB from g5_member where mb_id = '".$row['mb_id']."'";
			
			$result = sql_fetch($sql);

			$db_array = array('mb_id' =>$result['DB']);
			//array_push($data, $result['DB']);
			array_push($DB, $db_array);

			$result_id = $result['DB'];
		}while($result != 0);
*/		
	}
	
	
	$DB_result = array_merge($data1,$data2);
	//print_r($DB_result);
	

	if($_GET['input_id']){
		
		$DB_filter_result = array_filter($DB_result, searchForId);
		
		$DB_shift_result = array();
		 foreach ($DB_filter_result as $val){
			array_push($DB_shift_result,$val);
		}

		//print_r($rrr);
		//$sql = "select mb_id from {$g5['member_table']}  where mb_id like '%". $_GET['mb_id']."%' And mb_id !=  '". $member['mb_id']."'" ;
		//$DB_filter_result = array_filter($os, piramid());
		//print_r("<br><br>".$DB_filter_result);

		//테스트 $sql = "select mb_id from g5_member_190619  where mb_id like '%".$_GET['mb_id']."%'";
		/*
		$result = sql_query($sql);

		$rows = array();

		while($r = mysqli_fetch_assoc($result)) {
			$rows[] = $r;
		}
		*/
		//print_R( print json_encode($rows));

		print json_encode($DB_shift_result);
	}else{
		print json_encode($DB_result);
	}
?>
