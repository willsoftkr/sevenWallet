<?php
include_once('./_common.php');

$last_trans_num = "select idx from eos_coin_transfer_hist order by idx desc";
$last_idx = sql_fetch($last_trans_num);
if($last_idx == null){
	$start_pos = 0;
}else if($last_idx[idx]){
	$start_pos = $last_idx[idx];
}



echo 'query '.$last_trans_num;
echo '<br>';
echo 'start Point '.$start_pos;

?>
<script src="https://cdn.jsdelivr.net/npm/eosjs@16.0.8/lib/eos.min.js"
        integrity="sha512-zhPSKFEBlDVvUzjl9aBS66cI8tDYoLetynuKvIekHT8NZZ12oxwcZ//M/eT/2Rb/pR/cjFvLD8104Cy//sdEnA=="
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous"></script>
		<script src="./js/eosjs.js"> </script>
	<script type="text/javascript">
//교환
//var list
/*
회사 지갑 주소 : 수신할 주소
회원 지갑 주소 : 송금할 주소
회원 지갑 주소 primary key : 송금시 필요한 열쇠
송금할 금액
회원 아이디 (GTS로 올려 줘야함)
가스가 필요함 (etherium이 필요함)
*/
	$(function(){
		var m_spos = Number("<?echo $start_pos?>");
		

			const api = new Eos({httpEndpoint: 'https://eos.greymass.com', chainId: 'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906'});
			
			api.getActions('eosblockteam',0,10, (error,action)=> {

			if(error){
				console.log("NOTATA : " + error);
			}


			for(var i = 0 ; i <= 10; i++) {

				console.log(action.actions[i].action_trace.act.data.quantity);

				$.ajax({
					type: "POST",
					url: "./update_coin_point.php",
					async: true,
					dataType: "json",
					data:  {
						"amt" : action.actions[i].action_trace.act.data.quantity,
						"mb_id" : action.actions[i].action_trace.act.data.fullmemo,
						"coin_symbol" : 'EOS'
					},
					success: function(data) {
						console.log("success : " + data);
						break;
					},
					error:function(error){
						console.log("error : " +  error);
					}
				});
			}
			
		});
		
	});
</script>
