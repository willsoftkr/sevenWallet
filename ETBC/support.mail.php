<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

$idx = $argv[1];

$comment = sql_fetch("select content, pid from ticket_child where idx = {$idx} ");

$sth = sql_fetch("select mb_email from g5_member where mb_no = (select mb_no from ticket where idx = {$comment[pid]} )");

// 내용 작성
$sql = "
select if(a.mb_no=1,'FIJI Support', mb_id) as mb_id, content,
	if(DATEDIFF(now(),create_date)>0, DATE_FORMAT(create_date, '%b %d'), TIME_FORMAT(create_date, '%H:%i %p')) as create_date
	from ticket_child a
	inner join g5_member b on a.mb_no = b.mb_no
where pid = {$comment[pid]} order by idx desc";
$con = sql_query($sql);
$rows = array();
$content = "";
while($rec = mysqli_fetch_assoc($con)) {
	
	$content .= $rec['mb_id']." (".$rec['create_date'].") : ".conv_content($rec['content'],2)."<br>";
}
$content.="<br><br>- FIJI Support Center";
// $content = "FIJI Support : ".conv_content($comment['content'],2)."<br>";

mailer('FIJImining', 'noreply@FIJImining.net', "{$sth['mb_email']}", 'Support Ticket', "<br>"."{$content}", 1);

?>
