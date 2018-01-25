<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 2 | Log in</title>
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
    <!-- iCheck -->
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>asset/adminlte/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="../../index2.html"><b>Shopify APP</b>Admin</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form id = 'form_login' action="<?PHP echo base_url( $this->config->item('index_page') . '/home/login'); ?>" method="post">
          <div class="form-group has-feedback">
            <input type="email" class="form-control" name = 'username' placeholder="Email" required >
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name = 'password' placeholder="Password" required >
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="row">
            <div class="col-xs-8">
              <div class="checkbox icheck">
                <label>
                  <input type="checkbox"> Remember Me
                </label>
              </div>
              <div id = 'ret' ></div>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
          </div>
        </form>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.4 -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/bootstrap/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?PHP echo base_url(); ?>asset/adminlte/plugins/iCheck/icheck.min.js"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>


<script>
$(document).ready(function(){

    $( "#form_login" ).submit(function( event ) {

        $("#ret").html('<img src="<?php echo base_url() ?>asset/bootstrap/images/loader.GIF"/>');
        
        var self = $(this);
        var url = self.attr('action');
        console.log(url);
        $.ajax({
            url: url,
            data: self.serialize(),
            type: self.attr('method')
          }).done(function(data) {
              if(data !=='')
              {
                    $("#ret").html(data);
                    $('#form_login')[0].reset();
                  }
                  else
                  {
                      window.location.href='<?php echo base_url( $this->config->item('index_page') . '/home' ) ?>';   
                  }
              });
        event.preventDefault();
    });
});
        
</script>
