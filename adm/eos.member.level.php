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


echo "회원 레벨 승급 :: ".$to_date." <br>";

update_level('1');
update_level('2');
update_level('3');
update_level('4');
update_level('5');
update_level('6');
update_level('7');

update_grade();

function update_grade(){
    echo "<br><span class='title'> 등급 업데이트 - GRADE ====================================== </span><br>";

    $mem_sql = "select mb_id, grade, mb_recommend from g5_member order by mb_no";
    $mem_list = sql_query($mem_sql);

    while($m_row = sql_fetch_array($mem_list)){

        $grade = $m_row['grade'];

        echo "<br>**<br><strong>".$m_row['mb_id']."  |  현재등급 ".$m_row['grade']."</strong><br>";

        //회원의 직 추천인 수를 구한다. 
        $recom_cont = sql_fetch( "select count(mb_id) as r_count from g5_member where  mb_recommend = '".$m_row['mb_id']."' AND grade >= 3 ");
        $recom_cnt=$recom_cont['r_count'];

        if($grade == 3 &&  $recom_cnt >= 3){
            echo "<br> <span class='blue'>▶ 직추천 그린 등급 이상 : ".$recom_cnt."명 </span>";
            echo "<br> <span class='red'>▶▶ GREEN2 등급 업데이트 대상</span>";
            $update_grade_sql = "update g5_member set grade = 4 where mb_id = '{$m_row['mb_id']}'";
            sql_query($update_grade_sql);
        }
    
    }
}

function update_level($val){
    
    if( $val == '1' ){
        echo "<br><span class='title'>승급진행 - V1 ====================================== </span><br>";
        $sql_mb = "select mb_level, mb_id from g5_member where mb_level < '{$val}' order by mb_no asc";
    }else{
        $targetval = ($val-1);
        echo "<br><br><span class='title'>승급진행 - V".$val."  ====================================== </span> <br>";
        $sql_mb = "select mb_level, mb_id from g5_member where mb_level = ' $targetval ' order by mb_no asc";
    }
    
    $member_query = sql_query($sql_mb);


    while($mb_row = sql_fetch_array( $member_query)){
        global $to_date;
        $mbid = $mb_row['mb_id'];
        $member_update = my_bchild_hap($mbid); 
        $mblevel = $mb_row['mb_level'];

    
        $line_sales = $member_update[2];
        $line_levels = $member_update[3];

        
        
        print_r(" =  <strong>".$mbid."</strong> 총매출 : <span class='blue'>".number_format($member_update[0])."</span><br>");
        echo "<br>▶ 하부라인 : <span class='blue'>".$member_update[1]."</span> | 라인별 매출2만달성 :";
        print_r($line_sales);

        echo "<br>▶▶ 회원등급 : ";
        print_r($line_levels);
        
       
        /* 라인3개가 20,000 이상인지 확인 */
        if( count($line_sales) >= 3) {
            
            echo "1단계";

             /* 하부 총 합계 100,000 이상 */
            if($member_update[0] >= 100000){
                echo "2단계";
                
                if($val == 1){
                    echo "<br> <span class='red'> ▶▶ V1 승급 대상 : ".$mbid."</span>";
                    
                    $sql3 = " update g5_member set mb_level=1";
                    $sql3 .= " , rank_note='".$to_date."'";
                    $sql3 .= " where mb_id='".$mbid."'";
                    sql_query($sql3);

                    $sql4 = " insert rank set ";
                    $sql4 .= " mb_id='".$mbid."'";
                    $sql4 .= " , rank_day='".$to_date."'";
                    $sql4 .= " , rank=1";
                    $sql4 .= " , old_level='".$mblevel."'";
                    $sql4 .= " , rank_note='V1 승급함, 등급계산 에서 승급됨'";
                    sql_query($sql4);

                }else{
                    echo "<br>3단계";
                    if($val > $mblevel){
                       
                        $line_level = $val-1;
                            
                        /* 라인3개이상에 등급 존재 여부 확인 */
                        foreach ( $line_levels as $key => $value ){

                            /*echo "<br>";print_r($value); */
                            $level_count = substr_count($value,$line_level);
                            /*echo "<br>";print_r($level_count); */

                            if ($level_count > 0) 
                            { 
                                $level_confirm_count++; 
                            } 
                        }
                        echo "<br>4단계 confirm_count === " . $level_confirm_count;
                        //echo "confirm_count === " . $level_confirm_count;
                        
                        if($level_confirm_count >= 3){
                            echo "<br>V2~7 승급 대상 : <span class='red'>".$mbid."</span> | 하부 라인 <span class='blue'>V".$line_level."</span> 레벨 ".$level_confirm_count."명 존재" ;

                            $sql5 = " update g5_member set mb_level='$val'";
                            $sql5 .= " , rank_note='".$to_date."'";
                            $sql5 .= " where mb_id='".$mbid."'";
                            sql_query($sql5);

                            $sql6 = " insert rank set ";
                            $sql6 .= " mb_id='".$mbid."'";
                            $sql6 .= " , rank_day='".$to_date."'";
                            $sql6 .= " , rank='{$val}'";
                            $sql6 .= " , old_level='".$mblevel."'";
                            $sql6 .= " , rank_note='V{$val} 승급함, 등급계산 에서 승급됨'";
                            sql_query($sql6);
                            //echo  $sql4;
                        }

                        unset($level_confirm_count);
                    }

                }
            }
        }
    }
}



function my_bchild_hap($mb_id){
    global $hap_sale;
    //global $line_level;
   

    $cnt=0;
    $line_confirm = array();
    $line_levels = array();
    $hap_sale = 0; 

    echo "<br><br>**<br><strong>".$mb_id."</strong><br>";

    $res= sql_query("select mb_id,mb_level from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 

	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
       
        $line_level = self_habu_level($rrr['mb_id']);
        $line_level_total = $line_level.$rrr['mb_level'];
        array_push($line_levels, $line_level_total);


        $self_hap = self_sales($rrr['mb_id']);
        $line_hap = self_habu($rrr['mb_id']);
        $line_total = $line_hap + $self_hap; // 하부매출 + 직하부매출
        $hap+=$line_hap + $self_hap; // 하부매출 + 직하부매출 누계

        if($line_total >= 20000){
            array_push($line_confirm,$line_total);
        }

        $cnt++;
    } 
    return [$hap,$cnt,$line_confirm,$line_levels];
    
    /*배열초기화*/
    unset($line_confirm);
    unset($line_level);
} 





function self_habu($recom){
   
    $hap2=0;
    $res= sql_query("select mb_id,mb_level from g5_member where mb_recommend='".$recom."' order by mb_no"); 

	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 

		$hap2+=self_sales($rrr['mb_id']); // 하부매출을 구한다
        $hap2 = $hap2 + self_habu($rrr['mb_id']);
    } 	
	return $hap2;
} 


function self_habu_level($recom){
    $line_habu_level =array();
    
    $res= sql_query("select mb_id, mb_level from g5_member where mb_recommend='".$recom."' order by mb_no"); 

	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
        
        $levels .= $rrr['mb_level'].self_habu_level($rrr['mb_id']);
    } 	
	return $levels;
} 



function self_sales($recom){
    global $hap_sale,$to_date;
    $res= sql_fetch("select sum(upstair)as hap from g5_shop_order as o where o.mb_id='{$recom}' and date(o.od_time) <= '{$to_date}' ");    

    if(!$res['hap']) $res['hap'] = 0;{
         echo $recom." Sales : ". number_format($res['hap'])." | ";
    }
    return $res['hap'];
} 
