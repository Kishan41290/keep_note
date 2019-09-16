<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Notemaker - The world's most popular to save note app. We provide quality internet to save online note services that enable you to show anytime, anywhere you go.">
  <meta name="robots" content="index, follow" />
  <meta property="og:type"  content="website" /> 
  <meta property="og:image" content="">
  <meta property="og:image:type" content="image/jpg">
  <meta property="og:image:width" content="1024">
  <meta property="og:image:height" content="1024">
  <meta property="og:url" content="https://www.notemaker.xyz">
  <meta property="og:title" content="To make real time note and save the data online on local storage browser">
  <meta property="og:description" content="Notemaker - The world's most popular to save note app. We provide quality internet to save online note services that enable you to show anytime, anywhere you go.">
   <link rel="canonical" href="https://www.notemaker.xyz/" />

   <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
   <link href="<?php echo site_url(); ?>themes/css/materialize.css" rel="stylesheet" media="screen,projection" />
   <link href="<?php echo site_url(); ?>themes/css/style.css" rel="stylesheet" media="screen,projection" />
   <link href="<?php echo site_url(); ?>themes/css/custom.css" rel="stylesheet">
   <link href="<?php echo site_url(); ?>themes/css/toastr.min.css" rel="stylesheet">

   <!--  Scripts-->
   <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>

   <script type="text/javascript">
    var note_colour_html = '<?php foreach (json_decode(NOTE_COLOR_ARRAY) as $k=>$v) { ?> <li> <a href="#" id="<?php echo $v; ?>"><span class="<?php echo $k; ?>"></span></a> </li> <?php } ?>';
  </script>



   <script type="text/javascript" src="<?php echo site_url(); ?>themes/js/materialize.js" ></script>
   <script type="text/javascript" src="<?php echo site_url(); ?>themes/js/init.js" ></script>
   <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   <script type="text/javascript" src="<?php echo site_url(); ?>themes/js/toastr.min.js"></script>
   <script type="text/javascript" src="<?php echo site_url(); ?>themes/js/validate.min.js" ></script>
   <script type="text/javascript" src="<?php echo site_url(); ?>themes/js/validate_init.js" ></script>
   <script type="text/javascript" src="<?php echo site_url(); ?>themes/js/cookie_store.min.js" ></script>
   <script type="text/javascript" src="<?php echo site_url(); ?>themes/js/custom.js" ></script>
   <script type="text/javascript">
     var analytics_id = '<?php echo ANALYTICS_ID; ?>';
   </script>
   <!--FEVICON-->
  <link rel="shortcut icon" href="<?php echo site_url('themes/image/favicon.ico');?>" type="images/x-icon"/>
  <link rel="icon" href="<?php echo site_url('themes/image/favicon.ico');?>" type="images/x-icon"/>
  <?php include_once('js_messages.php'); ?>
</head>
<body>

  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="<?php echo site_url(); ?>" class="brand-logo">
        <img src="<?php echo site_url(); ?>themes/image/logo_new.png" width="70px">
        <p>Note</p>
      </a>
      <ul class="right hide-on-med-and-down header-link">
        <li><a class="modal-trigger" onclick="fn_note_event('Donate', 'Donate popup', 'Donate popup');" href="#donate-modal">Donate</a></li>
        <li><a onclick="fn_note_event('About', 'About popup', 'About popup');" href="<?php echo site_url('about');?>">About</a></li>     
        <li><a onclick="fn_note_event('Contact', 'Contact popup', 'Contact popup');" class="modal-trigger" href="#contact-modal">Contact</a></li>
        <?php if($this->session->userdata('user_logged_in')==FALSE){ ?> 
        <li><a id="reg-link" onclick="fn_note_event('Register', 'Register', 'Register');" class="modal-trigger reg-link" href="#register-modal">My account</a></li>
        <?php }else{ ?>
            <ul id='dropdown1' class='dropdown-content'>
              <!-- <li><a href="#!"><i class="material-icons">account_box</i>My profile</a></li>
              <li><a href="#!"><i class="material-icons">settings</i>Settings</a></li> -->
              <li><a href="<?php echo site_url('login/logout'); ?>"><i class="material-icons">power_settings_new</i>Logout</a></li>
            </ul>
            <li><a class="dropdown-trigger" href="#!" data-target="dropdown1"><?php echo $this->session->userdata('email'); ?></a><i class="material-icons right">arrow_drop_down</i></a></li>
       <!--  <li><a>Welcome, <?php echo $this->session->userdata('email'); ?></a></li> -->
        <?php } ?>
      </ul>

      <ul id="nav-mobile" class="sidenav">
        <li><a class="modal-trigger" onclick="fn_note_event('Donate', 'Donate popup', 'Donate popup');" href="#donate-modal">Donate</a></li>  
        <li><a onclick="fn_note_event('About', 'About popup', 'About popup');" href="<?php echo site_url('about');?>">About</a></li>
        <li><a onclick="fn_note_event('Contact', 'Contact popup', 'Contact popup');" class="modal-trigger" href="#contact-modal">Contact</a></li>
        <?php if($this->session->userdata('user_logged_in')==FALSE){ ?> 
        <li><a onclick="fn_note_event('Register', 'Register', 'Register');" class="modal-trigger reg-link" href="#register-modal">My account</a></li>
        <?php }else{ ?>
            <li><a class="dropdown-trigger" href="#!" data-target="dropdown2"><?php echo $this->session->userdata('email'); ?></a><i class="material-icons right">arrow_drop_down</i></a></li>
            <ul id='dropdown2' class='dropdown-content'>
              <!-- <li><a href="#!"><i class="material-icons">account_box</i>My profile</a></li>
              <li><a href="#!"><i class="material-icons">settings</i>Settings</a></li> -->
              <li><a href="<?php echo site_url('login/logout'); ?>"><i class="material-icons">power_settings_new</i>Logout</a></li>
            </ul>
        <?php } ?>

      </ul>
      <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
    </div>
  </nav>
   <?php include_once('display_msg.php'); ?>