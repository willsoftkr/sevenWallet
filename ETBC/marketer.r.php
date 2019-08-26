<?php
include_once('./_common.php');

$bo_table = 'marketer';
$ss_name = 'ss_view_'.$bo_table.'_'.$_POST['idx'];
$wr_id = $_POST['idx'];
set_session($ss_name, TRUE);

$sql = "select * from marketer A ";
$sql .= " WHERE A.writer = '".$member['mb_id']."' and idx=".$_POST['idx'];
$record = sql_fetch($sql);

$obj = new stdClass();
$obj->status = $record['status'];
$obj->content = $record['content'];
$obj->create_dt = $record['create_dt'];
$obj->update_dt = $record['update_dt'];
$obj->writing = conv_content($record['content'],1);
$obj->comment = conv_content($record['comment'],1);
$obj->commenter = $record['commenter'];
$obj->comment_dt = $record['comment_dt'];

$sql = " select wr_id, bf_source, bf_no from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id'  ";
$file_list = sql_query($sql,true);
$obj->file_list = array();
for ($i=0; $row=sql_fetch_array($file_list); $i++) {
    $obj2 = new stdClass();
    $obj2->filename = $row['bf_source'];
    $obj2->wr_id = $row['wr_id'];
    $obj2->bf_no = $row['bf_no'];
    array_push($obj->file_list,$obj2);
    // $obj->file_list[$i] = $obj2
}
// $obj->file_list = $file['bf_source'];

echo json_encode($obj);

?>

