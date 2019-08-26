<?php
include_once('./_common.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<?include_once('common_head.php')?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="./css/binary_tree/style.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
	<title>PINNACLE MINING | BINARY TREE</title>
</head>


<body>

<?
if (!$is_member)
	goto_url(G5_BBS_URL."/login.php?url=".urlencode(G5_SHOP_URL."/mypage.php"));

if(!$start_id){
	$start_id = $member[mb_id];
}

$b_recom_arr =  array();
$sql = "select mb_id as b_recomm from g5_member where mb_brecommend='".$start_id."' and mb_brecommend_type='L'";
$sql_r = "select mb_id as b_recomm2 from g5_member where mb_brecommend='".$start_id."' and mb_brecommend_type='R'";

$brst = sql_fetch($sql);
$brst_r = sql_fetch($sql_r);
//echo " 1".$brst[b_recomm]." 2".$brst[b_recomm2];


array_push($b_recom_arr, $start_id);
array_push($b_recom_arr, $start_id);
array_push($b_recom_arr, $brst[b_recomm]);
array_push($b_recom_arr, $brst_r[b_recomm2]);

$leg_l = "select pv from iwol where mb_id = '".$brst[b_recomm]."' order by no desc" ;
$today_l = "select habu_day_sales, mb_my_sales from g5_member where mb_id = '".$brst[b_recomm]."'" ;
$iwol_l_value = sql_fetch($leg_l);
$today_l_value = sql_fetch($today_l);
$total_l_value = $iwol_l_value[pv]+$today_l_value[habu_day_sales]+$today_l_value[mb_my_sales];

$let_r = "select pv from iwol where mb_id = '".$brst_r[b_recomm2]."' order by no desc";
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


?>
<?include_once('mypage_head.php')?>

	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<div class="title-container">
				<h2 class="gray binary-tree-title">Binary Tree</h2> <span class="gray"></span>
			</div>

			<form id="sForm" name="sForm" method="post" >
			<div class="binary-tree-header-right">
				<input type="text" placeholder="Search member" name="binary_seach" id="binary_seach"/>
				<button type="button" class="search-button" id="search_btn">Search</button>
			</div>
			</form>

		<div class="search_container">
			<div class="search_result" id="search_result"></div>
			<div class="result_btn">Close</div>
		</div>

			<!--div class="view-select">
				<form>
					<label class="default-view">
						<input type="radio" name="view" value="sponsor" checked> Sponsor View
					</label>
					<label>
						<input type="radio" name="view" value="binary"> Placement View
					</label>
				</form>
			</div-->

<?
	$leg_stack = array();
?>
				<div class="leg-view-container">
					<h5>Leg Stack</h5>
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
					<?
					$noo_sales = "SELECT * FROM  `noo2` where mb_id ='".$member[mb_id]."' ORDER BY  `noo2`.`day` DESC ";
					$rst_noo = sql_fetch($noo_sales);

					$bonus_btc = "select sum(benefit) as btc_sum from soodang_pay where mb_id='".$member[mb_id]."' and allowance_name <> 'mining payout (eth)'" ;
					$rst_btcB = sql_fetch($bonus_btc);

					$bonus_eth = "select sum(benefit) as eth_sum from soodang_pay where mb_id='".$member[mb_id]."' and  allowance_name = 'mining payout (eth)'" ;
					$rst_ethB = sql_fetch($bonus_eth);

					$currency = "select * from pinna_mining_day4 order by day desc limit 1";
					$rst = sql_fetch($currency);

					$total_bonus = $rst_btcB[btc_sum]*$rst[btcrate];
					$total_bonus2 = $rst_ethB[eth_sum]*$rst[ethrate];
					$mining_pw_sql = "SELECT * FROM pina_mb_hashpower where mb_id='".$member[mb_id]."'";
					$mining_pw = sql_fetch($mining_pw_sql );

					$mbid = $member['mb_id'];
					$sql = "select count(mb_recommend) as enroll from g5_member where mb_recommend='$mbid'";
					//echo $sql;
					$ret = sql_fetch($sql);
					$enroll = sql_fetch_array($ret);
					$cont = $ret['enroll'];

					$placement = $member['mb_brecommend'];
					$package_arry = array(1 => 'One Packages', 'Two Packages', 'Three Packages', 'Four Packages', 'Five Packages');
					$package_cnt =0;
					if($member['it_pool1']>0){
						$my_pool_lv = '<img src="./images/package-1.png" width="50" alt="package 1" />';
						$package_cnt += 1;
					}
					if($member['it_pool2']>0){
						$my_pool_lv = $my_pool_lv.'<img src="./images/package-2.png" width="50" alt="package 1" />';
							$package_cnt += 1;
					}
					if($member['it_pool3']>0){
						$my_pool_lv = $my_pool_lv.'<img src="./images/package-3.png" width="50" alt="package 1" />';
							$package_cnt += 1;
					}

					if($member['it_pool4']>0){
						$my_pool_lv = $my_pool_lv.'<img src="./images/package-4.png" width="50" alt="package 1" />';
							$package_cnt += 1;
					}

					if($member['it_pool5']>0){
						$my_pool_lv = $my_pool_lv.'<img src="./images/package-5.png" width="50" alt="package 1" />';
							$package_cnt += 1;
					}
					if($member['it_GPU']>0){
						$my_pool_lv = $my_pool_lv.'<img src="./images/package-gpu.png" width="50" alt="package 1" />';
							$package_cnt += 1;
					}

					?>
		</span>
			</p>
				</div>

				<div class="tree-container">
					<div class="tree">
						<div class="lvl1"> <!--1단계-->
							<div class="lvl" id="1">
								<div class="lvl_inner">


								<div class="img_area">
									<img src="./images/package_1.png" alt="">
									<img src="./images/package_2.png" alt="">
									<img src="./images/package_3.png" alt="">
									<img src="./images/package_4.png" alt="">
									<img src="./images/package_5.png" alt="">
								</div>
							<div class="user_id">

								<img src="./images/5star.png" alt=""> <?echo $b_recom_arr[1]?>
								</div>
							</div>
							</div>
						</div>
						<!--line-->
						<div class="line_1">
			        <div class="line1">
			            <div class="line1-1"></div>
			        </div>
        			<div class="line2"></div>
    					</div>

						<div class="lvl2"> <!--2단계-->
						<?for($i=2; $i<4;$i++){
							if($b_recom_arr[$i]){
						?>
							<div class="lvl" id="<?echo $i ;?>" >
								<?echo $b_recom_arr[$i]?>
							</div>
							<?//if end}
							}
							else{?>
							<div class="lvl-open" id="<?echo $i ;?>" >
								<select>
									<option selected="">Select Member</option>

								</select>
								<button>Add Meber</button>
							</div>
							<?}//else end
							}//for end?>
						</div>

						<!--line-->
						<div class="line_2_con">

							<div class="line_2">
			        	<div class="line1">
			            <div class="line1-1"></div>
			        	</div>
        			<div class="line2"></div>
								</div>

							<div class="line_2">
						     <div class="line1">
						       <div class="line1-1"></div>
						      </div>
			        	<div class="line2"></div>
			    			</div>

							</div>

						<div class="lvl3"> <!--3단계-->
						<?for($i=4; $i<8 ;$i++){
							if($b_recom_arr[$i]){
						?>
							<div class="lvl" id="<?echo $i ;?>" >
								<?echo $b_recom_arr[$i]?>
							</div>
							<?//if end}
							}
							else{?>
							<div class="lvl-open" id="<?echo $i ;?>" >
								<select>
									<option selected="">Select Member</option>

								</select>
								<button>Add Member</button>
							</div>
							<?}//else end
							}//for end?>
						</div>

						<!--line-->
						<div class="line_3_con">

							<div class="line_2">
			        	<div class="line1">
			            <div class="line1-1"></div>
			        	</div>
        			<div class="line2"></div>
								</div>

							<div class="line_2">
						     <div class="line1">
						       <div class="line1-1"></div>
						      </div>
			        	<div class="line2"></div>
			    			</div>

								<div class="line_2">
							     <div class="line1">
							       <div class="line1-1"></div>
							      </div>
				        	<div class="line2"></div>
				    			</div>

									<div class="line_2">
								     <div class="line1">
								       <div class="line1-1"></div>
								      </div>
					        	<div class="line2"></div>
					    			</div>

							</div>

						<div class="lvl4"> <!--4단계-->
						<?for($i=8; $i<16 ;$i++){
							if($b_recom_arr[$i]){
						?>
							<input type="hidden" class="<?echo $i ;?>"  value="<?echo $b_recom_arr[$i]?>">
							<div class="lvl" id="<?echo $i ;?>"  value="<?echo $b_recom_arr[$i]?>">
								<?echo $b_recom_arr[$i]?>
							</div>
							<?//if end}
							}
							else{?>
							<div class="lvl-open" id="<?echo $i ;?>" >
								<select>
									<option selected="">Select Member</option>

								</select>
								<button>Add Meber </button>
							</div>
							<?}//else end
							}//for end?>
						</div>

					</div>

					<div class="page-scroll">
						<span id="left_top">Left bottom</span>
						<span id="go_top">Back to top</span>
						<span id="go_up_one">One level up</span>
						<span id="right_top">Right bottom</span>
					</div>
				</div>
<?
$now_member = get_member($start_id); //get member info
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
$my_rank = $now_member[mb_level]-2;
$my_rank_img = '<img src="./images/'.$my_rank.'star.png" width="30">';
?>
				<div class="member-info">
					<div class="member-details">
						<h5>Member Information - Username</h5>
						<table class="table table-striped table-bordered">
						  <tbody>
						    <tr>
						      <th scope="row">First Name</th>
						      <td><?echo $now_member['fist_name'];?> </td>
						    </tr>
						    <tr>
						      <th scope="row">Last Name</th>
						      <td><?echo $now_member['last_name'];?></td>
						    </tr>
						    <tr>
						      <th scope="row">Country</th>
						      <td>Preparing...</td>
						    </tr>
						    <tr>
						      <th scope="row">Sponsor</th>
						      <td><?echo $now_member['mb_recommend'];?></td>
						    </tr>
						    <tr>
						      <th scope="row">Packages</th>
						      <td>
									<?echo $my_pool_lv?>
						      </td>
						    </tr>
						    <tr>
						      <th scope="row">Rank</th>
						      <td><?echo $my_rank_img?></td>
						    </tr>
						    <tr>
						      <th scope="row">Status</th>
						      <td>Active</td>
						    </tr>
						    <tr>
						      <th scope="row">Left Leg</th>
						      <td><?echo round($total_l_value/1000)?></td>
						    </tr>
						    <tr>
						      <th scope="row">Right Leg</th>
						      <td><?echo floor($total_r_value/1000)?></td>
						    </tr>
						    <tr>
						      <th scope="row">Distance</th>
						      <td>Preparing...</td>
						    </tr>
						    <tr>
						      <th scope="row">Sponsored</th>
						      <td><?echo $now_member['mb_b_child'];?> </td>
						    </tr>
						    <tr>
						      <th scope="row">Members</th>
						      <td><?echo $now_member['mb_child'];?></td>
						    </tr>
						    <tr>
						      <th scope="row">Enrollment Date</th>
						      <td><?echo $now_member['mb_open_date'];?></td>
						    </tr>
						    <tr>
						      <th scope="row">Placement Date</th>
						      <td><?echo $now_member['mb_bre_time '];?></td>
						    </tr>
						  </tbody>
						</table>
					</div>
					<div class="member-volume">
						<h5>Binary Volume</h5>
						<form>
					<label>
						<input type="radio" name="view" value="daily"> Daily
					</label>&nbsp;
							<label>
						<input type="radio" name="view" value="weekly" checked> Weekly
					</label>&nbsp;
							<label>
						<input type="radio" name="view" value="monthly"> Monthly
					</label>
						<table class="table table-hover">
						  <thead>
						    <tr>
						      <th scope="col">Week</th>
						      <th scope="col">Period</th>
						      <th scope="col">Left Point</th>
						      <th scope="col">Right Point</th>
						    </tr>
						  </thead>
						  <tbody>

						  </tbody>
						</table>
						<nav class="pagination-container">
						  <!--ul class="pagination">
						  	<li class="page-item"><a class="page-link" href="#!">Start</a></li>
						    <li class="page-item">
						      <a class="page-link" href="#" aria-label="Previous">
						        <span aria-hidden="true">&laquo;</span>
						        <span class="sr-only">Previous</span>
						      </a>
						    </li>
						    <li class="page-item"><a class="page-link" href="#">1</a></li>
						    <li class="page-item"><a class="page-link" href="#">2</a></li>
						    <li class="page-item"><a class="page-link" href="#">3</a></li>
						    <li class="page-item"><a class="page-link" href="#">4</a></li>
						    <li class="page-item"><a class="page-link" href="#">5</a></li>
						    <li class="page-item"><a class="page-link" href="#">6</a></li>
						    <li class="page-item"><a class="page-link" href="#">7</a></li>
						    <li class="page-item">
						      <a class="page-link" href="#" aria-label="Next">
						        <span aria-hidden="true">&raquo;</span>
						        <span class="sr-only">Next</span>
						      </a>
						    </li>
						    <li class="page-item"><a class="page-link" href="#!">End</a></li>
						  </ul-->
						</nav>
						  <!--div class="page-search-container">
							  <input class="search-input" type="number" placeholder="Page">
							  <button>Search</button>
						  </div-->
					</div></form>
				</div>

			</div>
		</div>
	</div>


<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

<script>
	$(function() {
		var b_recom_arr = <? echo json_encode($b_recom_arr);?>;

		$('.leg-name').click(function(){
			var move_id = $(this).attr("name");
			if(move_id){
				location.replace("./binary_tree.php?start_id="+move_id);
			}
		});
		$('.lvl-open').click(function(){
			var id_check = $(this).attr("id");
			var add_id = Math.floor(id_check/2);
			var add_id2 = id_check%2;
			//나머지가 0이면 Left //나머지가 1이면 Right
			//alert (b_recom_arr[add_id]);
			if(add_id2){
				set_type = 'R';
			}
			else
			{
				set_type = 'L';
			}
			window.open('recommend_set.php?set_id='+b_recom_arr[add_id]+'&set_type='+set_type+'&now_id='+$("#now_id").val(), 'set_recomm', 'width=520, height=500, resizable=no, scrollbars=yes, left=0, top=0');
		});
		var b_recom_arr = <? echo json_encode($b_recom_arr);?>;
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

	});



</script>
<script>
$(function(){
	$('button.search-button').click(function(){
		if($("#binary_seach").val() == ""){
			alert("Please enter a keyword.");
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
		var left_bottom = $('.8').val();
		if(left_bottom!=null && left_bottom!=""){
			location.replace("./binary_tree.php?start_id="+left_bottom);
		}
		else
			alert("Can't move left bottom");
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
						 alert("Now member is Top");
			}
		});
	});


	$("#right_top").click(function(){
		var right_bottom = $('.15').val();
		if(right_bottom!=null && right_bottom!=""){
			location.replace("./binary_tree.php?start_id="+right_bottom);
		}
		else
			alert("Can't move left bottom");
	});
})

$(function(){
	$('.result_btn').click(function(){
		$('.search_container').removeClass('active');
	})
})

function go_member(go_id){
	location.replace("./binary_tree.php?start_id="+go_id);
}
</script>

<style>
	/*line1*/
		.line_1 .line1{width:50%;height:20px; margin:0 auto;}
	 .line_1 .line1-1{float:left;width:50%;height:20px;margin:0 auto;box-sizing:border-box;border-right:2px solid rgb(0, 121, 211);}
	 	.line_1 .line2{width:50%;height:20px;margin:0 auto;border-left:2px solid rgb(0, 121, 211);border-right:2px solid rgb(0, 121, 211);border-top:2px solid rgb(0, 121, 211);  box-sizing:border-box;clear:both;}

/*line2*/
		.line_2_con{width:100%;height:40px;clear:both;}
		.line_2_con .line_2{float:left;width:50%;}
		.line_2_con .line_2 .line1{width:100%;height:20px; margin:0 auto;}
	 	.line_2_con .line_2 .line1-1{float:left;width:50%;height:20px;margin:0 auto;box-sizing:border-box;border-right:2px solid rgb(0, 121, 211);}
		.line_2_con .line_2 .line2{width:50%;height:20px;margin:0 auto;border-left:2px solid rgb(0, 121, 211);border-right:2px solid rgb(0, 121, 211);border-top:2px solid rgb(0, 121, 211);  box-sizing:border-box;clear:both;}

/*line3*/
.line_3_con{width:100%;height:40px;clear:both;}
.line_3_con .line_2{float:left;width:25%;}
.line_3_con .line_2 .line1{width:100%;height:20px; margin:0 auto;}
.line_3_con .line_2 .line1-1{float:left;width:50%;height:20px;margin:0 auto;box-sizing:border-box;border-right:2px solid rgb(0, 121, 211);}
.line_3_con .line_2 .line2{width:50%;height:20px;margin:0 auto;border-left:2px solid rgb(0, 121, 211);border-right:2px solid rgb(0, 121, 211);border-top:2px solid rgb(0, 121, 211);  box-sizing:border-box;clear:both;}

</style>

</body>
</html>
