<?php

$menu["menu700"] = array (
	array('700000', ' 입금/출금/매출', ''.G5_ADMIN_URL.'/shop_admin/sale1.php', '0'),
	array('700100', '매출현황 보기', G5_ADMIN_URL.'/shop_admin/sale1.php', 'sst_order_stats'),
	array('700200', '매출전환내역', G5_ADMIN_URL.'/shop_admin/orderlist.php', 'scf_order', 1),
	/*
    array('700300', '출금 요청 내역 (BTC)', G5_ADMIN_URL.'/config_withdrawal.php', 'bbs_board'),
    array('700400', '출금 요청 내역 (ETH)', G5_ADMIN_URL.'/config_withdrawal_eth.php', 'bbs_board'),
	*/
	/*
	array('700100', ' 입금 멤버 내역', ''.G5_ADMIN_URL.'/adm.eos.incom.enable.php', 'eos incom'),
	array('700200', ' 전체 수집 내역', ''.G5_ADMIN_URL.'/adm.eos.incom.php', 'eos incom all'),
	array('700300', '출금 요청 검토', G5_ADMIN_URL.'/withdrawal_batch.php', 'bbs_board')
	*/
);

?>