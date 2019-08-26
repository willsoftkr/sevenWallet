<?php
$sub_menu = "600600";
include_once ('./_common.php');

//auth_check($auth[$sub_menu], 'r');
//$to_date =  date("Y-m-d",time() - 3600*24);
if ($to_date){
	$day       = $to_date;
}else{
	$day    = date('Y-m-d');
}
habu_sales_calc('b','copy5285m',0);
habu_sales_calc('','copy5285m',0);
$cond = array(array('price_cond'=>'','source'=>'','source_cond1'=>'','source_cond2'=>'','source_in1'=>'','source_in2'=>'','mb_level_cond1'=>'','mb_level_cond2'=>'','mb_level_in1'=>'','mb_level_in2'=>'','partner_cnt'=>'','partner_cont'=>'','history_cnt'=>'','history_cond1'=>'','history_cond2'=>'','history_in1'=>'','history_in2'=>'','base_source'=>'','per'=>'','allowance_name'=>'','mat'=>'','level1'=>'','level2'=>'','bigsmall1'=>'','bigsmall2'=>'','history'=>'','benefit'=>'','benefit_limit1'=>'','source11'=>'','source_cond11'=>'','source_cond12'=>'','source_in11'=>'','source_in12'=>'','sales_reset'=>'','max_reset1'=>'','max_reset2'=>'','mb_level_in11'=>'','mb_level_cond11'=>'','mb_level_cond12'=>'','bf_limit1'=>''));

$benefitSql = "SELECT * from eos_soodang_set where immediate=2 order by partner_cnt desc, no";
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

	if( ($row['sales_reset']>0) && ($row['cycle']>0)  && ($row['max_reset1']<>'')  && ($row['max_reset2']<>'') ){
		$cond[$i]['limit_reset']=1;
	}  //극점 사용여부

	if( ($row['partner_cnt']>0) && ($row['partner_cont']>0) ){
		$cond[$i]['mat']=1;
	}  // 메트릭스

	if(  ($row['source_in1']!=0) || ($row['source_in2']!=0) ){
		$cond[$i]['bigsmall1']=1;
	}// 대소실적조건1

	if(  ($row['source_in11']!=0) || ($row['source_in12']!=0) ){
		$cond[$i]['bigsmall2']=1;  }// 대소실적조건12

	if(  ($row['mb_level_in1']!=0) || ($row['mb_level_in2']!=0) ){
		$cond[$i]['level1']=1;
	} //본인직급

	if(  ($row['mb_level_cond11']!=0) || ($row['mb_level_cond12']!=0) ){
		$cond[$i]['level2']=1;
	} //하부직급

	if(  ($row['history_cond1']!='') || ($row['history_cond2']!='') ){
		$cond[$i]['history']=1;
	}  //대수 level

	if( $row['benefit_limit1']>0  ){
		$cond[$i]['bf_limit1']=1;
	}  //매출자보다 수당받을자의 매출이 같거나 큰가?
}

if($iwol_yn_chk==1){
 	$sql= " delete from iwol where date_format(iwolday,'%Y-%m-%d')='$to_date'";
	echo "이월DB지우고 다시생성<br><br>";
	sql_query($sql);
}
$history_cnt=0;
$no_benefit=0;
$rec='';
// 직급이 최소 1스타(3) 이상

$sql = "select * from g5_member where mb_level>=1 order by mb_no";
echo $sql;
$result = sql_query($sql);

for($i=0; $recommend=sql_fetch_array($result); $i++) {

	$binary_trig=0;
	$today_sales=0;
	$today_sales2=0;
	$leg_success=0; // 메트릭스 성공찾기 클리어
	$mbid=$recommend['mb_id']; //회원 아이디 - 등급에 따라 바이너리 후원 %가 다르다.
	$mbname=$recommend['mb_name'];
	$mblevel=$recommend['mb_level'];
	$sum =  sql_fetch( "SELECT sum(pv) as od_sum FROM g5_shop_order WHERE 1 and od_receipt_time like '$to_date%' and mb_id ='".$mbid."'");
	if($sum['od_sum']==null)
		$limit_point=0;
	else 
		$limit_point = $sum['od_sum'];
	echo 'limit_point'.$limit_point;
	if(($mb_name=='본사') || ($mbid=='')  )
		break;
	$iwol_pass =0;
	$benefit_pass =0;

	for ($i=0; $i<count($cond); $i++) {
		if($cond[$i]['level1']=='1'){
			$temp_cond_level1=1;
		} else {
			$temp_cond_level1=0;
		} //본인직급
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
		if($cond[$i]['per']!=''){ // 수당 퍼센트가 비어있으면 패스~
			//******   본인직급 조건이 있다면  성공여부 기록
			if(($cond[$i]['mb_level_cond1']=='==')   ){
				if($mblevel==$cond[$i]['mb_level_in1']){
					$temp_cond_level1=0;
				}else{
					$temp_cond_level1=1;
				}
			}
			else if(($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='')  ){
				if($mblevel>=$cond[$i]['mb_level_in1']){
					$temp_cond_level1=0;
				}else{
					$temp_cond_level1=1;
				}
			}else if(($cond[$i]['mb_level_cond1']=='') && ($cond[$i]['mb_level_cond2']=='<=')  ){
				if($mblevel<=$cond[$i]['mb_level_in2']){
					$temp_cond_level1=0;
				}else{
					$temp_cond_level1=1;
				}
			}else if(  ($cond[$i]['mb_level_cond1']=='>=') && ($cond[$i]['mb_level_cond2']=='<=')  ){
				if(($mblevel>=$cond[$i]['mb_level_in1']) && ($mblevel<=$cond[$i]['mb_level_in2'])  ){
					$temp_cond_level1=0;
				}else{
					$temp_cond_level1=1;
				}
			}
			//******   본인직급 조건이 있다면  성공여부 기록

			//******  바이너리보너스 계산
			if(($limit_reset==1) && ($temp_cond_level1==0) ){
				$note='';
				$id1='';
				$id2='';
				$big=0;
				$small=0;
				$hap1=0;
				$hap2=0;
				list($id1,$hap1,$id2,$hap2) = my_bchild($mbid,$to_date,$cond[$i]['cycle']);
				echo $mbid.': '.$id1.'---'.$hap1.'---'.$id2.'---'.$hap2.'<br>';

				if(($hap1>0) || ($hap2>0)){
					if( $hap1<=$hap2 )
					{ //$hap1이 소실적이라면
						if( ($hap1*$cond[$i]['per']/100 ) > $limit_point && $limit_point!=0){ //소실적이 극점?

							$today_sales=$limit_point;
							$firstname=$mbname;
							$firstid=$mbid;
							echo "수당 계산 하자 asdf : ".$id1.' '.$hap1.' '.$id2.' '.$hap2.'percentage : '.$cond[$i]['per'].'today_sales'.$today_sales.'<br>';
							if( $cond[$i]['max_reset1'] =='대.소실적 모두이월'){
								$note_adm='극점초과(대.소실적 모두이월) (1) 소실적:'.$hap1.'('.$id1.') / 대실적:'.$hap2.'('.$id2.')';
								echo $note='Binary Cycle Bonus for '.$maxcycle.' cycles as a '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
								save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $hap1, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid, $id1, $mbname, 1, $hap1-$cond[$i]['sales_reset'] , $note_adm);
								iwol_process($to_date, $mbid, $id2, $mbname, 1,$hap2-$cond[$i]['sales_reset'] , $note_adm);
							}
							else if($cond[$i]['max_reset1']=='대실적만 이월'){
								$note_adm='소실적 발생 (대실적만 이월) (2) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap2-$hap1);
								
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0){
								save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $limit_point, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);}
								echo '대실적 이월해야한다.';
								echo '대실적 이월해야한다. 제발'."<br>";
								iwol_process($to_date, $mbid, $id2, $mbname, 2, $hap2-$hap1, $note_adm);
								//iwol_process($to_date, $mbid, $id2, $mbname, 2, $hap2, $note_adm);

							}
							else if($cond[$i]['max_reset1']=='소실적만 이월'){
								$note_adm='극점초과(소실적만 이월) (3) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap1-$cond[$i]['sales_reset']);
								echo  $note='Binary Cycle Bonus for '.$maxcycle.' cycles as a '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
									save_benefit($to_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $hap1, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid,  $id1, $mbname, 3, $hap2-$hap1 , $note_adm);
							}
						}
						else { //소실적이 극점x
						//if($hap1>=($cond[$i]['sales_reset']) ){ //소실적이 극점?
							$today_sales=$hap1*$cond[$i]['per']/100;
						//	$binary_trig=($cond[$i]['sales_reset']/$cond[$i]['cycle']);
							$firstname=$mbname;
							$firstid=$mbid;
							echo "수당 계산 하자 fads : ".$id1.' '.$hap1.' '.$id2.' '.$hap2.'percentage : '.$cond[$i]['per'].'today_sales'.$today_sales.'<br>';
							if( $cond[$i]['max_reset1'] =='대.소실적 모두이월'){
								$note_adm='극점초과(대.소실적 모두이월) (1) 소실적:'.$hap1.'('.$id1.') / 대실적:'.$hap2.'('.$id2.')';
								echo $note='Binary Cycle Bonus for '.$maxcycle.' cycles as a '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
								save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $hap1, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid, $id1, $mbname, 1, $hap1-$cond[$i]['sales_reset'] , $note_adm);
								iwol_process($to_date, $mbid, $id2, $mbname, 1,$hap2-$cond[$i]['sales_reset'] , $note_adm);
							}
							else if($cond[$i]['max_reset1']=='대실적만 이월'){
								$note_adm='소실적 발생 (대실적만 이월) (22) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap2-$hap1);
								echo $note='Binary Bonus for '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
								save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $limit_point, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid, $id2, $mbname, 2, $hap2-$hap1, $note_adm);
								//iwol_process($to_date, $mbid, $id2, $mbname, 2, $hap2, $note_adm);

							}
							else if($cond[$i]['max_reset1']=='소실적만 이월'){
								$note_adm='극점초과(소실적만 이월) (3) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap1-$cond[$i]['sales_reset']);
								echo  $note='Binary Cycle Bonus for '.$maxcycle.' cycles as a '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
									save_benefit($to_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $hap1, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid,  $id1, $mbname, 3, $hap2-$hap1 , $note_adm);
							}
						}
					}  //$hap1이 소실적이라면
					else{ //$hap2가 소실적이라면
						if($hap2*$cond[$i]['per']/100>=$limit_point && $limit_point!=0){ //소실적이 극점?
							$today_sales=$limit_point;
							$binary_trig=($cond[$i]['sales_reset']/$cond[$i]['cycle']);
							$firstname=$mbname;
							$firstid=$mbid;
							echo "수당 계산 하자 : ".$id1.' '.$hap1.' '.$id2.' '.$hap2.'percentage : '.$cond[$i]['per'].'today_sales'.$today_sales.'<br>';
							if($cond[$i]['max_reset1']=='대.소실적 모두이월'){
								$note_adm='극점초과(대.소실적 모두이월) (8) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.')';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
									save_benefit($to_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $hap2, cond[$i]['allowance_name'].' '.$note_adm,$note, $mblevel);
								//echo $note='Binary Cycle Bonus for '.$maxcycle.' cycles as a '.$deslv.' member';
								iwol_process($to_date, $mbid, $id1, $mbname, 8,$hap1-$cond[$i]['sales_reset'] , $note_adm);
								iwol_process($to_date, $mbid, $id2, $mbname, 8,$hap2-$cond[$i]['sales_reset'] , $note_adm);
							}else if($cond[$i]['max_reset1']=='대실적만 이월'){
								$note_adm='소실적 발생 (대실적만 이월) (9) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id1.') 이월금:'.($hap1-$hap2);
								echo $note='Binary Bonus for '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
								save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales,  $hap2, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid, $id1, $mbname, 9, $hap1-$hap2 , $note_adm);
								//iwol_process($to_date, $mbid, $id1, $mbname, 9, $hap1 , $note_adm); //대실적 1/2

							}else if($cond[$i]['max_reset1']=='소실적만 이월'){
								$note_adm ='극점초과(소실적만 이월) (10) 소실적:'.$hap2.	'('.$id2.') / 대실적:'.$hap1.	'('.$id2.') 이월금:'.($hap2-$cond[$i]['sales_reset']);
								//echo $note='Binary Cycle Bonus as a '.$maxcycle.' cycles as a '.$deslv.' member';
								iwol_process($to_date, $mbid, $id2, $mbname, 10, $hap2-$cond[$i]['sales_reset'] , $note_adm);
							}
						}
						else { //소실적이 극점x
						//if($hap1>=($cond[$i]['sales_reset']) ){ //소실적이 극점?
							$today_sales=$hap2*$cond[$i]['per']/100;
						//	$binary_trig=($cond[$i]['sales_reset']/$cond[$i]['cycle']);
							$firstname=$mbname;
							$firstid=$mbid;
							echo "수당 계산 하자 1234 : ".$id1.' '.$hap1.' '.$id2.' '.$hap2.'percentage : '.$cond[$i]['per'].'today_sales'.$today_sales.'<br>';
							if( $cond[$i]['max_reset1'] =='대.소실적 모두이월'){
								$note_adm='극점초과(대.소실적 모두이월) (1) 대실적:'.$hap1.'('.$id1.') / 소실적:'.$hap2.'('.$id2.')';
								echo $note='Binary Cycle Bonus for '.$maxcycle.' cycles as a '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
								save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $hap1, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid, $id1, $mbname, 1, $hap1-$cond[$i]['sales_reset'] , $note_adm);
								iwol_process($to_date, $mbid, $id2, $mbname, 1,$hap2-$cond[$i]['sales_reset'] , $note_adm);
							}
							else if($cond[$i]['max_reset1']=='대실적만 이월'){
								echo "수당 계산 하자 대실적만이월하면되잔아 : id1".$id1.' hap1'.$hap1.' id2'.$id2.' hap2'.$hap2.'percentage : '.$cond[$i]['per'].'today_sales'.$today_sales.'<br>';
								$note_adm='소실적 발생 (대실적만 이월) (99) 대실적:'.$hap1.	'('.$id1.') / 소실적:'.$hap2.	'('.$id2.') 이월금:'.($hap1-$hap2);
								echo $note='Binary Bonus for '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
								save_benefit($to_date, $mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $today_sales, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid, $id1, $mbname, 99, $hap1-$hap2, $note_adm);
								//iwol_process($to_date, $mbid, $id2, $mbname, 2, $hap2, $note_adm);

							}
							else if($cond[$i]['max_reset1']=='소실적만 이월'){
								$note_adm='극점초과(소실적만 이월) (3) 소실적:'.$hap1.	'('.$id1.') / 대실적:'.$hap2.	'('.$id2.') 이월금:'.($hap1-$cond[$i]['sales_reset']);
								echo  $note='Binary Cycle Bonus for '.$maxcycle.' cycles as a '.$deslv.' member';
								$no_benefit=1;$binary_firstname=$mbname;$binary_firstid=$mbid;
								if($today_sales>0)
									save_benefit($to_date,$mbid, $mbname, $recom, $cond[$i]['allowance_name'], '', 0, $today_sales, $hap1, $cond[$i]['allowance_name'].' '.$note_adm, $note, $mblevel);
								iwol_process($to_date, $mbid,  $id1, $mbname, 3, $hap2-$hap1 , $note_adm);
							}
						}
					}  //$hap2가 소실적이라면
	
				}// if(($hap1>0) || ($hap2>0)){
				echo $mbname.'('.$mbid.'): '.$note.'직급: '.$mblevel.'= 직급조건: '.$cond[$i]['mb_level_in1'].'<br>';
			} // 바이너리보너스 계산
		}// 수당per 가 있으면
	} // for
	$rec='';
	$rec='';
	$history_cnt=0;
	$today_sales=0;
} //for

function habu_sales_calc($gubun, $recom, $deep){

	global $fr_date, $to_date;
	$deep++; // 대수

	$start_day = '2017-07-01';

	if ($to_date){
		$day       = $to_date;
	}else{
		$day    = '2018-07-06'; //  = date('Y-m-d');
	}
	$yy= strtotime($day);

	$min30=date("Y-m-d", strtotime("-30 day", $yy));
	
    $res= sql_query("select * from g5_member where mb_".$gubun."recommend='".$recom."' ");
	echo "select * from g5_member where mb_".$gubun."recommend='".$recom."' "."<br>";
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
	
		$recom=$rrr['mb_id'];  
		echo $recom.'/'.'<br>'; 

		$noo_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$start_day' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
		$sql1= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $noo_search");
		$noo+=$sql1['hap'];

		$mon_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')>='$min30' and date_format(o.od_receipt_time,'%Y-%m-%d')<='$day'";
		$sql2= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $mon_search");
		$mon+=$sql2['hap'];
		
		$day_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')='$day'";
		$sql3= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search");
		$today+=$sql3['hap'];
		
		echo $recom.'/'.'<br>'; 
		echo "select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search"."<br>";
		
		/*
		if($sql['hap']>0){echo "<br>".$sql['hap']."---select sum(pv)as hap from g5_shop_order as o where mb_id='".$recom."' $day_search".'<br>';
		}
		*/
		
		
		echo "select sum(pv) as hap from g5_shop_order  where mb_id='".$mbid."' ".$day_search;
		echo			'today :'.$today.' / '.$sql3['hap'].'mysales : '.$mysales ."<br>";
		list($noo_r,$mon_r,$today_r)=habu_sales_calc($gubun, $recom, $deep);	 

			$noo_r+=$mysales;
			$mon_r+=$mysales;
			$today_r+=$mysales;

			$noo+=$noo_r;
			$mon+=$mon_r;  
			$today+=$today_r; 

				if( ($noo>0) && ($noo_r>0)) {
				if($j==0){
					$rec=$noo;
				}else{
					$rec=$noo_r;	
				}
				$inbnoo = "insert ".$gubun."noo2 SET noo=".$rec.", mb_id='".$recom."',  day = '".$to_date."'";
				sql_query($inbnoo);	
				echo $inbnoo."<br>";
			}
			
			if(($mon>0) && ($mon_r>0) ) {
				if($j==0){
					$rec=$mon;
				}else{
					$rec=$mon_r;	
				}
				$inthirty = "insert ".$gubun."thirty2 SET thirty=".$rec.", mb_id='".$recom."',  day = '".$to_date."'";
				sql_query($inthirty);
				echo $inthirty."<br>";
			}
			
			if(($today>0) && ($today_r>0)) {
				if($j==0){
					$rec=$today;
				}else{
					$rec=$today_r;
				}
				$mysql = sql_fetch("select sum(pv) as hap from g5_shop_order  where mb_id='".$recom."' and date_format(od_receipt_time,'%Y-%m-%d')='".$to_date."'");
				$mysum = $mysql['hap'];
				echo "<br>";
				echo "select sum(pv) as hap from g5_shop_order  where mb_id='".$recom."' and date_format(od_receipt_time,'%Y-%m-%d')='".$to_date."'";
				$rec=$rec - $mysum;
				echo "<br>";
				echo 'rec : '.$rec.' mysales : '.$mysum;
				$intoday = "insert ".$gubun."today2 SET todayy=".$rec.", mb_id='".$recom."',  day = '".$to_date."'";
				sql_query($intoday);
				echo $intoday."<br>";
			}
	} // for j	
	 return array($noo,$mon,$today);
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
function today_sales($mbid, $day){
	$day_search = " and date_format(o.od_receipt_time,'%Y-%m-%d')='$day'";
	$sql= sql_fetch("select sum(pv)as hap from g5_shop_order as o where mb_id='".$mbid."' $day_search");
	if($sql['hap']=='')
	{
		$hap=0;
	}else{
		$hap=$sql['hap'];
	}
	return $hap;
}
function btoday_select($mbid,$day){
	$res= sql_fetch("select todayy from btoday2 where mb_id='".$mbid."' and day='".$day."'");
	if($res['todayy']=='')
	{
		$hap=0;
	}else{
		$hap=$res['todayy'];
	}
	return $hap;
}
function minus_iwol($mbid,$day){
	$res2= sql_fetch("select sum(pv)as hap from iwol where mb_id='".$mbid."'");
	$hap=$res2['hap'];
	if($hap>0){
		$temp_sql1 = " insert iwol set iwolday='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		$temp_sql1 .= " ,pv		= '".-($hap)."'";
		$temp_sql1 .= " ,kind		= 1";
		$temp_sql1 .= " ,note		= '이월매출사용'";
		sql_query($temp_sql1);
		echo $temp_sql1.'***1***<br>';
	}
}
function plus_iwol($mbid,$day){
	$hap=(btoday_select($mbid,$day)+today_sales($mbid,$day));  //자기매출과 하부매출을 합하여
	if($hap>0){
		$temp_sql1 = " insert iwol set iwolday='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		$temp_sql1 .= " ,pv			= '".($hap)."'";
		$temp_sql1 .= " ,kind		= 100";
		$temp_sql1 .= " ,note		= '금일 발생한 매출이월'";
		sql_query($temp_sql1);
		echo $temp_sql1.'***100***<br>';
	}
}
function habu_iwol($mbid,$day,$cycle){
    //$res1= sql_fetch("select mb_my_sales+habu_day_sales as hap from g5_member where mb_id='".$mbid."' and sales_day='".$day."'");
	$hap1=(btoday_select($mbid,$day)+today_sales($mbid,$day));  //자기매출과 하부매출을 합하여
	$res2= sql_fetch("select sum(pv) as hap from iwol where mb_id='".$mbid."'");
	$hap2=$res2['hap'];
	echo '#########'.$hap1.'+'.$hap2.'<'.$cycle.' && '.$hap1.'>0';

	return ($hap1+$hap2);
	//return ($hap2);
}
function my_bchild($mb_id,$day,$cycle){
	echo 'my_bchild run : '.$mb_id.' '.$day.' '.$cycle;
	$id1='';
	$id2='';
	$hap1=0;
	$hap2=0;
    $res= sql_query("select mb_id from g5_member where mb_brecommend='".$mb_id."' order by mb_no");
	for ($j=0; $rrr=sql_fetch_array($res); $j++) {
		if($j==0){
			$id1=$rrr['mb_id'];
			$hap1=habu_iwol($id1, $day, $cycle);
			if($hap1==''){ $hap1=0;}
		}
		if($j==1){
			$id2=$rrr['mb_id'];
			$hap2=habu_iwol($id2, $day, $cycle);
			if($hap2==''){ $hap2=0;}
		}
	}
	echo 'my_bchild run hap: '.$id1.' '.$id2.' '.$hap1.' '.$hap2;

	if($hap1>0) { // cycle보다 같거나 크면 -이월한다
		minus_iwol($id1, $day);
	}
	if($hap2>0){
		minus_iwol($id2, $day);
	}
	return array($id1, $hap1, $id2, $hap2);
}
/* 이월이 있다면 함께 DB에 저장 한다.*/
function iwol_process($day,$mb_recommend, $mbid, $mb_name, $kind, $pv, $note){
	$iwol= sql_fetch("select count(*) as cnt from iwol where mb_id='".$mbid."' and kind<>1 and date_format(iwolday,'%Y-%m-%d')='$day'");
	echo '### '.$pv.'---'.$iwol['cnt'].'<br>';
//	if( ($pv>0) && ($iwol['cnt']==0) ){
	if( ($pv>0)  ){   // 소실적 제거용
		$temp_sql1 = " insert iwol set iwolday='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		//$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,kind		= '".$kind."'";
		$temp_sql1 .= " ,pv		= '".$pv."'";
		$temp_sql1 .= " ,note		= '".$note."'";
		$temp_sql1 .= " ,mb_brecommend		= '".$mb_recommend."'";
		sql_query($temp_sql1);
		echo $temp_sql1.'******2******<br>';
	}
}
/* function end*/
// benefit_level은 per 금액을 넣어서 바이너리매칭보너스에서 사용한다
function save_benefit($day, $mbid, $mbname, $recom, $allowance_name, $sales_day, $habu_day_sales, $benefit, $benefit_level, $rec_adm, $rec,$mblevel){
	$iwol= sql_fetch("select count(*) as cnt from iwol where mb_brecommend='".$mbid."' and date_format(iwolday,'%Y-%m-%d')='$day'");
	if($iwol['cnt']==0){
		//수당을 비트로 환산 한다.
		$benefit_bit = round($benefit,3);
		$temp_sql1 = " insert soodang_pay set day='".$day."'";
		$temp_sql1 .= " ,mb_id		= '".$mbid."'";
		$temp_sql1 .= " ,mb_name		= '".$mbname."'";
		$temp_sql1 .= " ,mb_level      = ".$mblevel;
		$temp_sql1 .= " ,mb_recommend		= '".$recom."'";
		$temp_sql1 .= " ,allowance_name		= '".$allowance_name."'";
		$temp_sql1 .= " ,benefit_level		=  '".($benefit_level)."'";
		$temp_sql1 .= " ,rec		= '".$rec."'";
		$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";

		
		
		$mrow =sql_fetch( "select mb_deposit_point from g5_member where mb_id='".$mbid."'");
		$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from soodang_pay where 1=1 and mb_id='".$mbid."'"); //reset_day 부터 오늘 까지 수당합.

		if($soodang_sum[eb_sum]  >= $mrow[mb_deposit_point]*5){//보유 EOS의 5배를 수당으로 받았을 시에 이 회원 레벨은 0으로 바뀌고 매출도 사라 진다.
			if($soodang_sum[eb_sum]>=		$mrow[mb_deposit_point]*5){				
				//$reset_mem = "update g5_member set mb_level=0, mb_deposit_point=0, reset_day='".$to_date."' where mb_id ='".$mbid."'";
				//sql_query($reset_mem);
			}
			/*else {
				$over5p =  ($soodang_sum[eb_sum] + $benefit) - ($mrow[mb_deposit_point]*5) ;
				if($over5p>0){
					$temp_sql1 .= " ,benefit			=  ".$benefit - $over5p;
					$temp_sql1 .= " ,benefit_usd		=  '".($benefit - $over5p)."'";
					sql_query($temp_sql1);
					$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit - $over5p.",3) 	where mb_id = '".$mbid."';";
					sql_query($balance_up);
					$reset_mem = "update g5_member set mb_level=0, mb_deposit_point=0, reset_day='".$to_date."' where mb_id ='".$mbid."'";
					sql_query($reset_mem);
				}
			}*/
		}
		else{
			$daily_soo_sum = sql_fetch("select sum(benefit) as ds_sum from soodang_pay where allowance_name='".$allowance_name."' and day='".$to_date."' and mb_id='".$mbid."'");
			if($daily_soo_sum[ds_sum]==null){
				$ds_sum  = 0;
			}
			else{
				$ds_sum = $daily_soo_sum[ds_sum];
			}
			if( $mrow[mb_deposit_point]<=$ds_sum){
					//아무것도 하지 않는다.
			}
			else if( $mrow[mb_deposit_point]>=$benefit + $ds_sum){ //수당 합과 지금 수당의 합이 디파짓보다 작으면 수당 업데이트
				$temp_sql1 .= " ,benefit			=  ".$benefit;
				$temp_sql1 .= " ,benefit_usd		=  '".($benefit)."'";
				sql_query($temp_sql1);
				echo $temp_sql1.'********3********<br>';
				$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit.",3) where mb_id = '".$mbid."';";
				sql_query($balance_up);
			}else {
				
					$over_sd = $benefit + $ds_sum - $mrow[mb_deposit_point];
					if($benefit - $over_sd>0){
					$temp_sql1 .= " ,benefit			=  '".($benefit - $over_sd)."'";
					$temp_sql1 .= " ,benefit_usd		=  '".($benefit - $over_sd)."'";
					sql_query($temp_sql1);
					echo $temp_sql1.'********3********<br>';
					$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit - $over_sd.",3) 	where mb_id = '".$mbid."';";
					sql_query($balance_up);
				}
			}
		}

	}
}
?>