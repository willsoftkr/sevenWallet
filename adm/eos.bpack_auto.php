<?php
$sub_menu = "600300";
include_once('./_common.php');
$today = $_GET['to_date'];

$yesterday = date("Y-m-d", strtotime(date("Y-m-d")."-1 day"));
$tomorow = date("Y-m-d", strtotime($today."+1 day"));

if($tomorow > $yesterday){
	$tomorow ='0';
}

$stx = $_SERVER['QUERY_STRING'];

?>


<script >
var i = 0;
var today = "<?=$today?>";
var stx = "<?=$stx?>";
var tomorow = "<?=$tomorow?>";
var yesterday = "<?=$yesterday?>";

document.write(today + " 날짜 실행 중<br>");
document.write(yesterday + " 까지실행<br>");
document.write("다음날짜 : "+ tomorow+"<br>");

var it = setInterval(function(){
if(i < 1){
    
    if(i == 0){
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
    //alert( today + "까지 수당지급이 완료되었습니다.");
    
    //history.back();
	if(tomorow  != 0){
		var url = "./eos.bpack_auto.php?&price=pv&to_date="+tomorow+"&fr_date="+tomorow;
		location.href = url;
	}else{
	 alert( today + " 까지 수당지급이 완료되었습니다.");
	}
	//location.href='/adm/eos.all.php?&price=pv&to_date=2019-08-02&fr_date=2019-08-02' ;
	//goto_url('http://211.238.13.198/adm/eos.all2.php?&price=pv&to_date=2019-08-02&fr_date=2019-08-02');
    //이후 실행문
	clearInterval(it);
  }

}, 5000);


</script>


<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="<?=G5_THEME_URL?>/_common/js/jquery-ui.min.js"></script>
	<script src="<?=G5_THEME_URL?>/_common/js/common.js"></script>
	<script src="<?=G5_THEME_URL?>/_common/js/gnb.js"></script>