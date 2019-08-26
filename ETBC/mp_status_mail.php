<?php
// E-mail 수정시 인증 메일 (회원님께 발송)
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!doctype html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <title>MP Requst Result Email</title>
</head>

<body>
    <div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">
        <div style="border:1px solid #dedede">
            <div style="padding:10px 30px 10px;background:#f7f7f7;text-align:left">
                <a href="<?php echo G5_URL ?>" target="_blank">
                    <img  src="<?php echo G5_URL; ?>/theme/basic/img/main_logo.png" />
                </a>
            </div>
            <p style="padding:10px 30px 30px;min-height:120px;height:auto !important;height:200px;border-bottom:1px solid #eee;word-break: break-word;">
                Hi <strong><?php echo $recipient['first_name']." ".$recipient['last_name']; ?></strong> (<?php echo $recipient['mb_id']; ?>)
                <br>
                <br>
                <?php if($status == 'R') {
                    echo "MP 요청이 정상적으로 등록되었습니다.";
                } else { 
                    $arrStatus = array(
                        "R" => "요청",
                        "Y" => "승인",
                        "S" => "추가 자료요청",
                        "N" => "부결"
                    ); 
                    echo "MP 요청 상태가 ".$arrStatus[$status]."로 변경되었습니다. ";
                ?>
                <?php } ?>
                <br>
                The Fiji Mining Team
            </p>
        </div>
    </div>
</body>
</html>
