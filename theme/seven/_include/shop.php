<?
	$today = date("Y-m-d");

	function shop_item($stx){

		$sql_common ="FROM g5_shop_item";
		$sql_search = " WHERE ca_id = '$stx' ";
		$sql_search .= " AND it_use = 1 ";
		
		$sql = " select *
				{$sql_common}
				{$sql_search}
				order by it_order asc";

		$row = sql_query($sql);
		return $row;
	}

	function get_shop_item($id_num){

		$sql_common ="FROM g5_shop_item";
		$sql_search = " WHERE it_id = '$id_num' ";
		$sql_search .= " AND it_use = 1 ";
		
		$sql = " select *
				{$sql_common}
				{$sql_search}
				order by it_order asc";

		$row = sql_fetch($sql);
		return $row;
	}

	function autoYn($val){
		$result = "X";

		if($val){
			$result = "●";
		}
		return $result;
	}

	function calc_price($val, $rate,$coin){
		
		$shift = "shift_".$coin;

		return $shift( conv_number($val) / conv_number($rate)) ;
	}

	function shop_history($mb_id){
		$receent_sql = "select ct_time as recent from g5_shop_cart where mb_id = '{$mb_id}' ORDER BY ct_time desc limit 1";
		$recent = sql_fetch($receent_sql);
		$recenttime = $recent['recent'];

		$sql = "select * from g5_shop_cart where mb_id = '{$mb_id}' AND ct_time ='{$recenttime}'";
		$result = sql_query($sql);
		return $result;
	}

?>