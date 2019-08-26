<?php
    include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8" />
	<title>FIJI MINING</title>
	
	<meta property="og:type" content="article" />
	<meta property="og:title" content="FIJI" />
	<meta property="og:url" content="" />
	<meta property="og:description" content="." />
	<meta property="og:site_name" content="" />
	<meta property="og:image" content="FIJI Mining Have" />
	<meta property="og:image:width" content="800" />
	<meta property="og:image:height" content="400" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- css연결 -->
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/adm.css">
	
	<!-- jQuery 연결 -->
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="js/adm.js"></script>
    <style>
        /* html, body {background-color: #fff !important;} */
        .tbl_head01 thead th {background:#fff;border-top:none;border-bottom:1px solid #000;padding: 5px 0;}
        .td_numbig {color:#ef0000; font-weight:bold;}
        .tbl_head01 td {border:0px;}
        .tbl_head01 tbody {border-bottom:1px solid #000;}
        .btn_del {cursor:pointer;}
        .Grp {min-height: 600px;}
        .tbl_wrap table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }
        .btn_submit {
            padding: 8px;
            border: 0;
            background: #0c5487;
            color: #fff;
            letter-spacing: -0.1em;
            cursor: pointer;
        }
        #sod_bsk { margin: 20px 0 0 0;}
        .ct_qty1, .ct_qty {width:50px;}
    </style>
    <script>
        $(function(){
            $('.ct_qty1').spinner({ min: 0, max: 1, step: 1 });
            $('.ct_qty').spinner({ min: 0, max: 40, step: 1 });
        });
    </script>
</head>
<body>
<?php

include_once('./mypage_head.php');
include_once('./mypage_left.php');


// 보관기간이 지난 상품 삭제
cart_item_clean();

// cart id 설정
set_cart_id($sw_direct);


$s_cart_id = get_session('ss_cart_id');

// 선택필드 초기화
$sql = " update {$g5['g5_shop_cart_table']} set ct_select = '0' where od_id = '$s_cart_id' ";
sql_query($sql);


?>

<!-- 장바구니 시작 { -->
<script src="<?php echo G5_JS_URL; ?>/shop.js"></script>
<div id="content">
<div id="sod_bsk">
<form name="fitem" method="post" action="/new/cartupdate.php" >
<!-- <input type="hidden" name="act" value="buy"> -->
<input type="hidden" name="sw_direct" value="1">
<div class="tbl_head01 tbl_wrap">
    <table>
        <colgroup>
            <col style="width:90px;">
            <col style="width:auto;">
            <col style="width:90px;">
            <col style="width:90px;">
        </colgroup>
        <thead>
            <tr>
                <th scope="col"> </th>
                <th scope="col" style="text-align:left;">Product Description</th>
                <th scope="col">Price</th>
                <th scope="col">Quantity</th>
            </tr>
        </thead>
        <tbody>
    <?php
    $tot_point = 0;
    $tot_sell_price = 0;

    // $s_cart_id 로 현재 장바구니 자료 쿼리
    $sql = " select a.it_id,
                    a.it_name,
                    a.it_basic,
                    a.it_price
               from {$g5['g5_shop_item_table']} a ";
    if($member['mb_level'] > 2){
        $sql .= " where it_id <> '1527096053' ";
    }
    $sql .= " order by a.it_id desc";
    $result = sql_query($sql,true);

    $it_send_cost = 0;

    $sum_qty = 0;

    for ($i=0; $row=sql_fetch_array($result); $i++)
    {

        if ($i==0) { // 계속쇼핑
            $continue_ca_id = $row['ca_id'];
        }

        $a1 = '<strong style="color:#1f4e79;">';
        $a2 = '</strong>';
        $image = get_it_image($row['it_id'], 70, 70);

        $it_name = $a1 . stripslashes($row['it_name']) . $a2;
        $it_options = print_item_options($row['it_id'], $s_cart_id);

        $sql_coin = "select * from coin_cost";
        $row_coin = sql_fetch($sql_coin);

    ?>

    <tr>
        <td class="sod_img"><?php echo $image; ?></td>
        <td>
            <input type="hidden" name="it_id[<?php echo $i; ?>]"    value="<?php echo $row['it_id']; ?>">
            <?php echo $it_name.$mod_options; ?><br>
            <?php echo $row['it_basic']?>
        </td>
        <td class="td_numbig">
            $ <span id="sell_price_<?php echo $i; ?>"><?php echo number_format($row['it_price']); ?></span>
        </td>
        <td class="td_num">
            
            <?if($row['it_id'] == '1527096053' ) { ?>
                <input class="ct_qty1"  name="ct_qty[<?php echo $row['it_id']; ?>][]" value="0" />
            <?} else {?>
                <input class="ct_qty"  name="ct_qty[<?php echo $row['it_id']; ?>][]" value="0" />
            <?} ?>
        </td>
    </tr>

    <?php
        $tot_point      += round($sell_price/ $row_coin['btc_cost'],8);
        $tot_sell_price += $sell_price;
    } // for 끝

    ?>
    </tbody>
    </table>
</div>

<?php
$tot_price = $tot_sell_price ; // 총계 = 주문상품금액합계 + 배송비
if ($tot_price > 0 ) {
?>
<div style="text-align:right;">
    <strong>
        Subtotal ( <?php echo $sum_qty;?> items) 
        <span style="color:#ef0000;">$ <?php echo number_format($tot_price); ?></span>
    </strong>
</div>
<?php } ?>

<div id="sod_bsk_act" style="text-align:center;margin-top:10px;">
    <button type="submit" class="btn_submit">To Order</button>
</div>

</form>

</div>
</div>

<?include_once('mypage_footer.php')?>
</body>
</html>