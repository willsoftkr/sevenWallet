<?include_once('/home/sdevftv/html/common.php');
$sql = 
"select * from g5_shop_order where substring(od_receipt_time,1,10)='$to_date' ";
$order_list = sql_query($sql);
//아이디를 갖고 와서 수당 테이블의 날짜와 비교 하여 있는지 없는지 파악 한다.
for($i, $fetchA = sql_fetch_array($order_list); $i++){
	
	$check_dirB = "select * from g5_soodang_pay where rec_adm like %({$fetchA['mb_id']})%";
	if(sql_fetch($check_dirB)){
		echo $fetchA['mb_id']."회원 direct 수당 발생"."<br>";
	}
	?>