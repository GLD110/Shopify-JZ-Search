<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Files
    <small>List</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Files</li>
  </ol>
</section>

<!-- Main content -->

<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-body">
          <table id="example2" class="table table-bordered table-hover">
            <thead>
                <tr class = "text-center">
                    <th class = "text-center" >No.</th>
                    <th class = "text-center" >File Number</th>
                    <th class = "text-center" >Orders</th>
                    <th class = "text-center" >Download Date</th>
                    <th class = "text-center" >Download</th>
                    <th class = "text-center" >Clear</th>
                </tr>
            </thead>
            <tbody>
            <?php $sno = 0;
            foreach ($query->result() as $row):
                $sno ++;
                 ?>
                 <tr class="tbl_view text-center" >
                    <td>
                        <?php echo $sno; ?>
                    </td>
                    <td><?=sprintf( '%08d', $row->file_no ) ?></td>
                    <td><?=$row->cnt ?></td>
                    <td><?=$row->down_date ?></td>
                    <td>
                        <button type = "button" class = "btn btn-success fa fa-download btn-download" file_no = '<?PHP echo $row->file_no; ?>' ></button>
                    </td>
                    <td>
                        <button type = "button" class = "btn btn-danger fa fa-trash btn-clear" file_no = '<?PHP echo $row->file_no; ?>' ></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div><!-- /.col -->
  </div><!-- /.row -->
  
<script>
$(document).ready(function(){
   // Download
   $('.btn-download').click(function(){
       window.location = "<?PHP echo base_url( 'order/download' ); ?>/" + $(this).attr( 'file_no' );
   });
   
   // Clear
   $('.btn-clear').click(function(){
    $.ajax({
        url: '<?php echo base_url('order/clear') ?>/' + $(this).attr( 'file_no' ),
        type: 'POST'
    }).done(function(data) {
        window.location.reload();
    });
   });
});
</script>