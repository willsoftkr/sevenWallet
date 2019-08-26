<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/faq/style.css">

	<script>
		$(function() {
			var questions = document.getElementsByClassName('question');

			for (var i = 0; i < questions.length; i++) {
				questions[i].onclick = function() {
					this.classList.toggle('qa-open');

					var answer = this.nextElementSibling;

					if (answer.style.maxHeight) {
						answer.style.maxHeight = null;
					} else {
						answer.style.maxHeight = answer.scrollHeight + 'px';
					}
				}
			}

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
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray" data-i18n="faq.title">FAQ</h2>
			<div class="faq-container shadow">

				<div class="faq-panels">
					<ul class="faq-tabs">
						<li rel="general" class="active" data-i18n="faq.tab1">GENERAL</li>
						<li rel="account" data-i18n="faq.tab2">ACCOUNT</li>
						<li rel="mining" data-i18n="faq.tab3">MINING</li>
					</ul>

					<div id="general" class="faq-panel active">
						<div class="qa-container">
							<div class="question" data-i18n="faq.tab1_title1" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab1_content1">
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab1_title2">
							</div>
							<div class="answer" data-i18n="[html]faq.tab1_content2" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab1_title3" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab1_content3" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab1_title4" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab1_content4" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab1_title5" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab1_content5" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab1_title6" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab1_content6" >
							</div>
						</div>
					</div>

					<div id="account" class="faq-panel">
						<div class="qa-container">
							<div class="question" data-i18n="faq.tab2_title1" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab2_content1" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab2_title2" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab2_content2" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab2_title3" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab2_content3" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab2_title4" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab2_content4" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab2_title5" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab2_content5" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab2_title6" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab2_content6" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab2_title7" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab2_content7" >
							</div>
						</div>
					</div>

					<div id="mining" class="faq-panel">
						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title1" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content1" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title2" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content2" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title3" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content3" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title4" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content4" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title5" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content5" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title6" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content6" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title7" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content7" >
							</div>
						</div>

						<div class="qa-container">
							<div class="question" data-i18n="faq.tab3_title8" >
							</div>
							<div class="answer" data-i18n="[html]faq.tab3_content8" >
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</body>
</html>
