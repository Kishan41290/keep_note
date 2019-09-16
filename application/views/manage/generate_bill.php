<form action="<?php echo site_url('manage/billing/makeBill'); ?>" method="post">
    <input type="hidden" name="post_user_id" value="<?php echo $post_user_id; ?>">
    <input type="hidden" name="post_order_id" value="<?php echo $post_order_id; ?>">
    
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Invoice</h1>
    </section>
    <!-- Main content -->
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> <?php echo $this->site_setting->site_name; ?>
                    <small class="pull-right">Date: <?php echo date('d M, Y'); ?></small>
                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-8 invoice-col">
                From
                <address>
                    <strong>Admin, Office.</strong><br>
                    <?php echo str_replace(",", ", <br />", $admin_data->Address); ?><br>
                    Phone: <?php echo $admin_data->MobileNo; ?><br>
                    Email: <?php echo $admin_data->Email;    ?>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong><?php echo ucfirst($client_data->Name); ?></strong><br>
                    <?php echo str_replace(",", ", <br />", $client_data->Address); ?><br>
                    Phone: <?php echo $client_data->MobileNo; ?><br>
                    Email: <?php echo $client_data->Email;    ?>
                </address>
            </div>
        </div>
        <hr>
        <!-- /.row -->
        <div style="margin-bottom: -13px;margin-top: -13px;font-size: 17px;font-weight: 600;">
            Name : <?php echo ucfirst($client_data->Name); ?>
        </div>
        <hr>
        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Date</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price(<i class="fa fa-inr"></i>)</th>
                        <th>Qty</th>
                        <th>Subtotal(<i class="fa fa-inr"></i>)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $total ='';
                    $i = 1;
                    foreach ($order_data as $r)
                    {
                        $total = $total + $r->Total;
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo date('d M, Y',strtotime($r->CreatedDate)); ?></td>
                            <td><?php echo $r->ProductName; ?></td>
                            <td><?php echo $r->CategoryName; ?></td>
                            <td><i class="fa fa-inr"></i> <?php echo number_format($r->Price,2) ; ?></td>
                            <td><?php echo $r->Qty; ?></td>
                            <td class="bill-total"><i class="fa fa-inr"></i> <?php echo number_format($r->Total,2); ?></td>
                        </tr>
                    <?php
                        $i++;
                    }?>
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div style="float: right;margin-right: 138px;">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td><i class="fa fa-inr"></i>  <?php echo number_format($total,2); ?></td>
                        </tr>
                        <tr>
                            <th>Tax (0.0%)</th>
                            <td><i class="fa fa-inr"></i>  0.00</td>
                        </tr>
                        <tr>
                            <th>Shipping:</th>
                            <td><i class="fa fa-inr"></i>  0.00</td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td><strong><i class="fa fa-inr"></i>  <?php echo number_format($total,2); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <a href="javascript:;" target="_blank" class="btn btn-default print"><i class="fa fa-print"></i> Print</a>
                
                <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">
                    <i class="fa fa-download"></i> Generate PDF
                </button>
            </div>
        </div>
    </section>
    <!-- /.content -->
    <div class="clearfix"></div>
</div>
</form>
