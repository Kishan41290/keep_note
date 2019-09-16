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
						<h2 class="inner-section" style="padding-left: 20px;"><?php echo $result->ProductName;
							if($result->OrderStatus=='Pending' && $this->session->userdata('admin_role') == ROLE_SUPPER_ADMIN) {?>
							<div id="<?php echo $result->Id; ?>" class="btn label-warning order-status pull-right" style="margin-right: 22px;">
								<span>Pending</span>
							</div>
                         <?php	} ?>
						</h2>

						<div class="form-wrapper diff">
								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Order Id</label>
									<div class="col-md-10 col-sm-10 form-field"><?php echo $result->Id; ?> </a></div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Category Name</label>
									<div class="col-md-10 col-sm-10 form-field"><?php echo $result->CategoryName; ?> </a></div>
								</div>
								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Product Name</label>
									<div class="col-md-10 col-sm-10 form-field"><?php echo $result->ProductName; ?> </a></div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Client Name</label>
									<div class="col-md-10 col-sm-10 form-field"><?php echo $result->AdminName; ?> </a></div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Client Email</label>
									<div class="col-md-10 col-sm-10 form-field"><a href="mailto:<?php echo $result->AdminEmail; ?>" ><?php echo $result->AdminEmail; ?></a></div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Client Address</label>
									<div class="col-md-10 col-sm-10 form-field"><?php echo $result->AdminAddress; ?></div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Client Mobile</label>
									<div class="col-md-10 col-sm-10 form-field"><?php echo $result->AdminMobile; ?></div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Quantity</label>
									<div class="col-md-10 col-sm-10 form-field"><?php echo $result->Qty; ?></div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Price</label>
									<div class="col-md-10 col-sm-10 form-field price-show"><i class="fa fa-inr"></i> <?php echo number_format($result->Price,2); ?> </div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Total</label>
									<div class="col-md-10 col-sm-10 form-field price-show"><i class="fa fa-inr"></i> <?php echo number_format($result->Total,2); ?> </div>
								</div>

								<div class="row">
									<label class="col-md-2 col-sm-2 form-lbl">Order Status</label>
									<div class="col-md-10 col-sm-10 form-field">
										<?php
										if($result->OrderStatus=='Cancel')
										{
											if($result->CancelBy == $this->session->userdata('adminid'))
											{
												$cancel_by = $this->session->userdata('adminname');
											}
											else{
												$cancel_by = 'Super Admin';
											}

											echo '<font color="red" style="font-weight: 600;">Cancel (Canceled by ' .$cancel_by.')</font>' ;
										}
										else
										{
											echo $result->OrderStatus=='Pending'?'<font color="#f39c12" style="font-weight: 600;">Pending</font>':'<font color="green" style="font-weight: 600;">Approved</font>' ;
										}

									 ?></div>
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
