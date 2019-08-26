<?php

$sub_menu = "600200";
include_once('./_common.php');
auth_check($auth[$sub_menu], 'r');
/*
1. 전전주 대상자 : 본인을 추천한 회원들 EOS 10개 이상 디파짓
2. 전주 대상 수당 : 전주 대상자별 1주일간 받은 데일리 수당
3. 팀구성은 대상자 100명씩 1팀
4. 팀별로 데일리 수당 합의 20%를 1->2->3->4 ...
5. 팀별로 데일리 수당 합의 20%를 ...4->3->2->1
6. 지급 제한 조건 : 디파짓 500%이상 수당을 지급 받았으면 지급하지 않는다.
*/
$clear_temp = "truncate table eos_team_bonus_temp";
sql_query($clear_temp);

$sql_rate = sql_fetch("select rate from eos_team_paid");
$eos_team_rate = $sql_rate[rate]/100;
//1. 대상자 찾기
$liststart_day = date('Y-m-d', time()-3600*24*15);//2주전
$listend_day = date('Y-m-d', time()-3600*24*9);//2주전
$sdstart_day = date('Y-m-d', time()-3600*24*8);//1주전
$sdsend_day = date('Y-m-d', time()-3600*24*2);//1주전

$liststart_day = '2019-06-27';
$listend_day = '2019-07-07';
$sdstart_day = '2019-07-01';
$sdsend_day = '2019-07-07';

$get_mem_list = "select mb_id,mb_no from soodang_pay where 1=1 and day>='{$liststart_day}' and day<='{$listend_day}' and allowance_name='Role Down Recom' group by mb_id order by mb_no ";
echo $get_mem_list."<br>";
$mem_list = sql_query($get_mem_list);
while($row = sql_fetch_array($mem_list)){
	sql_query("insert eos_team_bonus_temp set mb_id = '".$row[mb_id]."'");
	sql_query("insert eos_team_bonus_hist set mb_id = '".$row[mb_id]."', soodang_day='".date('Y-m-d', time())."'");

	//2. 전주 대상 수당
	$sd_sum = sql_fetch($get_ds_sum = "select mb_id, sum(benefit) as sd_sum from soodang_pay where 1=1 and day>='{$sdstart_day}' and day<='{$sdsend_day}' and allowance_name='daily payout' and mb_id='".$row[mb_id]."'");
	sql_query("update eos_team_bonus_temp set 	mb_weekbonus = $sd_sum[sd_sum] where mb_id='".$row[mb_id]."'");
	//echo "update eos_team_bonus_temp set 	mb_weekbonus = $sd_sum[sd_sum]";
}

$team_sum = array();
$team_amount = array();
$team_re_amount = array();

//팀별 수당 합을 구한다.
//해당 기간
//1~100 / 101~200 / 201~300 ............................
//$start_day = $to_date;
//$end_day = $fr_date;*/
/*fr_date:from_date, to_date: to_date benefit_list에서 받는다.*/
/*
$start_day = date('Y-m-d', time());
$end_day = date('Y-m-d', time());*/

//3팀구성 total구하고 100명씩 나눈다.
$total_sql = sql_fetch("select idx from eos_team_bonus_temp order by idx desc limit 1");
$total_num = $total_sql['idx'];
$t_group = round($total_num/100);
if($total_num%100 > 0) $t_group=$t_group+1;

//팀별 수당 합 체크
for($i=1; $i <= $t_group; $i++){
	$end_no = $i * 100;
	$start_no = $end_no - 99;
	$team_conut = sql_fetch("select count(mb_id) as team_count from eos_team_bonus_temp where idx >= ".$start_no." and idx <= ".$end_no);
	$team_sum[$i] = $team_conut[team_count];
	echo "select sum(mb_weekbonus) as team_amount from eos_team_bonus_temp where idx>=".$start_no." and idx<= ".$end_no."<br>";
	$team_amts = sql_fetch( "select round(sum(mb_weekbonus),2) as team_amount from eos_team_bonus_temp where idx>=".$start_no." and idx<= ".$end_no);
	$team_re_amount[$i] = $team_amts[team_amount];
	$team_amount[$i] = $team_amts[team_amount]*$eos_team_rate;

}
//팀별로 데일리 수당 합의 20%를 1->2->3->4 ...
for($i=1;$i<$t_group;$i++){
	echo 'team_sum  :::: '.$team_sum[$i]." team_amts :  ".$team_amount[$i].'<br>';
	$one_benefit = round($team_amount[$i]/ $team_sum[$i+1],10);
	echo "amount of one person benfit of group ".$one_benefit."<br>";
	$start_mno = ($i+1)*100-99;
	$end_mno = ($i+1)*100;
	for($j=$start_mno; $j<=$end_mno; $j++){
		$minfo = sql_fetch("select m.mb_id, m.mb_no, m.mb_name, m.mb_recommend, m.mb_level, ts.mb_weekbonus from eos_team_bonus_temp ts left join g5_member m on ts.mb_id = m.mb_id where ts.idx = ".$j);
		$one_benefit = round($team_amount[$i] * ($minfo[mb_weekbonus]/$team_re_amount[$i+1]) ,8);
		if($minfo[mb_id]==null)continue;
		echo $minfo[mb_id]."<br>";
		//500% 제한 체크
		$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from soodang_pay where 1=1 and mb_id='".$minfo[mb_id]."'");
		/*if($soodang_sum[eb_sum]>=$m_row[mb_deposit_point]*5){//보유 EOS의 5배를 수당으로 받았을 시에 이 회원 레벨은 0으로 바뀌고 매출도 사라 진다.
			//$reset_mem = "update g5_member set mb_level=0, mb_deposit_point=0 where mb_id ='".$mrow[mb_id]."'";
			//sql_query($reset_mem);
		}
		else{*/
			save_benefit($sdsend_day, $minfo[mb_id], $minfo[mb_no], $minfo[mb_name], $minfo[mb_recommend],  "Team Benefit",  $one_benefit,  "team bonus",  "team bonus", $minfo[mb_level] , $i+1);
		//}
	}
}
//팀별로 데일리 수당 합의 20%를 ...4->3->2->1
for($r=$t_group;$r>1;$r--){
	echo 'team_sum '.$r.' : '.$team_sum[$r]." team_amts :  ".$team_amount[$r-1].'<br>';
	$one_benefit = round($team_amount[$r]/ $team_sum[$r-1],3);
	echo "amount of one person benfit of group ".$one_benefit."<br>";
	$start_mno = ($r-1)*100-99;
	$end_mno = ($r-1)*100;
	for($j=$start_mno;$j<=$end_mno;$j++){
		$minfo = sql_fetch("select m.mb_id, m.mb_no, m.mb_name, m.mb_recommend, m.mb_level, ts.mb_weekbonus from eos_team_bonus_temp ts left join g5_member m on ts.mb_id = m.mb_id where ts.idx = ".$j);
		$one_benefit = round($team_amount[$r] * ($minfo[mb_weekbonus]/$team_re_amount[$r-1]) ,8);
		if($minfo[mb_id]==null)continue;
		echo $minfo[mb_id]."<br>";
		$soodang_sum  = sql_fetch("select sum(benefit) as eb_sum from soodang_pay where 1=1 and mb_id='".$minfo[mb_id]."'");
		/*if($soodang_sum[eb_sum]>=$m_row[mb_deposit_point]*5){//보유 EOS의 5배를 수당으로 받았을 시에 이 회원 레벨은 0으로 바뀌고 매출도 사라 진다.

		}
		else{*/
			save_benefit($sdsend_day,$minfo[mb_id], $minfo[mb_no],$minfo[mb_name],$minfo[mb_recommend],"Team Benefit", $one_benefit, "team bonus","team bonus" , $minfo[mb_level], $r-1);
//		}
	}
}
function save_benefit($day,  $mbid,  $mbno,  $mbname,  $recom,  $allowance_name,  $benefit,  $rec_adm,  $rec, $mb_level,$team){
	$balance_up = "update g5_member set mb_balance = round(mb_balance+ ".$benefit.",3)  where mb_id = '".$mbid."';";
	sql_query($balance_up);
	$temp_sql1 = " insert soodang_pay set day='".$day."'";
	$temp_sql1 .= " ,mb_no			= ".$mbno;
	$temp_sql1 .= " ,mb_id			= '".$mbid."'";
	$temp_sql1 .= " ,mb_level      = ".$mb_level;
	$temp_sql1 .= " ,mb_name	= '".$mbname."'";
	$temp_sql1 .= " ,mb_recommend	= '".$recom."'";
	$temp_sql1 .= " ,allowance_name	= '".$allowance_name."'";
	$temp_sql1 .= " ,benefit		=  ".$benefit;
	$temp_sql1 .= " ,rec			= '".$rec."'";
	$temp_sql1 .= ", od_id		= '".$team."'";
	$temp_sql1 .= " ,rec_adm	= '".$rec_adm."'";
	sql_query($temp_sql1);
	echo	$temp_sql1;
}

?>