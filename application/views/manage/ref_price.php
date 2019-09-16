<input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>

<div class="content-wrapper">
    <section class="content-header content-section">
        <h1 class="header-page">
            <?php echo $title; ?>
            <small style="font-weight: 700;margin-left: 18px;">Client List</small>
        </h1>
        <select id="ref-client-filter" class="form-control ref-client-dropdown" name="ref-client-filter">
            <option value="" selected>All</option>
            <?php foreach ($client_list as $r)
            { ?>
                <option value="<?php echo $r->Id; ?>" <?php echo $_GET['id'] == $r->Id ? 'selected' : '' ?> ><?php echo $r->Name; ?></option>
                <?php
            } ?>
        </select>
        <div class="header-menu" style="padding:0px; margin-left: 10px;">
			<span>
				<a class="btn btn-info btn-md" href="<?php echo site_url('manage/reference_price/add'); ?>" >Add New Reference Price</a></li>
            </span>
        </div>
        <div class="inner-heading">
            <form class="search-form" method="get" action="<?php echo $search_action; ?>">
                <input type="text" class="form-control" name="search" value="<?php echo htmlspecialchars($this->input->get('search')); ?>" placeholder="<?php echo $this->lang->line('search'); ?>" />
                <?php if($this->input->get('search')!=""){ ?>
                    <a href="<?php echo site_url('manage/reference_price'); ?>" class="list_all" style="margin-left: -50px;"></a>
                <?php } ?>
            </form>
        </div>
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
                                <th  width="1%">#</th>
                                <th width="3%"></th>
                                <th >Client Email</th>
                                <th >Client Name</th>
                                <th >Category</th>
                                <th >Product</th>
                                <th >Price(<i class="fa fa-inr"></i>)</th>
                                <th width="8%">Status</th>
                                <th width="8%" class="tcenter"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (empty($list_records)) { ?>
                                <tr class="data">
                                    <td colspan="8" align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
                                </tr>
                                <?php
                            } else {
                                $i = 1;
                                foreach (@$list_records as $row):?>
                                    <tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->Id; ?>">
                                        <td><?php echo ++$j; ?></td>
                                        <?php $img = $row->ProImage; ?>
                                        <td><img src="<?php echo $img!='' ? site_url('uploads/product/thumb/'.set_value('image',$img)) : site_url('uploads/default/default.png'); ?>" alt="display_image" class="imgdp" height="34px" width="35px"> </td>
                                        <td><a href="mailto:<?php echo $row->UserEmail; ?>" ><?php echo $row->UserEmail; ?></a></td>
                                        <td><?php echo $row->UserName; ?></td>
                                        <td><?php echo $row->CatName; ?></td>
                                        <td><?php echo $row->ProName; ?></td>

                                        <?php  $ref ='<span style="color: green; font-size: 20px;"><i class="fa fa-inr"></i> '.number_format($row->Price,2).'</span><span style="color: #727272;text-decoration: line-through;margin-left: 4px;font-size: 14px;"> <i class="fa fa-inr"></i> '.number_format($row->CurrentPrice,2).'</span>'; ?>

                                        <td class="price-show"><?php echo $ref; ?></td>


                                        <td class="tcenter">
                                            <div class="switch <?php echo $row->Status=="Enable" ? "on" :""; ?>">
                                                <div class="knob"></div>
                                            </div>
                                        </td>
                                        <td class="action tcenter">
                                            <a href="<?php echo $edit_link."/".$row->Id?>"> <span class="edit"></span></a>
                                            <a href="javascript:;"><p class="delete"><span class="delete1"></span></p></a>
                                        </td>
                                    </tr>
                                    <?php $i++;endforeach;
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <div class="pagination-wrapper">
                    <ul class="pagination">
                        <?php echo $pagination; ?>
                    </ul>
                </div>
                <!-- /.box -->
                <input type="hidden" id="hdn" value="<?php echo $this->admin_model->Encryption($tbl); ?>" />
            </div>
        </div>
    </section>
</div>
