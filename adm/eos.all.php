<?php
$sub_menu = "600300";
include_once('./_common.php');
$today = $_GET['to_date'];
$stx = $_SERVER['QUERY_STRING'];
?>


<script>
var i = 0;
var today = "<?=$today?>";
var stx = "<?=$stx?>";

document.write(today + " 날짜 수당지급 실행 중<br>");

var it = setInterval(function(){
if(i < 6){
    if(i == 0){
        $.ajax({ 
            type: 'GET',
            data: {  },
             url: 'eos.upstair.php?1=1'+stx,
             success: function (result) {
                document.body.append("매출 실행완료");
             }
          })
     }else if(i == 1){
        $.ajax({ 
            type: 'GET',
            data: { },
             url: 'eos.daily.pay.php?1=1'+stx,
             success: function (result) {
                 document.body.append("일수당지급완료");
             }
          })
     }else if(i == 2){
        $.ajax({ 
            type: 'GET',
            data: { },
             url: 'eos.benefit.immediate.php?1=1'+stx,
             success: function (result) {
                 document.body.append("10x10수당지급완료");
             }
          })
    }else if(i == 3){
        $.ajax({ 
            type: 'GET',
            data: { },
             url: 'eos.member.level.php?1=1'+stx,
             success: function (result) {
                 document.body.append("승급진행");
             }
          })
    }else if(i == 4){
        $.ajax({ 
            type: 'GET',
            data: { },
             url: 'eos.qpack.php?1=1'+stx,
             success: function (result) {
                 document.body.append("Q팩지급완료");
             }
          })
    }else if(i == 5){
        $.ajax({ 
            type: 'GET',
            data: { },
             url: 'eos.bpack.php?1=1'+stx,
             success: function (result) {
                document.body.append("B팩지급완료");
             }
          })
    }
    ++i;
  }else{
    alert( today + "수당지급이 완료되었습니다.");
    clearInterval(it);
    history.back();
   
    //이후 실행문
  }

}, 4500);


</script>


<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="<?=G5_THEME_URL?>/_common/js/jquery-ui.min.js"></script>
	<script src="<?=G5_THEME_URL?>/_common/js/common.js"></script>
	<script src="<?=G5_THEME_URL?>/_common/js/gnb.js"></script>