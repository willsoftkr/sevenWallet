<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('./inc.member.class.php');

if ($member[mb_org_num]){
	$max_org_num = $member[mb_org_num];
}else{
	$max_org_num = 3;
}

if ($gubun=="B"){
	$class_name     = "g5_member_bclass";
	$recommend_name = "mb_brecommend";
}else{
	$class_name     = "g5_member_class";
	$recommend_name = "mb_recommend";
}

//$mrow = sql_fetch("select * from g5_member where mb_id='{$go_id}'");
$crow = sql_fetch("select c_class from $class_name where mb_id='{$member[mb_id]}' and c_id='{$go_id}'");
$mdepth = (strlen($crow[c_class])/2);


$sql       = "select c.c_id,c.c_class from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.c_id='$go_id'";
$srow      = sql_fetch($sql);
$my_depth  = strlen($srow['c_class']);
//$max_depth = ($my_depth+($max_org_num*2));
$max_depth = ($my_depth + 6);

$sql = "select c.c_id,c.c_class,(select mb_open_date from g5_member where mb_id=c.c_id) as enrolled, (select mb_recommend from g5_member where mb_id=c.c_id) as sponsor, (select mb_brecommend from g5_member where mb_id=c.c_id) as placement, (select substring(mb_bre_time,1,10) from g5_member where mb_id=c.c_id) as placed_binary, (select noo from noo2 where mb_id=c.c_id order by day desc LIMIT 0 , 1) as noo,  (select thirty from thirty2 where mb_id=c.c_id order by day desc LIMIT 0 , 1) as thirty,(select mb_level from g5_member where mb_id=c.c_id) as mb_level,(select pool_level from g5_member where mb_id=c.c_id) as pool_level,(select mb_name from g5_member where mb_id=c.c_id) as c_name,(select count(*) from g5_member where mb_recommend=c.c_id) as c_child,(select mb_b_child from g5_member where mb_id=c.c_id) as b_child,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='L' limit 1) as b_recomm,(select mb_id from g5_member where mb_brecommend=c.c_id and mb_brecommend_type='R' limit 1) as b_recomm2,(select count(mb_no) from g5_member where ".$recommend_name."=c.c_id and mb_leave_date = '') as m_child, (select it_pool1 from g5_member where mb_id=c.c_id) as it_pool1, (select it_pool2 from g5_member where mb_id=c.c_id) as it_pool2, (select it_pool3 from g5_member where mb_id=c.c_id) as it_pool3, (select it_pool4 from g5_member where mb_id=c.c_id) as it_pool4, (select it_GPU from g5_member where mb_id=c.c_id) as it_GPU  from g5_member m join ".$class_name." c on m.mb_id=c.mb_id where c.mb_id='{$member[mb_id]}' and c.c_class like '{$crow['c_class']}%' and length(c.c_class)<".$max_depth." order by c.c_class";


?>
		<div class="zTreeDemoBackground left" style="min-height:573px;margin:0px 10px 0px 10px;">
			<ul id="treeDemo" class="ztree"></ul>
		</div>
		<SCRIPT type="text/javascript">
			//console.log("<?=$sql?>")
			<!--
			var setting = {
				view: {
					nameIsHTML: true,
					showTitle: false
				},
				data: {
					simpleData: {
						enable: true
					}
				},
				callback: {
					onClick: function(event, treeId, treeNode) {

						//getDepth(zNodes,treeNode.mb_id);
					}
				}
			};
			var zNodesSearch =[];
		<?
		$result = sql_query($sql);
		for ($i=0; $row=sql_fetch_array($result); $i++) {
			if (strlen($row[c_class])==2){
				$parent_id = 0;
			}else{
				$parent_id = substr($row[c_class],0,strlen($row[c_class])-2);
			}

			if ($order_proc==1){
				$sql  = "select today as tpv from ".$ngubun."today where mb_id='".$row[c_id]."'";
				$row2 = sql_fetch($sql);

				$sql  = "select noo as tpv from ".$ngubun."noo where mb_id='".$row[c_id]."'";
				$row3 = sql_fetch($sql);

				$sql  = "select thirty as tpv from ".$ngubun."thirty where mb_id='".$row[c_id]."'";
				$row5 = sql_fetch($sql);
			}else{
				$sql  = "select sum(od_receipt_price) as tprice,sum(pv) as tpv from g5_shop_order where mb_id='".$row[c_id]."' and od_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
				$row2 = sql_fetch($sql);

				$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_id<>'".$row[c_id]."' and c_class like '".$row[c_class]."%') and od_receipt_time between '$fr_date 00:00:00' and '$to_date 23:59:59'";
				$row3 = sql_fetch($sql);

				//이전 30일
				$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id in (select c_id from ".$class_name." where mb_id='".$go_id."' and c_id<>'".$row[c_id]."' and c_class like '".$row[c_class]."%') and od_receipt_time between '".Date("Y-m-d",time()-(60*60*24*30))." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
				$row5 = sql_fetch($sql);
			}

			//바이너리 왼쪽 오늘 매출
			if ($row[b_recomm]){
				$sql  = "select (mb_my_sales+habu_day_sales) as tpv from g5_member where mb_id ='".$row[b_recomm]."' and sales_day='".date("Y-m-d")."'";
				//$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row[b_recomm]."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
				$row6 = sql_fetch($sql);

				$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$row[b_recomm]."'";
				$row8 = sql_fetch($sql);

				$row6['tpv'] += $row8['tpv'];
			}else{
				$row6['tpv'] = 0;
			}

			//바이너리 오른쪽 오늘 매출
			if ($row[b_recomm2]){
				//$sql  = "select ".$order_field." as tpv from g5_shop_order where mb_id ='".$row[b_recomm2]."' and od_receipt_time between '".Date("Y-m-d",time())." 00:00:00' and '".Date("Y-m-d",time())." 23:59:59'";
				$sql  = "select (mb_my_sales+habu_day_sales) as tpv from g5_member where mb_id ='".$row[b_recomm2]."' and sales_day='".date("Y-m-d")."'";
				$row7 = sql_fetch($sql);

				$sql  = "select ".$order_field." as tpv from iwol where mb_id ='".$row[b_recomm2]."'";
				$row9 = sql_fetch($sql);
				$row7['tpv'] += $row9['tpv'];
			}else{
				$row7['tpv'] = 0;
			}

			if (!$row['b_child']) $row['b_child']=1;
			$name_line = "<img src='/img/".$row[mb_level]. ".png' class='pool' /> "; //레벨 등급 이미지
			$name_line .= $row[c_name]."[ ".$row[c_id]." ]";
			$enroll = $row[enrolled];
			$sponsor = $row[sponsor];
			//$noo = number_format($row[noo]);
			$thirty = number_format($row[thirty]);
			$placement = $row[placement];
			$placed_binary = $row[placed_binary];
			$noo = number_format($row[noo]);

			$name_line .= "<div class='info'><div><span class='info_tit'>Enrolled : </span><span class='blue info_in'>[$enroll]</span><span class='info_tit'>Sponsor : </span><span class='blue info_in'>[$sponsor]</span><span class='info_tit'>Placement : </span> <span class='blue info_in'>[$placement]</span><span class='info_tit'>Placed in Binary : </span><span class='blue info_in'>[$placed_binary]</span></div><div><span class='info_tit'>Total Sales : </span> <span class='blue info_in'>$[$noo]</span><span class='info_tit'>30-Day Sales : </span><span class='blue info_in'>$[$thirty]</span><span class='info_tit'>Personal Sales : </span> <span class='blue info_in'>[$]</span></div></div> ";
		?>
				zNodesSearch.push({ id:"<?=$row[c_class]?>", pId:"<?=$parent_id?>", name:"<?=$name_line;?>", open:true, click:false, mb_id: "<?=$row[c_id]?>"});
				
		<?
		}
		?>
			$(document).ready(function(){
				<?if ($stx && $sfl){?>
					btn_search();
				<?}?>
				$.fn.zTree.init($("#treeDemo"), setting, zNodesSearch);

				//alert('<?=$max_depth?>')
			});

			//-->
		</SCRIPT>