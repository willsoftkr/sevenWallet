<?
$math_sql = "select  sum(mb_btc_account + mb_btc_calc + mb_btc_amt) as btc_total, (mb_v7_account + mb_v7_calc) as v7_total from g5_member where mb_id = '".$member['mb_id']."'";
$math_total = sql_fetch($math_sql);

/* 지갑잔고 */
$btc_account = number_format($math_total['btc_total'],8);
$btc_account_num = $math_total['btc_total'];
$v7_account = number_format($math_total['v7_total'],2);
$v7_account_num = $math_total['v7_total'];
$balance_account = number_format($math_total['v7_total']/2,2);

/* 코인시세 */
$btc_cost = number_format(get_coin_cost('btc'),2);
$btc_cost_num = get_coin_cost('btc');
$v7_cost = number_format(get_coin_cost('v7'),2);

/* 시세반영잔고 */
$btc_rate = number_format( $math_total['btc_total'] * get_coin_cost('btc'),2);
$v7_rate = number_format( $math_total['v7_total'] * get_coin_cost('v7'),2);

/* 전체지갑잔고 */
$total_rate = number_format(($math_total['btc_total'] * get_coin_cost('btc')) + ($math_total['v7_total'] * get_coin_cost('v7')),2);

/*전환수수료*/
$exchange_fee = 3;

/*전환 수수료 계산*/
/*
function exchage_result($val) {
	$exchage_cost = get_coin_cost('btc')+((get_coin_cost('btc')*3/100);
	return Number_format($exchage_cost*$val, 2);
}*/

function exchage_result($val) {
	$exchage_cost = 100 + (100*3/100);
	return Number_format($exchage_cost*$val, 2);
}

/*업스테어*/ 
function deposit_result($val){
	return Number_format(get_coin_cost('btc')*$val, 2);
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