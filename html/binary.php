<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>


		<section class="v_center binary_wrap">
			<div class="btn_input_wrap">
				<input type="text" placeholder="회원찾기" />
				<a href="javascript:void(0);" class="search_result_btn pop_open">검색</a>
			</div>
			<div class="bin_top">
				후원계보
			</div>
			<div class="bin_wrap">
				<table>
					 <colgroup>
						<col style="width: 25%">
						<col style="width: 25%">
						<col style="width: 25%">
						<col style="width: 25%">
					  </colgroup>
						<tr>
							<td colspan="4">
								<div>
									<img src="_images/star6.png" alt="6">
									Coolrunning
								</div>
								<small>$12,595</small>
								<p>L : 0 &#92; R : 0</p>
							</td>
						</tr>
						<tr class="line_tr">
							<td colspan="4"><img src="_images/table_line.gif" alt="하위"></td>
						</tr>
						<tr>
							<td colspan="2">
								<div>
									<img src="_images/star2.png" alt="2">
									Coolrunning
								</div>
								<small>$12,595</small>
								<p>L : 0 l R : 0</p>
							</td>
							<td colspan="2">
								<select name='user_sel2'>
									<option value='' selected>회원 선택하기</option>
									<option value='u1'>회원1</option>
									<option value='u2'>회원2</option>
								</select>
								<input type="button" value="등록하기">
							</td>
						</tr>
						<tr class="line_tr">
							<td colspan="2"><img src="_images/table_line1.gif" alt="하위"></td>
							<td colspan="2"><img src="_images/table_line1.gif" alt="하위"></td>
						</tr>
						<tr>
							<td>
								<div>
									<img src="_images/star4.png" alt="4">
									Coolrunning
								</div>
								<small>$12,595</small>
								<p>L : 0 l R : 0</p>
							</td>
							<td>
								<select name='user_sel2'>
									<option value='' selected>회원 선택하기</option>
									<option value='u1'>회원1</option>
									<option value='u2'>회원2</option>
								</select>
								<input type="button" value="등록하기">
							</td>
							<td>
								<select name='user_sel2'>
									<option value='' selected>회원 선택하기</option>
									<option value='u1'>회원1</option>
									<option value='u2'>회원2</option>
								</select>
								<input type="button" value="등록하기">
							</td>
							<td>
								<select name='user_sel2'>
									<option value='' selected>회원 선택하기</option>
									<option value='u1'>회원1</option>
									<option value='u2'>회원2</option>
								</select>
								<input type="button" value="등록하기">
							</td>
						</tr>
						<tr>
							<td>
								<input type="image" src="_images/lb_btn.gif"  alt="왼쪽 맨 아래로">
							</td>
							<td>
								<input type="image" src="_images/top_btn.gif"  alt="맨 위로 가기">
							</td>
							<td>
								<input type="image" src="_images/up_btn.gif"  alt="한 단계 위로 가기">
							</td>
							<td>
								<input type="image" src="_images/rb_btn.gif" alt="오른쪽 맨 아래로">
							</td>
						</tr>
				</table>
			</div>
			<div class="vol_wrap">
				<table>
					<caption>바이너리 볼륨</caption>
					<colgroup>
						<col style="width: 20%">
						<col style="width: 40%">
						<col style="width: 40%">
					 </colgroup>
					<tbody>
						<tr>
							<th>날짜</th>
							<th>왼쪽 포인트</th>
							<th>오른쪽 포인트</th>
						</tr>
						<tr>
							<td>5&#47;31&#47;19</td>
							<td>1532</td>
							<td>55</td>
						</tr>
						<tr>
							<td>5&#47;28&#47;19</td>
							<td>574</td>
							<td>27</td>
						</tr>
						<tr>
							<td>5&#47;27&#47;19</td>
							<td>442</td>
							<td>0</td>
						</tr>
					</tbody>
				</table>
			</div>

		</section>

		<?php include '_include/popup.php'; ?>
		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_binary.png' alt='아이콘'> 바이너리 조직도");
			$('#wrapper').css("background", "#fff");
		});
	</script>



</body></html>
