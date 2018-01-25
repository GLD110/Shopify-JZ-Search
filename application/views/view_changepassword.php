<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Change Password
    <small>Manage</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="#">User Management</a></li>
    <li class="active">Change Password</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Change Password</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
            <div id='retChangePassword'></div>
            
            <form class="form-horizontal cus-form" id="frmChangePassword" method="POST" action="<?php echo base_url( $this->config->item('index_page') ).'/user/changePassword' ?>"  data-parsley-validate>
                <table class="table table-bordered">
                <tr>
                    <td>
                        <label>New password</label>
                        <input type="password" name="header_new_password" id='header_new_password' class="form-control  input-group-sm" required data-parsley-minlength="6"/>
                    </td>
                    
                </tr>
                <tr>
                    <td>
                        <label>Confirm password</label>
                        <input type="password" name="header_new_cppassword" id='header_new_cppassword' class="form-control  input-group-sm" required />
                    </td>
                    
                </tr>
                </table>
                <div style="padding-left: 10px; padding-bottom: 10px; margin-top: -8px;">
                    <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    <button id="cancle" name="cancle" class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>                
                </div>
            </form>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<script>
$(document).ready(function (){
    $( "#frmChangePassword" ).submit(function( event ) {
       var url = $(this).attr('action');
            $.ajax({
            url: url,
            data: $("#frmChangePassword").serialize(),
            type: $(this).attr('method')
          }).done(function(data) {
              $('#retChangePassword').html(data);
//                  window.location.reload();
            $('#frmChangePassword')[0].reset();
          });
        event.preventDefault();
    });
});
</script>       
