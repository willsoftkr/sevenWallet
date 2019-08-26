<?php
if (!defined('_GNUBOARD_')) exit;

$begin_time = get_microtime();

include_once(G5_PATH.'/head.sub.php');

function print_menu1($key, $no)
{
    global $menu;

    $str = print_menu2($key, $no);

    return $str;
}

function print_menu2($key, $no)
{
    global $menu, $auth_menu, $is_admin, $auth, $g5;

    $str .= "<ul class=\"gnb_2dul\">";
    for($i=1; $i<count($menu[$key]); $i++)
    {
        if ($is_admin != 'super' && (!array_key_exists($menu[$key][$i][0],$auth) || !strstr($auth[$menu[$key][$i][0]], 'r')))
            continue;

        if (($menu[$key][$i][4] == 1 && $gnb_grp_style == false) || ($menu[$key][$i][4] != 1 && $gnb_grp_style == true)) $gnb_grp_div = 'gnb_grp_div';
        else $gnb_grp_div = '';

        if ($menu[$key][$i][4] == 1) $gnb_grp_style = 'gnb_grp_style';
        else $gnb_grp_style = '';

        $str .= '<li class="gnb_2dli"><a href="'.$menu[$key][$i][2].'" class="gnb_2da '.$gnb_grp_style.' '.$gnb_grp_div.'">'.$menu[$key][$i][1].'</a></li>';

        $auth_menu[$menu[$key][$i][0]] = $menu[$key][$i][1];
    }
    $str .= "</ul>";

    return $str;
}


/*## adver ################################################*/
$adver = array(
	"BEST PRODUCT",
	"신규입고",
	"한정수량",
	"이벤트 특가",
	"K&T PRODUCT",
);
/*@@End.  #####*/
?>

<script>
var tempX = 0;
var tempY = 0;

function imageview(id, w, h)
{

    menu(id);

    var el_id = document.getElementById(id);

    //submenu = eval(name+".style");
    submenu = el_id.style;
    submenu.left = tempX - ( w + 11 );
    submenu.top  = tempY - ( h / 2 );

    selectBoxVisible();

    if (el_id.style.display != 'none')
        selectBoxHidden(id);
}
</script>
<?
/*## layer id search ################################################*/
/*## layer id search ################################################*/
?>
<?include_once('../test_server.php')?>

<div id="framewrp">
<style type="text/css">
#framewrp {position:fixed;width:100%;height:100%;background:rgba(0,0,0,0.6);z-index:99999;display:none;}
#framer {position:absolute;left:50%;margin-left:-300px;top:50%;margin-top:-250px;width:600px;height:500px;background-color:#fff;border:0;}
@media screen and (max-width:480px) {
#framer {position:absolute;left:0;margin-left:0;top:10%;margin-top:0;width:100%;height:80%;}
}
</style>

	<iframe name='framer' id="framer" frameborder="0"></iframe> 
</div><!-- // framewrp -->
<script>
$(function(){
	$('span[id^="ajax_"]').click(function () {
		var $type = $(this).attr("id").replace("ajax_","");
		if ($type == "id_search") {
			var $search = $('#set_id_sel').val();
			$('#framer').attr("src","/shop/ajax.id.php?mbid="+$search);
			$('#framewrp').fadeIn();
		} else if ($type == "rcm_search") {
			var $rcm = $('#mb_recommend').val();
			var $mb_id = $('#mb_id').val();
			$('#framer').attr("src","/shop/ajax.id.php?mb_id="+$mb_id+"&rcm="+$rcm);
			$('#framewrp').fadeIn();
		}
	});
	$('#framewrp').click(function () {
		$(this).hide();
	});
});
</script>
<?
/*@@End. layer id search #####*/
/*@@End. layer id search #####*/
?>
<div id="to_content"><a href="#container">본문 바로가기</a></div>



<header id="hd">
    <div id="hd_wrap">
        <h1><?php echo $config['cf_title'] ?></h1>

        <div id="logo"><a href="<?php echo G5_ADMIN_URL ?>"><img src="<?php echo G5_ADMIN_URL ?>/img/logo.jpg" alt="<?php echo $config['cf_title'] ?> 관리자"></a></div>

        <ul id="tnb">
            <li><a href="<?php echo G5_ADMIN_URL ?>/member_form.php?w=u&amp;mb_id=<?php echo $member['mb_id'] ?>">관리자정보</a></li>
            <li><a href="<?php echo G5_ADMIN_URL ?>/config_form.php">기본환경</a></li>
            <li><a href="<?php echo G5_URL ?>/">커뮤니티</a></li>
            <?php if(defined('G5_USE_SHOP')) { ?>
              <!--xx
            <li><a href="<?php echo G5_ADMIN_URL ?>/shop_admin/configform.php">쇼핑몰환경</a></li>
            -->
            <li><a href="<?php echo G5_ADMIN_URL ?>/service.php">부가서비스</a></li>
            <!--xx
            <li><a href="<?php echo G5_SHOP_URL ?>/list.php?ca_id=10">쇼핑몰</a></li>
            -->
            <?php } ?>
            <li id="tnb_logout"><a href="<?php echo G5_BBS_URL ?>/logout.php">로그아웃</a></li>
        </ul>
<style>
	.sysbtn{float:right;position:relative;right:10px;z-index:10000;}
	.sysbtn .btn{padding:10px 15px;background:orange;}
	.sysbtn .btn.btn2{background:turquoise}
</style>

<div class="sysbtn">
	<a href="./member_grade.php" class="btn btn2" >멤버 등급 수동 갱신</a>
	<a href="#" class="btn btn1" onclick="clear_db('member');">멤버 수당잔고 초기화</a>
	<a href="#" class="btn btn1" onclick="clear_db('soodang');">수당항목 전체 초기화 버튼</a>
</div>

        <nav id="gnb">
            <h2>관리자 주메뉴</h2>
            <?php
            $gnb_str = "<ul id=\"gnb_1dul\">";
            foreach($amenu as $key=>$value) {

                $href1 = $href2 = '';
                if ($menu['menu'.$key][0][2]) {
                    $href1 = '<a href="'.$menu['menu'.$key][0][2].'" class="gnb_1da">';
                    $href2 = '</a>';
                } else {
                    continue;
                }
                $current_class = "";
                if (isset($sub_menu) && (substr($sub_menu, 0, 3) == substr($menu['menu'.$key][0][0], 0, 3)))
                    $current_class = " gnb_1dli_air";
                $gnb_str .= '<li class="gnb_1dli'.$current_class.'">'.PHP_EOL;
                $gnb_str .=  $href1 . $menu['menu'.$key][0][1] . $href2;
                $gnb_str .=  print_menu1('menu'.$key, 1);
                $gnb_str .=  "</li>";
            }
            $gnb_str .= "</ul>";
            echo $gnb_str;
            ?>
        </nav>

    </div>



</header>




<script>

function clear_db(key_value){
	
	var key = key_value;
	
	 $.ajax({ 
          type : "POST", 
          url : "./delete_db.php", 
          //data: {memberId: memberId.val(),memberPw: memberPw.val()}, 
          data:{func : key},
          error : function() { 
              alert('실패!!'); 
         }, 
         success : function() { 
            alert('DB가 초기화되었습니다');
        } 
    }); 

	
}
</script>





<?php if($sub_menu) { ?>
<ul id="lnb">
<?php
$menu_key = substr($sub_menu, 0, 3);
$nl = '';
foreach($menu['menu'.$menu_key] as $key=>$value) {
    if($key > 0) {
        if ($is_admin != 'super' && (!array_key_exists($value[0],$auth) || !strstr($auth[$value[0]], 'r')))
            continue;

        if($value[3] == 'cf_service')
            $svc_class = ' class="lnb_svc"';
        else
            $svc_class = '';

        echo $nl.'<li><a href="'.$value[2].'"'.$svc_class.'>'.$value[1].'</a></li>';
        $nl = PHP_EOL;
    }
}
?>
</ul>
<?php } ?>

<div id="wrapper">

    <div id="container">
        <div id="text_size">
            <!-- font_resize('엘리먼트id', '제거할 class', '추가할 class'); -->
            <button onclick="font_resize('container', 'ts_up ts_up2', '');"><img src="<?php echo G5_ADMIN_URL ?>/img/ts01.gif" alt="기본"></button>
            <button onclick="font_resize('container', 'ts_up ts_up2', 'ts_up');"><img src="<?php echo G5_ADMIN_URL ?>/img/ts02.gif" alt="크게"></button>
            <button onclick="font_resize('container', 'ts_up ts_up2', 'ts_up2');"><img src="<?php echo G5_ADMIN_URL ?>/img/ts03.gif" alt="더크게"></button>
        </div>
        <h1><?php echo $g5['title'] ?></h1>
