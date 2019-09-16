<style type="text/css">
.container {
  width: 100%;
}
.tabs .indicator
{
  background-color: #e0f2f1;
  height: 60px;
  opacity: 0.3;
}

.form-container
{
  padding: 40px;
  padding-top: 10px;
}

.confirmation-tabs-btn
{
  position: absolute;
}
.teal {
    background-color: #0291d8!important;
    overflow: hidden;
}
#register{
  display: none;
}
.tabs .tab {
  width: 50%;
}
.tabs .tab a.active {
    background-color: #435a656b !important;
}
.modal-overlay{
  height: auto;
}
#register-modal{
  display: none;
  height: auto;
}
#register-modal.modal{
  height: auto;
}
</style>
<div class="container white z-depth-2">
  <ul class="tabs teal">
    <li class="tab col s3"><a id="login-link" class="white-text active" href="#login">login</a></li>
    <li class="tab col s3"><a id="register-link" class="white-text" href="#register">register</a></li>
  </ul>
  <div id="login" class="col s12">
    <form id="login_form" class="col s12">
      <div class="form-container">
        <div class="row">
          <div class="input-field col s12">
            <input id="email_login" type="email" name="email" class="validate">
            <label for="email_login">Email</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input id="password_login" type="password" name="password" class="validate">
            <label for="password_login">Password</label>
          </div>
        </div>
        <br>
        <center>
          <button class="btn waves-effect waves-light teal" type="submit" id="login-btn" name="action">Connect</button>
          <br>
          <br>
          <a href="javascript:void(0)" id="forgot-link">Forgotten password?</a>
        </center>
      </div>
    </form>
  </div>
  <div id="forgot" class="col s12">
    <form id="forgot_form" class="col s12">
      <div class="form-container">
        <div class="row">
          <div class="input-field col s12">
            <input id="forgot_email" type="email" name="forgot_email" class="validate">
            <label for="forgot_email">Email</label>
          </div>
        </div>
        <br>
        <center>
          <button class="btn waves-effect waves-light teal" type="submit" id="forgot-btn" name="action">Send</button>
          <br>
          <br>
          <a href="javascript:void(0)" id="remember-link">Remember password?</a>
        </center>
      </div>
    </form>
  </div>
  <div id="register" class="col s12">
    <form class="col s12" id="register_form" method="post">
      <div class="form-container">
        <div class="row">
          <div class="input-field col s12">
            <input name="email" id="email_reg" type="email" class="validate">
            <label for="email_reg">Email</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input name="password" id="password_reg" type="password" class="validate">
            <label for="password_reg">Password</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s12">
            <input name="cnf_password" id="password-confirm_reg" type="password" class="validate">
            <label for="password-confirm_reg">Confirm password</label>
          </div>
        </div>
        <center>
          <button class="btn waves-effect waves-light teal" id="register-btn" type="submit" name="action">Submit</button>
        </center>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {

    $('#register-link').click(function(e){
      e.preventDefault();
      $('#register').show();
      $('#login').hide();
      $(this).addClass('active');
      $('#login-link').removeClass('active');
    });

    $('#login-link').click(function(e){
      e.preventDefault();
      $('#login').show();
      $('#register').hide();
      $(this).addClass('active');
      $('#register-link').removeClass('active');
    });


  });

</script>