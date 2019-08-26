<?php
include_once('./_common.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" type="text/css" href="./css/style.css?v=20190130">
</head>


<body>

<?
if (!$is_member)
	goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));

if(!$start_id){
	$start_id = $member[mb_id];
}

$left_bottom = get_left_bottom($start_id);

$right_bottom = get_right_bottom($start_id);

$b_recom_arr =  array();
$sql = "select mb_id as b_recomm from g5_member where mb_brecommend='".$start_id."' and mb_brecommend_type='L'";
$sql_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$start_id."' and mb_brecommend_type='R'";

$brst = sql_fetch($sql);
$brst_r = sql_fetch($sql_r);

$get_point_cnt1 = "CREATE TEMPORARY TABLE IF NOT EXISTS tb_real2 select * from iwol where mb_id='".$brst[b_recomm]."' and pv>=0 and kind in (0,2,22,9,99);";
sql_query($get_point_cnt1);

$get_point_cnt2 = "select count(A.iwolday) as total_row from tb_real2 A left join iwol B on A.iwolday=B.iwolday where B.mb_id='".$brst_r[b_recomm2]."' and B.pv>=0 and B.kind in (0,2, 22, 9, 99) ";
$cnt_rst = sql_fetch($get_point_cnt2);
$total_count = $cnt_rst['total_row'];

$rows = 12;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$get_point1 = "CREATE TEMPORARY TABLE IF NOT EXISTS tb_real select * from iwol where mb_id='".$brst[b_recomm]."' and pv>0 and kind in (0,2,22,9,99);";
sql_query($get_point1);

$get_point2 = "(select A.iwolday, A.mb_id as l_mbid, A.pv as l_pv, B.mb_id as r_mbid, B.pv as r_pv from tb_real A left join iwol B on A.iwolday=B.iwolday where B.mb_id='".$brst_r[b_recomm2]."' and B.pv>=0 and B.kind in (0,2,9,22,99) order by A.no desc limit $from_record, $rows)";

array_push($b_recom_arr, $start_id);
array_push($b_recom_arr, $start_id);
array_push($b_recom_arr, $brst[b_recomm]);
array_push($b_recom_arr, $brst_r[b_recomm2]);

$leg_l = "select sum(pv) as pv from iwol where mb_id = '".$brst[b_recomm]."' order by no desc" ;
$today_l = "select habu_day_sales, mb_my_sales from g5_member where mb_id = '".$brst[b_recomm]."'" ;
$iwol_l_value = sql_fetch($leg_l);
$today_l_value = sql_fetch($today_l);
$total_l_value = $iwol_l_value[pv]+$today_l_value[habu_day_sales]+$today_l_value[mb_my_sales];

$leg_r = "select sum(pv) as pv from iwol where mb_id = '".$brst_r[b_recomm2]."' order by no desc";
$today_r = "select habu_day_sales, mb_my_sales from g5_member where mb_id = '".$brst_r[b_recomm2]."'" ;
$iwol_r_value = sql_fetch($leg_r);
$today_r_value = sql_fetch($today_r);
$total_r_value = $iwol_r_value[pv]+$today_r_value[habu_day_sales]+$today_r_value[mb_my_sales];

if($brst[b_recomm])
$sql2 = "select mb_id as b_recomm from g5_member where mb_brecommend='".$brst[b_recomm]."' and mb_brecommend_type='L'";
if($brst[b_recomm])
$sql2_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$brst[b_recomm]."' and mb_brecommend_type='R'";
$brst2 = sql_fetch($sql2);
$brst2_r = sql_fetch($sql2_r);
//echo " 3".$brst2[b_recomm]." 4".$brst2[b_recomm2];
array_push($b_recom_arr,$brst2[b_recomm]);
array_push($b_recom_arr,$brst2_r[b_recomm2]);


if($brst_r[b_recomm2])
$sql3 = "select mb_id as b_recomm from g5_member where mb_brecommend='".$brst_r[b_recomm2]."' and mb_brecommend_type='L'";
if($brst_r[b_recomm2])
$sql3_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$brst_r[b_recomm2]."' and mb_brecommend_type='R'";
$brst3 = sql_fetch($sql3);
$brst3_r = sql_fetch($sql3_r);
//echo " 5".$brst3[b_recomm]." 6".$brst3[b_recomm2];
array_push($b_recom_arr,$brst3[b_recomm]);
array_push($b_recom_arr,$brst3_r[b_recomm2]);

if($brst2[b_recomm])
$sql4 = "select mb_id as b_recomm from g5_member where mb_brecommend='".$brst2[b_recomm]."' and mb_brecommend_type='L'";
if($brst2[b_recomm])
$sql4_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$brst2[b_recomm]."' and mb_brecommend_type='R'";

$brst4 = sql_fetch($sql4);
$brst4_r = sql_fetch($sql4_r);
//echo " 7".$brst4[b_recomm]." 8".$brst4[b_recomm2];
array_push($b_recom_arr,$brst4[b_recomm]);
array_push($b_recom_arr,$brst4_r[b_recomm2]);

if($brst2_r[b_recomm2])
$sql5 = "select mb_id as b_recomm from g5_member where mb_brecommend='".$brst2_r[b_recomm2]."' and mb_brecommend_type='L'";
if($brst2_r[b_recomm2])
$sql5_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$brst2_r[b_recomm2]."' and mb_brecommend_type='R'";
$brst5 = sql_fetch($sql5);
$brst5_r = sql_fetch($sql5_r);
//echo " 9".$brst5[b_recomm]." 10".$brst5[b_recomm2];
array_push($b_recom_arr,$brst5[b_recomm]);
array_push($b_recom_arr,$brst5_r[b_recomm2]);

if($brst3[b_recomm])
$sql6 = "select mb_id as b_recomm from g5_member where mb_brecommend='".$brst3[b_recomm]."' and mb_brecommend_type='L'";
if($brst3[b_recomm])
$sql6_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$brst3[b_recomm]."' and mb_brecommend_type='R'";
$brst6 = sql_fetch($sql6);
$brst6_r = sql_fetch($sql6_r);
//echo " 11".$brst6[b_recomm]." 12".$brst6[b_recomm2];
array_push($b_recom_arr,$brst6[b_recomm]);
array_push($b_recom_arr,$brst6_r[b_recomm2]);

if($brst3_r[b_recomm2])
$sql7 = "select mb_id as b_recomm from g5_member where mb_brecommend='".$brst3_r[b_recomm2]."' and mb_brecommend_type='L'";
if($brst3_r[b_recomm2])
$sql7_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$brst3_r[b_recomm2]."' and mb_brecommend_type='R'";
$brst7 = sql_fetch($sql7);
$brst7_r = sql_fetch($sql7_r);
//echo " 13".$brst7[b_recomm]." 14".$brst7[b_recomm2];
array_push($b_recom_arr,$brst7[b_recomm]);
array_push($b_recom_arr,$brst7_r[b_recomm2]);
$list_info = array();
$list_pinfo = array();
$left_point = array();
$right_point = array();

for($i=1;$i<=15;$i++){
	$sql_left = "select mb_id as b_recom_left from g5_member where mb_brecommend='".$b_recom_arr[$i]."' and mb_brecommend_type='L'";
	$sql_right = "select mb_id as b_recom_right from g5_member where mb_brecommend='".$b_recom_arr[$i]."' and mb_brecommend_type='R'";

	$left = sql_fetch($sql_left);
	$right = sql_fetch($sql_right);

	$sql = "select it_pool1, it_pool2, it_pool3, it_pool4, it_pool5, it_gpu, mb_level, (select sum(pv) from iwol where mb_id ='$left[b_recom_left]' ) as left_p, (select sum(pv) from iwol where mb_id ='$right[b_recom_right]' ) as right_p  from g5_member where  mb_id ='$b_recom_arr[$i]' ";


	$rem_info = sql_fetch($sql);
	$my_rank = $rem_info[mb_level];
	$my_pool_lv="";
	if($rem_info[it_pool1]>0){
		$my_pool_lv = '<img src="./images/package-1.png" width="15" alt="package 1" />';
	}
	if($rem_info[it_pool2]>0){
		$my_pool_lv = $my_pool_lv.'<img src="./images/package-2.png" width="15" alt="package 1"  />';
	}
	if($rem_info[it_pool3]>0){
		$my_pool_lv = $my_pool_lv.'<img src="./images/package-3.png" width="15" alt="package 1"  />';
	}
	if($rem_info[it_pool4]>0){
		$my_pool_lv = $my_pool_lv.'<img src="./images/package-4.png" width="15" alt="package 1"  />';
	}
	if($rem_info[it_pool5]>0){
		$my_pool_lv = $my_pool_lv.'<img src="./images/package-5.png" width="15" alt="package 1"  />';
	}
	if($rem_info[it_gpu]>0){
		$my_pool_lv = $my_pool_lv.'<img src="./images/package-gpu.png" width="15" alt="package 1" />';
	}
	$my_rank_img = '<img src="./images/'.$my_rank.'eos.png" width="20">';
	array_push($list_info, $my_rank_img);
	array_push($list_pinfo, $my_pool_lv);
	array_push($left_point, floor($rem_info[left_p]/1000));
	array_push($right_point, floor ($rem_info[right_p]/1000));
}
?>
<?include_once('mypage_head.php')?>

	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<div class="title-container">
				<h2 class="gray binary-tree-title" data-i18n="tree.title" >Binary Tree</h2> <span class="gray"></span>
				<!-- <?=date('Y-m-d H:i:s')?> -->
				<!-- <?=date("Y-m-d H:i:s",time())?> -->
			</div>

			<form id="sForm" name="sForm" method="post" >
			<div class="binary-tree-header-right">
				<input type="text" placeholder="Search member" name="binary_seach" id="binary_seach" data-i18n="[placeholder]tree.searchMem" />
				<button type="button" data-i18n="tree.search" class="search-button" id="search_btn">Search</button>
			</div>
			</form>

			<div class="search_container">
				<div class="search_result" id="search_result"></div>
				<div class="result_btn">Close</div>
			</div>

<?
$leg_stack = array();
?>
				<div class="leg-view-container">
					<h5 data-i18n="tree.leg" >Leg Stack</h5>
					<p>
					<span class="gray">
					<?
						if($start_id!=$member[mb_id]){
							$get_list_higher  = "select mb_brecommend from g5_member where mb_id='".$start_id."'";
							$higher_id = sql_fetch($get_list_higher);
							 array_push($leg_stack, $higher_id[mb_brecommend]);

					?>

					<?
						while(true){
							if($higher_id[mb_brecommend] != $member[mb_id]){
							$get_list_higher  = "select mb_brecommend from g5_member where mb_id='".$higher_id[mb_brecommend]."'";
							$higher_id = sql_fetch($get_list_higher);
					?>
							<? array_push($leg_stack, $higher_id[mb_brecommend]);?>

					<?
							}
							else{
								break;
							}
						}
						 $reverse_stack  = array_reverse($leg_stack);
						$cnt = count($reverse_stack) ;

						for($i=0;$cnt > $i; $i++){
							if($i == $cnt - 1){ ?>
							<span class="leg-name" name="<?echo $reverse_stack[$i];?>"><?echo $reverse_stack[$i];?> </span>
							<?}else{?>
						<span class="leg-name" name="<?echo $reverse_stack[$i];?>"><?echo $reverse_stack[$i];?><i class="fas fa-arrow-right"></i> </span>
						<?}?>
						<?
						}
					}
					?>
		</span>
			</p>
				</div>

				<div class="tree-container">
					<div class="tree">
						<div class="lvl1"> <!--1단계-->
							<div class="lvl" id="1" align="center">
								<?echo $list_info[0] ?>
								<p class="mem_id"> <?echo $b_recom_arr[1]?></p>
						<!--xx		<?echo $list_pinfo[0]?><br> -->
						<p class="mem_lr">		L : <?echo $left_point[0]?> / R : <?echo $right_point[0]?></p>
							</div> 
						</div>
						<!--line-->
						<!--xx
					<div class="line_1">						
						<div class="line1-1"></div>						
						<div class="line2"></div>
					</div>
				-->
						<div class="lvl2"> <!--2단계-->
						<?for($i=2; $i<4;$i++){
							if($b_recom_arr[$i]){
						?>
							<div class="lvl" id="<?echo $i ;?>" >
							<?echo $list_info[$i-1] ?>
              <p class="mem_id">		<?echo $b_recom_arr[$i]?></p>
						<!--xx	<?echo $list_pinfo[$i-1]?><br> -->
							 L : <?echo $left_point[$i-1]?> / R : <?echo $right_point[$i-1]?>
							</div>
							<?//if end}
							}
							else{?>
							<div class="lvl-open" id="<?echo $i ;?>" >
								<!--xx	
							<select>
									<option selected="" data-i18n="tree.selectMem" value="" >Select Member</option>

								</select>
								<button data-i18n="tree.addMem" class="addMem" >Add Member</button>
							-->
							</div>
							<?}//else end
							}//for end?>
						</div>

						<!--line-->
						<!--xx
						<div class="line_2_con">
							<div class="line_2">			        	
							   <div class="line1-1"></div>			        	
								<div class="line2"></div>
							</div>
							<div class="line_2">
								<div class="line1-1"></div>                                
								<div class="line2"></div>
							</div>
						</div>
						-->
						<div class="lvl3"> <!--3단계-->
						<?for($i=4; $i<8 ;$i++){
							if($b_recom_arr[$i]){
						?>
							<div class="lvl" id="<?echo $i ;?>" >
								<?echo $list_info[$i-1] ?>
                <p class="mem_id">		<?echo $b_recom_arr[$i]?></p>
							<!--xx	<?echo $list_pinfo[$i-1]?><br> -->
								 L : <?echo $left_point[$i-1]?> / R : <?echo $right_point[$i-1]?>
							</div>
							<?//if end}
							}
							else{?>
							<div class="lvl-open" id="<?echo $i ;?>" >
							<!--xx	
							<select>
									<option selected="" data-i18n="tree.selectMem" value="" >Select Member</option>

								</select>
								<button data-i18n="tree.addMem" class="addMem" >Add Member</button>
							-->
							</div>
							<?}//else end
							}//for end?>
						</div>

						<!--line-->
						<!--
						<div class="line_3_con">
							<div class="line_2">			        
								<div class="line1-1"></div>			        
								<div class="line2"></div>
							</div>
							<div class="line_2">					
								<div class="line1-1"></div>					
								<div class="line2"></div>
							</div>
							<div class="line_2">					
								<div class="line1-1"></div>					
								<div class="line2"></div>
							</div>
							<div class="line_2">								     
								<div class="line1-1"></div>								     
								<div class="line2"></div>
							</div>
						</div>
						-->
            <!--xx
						<div class="lvl4"> <!--4단계--><!--
						<?for($i=8; $i<16 ;$i++){
							if($b_recom_arr[$i]){
						?>
							<input type="hidden" class="<?echo $i ;?>"  value="<?echo $b_recom_arr[$i]?>">
							<div class="lvl" id="<?echo $i ;?>"  value="<?echo $b_recom_arr[$i]?>">
								<?echo $list_info[$i-1] ?>
                <p class="mem_id">		<?echo $b_recom_arr[$i]?></p>
								<?echo $list_pinfo[$i-1]?><br>
								 L : <?echo $left_point[$i-1]?> / R : <?echo $right_point[$i-1]?>
							</div>
							<?//if end}
							}
							else{?>
              -->
            <!--xx 멤버등록폼
            <div class="lvl-open" id="<?echo $i ;?>" >
								<select>
									<option selected="" data-i18n="tree.selectMem" value="" >Select Member</option>

								</select>
								<button data-i18n="tree.addMem" class="addMem" >Add Member </button>
							</div>
							<?}//else end
							}//for end?>
            </div>
            -->

					</div>

					<div class="page-scroll">
						<span id="left_top">Left bottom</span>
						<span id="go_top">Back to top</span>
						<span id="go_up_one">One level up</span>
						<span id="right_top">Right bottom</span>
					</div>
				</div>
<?

$mb_id = $start_id;
$distance=0;
while($mb_id!=$start_id){
	$get_recommend  = "select mb_recommend from g5_member where mb_id='".$mb_id."'";
	$rst_recom = sql_fetch($get_recommend);
	$mb_id = $rst_recom[mb_recommend];
	$distance++;
}

$now_member = get_member($start_id); //get member info
$member_nation = "select * from pinna_nation_code where code = ".$now_member[nation_number];
//SELECT * FROM  `pinna_nation_code` 
$nation_rst = sql_fetch($member_nation);

$get_recom = "select count(mb_recommend) as recom_cnt from g5_member where mb_recommend = '".$start_id."'";
$recom_rst = sql_fetch($get_recom);

if($now_member['it_pool1']>0){
	$my_pool_lv = '<img src="./images/package_1.png" width="30" alt="package 1" />';
	$package_cnt += 1;
}
if($now_member['it_pool2']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_2.png" width="30" alt="package 1" />';
		$package_cnt += 1;
}
if($now_member['it_pool3']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_3.png" width="30" alt="package 1" />';
		$package_cnt += 1;
}

if($now_member['it_pool4']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_4.png" width="30" alt="package 1" />';
		$package_cnt += 1;
}
if($now_member['it_GPU']>0){
	$my_pool_lv = $my_pool_lv.'<img src="./images/package_gpu.png" width="30" alt="package 1" />';
		$package_cnt += 1;
}
$my_rank = $now_member[mb_level];
$my_rank_img = '<img src="./images/'.$my_rank.'eos.png" width="30">';
?>

<!--xx member_info
				<div class="member-info">
					<div class="member-details">
						<h5><span data-i18n="tree.info" >Member Information</span> - <?echo $now_member['mb_id'];?> </h5>
						<table class="table table-striped table-bordered">
						  <tbody>
							<tr>
							  <th scope="row" data-i18n="tree.fName" >First Name</th>
							  <td><?echo $now_member['first_name'];?> </td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.lName" >Last Name</th>
							  <td><?echo $now_member['last_name'];?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.country" >Country</th>
							  <td><?echo $nation_rst[nationv_en];?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.spon" >Sponsor</th>
							  <td><?echo $now_member['mb_recommend'];?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.pack" >Packages</th>
							  <td>
									<?echo $my_pool_lv?>
							  </td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.rank">Rank</th>
							  <td><?echo $my_rank_img?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.stat">Status</th>
							  <td>Active</td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.left">Left Volume</th>
							  <td><?echo round($total_l_value/1000)?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.right" >Right Volume</th>
							  <td><?echo round($total_r_value/1000)?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.dist" >Distance From Me</th>
							  <td><?echo $distance?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.sponsored" >Total Sponsored</th>
							  <td><?echo $recom_rst['recom_cnt'];?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.members" >Total Binary Members</th>
							  <td><?echo $now_member['mb_b_child'];?> </td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.enroll" >Enrollment Date</th>
							  <td><?echo $now_member['mb_open_date'];?></td>
							</tr>
							<tr>
							  <th scope="row" data-i18n="tree.place" >Placement Date</th>
							  <td><?echo $now_member['mb_bre_time'];?></td>
							</tr>
						  </tbody>
						</table>
					</div>
					<div class="member-volume">
						<h5 data-i18n="tree.vol" >Binary Volume</h5>
						<form>
	<?
		$res_point = sql_query($get_point2);

	?>
							<table class="table table-hover">
								<thead>
									<tr>
										<th scope="col" >Day</th>
										<th scope="col" >Left Point</th>
										<th scope="col" >Right Point</th>
									</tr>
								</thead>
								<tbody>
								<?while($list=sql_fetch_array($res_point)){?>
								<tr>
								<td><?echo $list[iwolday];?></td>
								<td><?echo round( $list[l_pv]/1000,2);?></td>
								<td><?echo round($list[r_pv]/1000,2);?></td>
								</tr>
								<?}?>
								</tbody>
							</table>
	<?php
		$qstr = 'start_id='.$start_id;
		$pagelist = get_paging_new($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
		if ($pagelist) {
			echo $pagelist;
		}
	?>
							<nav class="pagination-container">
							</nav>
						</form>
				</div>

			</div>
	-->
		</div>
	</div>
	<select style="display:none;" id="dup" >
		<option value=""></option>
	</select>
<script>
	var b_recom_arr = JSON.parse('<? echo json_encode($b_recom_arr);?>');
	var $div = $('<div>');

	var data1 = {};

	$(function() {
		$('.leg-name').click(function(){
			var move_id = $(this).attr("name");
			if(move_id){
				location.replace("./binary_tree.php?start_id="+move_id);
			}
		});

		$( ".lvl-open" ).each(function( index ) {
			var upperId = Math.floor($(this).attr("id")/2);
			var id = $(this).attr("id");
			
			
			if(b_recom_arr[upperId]){ // 상위 회원이 있을때 
				$.ajax({
					url: 'binary_tree.mem.php',
					type: 'GET',
					async: false,
					data: {
						mb_id: b_recom_arr[upperId]
					},
					dataType: 'json',
					success: function(result) {
						// console.log(result);
						$div.empty();
						$.each(result, function( index, obj ) {
							var opt = $('#dup > option').clone();
							opt.attr('value', obj.mb_id);
							opt.html(obj.mb_id + '(' + obj.first_name + ' ' + obj.last_name + ')');
							$div.append(opt);
						});
						$('#'+id+'.lvl-open').find('select').append($div.html());
					}
				});

			}
		});

		// 추가 버튼
		$('.addMem').click(function(){

			var no = $(this).parent().attr('id');
			var upperId = Math.floor(no/2);
			if(!b_recom_arr[upperId]){ // 상위 회원이 없을때
				commonModal('Error',"Can not place this position.",80);
				return;
			}

			if(!$(this).siblings('select').val()){
				commonModal('Error',"Select Member",80);
				return;
			}
			
			var set_type = "";
			if(no%2 == 0){ // 나머지가 0이면 좌측 노드 
				set_type = "L";
			}else{
				set_type = "R";
			}
			// console.log(set_type);
			// console.log($(this).siblings('select').val());
			data1 = {
				"set_id": b_recom_arr[upperId],
				"set_type": set_type,
				"recommend_id": $(this).siblings('select').val()
			};
			$('#confirmModal').modal('show');
		});

		$('#confirmModal .save').on('click',function(e){
			$.ajax({
				url: 'binary_tree.add.php',
				type: 'POST',
				async: false,
				data: data1,
				dataType: 'json',
				success: function(result) {
					//console.log(result);
					location.reload();
				},
				error: function(e){
					console.log(e);
				}
			});
		});

		$('.lvl').click(function(){
			var id_check = $(this).attr("id");
			var add_id = Math.floor(id_check/2);
			var add_id2 = id_check%2;
			//나머지가 0이면 Left //나머지가 1이면 Right
//			alert (b_recom_arr[id_check]);
			if(id_check!=1){
				location.replace("./binary_tree.php?start_id="+b_recom_arr[id_check]);
			}
			//alert (add_id);
		});

	$('button.search-button').click(function(){
		if($("#binary_seach").val() == ""){
			//alert("Please enter a keyword.");
			commonModal('Error','Please enter a keyword.',80);
			$("#binary_seach").focus();
		}else{
			$.post("ajax_get_tree_member.php", $("#sForm").serialize(),function(data){
				$('.search_container').addClass("active");
				$("#search_result").html(data);
				//alert(data); //2018.11.27 임시로 주석처리
			});
		}
	});

	$("#left_top").click(function(){
//		var left_bottom = $('.8').val();
		var left_bottom =  "<?=$left_bottom?>";
		if(left_bottom!=null && left_bottom!=""){
			location.replace("./binary_tree.php?start_id="+left_bottom);
		}
		else
			//alert("Can't move left bottom");
			commonModal('Error',"Can't move left bottom.",80);
	});

	$("#go_top").click(function(){
		location.replace("./binary_tree.php?start_id=<? echo $member[mb_id]?>");
	});

	$("#go_up_one").click(function(){
		var id = "<?echo $start_id?>";
		$.ajax({
			type: "POST",
			url: "ajax_get_org_one_level_up.php",
			cache: false,
			async: false,
			dataType: "json",
			data:  {
				start_id : id
			},
			success: function(data) {
					alert(data.result);
					if(data.result!="")
						location.replace("./binary_tree.php?start_id="+data.result);
					else
						 //alert("Now member is Top");
						 commonModal('Notice',"Now member is Top",80);
			}
		});
	});

	$("#right_top").click(function(){
		var right_bottom = "<?=$right_bottom?>";
		if(right_bottom!=null && right_bottom!=""){
			location.replace("./binary_tree.php?start_id="+right_bottom);
		}
		else
			//alert("Can't move left bottom");
			commonModal('Error',"Can't move left bottom.",80);
	});

	$('.result_btn').click(function(){
		$('.search_container').removeClass('active');
	});

	$('#binary_seach').on('keydown',function(e){
		if(e.which == 13) {
			e.preventDefault();
			$('#search_btn').trigger('click');
		}
	});
});

function go_member(go_id){
	location.replace("./binary_tree.php?start_id="+go_id);
}
</script>

</body>
</html>



<?php
function get_left_bottom($start_id){
	
	$sql = "select mb_id from g5_member where mb_brecommend='".$start_id."' and mb_brecommend_type='L'";
	$rst = sql_fetch($sql);
	$temp = $rst['mb_id'];
	
	if($temp==null || $temp==""){return '';}
	$left_bottom  = $temp;

	while(true){
		$sql2 = "select mb_id from g5_member where mb_brecommend='".$temp."' and mb_brecommend_type='L'";
		$rst2 = sql_fetch($sql2);
		
		if($rst2['mb_id']!=null &&  $rst2!=""){
			$temp = $rst2['mb_id'];
			$left_bottom  = $temp;
		}
		else 
		{
			break;
		}

	}
	return $left_bottom;
}

function get_right_bottom($start_id){
	
	$sql = "select mb_id from g5_member where mb_brecommend='".$start_id."' and mb_brecommend_type='R' ";
	$rst = sql_fetch($sql);
	$temp = $rst['mb_id'];
	if($temp==null || $temp==""){return '';}
	$right_bottom  = $temp;
	while(true){
		$sql2 = "select mb_id from g5_member where mb_brecommend='".$temp."' and mb_brecommend_type='R' ";
		$rst2 = sql_fetch($sql2);
		
		if($rst2['mb_id']!=null && $rst2!=""){
			$temp = $rst2['mb_id'];
			$right_bottom  = $temp;
		}
		else 
		{
			break;
		}

	}
	return $right_bottom;
}

?>