<?php header('X-Frame-Options: DENY');?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo @$this->site_setting->site_name;?> | <?php echo $title; ?> </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo site_url('themes/manage/css/bootstrap.css'); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo site_url('themes/manage/css/AdminLTE.min.css'); ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo site_url('themes/manage/css/blue.css'); ?>">

    <link rel="stylesheet" href="<?php echo site_url()."themes/manage/css/style.css"?>" />

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <!-- JS -->
    <!--    <script type="text/javascript" src="--><?php //echo site_url()."themes/manage/js/jquery-1.8.2.min.js" ?><!--"></script>-->

    <script src="<?php echo site_url('themes/manage/js/jquery-2.2.3.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo site_url()."themes/manage/js/validate.min.js" ?>"></script>
    <script type="text/javascript" src="<?php echo site_url()."themes/manage/js/validate_init.js" ?>"></script>

    <script type="application/javascript">
        var baseUrl = '<?php echo base_url();?>';
        var siteUrl = '<?php echo site_url();?>';
    </script>
    <script type="text/javascript">
        window.onload = function() {
            var txtBox = document.getElementById("email");
            if (txtBox != null) {
                txtBox.focus();
            }
        };
    </script>

</head>
<body class="hold-transition login-page">
<?php include_once('includes/js_messages.php');?>
<noscript><div class="msg"><div class="msg-warning"><p><?php echo $this->lang->line('error_javascript'); ?></p></div></div></noscript>
<div class="login-box ">
    <div class="login-logo">
        <a href="<?php echo site_url('manage/'); ?>"><b>Admin</b> PANEL</a>
    </div>
    <div class="login-box-body">
        <?php include_once('includes/display_msg.php');?>
        <h3>Recover Password</h3>
        <form autocomplete="off" method="post" id="recover_form" class="module-form" action="<?php echo $action; ?>">

            <div class="form-group has-feedback">
                <input type="password" name="recover_pass" class="form-control" id="recover_pass" value="<?php echo set_value('recover_pass',$this->form_data->recover_pass); ?>" />
                <span class="glyphicon glyphicon-lock form-control-feedback "></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control"  name="conf_recover_pass" id="conf_recover_pass" value="<?php echo set_value('conf_recover_pass',$this->form_data->conf_recover_pass); ?>" />
                <span class="glyphicon glyphicon-lock form-control-feedback "></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <input class="btn btn-primary btn-block btn-flat" type="submit" name="submit" id="submit" value="<?php echo $this->lang->line('submit'); ?>" /><br />
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <a href="<?php echo site_url('manage/login');?>" title="<?php echo $this->lang->line('login'); ?>">Remember Password?</a>
                    <a href="<?php echo site_url('manage/login');?>" title="<?php echo $this->lang->line('login'); ?>"><?php echo $this->lang->line('login'); ?></a>
                </div>
            </div>
            <input type="hidden" name="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
        </form>
    </div>

</div>

</body>
</html>

