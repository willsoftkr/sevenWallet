<?php
include_once('./_common.php');
echo $write_table;
$sql = "select * from {g5_write_news} order by wr_id desc";
$list = sql_query($sql);


echo "______________________".$list;

?>


<!doctype html>
<html lang="ko">
<head>
	
	<link rel="stylesheet" href="css/style.css">

	<script>
		var open = '<?=$_GET['open']?>';
		var $selected;
		$(function() {

			var alterClass = function() {
				var ww = document.body.clientWidth;
				if (ww < 400) {
				$('.news-table').addClass('table-responsive');
				} else if (ww >= 401) {
				$('.news-table').removeClass('table-responsive');
				};
			};
			$(window).resize(function(){
				alterClass();
			});
			alterClass();

			$(document).on('click','.question' ,function(e) {
				$selected = $(this).next();
				if($(this).hasClass('qa-open')){// 닫기
					$(this).removeClass('qa-open');
					$selected.css('max-height','0px');
				}else{ // 열기
					$(this).addClass('qa-open');
					$(this).find('.views').text(Number($(this).find('.views').text()) + 1);
					$.get( "pinnacle_news.r.php", {
						bo_table : 'notice',
						no : $(this).attr('no')
					}, function(data) {
						$('#notReadCnt').text(data.not_read_cnt);
						$selected.find('p.writing').html(data.writing);
						$selected.find('p.files').empty();
						$selected.find('p.images').empty();
						$.each(data.file_list, function( index, obj ) {
							if(obj.filename != ''){
								if(obj.bf_type == 0){
									var btn = $('<a>');
									btn.attr('href','<?=G5_URL?>/bbs/download.php?bo_table=<?=$bo_table?>&wr_id=' + obj.wr_id + '&no=' + obj.bf_no);
									btn.html(obj.filename);
									$selected.find('p.files').append(btn).append('<br>');
								}else {
									// console.log(obj)
									var img = $('<img>');
									img.attr('src','<?=G5_DATA_URL?>/file/<?=$bo_table?>/' + obj.bf_file);
									img.attr('onload',"$selected.css('max-height', $selected.prop('scrollHeight') + 'px');");
									$selected.find('p.images').append(img).append('<br>');
								}
							}
						});
						$selected.css('max-height', $selected.prop('scrollHeight') + 'px');
					},'json');
				}
			});

			if(open) {
				$('.question').eq(0).trigger('click');
			}
		});
	</script>
</head>
<body>
	
	<div class="main-container">		
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray"> <!--data-i18n="news.title"-->ETBC News</h2>
			<div class="faq-container shadow">
				<div class="qa-container ">
					<div class="title">
						<span class="date" data-i18n="news.th1">Date</span> 
						<span data-i18n="news.th2">Title</span>
						<!--xx<span class="views" data-i18n="news.th3">views</span>-->
					</div>
				</div>
				<!--
				<div class="qa-container">
                
<?for($i; $row = sql_fetch_array($list);$i++){?>
					<div class="question" no="<?echo $row['wr_id']?>">
						<span class="date"><?echo date("Y-m-d", strtotime($row['wr_last']))?></span> 
						<span class="inner_title"><?echo $row['wr_subject']?></span>
						<!--<span class="views"><?echo $row['wr_hit']?></span>--><!--
					</div>
					<div class="answer">
						<p class="images"></p> 
						<p class="files"></p> 
						<p class="writing"></p> 
					</div>
<?}?>			
				</div>
-->

<!--xx 관리자 게시판 링크버튼 
				<?php if($is_admin){				?>
				<a class="btn btn-primary" style="float:right;margin-top:30px;" href="/bbs/board.php?bo_table=notice">admin</a>
				<?php }?>
        -->
				<div style="clear:both;"></div>
			</div>
		</div>
	</div>
</body>
</html>
