<?php 

include_once('/home/sdevftv/html/common.php');
$init_item = "update g5_member set it_pool1=0, it_pool2=0, it_pool3=0, it_pool4=0, it_gpu=0";
sql_query($init_item);

$get_od = "select od_id, mb_id from g5_shop_order where od_status in('강제입금', '입금')";
$res_get_od = sql_query($get_od);

while($row = sql_fetch_array($res_get_od)){
	$get_cart_id = "select * from g5_shop_cart where od_id='".$row[od_id]."'";
	$rst = sql_query($get_cart_id);
	for($i=0; $rst_rw=sql_fetch_array($rst); $i++){
		$id = $rst_rw[mb_id];
		if($rst_rw['it_id'] == '1527096053'){//멤버쉽 구매
			$ct_id = $rst_rw['ct_id'];
			$id = $rst_rw['mb_id'];  
			$qty = $rst_rw['ct_qty'];
			$sql_poolist_up = "update g5_member set membership_yn='Y' where mb_id='$id';";
			sql_query($sql_poolist_up);
			$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
			sql_query($sql_chgst);
		}
		else if($rst_rw['it_id'] == '1527096045'){//pool1 구매
			$ct_id = $rst_rw['ct_id'];
			$id = $rst_rw['mb_id'];  
			$qty = $rst_rw['ct_qty'];
			$sql_poolist_up = "update g5_member set it_pool1=it_pool1+$qty where mb_id='$id';";
			sql_query($sql_poolist_up);

			$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
			sql_query($sql_chgst);
		}
		else if($rst_rw['it_id'] == '1527096041'){//pool2구매
			$rst_rw['ct_id'];
			$id = $rst_rw['mb_id'];  
			$qty = $rst_rw['ct_qty'];
			$sql_poolist_up = "update g5_member set it_pool2=it_pool2+$qty where mb_id='$id';";
			sql_query($sql_poolist_up);
			$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
			sql_query($sql_chgst);
		}
		else if($rst_rw['it_id'] == '1527096037'){//pool3 구매 
			$rst_rw['ct_id'];
			$id = $rst_rw['mb_id'];  
			$qty = $rst_rw['ct_qty'];
			$sql_poolist_up = "update g5_member set it_pool3=it_pool3+$qty where mb_id='$id';";
			sql_query($sql_poolist_up);
			$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
			sql_query($sql_chgst);
		}
		else if($rst_rw['it_id'] == '1527096030'){//pool4 구매
			$ct_id = $rst_rw['ct_id'];
			$id = $rst_rw['mb_id'];  
			$qty = $rst_rw['ct_qty'];
			$sql_poolist_up = "update g5_member set it_pool4=it_pool4+$qty where mb_id='$id';";
			sql_query($sql_poolist_up);
			$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
			sql_query($sql_chgst);
		}
		else if($rst_rw['it_id'] == '1515148167'){//GPU  1515148167
			$ct_id = $rst_rw['ct_id'];
			$id = $rst_rw['mb_id'];  
			$qty = $rst_rw['ct_qty'];
			$sql_poolist_up = "update g5_member set it_GPU=it_GPU+$qty where mb_id='$id';";
			sql_query($sql_poolist_up);
			$sql_chgst = "update g5_shop_cart set ct_status ='입금' where ct_id=$ct_id;";
			sql_query($sql_chgst);
		}
		echo $sql_poolist_up;
		echo "<br>";

	}//end for
}//end while

 


?>