<?php  $page_name= $this->router->fetch_class(); ?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo $this->session->userdata('admin_image')!=''?site_url('uploads/profile/thumb/'.$this->session->userdata('admin_image')):site_url('uploads/default/default.png'); ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $this->session->userdata('adminname'); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview <?php echo $page_name=="home" ? "active" :"" ?>">
                <a href="<?php echo site_url('manage/home'); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>

            <li class="treeview <?php echo $page_name=="user" ? "active" :"" ?>" >
                <a href="<?php echo site_url('manage/user'); ?>"><i class="fa fa-user"></i> <span>User</span></a>
            </li>

            <li class="treeview <?php echo $page_name=="category" ? "active" :"" ?>" >
                <a href="<?php echo site_url('manage/category'); ?>"> <i class="fa fa-th-list"></i> <span>Category</span>
                </a>
            </li>
    
            <li class="treeview <?php echo $page_name=="mailsetting" ? "active" :"" ?>" >
                <a href="<?php echo site_url('manage/mailsetting'); ?>"><i class="fa fa-envelope"></i> <span>Mail Settings</span></a>
            </li>

            <li class="treeview <?php echo $page_name=="settings" ? "active" :"" ?>" >
                <a href="<?php echo site_url('manage/settings'); ?>"> <i class="fa fa-folder"></i> <span>Settings</span>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>