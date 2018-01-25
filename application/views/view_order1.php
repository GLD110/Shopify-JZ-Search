<!-- Right side column. Contains the navbar and content of the page -->
<aside class="">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <!--section starts-->
        <h1>Orders</h1>
        <ol class="breadcrumb">
            <li>
                <a href="index.html">
                    <i class="livicon" data-name="home" data-size="14" data-loop="true"></i> Home
                </a>
            </li>
            <li class="active">Orders</li>
        </ol>
    </section>
    <!--section ends-->
    <section class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-primary panel-success filterable">
                    <div class="panel-heading clearfix  ">
                        <div class="panel-title pull-left">
                            <div class="caption">
                                <i class="livicon" data-name="camera" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> Orders with SKUs
                            </div>
                        </div>
                        <div class="tools pull-right"></div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-striped table-bordered" id="table1">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Order Name</th>
                                    <th>Order ID</th>
                                    <th>Product Name</th>
                                    <th>Customer</th>
									<th>Total</th>
                                    <th>Products</th>
                                    <th>Country</th>
                                    <th>Fulfillment Status</th>
                                    <th>Checkout Date</th>
                                    <th>Financial Status</th>
                                    <th>SKU</th>                            
                                </tr>
                            </thead>
                            <tbody>
                                <?php $sno = 0;
                                foreach ($query->result() as $row):
                                    $sno ++;
                                     ?>
                                     <tr class="tbl_view text-center" >              
                                        <td style="width:10px;">
                                            <?php echo $sno; ?>
                                        </td>
                                        <td><?=$row->order_name ?></td>
                                        <td><?=$row->order_id ?></td>
                                        <td><?=$row->product_name ?></td>
                                        <td><?=$row->customer_name ?></td>
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
                    </div>
                </div>
            </div>
        </div>
        <!-- row-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-success filterable" style="overflow:auto;">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="livicon" data-name="tasks" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> Re-order Columns
                                </h3>
                            </div>
                            <div class="panel-body table-responsive">
                                <table class="table table-striped table-bordered" id="table2" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Order Name</th>
                                            <th>Order ID</th>
                                            <th>Product Name</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Products</th>
                                            <th>Country</th>
                                            <th>Fulfillment Status</th>
                                            <th>Checkout Date</th>
                                            <th>Financial Status</th>
                                            <th>SKU</th>                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sno = 0;
                                        foreach ($query->result() as $row):
                                            $sno ++;
                                             ?>
                                             <tr class="tbl_view text-center" >              
                                               <td style="width:10px;">
                                                    <?php echo $sno; ?>
                                                </td>
                                                <td><?=$row->order_name ?></td>
                                                <td><?=$row->order_id ?></td>
                                                <td><?=$row->product_name ?></td>
                                                <td><?=$row->customer_name ?></td>
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
                            </div>
                        </div>
                    </div>
                </div>
    </section>
    <!-- content -->
</aside>
<!-- right-side -->

