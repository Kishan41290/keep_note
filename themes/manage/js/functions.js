$(document).ready(function(){
	var timer;
	/*$(document).live('click',function (e){
		if ($( ".header-sub-menu" ).has(e.target).length === 0){
			$( ".header-sub-menu" ).hide();
		}
	});*/
	
	/*DELETE RECORD FROM LISTING*/
	$('.delete').live('click',function() 
	 {	
			var id = $(this).closest('tr').attr('id').replace('data-','');
			var that = $(this);
			if($(this).parent().find('.confirm-wrapper').length==0)
			{
				$(this).after('<div class="confirm-wrapper" id="confirm-'+id+'"><div class="confirm-box"><div class="confirm-msg">Are you sure?</div><span class="confirm-select-yes"></span><span class="confirm-select-no"></span></div></div>');
			}
			else{$(this).parent().find('.confirm-wrapper').show();}
		
			var confirm_yes="#confirm-"+id+" .confirm-select-yes";
			var confirm_no="#confirm-"+id+" .confirm-select-no";
			$(confirm_yes).on("click", function()
			{
				/*CALLS DELETE METHOD TO DELETE RECORD*/
				$('.top_loader_wrap').show();	
				$.post(siteUrl+'manage/ajax_delete', {id:id,tbl:$("#hdn").val(),csrf_token:$('#csrf_token').val(),csrf_name:$('#csrf_name').val()}, function(obj){
					$('.top_loader_wrap').hide();	
					if(obj.code==0)
					{
						$('.error_message').show();
						timer = setTimeout(function(){
							$('.error_message').hide();
						},3000);
					}
					else
					{
						that.closest('.data').remove();
					}
					$('#csrf_token').val(obj.csrf_token);$('#csrf_name').val(obj.csrf_name);
				});
					$("#confirm-"+id).hide();
					$('.tip').remove();
			});
		
			$(confirm_no).on("click", function()
			{
				$("#confirm-"+id).detach();
				$(confirm_no).off('click');
				$(confirm_yes).off('click');
			});
		return false;
		});	
		
	 $('.switch').live('click',function() {
		 	window.clearTimeout(timer);
			var ele=$(this);
			var id =$(this).closest('tr').attr('id').replace('data-','');
			var checked = $(ele).find('.switch-radio').removeAttr("checked");
			if($(ele).hasClass('on')){
				$(ele).removeClass('on');
			}else{
				$(ele).addClass('on');
			}

			$('.top_loader_wrap').show();	
			$.post(siteUrl+'manage/ajax_status', {id:id,tbl:$("#hdn").val(),csrf_token:$('#csrf_token').val(),csrf_name:$('#csrf_name').val()}, function(obj){
				$('.top_loader_wrap').hide();
				if(obj.code==0)
				{
					$('.error_message').show();
					timer = setTimeout(function(){
						$('.error_message').hide();
					},3000);
				}
				else {
					$('#csrf_token').val(obj.csrf_token);
					$('#csrf_name').val(obj.csrf_name);
				}
			});
			
		});


		$('#category').change(function(){
			id = $(this).val();
			$('#product').empty();
			$('.top_loader_wrap').show();

			$.post(siteUrl+'manage/ajax_product', {id:id, csrf_token:$('#csrf_token').val(),csrf_name:$('#csrf_name').val()}, function(obj){
				$('.top_loader_wrap').hide();
				if(obj.code==0)
				{
					$('.error_message').show();
					timer = setTimeout(function(){
						$('.error_message').hide();
					},3000);
				}
				else {
					$('#csrf_token').val(obj.csrf_token);
					$('#csrf_name').val(obj.csrf_name);

					var option = '<option value="" >Select Product</option>';
					$.each(obj.pro, function(i, e){
						option += '<option value="'+ e.Id + '">' + e.ProductName + '</option>';
					});
					$('#product').append(option);

				}
			});
		});


		$(".filter-dropdown").change(function() {
			window.location.href = siteUrl +'manage/order?filter='+$(this).val();
		});
	$(".bill-client-dropdown").change(function() {
		window.location.href = siteUrl +'manage/billing?id='+$(this).val();
	});

	$(".client-dropdown").change(function() {
		window.location.href = siteUrl +'manage/order?id='+$(this).val();
	});

	$(".ref-client-dropdown").change(function() {
		window.location.href = siteUrl +'manage/reference_price?id='+$(this).val();
	});
	
			  $('.placeOrder').click(function() {
				  
				  var id = $(this).val();

				  $.post(siteUrl+'manage/ajax_product/Order', {id:id, csrf_token:$('#csrf_token').val(),csrf_name:$('#csrf_name').val()}, function(obj){
					  $('.top_loader_wrap').hide();
					  if(obj.code==0)
					  {
						  $('.error_message').show();
						  timer = setTimeout(function(){
							  $('.error_message').hide();
						  },3000);
					  }
					  else {

						  $('#csrf_token1, #csrf_token').val(obj.csrf_token);
						  $('#csrf_name1, #csrf_name').val(obj.csrf_name);

						  if(obj.pro.Image != "")
						  {
							  $("#modal_image").attr('src',siteUrl+'uploads/product/thumb/'+obj.pro.Image );
						  }
						  else {
							  $("#modal_image").attr('src',siteUrl+'uploads/default/default.png' );
						  }

						  $('#modal_product_name').val(obj.pro.ProductName);
						  $('#modal_product_id').val(obj.pro.Id);
						  $('#modal_price').val(obj.price);
						  
						  $('#place_order_modal').fadeIn();
					  }
				  });
			  });

		$('.close_button').click(function () {

			$('#modal_qty').css('border-color','#eee');
			$('#place_order_modal, #edit_order_modal').hide();
			$('#modal_qty, #modal_total').val('');
		});

		$('#modal_qty').change(function () {

			var price = $('#modal_price').val();
			var qty = $(this).val();

			$('#modal_total').val(parseInt(qty) * parseInt(price));
		});

	    $('#place_order_button').click(function () {


			var modal_product_id = $('#modal_product_id').val();
			var modal_price = $('#modal_price').val();
			var modal_total = $('#modal_total').val();
			var modal_qty = $('#modal_qty').val();

			$.post(siteUrl+'manage/order/placeOrder', {modal_product_id:modal_product_id,modal_price:modal_price,modal_total:modal_total,modal_qty:modal_qty, csrf_token:$('#csrf_token1').val(),csrf_name:$('#csrf_name1').val()}, function(obj){
				$('.top_loader_wrap').hide();
				if(obj.code==0)
				{
					$('#csrf_token1, #csrf_token').val(obj.csrf_token);
					$('#csrf_name1, #csrf_name').val(obj.csrf_name);
					$('#modal_qty').css('border-color','red');
					$('#modal_qty').focus();
					return false;
				}
				else {

					$('#csrf_token1, #csrf_token').val(obj.csrf_token);
					$('#csrf_name1, #csrf_name').val(obj.csrf_name);
					$('#place_order_modal').fadeOut();
					$('#modal_qty, #modal_total').val('');

					$('#info_modal').find('p.msg').text(obj.msg);

					$('#info_modal').fadeIn();
					$('#modal_qty').css('border-color','#eee');

					timer = setTimeout(function(){
						$('#info_modal').fadeOut();
					},2000);
				}
			});
		});

	    $('.order_edit').click(function () {
			var id = $(this).closest('tr').attr('id').replace('data-','');
			var image  = $(this).closest('tr').find('td.lbl-image').find('img').attr('src');
			var productName  = $(this).closest('tr').find('td.lbl-product-name').text();
			var price  = $(this).closest('tr').find('td.lbl-price').text();
			var qty  = $(this).closest('tr').find('td.lbl-qty').text();
			var total  = $(this).closest('tr').find('td.lbl-total').text();

          //  $('#place_order_button').removeAttrs('id');
			$('#edit_order_form').attr('action',siteUrl+'manage/order/editorder');
			$("#modal_image").attr('src',image);
			$('#modal_product_name').val(productName);
			$('#order_id').val(id);
			$('#modal_price').val(price);
			$('#modal_qty').val(qty);
			$('#modal_total').val(total);

			var url = window.location.href;
			$('#order_url').val(url);

			$('#edit_order_modal').fadeIn();
			//return false;
		});

	   $('.order_cancel').click(function () {
		    var id = $(this).attr('id');

		   var r = confirm("Are you sure to cancel order ? ");
		   if (r == true) {

			   $.post(siteUrl + 'manage/order/cancelOrder', {
				   id: id
			   }, function (obj) {
				   $('.top_loader_wrap').hide();
				   if (obj.code == 0) {
					   return false;
				   }
				   else {
					   

					   $('#info_modal').find('p.msg').text(obj.msg);

					   $('#info_modal').fadeIn();
					   timer = setTimeout(function () {
						   location.reload();
					   }, 1500);
				   }
			   });
		   }
	   });

	  $('.order-status').click(function () {

		  var id = $(this).attr('id');

		  var r = confirm("Are you sure 'Approve' this order ? ");

		  if(r == true){

			  $.post(siteUrl + 'manage/order/approveOrder', {
				  id: id
			  }, function (obj) {
				  $('.top_loader_wrap').hide();
				  if (obj.code == 0) {
					  return false;
				  }
				  else {

					  $('#info_modal').find('p.msg').text(obj.msg);

					  $('#info_modal').fadeIn();
					  timer = setTimeout(function () {
						  location.reload();
					  }, 1500);
				  }
			  });
		  }

	  });


	var temp = 0;
	$('.bill-order-id').change(function () {

		if($(this).is(':checked'))
		{
			temp = parseInt(temp) + parseInt(1);
			$('.view-bill-button').css('display','block');
		}
		else
		{
			temp = parseInt(temp) - parseInt(1);
			if(temp==0) {
				$('.view-bill-button').css('display', 'none');
			}
		}

	});

	$('.print').click(function () {
		window.print();
	});

}); 