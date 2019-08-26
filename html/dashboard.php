<?php include '_include/head.php'; ?>
<?php include '_include/gnb.php'; ?>

<!-- bxslider -->
	<link rel="stylesheet" href="_common/css/jquery.bxslider.css">
	<script src="_common/js/jquery.bxslider.min.js"></script>

		<div class="v_center dash_contents">

			<section class="dash_news">
				<h5>공지사항 <img class="close_news f_right" src="_images/close_round.gif" alt="공지사항 닫기"></h5>
				<div>
					공지사항입니다 공지사항 공지사항
				</div>
			</section>

			<section class="dash_wallet">
				<h5>현재 지갑 잔고 상황</h5>
				<div>
					<div>
						<canvas id="myChart"></canvas>
					</div>
					<ul class="clear_fix">
						<li class="bit">
							<a href="transaction_bit.php">
								<strong>Bitcoin</strong>
								<p>&#36;124.36</p>
								<span>0.01802328 BTC</span>
							</a>
						</li>
						<li class="eth">
							<a href="transaction_eth.php">
								<strong>Ether</strong>
								<p>&#36;0.00</p>
								<span>0 ETH</span>
							</a>
						</li>
						<li class="roc">
							<a href="transaction_rock.php">
								<strong>Rockwood</strong>
								<p>&#36;0.00</p>
								<span>0 RWD</span>
							</a>
						</li>
						<li class="look">
							<a href="transaction_look.php">
								<strong>Lookei</strong>
								<p>&#36;0.00</p>
								<span>0 LEKI</span>
							</a>
						</li>
						<li class="v7">
							<a href="transaction_v7.php">
								<strong>V7</strong>
								<p>&#36;0.00</p>
								<span>0 V7</span>
							</a>
						</li>
					</ul>
				</div>
			</section>

			<section class="dash_price">
				<h5>코인 가격 차트</h5>
				<div>
					<span>BITCOIN PRICE</span>
					<strong>&#36;6,894.34</strong>
					<p><img src="_images/bit_round.gif" alt="BITCOIN"> SEE CHARTS</p>
				</div>

				<div>
					<span>ETHER PRICE</span>
					<strong>&#36;186.24</strong>
					<p><img src="_images/eth_round.gif" alt="ETHER"> SEE CHARTS</p>
				</div>

				<div>
					<span>ROCKWOOD COIN PRICE</span>
					<strong>&#36;315.54</strong>
					<p><img src="_images/rock_round.gif" alt="ROCKWOOD"> SEE CHARTS</p>
				</div>

				<div>
					<span>LOOKEI PRICE</span>
					<strong>&#36;0.0054</strong>
					<p><img src="_images/look_round.gif" alt="LOOKIE"> SEE CHARTS</p>
				</div>

				<div>
					<span>V7 TOKEN PRICE</span>
					<strong>&#36;0.0054</strong>
					<p><img src="_images/v7_round.gif" alt="v7토큰"> SEE CHARTS</p>
				</div>
			</section>


			<section class="dash_business">
				<h5>비즈니스 현황</h5>
				<div>
					<img src="_images/busi1.gif" alt="아이콘">
					<p>Coolrunning</p>
					<span>나의 추천인</span>
				</div>
				<div>
					<img src="_images/busi2.gif" alt="아이콘">
					<p>Coolrunning</p>
					<span>나의 후원인</span>
				</div>
				<div>
					<img src="_images/busi3.gif" alt="아이콘">
					<p>100BTC / 100ETH</p>
					<span>입금한 금액</span>
				</div>
				<div>
					<img src="_images/busi4.gif" alt="아이콘">
					<p>10,000 V7 / $4,500</p>
					<span>나의 소득</span>
				</div>
				<div>
					<img src="_images/busi5.gif" alt="아이콘">
					<p>$1,500,000</p>
					<span>산하 매출액</span>
				</div>
				<div>
					<img src="_images/busi6.gif" alt="아이콘">
					<p>1 Created / $2900 Saved</p>
					<span>아바타 갯수와 적금잔고</span>
				</div>
			</section>

		</div>


		<div class="bx_wrap">
			<p>x</p>
			<div class="bxslider">
				<div>
					<p>BITCOIN PRICE</p>
					<div>
						<span>$7,745.87</span>
						<small>&#9650; 2.28%</small>
					</div>
					<img src="_images/graph.jpg">
				</div>
				<div>
					<p>ETHER PRICE</p>
					<div>
						<span>$7,745.87</span>
						<small>&#9650; 2.28%</small>
					</div>
					<img src="_images/graph.jpg">
				</div>
			</div>
		</div>
		

		<?php include '_include/popup.php'; ?>
		<div class="gnb_dim"></div>

	</section>




	<!-- 차트	-->
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	<script>
		window.onload = function() {
			var ctx = document.getElementById("myChart");
			var doughnutChart = new Chart(ctx, {
				type: 'doughnut',
				data: {
					labels: ["Bitcoin", "Ethereum", "Rockwood", "Lookie","V7"],
					datasets: [{
						backgroundColor: ["#ff9b22", "#473bcb", "#3edc89", "#fd8def", "#07b5e5"],
						data: [95, 2, 5, 1, 3]
					}]
				},
				options: {
					legend: {
						display: false
					}
				}
			});
		}
	</script>
	

	<script>
		$(function(){
			
			$('.dash_price p').click(function(){
				$('.bx_wrap').css("visibility","visible");
				$('.dim').css("display","block");
				$('body').css({"overflow":"hidden","height":"100%"});
			});
			$('.bx_wrap>p').click(function(){
				$('.bx_wrap').css("visibility","hidden");
				$('.dim').css("display","none");
				$('body').css({"overflow":"auto","height":"inherit"});
			});
			// bxslider
			$('.bxslider').bxSlider({
				autoControls: true,
				stopAutoOnClick: true,
				pager: true,
				responsive:true
			});
			
			
			
			// 공지사항 닫기
			$('.close_news').click(function(){
				$('.dash_news').css("display","none");
			});
		});
	</script>



</body>
</html>
