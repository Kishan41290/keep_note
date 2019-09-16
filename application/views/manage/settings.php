<input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php echo $title; ?>
            <small>Overview</small>
        </h1>
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
                                <th  width="1%">#</th>
                                <th ><?php echo $this->lang->line('title'); ?></th>
                                <th ><?php echo $this->lang->line('key'); ?></th>
                                <th ><?php echo $this->lang->line('value'); ?></th>
                                <th width="8%" class="tcenter"><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (empty($list_records)) { ?>
                                <tr class="data">
                                    <td colspan="6" align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
                                </tr>
                                <?php
                            } else {
                                $i = 1;
                                foreach (@$list_records as $row):?>
                                    <tr <?php if ($i % 2 == 0): ?>class="alter"<?php endif; ?> >
                                        <td><?php echo ++$j; ?></td>
                                        <td><?php echo $this->admin_model->filterOutput($row->Fieldname); ?></td>
                                        <td><?php echo $this->admin_model->filterOutput($row->Keytext); ?></td>
                                        <td><?php echo $this->admin_model->filterOutput($row->Value); ?></td>
                                        <td class="action tcenter">
                                            <a href="<?php echo $edit_link."/".$row->Id?>" ><span class="edit"></span></a>
                                        </td>
                                    </tr>
                                    <?php $i++;endforeach;
                            } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
</div>
