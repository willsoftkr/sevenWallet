<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<link rel="stylesheet" href="css/level_structure/style.css">

	<script>
		$(function() {

			var menu_dropdown = document.getElementsByClassName('lvl');

			for (var i = 0; i < menu_dropdown.length; i++) {
				menu_dropdown[i].onclick = function() {
					this.classList.toggle('lvl-is-open');

					var menu_item = this.nextElementSibling;

					if (menu_item.style.maxHeight) {
						menu_item.style.maxHeight = null;
					} else {
						menu_item.style.maxHeight = menu_item.scrollHeight + "px";
					}
				}
			}
		});
	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div id="overlay">
		<div id="text">
			<h2>Your browser is too small.</h2>
			<p>Level structure view works best on browsers that are at least 1235px wide.</p>
		</div>
	</div>

	<div class="main-container">		
		<div class="big-container-wrapper">
			<h2 class="gray level-structure-title">Level Structure</h2> <span class="gray">( 999 Total )</span>

			<div class="lvl-container lvl-one">
				<div class="lvl">
					<img src="./images/6star.png" alt="6 star"> 
					<span class="lvl-username">IronMan</span> 
					<span class="gray">( 42 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">6 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>			
			</div>

			<div class="lvl-container lvl-two">			
				<div class="lvl">
					<img src="./images/5star.png" alt="5 star"> 
					<span class="lvl-username">CaptainAmerica</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">5 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-three">			
				<div class="lvl">
					<img src="./images/4star.png" alt="4 star"> 
					<span class="lvl-username">Spiderman</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">4 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-four">			
				<div class="lvl">
					<img src="./images/3star.png" alt="3 star"> 
					<span class="lvl-username">CaptainAmerican</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">3 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-five">			
				<div class="lvl">
					<img src="./images/2star.png" alt="2 star"> 
					<span class="lvl-username">BlackPanther</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">2 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-six">			
				<div class="lvl">
					<img src="./images/1star.png" alt="1 star"> 
					<span class="lvl-username">DoctorStrange</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">1 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-seven">			
				<div class="lvl">
					<img src="./images/2star.png" alt="2 star"> 
					<span class="lvl-username">Thor</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">2 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-eight">			
				<div class="lvl">
					<img src="./images/3star.png" alt="3 star"> 
					<span class="lvl-username">AntMan</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">3 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-nine">			
				<div class="lvl">
					<img src="./images/4star.png" alt="4 star"> 
					<span class="lvl-username">Hulk</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">4 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>

			<div class="lvl-container lvl-ten">			
				<div class="lvl">
					<img src="./images/5star.png" alt="5 star"> 
					<span class="lvl-username">BlackWidow</span> 
					<span class="gray">( 16 )</span>			
				</div>
				<div class="lvl-info">
					<div class="card-info-left">
						<span class="gray">Name:</span> <span class="blue">Avengers</span> <br>
						<span class="gray">Enrollment Date:</span> <span class="blue">Jan 01, 2018</span>  <br>
						<span class="gray">Sponsor:</span> <span class="blue">Thanos</span>  <br>
						<span class="gray">Rank:</span> <span class="blue">5 Star</span>  <br>
						<span class="gray">Status:</span> <span class="blue">Active</span>  <br>
						<span class="gray">Email:</span> <span class="blue">example@email.com</span> 
					</div>
					<div class="card-info-right">
						<span class="gray">Mining Pools</span> <br>
						<span class="gray">Pool 1:</span> <span class="blue">4</span> <br>
						<span class="gray">Pool 2:</span> <span class="blue">3</span> <br>
						<span class="gray">Pool 3:</span> <span class="blue">1</span> <br>
						<span class="gray">Pool 4:</span> <span class="blue">2</span> <br>
						<span class="gray">GPU:</span> <span class="blue">1</span>
					</div>
				</div>
			</div>




		</div>
	</div>
</body>
</html>
