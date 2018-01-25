<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/ionicons-2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/daterangepicker/daterangepicker-bs3.css">   
    <link href="<?PHP echo base_url(); ?>asset/vendors/daterangepicker/css/daterangepicker.css" rel="stylesheet" type="text/css" />
    <link href="<?PHP echo base_url(); ?>asset/vendors/datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="<?PHP echo base_url(); ?>asset/vendors/clockface/css/clockface.css" rel="stylesheet" type="text/css" />      
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/colorpicker/bootstrap-colorpicker.min.css">
    <!-- Data Tables bootstrap -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/datatables/dataTables.bootstrap.css">
    <!-- jQuery 2.1.4 -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url() ?>asset/bootstrap/js/bootstrap-editable.min.js"></script>
    <script src="<?php echo base_url() ?>asset/bootstrap/ui/jquery-ui.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bootstrap/ui/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bootstrap/css/bootstrap-editable.css" rel="stylesheet"/>

    <!-- Bootstrap Multiselect -->
    <script src="<?php echo base_url() ?>asset/bootstrap/multiselect/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="<?php echo base_url() ?>asset/bootstrap/multiselect/bootstrap-multiselect.css">
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script>
    (function ( $ ) {
        // Button Init
        $.fn.btn_init = function( name, init_state, pending_state, success_state, error_state ){
            localStorage.setItem( name + '_init', JSON.stringify(init_state) );
            localStorage.setItem( name + '_pending', JSON.stringify(pending_state) );
            localStorage.setItem( name + '_success', JSON.stringify(success_state) );
            localStorage.setItem( name + '_error', JSON.stringify(error_state) );
        }

        // Button Pending    
        $.fn.btn_action = function( name, action ){
            
            // remove the init classes
            var old_state;
            if( action == 'pending')
            {
                old_state = JSON.parse(localStorage.getItem( name + '_' + 'init' ) );
                $(this).removeClass( old_state.class );
                old_state = JSON.parse(localStorage.getItem( name + '_' + 'success' ) );
                $(this).removeClass( old_state.class );
                old_state = JSON.parse(localStorage.getItem( name + '_' + 'error' ) );
                $(this).removeClass( old_state.class );
            }
            else
            {
                var old_state = JSON.parse(localStorage.getItem( name + '_' + 'pending' ) );
                $(this).removeClass( old_state.class );
            }
            
            // Add the success classes
            var new_state = JSON.parse(localStorage.getItem( name + '_' + action ) );
            $(this).addClass( new_state.class );
            
            // Add HTML
            $(this).html( new_state.caption );
        }
    }( jQuery ));    
    </script>
  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <?php if( $this->config->item('MENU_DIRECTION') == 'left' ): ?>
        <a href="<?PHP echo base_url(); ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>A</b>LT</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Admin</b>LTE</span>
        </a>
        <?php endif; ?>
        
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation" <?php if( $this->config->item('MENU_DIRECTION') != 'left' ): ?>style = "margin-left:0px;"<?php endif; ?> >
          <!-- Sidebar toggle button-->
          <?php if( $this->config->item('MENU_DIRECTION') == 'left' ): ?>
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <?php endif; ?>
          <?php if( $this->config->item('MENU_DIRECTION') == 'right' ): ?>
            <?php foreach( $this->config->item('MENU_TREE') as $menu_item ): ?>
            <a href="<?PHP echo base_url( $menu_item['link']); ?>" class="sidebar-toggle" >
              <i class="fa fa-<?= $menu_item['icon'] ?>" ></i>&nbsp;<span><?= $menu_item['title'] ?></span>
            </a>
            <?php endforeach; ?>
            
            <style>
            .main-header .sidebar-toggle:before{
              content : '';
            }
            </style>
          <?php endif; ?>
          
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="<?PHP echo base_url(); ?>asset/adminlte/dist/img/avatar5.png" class="user-image" alt="User Image">
                  <span class="hidden-xs"><?php echo date( $this->config->item('CONST_DATE_FORMAT')); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?PHP echo base_url(); ?>asset/adminlte/dist/img/avatar5.png" class="img-circle" alt="User Image">
                    <p>
                        <?php echo $this->session->userdata( 'username' ); ?> - Administrator
                        <small>Member since <?php echo date('F jS Y', strtotime($this->session->userdata( 'd_o_c' )) ); ?></small>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?PHP echo base_url( $this->config->item('index_page') . '/user/pageChangePassword' ); ?>" class="btn btn-default btn-flat">Change Password</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?PHP echo base_url( $this->config->item('index_page') . '/home/logout' ); ?>" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>
      
      <?php if( $this->config->item('MENU_DIRECTION') == 'left' ): ?>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?PHP echo base_url(); ?>asset/adminlte/dist/img/avatar5.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $this->session->userdata( 'username' ); ?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            
            <?php foreach( $this->config->item('MENU_TREE') as $menu_item ): ?>
              <?php $has_submenu  = ( isset( $menu_item['items'] ) && is_array($menu_item['items']) ) ? true : false; ?>
              <?php if( $menu_item['role'] == '' || $this->session->userdata( 'role' ) == $menu_item['role'] ): ?>
              <li class = "<?php if( $has_submenu): ?>treeview<?php endif; ?><?PHP if( $this->uri->segment(1) == $menu_item['link'] ) echo ' active'; ?>" >
                <a href="<?PHP echo base_url( $menu_item['link'] ); ?>">
                  <i class="fa fa-<?php echo $menu_item['icon']; ?>"></i>
                  &nbsp;
                  <span><?php echo $menu_item['title']; ?></span>
                  <?php if( $has_submenu ): ?>
                    &nbsp;<i class="fa fa-angle-left pull-right"></i>
                  <?php endif; ?>
                </a>
                <?php if( $has_submenu ): ?>
                <ul class="treeview-menu">
                  <?php foreach( $menu_item['items'] as $sub_menu_item ): ?>
                    <li class="<?PHP if( $this->uri->segment(2) == $sub_menu_item['link'] ) echo 'active'; ?>"><a href="<?PHP echo base_url( $menu_item['link'] . '/' . $sub_menu_item['link'] ); ?>"><i class="fa fa-<?= $sub_menu_item['icon'] ?>"></i>&nbsp;<?= $sub_menu_item['title'] ?></a></li>
                  <?php endforeach; ?>
                </ul>
                <?php endif; ?>                
              </li>
              <?php endif; ?>
            <?php endforeach; ?>
            
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
      <?php endif; ?>
      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper" <?php if( $this->config->item('MENU_DIRECTION') != 'left' ): ?>style = "margin-left:0px;"<?php endif; ?> >
        <!-- Content Header (Page header) -->