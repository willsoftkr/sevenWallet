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
        $mbid = $mb_row['mb_id'];
        $member_update = my_bchild_hap($mbid); 
        $mblevel = $mb_row['mb_level'];

        print_r(" =  ".$mbid." 총매출 : <span class='blue'>".number_format($member_update[0])."</span><br>");
       
        $line_sales = $member_update[2];
        $line_levels = $member_update[3];

        /* 라인3개가 20,000 이상인지 확인 */
        if( count($line_sales) >= 3) {
            $line_test = "1";
            //echo "1단계";

             /* 하부 총 합계 100,000 이상 */
            if($member_update[0] >= 100000){
                //echo "2단계";
                
                if($val == 1){
                    echo "<br>V1 승급 대상 : <span class='red'>".$mbid."</span>";
                    
                    $sql3 = " update g5_member set mb_level=1";
                    $sql3 .= " , rank_day='".$to_date."'";
                    $sql3 .= " where mb_id='".$mbid."'";
                    sql_query($sql3);

                    $sql3 = " insert rank set mb_level=1";
                    $sql3 .= " , rank_day='".$to_date."'";
                    $sql3 .= " , rank=1";
                    $sql3 .= " , old_level='".$mblevel."'";
                    $sql3 .= " , rank_note='V1 승급함, 등급계산 에서 승급됨'";
                    $sql3 .= " where mb_id='".$mbid."'";
                    sql_query($sql3);
                }else{
                    //echo "3단계";
                    if($val > $mblevel){

                        $line_level = $val-1;

                        /* 라인3개이상에 등급 존재 여부 확인 */
                        foreach ( $line_levels as $key => $value ){
                            if ($value == ($line_level-1)) 
                            { 
                                $level_confirm_count++; 
                            } 
                        }

                        if($level_confirm_count > 3){
                            echo "<br>V2~7 승급 대상 : <span class='red'>".$mbid."</span> | 하부 라인 <span class='blue'>V".$line_level."</span> 레벨 ".$level_confirm_count."명 존재" ;

                            $sql3 = " update g5_member set mb_level='$val'";
                            $sql3 .= " , rank_day='".$to_date."'";
                            $sql3 .= " where mb_id='".$mbid."'";
                            sql_query($sql3);

                            $sql4 = " insert rank set mb_level=1";
                            $sql4 .= " , rank_day='".$to_date."'";
                            $sql4 .= " , rank='{$val}'";
                            $sql4 .= " , old_level='".$mblevel."'";
                            $sql4 .= " , rank_note='V{$val} 승급함, 등급계산 에서 승급됨'";
                            $sql4 .= " where mb_id='".$mbid."'";
                            sql_query($sql4);
                            //echo  $sql4;
                        }
                    }

                }
            }
        }
    }
}

function my_bchild_hap($mb_id){
    
	$hap=0;
    $cnt=0;
    $line_confirm = array();
    $line_level = array();
    echo "<br>**<br>".$mb_id."<br>";

    $res= sql_query("select mb_id,mb_level from g5_member where mb_recommend='".$mb_id."' order by mb_no"); 
	for ($j=0; $rrr=sql_fetch_array($res); $j++) { 
        $line_hap = self_sales($rrr['mb_id']);

        if($line_hap > 20000){
            array_push($line_confirm,$line_hap);
        }

        array_push($line_level,$rrr['mb_level']);

        $hap+=$line_hap; // 하부매출을 구한다
		$cnt++;
    } 
    echo "▶▶ 하부라인 : ".$cnt;
    return [$hap,$cnt,$line_confirm,$line_level];

    /*배열초기화*/
    unset($line_confirm);
    unset($line_level);
} 

function self_sales($recom){
    $res= sql_fetch("select sum(upstair)as hap from g5_shop_order as o where o.mb_id='".$recom."'");    
    if(!$res['hap']) $res['hap'] = 0;
         echo $recom." Sales : ". number_format($res['hap'])." | ";
	return $res['hap'];    
} 
