<?

include_once('./_common.php');
/*
$_POST['use']
$_POST['nw_subject']
$_POST['nw_contents_html']
*/

//print_r($_POST);

if(!$_POST['useFn']){
	$_POST['useFn'] = 'N';
}


$sql_common = " 
                nw_use = '{$_POST['useFn']}',
				nw_subject = '{$_POST['nw_subject']}',
                nw_contents_html = '{$_POST['nw_content']}' ";

$sql = " update maintenance set $sql_common ";

//print_r("<br>".$sql);
sql_query($sql);


alert('등록되었습니다.',0);
goto_url("./admin.sub.maintenance.php");

?>