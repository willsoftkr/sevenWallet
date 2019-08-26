<?

include_once('./_common.php');


if(!$_POST['nw_with']){
	$nw_with = 'N';
}else{
	$nw_with = $_POST['nw_with'];
}


if(!$_POST['nw_upstair']){
	$nw_upstair = 'N';
}else{
	$nw_upstair = $_POST['nw_upstair'];
}

$sql_common = " 
                nw_with = '{$nw_with}',
				nw_upstair = '{$nw_upstair}'";

$sql = "update maintenance set $sql_common ";

//print_r("<br>".$sql);
sql_query($sql);


alert('등록되었습니다.',0);
goto_url("./admin.sub.maintenance.php");

?>