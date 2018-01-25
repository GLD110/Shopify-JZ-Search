<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Stores
    <small>Manage</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Stores</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
        </div><!-- /.box-header -->
        <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
          <?php if( $isAdmin ): ?>
          <a id="modal-666931" href="#modal-container-666931" role="button" class="btn btn-default btn-sm" data-toggle="modal">
            <i class="glyphicon glyphicon-plus"></i>&nbsp; Add new Store
          </a>
          <?php endif; ?>
        </div>
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead style="vertical-align: middle;">
              <tr>
                <th class = "text-center" >Store Domain</th>
                <?php foreach ($arrStoreList as $row): ?>
                <th class = "text-center" ><?php echo $row->shop; ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach( $this->config->item('SETTING_ITEMS') as $settings_item ): ?>
                <tr>
                  <th class = "text-center" ><?= $settings_item['title'] ?></th>
                  <?php foreach ($arrStoreList as $row): ?>
                  <td class = "text-center">
                    <?php if( $settings_item['type'] == 'text' ): ?>
                      <a href="#" class="editText" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( 'store/update/' . $settings_item['field'] . '/' . $row->id ) ?>" data-title="Enter new <?= $settings_item['title'] ?>"><?php eval( 'echo $row->' . $settings_item['field'] . ';' ); ?></a>
                    <?php endif; ?>
                    <?php if( $settings_item['type'] == 'flag' ): ?>
                      <div class="btn-group">
                        <button type="button" data_id = "<?PHP echo $row->id; ?>" data_role = "<?= $settings_item['field'] ?>" class="btn btn_<?= $settings_item['field'] ?>_<?php echo $row->id; ?> <?PHP eval( 'echo $row->' . $settings_item['field'] . ' == 1 ? "btn-success" : "btn-default";'); ?> btn-onoff" status = '1' >ON</button>
                        <button type="button" data_id = "<?PHP echo $row->id; ?>" data_role = "<?= $settings_item['field'] ?>" class="btn btn_<?= $settings_item['field'] ?>_<?php echo $row->id; ?> <?PHP eval( 'echo $row->' . $settings_item['field'] . ' != 1 ? "btn-danger" : "btn-default";'); ?> btn-onoff" status = '0' >OFF</button>
                      </div>
                    <?php endif; ?>
                    <?php if( $settings_item['type'] == 'webhook' ): ?>
                      <div class="btn-group">
                        <button  class="btn btn-success btn_webhook"  type="button" data-id = '<?PHP echo $row->id; ?>' data-role = "install" >Install</button>
                        <button  class="btn btn-warning btn_webhook"  type="button" data-id = '<?PHP echo $row->id; ?>' data-role = "uninstall" >Uninstall</button>
                        <button  class="btn btn-primary btn_webhook"  type="button" data-id = '<?PHP echo $row->id; ?>' data-role = "get" >Get</button>
                      </div>                
                    <?php endif; ?>
                  </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>

              <?php if( $isAdmin ): ?>
              <tr>
                <th class = "text-center" >Delete</th>
                <?php foreach ($arrStoreList as $row): ?>
                <td class = "text-center">
                  <div class="btn-group">
                    <button  class="btn btn-danger btn-sm btn_delete"  type="submit" title="Delete" del_id = '<?PHP echo $row->id; ?>' >
                    <i class="glyphicon glyphicon-remove"></i></button>
                  </div>                
                </td>
                <?php endforeach; ?>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->
        
<form method="POST" id='deluser' action="<?php echo base_url( $this->config->item('index_page') . '/store/del') ?>" >
    <input type="hidden" id = 'del_id' name="del_id" value=""/>
</form>

<div class="modal fade" id="modal-container-666931" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close</button>
        <h4 class="modal-title" id="myModalLabel">Add new Store</h4>
      </div>

      <div class="modal-body">
        <div id='retAddTransaction'></div>
        
        <form class="form-horizontal cus-form" id="Add_transaction" method="POST" action="<?php echo base_url( $this->config->item('index_page') . '/store/add' ) ?>"  data-parsley-validate>
          <table class="table table-bordered">
            <tr>
              <td colspan="2">
                <label>Store Domain <small>( ex: blablabla.myshopify.com )</small></label>
                <input type="text" name="shop" id='shop' class="form-control input-group-sm" required/>
              </td>
            </tr>
            <?php foreach( $this->config->item('SETTING_ITEMS') as $settings_item ): ?>
            <tr>
              <td colspan="2">
                <?php if( $settings_item['type'] == 'text' ): ?>
                  <label><?= $settings_item['title'] ?> <?php if( !empty( $settings_item['example'] ) ) echo '&nbsp;<small>( ex: ' . $settings_item['example'] . ' )</small>'; ?></label>
                  <input type="text" name="<?= $settings_item['field'] ?>" id='<?= $settings_item['field'] ?>' class="form-control input-group-sm" required/>
                <?php endif; ?>
                <?php if( $settings_item['type'] == 'flag' ): ?>
                  <label><?= $settings_item['title'] ?> <?php if( !empty( $settings_item['example'] ) ) echo '&nbsp;<small>( ex: ' . $settings_item['example'] . ' )</small>'; ?></label>
                  <?PHP echo form_dropdown( $settings_item['field'], array( '0' => 'OFF', '1' => 'ON'), '1', 'id="' . $settings_item['field'] . '" class="form-control input-group-sm"' ); ?>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </table>
          <div style="padding-left: 10px; padding-bottom: 10px; margin-top: -8px;">
            <button type="submit" name="submit" class="btn btn-success">Submit</button>
            <button id="cancle" name="cancle" class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>                
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<a class="confirmLink" href="#"></a>
<div id="dialog" title="Confirmation Required">
  Are you sure want to delete?
</div>

<style>
.editable-input input[type=text]{
  width: 300px;
}
.table>thead>tr>th{
  vertical-align: middle;
}

</style>
<script>
$("#modal-container-666931").on('hidden.bs.modal', function(e){window.location.reload();});

$(document).ready(function (){
  
  // Editable
  $('.editText').editable();
  
  $('.btn-onoff').click( function(){
      
      // Change the color
      $('.btn_' + $(this).attr('data_role') + '_' + $(this).attr('data_id') + '[status=1]').toggleClass( 'btn-default btn-success' );
      $('.btn_' + $(this).attr('data_role') + '_' + $(this).attr('data_id') + '[status=0]').toggleClass( 'btn-default btn-danger' );
      
      // Change the status
      $.ajax({
          url: '<?PHP echo base_url( 'store/update' ); ?>/' + $(this).attr('data_role') + '/' + $(this).attr('data_id'),
          type: 'POST',
          data : {
              value : $('.btn_' + $(this).attr('data_role') + '_' + $(this).attr('data_id') + '[status=1]').hasClass('btn-success') ? 1 : 0,
          }
      }).done(function(data1) {
          console.log( data1 );
      });
  });
        
  // ********** Delete Action ********** //
  $(".btn_delete").on('click', function (e){
      e.preventDefault();
      console.log('dele');
      $('#del_id').val( $(this).attr( 'del_id' ) );
      $('.confirmLink').trigger('click'); return false;
  });    

  $("#dialog").dialog({
      autoOpen: false,
      modal: true
  });


  $(".confirmLink").click(function(e) {
      e.preventDefault();
      var targetUrl = $(this).attr("href");

      $("#dialog").dialog({
          buttons : {
          "Confirm" : function() {
              $(this).dialog("close");
              $("#deluser").submit();
          },
          "Cancel" : function() {
              $(this).dialog("close");
              return false;
              }
          }
      });

      $("#dialog").dialog("open");
  });
  
  // ********************************* //
  
  $( "#Add_transaction" ).submit(function( event ) {
     var url = $(this).attr('action');
          $.ajax({
          url: url,
          data: $("#Add_transaction").serialize(),
          type: $(this).attr('method')
        }).done(function(data) {
          $('#retAddTransaction').html(data);
          window.location.reload();
          $('#Add_transaction')[0].reset();
        });
     event.preventDefault();
  });

  var sel_data_id = '';
  
  // Webhook
  $('.btn_webhook').click(function(){
    $.ajax({
      url: '<?php echo base_url($this->config->item('index_page') . '/store/webhook') ?>'  + '/' + $(this).attr('data-id') + '/' + $(this).attr('data-role'),
      type: 'GET'
    }).done(function(data) {
      console.log( data );
    });
    event.preventDefault();
  });
});
</script>