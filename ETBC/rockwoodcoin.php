<?php
include_once('./_common.php');
$sql = "select * from g5_write_notice";
$list = sql_query($sql);

?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/rockwood.css">

	<script>
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
		});
	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">		
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray">Rockwood Coin</h2>
			<div class="faq-container shadow">
				<div class="img"><img src="images/rockwoodcoin.png"></div>
				
								
						</div>				  
						 
			
		</div>
	</div>
<script>
	var menu_dropdown = document.getElementsByClassName('menu-dropdown');

for (var i = 0; i < menu_dropdown.length; i++) {
	menu_dropdown[i].onclick = function() {
		this.classList.toggle('is_open');

		var menu_item = this.nextElementSibling;

		if (menu_item.style.maxHeight) {
			menu_item.style.maxHeight = null;
		} else {
			menu_item.style.maxHeight = menu_item.scrollHeight + "px";
		}
	}
}
//dropdown
jQuery(document).ready(function($) {
  var alterClass = function() {
    var ww = document.body.clientWidth;
    if (ww >= 1000) {
      $('#side-menu').addClass('side-menu-open');
      $('#body-wrapper').addClass('nav-body-shift');
    } else if (ww < 1000) {
    	$('#side-menu').removeClass('side-menu-open');
    	$('#body-wrapper').removeClass('nav-body-shift');
    }
  };
  alterClass();
});
// side-menu
function toggleSideMenu() {
	document.getElementById('side-menu').classList.toggle('side-menu-open');
	document.getElementById('side-menu').classList.toggle('shadow');

	if (document.body.clientWidth >= 1000) {
		document.getElementById('body-wrapper').classList.toggle('nav-body-shift');		
	}
}
// side-body moving


var questions = document.getElementsByClassName('question');

for (var i = 0; i < questions.length; i++) {
	questions[i].onclick = function() {
		this.classList.toggle('qa-open');

		var answer = this.nextElementSibling;

		if (answer.style.maxHeight) {
			answer.style.maxHeight = null;
		} else {
			answer.style.maxHeight = answer.scrollHeight  + 'px';
		}
	}
}


$(function() {
	$('.faq-panels .faq-tabs li').on('click', function() {
		var $panel = $(this).closest('.faq-panels');

		$panel.find('.faq-tabs li.active').removeClass('active');
		$(this).addClass('active');

		var panelToShow = $(this).attr('rel');

		$panel.find('.faq-panel.active').fadeOut(300, showNextPanel);

		function showNextPanel() {
			$(this).removeClass('active');

			$('#' + panelToShow).fadeIn(300, function() {
				$(this).addClass('active');
			});
		}
	})
});
</script>

</body>
</html>
