<?php
$config['base_url'] = base_url( $this->config->item('index_page') . '/log/manage/' );
$config['total_rows'] = $total_count;
$config['per_page'] = $sel_page_size; 
$config['num_links'] = 4;

$config['first_link'] = 'First';
$config['first_tag_open'] = '<li class="paginate_button previous" id="example1_previous">';
$config['first_tag_close'] = '</li>';

$config['last_link'] = 'Last';
$config['last_tag_open'] = '<li class="paginate_button next" id="example1_previous">';
$config['last_tag_close'] = '</li>';

$config['prev_link'] = '&lt;';
$config['prev_tag_open'] = '<li class="paginate_button ">';
$config['prev_tag_close'] = '</li>';

$config['next_link'] = '&gt;';
$config['next_tag_open'] = '<li class="paginate_button ">';
$config['next_tag_close'] = '</li>';

$config['num_tag_open'] = '<li class="paginate_button ">';
$config['num_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="paginate_button active " disabled><a href = "#" disabled>';
$config['cur_tag_close'] = '</a></li>';

$this->pagination->initialize($config); 

$summary = 'Showing ' . ( $page + 1 ) . ' to ' . ( $page + $sel_page_size > $total_count ? $total_count : $page + $sel_page_size ) . ' of ' . $total_count . ' entries';
?>
<style>
#example2 td{
  word-wrap: break-all;
}
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Log
    <small>List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Log</li>
  </ol>
</section>

<!-- Main content -->

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
            <form style="display: inline" class = 'form-inline' id = 'frmSearch' action="<?php echo base_url( $this->config->item('index_page') . '/log') ?>" method = "post" >
              <label>Type</label>&nbsp;:&nbsp;
              <?PHP echo form_dropdown('sel_type', array( 'ALL' => 'ALL', 'Webhook' => 'Webhook', 'CronJob' => 'CronJob' ), $sel_type, 'id="sel_type" class="form-control input-group-sm"' ); ?>
              &nbsp;&nbsp;
              <label>Input</label>&nbsp;:&nbsp;
              <input type = 'text' class="form-control input-group-sm" id = 'sel_input' name = 'sel_input' value = "<?PHP echo $sel_input; ?>" style = "width:150px;" >
              &nbsp;&nbsp;&nbsp;
              <label>Page Size</label>&nbsp;:&nbsp;
              <?PHP echo form_dropdown('sel_page_size', array( 30 => 30, 50 => 50, 70 => 70, 100 => 100 ), $sel_page_size, 'id="sel_page_size" class="form-control input-group-sm"' ); ?>
              
              <button type = "submit" class = "btn btn-info" ><i class="glyphicon glyphicon-search" ></i></button>
              &nbsp;&nbsp;|&nbsp;&nbsp;
              <button type = "button" class = "btn btn-warning btn_clear" >Clear Log</button>
              <input type = hidden id = 'sel_sort_field' name = 'sel_sort_field' value = '<?PHP echo $sel_sort_field;?>' >
              <input type = hidden id = 'sel_sort_direction' name = 'sel_sort_direction' value = '<?PHP echo $sel_sort_direction;?>' >
            </form>
          </div>
          <div id = 'ret' class="col-md-12 column" ></div>
        </div><!-- /.box-header -->
        
        <!-- Pagenation -->
        <div class = 'box-body' style = "padding:0px 10px;">
            <div class="col-sm-5">
                <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
                    <?php echo $summary ; ?>    
                </div>
            </div>
            <div class="col-sm-7">
                <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                    <ul class="pagination">
                        <?php echo $this->pagination->create_links(); ?>
                    </ul>
              </div>
            </div>
        </div>
        <div class="box-body">
        <form style="display: inline" class = 'form-inline' id = 'frmProcess' action="" method = "post" enctype="multipart/form-data" >
          <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr class = "text-center">
                    <th class = "text-center" >No.</th>
                    <th class = "text-center" >Type</th>
                    <th class = "text-center" >Action</th>
                    <th class = "text-center" >Input</th>
                    <th class = "text-center" style = "width: 60%;">Message/Result</th>
                    <th class = "text-center" >Log Date</th>
                </tr>
            </thead>
            <tbody>
            <?php $sno = $page;
            $prevProduct_id = '';
            foreach ($query->result() as $row):
                $sno ++;
                 ?>
                 <tr class="tbl_view text-center" >
                    <td>
                        <?php echo $sno; ?>
                    </td>
                    <td><?=$row->type ?></td>
                    <td><?=$row->action ?></td>
                    <td class = 'text-left'><?=$row->input ?></td>
                    <td class = "text-left"><?=strip_tags($row->message) ?></td>
                    <td><?=$row->log_date ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </form>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
  
  <!-- Pagenation -->
  <div class="row">
    <div class="col-sm-5">
        <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">
            <?php echo $summary ; ?>    
        </div>
    </div>
    <div class="col-sm-7">
        <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
            <ul class="pagination">
                <?php echo $this->pagination->create_links(); ?>
            </ul>
      </div>
    </div>
  </div><!-- /.row -->  

<script>
var sel_product;

$(document).ready(function(){
  // Clean Button Config
  $('.btn_clear').btn_init(
    'sync',
    { class : 'btn-warning', caption : 'Sync' },
    { class : 'btn-default fa fa-spinner', caption : '' },
    { class : 'btn-success', caption : 'Done' },
    { class : 'btn-danger', caption : 'Error' }
  );

  // Clean Log
  $('.btn_clear').click(function(){
    
    if( !confirm( "Would you like to clear all log entries ?")) return;
    
    $(this).btn_action( 'sync', 'pending' );

    $.ajax({
      url: '<?php echo base_url( $this->config->item('index_page') . '/log/clear') ?>',
      type: 'GET'
    }).done(function(data) {
      console.log( data );
      if( data == 'success' )
      {
        $('.btn_clear').btn_action( 'sync', 'success' );
        
        setTimeout( function(){
                window.location.reload();
            }, 1000
        );
      }
      else
      {
        $('.btn_clear').btn_action( 'sync', 'error' );  
      }
    });
    
    event.preventDefault();
  });
});

function sort( field )
{
    $('#sel_sort_field').val( field );
    $('#sel_sort_direction').val( $('#sel_sort_direction').val() == 'ASC' ? 'DESC' : 'ASC' );
    
    $('#frmSearch').submit();
}


</script>