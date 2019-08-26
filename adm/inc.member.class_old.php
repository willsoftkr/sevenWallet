<?php

/*
if(!isset($member['mb_class'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_class` varchar(60) NOT NULL DEFAULT '' AFTER `mb_10` ", false);
}
*/

$max_num = 800;
$rd_num  = 0;
$ru_num  = 0;

/*
get_recommend_up("0010000339");
*/

function get_member_label($i){

	switch($i)
	{
		case 0: $label= "없음"; break;
		case 1: $label= "비회원";  break;
		case 2: $label= "일반";  break;
		case 3:$label= "스타터";  break;
		case 4: $label= "파트너";  break;
		case 5: $label= "팀장";  break;
		case 6:$label= "본부장";  break;
		case 7: $label= "총판";  break;
		case 8: $label= "지사장";  break;
		case 9: $label= "센터장";  break;
		case 10: $label= "최고관리자";  break;
	}

	return $label;
}

function get_org_down($srow){
	global $max_org_num, $org_num, $member, $fr_date, $to_date, $mdepth;
	$org_num++;

	if ($org_num>$max_org_num) {

	}else{
		$clen = strlen($srow[c_class])+2;
		$sql = "select c.c_id,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,c.c_class,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select mb_child from g5_member where mb_id=c.c_id) as c_child,(select count(mb_no) from g5_member where mb_recommend=c.c_id and mb_leave_date = '') as m_child from g5_member m join g5_member_class c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and length(c.c_class)={$clen} and c.c_class like '".$srow[c_class]."%' order by c.c_class limit 50";
		//echo $sql."<br>\n";
		$result = sql_query($sql);
		$count  = sql_num_rows($result);
		if ($count){

			$li_open = 0;
			echo "				<ul>\n";
			for ($i=0; $row=sql_fetch_array($result); $i++) {

				$sql  = "select sum(od_receipt_price+od_receipt_cash) as tprice,sum(pv) as tpv from g5_shop_order where mb_id='".$row[c_id]."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
				$row2 = sql_fetch($sql);

				$sql  = "select sum(od_receipt_price+od_receipt_cash) as tprice,sum(pv) as tpv from g5_shop_order where mb_id in (select c_id from g5_member_class where mb_id='".$member[mb_id]."' and c_id<>'".$row[c_id]."' and c_class like '".$row[c_class]."%') and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
				$row3 = sql_fetch($sql);
?>
					<li>[<?=(strlen($row[c_class])/2)-$mdepth?>-<?=($row[m_child])?>-<?=($row[c_child]-1)?>]|<?=get_member_label($row[mb_level])?>|<?=$row[c_id]?>|<?=$row[c_name]?>|<?=number_format($row2[tpv]/1000)?>|<?=number_format($row3[tpv]/1000)?>
<?
				$li_open = 1;
				$org_num++;
				if ($org_num>$max_org_num)  break;

				$clen = strlen($row[c_class])+2;
				$sql = "select count(*) as cnt from g5_member_class  where mb_id='{$member[mb_id]}' and length(c_class)={$clen} and c_class like '".$row[c_class]."%'";
				//echo $sql."<br>\n";
				$trow = sql_fetch($sql);
				if ($trow[cnt]){
					get_org_down($row);
				}
?>

					</li>
<?
				$li_open = 0;
			}
			if ($li_open){
				echo "			</li>";
			}
			echo "				</ul>\n";
		}
	}

}


function get_recommend_down($mb_id, $m_id, $ca_id) 
{ 
	global $g5,$max_num,$rd_num; 

	if ($mb_id==$m_id){
		$sql = "insert into g5_member_class set mb_id='".$mb_id."',c_id='".$m_id."',c_class='".$ca_id."'";
		sql_query($sql);
	}
	$sql  = " select * from {$g5['member_table']} where mb_recommend='{$m_id}' and length(mb_id)>0 and mb_leave_date = '' order by mb_datetime desc";	

	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		$rd_num++;
		$len = strlen($ca_id);
		if ($rd_num>$max_num)  break;
		if ($row[mb_id]=="admin") break;
		if ($len == 30)	break;
		$len2  = $len + 1;
		$subid = base_convert(($i+1), 36, 10);
		$subid += 36;
		if ($subid >= 36 * 36)
		{
			$subid = "  ";
		}
		$subid = base_convert($subid, 10, 36);
		$subid = substr("00" . $subid, -2);
		$subid = $ca_id . $subid;

//		echo $rd_num.".".$subid." = ".$row[mb_id]."<br>\n";
		$sql = "insert into g5_member_class set mb_id='".$mb_id."',c_id='".$row[mb_id]."',c_class='".$subid."'";
		sql_query($sql);
//		echo $sql."<br>\n";

		$sql  = "select count(mb_no) as cnt from {$g5['member_table']} where mb_recommend='".$row[mb_id]."' and length(mb_id)>0 and mb_leave_date = ''";	
		$row2 = sql_fetch($sql); 

		if ($row2[cnt]){
			get_recommend_down($mb_id,$row[mb_id],$subid);
		}
	}
	$sql  = " select * from {$g5['member_table']} where mb_recommend='{$m_id}' and length(mb_id)>0 and mb_leave_date = '' order by mb_datetime desc";	
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 

	}

} 


function get_recommend_up($m_id) 
{ 
	global $g5,$max_num,$ru_num; 
	$ru_num++;
	$sql  = " select mb_name,mb_recommend,mb_leave_date from {$g5['member_table']} where mb_id='{$m_id}' and mb_leave_date = ''";
	$row  = sql_fetch($sql);

	if ($row[mb_leave_date]){
		echo $m_id." (".$row[mb_name].",탈퇴) -> ".$row[mb_recommend]."<br>\n";
	}else{
		echo $m_id." (".$row[mb_name].") -> ".$row[mb_recommend]."<br>\n";
	}

	if ($ru_num>$max_num){
		//END
	}else{
		if ($row[mb_recommend]!="admin"){
			get_recommend_up($row[mb_recommend]);
		}
	}
}
?>