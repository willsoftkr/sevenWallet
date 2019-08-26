<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/orderhistory/style.css">

	<script>
		$(function() {
			var poolPackage = document.getElementsByClassName('pool-package');

			$('.pool-package').on('click', function() {
				$(this).addClass('active-package').siblings().removeClass('active-package');
			});
		});
	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div class="main-container">
		
		<div class="big-container-wrapper">
			<div class="title-container">
				<h2 class="gray order-history-title">Order History</h2> <span class="gray">( 99 Total )</span>				
			</div>
			<div class="order-history-container shadow">
				<div class="pool-package-container">
					<div class="pool-package">
						<img src="./images/package_1.png" width="50" alt="package 1" />
						<br>
						3,400 GH/s
					</div>
					<div class="pool-package">
						<img src="./images/package_2.png" width="50" alt="package 2" />
						<br>
						10,200 GH/s
					</div>
					<div class="pool-package">
						<img src="./images/package_3.png" width="50" alt="package 3" />
						<br>
						17,100 GH/s
					</div>
					<div class="pool-package">
						<img src="./images/package_4.png" width="50" alt="package 4" />
						<br>
						40,800 GH/s
					</div>
					<div class="pool-package active-package">
						<img src="./images/package_gpu.png" width="50" alt="package gpu" />
						<br>
						80 MH/s
					</div>
				</div>

				<div class="order-history-table-container">
					<table class="table table-hover">
					  <tr>
					    <th>
					    	Invoice
					    </th>
					    <th>
					    	Date
					  	</th> 
					    <th>
					    	Ordered
					    </th>
					    <th>
					    	Amount Paid
					    </th>
					    <th>
					    	Paid By
					    </th>
					  </tr>
					  <tr>
					    <td>271995</td>
					    <td>01.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Purchase</td>
					  </tr>
					  <tr>
					    <td>398582</td>
					    <td>02.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Repurchase</td>
					  </tr>
					  <tr>
					    <td>961170</td>
					    <td>03.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Repurchase</td>
					  </tr>
					  <tr>
					    <td>977351</td>
					    <td>04.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Purchase</td>
					  </tr>
					  <tr>
					    <td>792011</td>
					    <td>05.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Purchase</td>
					  </tr>
					  <tr>
					    <td>704970</td>
					    <td>06.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Purchase</td>
					  </tr>
					  <tr>
					    <td>591168</td>
					    <td>07.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Repurchase</td>
					  </tr>
					  <tr>
					    <td>676833</td>
					    <td>08.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Repurchase</td>
					  </tr>
					  <tr>
					    <td>671838</td>
					    <td>09.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Repurchase</td>
					  </tr>
					  <tr>
					    <td>896323</td>
					    <td>10.01.18</td>
					    <td>$1000</td>
					    <td>0.12345678 ETH</td>
					    <td>Pool Purchase</td>
					  </tr>
					</table>
				</div>

				<div class="order-history-stats-container">					
					<div class="single-stat-container">
						<img src="./images/ethereum_repurchase.png" width="55" alt="total repurchases" />
						<br>
						<span class="big-blue-stat-font">3.14159265 ETH</span>
						<br>
						<span class="gray">Total Repurchases</span>
					</div>

					<div class="single-stat-container">
						<img src="./images/ethereum_hash_power.png" width="55" alt="total hash power" />
						<br>
						<span class="big-blue-stat-font">654,321 GH/s</span>
						<br>
						<span class="gray">Total Hash Power</span>
					</div>

					<div class="single-stat-container">
						<img src="./images/total_ethereum_earned.png" width="55" alt="total earned" />
						<br>
						<span class="big-blue-stat-font">12.47247923 ETH</span>
						<br>
						<span class="gray">Total Earned</span>
					</div>
				</div>

				<div class="pagination-container">
					<nav aria-label="...">
					  <ul class="pagination pagination-lg">
					    <li class="page-item">
					      <a class="page-link" href="#!" tabindex="-1"><</a>
					    </li>
					    <li class="page-item"><a class="page-link" href="#!">1</a></li>
					    <li class="page-item"><a class="page-link" href="#!">2</a></li>
					    <li class="page-item"><a class="page-link" href="#!">3</a></li>
					    <li class="page-item"><a class="page-link" href="#!">4</a></li>
					    <li class="page-item"><a class="page-link" href="#!">5</a></li>
					    <li class="page-item"><a class="page-link" href="#!">6</a></li>
					    <li class="page-item"><a class="page-link" href="#!">7</a></li>
					    <li class="page-item"><a class="page-link" href="#!">8</a></li>
					    <li class="page-item"><a class="page-link" href="#!">9</a></li>
					    <li class="page-item"><a class="page-link" href="#!">10</a></li>
					    <li class="page-item">
					      <a class="page-link" href="#!">></a>
					    </li>
					  </ul>
					</nav>					
				</div>

			</div>
		</div>

	</div>
</body>
</html>
