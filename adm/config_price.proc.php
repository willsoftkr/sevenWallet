<?php
include_once('./_common.php');


$sql = " update coin_cost set 
btc_manual_use = '{$_POST[btc_use]}',
btc_manual_cost = '{$_POST[btc_cost]}',
v7_manual_use = '{$_POST[v7_use]}',
v7_manual_cost = '{$_POST[v7_cost]}' " ;

print_r($sql);
sql_query($sql);

goto_url('./config_price.php');
?>