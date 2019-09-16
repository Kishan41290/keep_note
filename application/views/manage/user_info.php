<div class="content-wrapper" xmlns="http://www.w3.org/1999/html">
	<section class="content-header" style="margin-bottom: 15px;float: left;">
		<h1 class="header-page">
			<?php echo $title; ?>
			<small>Overview</small>
		</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<!-- /.box-header -->
					<div class="box-body table-responsive no-padding">
						<!-- DETAIL CODE -->
						<h2 class="inner-section" style="padding-left: 20px;">
							<?php echo $result->Email; ?>
						</h2>

						<div class="form-wrapper diff">
								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Name</label>
									<div class="col-md-10 col-sm-10 form-field">
										<?php echo $result->FirstName.' '.$result->LastName; ?>
									</div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Address</label>
									<div class="col-md-10 col-sm-10 form-field">
									<?php echo $result->Address; ?> </a></div>
								</div>
								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Product Name</label>
									<div class="col-md-10 col-sm-10 form-field">
									<?php echo $result->Type; ?> </a></div>
								</div>

							
								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl"><?php echo $this->lang->line('creation_info'); ?></label>
									<div class="col-md-10 col-sm-10 form-field">
										<span>
											<b><?php echo $this->lang->line('date'); ?>: </b><?php echo ltrim(date("dS M, Y",strtotime($result->CreatedDate)),0); ?>&nbsp;&nbsp;
											<b>Time: </b><?php echo date("h:i A",strtotime($result->CreatedDate));?>&nbsp;&nbsp;
											<b><?php echo $this->lang->line('ip_address'); ?>: </b> <?php echo long2ip($result->CreatedIp); ?>&nbsp;&nbsp;
											</span>
									</div>
								</div>
						</div>
				</div>
			</div>
		</div>
	</section>
</div>
