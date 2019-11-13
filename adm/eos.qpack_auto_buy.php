<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/wallet.php');
include_once(G5_THEME_PATH.'/_include/shop.php');


$today = date('Y-m-d',strtotime($_GET['fr_date'])); //조회 날짜를 받아옴
$threeDaysAgo = date('Y-m-d',strtotime($today."-3 days")); //조회날짜로부터 -3일

// echo $today."/";
// echo $threeDaysAgo."<br />";

$get_auto_info_sql = "SELECT * FROM g5_shop_cart WHERE ct_select_time <= DATE('$today') AND ct_select_time >= DATE('$threeDaysAgo') AND  ct_option != '' AND ct_history = '' ";

$get_auto_info_result = sql_query($get_auto_info_sql);
$get_auto_info_row_num = sql_num_rows($get_auto_info_result);

if($get_auto_info_result && $get_auto_info_row_num > 0)

$cnt = 1; // 수량
$num = 0; // 주문번호 중복방지를 위함

while($get_auto_info_row = sql_fetch_array($get_auto_info_result)){

  $num +=1;
  $orderid = date("YmdHis",time()).$num.'1'; // 주문번호
  $mb_id = $get_auto_info_row['mb_id']; // 아이디
  $order_Q = get_shop_item($get_auto_info_row['it_id']); // 주문아이템 고유 번호
  $it_sc_type = $get_auto_info_row['it_sc_type']; // B 팩인지 Q 팩인지 구분
  $coin = $get_auto_info_row['it_sc_method']; // 어느 코인으로 결제를 했는지 나타냄
  $order_price = calc_price($b_it_price + $order_Q['it_price'], $btc_cost,'btc'); // 주문 아이템의 가격을 코인으로 환산 (B 팩,Q 팩 한번에 구매했을 시)
  $cp_price = calc_price($order_Q['it_price'], $btc_cost,'btc'); // 주문 아이템의 가격을 코인으로 환산
  $ct_option = $get_auto_info_row['ct_option']; // 팩 뒤에 붙은 숫자를 그대로 인용 (ex: Q1-> 1, Q2 -> 2, Q3 -> 3)
  $order_date_q = G5_TIME_YMDHIS; // 주문날짜
  $expire_date_q = date("Y-m-d", strtotime($order_date_q."+1 month")); // 주문날짜로부터 1달추가

  /*멤버 btc차감 | 팩구매기록 */
  $check_user_coin_sql = "select  sum(mb_btc_account + mb_btc_calc + mb_btc_amt) as btc_total,
  (mb_v7_account + mb_v7_calc) as v7_total,
  (mb_eth_account + mb_eth_calc) as eth_total,
  (mb_rwd_account + mb_rwd_calc) as rwd_total,
  (mb_lok_account + mb_lok_calc) as lok_total from g5_member where mb_id = '$mb_id'";

  $check_user_coin_result = sql_query($check_user_coin_sql);
  $check_user_coin_row = sql_fetch_array($check_user_coin_result);
  $btc_account_num = $check_user_coin_row['btc_total'];
  $btc_account = number_format($btc_account_num,8);

  // echo $mb_id."/".$btc_account."/";
  // echo $order_price."/";
  // echo "<br />";

  /* 소유한 코인의 액수가 주문 상품의 액수보다 높을때 */
  if($btc_account > $order_price){

    $sql = "UPDATE g5_member set
    mb_btc_calc = round((mb_btc_calc - '$order_price'),8)";
    /* Q_sql 은 g5_member 테이블에 등록되어지면 다음 수동 구매 시 유효기간의 상품이 있을 시 확인 알람이 뜸 */
    $Q_sql =  ",it_pool2 =  '$order_Q[it_name]' ,
    it_pool2_profit =  '".$expire_date_q."',
    q_autopack = '$ct_option' ";

    $sql.=   $Q_sql;

    $sql .= "where mb_id = '$mb_id' ";
    $mem_result = sql_query($sql, false);

    /* 조회했던 아이디에 ct_history = 1 을 추가를 하여 재구매 이력을 나타냄  */
    $ct_history_updqte_sql = "update g5_shop_cart set ct_history = 1
    where ct_select_time <= DATE('$today')
    and ct_select_time >= DATE('$threeDaysAgo')
    and ct_option != ''
    and ct_history = ''
    and mb_id = '$mb_id'";

    sql_query($ct_history_updqte_sql);

    // echo "od_id = " .$orderid."/";
    // echo "mb_id = ".$mb_id."/";
    // echo "btc_account = ".$btc_account."/";
    // echo "it_id = ".$order_Q['it_id']."/";
    // echo "it_name = ".$order_Q['it_name']."/";
    // echo "it_sc_type = 20/";
    // echo "it_sc_method = ".$coin."/";
    // echo "ct_status = 구매/";
    // echo "ct_price = ".$order_price."/";
    // echo "ct_point = ".$cp_price."/";
    // echo "cp_price = ".$cp_price."/";
    // echo "io_price = ".$order_Q['it_price']."/";
    // echo "ct_qty = ".$cnt."/";
    // echo "ct_option = ".$ct_option."/";
    // echo "ct_time = ".$order_date_q."/";
    // echo "ct_ip  = ".$_SERVER['REMOTE_ADDR']."/";
    // echo "ct_select_time  = ".$expire_date_q."<br />";

    $sql = "insert g5_shop_cart set
    od_id				= '".$orderid."'
    , mb_id     = '".$mb_id."'
    , it_id     = '{$order_Q['it_id']}'
    , it_name   = '{$order_Q['it_name']}'
    , it_sc_type    ='".$it_sc_type."'
    , it_sc_method  ='".$coin."'
    , ct_status     = '구매'
    , ct_price      = '".$order_price."'
    , ct_point      = '".$cp_price."'
    , cp_price      = '".$cp_price."'
    , io_price      = '{$order_Q['it_price']}'
    , ct_qty        = '".$cnt."'
    , ct_option     = '{$ct_option}'
    , ct_time       = '".$order_date_q."'
    , ct_ip         = '{$_SERVER['REMOTE_ADDR']}'
    , ct_select_time   = '".$expire_date_q."' ";
    sql_query($sql);
    // echo $expire_date_q;
  }
}
?>
<script type="text/javascript">

  setTimeout(function(){
    alert("팩 구매를 완료 하였습니다.");
    history.back();
  },2000);

</script>
