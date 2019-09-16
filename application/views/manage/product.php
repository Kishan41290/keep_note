<div class="content-wrapper">
    <section class="content-header">
        <h1 class="header-page">
            <?php echo $title; ?>
            <small>Overview</small>
        </h1>
        <?php if($this->session->userdata('admin_role')==ROLE_SUPPER_ADMIN){ ?>
        <div class="header-menu">
			<span>
				<a class="btn btn-info btn-md" href="<?php echo $link_add; ?>" >Add New <?php echo $title; ?></a></li>
			</span>
        </div>
        <?php } ?>

    </section>
    <?php include_once('includes/display_msg.php');?>
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
                                <th>Product Name</th>
                                <th>Category Name</th>
                                <th>Price(<i class="fa fa-inr"></i>)</th>
                                <?php if($this->session->userdata('admin_role')==ROLE_SUPPER_ADMIN){ ?>
                                <th width="8%" class="tcenter"><?php echo $this->lang->line('status'); ?></th>
                                <?php } ?>
                                <th width="8%" class="tcenter"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(empty($list_records))
                            { ?>
                                <tr class="data">
                                    <td colspan="7" align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
                                </tr>
                                <?php
                            }
                            else
                            {
                                $i=1;
                                foreach($list_records as $row){ ?>
                                    <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->Id; ?>">
                                        <td><?php echo ++$j;?></td>
                                        <td class="team-logo roundimg"><img width="40px;" src="<?php echo $row->Image!='' ? site_url('uploads/product/thumb/'.$row->Image) : site_url('uploads/default/default.png') ; ?>" /></td>
                                        <td><?php echo $row->ProductName; ?></td>
                                        <td><?php echo $row->CategoryName; ?></td>
                                        <?php
                                        $ref = '<span style="color: green; font-size: 20px;"><i class="fa fa-inr"></i>  '.number_format($row->Price,2).'</span>';

                                        ?>
                                        <td><?php echo $ref; ?></td>

                                        <?php if($this->session->userdata('admin_role')==ROLE_SUPPER_ADMIN){ ?>
                                        <td class="tcenter">
                                            <div class="switch <?php echo $row->Status=="Enable" ? "on" :""; ?>">
                                                <div class="knob"></div>
                                            </div>
                                        </td>
                                        <td class="action tcenter">
                                            <a href="<?php echo $edit_link."/".$row->Id?>"> <span class="edit"></span></a>
                                            <a href="javascript:;"><p class="delete"><span class="delete1"></span></p></a>
                                        </td>
                                        <?php } ?>
                                         <?php if($this->session->userdata('admin_role')==ROLE_CLIENT_ADMIN){ ?>
                                             <td>
                                                 <button type="button"  class="btn btn-block btn-primary placeOrder" value="<?php echo $row->Id; ?>" ><div class="fa fa-cart-plus"></div> Place Order</button>
                                             </td>
                                         <?php } ?>

                                        </tr>
                                    <?php $i++; }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
                    <input type="hidden" id="hdn" value="<?php echo $this->admin_model->Encryption($tbl); ?>" />
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>

<div class="modal" id="place_order_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo site_url('manage/order/placeOrder') ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close close_button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h3 class="modal-title">Place Order</h3>
                </div>
                <div class="modal-body">
           <div class="row">
               <div class="col-md-1"></div>
               <div class="col-md-3">
                  <img id="modal_image" width="120px;" src="" />
               </div>
               <div class="col-md-8">
                   <div class="row">
                       <div class="form-group">
                           <label class="col-md-4 control-label">Product Name : </label>
                           <div class="col-md-4 col-sm-10">
                               <input class="form-control" id="modal_product_name" name="modal_product_name" type="text" value="" style="margin-bottom: 4px;" readonly >
                           </div>
                       </div>
                   </div>
                   <div class="row" >
                       <div class="form-group">
                           <label class="col-md-4 control-label">Your Price : </label>
                           <div class="col-md-4 col-sm-10">
                               <input class="form-control" id="modal_price" name="modal_price" type="text" value="" style="margin-bottom: 4px;" readonly >
                           </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group">
                           <label class="col-md-4 control-label">Qty : </label>
                           <div class="col-md-4 col-sm-10">
                               <input placeholder="Qty" class="form-control" id="modal_qty" name="modal_qty" type="number" mi value="" style="margin-bottom: 4px;" >
                           </div>
                       </div>
                   </div>
                   <div class="row">
                       <div class="form-group">
                           <label class="col-md-4 control-label">Total : </label>
                           <div class="col-md-4 col-sm-10">
                               <input class="form-control" id="modal_total" name="modal_total" type="text" value="" style="margin-bottom: 4px;" readonly >
                           </div>
                       </div>
                   </div>
               </div>
           </div>
                </div>
                <input type="hidden" name="modal_product_id" id="modal_product_id" value=""/>
                <input type="hidden" name="csrf_name1" id="csrf_name1" value=""/>
                <input type="hidden" name="csrf_token1" id="csrf_token1" value=""/>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left close_button" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="place_order_button">Place Order</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

