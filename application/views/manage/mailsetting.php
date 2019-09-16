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
								<th width="2%">#</th>
								<th><?php echo $this->lang->line('title'); ?></th>
								<th width="5%" class="tcenter"><?php echo $this->lang->line('action'); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php
							if(empty($list_records))
							{ ?>
								<tr class="data">
									<td colspan="4" align="center"><?php echo $this->lang->line('no_rec_found'); ?></td>
								</tr>
								<?php
							}
							else
							{
								$i=1;
								foreach($list_records as $row){ ?>
									<tr class="data <?php echo $i%2==0 ? "alter" : ""; ?>" id="data-<?php echo $row->Id; ?>">
										<td><?php echo ++$j;?></td>
										<td><?php echo $this->admin_model->filterOutput($row->Title); ?></td>
										<td class="action tcenter">
											<a href="<?php echo $edit_link."/".$row->Id?>" ><span class="edit"></span></a>
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
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
		</div>
	</section>
</div>
