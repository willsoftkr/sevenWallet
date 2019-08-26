<?php
include_once('./_common.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?include_once('common_head.php')?>
		<link rel="stylesheet" href="css/mp_dashboard/style.css">
	</head>
<body>
<?include_once('mypage_head.php')?>
	<div class="main-container">		
		<div class="big-container-wrapper">
			<h2 class="gray">MP Dashboard</h2> 
			<div class="mp-dashboard-container shadow">
				<div class="input-group mb-3 wallet-address-container">
							  <input type="text" class="form-control" placeholder="Save BTC Wallet Address to get paid" aria-label=" BTC Wallet Address" aria-describedby="basic-addon2">
							  <div class="input-group-append">
							    <button class="btn btn-outline-primary save-button" type="button" data-toggle="modal" data-target="#bitcoinAddressModalCenter">Save</button>
							    <div class="modal fade" id="bitcoinAddressModalCenter" tabindex="-1" role="dialog" aria-labelledby="bitcoinAddressModalCenterTitle" aria-hidden="true">
							      <div class="modal-dialog modal-dialog-centered" role="document">
							        <div class="modal-content">
							          <div class="modal-header">
							            <h5 class="modal-title" id="bitcoinAddressModalLongTitle">BITCOIN WALLET</h5>
							            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
							              <span aria-hidden="true">&times;</span>
							            </button>
							          </div>
							          <div class="modal-body">
							            <i class="far fa-check-circle"></i>
							            <h4>Your wallet address has been saved.</h4>
							          </div>
							          <div class="modal-footer">
							            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							          </div>
							        </div>
							      </div>
							    </div>
							  </div>
							</div>
			</div><br> 
			<div class="mp-dashboard-container shadow">
				
				<table class="table table-striped">
					<tr>
						<th>No.</th>
						<th>Enrollment Date</th>
						<th>ID</th>
						<th>Name</th>
						<th>Commission ($)</th>
						<th>Commission (BTC)</th>
						<th>USD/BTC</th>
					</tr>
					<tr>
						<td>111</td>
						<td>01.01.18</td>
						<td>potus1</td>
						<td>George Washington</td>
						<td>30</td>
						<td>0.00478065</td>
						<td>6275.30</td>
					</tr>
					<tr>
						<td>222</td>
						<td>02.01.18</td>
						<td>potus2</td>
						<td>Thomas Jefferson</td>
						<td>30</td>
						<td>0.00478065</td>
						<td>6275.30</td>
					</tr>
					<tr>
						<td>333</td>
						<td>03.01.18</td>
						<td>potus3</td>
						<td>Abraham Lincoln</td>
						<td>30</td>
						<td>0.00478065</td>
						<td>6275.30</td>
					</tr>
					<tr>
						<td>444</td>
						<td>04.01.18</td>
						<td>potus4</td>
						<td>Bill Clinton</td>
						<td>30</td>
						<td>0.00478065</td>
						<td>6275.30</td>
					</tr>
					<tr>
						<td>555</td>
						<td>05.01.18</td>
						<td>potus5</td>
						<td>Barack Obama</td>
						<td>30</td>
						<td>0.00478065</td>
						<td>6275.30</td>
					</tr>
				</table>

				<div class="mp-stats-container">
					<div class="mp-stat-card">
						<span class="gray">Total Enrollments</span> <br>
						<span class="stat-bottom blue">42</span>
					</div>

					<div class="mp-stat-card">
						<span class="gray">Last Month's Enrollments</span> <br>
						<span class="stat-bottom blue">88</span>
					</div>

					<div class="mp-stat-card">
						<span class="gray">Current Month's Enrollments</span> <br>
						<span class="stat-bottom blue">19</span>
					</div>

					<div class="mp-stat-card">
						<span class="gray">Total Bonus</span> <br>
						<span class="stat-bottom blue">0.12345678 BTC</span>
					</div>

					<div class="mp-stat-card">
						<span class="gray">Last Month's Bonus</span> <br>
						<span class="stat-bottom blue">0.12345678 BTC</span>
					</div>

					<div class="mp-stat-card">
						<span class="gray">This Month's Bonus</span> <br>
						<span class="stat-bottom blue">0.12345678 BTC</span>
					</div>					
				</div>

				<div class="pagination-container">
					<nav aria-label="...">
					  <ul class="pagination pagination justify-content-center">
					  	<li class="page-item"><a class="page-link" href="#!">Start</a></li>
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
					    <li class="page-item"><a class="page-link" href="#!">End</a></li>
					  </ul>
					</nav>				
				</div>
			  <div class="page-search-container">
				  <input class="search-input" type="number" placeholder="Page">
				  <button>Search</button>			  	
			  </div>	
			</div>				
		</div>
	</div>


</body>
</html>
