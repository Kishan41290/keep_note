<div class="content-wrapper">
	<section class="content-header">
		<div class="col-md-1"></div>
		<h1>
			<?php echo $title; ?>
			<small>Preview</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<!-- right column -->
			<div class="col-md-1"></div>
			<div class="col-md-10">
				<!-- Horizontal Form -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><?php echo $title; ?></h3>
					</div>
					<!-- form start -->
					<?php include_once('includes/display_msg.php');?>
					<form role="form" class="form-horizontal" id="setting_form" action="<?php echo $action; ?>" method="post">
						<div class="box-body">
							<div class="form-group">
								<label for="exampleInputtitle" class="col-sm-2 control-label">Title</label>
								<div class="col-md-5 col-sm-10">
							   		<input type="type" name="title" id="title" id="exampleInputtitle" class="form-control validate[required] <?php if(form_error('value'))echo 'err_red';?>" value="<?php echo set_value('title',$this->form_data->title);?>"/>
							    </div>
							</div>
							<div class="form-group">
								<label for="exampleInputkeytext" class="col-sm-2 control-label">Key</label>
								<div class="col-md-5 col-sm-10">
									<input type="text" name="keytext" id="exampleInputkeytext"  <?php if($add=='Yes'): echo " "; else: echo "readonly"; endif;?> class="form-control validate[required] keyText <?php if(form_error('value'))echo 'err_red';?>" value="<?php echo set_value('keytext',$this->form_data->keytext);?>"/>
							    </div>
							</div>

							<?php if(@$field->Id=='17' || @$field->Id=='36') {?>
								<div class="form-group">
									<label for="exampleInputvalue" class="col-sm-2 control-label">Value</label>
									<div class="col-md-5 col-sm-10">
										<textarea id="exampleInputvalue" class="form-control <?php if(form_error('value'))echo 'err_red';?>" name="value" ><?php echo set_value('value',$this->form_data->value);?></textarea>
									</div>
								</div>
							<?php }
							else{ ?>
								<div class="form-group">
									<label for="exampleInputvalue" class="col-sm-2 control-label">Value</label>
									<div class="col-md-5 col-sm-10">
									<input type="text" id="exampleInputvalue" name="value" class="form-control <?php if(form_error('value'))echo 'err_red';?>" value="<?php echo set_value('value',$this->form_data->value);?>"/>
									</div>
								</div>
							<?php } ?>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label"></label>
								<span class="marginleft-btn">
							    	<input type="submit" class="btn btn-primary mg-right-10" value="<?php echo $this->lang->line('submit');?>" name="submit" id="submit" />
						        	<a href="<?php echo $link_back; ?>" class="btn btn-default"><?php echo $this->lang->line('back');?></a>
								</span>
							</div>
						</div>
						<input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
						<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
					</form>
				</div>
			</div>
			<!--/.col (right) -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
