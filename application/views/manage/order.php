<div class="content-wrapper">
    <section class="content-header content-section">
        <?php
        if($this->session->userdata('admin_role') == ROLE_SUPPER_ADMIN)
        {?>
            <h1 class="header-page">
                <?php echo $title; ?>
                <small style="font-weight: 700;margin-left: 18px;">Client List</small>
            </h1>
            <select id="client-filter" class="form-control client-dropdown" name="client-filter">
                <option value="" selected>All</option>
                <?php foreach ($client_list as $r)
                { ?>
                    <option value="<?php echo $r->Id; ?>" <?php echo $_GET['id'] == $r->Id ? 'selected' : '' ?> ><?php echo $r->Name; ?></option>
                    <?php
                } ?>
            </select>
            <?php
        }else
        {?>
            <h1 class="header-page">
                <?php echo $title; ?>
                <small style="font-weight: 700;margin-left: 18px;">Order Status</small>
            </h1>
            <select id="filter" class="form-control filter-dropdown" name="filter">
                <option value="">All</option>
                <option value="Pending" <?php  echo $_GET['filter'] == 'Pending' ? 'selected' : '' ?> >Pending</option>
                <option value="Approved" <?php echo $_GET['filter'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Cancel" <?php echo $_GET['filter'] == 'Cancel' ? 'selected' : '' ?>>Cancel</option>
            </select>
            <?php
        }
        ?>

        <div class="inner-heading">
            <form class="search-form" method="get" action="<?php echo $search_action; ?>">
                <input type="text" class="form-control" name="search"
                       value="<?php echo htmlspecialchars($this->input->get('search')); ?>"
                       placeholder="<?php echo $this->lang->line('search'); ?>"/>
                <?php if ($this->input->get('search') != "") { ?>
                    <a href="<?php echo site_url('manage/order'); ?>" class="list_all" style="margin-left: -50px;"></a>
                <?php } ?>
            </form>
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
                                <?php if ($this->session->userdata('admin_role') == ROLE_SUPPER_ADMIN) { ?>
                                    <th width="8%" class="tcenter"><?php echo $this->lang->line('status'); ?></th>
                                <?php } ?>
                                <th>OrderStatus</th>
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
                                        <td class="team-logo roundimg lbl-image"><img width="40px;"
                                                                                      src="<?php echo $row->ProImage != '' ? site_url('uploads/product/thumb/' . $row->ProImage) : site_url('uploads/default/default.png'); ?>"/>
                                        </td>
                                        <td><?php echo $row->CategoryName; ?></td class="lbl-fruit">
                                        <td class="lbl-product-name"><?php echo $row->ProductName; ?></td>
                                        <td><?php echo $row->AdminName; ?></td>
                                        <td class="price-show lbl-price"><i class="fa fa-inr"></i> <?php echo number_format($row->Price, 2); ?></td>
                                        <td class="lbl-qty"><?php echo $row->Qty; ?></td>
                                        <td class="price-show lbl-total"><i class="fa fa-inr"></i> <?php echo number_format($row->Total, 2); ?></td>
                                        <?php if ($this->session->userdata('admin_role') == ROLE_SUPPER_ADMIN) { ?>
                                            <td class="tcenter">
                                                <div class="switch <?php echo $row->Status == "Enable" ? "on" : ""; ?>">
                                                    <div class="knob"></div>
                                                </div>
                                            </td>
                                        <?php } ?>
                                        <td class="tcenter">
                                            <?php if ($row->OrderStatus == 'Pending') {
                                                if ($this->session->userdata('admin_role') == ROLE_SUPPER_ADMIN) {
                                                    $order_status_change = 'btn label-warning order-status';
                                                } else {
                                                    $order_status_change = 'label label-warning';
                                                }
                                            } elseif ($row->OrderStatus == 'Cancel') {
                                                $order_status_change = "label label-danger";
                                            } else {
                                                $order_status_change = "label label-success";

                                            }; ?>
                                            <div id="<?php echo $row->Id; ?>"
                                                 class="<?php echo $order_status_change; ?> "
                                                 style="font-size: 14px;font-weight: 600;">
                                                <span><?php echo $row->OrderStatus; ?></span>
                                            </div>

                                        </td>
                                        <td class="action tcenter">


                                            <?php
                                            if ($row->OrderStatus != "Cancel") {
                                                ?>
                                                    <a href="javascript:;" class="order_edit"> <span
                                                            class="edit"></span></a>

                                            <?php } ?>
                                            <a href="<?php echo site_url('manage/order/information/' . $row->Id); ?>">
                                                <span class="view-detail"></span></a>

                                            <?php if ($row->OrderStatus == "Pending") { ?>
                                                <a href="javascript:;" class="order_cancel"
                                                   id="<?php echo $row->Id; ?>"><img width="30px;"
                                                                                     style="vertical-align: baseline"
                                                                                     src="<?php echo site_url('uploads/default/cancel.png'); ?>"/></a>

<!--                                                --><?php
//                                                if ($this->session->userdata('admin_role') == ROLE_SUPPER_ADMIN) { ?>
<!--                                                    <a href="javascript:;"><p class="delete">-->
<!--                                                            <span  class="delete1"></span></p></a>-->
<!--                                                    --><?php
//                                                }
//                                                ?>
                                            <?php } ?>

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
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>


<div class="modal" id="edit_order_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post" id="edit_order_form">
                <div class="modal-header">
                    <button type="button" class="close close_button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h3 class="modal-title">Place Order</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-3">
                            <img id="modal_image" width="120px;" src=""/>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Product Name : </label>
                                    <div class="col-md-4 col-sm-10">
                                        <input class="form-control" id="modal_product_name" name="modal_product_name"
                                               type="text" value="" style="margin-bottom: 4px;" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Your Price : </label>
                                    <div class="col-md-4 col-sm-10">
                                        <input class="form-control" id="modal_price" name="modal_price" type="text"
                                               value="" style="margin-bottom: 4px;" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Qty : </label>
                                    <div class="col-md-4 col-sm-10">
                                        <input placeholder="Qty" class="form-control" id="modal_qty" name="modal_qty"
                                               type="number" min="0" value="" style="margin-bottom: 4px;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Total : </label>
                                    <div class="col-md-4 col-sm-10">
                                        <input class="form-control" id="modal_total" name="modal_total" type="text"
                                               value="" style="margin-bottom: 4px;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="order_id" id="order_id" value="">
                <input type="hidden" name="order_url" id="order_url" value="">

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left close_button" data-dismiss="modal">Close
                    </button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
