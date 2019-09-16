<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $title; ?> | <?php echo $this->site_setting->site_name; ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo site_url('themes/manage/css/bootstrap.css'); ?>">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

	<!-- fullCalendar 2.2.5-->
	<link rel="stylesheet" href="<?php echo site_url('themes/manage/css/fullcalendar.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('themes/manage/css/fullcalendar.print.css'); ?>" media="print">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo site_url('themes/manage/css/AdminLTE.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('themes/manage/css/_all-skins.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo site_url('themes/manage/css/style.css'); ?>">
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<!-- jQuery 2.2.3 -->
	<script src="<?php echo site_url('themes/manage/js/jquery-2.2.3.min.js') ?>"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="<?php echo site_url('themes/manage/js/bootstrap.min.js') ?>"></script>
	<!-- ADDED BY KISHAN -->
	<?php  include_once('common_js.php');?>
	<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
	<script src="<?php echo site_url('themes/manage/js/jquery.slimscroll.min.js'); ?>"></script>
	<!-- FastClick -->
	<script src="<?php echo site_url('themes/manage/js/fastclick.js'); ?>"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo site_url('themes/manage/js/app.min.js'); ?>"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?php echo site_url('themes/manage/js/demo.js'); ?>"></script>
	<!-- fullCalendar 2.2.5 -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
	<script src="<?php echo site_url('themes/manage/js/fullcalendar.min.js'); ?>"></script>
	<?php include_once('js_messages.php');?>
</head>


<body class="hold-transition skin-blue sidebar-mini">
<noscript><div class="msg"><div class="msg-warning"><p><?php echo $this->lang->line('error_javascript'); ?></p></div></div></noscript>
<div class="wrapper">
<!--	<div class="top_loader_wrap"><p class="top_loader">Processing</p></div>-->
<!--	<div class="error_message"><p class="top_loader">Try Again</p></div>-->
	<header class="main-header">
		<a href="<?php echo site_url('manage/home') ?>" class="logo">
			<span class="logo-mini"><b><?php echo $this->site_setting->site_name; ?></b></span>
			<span class="logo-lg"><b><?php echo $this->site_setting->site_name; ?></b></span>
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>


			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav" >
					<li class="dropdown tasks-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-flag-o"></i>
							<span class="label label-danger">9</span>
						</a>
						<ul class="dropdown-menu">
							<li class="header">You have 9 tasks</li>
							<li>
								<!-- inner menu: contains the actual data -->
								<ul class="menu">
									<li><!-- Task item -->
										<a href="#">
											<h3>
												Design some buttons
												<small class="pull-right">20%</small>
											</h3>
											<div class="progress xs">
												<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
													<span class="sr-only">20% Complete</span>
												</div>
											</div>
										</a>
									</li>
									<!-- end task item -->
									<li><!-- Task item -->
										<a href="#">
											<h3>
												Create a nice theme
												<small class="pull-right">40%</small>
											</h3>
											<div class="progress xs">
												<div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
													<span class="sr-only">40% Complete</span>
												</div>
											</div>
										</a>
									</li>
									<!-- end task item -->
									<li><!-- Task item -->
										<a href="#">
											<h3>
												Some task I need to do
												<small class="pull-right">60%</small>
											</h3>
											<div class="progress xs">
												<div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
													<span class="sr-only">60% Complete</span>
												</div>
											</div>
										</a>
									</li>
									<!-- end task item -->
									<li><!-- Task item -->
										<a href="#">
											<h3>
												Make beautiful transitions
												<small class="pull-right">80%</small>
											</h3>
											<div class="progress xs">
												<div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
													<span class="sr-only">80% Complete</span>
												</div>
											</div>
										</a>
									</li>
									<!-- end task item -->
								</ul>
							</li>
							<li class="footer">
								<a href="#">View all tasks</a>
							</li>
						</ul>
					</li>
					<!-- Notifications: style can be found in dropdown.less -->
					<!-- <li class="dropdown notifications-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-bell-o"></i>
							<span class="label label-warning"><?php $counter = count($this->order_notification); ?><?php echo $counter!='0'?$counter:'0'; ?></span>
						</a>
						<ul class="dropdown-menu">
							<li class="header">You have <?php echo $counter!='0'?$counter:'0'; ?> notifications</li>
							<li>

								inner menu: contains the actual data
								<ul class="menu">
									<?php foreach ($this->order_notification as $n){ ?>
										<li>
											<a href="<?php echo site_url('manage/order/information/'.$n->Id); ?>">
												<i class="fa fa-shopping-cart text-green"></i> <?php echo $n->Qty; ?> order made by <?php echo $n->adminname; ?>
											</a>
										</li>
									<?php } ?>
								</ul>
							</li>
						</ul>
					</li> -->
					<!-- User Account: style can be found in dropdown.less -->
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo $this->session->userdata('admin_image')!=''?site_url('uploads/profile/thumb/'.$this->session->userdata('admin_image')):site_url('uploads/default/default.png'); ?>" class="user-image" alt="User Image">
							<span class="hidden-xs"><?php echo $this->session->userdata('adminname'); ?></span>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
								<img src="<?php echo $this->session->userdata('admin_image')!=''?site_url('uploads/profile/thumb/'.$this->session->userdata('admin_image')):site_url('uploads/default/default.png'); ?>" class="img-circle" alt="User Image">
								<p>
									<?php echo $this->session->userdata('adminname'); ?>
								</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="<?php echo site_url('manage/profile') ?>" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="<?php echo site_url('manage/login/logout') ?>" class="btn btn-default btn-flat">Logout</a>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</header>

	<?php include_once('left_bar.php'); ?>
