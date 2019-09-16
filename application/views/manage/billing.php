<div class="content-wrapper">

    <?php
    if($_GET['id'] == '')
    { ?>
        <div class="pad margin no-print">
            <div class="callout callout-info" style="margin-bottom: 0!important;">
                <h4><i class="fa fa-info"></i> Note:</h4>
                Please Select any client, in '<strong>Client List</strong>' droup down.
            </div>
        </div>
    <?php } ?>

    <section class="content-header content-section" style="margin-bottom: 40px">
            <h1 class="header-page">
                <?php echo $title; ?>
                <small style="font-weight: 700;margin-left: 18px;">Client List</small>
            </h1>
            <select id="client-filter" class="form-control bill-client-dropdown" name="client-filter">
                <option value="" selected>All</option>
                <?php foreach ($client_list as $r)
                { ?>
                    <option value="<?php echo $r->Id; ?>" <?php echo $_GET['id'] == $r->Id ? 'selected' : '' ?> ><?php echo $r->Name; ?></option>
                    <?php
                } ?>
            </select>

        <form action="<?php echo site_url('manage/billing/generate_bill'); ?>" method="post">
            <input type="hidden" value="<?php echo $_GET['id']; ?>" name="user_id">
            <div class="form-group view-bill-button pull-right" style="display: none">
                <div class="col-md-12 col-sm-10">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> View Bill</button>
                </div>
            </div>
    </section>
    <?php include_once('includes/display_msg.php'); ?>

    <section class="content">
        <div class="row">


            <div class="col-xs-12">
                <div class="box">

                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table id="product" class="table listing" role="grid" aria-describedby="example1_info">
                            <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th width="50px;"></th>
                                <th width="150px;">Category Name</th>
                                <th>Product Name</th>
                                <th>Client Name</th>
                                <th>Price(<i class="fa fa-inr"></i>)</th>
                                <th>Qty</th>
                                <th>TotalPrice(<i class="fa fa-inr"></i>)</th>
                                <th><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (empty($list_records)) { ?>
                                <tr class="data">
                                    <td colspan="10"
                                        align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
                                </tr>
                                <?php
                            } else {
                                $i = 1;
                                foreach ($list_records as $row) {
                                    if ($this->session->userdata('admin_role') == ROLE_CLIENT_ADMIN) {

                                        if ($row->OrderStatus == "Cancel") {
                                            $order_status = 'order-cancel';
                                        } elseif ($row->OrderStatus == "Approved") {
                                            $order_status = 'order-complete';
                                        } else {
                                            $order_status = "";
                                        }
                                    }
                                    ?>
                                    <tr class="data <?php echo $i % 2 == 0 ? "alter" : ""; ?>"
                                        id="data-<?php echo $row->Id; ?>">
                                        <td><?php echo ++$j; ?></td>
                                        <td class="team-logo roundimg lbl-image">
                                            <?php
                                            if($_GET['id'] != '')
                                            { ?>
                                                <input class="bill-order-id" name="bill_order_id[]" type="checkbox" value="<?php echo $row->Id; ?>" >
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $row->CategoryName; ?></td class="lbl-fruit">
                                        <td class="lbl-product-name"><?php echo $row->ProductName; ?></td>
                                        <td><?php echo $row->AdminName; ?></td>
                                        <td class="price-show lbl-price"><i class="fa fa-inr"></i> <?php echo number_format($row->Price, 2); ?></td>
                                        <td class="lbl-qty"><?php echo $row->Qty; ?></td>
                                        <td class="price-show lbl-total"><i class="fa fa-inr"></i> <?php echo number_format($row->Total, 2); ?></td>
                                        <td class="action tcenter">
                                            <a href="<?php echo site_url('manage/order/information/' . $row->Id); ?>">
                                                <span class="view-detail"></span></a>
                                        </td>
                                    </tr>
                                    <?php $i++;
                                }
                            } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="pagination-wrapper">
                        <ul class="pagination">
                            <?php echo $pagination; ?>
                        </ul>
                    </div>
                    <input type="hidden" name="csrf_name" id="csrf_name"
                           value="<?php echo htmlspecialchars($unique_form_name); ?>"/>
                    <input type="hidden" name="csrf_token" id="csrf_token"
                           value="<?php echo htmlspecialchars($token); ?>"/>
                    <input type="hidden" id="hdn" value="<?php echo $this->admin_model->Encryption($tbl); ?>"/>
                    <!-- /.box-body -->
          </form>

        </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>


