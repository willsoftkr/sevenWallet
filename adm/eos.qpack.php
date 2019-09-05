<?php

include_once ('./_common.php');
?>

<style>
    body{font-size:14px;line-height:18px;letter-spacing:0px;}
    .red{color:red;font-weight:600;}
    .blue{color:blue;font-weight:600;}
    .title{font-weight:800;color:red;}
</style>

<?
if ($to_date){
	$to_date       = $to_date;
}else{
	$to_date    = date('Y-m-d');
}


$rate_array = array(
    array("11","23","0.153"),
    array("24","36","0.192"),
    array("37","49","0.153"),
    array("50","62","0.115"),
    array("63","75","0.076"),
    array("76","88","0.057"),
    array("89","101","0.057")
);

echo "<strong>Q팩 수당 현황</strong><br>";

foreach($rate_array as $value){
    echo "<br>".$value[0]." ~ ".$value[1]." : ".$value[2]."%";
}
echo "</br></br>";

/*
echo "<strong>Q팩 보유 멤버 현황</strong><br>";
$pre_sql = "select count(*) from g5_memeber where it_pool2 != 0 "
*/


echo "<br><strong> Q팩 직급 수당지급 :: ".$to_date."</strong> ";


/* 수당비율 가져오기 */
/*
$rate = [];
$recom_rate_sql = "SELECT * from eos_daily_paid order by idx";
$result = sql_query($recom_rate_sql);
while( $row = sql_fetch_array($result)){
	array_push($rate,$row);
}


$cond = array(array('recom_grade'=>'','recom_history'=>'','	recom_per'=>''));
$recom_cond = "SELECT * from eos_daily_immediate  order by idx ";

$list = sql_query($recom_cond);
for ($i=0; $row=sql_fetch_array($list); $i++) {   
	$cond[$i]['recom_grade']=$row['recom_grade'];
	$cond[$i]['recom_history']=$row['recom_history'];
	$cond[$i]['recom_per']=$row['recom_per'];
}
*/


$v7_cost = number_format(get_coin_cost('v7'),2);

//회원 리스트를 읽어 온다.

if($_GET['mb_id']){
    $test_id = $_GET['mb_id'];
    $mem_sql = "select * from g5_member where mb_id ='{$test_id}' order by mb_no";
}else{
    
    $test_id = 'coolrunning';
    $mem_sql = "select * from g5_member order by mb_no";
}

$mem_list = sql_query($mem_sql);

$result_hap;
while($m_row = sql_fetch_array($mem_list)){
	echo '<br><br><br><br>계산 대상 <strong>'.$m_row['mb_id']."</strong>";

    $result_hap = 0;
    
	$mb_level = $m_row['grade'];

    //$benefit_rate = $rate[ $m_row['grade'] ]; //회원등급별 수당률
    /*
    $mb_qpack = $m_row['it_pool2']; //Q팩 보유
    $mb_qpack_expire_date = $m_row['it_pool2_profit']; // Q팩유효기간
    
    if($mb_qpack && $mb_qpack_expire_date > $to_date){
        print_r(" _________  <span class='blue'>Q Pack 보유 : ".$mb_qpack." | 기간 :".$mb_qpack_expire_date."</span><br>" );
        $qpak_num = substr($mb_qpack,1,1);
    }else{
        $qpak_num = 0;
	}
	*/

	$cart_sql = "select * from g5_shop_cart as A left join g5_shop_item as B on A.it_name = B.it_name where A.mb_id ='{$m_row['mb_id']}' and date(A.ct_time) <= '{$to_date}' and date(A.ct_select_time) >= '{$to_date}' and A.it_sc_type = '20'  order by A.ct_time desc limit 0,1";
	$cart_result = sql_fetch($cart_sql);

	echo  $cart_sql;
	$qpak_num = 0;

	if($cart_result){
		$mb_qpack = $cart_result['it_name'];
		$mb_qpack_expire_date = $cart_result['ct_select_time'];
		
		print_r(" _________  <span class='blue'>Q Pack 보유 : ".$mb_qpack." | 기간 :".$mb_qpack_expire_date."</span><br>" );
		$qpak_num = substr($mb_qpack,1,1);
	}

	//회원의 직 추천인 수를 구한다. 
	$recom_cont = sql_fetch( "select count(mb_id) as r_count from g5_member where  mb_recommend = '".$m_row['mb_id']."' and mb_deposit_point >= 500 ");
	$recom_cnt=$recom_cont['r_count'];
    
    

	habu_sales_calc ($m_row['mb_id'], 0, $recom_cnt, $cond, $qpak_num);

    //echo $m_row['mb_id'].' of result_hap : '.$result_hap."<br>";
    //echo "<br><br>".$m_row['mb_id'].' -  직추천인수 ::'.$recom_cnt."<br>";


	$allowance_name = "Infinite matching";
	$rec_adm  = "Infinite matching";
	$rec = "Infinite matching";

    

	if($result_hap>0) {
		$result_hap = number_format($result_hap, 2);
		$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from soodang_pay where 1=1 and mb_id='".$mrow['mb_id']."'");
        
		save_benefit($to_date, $m_row['mb_id'], $m_row['mb_no'], $m_row['mbname'], $recom, $allowance_name,  $result_hap, $rec_adm, $rec, $mb_level,$m_row['mb_v7_account']);
	}
}


function habu_sales_calc($recom, $deep, $count, $cond, $qpak_num){
    global $result_hap, $fr_date, $to_date,$qpak_num;
        ;
    
	if($deep>=$count){
		//echo "총 보유 산하 대수 : ".$deep.' 단계';
		//return;
	}
    //$percent_hist = $benefit_rate['recom_2'];
    
	$deep++; // 대수	
    


	//echo "select * from g5_member where mb_".$gubun."recommend='".$recom."' "."<br>";
    $res= sql_query("select * from g5_member where mb_recommend='".$recom."' ");
    for ($j=0; $rrr=sql_fetch_array($res); $j++) { 		
       
        /* 수당적용*/
		if($deep > 10 && $deep < 24 &&$qpak_num > 0){
		    $percent_hist = 0.153;
        }else if($deep > 23 && $deep < 37 && $qpak_num > 1){
		    $percent_hist = 0.192;
        }else if($deep > 36 && $deep < 48 && $qpak_num > 2){
		    $percent_hist = 0.153;
        }else if($deep > 49 && $deep < 61 && $qpak_num > 3){
		    $percent_hist = 0.115;
        }else if($deep > 62 && $deep < 74 && $qpak_num > 4){
		    $percent_hist = 0.076;
        }else if($deep > 75 && $deep < 87 && $qpak_num > 5){
		    $percent_hist = 0.057;
        }else if($deep > 88 && $deep < 102 && $qpak_num > 6){
		    $percent_hist = 0.057;
        }else{
            $percent_hist = 0;
        }

        
        if($deep > 5 && $deep < 7 && $qpak_num > 3){
            $percent_hist = 0.011;
        }else if($deep > 6 && $deep < 8 && $qpak_num > 5){
            $percent_hist = 0.022;
        }
        

        $recom=$rrr['mb_id'];
       
			echo "<br>".$recom;
           

        $daily_paid = sql_fetch("select benefit from soodang_pay where allowance_name = 'daily payout' and mb_id='".$recom."'");
        $daily_benefit = $daily_paid['benefit']*$percent_hist;
        echo '  || 수당계산 - deep : '.$deep.'  | daily_benefit = : '.$daily_benefit;

			if($daily_benefit != 0){
				echo ' <span class="blue"> | 발생수당 '.$percent_hist.'   | Q PACK kind: Q'. $qpak_num."</span>"  ;
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
	return array($daily_benefit, $mon, $today);
}


function save_benefit($to_date, $mbid, $mbno,$mbname, $recom, $allowance_name, $benefit, $rec_adm, $rec, $mb_level,$v7_account ){
	global $v7_cost;
	$benefit = number_format($benefit, 2);
	echo "<br>";
	$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit.", 2), mb_v7_account = round(mb_v7_account+ ".$benefit."/".$v7_cost.",3)   where mb_id = '".$mbid."';";
	sql_query($balance_up);
	print_r($balance_up);

	$temp_sql1 = " insert soodang_pay set day='".$to_date."'";
	$temp_sql1 .= " ,mb_id			= '".$mbid."'";
	$temp_sql1 .= " ,mb_no			= ".$mbno;
	$temp_sql1 .= " ,mb_level     = ".$mb_level;
	$temp_sql1 .= " ,mb_name	= '".$mbname."'";
	$temp_sql1 .= " ,mb_recommend	= '".$recom."'";
	$temp_sql1 .= " ,source	= '".$v7_account."'";
	$temp_sql1 .= " ,allowance_name	= '".$allowance_name."'";
	$temp_sql1 .= " ,benefit		=  ".$benefit;	
	$temp_sql1 .= " ,rec			= '".$rec."'";
	$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";
	$temp_sql1 .= " ,datetime		= '".date("Y-m-d H:i:s")."'";

	sql_query($temp_sql1);
	echo "<br>".$temp_sql1;
}