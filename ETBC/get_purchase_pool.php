<?php
include_once('/home/sdevftv/html/common.php');
$mb = "select mb_id, it_pool1, it_pool2, it_pool3, it_pool4, it_gpu from g5_member order by mb_id ";

$mb_rst = sql_query($mb);

while($mb_row = sql_fetch_array($mb_rst)){

$id = $mb_row['mb_id'];
$pool1 = $mb_row['it_pool1'];
$pool2 = $mb_row['it_pool2'];
$pool3 = $mb_row['it_pool3'];
$pool4 = $mb_row['it_pool4'];
$poolg = $mb_row['it_gpu'];

$q1 = "SELECT count(od.mb_id) as p4cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1  AND od.od_status = '입금' AND od.od_id = ct.od_id and ct.it_id = '1527096030' 
AND substring( od.od_receipt_time, 1, 10 ) >= '2018-07-25' and od.mb_id='{$id}'";
$r1  = sql_fetch($q1);

$p4=$pool4 - $r1['p4cnt'];

$q2 = "SELECT count(od.mb_id) as p3cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1  AND od.od_status = '입금' AND od.od_id = ct.od_id and ct.it_id = '1527096037'
AND substring( od.od_receipt_time, 1, 10 ) >= '2018-07-25' AND od.mb_id='{$id}'";
$r2  = sql_fetch($q2);
$p3=$pool3 - $r2['p3cnt'];

$q3 = "SELECT count(od.mb_id) as p2cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status = '입금' AND od.od_id = ct.od_id and ct.it_id = '1527096041'
AND substring( od.od_receipt_time, 1, 10 ) >= '2018-07-25'  and od.mb_id='{$id}'";
$r3  = sql_fetch($q3);
$p2=$pool2 - $r3['p2cnt'];

$q4 = "SELECT count(od.mb_id) as p1cnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1 AND od.od_status = '입금' AND od.od_id = ct.od_id and ct.it_id = '1527096045'
AND substring( od.od_receipt_time, 1, 10 ) >= '2018-07-25'  and od.mb_id='{$id}'";
$r4  = sql_fetch($q4);
$p1=$pool1 - $r4['p1cnt'];


$qg = "SELECT count(od.mb_id) as pgcnt FROM `g5_shop_order` AS od, g5_shop_cart AS ct WHERE 1  AND od.od_status = '입금' AND od.od_id = ct.od_id and ct.it_id = '1515148167'
AND substring( od.od_receipt_time, 1, 10 ) >= '2018-07-25'  and od.mb_id='{$id}'";
$rG  = sql_fetch($qg);
$pg = $poolg - $rG['pgcnt'];


ECHO 'pool1 :'.$p1.'/ pool2:'.$p2.'/pool3:'.$p3.'/pool4:'.$p4.'/GPU : '.$pg.' '.$id;
Echo '<br>';
}