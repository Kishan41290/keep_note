$(function(){	
    // $.validator.addMethod("myDateFormat",function(value, element) {
    //     // yyyy-mm-dd
    //     var re = /^\d{4}-\d{1,2}-\d{1,2}$/;
		// // valid if optional and empty OR if it passes the regex test
    //     return (this.optional(element) && value=="") || re.test(value);
    // });
	$("#login_form").validate({
		rules: {
			email: {
				required: true,
				email:true		
			},
			password: {
				required: true,
			}
		},
		messages: {
			email: {
				required: "&nbsp;<span>"+js_required+"</span>",
				email:"&nbsp;<span>"+js_email+"</span>"
			},
			password: {
				required: "&nbsp;<span>"+js_required+"</span>",
			}
		},

	}),
	$("#register_form").validate({
		rules: {
			email: {
				required: true,
				email:true		
			},
			password: {
				required: true,
				minlength:6,		
			},
			cnf_password: {
				required: true,
				minlength:6	,
			},
		},
		messages: {
			email: {
				required: "&nbsp;<span>"+js_required+"</span>",
				email:"&nbsp;<span>"+js_email+"</span>"
			},
		},

	});
	$("#forgot_form").validate({
		rules: {
			forgot_email: {
				required: true,
				email:true		
			},
		},
		messages: {
			forgot_email: {
				required: "&nbsp;<span>"+js_required+"</span>",
				email:"&nbsp;<span>"+js_email+"</span>"
			},
		},
	});
	$("#reset_form").validate({
		rules: {
			new_pwd: {
				required: true,
				minlength:6,		
			},
			cnf_pwd: {
				required: true,
				minlength:6	,
			},
		},
	});

});
