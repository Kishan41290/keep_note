

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
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
						<h3 class="box-title"> <?php echo $title; ?></h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<?php include_once('includes/display_msg.php');?>

					<form class="form-horizontal" id="category_form" enctype="multipart/form-data" action="<?php echo $action; ?>" method="post">

						<div class="box-body">
							<div class="form-group">
								<label for="inputEmail3" class="col-sm-2 control-label">Name</label>
								<div class="col-md-5 col-sm-10">
									<input placeholder="Category Name" class="form-control" id="category_name" name="category_name" type="text" value="<?php echo set_value('name',$this->form_data->categoryname);?>" >
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
									<button type="submit" class="btn btn-primary mg-right-10">Submit</button>
									<a href="<?php echo site_url('manage/category') ?>" class="btn btn-default ">Cancel</a>
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
