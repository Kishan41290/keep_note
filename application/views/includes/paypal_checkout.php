<!-- Include the PayPal JavaScript SDK -->
<input type="hidden" name="donate-val" id="donate-val" value="1">
 <script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=USD"></script>
<script>
var amt = $('#donate-val').val();
if(amt>0){
    amt = $('#donate-val').val();
}else{
    amt = '1';
}
// Render the PayPal button into #paypal-button-container
paypal.Buttons({
   style: {
            color:  'blue',
            shape:  'pill',
            label:  'pay',
            height: 40
        },
    // Set up the transaction
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: $('#donate-val').val()>0?$('#donate-val').val():'10'
                }
            }]
        });
    },

    // Finalize the transaction
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Show a success message to the buyer
            $('#donate-modal').modal();
            console.log(details);
            if(details.status=='COMPLETED'){
                swal("Thank you " + details.payer.name.given_name + "! ", " Your payment has been successfully procecssed. Your generous support will help us to make better services for you, it's thanks to people like you that we can continue our services for all people.", "success");
                if(analytics_id!=''){
                    details.purchase_units[0].amount.value
                    fn_ecommerce_track(details.id, 'Donation', details.purchase_units[0].amount.value, '0', details.payer.email_address);
                }
            }else{
                swal("Opps!", " Your payment has not been successfully procecssed, Please try again.", "error");
            }
            
        });
    }
}).render('#paypal-button-container');
</script>