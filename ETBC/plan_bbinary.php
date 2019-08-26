<?
function realtime_binary($mb_id, $pv, $ord_id){
	$to_date = date("Y-m-d",time() -3600*24);
	$sql_price = "select btc_cost from coin_cost";
	$result = sql_query($sql_price);
	$ret = sql_fetch_array($result);
	$exchange_rate =  $ret['btc_cost'];
	$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));
	$benefitSql = "SELECT * from pinna_soodang_set where immediate=2 order by partner_cnt desc, no";
	$rrr = sql_query($benefitSql);
	for ($i=0; $row=sql_fetch_array($rrr); $i++) {   
		$cond[$i]['mb_level_cond1']=$row['mb_level_cond1'];
		$cond[$i]['mb_level_in1']=$row['mb_level_in1'];
		$cond[$i]['mb_level_in2']=$row['mb_level_in2'];
		$cond[$i]['history_cnt']=$row['history_cnt'];
		$cond[$i]['per']=$row['per'];
		$cond[$i]['allowance_name']=$row['allowance_name'];
		$cond[$i]['sales_reset']=$row['sales_reset'];
		//$cond[$i]['iwolyn']=$row['iwolyn'];
		$cond[$i]['max_reset1']=$row['max_reset1'];
		$cond[$i]['max_reset2']=$row['max_reset2'];
		$cond[$i]['cycle']=$row['cycle'];
		$cond[$i]['recom_kind']=$row['recom_kind'];
		if( ($row['sales_reset']>0) && ($row['cycle']>0)  && ($row['max_reset1']<>'')  && ($row['max_reset2']<>'') ){ 
			$cond[$i]['limit_reset']=1;  
		}  //극점 사용여부
		if(  ($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){ 
			$cond[$i]['level1']=1; 
		} //본인직급 
	}
	$history_cnt=0;
	$no_benefit=0;
	$rec='';
	//구매한 회원의 후원인(brecommend)을 찾아서 
	while($mb_id != '' && $mb_id != 'landlord'){
		//echo "select mb_brecommend from g5_member where mb_id='".$mb_id."'";
		echo "select pv from iwol where mb_id='".$mb_id."' order by no desc limit 1;";
		$get_myinfo = sql_fetch("select mb_id, mb_name, mb_hp, mb_level, mb_recommend, mb_brecommend from g5_member where mb_id='".$mb_id."'");
		$mb_brcom = $get_myinfo['mb_brecommend'];
		$my_point = sql_fetch("select pv, iwolday from iwol where mb_id='".$mb_id."' order by no desc limit 1");
		echo "<br>".$my_point[iwolday]."<br>";
		$binary_trig=0;
		$today_sales=0;
		$today_sales2=0;
		$leg_success=0; // 메트릭스 성공찾기 클리어
		$iwol_pass =0; 
		$benefit_pass =0; 
		if($my_point[iwolday] == date("Y-m-d",time() -3600*24*12)){
			echo "pv를 추가한다";
			echo "<br>";
		}
		else{
			echo "추가한다";
			echo "<br>";
		}
		echo "C : ".$mb_id."<br>";
		echo $my_point[pv]+$pv;
		echo "<br>";
		echo 'A : '.$mb_brcom ;
		$target_mb = get_member($mb_brcom); //수당 받을 회원의 정보를 얻는다.
		$mbid		= $target_mb['mb_id'];			//타겟 회원 아이디
		$mbname	= $target_mb['mb_name'];	//타겟 회원 이름
		$mblevel	= $target_mb['mb_level'];		//타겟 회원 레벨 (-2하면 등급 (스타))
		$get_pair = sql_fetch("select mb_id from g5_member where 1=1 and mb_id <> '".$mb_id."' and mb_brecommend = '".$get_myinfo['mb_brecommend']."'");
		$my_pair = $get_pair['mb_id'];
		$pair_point = sql_fetch("select pv, iwolday from iwol where mb_id='".$my_pair."' order by no desc limit 1");
		echo "<br>";
		echo 'B : '.$my_pair;
		if($my_pair) echo ' pair pv : '.$pair_point[pv];
		echo "<br>";
		if($my_pair=='' || $my_pair==null){
			//echo "empty partner";
		}
		else{
			//echo "point 작업";
		}
		for ($i=0; $i < count($cond); $i++) {//End for (조건 별로 반복)
			if($cond[$i]['limit_reset']==1){
				$limit_reset=1; 
			} else {
				$limit_reset=0;
			} //바이너리보너스조건
			if($cond[$i]['recom_kind']=='mb_recommend'){
				$recom=$recommend['mb_recommend'];
			} else{
				$recom=$recommend['mb_brecommend'];
			}
			$temp_sql1 = '';
			if($cond[$i]['per'] != ''){ // 수당 퍼센트가 비어있으면 패스~
				//******   본인직급 조건이 있다면  성공여부 기록 
				if(($cond[$i]['mb_level_cond1']=='==')   ){
					if($mblevel==$cond[$i]['mb_level_in1']){
						$temp_cond_level1=0;
					}else{
						$temp_cond_level1=1;
					}
				}
				//******   본인직급 조건이 있다면  성공여부 기록 
				//******  바이너리보너스 계산
				if(($limit_reset == 1) && ($temp_cond_level1 == 0) ){
					$count = $conut++;
					echo "point 진입 회수 : ".$count;
					$note='';
					$id1=$mb_id;
					$id2=$my_pair;
					$hap1=$my_point[pv]+$pv;
					$hap2=$pair_point[pv];
					//list($id1,$hap1,$id2,$hap2) = my_bchild($mbid,$to_date,$cond[$i]['cycle']);
					echo $mbid.': '.$id1.'---'.$hap1.'---'.$id2.'---'.$hap2.'<br>';
					if(  ($hap1 > 0 || $hap2 > 0) && $id1!='' && $id2!=''){
						if( $hap1<= $hap2 )
						{ //$hap1이 소실적이라면
							if($hap1>=0 ){ //소실적이 극점?
								$today_sales=$hap1*$cond[$i]['per']/100;
								$firstname=$mbname;
								$firstid=$mbid;
								if($cond[$i]['max_reset1']=='대실적만 이월'){
									$note_adm='소실적 발생 (대실적만 이월) (2) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap2-$hap1);
									$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
									if($today_sales>0) 
										save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $today_sales, $cond[$i]['allowance_name'].' '.$note_adm, $note, $exchange_rate);
									iwol_process($to_date, $mbid, $id2, $mbname, 10, $hap2-$hap1, $note_adm); 
									iwol_process($to_date, $mbid, $id1, $mbname, 11, $hap1-$hap1, $note_adm); 
								} 
							} 
						}  //$hap1이 소실적이라면
						else{ //$hap2가 소실적이라면
							if($hap2 >= 0 ){ //소실적이 극점?
								$today_sales=$hap2*$cond[$i]['per']/100; 
								$binary_trig=($cond[$i]['sales_reset']/$cond[$i]['cycle']);
								$firstname=$mbname;
								$firstid=$mbid;
								if($cond[$i]['max_reset1']=='대실적만 이월'){
									$note_adm='소실적 발생 (대실적만 이월) (9) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.') 이월금:'.($hap1-$hap2);									
									$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
									if($today_sales>0) 
									save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales,  $hap2, $cond[$i]['allowance_name'].' '.$note_adm, $note, $exchange_rate);
									iwol_process($to_date, $mbid, $id1, $mbname, 20, $hap1-$hap2, $note_adm); 
									iwol_process($to_date, $mbid, $id2, $mbname, 21, $hap2-$hap2, $note_adm); //대실적 1/2
								}
							}						
						}  //$hap2가 소실적이라면 
					}// if(($hap1>0) || ($hap2>0)){
					echo $mbname.'('.$mbid.'): '.$note.'직급: '.$mblevel.'= 직급조건: '.$cond[$i]['mb_level_in1'].'<br>';
				}//바이너리 계산 조건 충족
			}//조건에 들어갔고 지급 비율이 충족
		}//End for (조건 별로 반복)
		$rec='';
		$rec='';
		$history_cnt=0;
		$today_sales=0;
		$mb_id = $mb_brcom;
	}
}
//이월관련 Function
/* 이월이 있다면 함께 DB에 저장 한다.*/
function iwol_process($day, $mb_recommend, $mbid, $mb_name, $kind, $pv, $note){
	$iwol= sql_fetch("select count(*) as cnt from iwol where mb_id='".$mbid."' and kind<>1 and date_format(iwolday,'%Y-%m-%d')='$day'");
	echo '### '.$pv.'---'.$iwol['cnt'].'<br>';
	if( ($pv>=0) && ($iwol['cnt']==0) ){   
		$sql_iwol  = "insert iwol set iwolday='".$day."'";
		$sql_iwol .= ", mb_id	= '".$mbid."'";
		$sql_iwol .= ", kind = '".$kind."'";
		$sql_iwol .= ", pv = '".$pv."'";
		$sql_iwol .= ", note = '".$note."'";
		$sql_iwol .= ", mb_brecommend	= '".$mb_recommend."'";
		sql_query($sql_iwol);
		echo $sql_iwol.'******2******<br>';
	}
}
/* function end*/
// benefit_level은 per 금액을 넣어서 바이너리매칭보너스에서 사용한다
function save_benefit($day,$mbid, $mbname, $recom, $allowance_name, $sales_day, $habu_day_sales, $benefit, $benefit_level, $rec_adm, $rec,$exchange_rate){
	$iwol= sql_fetch("select count(*) as cnt from iwol where mb_brecommend='".$mbid."' and date_format(iwolday,'%Y-%m-%d')='$day'");
	if($iwol['cnt']==0){ 
		//수당을 비트로 환산 한다.
		$benefit_bit = round($benefit/$exchange_rate,8);
		//회원 잔고에 더해 준다.
		//$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit_bit.",8) 	where mb_id = '".$mbid."';";
		//sql_query($balance_up);
		$temp_sql1 = " insert soodang_pay set day='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
		$temp_sql1 .= " ,allowance_name		= '".$allowance_name."'";
		//$temp_sql1 .= " ,day_sales		=  '".$sales_day."'";
		//$temp_sql1 .= " ,habu_day_sales 		=  '".$habu_day_sales."'";
		$temp_sql1 .= " ,benefit			=  ".$benefit_bit;
		$temp_sql1 .= " ,benefit_usd		=  '".($benefit)."'";
		$temp_sql1 .= " ,exchange_rate      =  ".$exchange_rate;
		$temp_sql1 .= " ,benefit_level		=  '".($benefit_level)."'";
		$temp_sql1 .= " ,rec		= '".$rec."'";
		$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";
		sql_query($temp_sql1);
		echo $temp_sql1.'********3********<br>';
	}
}
?>