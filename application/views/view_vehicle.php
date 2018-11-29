<?php
  $arrRole = $this->config->item('USER_ROLE'); 
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Vehicle List
    <small>Manage</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Vehicle</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Vehicle List</h3>
        </div><!-- /.box-header -->
        <div class="col-md-12 column"  style = "border-bottom:solid 1px #ddd; margin-bottom:4px; padding-bottom: 5px;" >
            <a id="modal-666931" href="#modal-container-666931" role="button" class="btn btn-default btn-sm" data-toggle="modal">
                <i class="glyphicon glyphicon-plus"></i>&nbsp; Add new Vehicle
            </a>&nbsp;
        </div>
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th class = "text-center" >S. NO.</th>
                    <th class = "text-center" >Make</th>
                    <th class = "text-center" >Model</th>
                    <th class = "text-center" >Years</th>
                    <th class = "text-center" >Bolt Pattern (cm)</th>
                    <th class = "text-center" >OEM Tire Size</th>
                    <th class = "text-center" style="display: none;">OEM Wheel Size</th>
                    <th class = "text-center" >Plus Tire Size</th>
                    <th class = "text-center" style="display: none;">Plus Wheel Size</th>
                    <th class = "text-center" >Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $sno = 1; ?>
                 <?php foreach ($query->result() as $row): ?>

                <tr class="tbl_view text-center" >

                    <td>
                        <?php echo $sno;?>
                    </td>
                    <td>
                        <?=$row->make ?>
                    </td>
                    <td>
                        <?=$row->model ?>
                    </td>
                    <td>
                        <?=$row->start_year . '~' . $row->end_year ?>
                    </td>
                    <td>
                        <a href="#" name="bolt_pattern_cm" class="prefix" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( $this->config->item('index_page') . '/vehicle/updateVehicle/bolt_pattern_cm' ) ?>" data-title="Enter new Bolt Pattern"><?=$row->bolt_pattern_cm ?></a>
                    </td>
                    <td>
                        <a href="#" name="oem_tire_size" class="prefix" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( $this->config->item('index_page') . '/vehicle/updateVehicle/oem_tire_size' ) ?>" data-title="Enter new OEM Tire Size"><?=$row->oem_tire_size ?></a>
                    </td>
                    <td style="display: none;">
                        <a href="#" name="oem_wheel_size" class="prefix" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( $this->config->item('index_page') . '/vehicle/updateVehicle/oem_wheel_size' ) ?>" data-title="Enter new OEM Wheel Size"><?=$row->oem_wheel_size ?></a>
                    </td>
                    <td>
                        <a href="#" name="plus_tire_size" class="prefix" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( $this->config->item('index_page') . '/vehicle/updateVehicle/plus_tire_size' ) ?>" data-title="Enter new Plus Tire Size"><?=$row->plus_tire_size ?></a>
                    </td>
                    <td style="display: none;">
                        <a href="#" name="plus_wheel_size" class="prefix" data-type="text" data-pk="<?= $row->id?>" data-url="<?php echo base_url( $this->config->item('index_page') . '/vehicle/updateVehicle/plus_wheel_size' ) ?>" data-title="Enter new Plus Wheel Size"><?=$row->plus_wheel_size ?></a>
                    </td>

                    <td>
                        <div class="btn-group">
                            <button  class="btn btn-default btn-sm btn_delete"  type="submit" title="Delete" del_id = '<?PHP echo $row->id; ?>' >
                            <i class="glyphicon glyphicon-remove"></i></button>
                         </div>
                    </td>
                </tr>
               <?php $sno = $sno+1;  endforeach; ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
</section><!-- /.content -->

<form method="POST" id='delvehicle' action="<?php echo base_url( $this->config->item('index_page') . '/vehicle/delVehicle' ) ?>" >
    <input type="hidden" id = 'del_id' name="del_id" value=""/>
</form>

<div class="modal fade" id="modal-container-666931" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Create new entry</h4>
            </div>

            <div class="modal-body">
                <div id='retAddTransaction'></div>

                <form class="form-horizontal cus-form" id="Add_transaction" method="POST" action="<?php echo base_url( $this->config->item('index_page') . '/vehicle/createVehicle' ) ?>"  data-parsley-validate>

                    <table class="table table-bordered">
                    <tr>
                        <td colspan="2">
                            <label>Make</label>
                            <?PHP echo form_dropdown('sel_make', $make, $sel_make="0", 'id="sel_make" class="form-control input-group-sm"' ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label>Model</label>
                            <?PHP echo form_dropdown('sel_model', $model, $sel_model="0", 'id="sel_model" class="form-control input-group-sm"' ); ?>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div>
                            <label>Years</label>
                            </div>
                            <div class="tran-type">
                                <label>Star Year</label>
                                <input type="text" name="start_year" style="display: inline">
                                <label>End Year</label>
                                <input type="text" name="end_year" style="display: inline">
                            </div>
                        </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <label>Bolt Pattern (cm)</label>
                        <input type="text" name="bolt_pattern_cm" class="form-control input-group-sm">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <label>OEM Tire Sizes</label>
                        <input type="text" name="oem_tire_size" class="form-control input-group-sm">
                      </td>
                    </tr>
                    <tr style="display: none;">
                      <td colspan="2">
                        <label>OEM Wheel Sizes</label>
                        <input type="text" name="oem_wheel_size" class="form-control input-group-sm">
                      </td>
                    </tr>
                    <tr>
                      <td colspan="2">
                        <label>Plus Tire Sizes</label>
                        <input type="text" name="plus_tire_size" class="form-control input-group-sm">
                      </td>
                    </tr>
                    <tr style="display: none;">
                      <td colspan="2">
                        <label>Plus Wheel Sizes</label>
                        <input type="text" name="plus_wheel_size" class="form-control input-group-sm">
                      </td>
                    </tr>
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
<div id="dialog" title="Confirmation Required" style="display:none;">
  Are you sure want to delete?
</div>

<script>
$("#modal-container-666931").on('hidden.bs.modal', function(e){window.location.reload();});

$(document).ready(function (){
    // Editable
    $('.prefix').editable();
    $('.is_active').editable({
         source: [
            {value: 1, text: 'Active'},
            {value: 0, text: 'Inactive'}

        ]
    });

    // ********** Delete Action ********** //
    $(".btn_delete").on('click', function (e){
;       //e.preventDefault();
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
                $("#delvehicle").submit();
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
//                  window.location.reload();
            $('#Add_transaction')[0].reset();
          });
        event.preventDefault();
    });

});
</script>
