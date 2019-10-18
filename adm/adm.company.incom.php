<?php
$sub_menu = "700200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$g5['title'] = '대표 지갑 송금/입금 내역';

$g5['income_table'] = "wallet_income"; 

include_once('./adm.eos_header.php');

$colspan = 6;

$to_date = date("Y-m-d", strtotime(date("Y-m-d")."+1 day"));

$sql_common = " from {$g5['income_table']} ";
$sql_search = " where createdAt between '{$fr_date}' and '{$to_date}' ";


if (isset($domain))
    $sql_search .= " and mb_id like '%{$mb_id}%' ";

$sql = " select count(C.createdAt) as cnt";
$sql .= " from (select DATE_FORMAT(`createdAt`, '%Y-%m-%d') as createdAt from wallet_income ";
$sql .= $sql_search;
$sql .= ") as C";



$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *,DATE_FORMAT(`createdAt`, '%Y-%m-%d %H:%i:%s') as createdAt
            {$sql_common}
            {$sql_search}
            order by createdAt desc
            limit {$from_record}, {$rows} ";
            
$result = sql_query($sql);
?>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">no</th>
        <th scope="col">아이디</th>
        <th scope="col">Wallet_id</th>
        <th scope="col">transaction</th>
        <th scope="col">입금금액</th>
        <th scope="col">코인종류</th>
        <th scope="col">지갑 타임스탬프</th>
        <th scope="col">종류</th>
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
        $device = $row['vi_device'];

        $link = '';
        $link2 = '';
        $referer = '';
        $title = '';

        if ($row['vi_referer']) {

            $referer = get_text(cut_str($row['vi_referer'], 255, ''));
            $referer = urldecode($referer);

            if (!is_utf8($referer)) {
                $referer = iconv_utf8($referer);
            }

            $title = str_replace(array('<', '>', '&'), array("&lt;", "&gt;", "&amp;"), $referer);
            $link = '<a href="'.$row['vi_referer'].'" target="_blank">';
            $link = str_replace('&', "&amp;", $link);
            $link2 = '</a>';
        }

        if ($is_admin == 'super')
            $ip = $row['vi_ip'];
        else
            $ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", G5_IP_DISPLAY, $row['vi_ip']);

        if ($brow == '기타') { $brow = '<span title="'.get_text($row['vi_agent']).'">'.$brow.'</span>'; }
        if ($os == '기타') { $os = '<span title="'.get_text($row['vi_agent']).'">'.$os.'</span>'; }

        $bg = 'bg'.($i%2);

        $amt = $row['transfer_value']/100000000;
        if($amt < 0){
            $bool = 'red';
            $memo = "회사지갑으로 송금";
        }else{
            $bool = '';
            $memo = "회원지갑 입금";
        }   
    ?>
    <style>
        .red{color:red}
    </style>
    <tr class="<?php echo $bg; ?>">
        <td class="td_category"><?php echo $row['no'] ?></td>
        <td><?php echo $rowid ?></td>
        <td><?=$row['transfer'] ?></td>
        <td style="text-align:center"><a href="https://www.smartbit.com.au/tx/<?=$row['hash']?>" target='_blank'>transaction</a></td>
        
        <td class="td_category td_category1 <?=$bool?>"><?= $amt ?></td>
        <td class="td_category td_category3"><?php echo $row['coin'] ?></td>
        <td class="td_category td_category2" style="width:20%"><?php echo $row['createdAt'] ?></td>
        <td class="td_datetime"><?=$memo ?></td>
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
