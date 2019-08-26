<?php
	include_once('./_common.php');
	
	/* 개별 서비스 사용설정*/
	$sql = " select * from maintenance";
	$nw = sql_fetch($sql);
	
	if($nw['nw_with'] == 'Y'){
		$nw_with = 'Y';
	}else{
		$nw_with = 'N';
	}

	
?>

<!DOCTYPE html>
<html>

<head>
  <?include_once('common_head.php')?>
  <link rel="stylesheet" href="css/style.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/js-sha256/0.9.0/sha256.js" type="text/javascript"></script>
  <script>
    $(function () {
      var mb_block = Number("<?=$member['mb_block']?>");
      var search = $('#recipient').val();
      var mb_id = '<?=$member["mb_id"]?>';
      var auth_mail_code = '';
      var nw_with = '<?=$nw_with?>';

        //출금
      $('#eos_withd_EOS').on('click', function () {
		 console.log('withdraw click!');

        if (!mb_block && nw_with != "Y") {
          $.ajax({
            type: "POST",
            url: "./coin_trans_proc.php",
            cache: false,
            async: false,
            dataType: "json",
            data: {
              func: 'withdraw_eos',
              wallet_addr: $('#withdrawal-address').val(),
			  wallet_addr_memo :  $('#withdrawal-address-memo').val(),
              auth_code: $('#otp_auth_with').val(),
			  auth_mail_code: auth_mail_code,
              mb_id: mb_id,
              amt: $('#sendCoin').val()
            },
            success: function (data) {
              //alert(data.result);
              

              if (data.result == "OK") {
				  //console.log(data.result);
                $('#withdrawBitcoin').modal('show');
              }else{
				commonModal('Error', data.result, 80);
				}
            }
          });
        } else {
          commonModal('<strong>Not available right now</strong>',
            '<i class="fas fa-exclamation-triangle red"></i><h4>Try again later</h4>');
        }
      });
	
	  /*출금액 입력*/
      $('#sendCoin').on('change', function () {
        var n = $(this).val();
        if (n % 5 != 0) {
          commonModal('Check Upstair input!',
            "It's only available in five units<br> ex) 5, 10, 20, 35, 100 ...", 80);
        } else {
          $(this).val(n);
        }
      });


      /*메일 인증코드 발송*/
      $('#sendMail').on('click', function (e) {
        var mb_email = '<?=$member[mb_email]?>';
        console.log('sendmail');

        $.ajax({
          url: '/bbs/register.mail.otp.php',
          type: 'GET',
          async: false,
          data: {
            "mb_email": mb_email
          },
          dataType: 'json',
          success: function (result) {
            //console.log(result);
            key = result.key;
            console.log(key);

            commonModal('EOS Withdrawal OTP authentication',
              '<p>Send a authentication code to your mail.</p>', 80);
          },
          error: function (e) {
            //console.log(e);
          }
        });
        $('#sendMail_auth_with').show();
        $('#verify').show();

      });

	
	  /* OTP 인증 */
      $('#verify').on('click', function (e) {

        if ($('#sendMail_auth_with').val()) {
          if (key == sha256($('#sendMail_auth_with').val().trim())) {
            console.log("key OK!!!");
            //commonModal('EOS Withdrawal OTP authentication','<p>Send a authentication code to your mail.</p>',80);

            $('.otp-auth-code-container').hide();
            auth_mail_code = 'ok';

          } else {
            commonModal('Do not match',
              '<p>Email verification code is incorrect. Please enter the correct code</p>', 80);
          }
        } else {
          commonModal('Do not match',
            '<p>Email verification code is incorrect. Please enter the correct code</p>', 80);
        }
      });

	  /* OTP 인증 */
      $('#otp_open').on('click', function (e) {
        $('.verifyContainer').show();
        $('.verifyContainerOTP').hide();
      });
		
	  /* OTP 메일 */
      $('#mail_otp_open').on('click', function (e) {
        $('.verifyContainer').hide();
        $('.verifyContainerOTP').show();
      });

	  /* 초기설정 */
      $('.verifyContainer').hide();
      $('#sendMail_auth_with').hide();
      $('#verify').hide();

    });
  </script>

  <style>
    .account {
      background: lightslategrey;
      margin-bottom: 1em;
    }

    .wallet {
      width: 95%;
    }

    .account_w {
      background: steelblue;
    }

    hr {
      width: 95%;
      border-top-style: dashed
    }
  </style>

</head>



<body>
  <?include_once('mypage_head.php')?>

  <div class="main-container">
    <div id="body-wrapper" class="big-container-wrapper">
      <div class="crypto-wallets-container">
        <h2 class="gray" data-i18n="walletETBC.title">ETBC Wallet</h2>

        <!-- 지갑 주소 -->
        <section class="account">

          <p class="gtt_title" data-i18n="walletETBC.addr">My EOS Wallet Address</p>

          <p class="account_tit" data-i18n="walletETBC.addr_ac">Account Address</p>
          <p class="eos_memo" id="eos_wallet"> eosblockteam </p>
          <p class="account_cp" id="walletCopy" onclick='copyToClipboard1("#eos_wallet")'> <span
              data-i18n="walletETBC.copy_ac">Copy Address</span><i class="far fa-clone"></i></p>


          <p class="account_tit" data-i18n="walletETBC.addr_memo">MEMO Address</p>
          <p class="eos_memo" id="eos_memo">
            <?echo $member['mb_id']?>
          </p>
          <p class="account_cp" id="accountCopy" onclick='copyToClipboard2("#eos_memo")'> <span
              data-i18n="walletETBC.copy_memo">Copy MEMO</span><i class="far fa-clone"></i></p>
        </section>


        <script type="text/javascript">
          function copyURL() {
            alert($('#token_addr').val() + " 지갑 주소가 복사 되었습니다");
            document.getElementById("token_addr").select();
            document.execCommand("copy");
          }
        </script>
        <!-- 지갑 주소 -->



        <hr>
        <!-- //BALANCE -->
        <section class="wallet">
          <div class="wallet_inner">
            <p class="balance_tit" data-i18n="walletETBC.balance_title">My Balance</p>
            <h3 class="balance">
              <span data-i18n="walletETBC.total_balance"> Total Balance </span>
              <span class="total_balance"> <?=$EOS_TOTAL?> </span>
              EOS
            </h3>

            <div class="coin_list">
              <div class="coin_img">
                <img src="./images/eos_logo_c.png" alt="">
                <p class="coin_name">EOS</p>
              </div>
              <div class="eos_balance">
                <p> <?=$EOS_TOTAL?> EOS</p>
              </div>
            </div>

            <div class="coin_list">
              <p class="eos_up_name">UPSTAIRS</p>
              <div class="eos_up_balance">
                <p> <?=$upstair_eos;?> EOS</p>
              </div>
            </div>
          </div>
        </section>
        <!-- //BALANCE -->

        <style>
          .eos_title,
          .coin_title {
            font-weight: 600
          }

          .eos_account {
            width: 90%;
          }

          .eos_account+p {
            margin-top: 30px;
          }

          .input_address,
          .input_shift_value {
            border-radius: 15px;
            margin: 20px 0;
            padding: 20px 10px;
          }

          .input_address {
            background: rgba(255, 255, 255, 0.2)
          }

          .input_shift_value {
            background: rgba(0, 0, 0, 0.3);
            padding: 20px 10px;
          }

          .input_shift_value .coin_title {
            margin-top: 0;
          }

          .otp-auth-code-container {
            width: 90%;
            margin: 0 auto;
            text-align: center;
            background: #333;
            padding: 20px 0;
            border-radius: 15px;
          }

          .verifyContainerOTP {
            width: 80%;
            margin: 0 auto;
          }

          .otp-auth-code-container i.fas {
            color: white;
          }

          .otp-auth-code-container .trans_input {
            background: transparent;
            border: 0;
            border-bottom: 1px solid #ccc;
            padding: 0 10px;
            color: white;
            letter-spacing: -1px;
            line-height: 2em;
          }

          /*.form-send-button{height:60px;}*/
          hr.hr_w {
            border-top-color: white;
          }

          hr.hr_w+div {
            margin-top: 1em;
          }

          .open_guide {
            color: #f9a62e !important;
            font-size: 13px;
            text-decoration: underline;
            margin: 20px 0 0;
            display: block;
            border: 1px solid #f9a62e;
            padding: 10px 0;
          }

          .verifyContainer {
            margin: 0 20px;
          }

          #sendMail_auth_with {
            margin: 15px 0
          }
        </style>

        <!-- WITHDRAW -->
        <section class="account_w">
          <div class="account_inner">

            <p class="eos_title" data-i18n="walletETBC.withdraw_address">Withdrawal EOS Address</p>
            <div class="input_address">
              <p class="account_tit_2" data-i18n="walletETBC.withdraw_address_ac">Account Address</p>
              <input type="text" id="withdrawal-address" class="eos_account" placeholder="Enter the Account address.">

              <p class="account_tit_2" data-i18n="walletETBC.withdraw_address_memo">MEMO Address</p>
              <input type="text" id="withdrawal-address-memo" class="eos_account" placeholder="Enter the MEMO address.">
            </div>


            <p class="coin_title" data-i18n="walletETBC.balance_title_count">Withdrawal EOS quantity</p>
            <div class="input_shift_value">
              <input type="text" id="sendCoin" class="send_coin" placeholder="Enter Withdrawal quantity">
              <span>EOS</span>
            </div>

            <hr class="hr_w">

            <div class="otp-auth-code-container">

              <div class="verifyContainerOTP">
                <i class="fas fa-key"></i>
                <input type="text" id="otp_auth_with" class="trans_input " name="otp auth code"
                  placeholder="OTP Authorization Code" maxlength="6"
                  onKeypress="if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                  style="IME-MODE:disabled;">
                <a id="otp_open" class="open_guide">Or you can use e-mail code.</a>
              </div>

              <div class="verifyContainer">
                <button type="button" class="btn btn-primary" id="sendMail" data-toggle="modal" data-target="">Send
                  E-Mail OTP code</button>

                <input type="text" id="sendMail_auth_with" class="trans_input" name="sendMail_auth_with"
                  placeholder="Mail Authorization Code" maxlength="8"
                  onKeypress="if(event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;"
                  style="IME-MODE:disabled;">

                <button type="button" class="btn btn-primary form-send-button" id="verify" data-toggle="modal"
                  data-target="">Verify Check!</button>
                <a id="mail_otp_open" class="open_guide"> Or Use OTP Application code.</a>
              </div>

            </div>

            <div class="send-button-container">
              <button type="button" class="btn btn-primary form-send-button" style="height:60px;" id="eos_withd_EOS"
                data-toggle="modal" data-target="">Withdrawal EOS</button>
            </div>
          </div>
        </section>
		


		<style>
			.history_box{width:95%;margin:0 auto;}
			.row1_left{width:60%}
			.row2_left{width:20%;}
			.row2_right{width:80%;padding-left:5px;}
			.hist_name{font-weight:600;}
			.hist_td{font-weight:600;font-size:0.8em;}
			.hist_value{font-size:1.2em;}
			.hist_date{background:#f5f5f5; border-radius:10px; padding:2px 10px;color:black;}
			.hist_td.addr{}
			.pg_page, .pg_current{color:white;}
			.pg_current{color:black}
		</style>

		
		<?
			/*날짜선택 기본값 지정*/
			if (empty($fr_date)) {$fr_date = date("Y-m-d", strtotime(date("Y-m-d")."-3 month"));}
			if (empty($to_date)) $to_date = G5_TIME_YMD;

			/*날짜계산*/
			$qstr = "stx=".$stx."&fr_date=".$fr_date."&amp;to_date=".$to_date;
			$query_string = $qstr ? '?'.$qstr : '';

			$sql_common ="FROM pinna_eos_trans";
			$sql_search = " WHERE mb_id = '{$member[mb_id]}' ";
			$sql_search .= " AND create_dt between '{$fr_date}' and '{$to_date}' ";
			
			$sql = " select count(*) as cnt
					{$sql_common}
					{$sql_search} ";
			//print_r($sql);
			$row = sql_fetch($sql);
			$total_count = $row['cnt']; 

			$rows = 20; //한페이지 목록수
			$total_page  = ceil($total_count / $rows);
			if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지
			$from_record = ($page - 1) * $rows; // 시작 열

			$sql = " select *
					{$sql_common}
					{$sql_search}
					order by create_dt desc
					limit {$from_record}, {$rows} ";
			$result = sql_query($sql);

			//print_R($sql );
		

			function string_shift_code($val){
				switch ($val) {
					case "R" :
						echo "Request";
						break;
					case "Y" :
						echo "Complete";
						break;
					case "S" :
						echo "Processing";
						break;
					case "N" :
						echo "Reject";
						break;
					default :
						echo "Processing";
				}
			}	
		?>

        <!-- WITHDRAWAL HISTORY -->
        <section class="history_box">
          <h3 class="hist_tit">Widrawal History</h3>
			
		 <?while( $row = sql_fetch_array($result) ){?>
          <div class="hist_con">
            <div class="hist_con_row1">
              <div class="row1_left">
                <span class="hist_name">Withdrawal</span><br>
                <span class="hist_date"><?=$row['create_dt']?></span>
              </div>
              <div class="row1_right">
                <span class="hist_value"><strong><?=Number_format($row['amt'],3)?></strong> EOS</span>
              </div>
            </div>

            <div class="hist_con_row2">
              <div class="row2_left">
                <span class="hist_th">Address</span>
                <span class="hist_th">Memo</span>
                <span class="hist_th">Status</span>
              </div>
              <div class="row2_right">
                <span class="hist_td"><?=$row['addr']?></span>
                <span class="hist_td addr"><?= $row['addrmemo'] ? $row['addrmemo'] : "-" ?></span>
                <span class="hist_td"><?string_shift_code($row['status'])?></span>
              </div>
            </div>
        </div>
		<?}?>

		<?php
			$pagelist = get_paging($config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr");
			echo $pagelist;
		 ?>



<!--
            <div class="hist_con">
            <div class="hist_con_row1">
              <div class="row1_left">
                <span class="hist_name">Withdrawal</span>
                <span class="hist_date">2019.07.04 10:15</span>
              </div>
              <div class="row1_right">
                <span class="hist_value">1,000,000 EOS</span>
              </div>
            </div>
            <div class="hist_con_row2">
              <div class="row2_left">
                <span class="hist_th">Address</span>
                <span class="hist_th">Memo</span>
                <span class="hist_th">Status</span>
              </div>
              <div class="row2_right">
                <span class="hist_td">eosblockteam</span>
                <span class="hist_td">usernameusernameusername</span>
                <span class="hist_td">Rejected</span>
              </div>
            </div>
            </div>
            <div class="hist_con">
            <div class="hist_con_row1">
              <div class="row1_left">
                <span class="hist_name">Withdrawal</span>
                <span class="hist_date">2019.07.04 10:15</span>
              </div>
              <div class="row1_right">
                <span class="hist_value">1,000,000 EOS</span>
              </div>
            </div>
            <div class="hist_con_row2">
              <div class="row2_left">
                <span class="hist_th">Address</span>
                <span class="hist_th">Memo</span>
                <span class="hist_th">Status</span>
              </div>
              <div class="row2_right">
                <span class="hist_td">eosblockteam</span>
                <span class="hist_td">usernameusernameusername</span>
                <span class="hist_td">Rejected</span>
              </div>
            </div>
            </div>
            <div class="hist_con">
            <div class="hist_con_row1">
              <div class="row1_left">
                <span class="hist_name">Withdrawal</span>
                <span class="hist_date">2019.07.04 10:15</span>
              </div>
              <div class="row1_right">
                <span class="hist_value">1,000,000 EOS</span>
              </div>
            </div>
            <div class="hist_con_row2">
              <div class="row2_left">
                <span class="hist_th">Address</span>
                <span class="hist_th">Memo</span>
                <span class="hist_th">Status</span>
              </div>
              <div class="row2_right">
                <span class="hist_td">eosblockteam</span>
                <span class="hist_td">usernameusernameusername</span>
                <span class="hist_td">Rejected</span>
              </div>
            </div>
            </div>
            <div class="hist_con">
            <div class="hist_con_row1">
              <div class="row1_left">
                <span class="hist_name">Withdrawal</span>
                <span class="hist_date">2019.07.04 10:15</span>
              </div>
              <div class="row1_right">
                <span class="hist_value">1,000,000 EOS</span>
              </div>
            </div>
            <div class="hist_con_row2">
              <div class="row2_left">
                <span class="hist_th">Address</span>
                <span class="hist_th">Memo</span>
                <span class="hist_th">Status</span>
              </div>
              <div class="row2_right">
                <span class="hist_td">eosblockteam</span>
                <span class="hist_td">usernameusernameusername</span>
                <span class="hist_td">Rejected</span>
              </div>
            </div>        


          </div>
-->

        </section>
   
      </div>
      </form>

    </div>

  </div>
  </div>
  </div>

  <div class="modal fade" id="ethereumAddressModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="ethereumAddressModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ethereumAddressModalLongTitle">EOS WALLET</h5>
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

  <!--출금완료시 메세지-->
  <div class="modal fade" id="withdrawBitcoin" tabindex="-1" role="dialog"
    aria-labelledby="withdrawBitcoinModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="withdrawBitcoinModalLongTitle">EOS Withdrawal</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <i class="far fa-check-circle"></i>
          <h4>Your EOS has been successfully withdrawn.</h4>
          <p>Please allow up to 72 hours for the transaction to complete.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="transferBitcoin" tabindex="-1" role="dialog"
    aria-labelledby="transferBitcoinModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="transferBitcoinModalLongTitle">EOS Transfer</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <i class="far fa-check-circle"></i>
          <h4>Your EOS has been successfully transferred.</h4>
          <p>Please allow up to 72 hours for the transaction to complete.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>





  <!--xx Copy clipboad -->
  <script>
    function copyToClipboard1(element) {
      alert("Your Wallet adress is copied!");
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }

    function copyToClipboard2(element) {
      alert("Your MEMO is copied!");
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }
  </script>
</body>

</html>