<?php
include_once('../common.php');  

?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8" />
	<title>FIJI MINING</title>
	<meta property="og:type" content="article" />
	<meta property="og:title" content="FIJI" />
	<meta property="og:url" content="" />
	<meta property="og:description" content="." />
	<meta property="og:site_name" content="" />
	<meta property="og:image" content="FIJI Mining Have" />
	<meta property="og:image:width" content="800" />
	<meta property="og:image:height" content="400" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- css연결 -->
	<link rel="stylesheet" href="css/all.css">
	<link rel="stylesheet" href="css/adm.css">
	<link rel="stylesheet" href="css/marketer.css">

	<!-- jQuery 연결 -->
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/adm.js"></script>
	
</head>
<body>
	<?include_once('mypage_head.php')?>
	<?include_once('mypage_left.php')?>
	
		<div id="content">
			<p class="line1">
				REQUEST MP
				<span class="btn" id="btn_write">Apply</span>
			</p>
			
			<table cellspacing="0" cellpadding="0" border="0" class="regTb" id="list_writing">
				<colgroup>
					<col style="width:auto;"/>
					<col style="width:160px;"/>
					<col style="width:160px;"/>
					<col style="width:160px;"/>
				</colgroup>
				<thead>
					<th>Message</th>
					<th>Requested</th>
					<th>Status</th>
					<th>Approved</th>
				</thead>
				<tbody>
<?

	$sql = " select count(*) as cnt from marketer A inner join g5_member M on A.writer = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."'";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 5;
	$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
	if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = "select * from marketer A inner join g5_member M on A.writer = M.mb_id ";
	$sql .= " WHERE M.mb_id = '".$member['mb_id']."'";
	$sql .= " order by create_dt desc ";
	$sql .= " limit {$from_record}, {$rows} ";
	$list = sql_query($sql);
?>
				<?for ($i=0; $row=sql_fetch_array($list); $i++) {?>
					<tr idx="<?php echo $row['idx']?>" >
						<td title="<?=$row[content]?>">
							<?=$row[content]?>
						</td>
						<td class="mid"><?=$row[create_dt]?></td>
						<td class="mid">
							<?=$row[status] == 'R' ? 'Requested':'';?>
							<?=$row[status] == 'Y' ? 'Approved':'';?>
							<?=$row[status] == 'S' ? 'Wait':'';?>
							<?=$row[status] == 'N' ? 'Disapproved':'';?>
						</td >
						<td class="mid"><?=$row[update_dt]?></td>
					</tr>
				<?}?>
				</tbody>
			</table>
	<?php
		$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&amp;domain='.$domain.'&amp;page=');
		if ($pagelist) {
			echo $pagelist;
		}
	?>

			<form action ="./marketer.i.u.php" method="POST" enctype="multipart/form-data" >
				<input type="hidden" name="idx" value="" />
				<input type="hidden" name="mb_id" value="<?php echo $member['mb_id'];?>" />
				<table cellspacing="0" cellpadding="0" border="0" class="regTb" id="con_write" style="display:none;">
					<colgroup>
						<col style="width:160px;"/>
						<col style="width:350px;"/>
						<col style="width:160px;"/>
						<col style="width:auto;"/>
						<col style="width:80px;"/>
					</colgroup>
					<tbody>
						<tr>
							<th>Important</th>
							<td colspan="3">
								Download the MP application, fill it up and send back with the copy of lease contract and office photos<br>MP 지원서를 다운받아서 작성 한 후에 사무실 임대 계약서와 사무실 사진과 함께 업로드 하시면 개별 심사 후 승인을 해드리겠습니다.
							</td>

							<td><input type="submit" name="submit" id="btn_req" class="btn" value="Request" /></td>
						</tr>
						<tr>
							<th>Message</th>
							<td colspan="4" >
								<textarea name="content" class="content" maxlength="65536" style="width:100%;height:150px;" ></textarea>
								<p id="writing">
									
								</p>
							</td>
						</tr>
						<tr>
							<th>Download </th>
							<td colspan="4">
								<a href="<?php echo G5_URL?>/new/M.P_application.pdf" target="_blank">MP Application</a>
							</td>
						</tr>
						<tr>
							<th>Upload ( jpg, png, pdf )</th>
							<td colspan="4" >
								<input type="file" name="bf_file[]" title="파일첨부 1 : 용량 1,048,576 바이트 이하만 업로드 가능" class="frm_file frm_input" accept=".jpg, .png, .pdf">
								<input type="file" name="bf_file[]" title="파일첨부 2 : 용량 1,048,576 바이트 이하만 업로드 가능" class="frm_file frm_input" accept=".jpg, .png, .pdf">
								<input type="file" name="bf_file[]" title="파일첨부 3 : 용량 1,048,576 바이트 이하만 업로드 가능" class="frm_file frm_input" accept=".jpg, .png, .pdf">
								<input type="file" name="bf_file[]" title="파일첨부 4 : 용량 1,048,576 바이트 이하만 업로드 가능" class="frm_file frm_input" accept=".jpg, .png, .pdf">
								<input type="file" name="bf_file[]" title="파일첨부 5 : 용량 1,048,576 바이트 이하만 업로드 가능" class="frm_file frm_input" accept=".jpg, .png, .pdf">
							</td>
						</tr>
					</tbody>
					
				</table>
			</form>
			<table cellspacing="0" cellpadding="0" border="0" class="regTb" id="con_view" style="display:none;">
				<colgroup>
					<col style="width:160px;"/>
					<col style="width:350px;"/>
					<col style="width:160px;"/>
					<col style="width:auto;"/>
					<col style="width:80px;"/>
				</colgroup>
				<tbody>
					<tr>
						<th>Status</th>
						<td class="status">Initial</td>
						<th>Requested</th>
						<td class="create_dt"></td>
						<td></td>
					</tr>
					<tr>
						<th>Message</th>
						<td colspan="4" >
							<p class="writing">
								
							</p>
						</td>
					</tr>
				</tbody>
				<tbody class="con_file" style="display:none;">
					<tr>
						<tr>
							<th>file</th>
							<td colspan="4" class="file">
								
							</td>
						</tr>
					</tr>
				</tbody>
				<tbody class="con_comment" style="display:none;">
					<tr>
						<th>Commenter</th>
						<td class="commenter"></td>
						<th>Comment Date</th>
						<td class="comment_dt"></td>
						<td></td>
					</tr>
					<tr>
						<th>Comment</th>
						<td colspan="4" class="comment">
							
						</td>
					</tr>
				</tbody>
			</table>
		</div>

	<?include_once('mypage_footer.php')?>
</body>
</html>
<script>
	var map = {
		R : 'requested',
		Y : 'Approved',
		S : 'Request additional information',
		N : 'Dispproval',
	}
	$(function(){
		$('#list_writing tbody td').on('click',function(e){
			//console.log($(this).parents('tr').attr('idx'));
			$('#con_view').show();
			$('#con_write').hide();
			$.post( "./marketer.r.php", {
				idx : $(this).parents('tr').attr('idx')
			}, function(data) {
				// console.log(data.filename);
				$('#con_view .status').html(map[data.status]);
				$('#con_view .create_dt').html(data.create_dt);
				$('#con_view .writing').html(data.writing);

				console.log(data);
				if(data.comment){
					$('#con_view .con_comment').show();
					$('#con_view .comment').html(data.comment);
					$('#con_view .comment_dt').html(data.comment_dt);
					$('#con_view .commenter').html(data.commenter);
				}else{
					$('#con_view .con_comment').hide();
				}
				if(data.file_list.length > 0){
					$('#con_view .con_file .file').empty();
					$('#con_view .con_file').show();
					$.each(data.file_list, function( index, obj ) {
						if(obj.filename != ''){
							var btn = $('<a>');
							btn.attr('href','<?=G5_URL?>/bbs/download.php?bo_table=marketer&wr_id=' + obj.wr_id + '&no=' + obj.bf_no);
							btn.html(obj.filename);
							$('#con_view .con_file .file').append(btn).append('<br>');
						}
					});
				}else{
					$('#con_view .con_file').hide();
				}
			},'json');
		});
		var placeholder = '1. Name \n';
		placeholder += '2. Username \n';
		placeholder += '3. Phone number \n';
		placeholder += '4. Office Address';
		$('#btn_write').on('click',function(e){
			$('#con_view').hide();
			$('#con_write').show();
			$('textarea.content').val( placeholder);
		});

		/*<$('textarea.content').focus(function(){
			if($(this).val() === placeholder){
				$('textarea.content').val('');
			}
		});

		$('textarea.content').blur(function(){
			if($(this).val() ===''){
				$('textarea.content').val( placeholder);
			}    
		});*/


		<?php
			if($total_count < 1){
				echo "$('#btn_write').trigger('click');";
				echo "$('#list_writing').hide();";
			}
		?>


	});
</script>