<?php

/*
if(!isset($member['mb_class'])) {
    sql_query(" ALTER TABLE {$g5['member_table']} ADD `mb_class` varchar(60) NOT NULL DEFAULT '' AFTER `mb_10` ", false);
}
*/



$max_num    = 800;
$max_up_num = 4; //5단계만 보이길 원하실 경우 4로 하시면 됩니다.
$rd_num   = 0;
$ru_num   = 0;
$brd_num  = 0;
//주문금액
$order_field = "sum(pv)";
$order_split = 1;


$order_proc  = 1; //1 NEW

$ngubun = "";
if ($gubun){
	$ngubun = strtolower($gubun);
}

/*
get_recommend_up("0010000339");
*/


//
function make_habu($gubun){
	$noo=0;
	$mon=0;
	$today=0;
	$gubun = strtolower($gubun);

	$sql= " delete from ".$gubun."noo"; // 
	sql_query($sql);

	$sql= " delete from ".$gubun."thirty"; // 
	sql_query($sql);

	$sql= " delete from ".$gubun."today"; //
	sql_query($sql);

global $order_proc;

	if ($order_proc==1){

		habu_sales_calc($gubun,'coolrunning',0); 
	}
}

function habu_sales_calc($gubun, $recom, $deep){

	global $fr_date, $to_date;
	$deep++; // 대수

	if ($fr_date){
		$start_day = $fr_date;
	}else{
		$start_day = '2017-07-01';
	}
	if ($to_date){
		$day       = $to_date;
	}else{
		$day       = date('Y-m-d');
	}
	$yy= strtotime($day);

	$min30=date("Y-m-d", strtotime("-30 day", $yy));
	
    $res= sql_query("select * from g5_member where mb_".$gubun."recommend='".$recom."' ");

	$nooyn=0;
	$monyn=0;
	$todayyn=0;
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 

			$recom=$rrr['mb_id'];  

			$noo_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$start_day' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $noo_search");
			$noo+=$sql['hap'];
			

			$mon_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$min30' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $mon_search");
			$mon+=$sql['hap'];

			/*
			$day_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')='$day'";
			$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search");
			$today+=$sql['hap'];
			*/

			$mysql=sql_fetch("select (pv)as hap from g5_shop_order as o where o.mb_id='".$mbid."'");
			$mysales=$mysql['hap'];


			list($noo_r,$mon_r,$today_r)=habu_sales_calc($gubun, $recom, $deep);	 
				
				$noo_r+=$mysales;
				$mon_r+=$mysales;
				//$today_r+=$mysales;


				$noo+=$noo_r;
				$mon+=$mon_r;  
				//$today+=$today_r; 

			if( ($noo>0) && ($noo_r>0)) {
				if($j==0){
					$rec=$noo;
				}else{
					$rec=$noo_r;	
				}
				sql_query("insert ".$gubun."noo SET noo=".$rec." ,mb_id='".$recom."'");	
				//echo "insert ".$gubun."noo SET noo=".$rec." ,mb_id='".$recom."'";
			}
			
			if(($mon>0) && ($mon_r>0) ) {
				if($j==0){
					$rec=$mon;
				}else{
					$rec=$mon_r;	
				}
				sql_query("insert ".$gubun."thirty SET thirty=".$rec." ,mb_id='".$recom."'");
				//echo "insert ".$gubun."thirty SET thirty=".$rec." ,mb_id='".$recom."'";
			}
			
			/*
			if(($today>0)&& ($today_r>0)) {
				if($j==0){
					$rec=$today;
				}else{
					$rec=$today_r;	
				}
				sql_query("insert ".$gubun."today SET todayy=".$rec." ,mb_id='".$recom."'");
				//echo "insert ".$gubun."today SET todayy=".$rec." ,mb_id='".$recom."'<br>";

			}
			*/



	} // for j	
	 return array($noo,$mon,$today);
}  


function get_org_down($srow){
	global $max_org_num, $org_num, $my_depth, $member, $fr_date, $to_date, $mdepth, $mrow, $gubun, $order_field, $order_split, $ngubun, $order_proc;

	if ($gubun=="B"){
		$class_name     = "g5_member_bclass";
		$recommend_name = "mb_brecommend";
	}else{
		$class_name     = "g5_member_class";
		$recommend_name = "mb_recommend";
	}

	$org_num++;


	//if ($org_num>$max_org_num) {

	//}else{
	$u_id    = $srow['c_id'];
	$u_depth = strlen($srow['c_class']);

	if ($max_org_num>8){
		$max_org_num = 8;
	}

	//echo $u_id." : ".$my_depth." : ".($my_depth+($max_org_num*2)-2)."<".$u_depth;
	if (($my_depth+($max_org_num*2)-2)<=$u_depth)  {
		//넘으면..
		//echo "ERROR";
	}else{


		$clen = strlen($srow[c_class])+2;
		$sql = "select c.c_id,(select mb_lr from g5_member where mb_id=c.c_id limit 1) as mb_lr,(select mb_brecommend_type from g5_member where mb_id=c.c_id limit 1) as mb_brecommend_type,(select mb_level from g5_member where mb_id=c.c_id limit 1) as mb_level,(select pool_level from g5_member where mb_id=c.c_id  limit 1) as pool_level,c.c_class,(select mb_name from g5_member where mb_id=c.c_id  limit 1) as c_name,(select count(*) from g5_member where mb_recommend=c.c_id) as c_child,(select mb_b_child from g5_member where mb_id=c.c_id) as b_child,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='L'  limit 1) as b_recomm,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='R'  limit 1) as b_recomm2,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child, (select it_pool1 from g5_member where mb_id=c.c_id) as it_pool1, (select it_pool2 from g5_member where mb_id=c.c_id) as it_pool2, (select it_pool3 from g5_member where mb_id=c.c_id) as it_pool3, (select it_pool4 from g5_member where mb_id=c.c_id) as it_pool4, (select it_GPU from g5_member where mb_id=c.c_id) as it_GPU from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and length(c.c_class)={$clen} and c.c_class like '".$srow[c_class]."%' order by c.c_class limit 50";

		//echo "<!-- $sql -->";
		$result = sql_query($sql);
		$count  = sql_num_rows($result);
		if ($count){

			$li_open = 0;
			echo "<!-- ".$u_id." --> <ul>\n";
			$rc = 0;
			$bi_open = 0;

			for ($i=0; $row=sql_fetch_array($result); $i++) {
				$rc++;

				if ($gubun=="B"){
					//echo "<!-- $count | ".$row['mb_lr']." -->";
					if ($count==1 && $row['mb_lr']==2){
						$bi_open = 1;

						if ($row['mb_brecommend_type']!="R"){
							$sql = "update g5_member set mb_lr=2,mb_brecommend_type='R' where mb_id='".$row['c_id']."'";
							sql_query($sql);
						}
						?>
							<li>NO|<?=$u_id?>|L</li>
						<?
					}
				}

				$c_id = $row[c_id];

				if ($gubun=="B"){
					$sql  = "select count(*) as cnt from g5_member where mb_brecommend='".$row[c_id]."' and mb_leave_date = ''";
					$row1 = sql_fetch($sql);
					if ($row1['cnt']==2 && ($row['b_recomm']=="" || $row['b_recomm2']=="")){
					
						$sql = "select * from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and length(c.c_class)=".($clen+2)." and c.c_class like '".$row[c_class]."%' ";
						$tres = sql_query($sql);
						for ($j=0; $trow=sql_fetch_array($tres); $j++) {
							if ($j==0){
								$sql = "update g5_member set mb_lr=1,mb_brecommend_type='L' where mb_id='".$trow['c_id']."'";
								$row['b_recomm'] = $trow['c_id'];
							}else{
								$sql = "update g5_member set mb_lr=2,mb_brecommend_type='R' where mb_id='".$trow['c_id']."'";
								$row['b_recomm2'] = $trow['c_id'];
							}
							sql_query($sql);
						}
					} 

				}

				if ($order_proc==1){
					$sql  = "select today as tpv from ".$ngubun."today where mb_id='".$row[c_id]."'";
					$row2 = sql_fetch($sql);

					$sql  = "select noo as tpv from ".$ngubun."noo where mb_id='".$row[c_id]."'";
					$row3 = sql_fetch($sql);

					$sql  = "select thirty as tpv from ".$ngubun."thirty where mb_id='".$row[c_id]."'";
					$row5 = sql_fetch($sql);
				}else{
					$sql  = "select no,today as tpv from ".$ngubun."today where mb_id='".$row[c_id]."'";
					$row2 = sql_fetch($sql);

					if ($row2[no]){

					}else{
						$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id='".$row[c_id]."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
						$row2 = sql_fetch($sql);
						if (!$row2['tpv']) $row2['tpv'] = 0;
						sql_query("insert ".$ngubun."today SET today=".$row2['tpv']." ,mb_id='".$row[c_id]."'");	
					}

					$sql  = "select no,noo as tpv from ".$ngubun."noo where mb_id='".$row[c_id]."'";
					$row3 = sql_fetch($sql);
					if ($row3[no]){

					}else{
						$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member[mb_id]."' and c_class like '".$row[c_class]."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
						$row3 = sql_fetch($sql);
						if (!$row3['tpv']) $row3['tpv'] = 0;
						$sql  = "insert ".$ngubun."noo SET noo=".$row3['tpv']." ,mb_id='".$row[c_id]."'";
						sql_query($sql);	
					}

					//이전 30일
					$sql  = "select no,thirty as tpv from ".$ngubun."thirty where mb_id='".$row[c_id]."'";
					$row5 = sql_fetch($sql);
					if ($row5[no]){

					}else{
						$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$member[mb_id]."' and c_class like '".$row[c_class]."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
						$row5 = sql_fetch($sql);
						if (!$row5['tpv']) $row5['tpv'] = 0;
						sql_query("insert ".$ngubun."thirty SET thirty=".$row5['tpv']." ,mb_id='".$row[c_id]."'");	
					}
				}

				//바이너리 왼쪽 오늘 매출
				if ($row[b_recomm]){
					//$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row[b_recomm]."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
					$sql  = "select (mb_my_sales+habu_day_sales) as tpv from g5_member where mb_id ='".$row[b_recomm]."' and sales_day='".date("Y-m-d")."'"; // 
					$row6 = sql_fetch($sql);

					$sql  = "select pv as tpv from iwol where mb_id ='".$row['b_recomm']."' order by iwolday desc limit 1";
					
					$row8 = sql_fetch($sql);

					$row6['tpv'] += $row8['tpv'];
				}else{
					$row6['tpv'] = 0;
				}

				//바이너리 오른쪽 오늘 매출
				if ($row[b_recomm2]){
					//$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row[b_recomm2]."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
					$sql  = "select (mb_my_sales+habu_day_sales) as tpv from g5_member where mb_id ='".$row[b_recomm2]."' and sales_day='".date("Y-m-d")."'";
					// and sales_day='".date("Y-m-d")."'
					$row7 = sql_fetch($sql);

					$sql  = "select pv as tpv from iwol where mb_id ='".$row[b_recomm2]."' order by iwolday desc limit 1";
					$row9 = sql_fetch($sql);
					$row7['tpv'] += $row9['tpv'];

				}else{
					$row7['tpv'] = 0;
				}


				$mb_my_sales=$row2['tpv'];
				$mb_habu_sum=$row3['tpv'];

				if($mb_my_sales==''){ $mb_my_sales=0; }
				if($mb_habu_sum==''){$mb_habu_sum=0;}

				if (!$row['b_child']) $row['b_child']=1;
	//			if (!$row['c_child']) $row['c_child']=1;


				if ($mrow[cc_run]==0){  //업데이트가 안되었으면
					$sql  = "update g5_member set mb_my_sales=".$mb_my_sales." , mb_habu_sum=".$mb_habu_sum."   where mb_id='".$row[c_id]."'";
			//		sql_query($sql);
				}

?>
						<li><?=(strlen($row[c_class])/2)-$mdepth?>-<?=($row[c_child])?>-<?=($row[b_child]-1)?>|<?=get_member_label($row[mb_level])?>|<?=$row[c_id]?>|<?=$row[c_name]?>|<?=number_format($row3[tpv]/1000)?>|<?=number_format($row5[tpv]/1000)?>|<?=$row[mb_level]?>|<?=number_format($row6[tpv]/$order_split)?>|<?=number_format($row7[tpv]/$order_split)?>|<?=$row[it_pool1]?>|<?=$row[it_pool2]?>|<?=$row[it_pool3]?>|<?=$row[it_pool4]?>|<?=$row[it_GPU]?>|<?=(strlen($row[c_class])/2)-1?>|<?=($row[c_child])?>|<?=($row[b_child]-1)?>|<?=$gubun?>
<?




				$li_open = 1;
				$org_num++;
				//if ($org_num>$max_org_num)  break;

				$clen = strlen($row[c_class])+2;
				$sql = "select count(*) as cnt from ".$class_name."  where mb_id='{$member[mb_id]}' and length(c_class)={$clen} and c_class like '".$row[c_class]."%'";
				//echo $sql."<br>\n";
				$trow = sql_fetch($sql);
				if ($trow[cnt]){
					get_org_down($row);
				}else{
					if ($gubun=="B"){
?>
									<ul>
										<li>NO|<?=$row['c_id']?>|L</li>
										<li>NO|<?=$row['c_id']?>|R</li>
									</ul>
<?
					}
				}
?>

						</li>
<?
				$li_open = 0;
			}

			if ($gubun=="B" && $bi_open == 0){

				$sql  = "select count(*) as cnt from g5_member where mb_brecommend='".$u_id."'";
				$row2 = sql_fetch($sql);

				//echo $rc."|".$sql;
				if ($rc==1 && ceil($row2['cnt'])<2){
?>
					<li>NO|<?=$u_id?>|R</li>
<?
				}
			}

			if ($li_open){
				echo "			</li>";
			}


			echo "				</ul>\n";
		}else{ //카운트가 없으면

			if ($gubun=="B"){
?>
					<ul>
						<li>NO|<?=$srow['c_id']?>|L</li>
						<li>NO|<?=$srow['c_id']?>|R</li>
					</ul>
<?
			}
		}
	}

}

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

		$sql = "delete from g5_member_class where mb_id='".$member[mb_id]."'";
		sql_query($sql);

		get_recommend_down($member[mb_id],$member[mb_id],'11');

		$sql  = " select * from g5_member_class where mb_id='{$member[mb_id]}' order by c_class asc";	
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
			$row2 = sql_fetch("select count(c_class) as cnt from g5_member_class where  mb_id='".$member[mb_id]."' and c_class like '".$row[c_class]."%'");
			$sql = "update g5_member set mb_child='".$row2[cnt]."' where mb_id='".$row[c_id]."'";
			sql_query($sql);
		}

		$sql = "insert into g5_member_class_chk set mb_id='".$member[mb_id]."',cc_date='".date("Y-m-d",time())."',cc_usr='".$mrow[cnt]."'";
		sql_query($sql);


		$org_gubun = $gubun;
		$gubun     = "B";

		$sql = "delete from g5_member_bclass where mb_id='".$member[mb_id]."'";
		sql_query($sql);

		get_brecommend_down($member[mb_id],$member[mb_id],'11');

		$sql  = " select * from g5_member_bclass where mb_id='{$member[mb_id]}' order by c_class asc";	
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) { 
			$row2 = sql_fetch("select count(c_class) as cnt from g5_member_bclass where  mb_id='".$member[mb_id]."' and c_class like '".$row[c_class]."%'");
			$sql = "update g5_member set mb_b_child='".$row2[cnt]."' where mb_id='".$row[c_id]."'";
			sql_query($sql);
		}

		$sql = "insert into g5_member_bclass_chk set mb_id='".$member[mb_id]."',cc_date='".date("Y-m-d",time())."',cc_usr='".$mrow[cnt]."'";
		sql_query($sql);

		$gubun = $org_gubun;


	}
}


function make_price(){
	global $fr_date, $member, $to_date, $order_field;

	if (!$fr_date) $fr_date = Date("Y-m-d", time()-60*60*24*365);
	if (!$to_date) $to_date = Date("Y-m-d", time());

	$go_id          = $member['mb_id'];
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";


	$sql = "select c.c_id,c.c_class,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,(select pool_level from g5_member where mb_id=c.c_id) as pool_level,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select count(*) from g5_member where mb_recommend=c.c_id) as c_child,(select mb_b_child from g5_member where mb_id=c.c_id) as b_child,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$go_id}' order by c.c_class";
	//(select mb_child from g5_member where mb_id=c.c_id) as c_child

	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) {

		//내매출
		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id='".$row[c_id]."' and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
		$row2 = sql_fetch($sql);

		//내매출 오늘
		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id='".$row[c_id]."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
		$row4 = sql_fetch($sql);

		//누적하부매출
		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_class like '".$srow[c_class]."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
		$row3 = sql_fetch($sql);
		// and c_id<>'".$row[c_id]."'

		//이전 30일
		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_class like '".$srow[c_class]."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
		$row5 = sql_fetch($sql);
		// and c_id<>'".$row[c_id]."'

		//오늘하부매출
		$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_class like '".$srow[c_class]."%') and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
		$row6 = sql_fetch($sql);
		//  and c_id<>'".$row[c_id]."'

		$mb_my_sales    = $row2['tpv'];
		$noo_habu_sum   = $row3['tpv'];
		$mon_habu_sum   = $row5['tpv'];
		$day_habu_sales = $row6['tpv'];
		$day_my_sales   = $row4['tpv'];

		$sql  = "update g5_member set day_my_sales=".$day_my_sales.",mb_my_sales=".$mb_my_sales.", noo_habu_sum=".$noo_habu_sum.", mon_habu_sum=".$mon_habu_sum.", day_habu_sales=".$day_habu_sales." where mb_id='".$row[c_id]."'";
		sql_query($sql);

	}
	echo $i."건 처리";
 }

function check_level($chk_id){
	global $member;

	$go_id          = $member['mb_id'];

	$sql  = "select mb_level,count(*) as cnt from g5_member where mb_id<>'".$go_id."' and mb_id in (select c_id from g5_member_class where mb_id='".$go_id."' and c_id<>'".$row[c_id]."' and c_class like '".$srow[c_class]."%') group by mb_level";
	$result = sql_query($sql);
	$txt = "";
	for ($i=0; $row=sql_fetch_array($result); $i++) {
		if ($txt) $txt .= "|";
		$txt .= $row['mb_level'].",".$row['cnt'];
	}

	return $txt;

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




function get_recommend_down($mb_id, $m_id, $ca_id) 
{ 
	global $g5,$max_num,$rd_num, $gubun;
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";

	if ($mb_id==$m_id){
		$sql = "insert into ".$class_name." set mb_id='".$mb_id."',c_id='".$m_id."',c_class='".$ca_id."'";
		sql_query($sql);
	}
	$sql  = " select * from {$g5['member_table']} where ".$recommend_name."='{$m_id}' and length(mb_id)>0 and mb_leave_date = '' order by mb_datetime desc";	

	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		$rd_num++;
		$len = strlen($ca_id);
		//if ($rd_num>$max_num)  break;
		if ($row[mb_id]=="admin") break;
		if ($len == 200)	break;
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
		if ($_GET[debug]){
			echo $sql."<br>\n";
		}

		$sql  = "select count(mb_no) as cnt from {$g5['member_table']} where ".$recommend_name."='".$row[mb_id]."' and length(mb_id)>0 and mb_leave_date = ''";	
		if ($_GET[debug]){
		//	echo $sql."<br>\n";
		}
		$row2 = sql_fetch($sql); 

		if ($row2[cnt]){
			get_recommend_down($mb_id,$row[mb_id],$subid);
		}


	}


} 

function get_brecommend_down($mb_id, $m_id, $ca_id) 
{ 
	global $g5,$max_num,$brd_num, $gubun;


	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";

	if ($mb_id==$m_id){
		$sql = "insert into ".$class_name." set mb_id='".$mb_id."',c_id='".$m_id."',c_class='".$ca_id."'";
		sql_query($sql);
	}

	$sql  = " select * from {$g5['member_table']} where ".$recommend_name."='{$m_id}' and length(mb_id)>0 and mb_leave_date = '' order by mb_lr asc,mb_brecommend_type asc,mb_datetime desc";	

	$result = sql_query($sql);
	for ($i=0; $row=sql_fetch_array($result); $i++) { 
		$brd_num++;
		$len = strlen($ca_id);
		//if ($brd_num>$max_num)  break;
		if ($row[mb_id]=="admin") break;
		if ($len == 200)	break;
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

		if ($i==0 && !$row['mb_lr']){
			$sql = "update {$g5['member_table']} set mb_lr=1,mb_brecommend_type='L' where mb_id='".$row['mb_id']."'";
			sql_query($sql);
		}else if ($i==1 && !$row['mb_lr']){
			$sql = "update {$g5['member_table']} set mb_lr=2,mb_brecommend_type='R' where mb_id='".$row['mb_id']."'";
			sql_query($sql);
		}

//		echo $brd_num.".".$subid." = ".$row[mb_id]."<br>\n";
		$sql = "insert into ".$class_name." set mb_id='".$mb_id."',c_id='".$row[mb_id]."',c_class='".$subid."'";
		sql_query($sql);
		//echo $sql."<br>\n";

		$sql  = "select count(mb_no) as cnt from {$g5['member_table']} where ".$recommend_name."='".$row[mb_id]."' and length(mb_id)>0 and mb_leave_date = ''";	
		$row2 = sql_fetch($sql); 

		if ($row2[cnt]){
			get_brecommend_down($mb_id,$row[mb_id],$subid);
		}


	}


} 



function get_recommend_up($m_id) 
{ 
	global $g5,$max_num,$ru_num, $gubun;

	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";

	$ru_num++;
	if ($m_id){
		$sql  = " select ".$recommend_name." from {$g5['member_table']} where mb_id='{$m_id}' and mb_leave_date = ''";
		$row  = sql_fetch($sql);

		if ($row['mb_recommend']){	
			echo $m_id." -> ".$row[mb_recommend]."<br>\n";

			if ($ru_num>$max_num){
				//END
			}else{
				if ($row[mb_recommend]!="admin"){
					get_recommend_up($row[mb_recommend]);
				}
			}
		}
	}
}


function get_brecommend_up($m_id) 
{ 
	global $g5,$max_num,$ru_num, $gubun;

	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";

	if ($m_id){
		$ru_num++;
		$sql  = " select ".$recommend_name." from {$g5['member_table']} where mb_id='{$m_id}' and mb_leave_date = ''";
		$row  = sql_fetch($sql);

		if ($row['mb_brecommend']){
			echo $m_id." -> ".$row[mb_brecommend]."<br>\n";

			if ($ru_num>$max_num){
				//END
			}else{
				if ($row[mb_brecommend]!="admin"){
					get_brecommend_up($row[mb_brecommend]);
				}
			}
		}
	}
}


function get_recommend2_up($m_id,$admin_id) 
{ 
	global $g5,$max_up_num,$ru_num, $gubun;

	if (!$admin_id) $admin_id="admin";

	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";

	if ($m_id==$admin_id){

	}else{
		$ru_num++;
		$sql  = " select ".$recommend_name.",(select mb_name from g5_member where mb_id=m.".$recommend_name.") as recomm_name from {$g5['member_table']} as m where mb_id='{$m_id}' and mb_leave_date = ''";

		//print_r($sql);
		$row  = sql_fetch($sql);

		if ($row[mb_recommend]){
			echo '
					<tr>
						<td bgcolor="#f9f9f9"  style="padding:10px 0px 10px 10px">
						<span style="cursor:pointer" onclick="go_member(\''.$row[mb_recommend].'\')">'.$row[recomm_name].' ('.$row[mb_recommend].')</span>
						</td>
					</tr> |
					';

			if ($ru_num>$max_up_num){
				//END
			}else{
				if ($row[mb_recommend]!=$admin_id){
					get_recommend2_up($row[mb_recommend],$admin_id);
				}
			}
		}
	}
}



function get_brecommend2_up($m_id,$admin_id) 
{ 
	global $g5,$max_up_num,$ru_num, $gubun;

	if (!$admin_id) $admin_id="admin";

	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";

	if ($m_id==$admin_id){

	}else{
		$ru_num++;
		$sql  = " select ".$recommend_name.",(select mb_name from g5_member where mb_id=m.".$recommend_name.") as recomm_name from {$g5['member_table']} as m where mb_id='{$m_id}' and mb_leave_date = ''";

		$row  = sql_fetch($sql);
		if ($row[mb_brecommend]){
			echo '
					<tr>
						<td bgcolor="#f9f9f9"  style="padding:10px 0px 10px 10px">
						<span style="cursor:pointer" onclick="go_member(\''.$row[mb_brecommend].'\')">'.$row[recomm_name].' ('.$row[mb_brecommend].')</span>
						</td>
					</tr> |
					';

			if ($ru_num>$max_up_num){
				//END
			}else{

				if ($row[mb_brecommend]!=$admin_id){
					get_brecommend2_up($row[mb_brecommend],$admin_id);
				}

			}
		}
	}
}


?>