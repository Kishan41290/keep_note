<div class="content-wrapper">
    <section class="content-header">
        <h1 class="header-page">
            <?php echo $title; ?>
            <small>Overview</small>
        </h1>
        <div class="header-menu">
			<span>
				<a class="btn btn-info btn-md" href="<?php echo site_url('manage/category/insert'); ?>" >Add New <?php echo $title; ?></a></li>
			</span>
        </div>
    </section>
    <?php include_once('includes/display_msg.php');?>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table id="example1" class="table listing" role="grid" aria-describedby="example1_info">
                            <thead>
                            <tr>
                                <th width="1%">#</th>
                                <th ><?php echo $this->lang->line('name'); ?></th>
                                <th width="8%" class="tcenter"><?php echo $this->lang->line('status'); ?></th>
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
                                        <td><?php echo $row->CategoryName; ?></td>
                                        <td class="tcenter">
                                            <div class="switch <?php echo $row->Status=="Enable" ? "on" :""; ?>">
                                                <div class="knob"></div>
                                            </div>
                                        </td>
                                        <td class="action tcenter">
                                            <a href="<?php echo $edit_link."/".$row->Id?>" title="jjdhjsh"><span class="edit"></span></a>
                                            <a href="javascript:;"><p class="delete" ><span class="delete1"></span></p></a>
                                        </td>
                                    </tr>
                                    <?php $i++; }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination-wrapper">
                        <ul class="pagination">
                            <?php echo $pagination; ?>
                        </ul>
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


