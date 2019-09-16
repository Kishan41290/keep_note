<style type="text/css">
label.error{
    color: red;
    position: initial;
    float: left;
    width: 100%;
    text-align: right;
}
</style>
<div class="section no-pad-bot" id="index-banner">
  <div class="container">
      <div class="row">
          <div class="col-md-10 col-md-offset-1">
              <div class="add-note">
              <h4 class="form-title contact"><span class="change-password-icon"></span><?php echo $this->lang->line('reset_pwd') ?></h4>
                    <form id="reset_form" method="post" action="<?php echo $action; ?>">
                         <div class="row">
                            <input placeholder="New password" type="password" name="new_pwd" autofocus="true">
                         </div>
                         <div class="row">
                            <input placeholder="Confirm password" type="password" name="cnf_pwd">
                         </div>
                         <input type="hidden" name="hdn_token" value="<?php echo $token;?> ">
                         <div class="row">
                            <input class="waves-effect waves-light btn text-white" type="submit" name="submit" value="Reset password">
                         </div>
                         <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                        <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                        <input type="hidden" id="hdn" value="<?php echo $this->common_model->Encryption($tbl); ?>" />
                    </form>

              </div>
          </div>
      </div>
  </div>
</div>


