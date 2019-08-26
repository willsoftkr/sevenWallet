<!DOCTYPE html>
<html lang="ko">
<?php
include_once('./_common.php');
include_once('common_head.php');
// 오류시 공히 Error 라고 처리하는 것은 회원정보가 있는지? 비밀번호가 틀린지? 를 알아보려는 해킹에 대비한것

$mb_no = trim($_GET['mb_no']);
$mb_id = trim($_GET['mb_id']);
$mb_nonce = trim($_GET['mb_nonce']);

// 회원아이디가 아닌 회원고유번호로 회원정보를 구한다.
$sql = " select mb_id, mb_lost_certify from {$g5['member_table']} where mb_no = '$mb_no' ";
$mb  = sql_fetch($sql);
if (strlen($mb['mb_lost_certify']) < 33)
   die("This page is invalid11");

// 인증 링크는 한번만 처리가 되게 한다.
sql_query(" update {$g5['member_table']} set mb_lost_certify = '' where mb_no = '$mb_no' ");

// 인증을 위한 난수가 제대로 넘어온 경우 임시비밀번호를 실제 비밀번호로 바꿔준다.
if ($mb_nonce === substr($mb['mb_lost_certify'], 0, 32)) {
    $new_password_hash = substr($mb['mb_lost_certify'], 33);
    sql_query(" update {$g5['member_table']} set mb_password = '$new_password_hash' where mb_no = '$mb_no' ");
}
else {
  die($mb_nonce);
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.0/normalize.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
		<link rel="stylesheet" href="css/manage_profile/style.css">
    <style>
@import url('https://fonts.googleapis.com/css?family=Montserrat');

    * {
	box-sizing: border-box;
    font-family: 'Montserrat', sans-serif;}
    
    .input_container{
        border:1px solid #ccc;
        width:500px;
        height:auto;
        margin:0 auto;
    }
    .logo-container {
	display: inline-block;
    height: 100%;
    width:100%;
    margin:0 auto;
    padding:20px;
	text-align: center;
}
.top-logo-icon {
	width: 20px;
}
.top-logo-text {
	color: #212121;
	font-size: 20px;
	margin-left: 5px;
	position: relative;
   top: 3px;
     font-weight: normal;
}
    .input_container .cp_title{
        text-align: center;
        font-size:20px;
        font-weight: normal;        
    }
    .input_container .id{
        text-align: center;
        font-size:18px;
        font-weight: normal;
        color:rgb(0, 121, 211);
    }
    .input_container .n_pw span{margin-left:50px;margin-right:35px;}
    .input_container .c_pw span{margin-left:50px;margin-right:10px;}
    .input_container .c_pw{margin-top:10px;}


    .input_container input{   
    border: 1px solid #e5e5e5;    
    margin-right: 30px;
    margin-bottom: 15px;
    padding: 10px 5px;
    width: 200px;
    }

    #password_save{
        cursor: pointer;
        text-align: center;
        background-color: rgb(0, 121, 211);
    border-color: rgb(0, 121, 211);
    margin-left: 180px;
    margin-top:20px;
    margin-bottom:30px;
    color:#fff;
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    </style>
    <title>FIJI Mining || Change Password</title>
	<script>
		$(function() {
				//비번 수정 버튼 클릭시
				$('#password_save').on('click', function() {
					
						var crrunt_pw = "<?=$new_password_hash?>";
						$.ajax({
						type: "POST",
						url: "manage_profile_proc.php",
						cache: false,
						async: false,
						dataType: "json",
						data:  {
							func : "change_password",
							mb_id : $('#mb_id').val(),
							temp_password : crrunt_pw,
							new_password : $('#new_password').val(),
							new_password2 : $('#new_password2').val(),
							
						},
						success: function(data) {
							if(data.result=="OK"){
								$('#profileSave').modal('show');
							}
							else{
								$('#profilenotSave').modal('show');
							}
						}
					});
				});			
			$('#su_close').on('click',function(){
				location.replace('../bbs/login.php');
			});

				
		});
	</script>
</head>
<body>

    <div class="input_container">  
            <div class="logo-container">
                    <img src="./images/logo.png" alt="FIJI logo" class="top-logo-icon">
                    <span class="top-logo-text">FIJI Mining</span>
			</div>         
           <h2 class="id"><?echo $mb_id;?></h2>
		   <input type="hidden" id="mb_id" value=<?echo $mb_id?> />
           <div class="n_pw" > <span>New Password</span> <input type="password" id="new_password" name="new_password" /> </div>
           <div class="c_pw" ><span>Confirm Password</span><input type="password" id="new_password2" name="new_password" /></div>
           <button id="password_save" class="btn btn-primary save-button" class="btn btn-primary">Save Changes</button>			
    </div>
	<div class="modal fade" id="profileSave" tabindex="-1" role="dialog" aria-labelledby="profileSaveModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="profileSaveModalLongTitle">Password change Information</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<i class="far fa-check-circle blue"></i>
			<h4>Your settings have been successfully saved.</h4>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="su_close"  data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>

	<div class="modal fade" id="profilenotSave" tabindex="-1" role="dialog" aria-labelledby="profileSaveModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="profileSaveModalLongTitle">Password change Information</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<i class="far fa-check-circle blue"></i>
			<h4>Your settings have been not successfully saved.</h4>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" id="failed_close" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
</body>
</html>