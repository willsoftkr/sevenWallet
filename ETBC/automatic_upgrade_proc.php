<?php

include_once('../common.php');
		if(trim($_POST['auto_status'])){
			if($_POST['auto_status']=="enable"){
				$sql = "update g5_member set mb_autopack = 'Y' where mb_id='".$member[mb_id]."'";
				sql_query($sql);
			}
			else if ($_POST['auto_status']=="disable"){
				$sql = "update g5_member set mb_autopack = 'N' where mb_id='".$member[mb_id]."'";
				sql_query($sql);

			}
		}	
$myObj = new stdClass();
echo json_encode($myObj);

sql_query($sql);
?>