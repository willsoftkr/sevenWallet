<?php

$sub_menu = "600600";
include_once ('../common.php');

$rate = [];
$recom_rate_sql = "SELECT * from eos_daily_paid order by idx";
$result = sql_query($recom_rate_sql);
while( $row = sql_fetch_array($result)){
	array_push($rate,$row);
}

$gradetxt = ['black', 'red','yellow','green', 'green2'];
$grade_count = 0;

$cond = array(array('recom_grade'=>'','recom_history'=>'','	recom_per'=>''));
$recom_cond = "SELECT * from eos_daily_immediate  order by idx ";

$list = sql_query($recom_cond);
for ($i=0; $row=sql_fetch_array($list); $i++) {   
	$cond[$i]['recom_grade']=$row['recom_grade'];
	$cond[$i]['recom_history']=$row['recom_history'];
	$cond[$i]['recom_per']=$row['recom_per'];
}


//회원 리스트를 읽어 온다.

if ($to_date){
	$to_date       = $to_date;
}else{
	$to_date    = date('Y-m-d');
}

$mem_sql = "select * from g5_member order by mb_no";
$mem_list = sql_query($mem_sql);
$result_hap;


while($m_row = sql_fetch_array($mem_list)){
	$grade = $m_row['grade'];

	echo '<br><br><br><br>계산 대상 '.$m_row[mb_id]." | ". $gradetxt[$grade]."<br>";

	$result_hap = 0;
	
	$benefit_rate = $rate[ $m_row['grade'] ]; //회원등급별 수당률


	//회원의 직 추천인 수를 구한다. 
	$recom_cont = sql_fetch( "select count(mb_id) as r_count from g5_member where  mb_recommend = '".$m_row['mb_id']."' and mb_deposit_point >= 500 ");
	$recom_cnt=$recom_cont['r_count'];
	

	habu_sales_calc ($m_row['mb_id'], 0, $recom_cnt, $cond,$benefit_rate);

	//echo $m_row[mb_id].' of result_hap : '.$result_hap."<br>";
	
	echo "<br><span style='color:red;font-weight:600'>직추천 그린등급 카운팅::".$grade_count."</span>";

	if($grade > 2 && $grade_count > 2){
		echo "<br><span style='color:blue;font-weight:600'> [ Green2 등급 업데이트 ]</span>";
		$grade_update = 4;
	}

	$allowance_name = "Role Down Recom";
	$rec_adm  = "Role Down Recom";
	$rec = "Role Down Recom";

	if($result_hap>0) {
		$result_hap = number_format($result_hap, 2);
		$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from soodang_pay where 1=1 and mb_id='".$mrow['mb_id']."'");
	}
	save_benefit($to_date, $m_row[mb_id], $m_row[mb_no], $m_row[mbname], $recom, $allowance_name,  $result_hap, $rec_adm, $rec, $grade, $m_row['mb_v7_account'], $grade_update);
	unset($grade_count);
}

function habu_sales_calc($recom, $deep, $count, $cond,$benefit_rate){
	global $result_hap;
	global $grade_count;
	

	if($deep>=$count){
		//echo $deep.' return'.'<br>';
		return;
	}
	global $fr_date, $to_date;
	//$percent_hist = $benefit_rate['recom_2'];


	$deep++; // 대수	

	//echo "select * from g5_member where mb_".$gubun."recommend='".$recom."' "."<br>";
    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' ");
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 		

		/*
		for ($i=0; $i<count($cond); $i++) {
			
			if( ($cond[$i]['recom_grade']==$count) && ($cond[$i]['recom_history']==$deep) ){
				
			}	
		}
		*/

		if($deep == 1){
			$percent_hist = $benefit_rate['recom_1'];
		}else{
			$percent_hist = $benefit_rate['recom_2'];
		}
		

		$recom=$rrr['mb_id'];  
			echo "<br>day : '".$to_date."'   |  mb_id='".$recom."'";
		
		if($deep == '1'){
			echo "  |  grade =". $rrr['grade'];

			if($rrr['grade'] >= 3)
			{
				$grade_count = $grade_count + 1;
			}
		}

		$daily_paid = sql_fetch("select benefit from soodang_pay where 1=1 and day='".$to_date."' and allowance_name = 'daily payout' and mb_id='".$recom."'");
		$daily_benefit = $daily_paid['benefit']*$percent_hist;
			if($daily_benefit != 0){
				echo '| deep : '.$deep.'  | daily_benefit.. : '.$daily_benefit.'   | percent_hist '.$percent_hist;
			}
		$result_hap+=$daily_benefit;
		
		list($noo, $mon_r, $today_r)=habu_sales_calc($recom, $deep,$count, $cond,$benefit_rate);	 
		
		//echo 'noo.list : '.$noo."<br>";
		//$result_hap+=$noo;
		if( ($noo>0)) {
			if($j==0){
				$rec=$noo;
			}else{
				$rec=$noo;	
			}
			
			//echo $inbnoo.' depth '.$deep."<br>";
			//$result_hap=$rec;
		}		
	} // for j	
	return array($daily_benefit, $mon, $today, $grade_count);
}

function save_benefit($to_date, $mbid, $mbno, $mbname, $recom, $allowance_name, $benefit, $rec_adm, $rec, $grade,$v7_account,$grade_update = 0 ){
	$benefit = number_format($benefit, 2);
	$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit.", 2), mb_v7_account = round(mb_v7_account+ ".$benefit."/0.5,3)   where mb_id = '".$mbid."';";
	
	echo "<br>mb_v7_account".$v7_account;
	//sql_query($balance_up);

	if($grade_update == '4')
	{
		$grade = 4;
	}

	$temp_sql1 = " insert soodang_pay set day='".$to_date."'";
	$temp_sql1 .= " ,mb_id			= '".$mbid."'";
	$temp_sql1 .= " ,mb_no			= ".$mbno;
	$temp_sql1 .= " ,grade     = ".$grade;
	$temp_sql1 .= " ,mb_name	= '".$mbname."'";
	$temp_sql1 .= " ,mb_recommend	= '".$recom."'";
	$temp_sql1 .= " ,source	= '".$recom."'";
	$temp_sql1 .= " ,allowance_name	= '".$allowance_name."'";
	$temp_sql1 .= " ,benefit		=  ".$benefit;	
	$temp_sql1 .= " ,rec			= '".$rec."'";
	$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";

	//sql_query($temp_sql1);
	echo "<br>".$temp_sql1;
}