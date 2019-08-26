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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="./css/all.css">
<link rel="stylesheet" href="./css/adm.css">

<?include_once('./mypage_head.php')?>
<?include_once('./mypage_left.php')?>

