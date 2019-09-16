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
					<!-- /.box-header -->
					<!-- form start -->
					<?php include_once('includes/display_msg.php');?>
					<form class="form-horizontal" id="<?php  echo $method=="Edit" ? "admin_form1" : "admin_form"; ?>" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">
						<div class="box-body">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Email</label>
								<div class="col-md-5 col-sm-10">
									<input placeholder="Email" class="form-control" id="email" name="email" type="email" value="<?php echo set_value('email', $this->form_data->email); ?>" >
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Name</label>
								<div class="col-md-5 col-sm-10">
									<input placeholder="Name" class="form-control" id="name" name="name" type="text" value="<?php echo set_value('name', $this->form_data->name); ?>" >
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Address</label>
								<div class="col-md-5 col-sm-10">
									<textarea  placeholder="Address" class="form-control" id="address" name="address" rows="4" cols="50"><?php echo set_value('address', $this->form_data->address); ?> </textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Mobile No</label>
								<div class="col-md-5 col-sm-10">
									<input placeholder="Mobile Number" class="form-control" id="mobile_no" name="mobile_no" type="text" value="<?php echo set_value('mobile_no', $this->form_data->mobile); ?>" >
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Image</label>
								<div class="col-md-5 col-sm-10">
									<input type="file" name="file" id="upload_dp" class="form-control img-upload-ctrl">
									<?php if($method=="Edit"){ ?>
										<?php $img = $this->form_data->image; ?>
										<img src="<?php echo $img!='' ? site_url('uploads/profile/thumb/'.set_value('image',$img)) : site_url('uploads/default/default.png'); ?>" alt="display_image" class="imgdp" height="34px" width="35px">
									<?php } ?>
									<input type="hidden" name="image" value="<?php echo set_value('image',$this->form_data->image); ?>" />
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Password</label>
								<div class="col-md-5 col-sm-10">
									<input placeholder="Password" class="form-control" type="password" name="password" id="password">
								</div>
							</div>

							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Confirm Password</label>
								<div class="col-md-5 col-sm-10">
									<input placeholder="Confirm Password" class="form-control" type="password" name="confirm_password" id="confirm_password">
								</div>
							</div>

						</div>
						<!-- /.box-body -->
						<input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
						<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>

						<div class="box-footer">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label"></label>
								<span class="marginleft-btn">
									<button type="submit" class="btn btn-primary mg-right-10">Update</button>
									<a href="<?php echo site_url('manage/home') ?>" class="btn btn-default ">Cancel</a>
								</span>
							</div>
						</div>
						<!-- /.box-footer -->
					</form>



				</div>
			</div>
			<!--/.col (right) -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
