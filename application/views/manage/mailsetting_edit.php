<script type="text/javascript" src="<?php echo site_url()."editor/ckeditor/ckeditor.js" ?>" ></script>
<script>
$(document).ready(function(){
	$(function () {
		CKEDITOR.replace('ckeditor');
	});
});
</script>
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
					<form class="form-horizontal" role="form" id="mail_form" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
						<div class="box-body">
							<div class="form-group">
								<label for="title" class="col-sm-2 control-label">Title</label>
								<div class="col-md-9 col-sm-10">
									<input type="text" name="title" id="title" class="form-control" value="<?php echo set_value('title',$this->form_data->title);?>"/>
						        </div>
							</div>
							<div class="form-group">
								<label for="email" class="col-sm-2 control-label">From Email</label>
								<div class="col-md-9 col-sm-10">
									<input type="text" name="email" id="email" class="form-control" value="<?php echo set_value('email',$this->form_data->email);?>"/>
								</div>
							</div>
							<div class="form-group">
								<label for="from_text" class="col-sm-2 control-label">From Text</label>
								<div class="col-md-9 col-sm-10">
									<input type="text" name="from_text" id="from_text" class="form-control" value="<?php echo set_value('from_text',$this->form_data->from_text);?>"/>
								</div>
							</div>
							<div class="form-group">
								<label for="subject" class="col-sm-2 control-label">Subject</label>
								<div class="col-md-9 col-sm-10">
									<input type="text" name="subject" id="subject" class="form-control"  value="<?php echo set_value('subject',$this->form_data->subject);?>"/>
								</div>
							</div>
							<div class="form-group">
								<label for="ckeditor" class="col-sm-2 control-label">Mail Content</label>
								<div class="col-md-9 col-sm-10">
									<textarea id="ckeditor" name="page_content" class="form-control"  ><?php echo set_value('page_content',$this->form_data->page_content);?></textarea>
								</div>
							</div>
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
						<input type="hidden" id="hdn" value="<?php echo $this->admin_model->Encryption($tbl); ?>" />
						<?php if($hdn_id!=''){ ?>
							<input type="hidden" id="hdn_id" value="<?php echo $hdn_id; ?>" />
						<?php } ?>
					</form>
				</div>
			</div>
			<!--/.col (right) -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>















