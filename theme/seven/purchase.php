<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>
	<script>
		$(function() {
			var chb=$('.purchase_wrap input[type=checkbox]');
			$(chb).change(function(){
				if($(chb).is(":checked")){
					$(this).closest('table').find('input:checkbox').not(this).prop("checked",false);
					$(this).closest('tr').find('input:radio').prop("checked",true);
				}else{
					$(this).closest('tr').find('input:radio').prop("checked",false);
				}
			});

			$('.purchase_wrap table td:last-child').click(function(){
				$('.purchase_wrap').find('input:radio').prop("checked",false);
				$('.purchase_wrap').find('input:checkbox').prop("checked",false);
			});

			$('.purchase_wrap input:radio').click(function(){
				$(chb).prop("checked",false);
			});

});
</script>

		<section class="v_center purchase_wrap">
			
			<div>
				<table>
					<tr>
						<th>B 팩</th>
						<th>가격</th>
						<th>선택</th>
						<th>자동 <span class="m_br">재구매</span></th>
						<th>취소</th>
					</tr>
					<tr>
						<td>B1</td>
						<td>&#36;600</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd1" name="b_rd1"> 
								<label for="p_rd1"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk1">
							  <label for="q_chk1"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>B2</td>
						<td>&#36;800</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd2" name="b_rd1"> 
								<label for="p_rd2"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk2">
							  <label for="q_chk2"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>B3</td>
						<td>&#36;1,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd3" name="b_rd1"> 
								<label for="p_rd3"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk3">
							  <label for="q_chk3"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
				</table>
				<p>
					바이너리 수당을 받으려면 B 팩을 구매해야 합니다.<br/>
					구매일로 부터 30일간 유효합니다.<br/>
					한번에 한개의 상품만 살 수 있습니다.<br/>
					자동 재구매를 활성화하면 매월 자동으로 재구매가 됩니다.
				</p>
				<table>
					<tr>
						<th>Q 팩</th>
						<th>가격</th>
						<th>선택</th>
						<th>자동 <span class="m_br">재구매</span></th>
						<th>취소</th>
					</tr>
					<tr>
						<td>Q1</td>
						<td>&#36;1,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd4" name="q_rd1"> 
								<label for="p_rd4"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk4">
							  <label for="q_chk4"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>Q2</td>
						<td>&#36;2,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd5" name="q_rd1"> 
								<label for="p_rd5"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk5">
							  <label for="q_chk5"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>Q3</td>
						<td>&#36;3,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd6" name="q_rd1"> 
								<label for="p_rd6"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk6">
							  <label for="q_chk6"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>Q4</td>
						<td>&#36;5,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd7" name="q_rd1"> 
								<label for="p_rd7"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk7">
							  <label for="q_chk7"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>Q5</td>
						<td>&#36;7,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd8" name="q_rd1"> 
								<label for="p_rd8"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk8">
							  <label for="q_chk8"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>Q6</td>
						<td>&#36;10,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd9" name="q_rd1"> 
								<label for="p_rd9"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk9">
							  <label for="q_chk9"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
					<tr>
						<td>Q7</td>
						<td>&#36;20,000</td>
						<td>
							<div class="radio_box"> 
								<input type="radio" id="p_rd10" name="q_rd1"> 
								<label for="p_rd10"></label> 
							</div>
						</td>
						<td>
							<div class="round_chkbox">
							  <input type="checkbox" id="q_chk10">
							  <label for="q_chk10"><span></span></label>
							</div>
						</td>
						<td>X</td>
					</tr>
				</table>
				<p>
					직급유지와 직급 수당, 공유 수당을 받으려면 Q 팩을 구매해야 합니다.<br/>
					구매일로 부터 30일간 유효합니다.<br/>
					한번에 한 개의 상품만 살 수 있습니다.<br/>
					자동 재구매를 활성화하면 매월 자동으로 재구매가 됩니다.
				</p>
			</div>
			
			<div class="btn2_wrap">
				<input type="button" value="취소" onclick="history.back();">
				<input type="button" value="다음단계" onclick="location.href='purchase_1.php'">
			</div>

		</section>

		<?php include '_include/popup.php'; ?>
		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='_images/top_purchase.png' alt='아이콘'> 팩 상품 구매하기");
			$('#wrapper').css("background", "#fff");

		});
	</script>



</body></html>
