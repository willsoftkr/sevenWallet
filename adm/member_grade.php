<?php
$sub_menu = "200100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '멤버 등급 수동 갱신';

$sql = "select * from g5_member";
$result = sql_query($sql);

$member_grade0 = 0;
$member_grade1 = 0;
$member_grade2 = 0;
$member_grade3 = 0;

while( $row = sql_fetch_array($result) ){
	
	if($row['mb_deposit_point'] < 500){
		$grade = 0;
		$member_grade0 += 1;
	}else if($row['mb_deposit_point'] > 499 && $row['mb_deposit_point'] < 3000){
		$grade = 1;
		$member_grade1 += 1;
	}else if($row['mb_deposit_point'] > 2999 && $row['mb_deposit_point'] < 10000){
		$grade = 2;
		$member_grade2 += 1;
	}else{
		$grade = 3;
		$member_grade3 += 1;
	}

	$grade_sql = "update g5_member set grade = '".$grade."' where mb_id != 'coolrunning' and mb_no ='".$row['mb_no']."'";
	//echo $row['mb_deposit_point']." / ".$grade_sql."<br>";
	sql_query($grade_sql);
	
}
alert("Black : ".$member_grade0." 명 <br> Red : ".$member_grade1." 명 <br> Yellow : ".$member_grade2." 명 <br> Green : ".$member_grade3." 명 <br> 의 회원 등급이 변경되었습니다." );
goto_url('./member_list.php?'.$qstr);
?>

<?php
include_once('./admin.tail.php');
?>
