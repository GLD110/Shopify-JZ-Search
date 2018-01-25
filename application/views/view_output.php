<?php
$config['base_url'] = base_url( 'output/manage' );
?>
<style>
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Output Settings
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Output</li>
  </ol>
</section>

<!-- Main content -->

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
          <form style="display: inline" class = 'form-inline' id = 'frmSearch' action="<?php echo base_url('output') ?>" method = "post" >
            <label>Store</label>&nbsp;:&nbsp;
            <?PHP echo form_dropdown('sel_shop', $arrStoreList, $sel_shop, 'id="sel_shop" class="form-control input-group-sm"' ); ?>
            &nbsp;&nbsp;&nbsp;
            <!--<label>Product SKU</label>&nbsp;:&nbsp;
            <input type = 'text' class="form-control input-group-sm" id = 'sel_sku' name = 'sel_sku' value = "<?PHP echo ''; ?>" style = "width:200px;" >
            &nbsp;&nbsp;&nbsp;-->
            <button type = "submit" class = "btn btn-info" ><i class="glyphicon glyphicon-search" ></i></button>
          </form>
          </div>
          <div id = 'ret' class="col-md-12 column" ></div>
        </div><!-- /.box-header -->
        
        <!-- Pagenation -->
        <div class = 'box-body' style = "padding:0px 10px;">
            <div class="col-sm-12" style="margin-left: 50px;">
              <form style="display: inline" class = 'form-inline' id = 'frmSearch' action="<?php echo base_url('output/save') ?>" method = "post" >
                <div id = "f-email" class = "col-sm-12" style="padding-bottom: 10px;">  
                <label>e-Mail</label>&nbsp;:&nbsp;
                <input type = 'text' class="form-control input-group-sm" id = 'in_mail' name = 'vendor_mail' value = "<?PHP $vendor_mail = (isset($settings)? $settings->vendor_mail: ''); echo $vendor_mail; ?>" style = "width:200px;" >
                &nbsp;&nbsp;&nbsp;
                </div>
                <div class="col-sm-12" style="padding-bottom: 10px;">
                    <label>FTP</label>&nbsp;:&nbsp;
                    <input type = 'text' class="form-control input-group-sm" id = 'in_url' name = 'ftp_uri' value = "<?PHP $ftp_uri = (isset($settings )? $settings->ftp_uri: '');echo $ftp_uri; ?>" style = "width:300px;" >&nbsp;&nbsp;
                    <label>User ID</label>&nbsp;:&nbsp;
                    <input type = 'text' class="form-control input-group-sm" id = 'in_userid' name = 'ftp_id' value = "<?PHP $ftp_id = (isset($settings )? $settings->ftp_id: ''); echo $ftp_id; ?>" style = "width:200px;" >&nbsp;&nbsp;
                    <label>Password</label>&nbsp;:&nbsp;
                    <input type = 'text' class="form-control input-group-sm" id = 'in_password' name = 'user_pwd' value = "<?PHP $user_pwd = (isset($settings )? $settings->user_pwd: ''); echo $user_pwd; ?>" style = "width:200px;" >&nbsp;&nbsp;                  
                    &nbsp;&nbsp;&nbsp; 
                </div>
                <div class="col-sm-12" style="padding-bottom: 10px; display:none;">
                    <div class="form-group">
                        <label>
                            Time
                        </label>&nbsp;:&nbsp;
                        <input type="text" class="form-control input-small" id="clockface2" name="output_time" value="<?PHP $output_time = (isset($settings )? $settings->output_time: '12:00'); echo $output_time; ?>" readonly="">
                    </div>    &nbsp;&nbsp;&nbsp;                 
                    <label>Interval</label>&nbsp;:&nbsp;
                    <input type = 'text' class="form-control input-group-sm" id = 'in_hours' name = 'output_hours' value = "<?PHP $output_hours = (isset($settings )? $settings->output_hours: 24); echo $output_hours; ?>" style = "width:100px;" >&nbsp;&nbsp;<span>hours</span>                    
                </div>      
                <input type="hidden" name="shop" id="in_shop">  
                <button type = "submit" class = "btn btn-info" style="margin: 10px 0 30px 15px;">save</button>
              </form>                
            </div>
        </div>
        
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
  
  <!-- Pagenation -->
  
<script>

$(document).ready(function(){
  // ********************************* //
    $('#in_shop').val($(this).find('option:selected').val());
    
    $('#sel_shop').change(function(){
        $('#in_shop').val($(this).find('option:selected').val());
    });  
    
});

</script>