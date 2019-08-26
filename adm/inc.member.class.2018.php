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

//class 생성 함수
function make_class(){
	global $member, $gubun;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}
	$sql  = "select count(*) as cnt from g5_member";
	$mrow = sql_fetch($sql);

	$sql = "select * from ".$class_name."_chk where cc_date='".date("Y-m-d",time())."' order by cc_no desc";
	$row = sql_fetch($sql);

	if ($mrow[cnt]>$row[cc_usr] || !$row[cc_no]){

		$sql = "delete from ".$class_name." where mb_id='".$member[mb_id]."'";
		sql_query($sql);

		get_recommend_down($member[mb_id],$member[mb_id],'11');

		$sql  = " select * from ".$class_name." where mb_id='{$member[mb_id]}' order by c_class asc";	
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
			$row2 = sql_fetch("select count(c_class) as cnt from ".$class_name." where  mb_id='".$member[mb_id]."' and c_class like '".$row[c_class]."%'");
			$sql = "update g5_member set mb_child='".$row2[cnt]."' where mb_id='".$row[c_id]."'";
			sql_query($sql);
		}

		$sql = "insert into ".$class_name."_chk set mb_id='".$member[mb_id]."',cc_date='".date("Y-m-d",time())."',cc_usr='".$mrow[cnt]."'";
		sql_query($sql);

	}
}



function get_depth($m_id){
	global $member, $start_set, $is_true, $gubun;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}
	$sql = "select c.c_id,c.c_class,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.c_id='".$m_id."'";
	$row = sql_fetch($sql);
	if ($row[m_child]<2){
		$my_depth = 0;
	}else{
		$start_set   = $row[m_child];
		$start_class = $row[c_class];
		$sql = "delete from g5_member_depth where mb_id='{$member[mb_id]}' and m_id='{$m_id}'";
		sql_query($sql);
		for ($chk_set=$start_set;$chk_set>=2;$chk_set--){
			$sql    = "select mb_name,mb_id,(select count(mb_no) from g5_member where ".$recommend_name."=m.mb_id) as m_child,(select c_class from ".$class_name." where mb_id='{$member[mb_id]}' and c_id=m.mb_id) as c_class from g5_member as m where ".$recommend_name."='".$m_id."'";
			$result = sql_query($sql);
			$g_id  = "";
			$c_cnt = 0;
			for ($j=0; $row=sql_fetch_array($result); $j++) {
				if ($row[m_child]>=$chk_set){
					$sql = "insert into g5_member_depth set mb_id='{$member[mb_id]}',m_id='{$m_id}',c_set='{$chk_set}',c_class='{$row[c_class]}',c_depth=1,c_id='{$row[mb_id]}',c_cnt='{$row[m_child]}'";
					sql_query($sql);
					if ($g_id) $g_id .= ",";
					$g_id .= $row[mb_id];
					$c_cnt++;
				}
			}
			$is_true = 0;
			if ($chk_set>$c_cnt){
				// 실패
			}else{
				get_depth2(2,$g_id,$chk_set,$m_id,$start_class);
			}
			$clen = strlen($start_class)+2;
			$sql = "select count(*) as cnt from g5_member m join g5_member_depth c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.m_id='".$m_id."' and length(c.c_class)={$clen} and c.c_class like '".$start_class."%' and c_success=1";
			$row = sql_fetch($sql);
			if ($chk_set>$row[cnt]){
			}else{
				$is_true = 1;
			}
			if ($is_true){
				$my_depth = $chk_set;
				break;
			}
		}
		if (!$is_true) $my_depth = 0;
	}
	return $my_depth;
}


function get_depth2($n_depth,$g_id,$chk_set,$m_id,$start_class){
	global $m_depth, $member,$start_set, $is_true, $gubun;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}
	$temp = explode(",",$g_id);
	$g_cnt = 0;
	for ($i=0;$i<count($temp);$i++){
		$sql = "select c.c_id,c.c_class,(select mb_name from g5_member where mb_id=c.c_id) as c_name from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.c_id='".$temp[$i]."'";
		$row = sql_fetch($sql);
		$second_class = $row[c_class];
		$sql = "select mb_id,mb_name,(select count(mb_no) from g5_member where ".$recommend_name."=m.mb_id) as m_child,(select c_class from ".$class_name." where mb_id='{$member[mb_id]}' and c_id=m.mb_id) as c_class from g5_member as m where ".$recommend_name."='".$temp[$i]."'";
		$result = sql_query($sql);
		$group_id = "";
		$c_cnt    = 0;
		for ($j=0; $row=sql_fetch_array($result); $j++) {
			if ($row[m_child]>=$chk_set){
				$sql = "insert into g5_member_depth set mb_id='{$member[mb_id]}',m_id='{$m_id}',c_set='{$chk_set}',c_class='{$row[c_class]}',c_depth=2,c_id='{$row[mb_id]}',c_cnt='{$row[m_child]}'";
				sql_query($sql);

				if ($group_id) $group_id .= ",";
				$group_id .= $row[mb_id];
				$c_cnt++;
			}
		}
		if ($chk_set>$c_cnt){
			$sql = "delete g5_member_depth where mb_id='{$member[mb_id]}' and m_id='{$m_id}' and c_set='{$chk_set}' and c_id='{$temp[$i]}'";
			sql_query($sql);
		}else{
			$c_cnt = get_depth3(3,$group_id,$chk_set,$m_id,$second_class);
			$g_cnt++;
		}
	}

	$clen = strlen($start_class)+2;
	$sql = "select count(*) as cnt from g5_member m join g5_member_depth c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.m_id='".$m_id."' and length(c.c_class)={$clen} and c.c_class like '".$start_class."%' and c_success=1";
	$row = sql_fetch($sql);

	if ($chk_set>$row[cnt]){
		$sql = "delete g5_member_depth where mb_id='{$member[mb_id]}' and m_id='{$m_id}' and c_set='{$chk_set}' and c_class='{$start_class}'";
		sql_query($sql);
	}else{
		$sql = "update g5_member_depth set c_success=1 where mb_id='{$member[mb_id]}' and m_id='{$m_id}' and c_set='{$chk_set}' and c_class='{$start_class}'";
		sql_query($sql);
	}
}


function get_depth3($n_depth,$g_id,$chk_set,$m_id,$second_class){
	global $m_depth, $member, $start_set, $is_true, $gubun;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}
	$temp = explode(",",$g_id);
	$g_cnt = 0;
	for ($i=0;$i<count($temp);$i++){

		$sql = "select c.c_id,c.c_class,(select mb_name from g5_member where mb_id=c.c_id) as c_name from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.c_id='".$temp[$i]."'";
		$row = sql_fetch($sql);
		$third_class = $row[c_class];

		$sql = "select mb_id,mb_name,(select count(mb_no) from g5_member where ".$recommend_name."=m.mb_id) as m_child,(select c_class from ".$class_name." where mb_id='{$member[mb_id]}' and c_id=m.mb_id) as c_class from g5_member as m where ".$recommend_name."='".$temp[$i]."'";
		$result = sql_query($sql);
		$group_id = "";
		$c_cnt    = 0;
		for ($j=0; $row=sql_fetch_array($result); $j++) {
			if ($row[m_child]>=$chk_set){
				$sql = "insert into g5_member_depth set mb_id='{$member[mb_id]}',m_id='{$m_id}',c_set='{$chk_set}',c_class='{$row[c_class]}',c_depth=3,c_id='{$row[mb_id]}',c_cnt='{$row[m_child]}',c_success=1";
				sql_query($sql);
				if ($group_id) $group_id .= ",";
				$group_id .= $row[mb_id];
				$c_cnt++;
			}
		}
		if ($chk_set>$c_cnt){
			$sql = "delete g5_member_depth where mb_id='{$member[mb_id]}' and m_id='{$m_id}' and c_set='{$chk_set}' and c_id='{$temp[$i]}'";
			sql_query($sql);
		}else{
			$sql = "update g5_member_depth set c_success=1 where mb_id='{$member[mb_id]}' and m_id='{$m_id}' and c_set='{$chk_set}' and c_id='{$temp[$i]}'";
			sql_query($sql);
			$g_cnt++;
		}
	}

	$clen = strlen($second_class)+2;
	$sql = "select count(*) as cnt from g5_member m join g5_member_depth c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.m_id='".$m_id."' and length(c.c_class)={$clen} and c.c_class like '".$second_class."%' and c_success=1";
	$row = sql_fetch($sql);

	if ($chk_set>$row[cnt]){
		$sql = "delete g5_member_depth where mb_id='{$member[mb_id]}' and m_id='{$m_id}' and c_set='{$chk_set}' and c_class='{$second_class}'";
		sql_query($sql);
	}else{
		$sql = "update g5_member_depth set c_success=1 where mb_id='{$member[mb_id]}' and m_id='{$m_id}' and c_set='{$chk_set}' and c_class='{$second_class}'";
		sql_query($sql);
	}

}



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
	global $max_org_num, $org_num, $member, $fr_date, $to_date, $mdepth, $mrow, $gubun;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}

	$org_num++;

	if ($org_num>$max_org_num) {

	}else{


		$clen = strlen($srow[c_class])+2;
		$sql = "select c.c_id,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,c.c_class,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select mb_child from g5_member where mb_id=c.c_id) as c_child,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and length(c.c_class)={$clen} and c.c_class like '".$srow[c_class]."%' order by c.c_class limit 50";
		//echo $sql."<br>\n";
		$result = sql_query($sql);
		$count  = sql_num_rows($result);
		if ($count){

			$li_open = 0;
			echo "				<ul>\n";
			for ($i=0; $row=sql_fetch_array($result); $i++) {

				$sql  = "select sum(od_receipt_price+od_receipt_cash) as tprice,sum(pv) as tpv from g5_shop_order where mb_id='".$row[c_id]."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
				$row2 = sql_fetch($sql);

				$sql  = "select sum(od_receipt_price+od_receipt_cash) as tprice,sum(pv) as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member[mb_id]."' and c_id<>'".$row[c_id]."' and c_class like '".$row[c_class]."%') and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";

				$row3 = sql_fetch($sql);


				$mb_my_sales=$row2['tpv'];
				$mb_habu_sum=$row3['tpv'];

				if($mb_my_sales==''){ $mb_my_sales=0; }
				if($mb_habu_sum==''){$mb_habu_sum=0;}


				if ($mrow[cc_run]==0){  //업데이트가 안되었으면
					$sql  = "update g5_member set mb_my_sales=".$mb_my_sales." , mb_habu_sum=".$mb_habu_sum."   where mb_id='".$row[c_id]."'";
					sql_query($sql);
				}

?>
					<li>[<?=(strlen($row[c_class])/2)-$mdepth?>-<?=($row[m_child])?>-<?=($row[c_child]-1)?>]|<?=get_member_label($row[mb_level])?>|<?=$row[c_id]?>|<?=$row[c_name]?>|<?=number_format($row2[tpv]/1000)?>|<?=number_format($row3[tpv]/1000)?>
<?
				$li_open = 1;
				$org_num++;
				if ($org_num>$max_org_num)  break;

				$clen = strlen($row[c_class])+2;
				$sql = "select count(*) as cnt from ".$class_name."  where mb_id='{$member[mb_id]}' and length(c_class)={$clen} and c_class like '".$row[c_class]."%'";
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
		}else{

			if ($gubun=="B"){
				echo "NONO";
			}
		}
	}

}


function get_recommend_down($mb_id, $m_id, $ca_id) 
{ 
	global $g5,$max_num,$rd_num, $gubun;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}

	if ($mb_id==$m_id){
		$sql = "insert into ".$class_name." set mb_id='".$mb_id."',c_id='".$m_id."',c_class='".$ca_id."'";
		sql_query($sql);
	}
	$sql  = " select * from {$g5['member_table']} where ".$recommend_name."='{$m_id}' and length(mb_id)>0 and mb_leave_date = '' order by mb_datetime desc";	

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
		$sql = "insert into ".$class_name." set mb_id='".$mb_id."',c_id='".$row[mb_id]."',c_class='".$subid."'";
		sql_query($sql);
//		echo $sql."<br>\n";

		$sql  = "select count(mb_no) as cnt from {$g5['member_table']} where ".$recommend_name."='".$row[mb_id]."' and length(mb_id)>0 and mb_leave_date = ''";	
		$row2 = sql_fetch($sql); 

		if ($row2[cnt]){
			get_recommend_down($mb_id,$row[mb_id],$subid);
		}
	}
	$sql  = " select * from {$g5['member_table']} where ".$recommend_name."='{$m_id}' and length(mb_id)>0 and mb_leave_date = '' order by mb_datetime desc";	
	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 

	}

} 


function get_recommend_up($m_id) 
{ 
	global $g5,$max_num,$ru_num, $gubun;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}
	$ru_num++;
	$sql  = " select ".$recommend_name." from {$g5['member_table']} where mb_id='{$m_id}' and mb_leave_date = ''";
	$row  = sql_fetch($sql);
	if ($gubun=="B"){
		echo $m_id." -> ".$row[mb_brecommend]."<br>\n";
	}else{
		echo $m_id." -> ".$row[mb_recommend]."<br>\n";
	}

	if ($ru_num>$max_num){
		//END
	}else{
		if ($gubun=="B"){
			if ($row[mb_brecommend]!="admin"){
				get_recommend_up($row[mb_brecommend]);
			}
		}else{
			if ($row[mb_recommend]!="admin"){
				get_recommend_up($row[mb_recommend]);
			}
		}
	}
}
?>