<?php
include_once('./_common.php');
include_once(G5_THEME_PATH.'/_include/head.php'); 
include_once(G5_THEME_PATH.'/_include/gnb.php');

$bo_table = "g5_write_news";


$list_cnt = sql_fetch("select count(*) as cnt from {$bo_table} where wr_1 = '1' order by wr_datetime desc");
$cnt = $list_cnt['cnt'];

$sql = "select * from {$bo_table} where wr_1 = '1' order by wr_datetime desc";
$list = sql_query($sql);

?>

	<!--<link rel="stylesheet" href= "<?=G5_THEME_URL?>/theme/css/style.css">-->

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
                var table = "news";

				$selected = $(this).next();
				if($(this).hasClass('qa-open')){// 닫기
					$(this).removeClass('qa-open');
					$selected.css('height','0px');
				}else{ // 열기
					$(this).addClass('qa-open');
                   //0px $(this).find('.views').text(Number($(this).find('.views').text()) + 1);
                    
					$.get( g5_url + "/util/news_read.php", {
						bo_table : table,
						no : $(this).attr('no')
					}, function(data) {
                        //$('#notReadCnt').text(data.not_read_cnt);
                        
						$selected.find('p.writing').html(data.writing);
						$selected.find('p.files').empty();
						$selected.find('p.images').empty();

						$.each(data.file_list, function( index, obj ) {
							if(obj.filename != ''){
								if(obj.bf_type == 0){
									var btn = $('<a>');
									btn.attr('href','/bbs/download.php?bo_table='+ table +'&wr_id=' + obj.wr_id + '&no=' + obj.bf_no);
									btn.html(obj.filename);
									$selected.find('p.files').append("<span class='font_red' style='font-weight:600'>Download : </span>").append(btn).append('<br>');
								}else {
									// console.log(obj)
									var img = $('<img>');
									img.attr('src','<?=G5_DATA_URL?>/file/<?=$bo_table?>/' + obj.bf_file);
									$selected.find('p.images').append(img).append('<br>');
                                }
							}
						});

						$selected.css('height', ($selected.prop('scrollHeight') + 30) + 'px');
					},'json');
				}
			});

			if(open) {
				$('.question').eq(0).trigger('click');
			}
		});
	</script>
     <?php if($is_admin){?>
        <div style="position:relative;right:10px;text-align:right;"><a class="btn btn-primary" style="margin-top:30px;color:white;" href="/bbs/write.php?bo_table=news">admin</a></div>
    <?php }?>


    <section class="con90_wrap">

   
	<div class="main-container">	
        
		<div id="body-wrapper" class="big-container-wrapper">
			<div class="faq-container shadow">
				<div class="qa-container ">
					<div class="title">
						<span class="date" data-i18n="news.th1" style="text-align:left" >Date</span> 
						<span class="inner_title" data-i18n="news.th2">Title</span>
						<span class="views" data-i18n="news.th3">Views</span>
					</div>
				</div>
				
				<div class="qa-container">
                
                    <?for($i; $row = sql_fetch_array($list); $i++){?>
					

						<div class="question" no="<?echo $row['wr_id']?>">
							<span class="date"><?echo date("d-m-Y", strtotime($row['wr_last']))?></span> 
							<span class="inner_title" ><?echo $row['wr_subject']?></span>
							<span class="views"><?echo $row['wr_hit']?></span>
						</div>
						<div class="answer">
							<p class="images"></p> 
							<p class="files"></p> 
							<p class="writing"></p> 
						</div>

					<?}?>
					<?if($cnt == 0){?>
						<div style="height:200px; text-align:center;line-height:200px;font-size:1.5em;"> Not yet news.</div>
					<?}?>
				</div>

            </div>
        </div>
    </div>		
			    
    <div class="gnb_dim"></div>

    </section>



<script>
    $(function() {
        $(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_news.png' alt='아이콘'> <span data-i18n='title.뉴스'>코인 거래 내역</span>");
    });
</script>

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>
