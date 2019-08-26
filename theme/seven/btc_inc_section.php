				<div class="color_block bit_block">
					<div class="clear_fix">
						<strong class="f_left">Bitcoin</strong>
						<b class="f_right"><?=number_format((round((int)(get_coin_currency(BTC))*$member[mb_btc_in_value],2)),2)?> USD</b>
					</div>
					<div class="clear_fix">
						<span class="f_left">=<?=get_coin_currency(BTC)?> USD</span>
						<p class="f_right"><?=$member[mb_btc_in_value]?> BTC</p>
					</div>
				</div>