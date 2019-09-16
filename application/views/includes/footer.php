<link rel="stylesheet" type="text/css" href="themes/css/home.css">
<!-- CONTACT MODAL -->
<div id="contact-modal" class="modal">
  <div class="modal-content">
    <h4>Experiencing difficulties?</h4>
    <div class="row mg0">
      <form class="col s12 center">
        <div class="row mg0">
          <div class="input-field col s12">
            <p>We're here to help so please don't hasistate to contact us:</p>
            <p><b>Email:</b> <a href="mailto:info@notemaker.xyz" target="_top">info@notemaker.xyz</a></p>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- CONTACT MODAL -->
<div id="privacy-modal" class="modal">
  <div class="modal-content">
    <h4>Experiencing difficulties?</h4>
    <div class="row mg0">
      <form class="col s12 center">
        <div class="row mg0">
          <div class="input-field col s12">
            <p>We're here to help so please don't hasistate to contact us:</p>
            <p><b>Email:</b> info@notemaker.xyz</p>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- DONATE MODAL -->
<div id="donate-modal" class="modal">
  <div class="modal-content">
    <h4>Support Us - Donate Today!</h4>
    <p>Your support is invaluable and will help us continue helping the notemaker community and improve our services</p>
    <div class="row mg0">
      <form class="col s12 center">
        <div class="row mg0">
          <div class="input-field col s1">
            <label class="lbl-donate">$</label>
          </div>
          <div class="input-field col s4">
            <input type="text" id="donate-text" placeholder="Amount" value="1" />
          </div>
           <div class="input-field col s7 mg0">
            <div id="paypal-button-container"></div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div id="register-modal" class="modal">
<?php $this->load->view('login'); ?>
</div>

<?php include_once('paypal_checkout.php'); ?>
	<footer class="page-footer orange">
	    <div class="footer-copyright">
	      <div class="container bold-text">
	         © 2019 Note maker.
	      </div>
	    </div>
	  </footer>
  </body>
<?php if(ANALYTICS_ID!=''){
  include_once('google_analytics.php'); 
}else{ ?>
  <script type="text/javascript">
    function fn_note_event(category, action, label) {}
  </script>
<?php } ?>
</html>