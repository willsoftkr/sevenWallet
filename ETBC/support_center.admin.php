<?php
include_once('./_common.php');
if(!$is_admin){
	header('Location: support_center.php');
}
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/support_center/style.css?v=20190311">
	<script>
	/*
		var topicOption = {
			0 : 'General Support',
			1 : 'Withdrawal Issue',
			2 : 'Payment Issue',
			3 : 'Commission Issue',
			4 : 'Mining Pool Earnings Issue',
			5 : 'Account Changes',
			6 : 'HACKED'
		};
		$(function() {

			// 티켓 펼치기 
			$(document).on('click','.ticket-header' ,function(e) {
				$selected = $(this).next();
				if($selected.css('max-height') != '0px' ){ // close
					$selected.css('max-height','0px');
				}else{ // open
					getComment($(this).attr('idx'));
				}
			});

			// 코멘트 달기
			$(document).on('click','.btn.send' ,function(e) {
				
				$('#ticketChildForm [name=idx]').val($(this).attr('idx'));
				$('#ticketChildForm [name=content]').val($(this).parents('.chat-input').find('.message').val());
				$('#ticketChildForm').append($(this).parents('.chat-input').find('.messageFile').clone());
				$('#ticketChildForm').submit();

			});

			$(document).on('keydown','.message' ,function(e) {
				if(e.which == 13) {
					e.preventDefault();
					$(this).next().find('.send').trigger('click');
				}
			});

			$(document).on('click','.btn.cl' ,function(e) {
				$.ajax({
					url: 'support_center.ticket.php',
					type: 'PUT',
					data: {idx : $(this).attr('idx')},
					success: function(result) {
						$('.support-panels .support-tabs li[rel=active-tickets]').trigger('click');
						// console.log(result);
					}
				});
			});

			$('.support-panels .support-tabs li').on('click', function(e) {
				$('.support-panels .support-tabs li').removeClass('active');
				$('.support-panels .panel').removeClass('active').hide();

				$(this).addClass('active');
				$('#' + $(this).attr('rel')).addClass('active').fadeIn(300);

				if($(this).attr('rel') == 'active-tickets'){
					$.get( "support_center.ticket.php",{
						is_closed : 0
					}).done(function( data ) {
						// console.log(data);
						makeList('#active-tickets',data);
					});
				}else if($(this).attr('rel') == 'closed-tickets'){
					$.get( "support_center.ticket.php",{
						is_closed : 1
					}).done(function( data ) {
						makeList('#closed-tickets',data);
					});
				}else if($(this).attr('rel') == 'answered-tickets'){
					$.get( "support_center.ticket.php",{
						is_closed : 1,
						is_answer : 1
					}).done(function( data ) {
						makeList('#answered-tickets > .list',data);
					});
				}
			});

			$('.support-panels .support-tabs li[rel=active-tickets]').trigger('click');
		});

		function makeList(tabId, data){
			var vHtml = $('<div>');
			$.each(data, function( index, ticket ) {
				var row = $('#dup').clone();
				row.find('.ticket-header').attr('idx', ticket.idx);
				row.find('.topic').append('['+ topicOption[ticket.topic] +'] ');
				row.find('.subject').append(ticket.subject);
				row.find('.create_date').text(ticket.create_date);

				if(Number(ticket.is_closed)){
					row.find('.ticket-header').addClass('closed');
					row.find('.chat-input').remove();
				}else{
					row.find('.btn.send').attr('idx', ticket.idx);
					row.find('.btn.cl').attr('idx', ticket.idx);	
				}

				vHtml.append(row.html());
			});
			$(tabId).html(vHtml.html());
		}

		function getComment(paramIdx){
			$selected.find('.chat').empty();
			$selected.find('.chat-input .message').val('');
			$.get( "support_center.ticket.child.php",{
				idx : paramIdx
			}).done(function( data ) {
				// console.log(data);
				var vHtml = $('<div>');
				$.each(data.list, function( index, obj ) {
					var row = $('#dup2').clone();
					if(obj.mb_no == 1){ // 관리자
						row.find('.message').addClass('support-message');
						row.find('.name').text('FIJI Support');
					}else{
						row.find('.message').addClass('member-message');
						row.find('.name').text(obj.mb_id + ' (' + obj.mb_name + ')');
					}
					row.find('.content').text(obj.content);
					row.find('.time').text(obj.create_date);
					if(obj.bf_source){
						var btn = $('<a>');
						btn.attr('href','<?=G5_URL?>/bbs/download.php?bo_table=supportCenterChild&wr_id=' + obj.wr_id + '&no=' + obj.bf_no);
						btn.text(obj.bf_source);
						row.find('.message').append(btn);
					}
					vHtml.append(row.html());
				});
				$selected.find('.chat').append(vHtml.html());
				$selected.css('max-height', $selected.prop('scrollHeight') + 'px');
			}).fail(function(e) {
				console.log( e );
			});
		}
		*/
	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">		
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="support-title">Support Center Admin</h2> 
			
			<span class="gray"></span>
			<div class="support-container shadow">
				<div class="support-panels">
					<ul class="support-tabs">
						<li rel="active-tickets" class="active">Active Tickets</li>
						<li rel="answered-tickets">Answered Tickets</li>
						<li rel="closed-tickets">Closed Tickets</li>
					</ul>
					<div id="active-tickets" class="panel active"></div>
					<div id="answered-tickets" class="panel">
						<div class="list"></div>
					</div>
					<div id="closed-tickets" class="panel"></div>
				</div>
			</div>
		</div>
	</div>

	<div style="display:none;" id="dup">
		<div class="ticket-header">
			<strong class="topic"></strong>
			<span class="ticket-title subject" ></span> 
			<span class="ticket-time create_date">12:34 PM</span>
		</div>
		
		<div class="chat-box">
			<div class="chat">
				
			</div>

			<div class="chat-input">
				<div class="input-group mb-3">
					<input type="text" class="form-control message" placeholder="Message" aria-label="Message" aria-describedby="basic-addon2" >
					<div class="input-group-append">
						<button class="btn btn-primary send" type="button">Send</button>
						<button class="btn btn-danger cl" type="button">close</button>
					</div>
				</div>
				<div class="custom-file">
					<input type="file" class="custom-file-input messageFile" name="bf_file[]" accept=".jpg, .png, .pdf" >
					<label class="custom-file-label" for="customFile">Choose file ( 5MB limit, .jpg, .png, .pdf )</label>
				</div>
			</div>
		</div>
	</div>

	<div style="display:none;" id="dup2" >
		<div class="message">
			<span class="content">Mauris et interdum tellus. Praesent nec </span><br>
			<p>- <span class="name">FIJI Support</span> (<span class="time" >12:40 PM</span>)</p>
		</div>
	</div>

	<div style="display:none;" >
		<form id="ticketChildForm" action ="support_center.ticket.child.php" method="post" enctype="multipart/form-data" >
			<input type="hidden" name="idx" >
			<input type="hidden" name="content" >
		</form>
	</div>
</body>
</html>
