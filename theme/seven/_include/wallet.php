<?
$math_sql = "select  sum(mb_btc_account + mb_btc_calc + mb_btc_amt) as btc_total, (mb_v7_account + mb_v7_calc) as v7_total, (mb_eth_account + mb_eth_calc) as eth_total, (mb_rwd_account + mb_rwd_calc) as rwd_total,(mb_lok_account + mb_lok_calc) as lok_total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);

/* 지갑잔고 */
$btc_account_num = $math_total['btc_total'];
$btc_account = number_format($btc_account_num,8);

$v7_account_num = $math_total['v7_total'];
$v7_account = number_format($v7_account_num,2);

$eth_account_num = $math_total['eth_total'];
$eth_account = number_format($eth_account_num,2);

$rwd_account_num = $math_total['rwd_total'];
$rwd_account = number_format($rwd_account_num,2);

$lok_account_num = $math_total['lok_total'];
$lok_account = number_format($lok_account_num,2);

$balance_account = number_format($math_total['v7_total']/2,2);

/* 코인시세 */
$btc_cost = number_format(get_coin_cost('btc'),2);
$btc_cost_num = get_coin_cost('btc');
$v7_cost = number_format(get_coin_cost('v7'),2);
$v7_cost_num = get_coin_cost('v7');
$eth_cost = number_format(get_coin_cost('eth'),2);
$eth_cost_num = get_coin_cost('eth');
$rwd_cost = number_format(get_coin_cost('rwd'),2);
$rwd_cost_num = get_coin_cost('rwd');
$lok_cost = number_format(get_coin_cost('lok'),2);
$lok_cost_num = get_coin_cost('lok');

/* 시세반영잔고 */
$btc_rate_num = $math_total['btc_total'] * get_coin_cost('btc');
$btc_rate = number_format( $btc_rate_num,2);

$v7_rate_num = $math_total['v7_total'] * get_coin_cost('v7');
$v7_rate = number_format($v7_rate_num,2);

$eth_rate_num = $math_total['eth_total'] * get_coin_cost('eth');
$eth_rate = number_format($eth_rate_num,2);

$rwd_rate_num = $math_total['rwd_total'] * get_coin_cost('rwd');
$rwd_rate = number_format($rwd_rate_num,2);

$lok_rate_num = $math_total['lok_total'] * get_coin_cost('lok');
$lok_rate = number_format($lok_rate_num,2);

/* 전체지갑잔고 */
$total_rate = number_format(($math_total['btc_total'] * get_coin_cost('btc')) + ($math_total['v7_total'] * get_coin_cost('v7')) + ($math_total['eth_total'] * get_coin_cost('eth')) + ($math_total['rwd_total'] * get_coin_cost('rwd'))+ ($math_total['lok_total'] * get_coin_cost('lok')),2);

/*전환수수료*/
$exchange_fee = 3;

/*입금 시세*/
$deposit_fee = 5;
$deposit_cost =  round($btc_cost_num - ($btc_cost_num*($deposit_fee/100)),2);

/*내 지갑 주소*/
/*
$wallet_sql = "select mb_wallet from g5_member where mb_id = '".$member['mb_id']."'";
$wallet_account = sql_fetch($wallet_sql);
$wallet_addr =  $wallet_account['mb_wallet'];
*/
/*전환 수수료 계산*/
/*
function exchage_result($val) {
	$exchage_cost = get_coin_cost('btc')+((get_coin_cost('btc')*3/100);
	return Number_format($exchage_cost*$val, 2);
}*/

/* 전환*/
function exchage_result($val) {
	$exchage_cost = 100 + (100*5/100);
	return Number_format($exchage_cost*$val, 2);
}

/*업스테어*/ 
function deposit_result($val){
	global $btc_cost;
	$deposit_cost =  $btc_cost - ($btc_cost*0.03);
	return Number_format($deposit_cost*$val, 2);
}

/*달러 표시*/
function shift_doller($val){
	return Number_format($val, 2);
}

/*BTC 표시*/
function shift_btc($val){
	return Number_format($val, 8);
}

/*숫자표시*/
function shift_number($val){
	return preg_replace("/[^0-9].*/s","",$val);
}

/*콤마제거숫자표시*/
function conv_number($val) {
	$number = (int)str_replace(',', '', $val);
	return $number;
}



/*날짜형식 변환*/
function timeshift($time){
	return date("d/m/Y ",strtotime($time));
}


function nav_active($val){
		global $stx;
		if($val == $stx) echo "active";
		if(!$stx && $val='all') echo "active";
}

function string_explode($val ){
	$stringArray = explode("member",$val);
	$string1= "<span class='tx1'>".$stringArray[0]." member</span>";
	$string2 = "<span class='tx2'>".$stringArray[1]."</span>";
	return $string1.$string2;
}

function Number_explode($val ){
	$stringArray = explode(".",$val);
	$string1= $stringArray[0].".";
	$string2 = "<string class='demical'>".$stringArray[1]."</string>";
	return $string1.$string2;
}	

?>