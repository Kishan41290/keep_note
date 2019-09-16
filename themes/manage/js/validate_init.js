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

	}),
	$("#setting_form").validate({
		rules: {
			title: {
				required: true,
				maxlength:200		
			},
			keytext: {
				required: true,
				maxlength:200		
			},
			setting_type: {
				required: true,
			},
			value: {
				required: true,
				
			},
		},
		messages: {
			title: {
				required: "&nbsp;<span>"+js_required+"</span>",
				maxlength:"&nbsp;<span>"+maxlengths.replace("%s",200)+"</span>"
			},
			keytext: {
				required: "&nbsp;<span>"+js_required+"</span>",
				maxlength:"&nbsp;<span>"+maxlengths.replace("%s",200)+"</span>"
			},
			setting_type: {
				required: "&nbsp;<span>"+js_required+"</span>",
			},
			value: {
				required: "&nbsp;<span>"+js_required+"</span>",
				
			},
		},
	
	}),
	$('#recover_form').validate({
		rules: {
			recover_pass: {
				required: true,
				minlength:6,
				maxlength:15,
			},
			conf_recover_pass:{
				equalTo: "#recover_pass",
			},
		},
		messages: {
			recover_pass:{
				required: "&nbsp;<span>"+js_required+"</span>",
				minlength:"&nbsp;<span>"+js_passlength+"</span>",
				maxlength:"&nbsp;<span>"+js_passmax+"</span>",
			},
			conf_recover_pass: {
				equalTo:"&nbsp;<span>"+js_pwmismatch+"</span>"
			},
		},
		success: function(label) {
			label.html('&nbsp;').removeClass('error').addClass('ok');
			
		}					
	}),
	$("#admin_form").validate({
		rules: {
			name:{
				required: true,
			},
			email: {
				required: true,
				email:true		
			},
			address: {
				required: true,
			},
			mobile: {
				required: true,
				numeric:true
			},
			password:{
				required: true,
				minlength:6,
				maxlength:15,
			},
			confirm_password:{
				required: true,
				equalTo: "#password",
			},
		},
		messages: {
			name: {
				required: "&nbsp;<span>"+js_required+"</span>",
			},
			email: {
				required: "&nbsp;<span>"+js_required+"</span>",
				email:"&nbsp;<span>"+js_email+"</span>"
			},
			address: {
				required: "&nbsp;<span>"+js_required+"</span>",
			},
			mobile: {
				required: "&nbsp;<span>"+js_required+"</span>",
				number:"&nbsp;<span>"+js_numeric+"</span>"
			},
			password: {
				required: "&nbsp;<span>"+js_required+"</span>",
				minlength:"&nbsp;<span>"+js_passlength+"</span>",
				maxlength:"&nbsp;<span>"+js_passmax+"</span>",
			},
			confirm_password: {
				required: "&nbsp;<span>"+js_required+"</span>",
				equalTo:"&nbsp;<span>"+js_pwmismatch+"</span>"
			},
		},
	
	}),
	$("#admin_form1").validate({
		rules: {
			name:{
				required: true,
			},
			email: {
				required: true,
				email:true		
			},
			password: {
				minlength:6,
				maxlength:15,
			},
			confirm_password:{
				equalTo: "#password",
			},
		},
		messages: {
			name: {
				required: "&nbsp;<span>"+js_required+"</span>",
			},
			email: {
				required: "&nbsp;<span>"+js_required+"</span>",
				email:"&nbsp;<span>"+js_email+"</span>"
			},
			password:{
				minlength:"&nbsp;<span>"+js_passlength+"</span>",
				maxlength:"&nbsp;<span>"+js_passmax+"</span>",
			},
			confirm_password: {
				equalTo:"&nbsp;<span>"+js_pwmismatch+"</span>"
			},
		}

	}),
	$("#webpage_form").validate({
		rules: {
			type:{
				required:true,
			},
			heading:{
				required:true,
				maxlength:100
			},
			title:{
				required:true,
				maxlength:50
			},	
			meta_key:{required:true, maxlength:100},
			meta_desc:{required:true, maxlength:200}
		},
		messages: {
			type: {required:"&nbsp;<span>"+js_required+"</span>"},
			heading: {required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",50)+"</span>"},
			title: {required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>"},
			meta_key: {required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>"},
			meta_desc :{required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",200)+"</span>",},
		},
		success: function(label) {
			label.html('&nbsp;').removeClass('error').addClass('ok');
		},
	}),
	$("#mail_form").validate({
		rules: {
			title:{
				required:true,
				maxlength:100
			},	
			email: {
				required: true,
				email:true	
			},
			password:{
				required:true,
				maxlength:20,
			},

			from_text:{required:true, maxlength:100},
			subject:{required:true, maxlength:100}
		},
		messages: {
			title: {required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>"},
			email: {
				required: "&nbsp;<span>"+js_required+"</span>",
				email:"&nbsp;<span>"+js_email+"</span>"
			},
			password: {
				required: "&nbsp;<span>"+js_required+"</span>",
				maxlength:"&nbsp;<span>"+maxlengths.replace("%s",20)+"</span>"
			},
			from_text: {required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>"},
			subject :{required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>",},
		},
	}),


    $("#category_form").validate({
        rules: {
            title:{
                required:true,
                maxlength:100
            },
            category_name: {
                required: true

            },

            from_text:{required:true, maxlength:100},
            subject:{required:true, maxlength:100}
        },
        messages: {
            title: {required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>"},
            category_name: {
                required: "&nbsp;<span>"+js_required+"</span>",

            },

            from_text: {required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>"},
            subject :{required:"&nbsp;<span>"+js_required+"</span>", maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>",},
        },
    }),

		$("#product_form").validate({
			rules: {
				category:{
					required: true
				},
				name: {
					required: true,
					maxlength: 100
				},
				price:{
					required: true,
					number: true

				}
			},
			messages: {
				category: {
					required: "&nbsp;<span>"+js_required+"</span>",

				},
				name: {
					    required:"&nbsp;<span>"+js_required+"</span>",
					    maxlength:"&nbsp;<span>"+maxlengths.replace("%s",100)+"</span>"
				      },
				price: {
					    required: "&nbsp;<span>" + js_required + "</span>",
				     	number: "&nbsp;<span>" + js_numeric + "</span>"
				}
			},
		}),

	$("#ref_price_form").validate({
		rules: {
			category:{
				required:true,
			},
			product:{
				required:true,
			},
			client:{
				required:true,
			},
			price: {
				required: true,
				number:true
			},
		},
		messages: {
			category: {required:"&nbsp;<span>"+js_required+"</span>"},
			product: {required: "&nbsp;<span>"+js_required+"</span>",},
			client: {required: "&nbsp;<span>"+js_required+"</span>",},
			price: {required: "&nbsp;<span>"+js_required+"</span>",}
		},
	}),

	$("#place_order_modal").validate({
		rules: {
			modal_qty: {
				required: true,
				number:true
			},
		},
		messages: {
			modal_qty: {required: "&nbsp;<span>"+js_required+"</span>",}
		},
	})






});
