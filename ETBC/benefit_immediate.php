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
		$holiday = array(date("Y")."-01-01",);
		$sql = " select * from holiday where YEAR(h_day)=$year";
		$result = sql_query($sql);


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



$yy= strtotime($day);
$min30=date("Y-m-d", strtotime("-30 day", $yy));

function habu_sales_calc($recom,$fr_date,$to_date,$kind){

	$sql_search = " and date_format(o.od_time,'%Y-%m-%d')>='$fr_date' and date_format(o.od_time,'%Y-%m-%d')<='$to_date'";

    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' ");

    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
 			  $recom=$rrr['mb_id'];  	
			  
 			  $odsql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $sql_search");
 			  $odhap+=$odsql['hap'];
			$od=habu_sales($recom,$fr_date,$to_date,$kind);	 
			$odhap+=$od;  	

			switch($kind){
				case 'noo':
					sql_query("insert noo SET noo_habu_sum=".$odhap." ,mb_id='".$recom."'");
				break;
				case 'thirty':
					sql_query("insert thirty SET noo_habu_sum=".$odhap." ,mb_id='".$recom."'");
				break;
			}

	} // for j	
	 return $odhap;    
}  




//habu_sales_calc('admin',$start_day,$to_date, 'noo'); // 누적

//habu_sales_calc('admin',$min30,$to_date,'thirty'); // 30일매출



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


function self_sales($recom){

    $res= sql_fetch("select sum(pv)as hap from g5_shop_order as o where o.mb_id='".$recom."'");
	echo "select sum(pv)as hap from g5_shop_order as o where o.mb_id='".$recom."'". $res['hap'].'<br>';    
	return $res['hap'];    
	
} 


function self_iwol($recom,$fr_date,$to_date){

	$sql_search = " and date_format(iwolday,'%Y-%m-%d')>='$fr_date' and date_format(iwolday,'%Y-%m-%d')<='$to_date'  group by md_id";

    $res= sql_fetch("select sum(pv)as hap from iwol where mb_id='".$recom."' $sql_search");
	
	return $res['hap'];    
	
} 



function my_bchild_hap($mb_id){

	$hap=0;
	$cnt=0;
	
	$hap=self_sales($mb_id); //먼저 자기매출

    $res= sql_query("select mb_id from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
		$hap+=self_sales($rrr['mb_id']); // 하부매출을 구한다
		$cnt++;
	} 
	
	if($cnt>=2){
		return $hap;
	}else{
		return 0;
	}
} 




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
function iwol_process($day,$mbid, $mb_name, $kind, $pv, $note){
	$temp_sql1 = " insert iwol set iwolday='".$day."'";
	$temp_sql1 .= " ,mb_id		= '".$mbid."'";
	$temp_sql1 .= " ,mb_name		= '".$mbname."'";
	$temp_sql1 .= " ,kind		= '".$kind."'";
	$temp_sql1 .= " ,pv		= '".$pv."'";
	$temp_sql1 .= " ,note		= '".$note."'";
	sql_query($temp_sql1);
}


$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));

$benefit = "SELECT * from soodang_set where immediate=1 order by partner_cnt desc, no ";
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


						


if($benefit_yn_chk==1){
 	$sql= " delete from soodang_pay"; 
	sql_query($sql);
}



if($sales_yn_chk==1){

$sql= " UPDATE g5_member  SET mb_my_sales=0, noo_my_sales=0,  mon_my_sales=0 ,day_my_sales=0, mb_habu_sum=0, noo_habu_sum=0, mon_habu_sum=0, habu_day_sales=0 ";
sql_query($sql);
}



//$fr_date="2015-05-01";
//$to_date="2015-05-31";

$day=date('Y-m-d');

$start_day='2018-01-01'; // 누적 수당계산 시작일

if($fr_date==''){ 
	$fr_date=$day;
}
if($to_date==''){ 
	$to_date=$day;
}

	//$to_date=date('Y-m-d');

	

	//$ym=substr($fr_date,0,7);




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


if($noo_start_day==''){
	$noo_start_day=date('Y-01');
}

$sql_common = " FROM g5_shop_order AS o, g5_member AS m ";

$sql_search=" WHERE o.mb_id=m.mb_id AND o.mb_id=m.mb_id";
if($mb_id) {$sql_member=" AND m.mb_id='".$mb_id."'"; }else{ $sql_member=""; }

$searchdate=" AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')>='".$fr_date."' AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')<='".$to_date."' GROUP BY mb_id, DATE_FORMAT(o.od_receipt_time,'%Y-%m')";


$search_mon_date=" AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')>='".$min30."' AND DATE_FORMAT(o.od_receipt_time,'%Y-%m-%d')<='".$day."' GROUP BY mb_id, DATE_FORMAT(o.od_receipt_time,'%Y-%m')"; //

$searchdate_noo=" AND DATE_FORMAT(o.od_receipt_time,'%Y-%m')>='".$noo_start_day."'";

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


//make_class();


$history_cnt=0;

$rec='';


$onestar=3000; // 1스타 승급매출 조건


for ($i=0; $row=sql_fetch_array($result); $i++) {   

	
	$today_sales2=0;
	$today=$row['od_receipt_time'];
	

		$comp=$row['mb_id'];
		$confirm_exit1=0;
		$first=0; 
		$firstname='';
		$firstid='';
		$today_sales=$row['hap'];


		while(  ($comp!='admin')  ){   

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


				if(   ($mb_name=='본사')  || ($mbid=='') || ($mbid=='admin')  ) { echo $oldcomp."admin , 본사 혹은 ''을 만나 정지됨"; break;}





					$sql3 = " update g5_member set sales_day='".$today."',";
					if($history_cnt==0)
					{ 
						$sql3 .= " mb_my_sales=mb_my_sales	+ ".$today_sales;
					}else{
						$sql3 .= " habu_day_sales=habu_day_sales+".$today_sales;
					}

					//************************* 1스타 직급 승급 ************************************			
					if($mblevel<3){  //현재 내 직급이 1star 이하이면
						if(my_bchild_hap($mbid)>=$onestar){
							$sql3 .= " , mb_level=3";
							$sql3 .= " , rank_day='".$day."'";
								echo $mbid." 승급함<br>";
						}
					}

					$sql3 .= " where mb_id='".$comp."'";
					//echo $sql3;
					sql_query($sql3);

	



							
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
					
						if( ($cond[$i]['base_source']=='추천수당') && ($history_cnt==0) ){
							
							$firstname=$mbname;
							$firstid=$mbid;
							
							$first=$pool_level; 
							
						}
						



						//그런데 추천수당중 매출발생자 매출보다 커야만 받을수 있는 조건이 있다면 상위업라인들의 누적매출이 매출발생자 매출보다 같거나 커야만 지급한다. 
						if( $bf_limit1>0 ){
						
							if($first>=$pool_level){
								$limit1=0;
							}else{
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
						
								if(   ($temp_cond_history==0)  &&  ($temp_cond_bigsmall1==0) &&  ($temp_cond_bigsmall2==0)  &&  ($temp_cond_level1==0)  && ($temp_cond_leve2==0)  &&  ($temp_cond_mat==0) && ($limit_reset==0)  ){


										//if($save_benefit==1){ // 저장하라면 

											
													$benefit=($today_sales)*($cond[$i]['per']/100);

								$rec.=$cond[$i]['allowance_name'].': '.$firstname.'('.$firstid.') 으로부터 '.$history_cnt.'대 -'.$cond[$i]['base_source'].':'.$today_sales.'*'.($cond[$i]['per']/100).'='.$benefit.' / <br/>';
						
											

									

												echo $rec;


												   //**** 수당이 있다면 함께 DB에 저장 한다.
												$temp_sql1 = " insert soodang_pay set day='".$day."'";
												$temp_sql1 .= " ,mb_id		= '".$mbid."'";
												$temp_sql1 .= " ,mb_name		= '".$mbname."'";
												$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
												$temp_sql1 .= " ,allowance_name		= '".$cond[$i]['allowance_name']."'";
												$temp_sql1 .= " ,andor		= '".$cond[$i]['andor']."'";
												
												$temp_sql1 .= " ,accu_my_sales		=  '".$recommend['noo_my_sales']."'";
												$temp_sql1 .= " ,mon_my_sales		=  '".$recommend['mon_my_sales']."'";
												$temp_sql1 .= " ,accu_habu_sum		=  '".$recommend['noo_habu_sum']."'";
												$temp_sql1 .= " ,mon_habu_sum		=  '".$recommend['mon_habu_sum']."'";
												$temp_sql1 .= " ,benefit		=  '".($benefit)."'";
												if($limit1>0){ $temp_sql1 .= " ,benefit_limit1		=  1";}
												
												$temp_sql1 .= " ,rec		= '".$rec."'";
												

												sql_query($temp_sql1);
												

									
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

alert('수당계산이 완료되었습니다');

?>

