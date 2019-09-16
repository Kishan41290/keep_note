$(document).ready(function(){
	if (!$.cookie('uuid')) {
        fn_create_temp_user();    // CREATE NEW USER.
    } else {
        fn_check_temp_user();    // CREATE NEW USER. 
    }

	$('.modal').modal();
  	if(user_type=="user"){ // ONLY LOGIN USER
  		$.ajax({
	        url: weburl+'ajax/get_note_count',
	        data: {},
	        dataType: 'json',
	        type: 'POST',
	        cache: false,
	        success: function (obj) {
	        	localStorage.setItem('total-note', obj.n_count);
	        }
	    });

  	}

   	//var note_length = $('.added-note > .main-wrap > .content-editable').length;
	// var _html = '';
	$('#add-note').on('click', function(e){
		e.preventDefault();
		if(user_type=="user"){
			note_length = localStorage.getItem('total-note')!=''?localStorage.getItem('total-note'):0;
			$('.add-note-text').hide();
			note_length = parseInt(note_length==null?0:note_length) + parseInt(1);
			var _note_html = '<div class="main-wrap"> <div class="note-handle"></div> <div class="content-editable" id="data-'+note_length+' " contenteditable="true" style=""> </div> <div class="bs-example"> <div class="dropdown"> <ul class="dropdown-menu note-color-drpdown">';
			_note_html += note_colour_html;
			_note_html += '</ul> </div> </div> <a href="javascript:;" class="delete-note"><i class="material-icons deleteNote">delete</i></a> </div> </div>';
			$('.added-note').prepend(_note_html);
			localStorage.setItem('total-note', note_length);

			$('.delete-note').on('click', function(e){
				var that = $(this);
				e.preventDefault();
				swal({
				  title: "Are you sure?",
				  text: "Once deleted, you will not be able to recover this note!",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				  	var nid = $(that).closest('.main-wrap').find('.content-editable').attr('id').replace('data-','');
				  	$.ajax({
				        url: weburl+'ajax/delete_note',
				        data: {id:nid},
				        dataType: 'json',
				        type: 'POST',
				        cache: false,
				        success: function (obj) {
				        	$(that).closest('div.main-wrap').remove();
				        	var note_length = $('.added-note > .main-wrap').length;
				        //	localStorage.setItem('total-note', obj.n_count);
				        	localStorage.removeItem('note-'+nid);
				        	if(note_length==0){
						    	$('.add-note-text').fadeIn();
						    }
						    swal("Deleted!", "Your note has been deleted.", "success");
						    if(analytics_id!=''){
								fn_note_event('Delete', 'Delete note', 'Delete note');
							}
				        }
				    });

				  }
				});
			});

			$('.note-color-drpdown > li > a').click(function(e){
				e.preventDefault();
				var cl = $(this).attr('id');
				$(this).closest('li').closest('ul').closest('div').closest('div').closest('.main-wrap').find('div.content-editable').css('background', cl);
				var id = $(this).closest('li').closest('ul').closest('div').closest('div').closest('.main-wrap').find('div.content-editable').attr('id').replace('data-','');
				localStorage.setItem('note-color-'+id, cl);
				if(analytics_id!=''){
					fn_note_event('Colour', 'Note colour change', 'Colour change');
				}
				fn_save_notes(id);	
			});
			$('.content-editable').keyup(function(){
				var id = $(this).attr('id').replace('data-', '');
				localStorage.setItem('note-'+id, $(this).html());
			});
			$('.content-editable').focusout(function(e){
				e.preventDefault();
				var id = $(this).attr('id').replace('data-', '');
				fn_save_notes(id);
			});
		}else{
			fn_error_msg('Please create an account, to save more notes');
		}
		
	});

	$('.content-editable').focusout(function(e){
		e.preventDefault();
		var id = $(this).attr('id').replace('data-', '');
		fn_save_notes(id);
	});

	
	$('.content-editable').keyup(function(){
		var id = $(this).attr('id').replace('data-', '');
		localStorage.setItem('note-'+id, $(this).html());
	});

	$('.delete-note').on('click', function(e){
				var that = $(this);
				e.preventDefault();
				swal({
				  title: "Are you sure?",
				  text: "Once deleted, you will not be able to recover this note!",
				  icon: "warning",
				  buttons: true,
				  dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				  	var nid = $(that).closest('.main-wrap').find('.content-editable').attr('id').replace('data-','');
				  	$.ajax({
				        url: weburl+'ajax/delete_note',
				        data: {id:nid},
				        dataType: 'json',
				        type: 'POST',
				        cache: false,
				        success: function (obj) {
				        	$(that).closest('div.main-wrap').remove();
				        	//localStorage.setItem('total-note', obj.n_count);
				        	localStorage.removeItem('note-'+nid);
				        	if(obj.n_count==0){
						    	$('.add-note-text').fadeIn();
						    }
						    swal("Deleted!", "Your note has been deleted.", "success");
						    if(analytics_id!=''){
								fn_note_event('Delete', 'Delete note', 'Delete note');
							}
				        }
				    });

				  }
				});
			});
	
	$('.note-color-drpdown > li > a').click(function(e){
		e.preventDefault();
		var cl = $(this).attr('id');
		$(this).closest('li').closest('ul').closest('div').closest('div').closest('.main-wrap').find('div.content-editable').css('background', cl);
		var id = $(this).closest('li').closest('ul').closest('div').closest('div').closest('.main-wrap').find('div.content-editable').attr('id').replace('data-','');
		localStorage.setItem('note-color-'+id, cl);
		if(analytics_id!=''){
			fn_note_event('Colour', 'Note colour change', 'Colour change');
		}
		fn_save_notes(id);
	});

	$('#donate-text').keyup(function(){
		$('#donate-val').val($(this).val());
	});
	$('#donate-text').focusout(function(){
		if(analytics_id!=''){
			fn_note_event('Donate', 'Donate', 'Donate amount '+$('#donate-val').val());
		}
	});
		
	$('#register_form').submit(function(e){
		e.preventDefault();
		if($(this).valid()) {
			$('#register-btn').text('Processing');
			$('#register-btn').attr('disabled', true);
			$.ajax({
		        url: weburl+'register',
		        data: $(this).serialize(),
		        dataType: 'json',
		        type: 'POST',
		        cache: false,
		        success: function (data) {
		        	$('#register-btn').text('SUBMIT');
					$('#register-btn').removeAttr('disabled');
		        	if(data.code==1){
		        		fn_success_msg(data.msg);
		        	//	location.reload(true);
		        	}else{
		        		fn_error_msg(data.msg);
		        	}
		        }
		    });
		}
	});
	$('#login_form').submit(function(e){
		e.preventDefault();
		if($(this).valid()) {
			$('#login-btn').text('Processing');
			$('#login-btn').attr('disabled', true);
			$.ajax({
		        url: weburl+'login',
		        data: $(this).serialize(),
		        dataType: 'json',
		        type: 'POST',
		        cache: false,
		        success: function (data) {
		        	$('#login-btn').text('SUBMIT');
					$('#login-btn').removeAttr('disabled');
		        	if(data.code==1){
		        		fn_success_msg(data.msg);
		        		location.reload(true);
		        	}else{
		        		fn_error_msg(data.msg);
		        	}
		        }
		    });
		}
	});

	$('#forgot_form').submit(function(e){
		e.preventDefault();
		if($(this).valid()) {
			$('#forgot-btn').text('Sending...');
			$('#forgot-btn').attr('disabled', true);
			$.ajax({
		        url: weburl+'forgot',
		        data: $(this).serialize(),
		        dataType: 'json',
		        type: 'POST',
		        cache: false,
		        success: function (data) {
		        	$('#forgot-btn').text('SENT');
		        	$('#forgot-btn').removeAttr('disabled');
		        	if(data.code==1){
		        		fn_success_msg(data.msg);
		        	}else{
		        		fn_error_msg(data.msg);
		        	}
		        }
		    });
		}
	});

	

	$('#forgot-link').click(function(e){
		e.preventDefault();
		$('#login').hide();
		$('#forgot').show();
		$('#forgot_form').show();
	});

	$('#remember-link').click(function(e){
		e.preventDefault();
		$('#login').show();
		$('#forgot').hide();
	});

	$('#login-link, #register-link').click(function(e){	
		e.preventDefault();
		$('#forgot_form').hide();
	});


	document.addEventListener('DOMContentLoaded', function() {
	    var elems = document.querySelectorAll('.dropdown-trigger');
	    var instances = M.Dropdown.init(elems, options);
	});
 	$('.dropdown-trigger').dropdown();
});

function fn_save_notes(id){
	var note_data = localStorage.getItem('note-'+id);
	var note_color = localStorage.getItem('note-color-'+id);
	if(note_data!='' && id!='' && note_data!=undefined){
		$.ajax({
	        url: weburl+'ajax/save_note',
	        data: {id: id, note_data:note_data, note_color:note_color},
	        dataType: 'json',
	        type: 'POST',
	        cache: false,
	        success: function (data) {
	        	if(data.code==1){
	        		fn_success_msg(data.msg);
	        	}else{
	        		fn_error_msg(data.msg);
	        	}
	        }
	    });
	}
	
}



// toastr MESSAGE.
function fn_error_msg(msg) {
    toastr.error(msg, '');
    return false;
}

function fn_success_msg(msg) {
    toastr.success(msg, '');
    return false;
}

function fn_warning_msg(msg) {
    toastr.warning(msg, '');
    return false;
}

function fn_info_msg(msg) {
   toastr.info(msg, '');
    return false;
}
// CREATE GUEST USER
function fn_create_temp_user() {
    _submit_form(weburl + 'ajax/create_guest_user', '', {}, '', function (res) {
        fn_check_temp_user();
    });
}
function fn_check_temp_user() {
    _submit_form(weburl + 'ajax/check_guest_user', '', {}, '', function (res) {
        if (res.code == 0) {
            fn_create_temp_user();
        }
    });
}
// AJAX CALL FUNCTION.
function _submit_form(formAction, header, formdata, form, callback, asyncstatus) {
    asyncstatus = asyncstatus || '';
    $.ajax({
        url: formAction,
        async: asyncstatus != '' ? asyncstatus : true,
        headers: header,
        data: formdata ? formdata : form.serialize(),
        dataType: 'json',
        type: 'POST',
        cache: false,
        success: function (data) {
            try {
                callback(data);
            } catch (err) {
                // alert(data);
            }
        }
    });
}

toastr.options = {
  "closeButton": false,
  "debug": false,
  "newestOnTop": false,
  "progressBar": false,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}