<?php
$sub_menu = "600600";
include_once ('../common.php');
//auth_check($auth[$sub_menu], 'r');

$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));

$benefit = "SELECT * from eos_soodang_set where immediate=1 order by partner_cnt desc, no ";
$rrr = sql_query($benefit);

for ($i=0; $row=sql_fetch_array($rrr); $i++) {   
	$cond[$i]['price_cond']=$row['price_cond'];
	$cond[$i]['source']=$row['source'];
	$cond[$i]['source_cond1']=$row['source_cond1'];
	$cond[$i]['source_cond2']=$row['source_cond2'];
	$cond[$i]['source_in1']=$row['source_in1'];
	$cond[$i]['source_in2']=$row['source_in2'];
	$cond[$i]['mb_level_cond1']=$row['mb_level_cond1'];
	$cond[$i]['mb_level_cond2']=$row['mb_level_cond2'];
	$cond[$i]['mb_level_in1']=$row['mb_level_in1'];
	$cond[$i]['mb_level_in2']=$row['mb_level_in2'];
	$cond[$i]['partner_cnt']=$row['partner_cnt'];
	$cond[$i]['partner_cont']=$row['partner_cont'];
	$cond[$i]['history_cnt']=$row['history_cnt'];
	$cond[$i]['history_cond1']=$row['history_cond1'];
	$cond[$i]['history_cond2']=$row['history_cond2'];
	$cond[$i]['history_in1']=$row['history_in1'];
	$cond[$i]['history_in2']=$row['history_in2'];
	$cond[$i]['base_source']=$row['base_source'];
	$cond[$i]['immediate']=$row['immediate'];
	$cond[$i]['per']=$row['per'];
	$cond[$i]['andor']=$row['andor'];
	$cond[$i]['allowance_name']=$row['allowance_name'];
	$cond[$i]['benefit_limit1']=$row['benefit_limit1'];
	$cond[$i]['source11']=$row['source11'];
	$cond[$i]['source_cond11']=$row['source_cond11'];
	$cond[$i]['source_cond12']=$row['source_cond12'];
	$cond[$i]['source_in11']=$row['source_in11'];
	$cond[$i]['source_in12']=$row['source_in12'];
	$cond[$i]['sales_reset']=$row['sales_reset'];
	$cond[$i]['iwolyn']=$row['iwolyn'];
	$cond[$i]['max_reset1']=$row['max_reset1'];
	$cond[$i]['max_reset2']=$row['max_reset2'];
	$cond[$i]['cycle']=$row['cycle'];
	$cond[$i]['mb_level_in11']=$row['mb_level_in11'];
	$cond[$i]['mb_level_cond11']=$row['mb_level_cond11'];
	$cond[$i]['mb_level_cond12']=$row['mb_level_cond12'];
	$cond[$i]['recom_kind']=$row['recom_kind'];

	if(  ($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){ $cond[$i]['level1']=1; } //본인직급 
	if(  ($row['mb_level_cond11']!=0) || ($row['mb_level_cond12']!=0) ){ $cond[$i]['level2']=1; } //하부직급
	if(  ($row['history_cond1']!='') || ($row['history_cond2']!='') ){ $cond[$i]['history']=1;}  //대수 level
	if( $row['benefit_limit1']>0  ){$cond[$i]['bf_limit1']=1;}  //매출자보다 수당받을자의 매출이 같거나 큰가?
}

	if ($to_date){
		$day       = $to_date;
	}else{
		$day    = date('Y-m-d');
	}

	$price=pv;
	if(   $price=='pv') {
		// PV가로
		$price_cond=", SUM(pv) AS hap";

	} else if(   $price=='bv') {
		//BV가로 계산
		$price_cond=", SUM(bv) AS hap";

	}else{
		// 판매가로 
		$price_cond=",SUM(od_receipt_price +od_receipt_cash) AS hap";
	}

$sql_common = " FROM g5_shop_order AS o, g5_member AS m ";

$sql_search=" WHERE o.mb_id=m.mb_id AND o.mb_id=m.mb_id";
if($mb_id) {$sql_member=" AND m.mb_id='".$mb_id."'"; }else{ $sql_member=""; }

$searchdate=" AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')>='".$to_date."' AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')<='".$to_date."' GROUP BY mb_id, DATE_FORMAT(o.od_receipt_time,'%Y-%m')";


$search_mon_date=" AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')>='".$min30."' AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')<='".$to_date."' GROUP BY mb_id, DATE_FORMAT(o.od_receipt_time,'%Y-%m')"; //

$sql_mgroup='GROUP BY m.mb_id';


$sql_orderby=' order by od_receipt_time asc';

$sql = "SELECT SUBSTRING(o.od_receipt_time,1,10) AS od_receipt_time, m.mb_id, m.mb_name,m.mb_5, m.mb_recommend, m.mb_hp 
			$price_cond 
            {$sql_common}
            {$sql_search}
			{$sql_member}
			{$searchdate}
";
$result = sql_query($sql);
echo $sql;
$history_cnt=0;
$rec='';

for ($i=0; $row=sql_fetch_array($result); $i++) {   
	$today_sales2=0;
	$today=$row['od_receipt_time'];
	$comp=$row['mb_id'];
	$confirm_exit1=0;
	$first=0; 
	$firstname='';
	$firstid='';
	$today_sales=$row['hap'];
		while(  ($comp!='admin')  || ($comp!='copy5285m')  ){   
			$sql = " SELECT mb_id, mb_name, mb_hp, mb_level, pool_level, mb_recommend, mb_brecommend, mb_deposit_point FROM g5_member WHERE mb_id= '".$comp."'";
			$recommend = sql_fetch($sql);
				$leg_success=0; // 메트릭스 성공찾기 클리어
				$mesales=0; // 최초 내 매출저장
				$limit1=0;
				$mbid=$recommend['mb_id'];
				$mbname=$recommend['mb_name'];
				$mbhp=$recommend['mb_hp'];
				$mblevel=$recommend['mb_level'];
				$pool_level=$recommend['pool_level'];
				//위로 연속 올라가는 부분 추천인으로 올라갈지 바이너리추천인으로 올라갈지 결정
				if(   ($mb_name=='본사')  || ($mbid=='')  ) { echo "admin , 본사 혹은 ''을 만나 정지됨"; break;}
					
					$sql_sale = " update g5_member set sales_day='".$today."',";

					$sql_sale .= " where mb_id='".$comp."'";
					sql_query($sql_sale);
					for ($i=0; $i<count($cond); $i++) {
						if($cond[$i]['recom_kind']=='mb_recommend'){
								$recom=$recommend['mb_recommend'];
						}else{
								$recom=$recommend['mb_brecommend'];
						}
						if($cond[$i]['level1']=='1'){ $temp_cond_level1=1; } else {$temp_cond_level1=0;} //본인직급
						if($cond[$i]['level2']=='1'){ $temp_cond_level2=1; } else {$temp_cond_level2=0;} //하부직급
						if($cond[$i]['history']=='1'){ $temp_cond_history=1; } else {$temp_cond_history=0;} //대수조건
						if($cond[$i]['bf_limit1']=='1'){ $bf_limit1=1; } else {$bf_limit1=0;} //대수조건
						//추천수당이라면 최초 매출자의 매출을 기록한다. 왜냐면 최초 매출자의 매출을 가지고 위로 올라가며 수당을 계산하기 때문에... 
						if( ($cond[$i]['base_source']=='binary Sponsor') && ($history_cnt==0) ){
							$firstname=$mbname;
							$firstid=$mbid;
							$first=$pool_level; 
						}
						//그런데 추천수당중 매출발생자 매출보다 커야만 받을수 있는 조건이 있다면 상위업라인들의 누적매출이 매출발생자 매출보다 같거나 커야만 지급한다. 
						if( $bf_limit1>0 ){
						
							if($first<=$pool_level){
								$limit1=0;
							}else{
								 echo '지급유보발생!! 발생자'.$firstid.'('.$first.')<='.$mbid.'지급유보자('.$pool_level.')';

								$limit1=1;
							}

						}
						$temp_sql1 = '';
						if($cond[$i]['per']!=''){ // 수당 퍼센트가 비어있으면 패스~
					  //******   본인직급 조건이 있다면  성공여부 기록 
								if(   ($cond[$i]['mb_level_cond1']=='==')   ){
									if($mblevel==$cond[$i]['mb_level_in1']){
										$temp_cond_level1=0;
									}else{
										$temp_cond_level1=1;
									}
								}else if(  ($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='')  ){
									if($mblevel>=$cond[$i]['mb_level_in1']){
										$temp_cond_level1=0;
									}else{
										$temp_cond_level1=1;
									}
								}else if(  ($cond[$i]['mb_level_cond1']=='') && ($cond[$i]['mb_level_cond2']=='<=')  ){
									if($mblevel<=$cond[$i]['mb_level_in2']){
										$temp_cond_level1=0;
									}else{
										$temp_cond_level1=1;
									}
								}else if(  ($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='<=')  ){
									if(  ($mblevel>=$cond[$i]['mb_level_in1']) && ($mblevel<=$cond[$i]['mb_level_in2'])  ){
										$temp_cond_level1=0;
									}else{
										$temp_cond_level1=1;
									}
								}
								 //******   하부직급 조건이 있다면  성공여부 기록 
								if(  ($cond[$i]['mb_level_cond11']!='') && ($cond[$i]['mb_level_cond12']>0)   ){
										//하위에 몇명의 원하는 직급이 있는지 파악
										$temp_cond_level2=0;


								}

								
							  
							  //******   대수 조건이 있다면 계산하라 


								if(   ($cond[$i]['history_cond1']=='==')   ){

										if($history_cnt==$cond[$i]['history_in1']){
											
											$temp_cond_history=0;
										}else{
											$temp_cond_history=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond2']=='')  ){
										if($history_cnt>=$cond[$i]['history_in1']){

											$temp_cond_history=0;
										}else{
											$temp_cond_history=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='') && ($cond[$i]['history_cond2']=='<=')  ){

										if($history_cnt<=$cond[$i]['history_in1']){

											$temp_cond_history=0;
										}else{
											$temp_cond_history=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond2']=='<=')  ){
										if( ($history_cnt>=$cond[$i]['history_in1']) && ($history_cnt<=$cond[$i]['history_in2']) ){

											$temp_cond_history=0;
										}else{
											$temp_cond_history=1;
										}
								
								}
								//echo $cond[$i]['base_source'].'=='.$today_sales.'==='.$temp_cond_history.'   '.$temp_cond_bigsmall1.'    '.$temp_cond_level.'   '.$temp_cond_mat.'<br>';;
								 //echo $comp.'-대수: '.$temp_cond_history.'-본인직급: '.$temp_cond_level1.' -하부직급: '.$temp_cond_mat.'-매출크기: '.$bf_limit1.'<br>';
								// ***** 걸린 조건을 모두 충족한다면 계산하라 
								if(   ($temp_cond_history==0)  &&  ($temp_cond_bigsmall1==0) &&  ($temp_cond_bigsmall2==0)  &&  ($temp_cond_level1==0)  && ($temp_cond_leve2==0)  &&  ($temp_cond_mat==0) && ($limit_reset==0)  ){
										//if($save_benefit==1){ // 저장하라면 
								$benefit=($today_sales)*($cond[$i]['per']/100);
								$rec_adm=$cond[$i]['allowance_name'].': 조건 '.$firstid.'('.$first.')<='.$mbid.'('.$pool_level.')   '.$firstname.'('.$firstid.') 으로부터 '.$history_cnt.'대 -'.$cond[$i]['base_source'].':'.$today_sales.'*'.($cond[$i]['per']/100).'='.$benefit.' / <br/>';
								$hist = $history_cnt-1;	
								
								$rec=$cond[$i]['allowance_name'].' Bonus from member '.$firstid.' ( level '.$hist.')';
								//echo $rec;
							   //**** 수당이 있다면 함께 DB에 저장 한다.
										$benefit_bit = round($benefit, 3);
										//$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit_bit.",4) where mb_id = '".$mbid."';";
										//sql_query($balance_up);
										   //**** 수당이 있다면 함께 DB에 저장 한다.
										$temp_sql1 = " insert soodang_pay set day='".$to_date."'";
										$temp_sql1 .= " ,mb_id		= '".$mbid."'";
										$temp_sql1 .= " ,mb_name		= '".$mbname."'";
										$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
										$temp_sql1 .= " ,allowance_name		= '".$cond[$i]['allowance_name']."'";
										$temp_sql1 .= " ,andor		= '".$cond[$i]['andor']."'";												
										$temp_sql1 .= " ,accu_my_sales		=  '".$recommend['noo_my_sales']."'";
										$temp_sql1 .= " ,mon_my_sales		=  '".$recommend['mon_my_sales']."'";
										$temp_sql1 .= " ,accu_habu_sum		=  '".$recommend['noo_habu_sum']."'";
										$temp_sql1 .= " ,mon_habu_sum		=  '".$recommend['mon_habu_sum']."'";

										if($limit1>0){ $temp_sql1 .= " ,benefit_limit1		=  1";}												
										$temp_sql1 .= " ,rec		= '".$rec."'";
										$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";
										$temp_sql1 .= " ,mb_level	= ".$mblevel;
									 if($mblevel>0){		
											$ds_sum  = 0;
											$daily_soo_sum = sql_fetch("select sum(benefit) as ds_sum from soodang_pay where allowance_name='".$cond[$i]['allowance_name']."' and day='".$to_date."' and mb_id='".$mbid."'");
											ec("select sum(benefit) as ds_sum from soodang_pay wehre allowance_name='".$cond[$i]['allowance_name']."' and day='".$to_date."' and mb_id='".$mbid."'");
											if($daily_soo_sum[ds_sum]==null){
												$ds_sum  = 0;
											}
											else{
												$ds_sum = $daily_soo_sum[ds_sum];
											}
							$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from soodang_pay where 1=1 and mb_id='".$mbid."'");
							if($soodang_sum[eb_sum] >= ($recommend[mb_deposit_point]*5)){//보유 EOS의 5배를 수당으로 받았을 시에 이 회원 레벨은 0으로 바뀌고 매출도 
								//매출액에 500%를 넘어서 수당이 발생하지 않는다.
								/*if($soodang_sum[eb_sum]>=		$recommend[mb_deposit_point]*5){				
									$reset_mem = "update g5_member set mb_level=0, mb_deposit_point=0, reset_day='".$to_date."' where mb_id ='".$mbid."'";
									sql_query($reset_mem);
								}
								else {
									$over5p =  ($soodang_sum[eb_sum] + $benefit) - ($recommend[mb_deposit_point]*5) ;
									if($over5p>0){
										$temp_sql1 .= " ,benefit			=  ".$benefit - $	;
										$temp_sql1 .= " ,benefit_usd		=  '".($benefit - $over5p)."'";
										sql_query($temp_sql1);
										$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit - $over5p.",3) 	where mb_id = '".$mbid."';";
										sql_query($balance_up);
										$reset_mem = "update g5_member set mb_level=0, mb_deposit_point=0, reset_day='".$to_date."' where mb_id ='".$mbid."'";
										sql_query($reset_mem);
								}
							}*/
						}else{
			if( $recommend[mb_deposit_point]<=$ds_sum){
					//아무것도 하지 않는다.
			}
			else if($recommend[mb_deposit_point]>=$benefit + $ds_sum){
				$temp_sql1 .= " ,benefit			=  ".$benefit;
				$temp_sql1 .= " ,benefit_usd		=  '".($benefit)."'";
				sql_query($temp_sql1);
				$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit.",3) where mb_id = '".$mbid."';";
				sql_query($balance_up);
			}else {
		
				ec("맥시멈 초과".' '.$benefit.' 수당 섬 : '.$ds_sum);
				$over_sd = $benefit + $ds_sum - $recommend[mb_deposit_point];
				if($benefit - $over_sd>0){
				$temp_sql1 .= " ,benefit			=  ".($benefit - $over_sd);
				$temp_sql1 .= " ,benefit_usd		=  '".($benefit - $over_sd)."'";
				sql_query($temp_sql1);
				$benefit_temp = $benefit - $over_sd;
				$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit_temp.",3) where mb_id = '".$mbid."';";
				sql_query($balance_up);
											ec($temp_sql1);
										echo $balance_up."<br>";
				}
			}
						}
										}

										$oldcomp=$comp;
								}//if(   ($temp_cond_history==0)  &&  ($temp_cond_bigsmall1==0) 
						}// 수당per 가 있으면 
					} // for
					//echo $rec;
					$rec='';
			$comp=$recom;
			$history_cnt++;
		} // while

		$rec='';
		$history_cnt=0;
		$today_sales=0;

} //for
function ec($string){
	echo $string."<br>";
}
//alert('수당계산이 완료되었습니다');



?>

