<?include_once("./_common.php");?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
.container {
	margin:0;
	padding:0;
	width:100%;
	display:block; 
	height:100vh; 
	background:url('./images/launcher.jpg') no-repeat 50% 50%;
	background-size:cover;
}

#btnDiv {
  display: none;
  text-align: center;
  position:absolute;
  bottom:10%;
  width:100%;
  z-index:1000;
}



#myProgress {
  width: 100%;
}

#myBar {
  width: 1%;
  height: 2px;
  background-color: #faa731;
}
.btn{
	width:80%;
	margin:0 auto;
	height:50px;
	display:block;
	background:#fff;
	text-decoration:none;
	line-height:50px;
	border-radius:5px;
	font-family:"맑은 고딕",Malgun-gothic;
}

.btn + .btn{margin-top:20px;}

.login_btn{
	color:white;
	font-weight:600;
	background:linear-gradient(to right,#fd4964,#f9a62e);
}

.signup_btn{
	color:black;
	font-weight:600;
	background:#f5f5f5
}


.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-10%; opacity:0 } 
  to { bottom:10%; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-10%; opacity:0 } 
  to{ bottom:10%; opacity:1 }
}



@media screen and (max-width: 1600px) {
	
}

@media screen and (max-width: 1200px) {

}

@media screen and (max-width: 1024px) {
	
}
@media screen and (max-width: 993px) {

}

@media screen and (max-width: 767px){
	
}

@media screen and (max-width: 736px) {
	
}


@media (max-width: 414px) {
	
}

@media (max-width: 650px) {
	
}

@media (max-width: 768px) {
	
}

@media (min-width: 768px) {
	body{background:#f5f5f5}
	.container{width:767px;margin:0 auto;}
	#btnDiv{width:767px;}
}






</style>


<script >
	var myVar;

	function myFunction() {
	  move()
	}

	function showPage() {
	  document.getElementById("myBar").style.display = "none";
	  document.getElementById("btnDiv").style.display = "block";
	}

	function move() {
	  var elem = document.getElementById("myBar");   
	  var width = 1;
	  var id = setInterval(frame, 5);
	  function frame() {
		if (width >= 100) {
		  clearInterval(id);
		  showPage();
		} else {
		  width++; 
		  elem.style.width = width + '%'; 
		}
	  }
	}
</script>


</head>

<body onload="myFunction();" style="margin:0;">
<div class="container">
	<div id="myBar"></div>

	<div id="btnDiv" class="animate-bottom">
	  <a href="./dashboard.php" class="btn login_btn" onclick ="this.disable = true">LOGIN</a>
	  <a href="/bbs/register_form.php" class="btn signup_btn" onclick ="this.disable = true">SIGN UP</a>
	</div>
</div>

</body>
</html>
