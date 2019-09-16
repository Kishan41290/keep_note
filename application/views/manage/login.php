<!DOCTYPE html>
<html>
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
        <h3>Login</h3>
        <form autocomplete="off" method="post" id="login_form" class="module-form" action="<?php echo site_url('manage/login/check');?>">
            <div class="form-group has-feedback">
                <input type="text" name="email" placeholder="Email" id="email" class="form-control" value="<?php echo set_value('email',$this->form_data->email); ?>" />
                <span class="glyphicon glyphicon-envelope form-control-feedback "></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="password" placeholder="Password" class="form-control" id="password" />
                <span class="glyphicon glyphicon-lock form-control-feedback "></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Login</button>
                </div>
            </div>
            <input type="hidden" name="csrf_name"  value="<?php echo htmlspecialchars($unique_form_name);?>"/>
            <input type="hidden" name="csrf_token"  value="<?php echo htmlspecialchars($token);?>"/>
        </form>

        <div class="forgot display-nonea">
            <a href="<?php echo site_url('manage/forgot');?>" title="<?php echo $this->lang->line('forgot_password'); ?>"><?php echo $this->lang->line('forgot_password'); ?>?</a>
        </div>

    </div>

</div>

</body>
</html>

