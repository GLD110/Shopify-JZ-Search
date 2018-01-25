<?php
$config['base_url'] = base_url( 'order/manage/' );
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

$summary = 'Showing ' . ( $page + 1 ) . ' to ' . ( $page + $sel_page_size > $total_count ? $total_count : $page + $sel_page_size ) . ' of ' . $total_count . ' orders';

?>
<style>
</style>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Order
    <small>List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Order</li>
  </ol>
</section>

<!-- Main content -->

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
          <form style="display: inline" class = 'form-inline' id = 'frmSearch' action="<?php echo base_url('order') ?>" method = "post" >
              <label>Store</label>&nbsp;:&nbsp;
              <?PHP echo form_dropdown('sel_shop', $arrStoreList, $sel_shop, 'id="sel_shop" class="form-control input-group-sm"' ); ?>
              &nbsp;&nbsp;&nbsp;
              <label>Order Name</label>&nbsp;:&nbsp;
              <input type = 'text' class="form-control input-group-sm" id = 'sel_order_name' name = 'sel_order_name' value = "<?PHP echo $sel_order_name; ?>" style = "width:90px;" >
              &nbsp;&nbsp;&nbsp;
              <label>Customer Name</label>&nbsp;:&nbsp;
              <input type = 'text' class="form-control input-group-sm" id = 'sel_custoer_name' name = 'sel_customer_name' value = "<?PHP echo $sel_customer_name; ?>" style = "width:90px;" >
              &nbsp;&nbsp;
              <label>Page Size</label>&nbsp;:&nbsp;
              <input type = 'text' class="form-control input-group-sm" id = 'sel_page_size' name = 'sel_page_size' value = "<?PHP echo $sel_page_size; ?>" style = "width:70px;" >&nbsp;&nbsp;&nbsp;&nbsp;
              <div class="form-group date">
                <label> Date: </label>&nbsp;&nbsp;
                <div class="input-group">
                    <input type="text" class="form-control" id="rangepicker4" name = "sel_created_at" value="<?PHP echo $sel_created_at; ?>"/>
                </div>&nbsp;&nbsp;
                <!-- /.input group -->
              </div>        
              <button type = "submit" class = "btn btn-info" ><i class="glyphicon glyphicon-search" ></i></button>
              
              <input type = hidden id = 'sel_sort_field' name = 'sel_sort_field' value = '<?PHP echo $sel_sort_field;?>' >
              <input type = hidden id = 'sel_sort_direction' name = 'sel_sort_direction' value = '<?PHP echo $sel_sort_direction;?>' >
          </form>
          &nbsp;&nbsp;|&nbsp;&nbsp;
          <form style="display: inline" class = 'form-inline' id = 'frmProcess' action="<?php echo base_url('order/download') ?>" method = "post" target = "new" >
              <button type = "button" class = "btn btn-info btn_sync" >Sync Orders</button>
              <input type = 'hidden' id = 'sel_ids' name = 'sel_ids' >
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
                    <th class = "text-center" >
                        <input type = 'checkbox' id = 'chk_all' >
                    </th>
                    <th class = "text-center" >No.</th>
                    <th class = "text-center" ><a href = "javascript:sort('order_name');" >Order Name</a></th>
                    <th class = "text-center" ><a href = "javascript:sort('order_id');" >Order ID</a></th>
                    <th class = "text-center" ><a href = "javascript:sort('product_name');" >Product Name</a></th>              
                    <th class = "text-center" ><a href = "javascript:sort('customer_name');" >Customer</a></th>
                    <th class = "text-center" ><a href = "javascript:sort('email');" >email</a></th>
                    <th class = "text-center" >Total</th>
                    <th class = "text-center" ><a href = "javascript:sort('num_products');" >Products</a></th>
                    <th class = "text-center" ><a href = "javascript:sort('country');" >Country</a></th>
                    <th class = "text-center" >Fulfillment Status</th>
                    <th class = "text-center" ><a href = "javascript:sort('created_at');" >Checkout Date</a></th>
                    <th class = "text-center" ><a href = "javascript:sort('financial_status');" >Financial Status</a></th>      
                    <th class = "text-center" ><a href = "javascript:sort('sku');" >SKU</a></th>
                </tr>
            </thead>
            <tbody>
            <?php $sno = $page;
            foreach ($query->result() as $row):
                $sno ++;
                 ?>
                 <tr class="tbl_view text-center" >
                    <td>
                        <input type = 'checkbox' value = '<?PHP echo $row->id; ?>' class = 'chk_order' >
                    </td>                
                    <td>
                        <?php echo $sno; ?>
                    </td>
                    <td><?=$row->order_name ?></td>
                    <td><?=$row->order_id ?></td>
                    <td><?=$row->product_name ?></td>
                    <td><?=$row->customer_name ?></td>
                    <td><?=$row->email ?></td>
                    <td>$<?=$row->amount ?></td>
                    <td><?=$row->num_products ?></td>
                    <td><?=$row->country ?></td>
                    <td><?=$row->fulfillment_status ?></td>
                    <td><?=$row->created_at ?></td>
                    <td><?=$row->financial_status ?></td>
                    <td><?=$row->sku ?></td>
                </tr>
                <?php endforeach; ?>
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
var sel_product;

// Collect selected ids
function collect_sels()
{
    // Clear
    $('#sel_ids').val('');
    
    // Collect vals for variants
    $('.chk_order').each(function(){
       if( $(this).is(':checked') )
       {
           $('#sel_ids').val( $('#sel_ids').val() + '_' + $(this).val() );
       } 
    });
}

$(document).ready(function(){

  // Checkbox selection
  $('#chk_all').click( function(){
    if( $(this).is(':checked')) 
    {
      $('.chk_order').prop('checked', true );
    }
    else
    {
      $('.chk_order').prop('checked', false );
    }
  });
    
  // ********************************* //

  // Map Button Config
  $('.btn_download').btn_init(
    'download',
    { class : 'btn-warning', caption : 'Download' },
    { class : 'btn-default fa fa-spinner', caption : '' },
    { class : 'btn-success', caption : 'Done' },
    { class : 'btn-danger', caption : 'No new order' }
  );

  // Map category
  $('.btn_download').click(function(){
    collect_sels();
    var url = '<?php echo base_url('order/download') ?>/' + $('#sel_shop').val() + '/0/' + $('#sel_rate').val() + '/' + $('#sel_ids').val();
    window.location = url;
  });
   
  // Sync Button Config
  $('.btn_sync').btn_init(
    'sync',
    { class : 'btn-warning', caption : 'Sync' },
    { class : 'btn-default fa fa-spinner', caption : '' },
    { class : 'btn-success', caption : 'Done' },
    { class : 'btn-danger', caption : 'Error' }
  );

  // Sync category
  $('.btn_sync').click(function(){
    $(this).btn_action( 'sync', 'pending' );
    $.ajax({
      url: '<?php echo base_url($this->config->item('index_page') . '/order/sync') ?>'  + '/' + $('#sel_shop').val(),
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
        $('.btn_sync').btn_action( 'sync', 'error' );  
      }
    });
    
    event.preventDefault();
  }); 
  
  $('#sel_shop').change( function(){
    $('#frmSearch').submit();
  });
});

function sort( field )
{
  $('#sel_sort_field').val( field );
  $('#sel_sort_direction').val( $('#sel_sort_direction').val() == 'ASC' ? 'DESC' : 'ASC' );
  
  $('#frmSearch').submit();
}

</script>