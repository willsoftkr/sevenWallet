<?php
$sub_menu = "600600";
include_once('./_common.php');


auth_check($auth[$sub_menu], 'r');


 
// 새로운 공휴일 처리함수
function plus_day($day,$add)
{


		if($add<7 && $add>0){ $add+=2; }

		else if($add<=14 && $add>7){ $add+=4;}

		else if($add<=21 && $add>14){ $add+=6;  }

		else if($add<=28 && $add>21){ $add+=8;}

		else if($add<=31 && $add>28){ $add+=10;  }




		$year=date(Y);
		$holiday = array("2018-01-01",);
		$sql = " select * from holiday where YEAR(h_day)=$year";
		$result = sql_query($sql);
		/*
		if($result){
		  $count = mysql_num_rows($result);
		  if($count == 0){
			alert('공휴일 자료가 없습니다. 공휴일 관리에서 올해의 공휴일을 등록하세요!(배당지급시 공휴일은 건너 뛰어 계산합니다.)');
		  }
		  
		}
		*/

		$il= strtotime($day);
		$nal=date("Y-m-d", strtotime("+$add day", $il));

		   for ($i=0; $row=sql_fetch_array($result); $i++) {
				
				
				  if($nal==$row['h_day']){
					$il=strtotime($nal);
					$nal=date("Y-m-d", strtotime("+1 day", $il));
					$add++;
				  }
			}    
			

		 $exdate = explode("-", $day); 

		 $exyear = $exdate[0]; 
		 $exmonth = $exdate[1]; 
		 $exday = $exdate[2]; 
		 
		 $exweek = array(0=>'일',1=>'월',2=>'화',3=>'수',4=>'목',5=>'금',6=>'토'); 

		 



		  $exweek2 = date("w",mktime(0,0,0,(int) $exmonth,(int) $exday+$add,(int) $exyear)); 
		 if ($exweek2 == 0) {
		   $add++;
		 }

		// 토요일 이라면 월요일로...
		if ($exweek2 == 6) { 
		   $add++;
			$add++;
		 }

   return date("Y-m-d", mktime(0,0,0,(int) $exmonth,(int) $exday+$add,(int) $exyear));
 

}








function habu_sales($recom,$fr_date,$to_date){

	$sql_search = " and date_format(o.od_time,'%Y-%m-%d')>='$fr_date' and date_format(o.od_time,'%Y-%m-%d')<='$to_date'";

    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' ");

    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  	
 			  $odsql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $sql_search");
 			  $odhap+=$odsql['hap'];

			$od=habu_sales($recom,$fr_date,$to_date);	 
			$odhap+=$od;   		
	} // for j	
	 return $odhap;    
}  






function self_sales($recom,$fr_date,$to_date){

	$sql_search = " and date_format(o.od_time,'%Y-%m-%d')>='$fr_date' and date_format(o.od_time,'%Y-%m-%d')<='$to_date' group by md_id";

    $res= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $sql_search");
	
	return $res['hap'];    
	
} 


function self_iwol($recom,$fr_date,$to_date){

	$sql_search = " and date_format(iwolday,'%Y-%m-%d')>='$fr_date' and date_format(iwolday,'%Y-%m-%d')<='$to_date'  group by md_id";

    $res= sql_fetch("select sum(pv)as hap from iwol where mb_id='".$recom."' $sql_search");
	
	return $res['hap'];    
	
} 


function habu_iwol($mbid){
    $res1= sql_fetch("select mb_my_sales+habu_day_sales as hap from g5_member where mb_id='".$mbid."'");
	$res2= sql_fetch("select sum(pv)as hap from iwol where mb_id='".$mbid."'");
	return $res1['hap']+$res['hap'];    	
} 



function my_bchild($mb_id){
	$id1='';
	$id2='';
    $res= sql_query("select mb_id from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		if($j==0){$id1=$rrr['mb_id']; $hap1=habu_iwol($id1); }
		if($j==1){$id2=$rrr['mb_id']; $hap2=habu_iwol($id2); }
	} 
	return array($id1,$hap1,$id2,$hap2);
} 



/*
function my_bchild_sales($mb_id, $self_habu, $fr_date, $to_date){

    $res= sql_query("select * from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
   // $child =array(array('mb_id'=>'','hap'=>'','iwol'=>'','bigorsmall'=>''));

	
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
	
		$hap=0;
		$iwol=0;
		$iiiid=$rrr['mb_id'];

		if($self_habu=='self'){
			$hap=self_sales($iiiid,$fr_date,$to_date); 
			$iwol=self_iwol($iiiid,$fr_date,$to_date); 
		}else{ 
			
			$hap=habu_sales($iiiid,$fr_date,$to_date); 
			$iwol=self_iwol($iiiid,$fr_date,$to_date); 
		}

		$child[$j]['mb_id']=$iiiid;
		$child[$j]['hap']=$hap;
		$child[$j]['iwol']=$iwol;

	} 




	return array($child);
} 
*/




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


//**** 수당이 있다면 함께 DB에 저장 한다.
function iwol_process($day,$mb_recommend, $mbid, $mb_name, $kind, $pv, $note){

	
	$iwol= sql_fetch("select count(*) as cnt from iwol where mb_id='".$mbid."' and date_format(iwolday,'%Y-%m-%d')='$day'");
	if( ($pv>0) && ($iwol['cnt']==0) ){   

		$temp_sql1 = " insert iwol set iwolday='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		//$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,kind		= '".$kind."'";
		$temp_sql1 .= " ,pv		= '".$pv."'";
		$temp_sql1 .= " ,note		= '".$note."'";
		$temp_sql1 .= " ,mb_recommend		= '".$mb_recommend."'";
		sql_query($temp_sql1);
	}

}


function save_benefit($day,$mbid, $mbname, $recom, $allowance_name, $sales_day, $habu_day_sales, $benefit, $rec){


		$temp_sql1 = " insert soodang_pay set day='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
		$temp_sql1 .= " ,allowance_name		= '".$allowance_name."'";
		
		$temp_sql1 .= " ,day_sales		=  '".$sales_day."'";
		$temp_sql1 .= " ,habu_day_sales 		=  '".$habu_day_sales."'";
		$temp_sql1 .= " ,benefit		=  '".($benefit)."'";
		$temp_sql1 .= " ,rec		= '".$rec."'";

		sql_query($temp_sql1);

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





/*
if($sales_yn_chk==1){

$sql= " UPDATE g5_member SET noo_my_sales=0,  mon_my_sales=0 ,day_sales=0, mb_habu_sum=0, noo_habu_sum=0, mon_habu_sum=0, habu_day_sales=0 ,mb_my_sales=0";
sql_query($sql);
}
*/
$allowance_name='바이너리보너스';

if($noo_start_day==''){
	$noo_start_day=date('Y-01');
}


// 직급이 최소 1스타(3) 이상이고 하부에 오늘 매출이 있는 사람만 
$sql = "SELECT m.mb_id, m.mb_name, m.mb_hp, m.mb_level, m.mb_recommend, m.mb_brecommend , s.allowance_name , s.benefit, s.benefit_level FROM g5_member as m, soodang_pay as s WHERE m.mb_id=s.mb_id and benefit_level>0 and date_format(s.day,'%Y-%m-%d')='$to_date'";
$result = sql_query($sql);

//make_class();
//echo $sql;



$rec='';


for ($i=0; $row=sql_fetch_array($result); $i++) {   


		$comp=$row['mb_id'];
		$pay=$row['benefit'];
		$benefit=$row['benefit']/$row['benefit_level'];
		$binary_firstname=$row['mb_name'];
		$binary_firstid=$comp;
		$history_cnt=0;

		while(  ($comp!='admin')  ){ 
			
			$sql = " SELECT mb_id, mb_name, mb_hp, mb_level, mb_recommend, mb_brecommend FROM g5_member WHERE mb_id= '".$comp."'";
			$recommend = sql_fetch($sql);

				$leg_success=0; // 메트릭스 성공찾기 클리어

				
				$mbid=$recommend['mb_id'];
				$mbname=$recommend['mb_name'];
				
				$mblevel=$recommend['mb_level'];

				if(   ($mb_name=='본사')  || ($mbid=='')  ) break;


					
							
					for ($i=0; $i<count($cond); $i++) {


					//위로 연속 올라가는 부분 추천인으로 올라갈지 바이너리추천인으로 올라갈지 결정
					if($cond[$i]['recom_kind']=='mb_recommend'){
							$recom=$recommend['mb_recommend'];
					}else{
							$recom=$recommend['mb_brecommend'];
					}


						//위$cond에서 설정한 내용에 대해 다시 변수설정하여 트리거 걸릴지 말지 설정
					
						if($cond[$i]['level1']=='1'){ $temp_cond_level1=1; } else {$temp_cond_level1=0;} //본인직급
					
						if($cond[$i]['history']=='1'){ $temp_cond_history=1; } else {$temp_cond_history=0;} //대수조건
						






						

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


								
						

								// 바이너리매칭 대수조건

								if(   ($cond[$i]['history_cond1']=='==')   ){

										if(($history_cnt)==$cond[$i]['history_in1']){
											$bm=0;
										}else{
											$bm=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond1']=='')  ){
										if(($history_cnt)>=$cond[$i]['history_in1']){
											$bm=0;
										}else{
											$bm=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='') && ($cond[$i]['history_cond1']=='<=')  ){

										if(($history_cnt)<=$cond[$i]['history_in1']){
											$bm=0;
										}else{
											$bm=1;
										}

								}else if(  ($cond[$i]['history_cond1']=='>=') && ($cond[$i]['history_cond2']=='<=')  ){
										if( (($history_cnt)>=$cond[$i]['history_in1']) && ($history_cnt<=$cond[$i]['history_in2']) ){
											$bm=0;
										}else{
											$bm=1;
										}
								
								}

echo $mbname.'('.$mbid.'): 대수'.$history_cnt.'=='.$cond[$i]['history_in1'].'   직급 : '.$cond[$i]['mb_level_in1'].'=='.$mblevel.'  '.$bm,'---'.$temp_cond_level1.' /  '.$binary_firstname.'('.$binary_firstid.') 로부터 '.$cond[$i]['allowance_name'].' 발생<br>';
								if( ($bm==0) && ($temp_cond_level1==0)  ){


									if( ($pay>0) && ($cond[$i]['base_source']=='Cycle') ) {
									
										
										$rec=$binary_firstname.'('.$binary_firstid.') 로부터 '.$cond[$i]['allowance_name'].' 발생, 내 직급: '.$mb_level.', 대수: '.($history_cnt).' Cycle 수: '.$benefit;

										//echo $mbname.'('.$mbid.'): '.$rec.'<br>';

										save_benefit($fr_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], $recommend['sales_day'], $recommend['habu_day_sales'],($benefit*$cond[$i]['per']) , $cond[$i]['allowance_name'].' '.$rec);
									}
								}




							

						}// 수당per 가 있으면 
					} // for
							
				
					
					//echo $rec;
					$rec='';


			$comp=$recom;


			$history_cnt++;



		} // while

		$rec='';
		

} //for

alert('수당계산이 완료되었습니다');

?>

