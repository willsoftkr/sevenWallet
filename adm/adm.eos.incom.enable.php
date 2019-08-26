<?php
$sub_menu = "700100";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = 'EOS 멤버 입금 내역';
$g5['income_table'] = "eos_coin_transfer_hist"; 

include_once('./adm.eos_header.php');

$colspan = 6;

$to_date = date("Y-m-d", strtotime(date("Y-m-d")."+1 day"));


$sql_common = " from {$g5['income_table']} ";
$sql_condition = "as A inner Join g5_member as B ON B.mb_id = A.mb_id";
$sql_search = " where timestamp between '{$fr_date}' and '{$to_date}' ";

if($fr_id)
$sql_search .=" And B.mb_id = '" .$fr_id."'";

/*
if (isset($domain))
    $sql_search .= " and mb_id like '%{$mb_id}%' ";
*/

$sql = " select count(C.timestamp) as cnt";
$sql .= " from (select DATE_FORMAT(`timestamp`, '%Y-%m-%d') as timestamp from eos_coin_transfer_hist ";
$sql .= $sql_condition;
$sql .= $sql_search;
$sql .= ") as C";

		 

$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select idx,A.mb_id,transfer_amount,transfer_date,coin_symbol,DATE_FORMAT(`timestamp`, '%Y-%m-%d %H:%i:%s') as timestamp
            {$sql_common}
			{$sql_condition}
            {$sql_search}
            order by timestamp desc
            limit {$from_record}, {$rows} ";
 //print_r($sql );
$result = sql_query($sql);
?>
<style>
	.bg1{background:floralwhite}
</style>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">idx</th>
        <th scope="col">MEMO</th>
        <th scope="col">입금금액</th>
        <th scope="col">코인종류</th>
        <th scope="col">지갑 타임스탬프</th>
        <th scope="col">데이터 수집시간</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $rowid = $row['mb_id'];
		
		/*
        if(!$brow)
            $brow = get_brow($row['vi_agent']);
		*/

        $amt = $row['transfer_amount'];
		/*
        if(!$os)
            $os = get_os($row['vi_agent']);
		*/
      
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_category"><?php echo $row['idx'] ?></td>
        <td><?php echo $rowid ?></td>
        <td class="td_category td_category1"><?php echo Number_format($amt,2) ?></td>
        <td class="td_category td_category3"><?php echo $row['coin_symbol'] ?></td>
        <td class="td_category td_category2" style="width:20%"><?php echo $row['timestamp'] ?></td>
        <td class="td_datetime"><?php echo $row['transfer_date'] ?></td>
    </tr>

    <?php
    }
    if ($i == 0)
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없거나 관리자에 의해 삭제되었습니다.</td></tr>';
    ?>
    </tbody>
    </table>
</div>

<?php
if (isset($domain))
    $qstr .= "&amp;domain=$domain";
$qstr .= "&amp;page=";

$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
echo $pagelist;

include_once('./admin.tail.php');
?>
