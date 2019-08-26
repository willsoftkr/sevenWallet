<?php
include_once('/home/sdevftv/html/common.php');//테스트 서버용 경로


echo $sql = "SELECT mb_id, mb_name, mb_hp, mb_level, mb_recommend, mb_brecommend, sales_day, mb_my_sales, habu_day_sales FROM g5_member WHERE habu_day_sales>0 and mb_level>=2 and date_format(sales_day,'%Y-%m-%d')='2018-06-28'";

$ret = sql_query($sql);
$list = sql_fetch_array($ret);

while($list = sql_fetch_array($ret)){

	echo $list['mb_id']." ".$list['habu_day_sales']."\n";

}
?>
