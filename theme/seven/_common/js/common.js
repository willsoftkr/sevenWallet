$(function(){
		 
		//파일올리기
		 var fileTarget = $('.filebox .upload-hidden'); 
		 fileTarget.on('change', function(){ // 값이 변경되면 
			 if(window.FileReader){ // modern browser 
				 var filename = $(this)[0].files[0].name; 
			 } else { // old IE 
				 var filename = $(this).val().split('/').pop().split('\\').pop(); // 파일명만 추출 
			 } // 추출한 파일명 삽입 
			 $(this).siblings('.upload-name').val(filename); 
		 }); 
});

function commonModal(title, htmlBody, bodyHeight){
	$('#commonModal').modal('show');
	$('#commonModal .modal-header .modal-title').html(title);
	$('#commonModal .modal-body').html(htmlBody);
	if(bodyHeight){
		$('#commonModal .modal-body').css('height',bodyHeight+'px');
	} 
	$('#closeModal').focus();
}

function confirmModal(title, htmlBody, bodyHeight){
	$('#confirmModal').modal('show');
	$('#confirmModal .modal-header .modal-title').html(title);
	$('#confirmModal .modal-body').html(htmlBody);
	if(bodyHeight){
		$('#confirmModal .modal-body').css('height',bodyHeight+'px');
	} 
	$('#confirmModal').focus();
}

function serviceModal(){
	$('#commonModal').modal('show');
	$('#commonModal .modal-header .modal-title').html('Service is being prepared');
	$('#commonModal .modal-body').html('this Service Will be avaiable shortly');
	$('#commonModal .modal-body').css('height','auto');
	
	$('#closeModal').focus();
}

function LoginModal(){
	$('#commonModal').modal('show');
	$('#commonModal .modal-header .modal-title').html('Plese check login');
	$('#commonModal .modal-body').html('this Service Will be avaiable with login');
	$('#commonModal .modal-body').css('height','auto');
	
	$('#closeModal').focus();
}

function purchaseModal(title, htmlBody, category){
	
	$('#purchaseModal').modal('show');
	$('#purchaseModal .modal-header .modal-title').html(title);

	if(category == 'success'){
		$('#purchaseModal .modal-body').html("<div class=chkimg><img src='"+g5_url+"/img/comform_chk.gif'></div>" + htmlBody);
		$('#purchaseModal .modal-footer').html("<button type='button' class='btn btn-secondary' data-dismiss='modal' id='modal_return_url' onclick='dimHide();'>Close</button>");
	}
	else if(category == 'confirm'){
		$('#purchaseModal .modal-body').html("<div class=chkimg><img src='"+g5_url+"/img/notice.png'></div>" + htmlBody);
		$('#purchaseModal .modal-footer').html("<button type='button' class='btn btn-secondary' data-dismiss='modal' onclick='dimHide();'>Cancle</button> <button type='button' class='btn btn-secondary' id='modal_confirm' >OK</button>");
	}
	else if(category == 'failed'){
		$('#purchaseModal .modal-body').html("<div class=chkimg><img src='"+g5_url+"/img/notice_pop.gif'></div>" + htmlBody);
		$('#purchaseModal .modal-footer').html("<button type='button' class='btn btn-secondary' data-dismiss='modal' id='modal_return_back' onclick='dimHide();'>Close</button>");
	}
	$('#purchaseModal').focus();
	
}

