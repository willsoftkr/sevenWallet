<?php
$sub_menu = "600600";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'r');




function clear_all_benefit_mem(){
	for ($i=0; $row=sql_fetch_array($rrr); $i++) {   
		$cond[$i]['price_cond']='';
		$cond[$i]['source']='';
		$cond[$i]['source_cond1']='';
		$cond[$i]['source_cond2']='';
		$cond[$i]['source_in1']='';
		$cond[$i]['source_in2']='';


		$cond[$i]['mb_level_cond1']='';
		$cond[$i]['mb_level_cond2']='';
		$cond[$i]['mb_level_in1']='';
		$cond[$i]['mb_level_in2']='';

		$cond[$i]['partner_cnt']='';
		$cond[$i]['partner_cont']='';
		$cond[$i]['history_cnt']='';
		$cond[$i]['history_cond1']='';
		$cond[$i]['history_cond2']='';
		$cond[$i]['history_in1']='';
		$cond[$i]['history_in2']='';
		$cond[$i]['base_source']='';
		$cond[$i]['per']='';
		$cond[$i]['allowance_name']='';
		
		$cond[$i]['benefit_limit1']='';
		
		$cond[$i]['benefit']=0;

		$cond[$i]['source11']='';
		$cond[$i]['source_cond11']='';
		$cond[$i]['source_cond12']='';
		$cond[$i]['source_in11']='';
		$cond[$i]['source_in12']='';
		
		
		$cond[$i]['iwolyn']='';
		
		
		$cond[$i]['mb_level_in11']='';
		$cond[$i]['mb_level_cond11']='';
		$cond[$i]['mb_level_cond12']='';

		$cond[$i]['cycle']='';
		$cond[$i]['sales_reset']='';
		$cond[$i]['max_reset1']='';
		$cond[$i]['max_reset2']='';

		$cond[$i]['recom_kind']='';
		

		$cond[$i]['bigsmall1']='';
		$cond[$i]['bigsmall2']='';
		$cond[$i]['level1']='';
		$cond[$i]['level2']='';
		$cond[$i]['andor']='';
		$cond[$i]['mat']='';
		$cond[$i]['history']='';
		$cond[$i]['bf_limit1']='';



	}
}


function clear_benefit_mem(){
	for ($i=0; $row=sql_fetch_array($rrr); $i++) {   

		$cond[$i]['bigsmall1']='';
		$cond[$i]['bigsmall2']='';
		$cond[$i]['level1']='';
		$cond[$i]['level2']='';
		$cond[$i]['mat']='';
		$cond[$i]['history']='';
	}
}




function habu_iwol($mbid,$day){

    $res1= sql_fetch("select mb_my_sales+habu_day_sales as hap from g5_member where mb_id='".$mbid."' and sales_day='".$day."'");

	
	$res2= sql_fetch("select sum(pv)as hap from iwol where mb_id='".$mbid."'");

	$hap=$res2['hap'];

	if($hap>0){
		$temp_sql1 = " insert iwol set iwolday='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		$temp_sql1 .= " ,pv		= '".-$hap."'";
		$temp_sql1 .= " ,kind		= 1";
		$temp_sql1 .= " ,note		= '이월매출사용'";
		sql_query($temp_sql1);
	}
		
	//echo $temp_sql1.'<br>';

	return $res1['hap']+$hap;    	
} 



function my_bchild($mb_id,$day){
	$id1='';
	$id2='';
    $res= sql_query("select mb_id from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		if($j==0){$id1=$rrr['mb_id']; $hap1=habu_iwol($id1,$day); }
		if($j==1){$id2=$rrr['mb_id']; $hap2=habu_iwol($id2,$day); }
	} 
	return array($id1,$hap1,$id2,$hap2);
} 




//**** 수당이 있다면 함께 DB에 저장 한다.
function iwol_process($day,$mb_recommend, $mbid, $mb_name, $kind, $pv, $note){

	
	$iwol= sql_fetch("select count(*) as cnt from iwol where mb_id='".$mbid."' and kind=0 and date_format(iwolday,'%Y-%m-%d')='$day'");
	
	if( ($pv>0) && ($iwol['cnt']==0) ){   
		
		$temp_sql1 = " insert iwol set iwolday='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		//$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,kind		= '".$kind."'";
		$temp_sql1 .= " ,pv		= '".$pv."'";
		$temp_sql1 .= " ,note		= '".$note."'";
		$temp_sql1 .= " ,mb_recommend		= '".$mb_recommend."'";
		sql_query($temp_sql1);
		//echo $temp_sql1.'<br>';
	}

}

// benefit_level은 per 금액을 넣어서 바이너리매칭보너스에서 사용한다
function save_benefit($day,$mbid, $mbname, $recom, $allowance_name, $sales_day, $habu_day_sales, $benefit, $benefit_level, $rec){

	$iwol= sql_fetch("select count(*) as cnt from iwol where mb_recommend='".$mbid."' and date_format(iwolday,'%Y-%m-%d')='$day'");
	if($iwol['cnt']==0){ 

		$temp_sql1 = " insert soodang_pay set day='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
		$temp_sql1 .= " ,allowance_name		= '".$allowance_name."'";
		
		$temp_sql1 .= " ,day_sales		=  '".$sales_day."'";
		$temp_sql1 .= " ,habu_day_sales 		=  '".$habu_day_sales."'";
		$temp_sql1 .= " ,benefit		=  '".($benefit)."'";
		$temp_sql1 .= " ,benefit_level		=  '".($benefit_level)."'";
		$temp_sql1 .= " ,rec		= '".$rec."'";

		sql_query($temp_sql1);

		

	}
}



$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));

$benefitSql = "SELECT * from soodang_set where immediate=2 order by partner_cnt desc, no";
$rrr = sql_query($benefitSql);

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


	if( ($row['sales_reset']>0) && ($row['cycle']>0)  && ($row['max_reset1']<>'')  && ($row['max_reset2']<>'') ){  $cond[$i]['limit_reset']=1;  }  //극점 사용여부


	if( ($row['partner_cnt']>0) && ($row['partner_cont']>0) ){$cond[$i]['mat']=1;}  // 메트릭스
	
	if(  ($row['source_in1']!=0) || ($row['source_in2']!=0) ){ $cond[$i]['bigsmall1']=1;  }// 대소실적조건1

	if(  ($row['source_in11']!=0) || ($row['source_in12']!=0) ){ $cond[$i]['bigsmall2']=1;  }// 대소실적조건12

	if(  ($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){ $cond[$i]['level1']=1; } //본인직급 

	if(  ($row['mb_level_cond11']!=0) || ($row['mb_level_cond12']!=0) ){ $cond[$i]['level2']=1; } //하부직급

	if(  ($row['history_cond1']!='') || ($row['history_cond2']!='') ){ $cond[$i]['history']=1;}  //대수 level

	if( $row['benefit_limit1']>0  ){$cond[$i]['bf_limit1']=1;}  //매출자보다 수당받을자의 매출이 같거나 큰가?



}





//$fr_date="2015-05-01";
//$to_date="2015-05-31";

$day=date('Y-m-d');

$start_day='2018-01-01'; // 누적 수당계산 시작일

//if($fr_date==''){ 
	$fr_date=$day;
//}
//if($to_date==''){ 
	$to_date=$day;
//}

	//$to_date=date('Y-m-d');

	

	//$ym=substr($fr_date,0,7);




if($iwol_yn_chk==1){
 	$sql= " delete from iwol where date_format(iwolday,'%Y-%m-%d')='$to_date'"; 
	echo "이월DB지우고 다시생성<br><br>";
	sql_query($sql);
	
}




if($noo_start_day==''){
	$noo_start_day=date('Y-01');
}


// 직급이 최소 1스타(3) 이상이고 하부에 오늘 매출이 있는 사람만 
$sql = "SELECT mb_id, mb_name, mb_hp, mb_level, mb_recommend, mb_brecommend, sales_day, habu_day_sales FROM g5_member WHERE habu_day_sales>0 and mb_level>=3 and date_format(sales_day,'%Y-%m-%d')='$fr_date'";
$result = sql_query($sql);

//echo $sql;

$history_cnt=0;
$no_benefit=0;
$rec='';


for ($i=0; $recommend=sql_fetch_array($result); $i++) {   



		$binary_trig=0;

			
			$today_sales=0;
			$today_sales2=0;
			
		

				$leg_success=0; // 메트릭스 성공찾기 클리어

				$my_today_sales=$recommend['mb_my_sales'];
				$habu_today_sales=$recommend['habu_day_sales'];
				
				$mbid=$recommend['mb_id'];
				
				$mbname=$recommend['mb_name'];
				
				$mblevel=$recommend['mb_level'];

				if(   ($mb_name=='본사')  || ($mbid=='')  ) break;


				$iwol_pass =0; 
				$benefit_pass =0; 


					
							
					for ($i=0; $i<count($cond); $i++) {

						
						if($cond[$i]['level1']=='1'){ $temp_cond_level1=1; } else {$temp_cond_level1=0;} //본인직급
						

						if($cond[$i]['limit_reset']==1){ 	$limit_reset=1; } else {$limit_reset=0;} //바이너리보너스조건
					

						
						if($cond[$i]['recom_kind']=='mb_recommend'){
									$recom=$recommend['mb_recommend'];
							}else{
									$recom=$recommend['mb_brecommend'];
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




						  //******  바이너리보너스 계산
						 

						 if(($limit_reset==1) && ($temp_cond_level1==0) ){

							
							$note='';
							$id1='';
							$id2='';
							$big=0;
							$small=0;
							
							$hap1=0;
							$hap2=0;

							list($id1,$hap1,$id2,$hap2)=my_bchild($mbid,$day);
							echo $id1.'   '.$hap1.'    '.$id2.'   '.$hap2.'<br>';
								
							if(($hap1>0) || ($hap2>0)){
								
								if( $hap1<=$hap2 ){ //$hap1이 소실적이라면

									if($hap1>=($cond[$i]['sales_reset']) ){ //소실적이 극점?

										
										$today_sales=($cond[$i]['sales_reset']/$cond[$i]['cycle'])*$cond[$i]['per'];
										$binary_trig=($cond[$i]['sales_reset']/$cond[$i]['cycle']);
										$firstname=$mbname;
										$firstid=$mbid;

				
										


										if( $cond[$i]['max_reset1'] =='대.소실적 모두이월'){

											$note='극점초과(대.소실적 모두이월) (1) 소실적:'.$hap1.'('.$id1.') / 대실적:'.$hap2.'('.$id2.')';

											$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
											if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

											iwol_process($fr_date, $mbid, $id1, $mbname, '극점초과', $hap1-$cond[$i]['sales_reset'] , $note);
											iwol_process($fr_date, $mbid, $id2, $mbname, '극점초과',$hap2-$cond[$i]['sales_reset'] , $note);


										}else if($cond[$i]['max_reset1']=='대실적만 이월'){

											$note='극점초과(대실적만 이월) (2) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap2-$cond[$i]['sales_reset']);
											
											$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
											if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

											iwol_process($fr_date, $mbid, $id2, $mbname, '극점초과',$hap2-$cond[$i]['sales_reset'] , $note); 

										}else if($cond[$i]['max_reset1']=='소실적만 이월'){
											$note='극점초과(소실적만 이월) (3) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap1-$cond[$i]['sales_reset']);

											$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
											if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

											iwol_process($fr_date,$mbid,  $id1, $mbname, '극점초과',$hap1-$cond[$i]['sales_reset'] , $note); 
										}


										
										
									}else{ //극점이 아니고
										if($hap1>=($cond[$i]['cycle'])){ // 1Cycle보다 크다

											$cnt=floor(($hap1)/($cond[$i]['cycle'])); // 몇Cycle인지 알아낸다

											$today_sales=$cnt*$cond[$i]['per'];
											$binary_trig=$cnt;
											$firstname=$mbname;
											$firstid=$mbid;


											if($cond[$i]['max_reset2']=='대.소실적 모두이월'){

												$note=$cnt.'Cycle달성(대.소실적 모두이월) (4) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.')';

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

												iwol_process($fr_date, $mbid, $id1, $mbname, $cnt.'Cycle성공', $hap1-($cnt*($cond[$i]['cycle']) )  , $note); 
												iwol_process($fr_date, $mbid, $id2, $mbname, $cnt.'Cycle성공', $hap2-($cnt*($cond[$i]['cycle']) )  , $note); 


											}else if($cond[$i]['max_reset2']=='대실적만 이월'){

												$note=$cnt.'Cycle달성(대실적만 이월) (5) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap2-$cond[$i]['sales_reset']);

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

												iwol_process($fr_date, $mbid,  $id2, $mbname, $cnt.'Cycle성공', $hap2-$cond[$i]['sales_reset'] , $note); 

											}else if($cond[$i]['max_reset2']=='소실적만 이월'){
												$note=$cnt.'Cycle달성(소실적만 이월)(6) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap1-$cond[$i]['sales_reset']);

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

												iwol_process($fr_date,$mbid,  $id1, $mbname, $cnt.'Cycle성공', $hap1-$cond[$i]['sales_reset'] , $note); 
											}
											

										}else{ // 1Cycle보다 작다
											$note='*Cycle미달 (7) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.')';

												
				
												iwol_process($fr_date, $mbid, $id1, $mbname, 'Cycle미달', $hap1  , $note); 
												iwol_process($fr_date, $mbid, $id2, $mbname, 'Cycle미달', $hap2  , $note); 
										}
									}

								}else{ //$hap2가 소실적이라면

									if($hap2>=($cond[$i]['sales_reset']) ){ //소실적이 극점?



										$today_sales=($cond[$i]['sales_reset']/$cond[$i]['cycle'])*$cond[$i]['per']; 
										$binary_trig=($cond[$i]['sales_reset']/$cond[$i]['cycle']);
										$firstname=$mbname;
										$firstid=$mbid;
									

										if($cond[$i]['max_reset1']=='대.소실적 모두이월'){

											$note='극점초과(대.소실적 모두이월) (8) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.')';

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

											iwol_process($fr_date, $mbid, $id1, $mbname, '극점초과',$hap1-$cond[$i]['sales_reset'] , $note);
											iwol_process($fr_date, $mbid, $id2, $mbname, '극점초과',$hap2-$cond[$i]['sales_reset'] , $note);


										}else if($cond[$i]['max_reset1']=='대실적만 이월'){

											$note='극점초과(대실적만 이월) (9) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.') 이월금:'.($hap1-$cond[$i]['sales_reset']);

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales,  $cond[$i]['per'],$cond[$i]['allowance_name'].' '.$note);

											iwol_process($fr_date, $mbid, $id1, $mbname, '극점초과',$hap1-$cond[$i]['sales_reset'] , $note); 

										}else if($cond[$i]['max_reset1']=='소실적만 이월'){
											$note='극점초과(소실적만 이월) (10) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id2.') 이월금:'.($hap2-$cond[$i]['sales_reset']);
											iwol_process($fr_date, $mbid, $id2, $mbname, '극점초과',$hap2-$cond[$i]['sales_reset'] , $note); 
										}


										
										
									}else{ //극점이 아니고
										if($hap2>=($cond[$i]['cycle'])){ // 1Cycle보다 크다

											$cnt=floor(($hap2)/($cond[$i]['cycle'])); // 몇Cycle인지 알아낸다
											$today_sales=$cnt*$cond[$i]['per'];
											$binary_trig=$cnt;
											$firstname=$mbname;
											$firstid=$mbid;

											if($cond[$i]['max_reset2']=='대.소실적 모두이월'){

												$note=$cnt.'Cycle달성(대.소실적 모두이월) (11) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.')';

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales,  $cond[$i]['per'],$cond[$i]['allowance_name'].' '.$note);

												iwol_process($fr_date, $mbid, $id1, $mbname, $cnt.'Cycle성공', $hap1-($cnt*($cond[$i]['cycle']) )  , $note); 
												iwol_process($fr_date, $mbid, $id2, $mbname, $cnt.'Cycle성공', $hap2-($cnt*($cond[$i]['cycle']) )  , $note); 


											}else if($cond[$i]['max_reset2']=='대실적만 이월'){

												$note=$cnt.'Cycle달성(대실적만 이월) (12) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.') 이월금:'.($hap1-$cond[$i]['sales_reset']);

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales,  $cond[$i]['per'],$cond[$i]['allowance_name'].' '.$note);

												iwol_process($fr_date, $mbid, $id1, $mbname, $cnt.'Cycle성공',$hap1-$cond[$i]['sales_reset'] , $note); 

											}else if($cond[$i]['max_reset2']=='소실적만 이월'){
												$note=$cnt.'Cycle달성(소실적만 이월)(13) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.') 이월금:'.($hap2-$cond[$i]['sales_reset']);

												$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
												if($today_sales>0) save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'], $today_sales, $cond[$i]['per'], $cond[$i]['allowance_name'].' '.$note);

												iwol_process($fr_date, $mbid, $id2, $mbname, $cnt.'Cycle성공',$hap2-$cond[$i]['sales_reset'] , $note); 
											}
											

										}else{ // 1Cycle보다 작다
											$note='^Cycle미달 (14) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.')';

												
												iwol_process($fr_date,$mbid,  $id1, $mbname, 'Cycle미달', $hap1  , $note); 
												iwol_process($fr_date,$mbid,  $id2, $mbname, 'Cycle미달', $hap2  , $note); 
										}
									}
								}  //$hap2가 소실적이라면 

							 }// if(($hap1>0) || ($hap2>0)){


							echo $mbname.'('.$mbid.'): '.$note.'직급: '.$mblevel.'= 직급조건: '.$cond[$i]['mb_level_in1'].'<br>';
								
						  } // 바이너리

											



						}// 수당per 가 있으면 
					} // for
							

					$rec='';



		$rec='';
		$history_cnt=0;
		$today_sales=0;
		

} //for

alert('수당계산이 완료되었습니다');

?>

