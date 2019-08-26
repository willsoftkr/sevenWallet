<?php
$sub_menu = "600600";
include_once ('../common.php');
$sql_price = "select btc_cost from coin_cost";
$result = sql_query($sql_price);
$ret = sql_fetch_array($result);
$exchange_rate =  $ret['btc_cost'];     
function self_sales($recom){
    $res= sql_fetch("select sum(pv)as hap from g5_shop_order as o where o.mb_id='".$recom."'");
	echo "select sum(pv)as hap from g5_shop_order as o where o.mb_id='".$recom."'". $res['hap'].'<br>';    
	return $res['hap'];    
} 
function my_bchild_sub($mb_id){
	$hap2=0;
	//자기매출제외
	//$hap=self_sales($mb_id); //먼저 자기매출
    $res= sql_query("select mb_id from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		$hap2+=self_sales($rrr['mb_id']); // 하부매출을 구한다
		$hap2 = $hap2+ my_bchild_sub($rrr['mb_id']);		
	} 	
	return $hap2;
} 
function my_bchild_hap($mb_id){
	$cnt = 0;
	$hap = 0;
	$bcnt =0;
    $res= sql_query("select count(mb_id) as cnt from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	$ret = sql_fetch_array($res);
	$cnt = $ret['cnt']; 

    $res2= sql_query("select count(mb_id) as bcnt from g5_member where mb_brecommend='".$mb_id."' order by mb_no"); 
	$ret2 = sql_fetch_array($res2);
	$cnt2 = $ret2['bcnt']; // 하부매출을 구한다
		
	if($cnt >= 2 and $cnt2=2){
		$hap = my_bchild_sub($mb_id);
	}
	else{
		return 0;
	}
	return $hap;
} 
function paid_direct_bonus($mb_id, $pv, $ord_id){
	$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));
	$benefit = "SELECT * from pinna_soodang_set where immediate=1 order by partner_cnt desc, no ";
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
		if(  ($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){ 
			$cond[$i]['level1']=1; 
		} //본인직급 
		if(($row['mb_level_cond11']!=0) || ($row['mb_level_cond12']!=0) ){ 
			$cond[$i]['level2']=1; 
		} //하부직급
		if(($row['history_cond1']!='') || ($row['history_cond2']!='') ){ $cond[$i]['history']=1;}  //대수 level
		if($row['benefit_limit1']>0  ){$cond[$i]['bf_limit1']=1;}  //매출자보다 수당받을자의 매출이 같거나 큰가?
	}
	$to_date = date("Y-m-d", time());
	$history_cnt=0;
	$rec='';
	$onestar=3000; // 1스타 승급매출 조건
	$today_sales2=0;
	$today=$to_date;
	$comp=$mb_id;//구매한 회원 아이디
	$first=0; 
	$firstname='';
	$firstid='';
	$today_sales=$pv; //직간접 추천 수당 대상 금액.
	while(($comp!='admin')  || ($comp!='Coolrunning')){   
		$sql = " SELECT mb_id, mb_name, mb_hp, mb_level, pool_level, mb_recommend, mb_brecommend FROM g5_member WHERE mb_id= '".$comp."'";
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
		if($mbid=='') { 
			echo "admin , 본사 혹은 ''을 만나 정지됨"; 
			break;
		}

		$sql_sale = " update g5_member set sales_day='".$today."',";
		if($history_cnt==0)
		{ 
			$sql_sale .= " mb_my_sales = mb_my_sales	+ ".$today_sales;
		}else{
			$sql_sale .= " habu_day_sales = habu_day_sales +".$today_sales;
		}
		$sql_sale .= " where mb_id='".$comp."'";
		sql_query($sql_sale);
		//************************* 1스타 직급 승급 ************************************			
		if($mblevel<3){  //현재 내 직급이 1star 이하이면
			if(my_bchild_hap($comp)>=$onestar){
				$sql3 = " update g5_member set mb_level=3";
				$sql3 .= " , rank_day='".$to_date."'";
				$sql3 .= " where mb_id='".$comp."'";
				sql_query($sql3);
				$sql3 = " insert rank set ";
				$sql3 .= " rank_day='".$to_date."'";
				$sql3 .= " , rank=3";
				$sql3 .= " , old_level='".$mblevel."'";
				$sql3 .= " , rank_note='1스타 승급함, benefit_immediate에서 계산됨'";
				$sql3 .= " where mb_id='".$comp."'";
				//	sql_query($sql3);
				echo $mbid." 승급함<br>";
			}
		}
		for ($i=0; $i<count($cond); $i++) {
			if($cond[$i]['recom_kind']=='mb_recommend'){
				$recom=$recommend['mb_recommend'];
			}else{
				$recom=$recommend['mb_brecommend'];
			}			
			if($cond[$i]['level1']=='1'){
				$temp_cond_level1=1; 
			} else {
				$temp_cond_level1=0;
			} //본인직급
			if($cond[$i]['level2']=='1'){
				$temp_cond_level2=1;
			} else {
				$temp_cond_level2=0;
			} //하부직급
			if($cond[$i]['history']=='1'){ $temp_cond_history=1; } else {$temp_cond_history=0;} //대수조건
			if($cond[$i]['bf_limit1']=='1'){ $bf_limit1=1; } else {$bf_limit1=0;} //대수조건
			//추천수당이라면 최초 매출자의 매출을 기록한다. 왜냐면 최초 매출자의 매출을 가지고 위로 올라가며 수당을 계산하기 때문에... 
			if( ($cond[$i]['base_source']=='추천수당') && ($history_cnt==0) ){
				$firstname=$mbname;
				$firstid=$mbid;
				$first=$pool_level; 
			}
			if($cond[$i]['per']!=''){ // 수당 퍼센트가 비어있으면 패스~
			//******   본인직급 조건이 있다면  성공여부 기록 
				if(($cond[$i]['mb_level_cond1']=='==')){
					if($mblevel==$cond[$i]['mb_level_in1']){
								$temp_cond_level1=0;
							}else{
								$temp_cond_level1=1;
							}
				}else if(($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='')){
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
				if(($cond[$i]['history_cond1']=='==')){
					if($history_cnt==$cond[$i]['history_in1']){
						$temp_cond_history=0;
					}else{
						$temp_cond_history=1;
					}

				}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond1']=='')  ){
					if($history_cnt>=$cond[$i]['history_in1']){
						$temp_cond_history=0;
					}else{
						$temp_cond_history=1;
					}
				}else if(  ($cond[$i]['history_cond1']=='') && ($cond[$i]['history_cond1']=='<=')  ){
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
				 echo $comp.'-대수: '.$temp_cond_history.'-본인직급: '.$temp_cond_level1.' -하부직급: '.$temp_cond_mat.'-매출크기: '.$bf_limit1.'<br>';
				// ***** 걸린 조건을 모두 충족한다면 계산하라 
				if(($temp_cond_history==0)   &&   ($temp_cond_level1==0) ){
					$benefit=($today_sales)*($cond[$i]['per']/100);
					$rec_adm=$cond[$i]['allowance_name'].': 조건 '.$firstid.'('.$first.')<='.$mbid.'('.$pool_level.')   '.$firstname.'('.$firstid.') 으로부터 '.$history_cnt.'대 -'.$cond[$i]['base_source'].':'.$today_sales.'*'.($cond[$i]['per']/100).'='.$benefit.' / <br/>';
					$hist = $history_cnt-1;	
					$rec=$cond[$i]['allowance_name'].' Bonus from member '.$firstid.' ( level '.$hist.')';
					echo $rec;
					//**** 수당이 있다면 함께 DB에 저장 한다.
					$benefit_bit = round($benefit/$exchange_rate,8);
					//$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit_bit.",8)
					//where mb_id = '".$mbid."';";
					//sql_query($balance_up);
					   //**** 수당이 있다면 함께 DB에 저장 한다.
					$temp_sql1 = " insert soodang_pay set day='".$to_date."'";
					$temp_sql1 .= ", mb_id					= '".$mbid."'";
					$temp_sql1 .= ", mb_name			= '".$mbname."'";
					$temp_sql1 .= ", mb_recommend	= '".$recom."'";
					$temp_sql1 .= ", allowance_name	= '".$cond[$i]['allowance_name']."'";
					$temp_sql1 .= ", andor					= '".$cond[$i]['andor']."'";
					$temp_sql1 .= ", accu_my_sales	= '".$recommend['noo_my_sales']."'";
					$temp_sql1 .= ", mon_my_sales		= '".$recommend['mon_my_sales']."'";
					$temp_sql1 .= ", accu_habu_sum	= '".$recommend['noo_habu_sum']."'";
					$temp_sql1 .= ", mon_habu_sum	= '".$recommend['mon_habu_sum']."'";
					$temp_sql1 .= ", benefit					= ".$benefit_bit;
					$temp_sql1 .= ", benefit_usd			= '".($benefit)."'";
					$temp_sql1 .= ", exchange_rate     = ".$exchange_rate;
					if($limit1>0){ 
						$temp_sql1 .= ", benefit_limit1	 = 1";
					}
					$temp_sql1 .= ", rec		= '".$rec."'";
					$temp_sql1 .= ", rec_adm	= '".$rec_adm."'";
					echo $temp_sql1;
					sql_query($temp_sql1);
					$oldcomp=$comp;
				}//if(   ($temp_cond_history==0)  &&  ($temp_cond_bigsmall1==0) 
			}// 수당per 가 있으면 
		} // for
		$rec='';
		$comp=$recom; //상위 추천인을 검색한다.
		$history_cnt++;
	} // while
	$rec='';
	$history_cnt=0;
	$today_sales=0;
} 
?>