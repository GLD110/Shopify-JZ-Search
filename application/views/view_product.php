<?php
$config['base_url'] = base_url( $this->config->item('index_page') . '/product/manage/' );
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

$summary = 'Showing ' . ( $page + 1 ) . ' to ' . ( $page + $sel_page_size > $total_count ? $total_count : $page + $sel_page_size ) . ' of ' . $total_count . ' products';
?>
<style>
tr.warning td.new-date{ font-weight:bold; color:green; }
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Products
    <small>List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Products</li>
  </ol>
</section>

<!-- Main content -->

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
            <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
            <form style="display: inline" class = 'form-inline' id = 'frmSearch' action="<?php echo base_url($this->config->item('index_page') . '/product') ?>" method = "post" >
                <label>Store</label>&nbsp;:&nbsp;
                <?PHP echo form_dropdown('sel_shop', $arrStoreList, $sel_shop, 'id="sel_shop" class="form-control input-group-sm"' ); ?>
                &nbsp;&nbsp;&nbsp;
                <label>Make</label>&nbsp;:&nbsp;
                <?PHP echo form_dropdown('sel_make', array( 30 => 30, 50 => 50, 70 => 70, 100 => 100 ), $sel_make, 'id="sel_make" class="form-control input-group-sm"' ); ?>
                &nbsp;&nbsp;&nbsp;
                <label>Model</label>&nbsp;:&nbsp;
                <?PHP echo form_dropdown('sel_model', array( 30 => 30, 50 => 50, 70 => 70, 100 => 100 ), $sel_model, 'id="sel_model" class="form-control input-group-sm"' ); ?>
                &nbsp;&nbsp;&nbsp;
                <label>Year</label>&nbsp;:&nbsp;
                <?PHP echo form_dropdown('sel_year', array( 30 => 30, 50 => 50, 70 => 70, 100 => 100 ), $sel_year, 'id="sel_year" class="form-control input-group-sm"' ); ?>
                &nbsp;&nbsp;&nbsp;
                <label>Product Name</label>&nbsp;:&nbsp;
                <input type = 'text' class="form-control input-group-sm" id = 'sel_name' name = 'sel_name' value = "<?PHP echo $sel_name; ?>" style = "width:150px;" >
                &nbsp;&nbsp;&nbsp;

                <label>Page Size</label>&nbsp;:&nbsp;
                <?PHP echo form_dropdown('sel_page_size', array( 30 => 30, 50 => 50, 70 => 70, 100 => 100 ), $sel_page_size, 'id="sel_page_size" class="form-control input-group-sm"' ); ?>

                <button type = "submit" class = "btn btn-info" ><i class="glyphicon glyphicon-search" ></i></button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type = "button" class = "btn btn-warning btn_sync" >Sync products</button>

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
          <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr class = "text-center">
                    <th class = "text-center" >No.</th>
                    <th class = "text-center" style = "width:20%" ><a href = "javascript:sort('title');" >Title</a></th>
                    <th class = "text-center" style = "width:30%" >Description</th>
                    <th class = "text-center" style = "width:20%" >TAGs</th>
                    <th class = "text-center" ><a href = "javascript:sort('title');" >Price</a></th>
                    <th class = "text-center" >Image</th>
                </tr>
            </thead>
            <tbody>
            <?php $sno = $page;
            $prevProductId = '';
            foreach ($query->result() as $row):
              $sno ++;
               ?>
               <tr class="tbl_view text-center" >
                  <td><?php echo $sno; ?></td>
                  <td class = 'text-left' ><a href="#" class="text" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( $this->config->item('index_page') . '/product/update/title/' . $row->id ) ?>" data-title="Enter Title" style="display:none;"><?php echo $row->title; ?></a><?php echo $row->title; ?></td>
                  <td class = 'text-left' ><?= $prevProductId == $row->product_id ? '' : substr( strip_tags( base64_decode($row->body_html)), 0, 100 ) . '...' ?></td>
                  <td class = ''><a href="#" class="text" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( $this->config->item('index_page') . '/product/update/tags/' . $row->id ) ?>" data-title="Enter TAGs" style="display:none;"><?php echo $row->tags; ?></a><?php echo $row->tags; ?></td>
                  <td class = '' ><?=$row->price ?></td>
                  <td class = '' ><img src = "<?=$row->image_url ?>" width = "100" ></td>
              </tr>
              <?php
              $prevProductId = $row->product_id;
              endforeach; ?>
            </tbody>
          </table>
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

$(document).ready(function(){

  var sync_page = 0;
  var sync_count = 0;

  // Editable
  $('.text').editable();

  // Sync Button Config
  $('.btn_sync').btn_init(
    'sync',
    { class : 'btn-warning', caption : 'Sync' },
    { class : 'btn-default fa fa-spinner', caption : '' },
    { class : 'btn-success', caption : 'Done' },
    { class : 'btn-danger', caption : 'Error' }
  );

  $('.btn_sync').click(function(){
    event.preventDefault();

    $(this).btn_action( 'sync', 'pending' );

    // Clear the sync value
    sync_page = 1;
    sync_count = 0;

    // Work with process
    funcSyncProcess();

  });

  var funcSyncProcess = function(){
    $.ajax({
      url: '<?php echo base_url($this->config->item('index_page') . '/product/sync') ?>' + '/' + $('#sel_shop').val() + '/' + sync_page,
      type: 'GET'
    }).done(function(data) {
      console.log( data );
      if( data == 'success' )
      {
        $('.btn_sync').btn_action( 'sync', 'success' );

        setTimeout( function(){
            window.location.reload();
          }, 1000
        );
      }
      else
      {
        var arr = data.split( '_' );

        sync_page = arr[0];
        sync_count = parseInt(sync_count) + parseInt(arr[1]);

        // Show the products
        $('.btn_sync').removeClass( 'fa fa-spinner');
        $('.btn_sync').html( sync_count + ' downloaded ...' );

        // Continue to access
        funcSyncProcess();
      }
    });
  }
});

function sort( field )
{
    $('#sel_sort_field').val( field );
    $('#sel_sort_direction').val( $('#sel_sort_direction').val() == 'ASC' ? 'DESC' : 'ASC' );

    $('#frmSearch').submit();
}

</script>
