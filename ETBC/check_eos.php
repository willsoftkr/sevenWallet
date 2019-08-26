

<script src='https://cdn.jsdelivr.net/npm/eosjs@16.0.8/lib/eos.min.js'
        integrity='sha512-zhPSKFEBlDVvUzjl9aBS66cI8tDYoLetynuKvIekHT8NZZ12oxwcZ//M/eT/2Rb/pR/cjFvLD8104Cy//sdEnA=='
        crossorigin='anonymous'></script>
<script src='https://code.jquery.com/jquery-3.3.1.js' integrity='sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60='
		crossorigin='anonymous'></script>
		<script src='./js/eosjs.js'> </script>
	<script type='text/javascript'>

	$(function(){
		
		const api = new Eos({httpEndpoint: 'https://eos.greymass.com', chainId: 'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906'});
		api.getActions('eosblockteam','<?=$start_pos?>',10, (error,action)=> {
			
			for(var i = 0 ; i <= 10; i++) {
				//console.log(action.actions[i].action_trace.act.data.quantity);
				//console.log(action.actions[i].action_trace);
			
				
				
				$.ajax({
					type: 'POST',
					url: 'http://202.239.26.187/ETBC/update_coin_point.php',
					async: true,
					dataType: 'json',
					data:  {
						'amt' : action.actions[i].action_trace.act.data.quantity,
						'mb_id' : action.actions[i].action_trace.act.data.memo,
						'timestamp' : action.actions[i].action_trace.block_time, 
						'coin_symbol' : 'EOS'
					},
					success: function(data) {
						console.log('success :' + data);
					},
					error:function(error){
						console.log('error : ' + error);
					}
				});
							
				
			}
		});

	});

</script>

