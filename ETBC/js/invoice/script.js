$(function() {
	$('.tab-panels .tabs li').on('click', function() {

		if ($(this).attr('rel') === 'bitcoinPanel') {
			$('.invoice-amounts-eth, .invoice-amounts-hm').fadeOut(0, function() {
				$('.invoice-amounts-eth, .invoice-amounts-hm').removeClass('active')

				$('.invoice-amounts-btc').fadeIn(300, function() {
					$('.invoice-amounts-btc').addClass('active')					
				})
			})
		} else if ($(this).attr('rel') === 'hashmaxxPanel') {
			$('.invoice-amounts-eth, .invoice-amounts-btc').fadeOut(0, function() {
				$('.invoice-amounts-eth, .invoice-amounts-btc').removeClass('active')

				$('.invoice-amounts-hm').fadeIn(300, function() {
					$('.invoice-amounts-hm').addClass('active')					
				})
			})
		} else {
			$('.invoice-amounts-hm, .invoice-amounts-btc').fadeOut(0, function() {
				$('.invoice-amounts-hm, .invoice-amounts-btc').removeClass('active')

				$('.invoice-amounts-eth').fadeIn(300, function() {
					$('.invoice-amounts-eth').addClass('active')					
				})
			})
		}


		$('.tab-panels .tabs li.active').removeClass('active')
		$(this).addClass('active')
		var panelToShow = $(this).attr('rel')

		$('.tab-panels .panel.active').fadeOut(300, function() {
			$(this).removeClass('active')

			$('#' + panelToShow).fadeIn(300, function() {
				$(this).addClass('active')
			})

		})
	})
})