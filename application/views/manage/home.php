<!--<script src="--><?php //echo site_url()."themes/manage/js/highcharts.js" ?><!--"></script>-->
<!-- <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script> -->
<script>
	function myFunction() {
		var x = document.getElementById("filterby").value;
		window.location= siteUrl+"manage/home/?filterby="+x;
	}
</script>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Calendar
			<small>Control panel</small>
		</h1>
	</section>
	<!-- Main content -->
	<section class="content">

		<?php if($this->session->userdata('admin_role')==ROLE_SUPPER_ADMIN){ ?>
			<div class="row">
					<!-- ./col -->
					<div class="col-lg-2 col-xs-6">
						<!-- small box -->
						<div class="small-box bg-yellow">
							<div class="inner">
								<h3><?php // echo $user_counter; ?></h3>
								<p>User Registrations</p>
							</div>
							<div class="icon">
								<i class="ion ion-person-add"></i>
							</div>
							<a href="<?php // echo site_url('manage/user'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<!-- ./col -->
					<div class="col-lg-2 col-xs-6">
						<!-- small box -->
						<div class="small-box bg-maroon">
							<div class="inner">
								<h3><?php // echo $pro_counter; ?></h3>
								<p>Total Products</p>
							</div>
							<div class="icon">
								<i class="ion ion-bag"></i>
							</div>
							<a href="<?php echo site_url('manage/product'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>

					<!-- ./col -->
					<div class="col-lg-2 col-xs-6">
						<!-- small box -->
						<div class="small-box bg-purple">
							<div class="inner">
								<h3><?php // echo $cat_counter; ?></h3>
								<p>Total Category</p>
							</div>
							<div class="icon">
								<i class="ion ion-pie-graph"></i>
							</div>
							<a href="<?php // echo site_url('manage/category'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<!-- ./col -->
					<div class="col-lg-2 col-xs-6">
						<!-- small box -->
						<div class="small-box bg-aqua">
							<div class="inner">
								<h3><?php // echo $order_counter; ?></h3>
								<p>New Orders</p>
							</div>
							<div class="icon">
								<i class="fa fa-shopping-cart"></i>
							</div>
							<a href="<?php echo site_url('manage/order?filter=Pending'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<!-- ./col -->
					<div class="col-lg-2 col-xs-6">
						<!-- small box -->
						<div class="small-box bg-green">
							<div class="inner">
								<h3><?php // echo $order_complete_counter; ?></h3>
								<p>Orders Approved</p>
							</div>
							<div class="icon">
								<i class="ion ion-stats-bars"></i>
							</div>
							<a href="<?php echo site_url('manage/order?filter=Approved'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
					<!-- ./col -->
					<div class="col-lg-2 col-xs-6">
						<!-- small box -->
						<div class="small-box bg-red">
							<div class="inner">
								<h3><?php // echo $order_cancel_counter; ?></h3>
								<p>Order Canceled</p>
							</div>
							<div class="icon">
								<i class="fa fa-times"></i>
							</div>
							<a href="<?php echo site_url('manage/order?filter=Cancel'); ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						</div>
					</div>
			</div>
			<form action="<?php echo site_url('manage/home/'); ?>" method="get">
				<div class="row">
					<!-- ORDERS AND PRODUCTS GRAPH -->
					<div class="col-md-6 col-sm-12">
						<select class="form-control" style="margin-bottom: 10px; width: auto;" name="filterby" onchange="myFunction()" id="filterby">
							<option value="seven" <?php echo ($this->input->get('filterby')=='seven' || $this->input->get('filterby')=='')?'selected':''; ?>>Last 7 days</option>
							<option value="thirty" <?php echo ($this->input->get('filterby')=='thirty')?'selected':''; ?>>Last 1 Month</option>
							<option value="sixty" <?php echo ($this->input->get('filterby')=='sixty')?'selected':''; ?>>Last 2 Month</option>
							<option value="ninty" <?php echo ($this->input->get('filterby')=='ninty')?'selected':''; ?>>Last 3 Month</option>
							<option value="sixmonth" <?php echo ($this->input->get('filterby')=='sixmonth')?'selected':''; ?>>Last 6 Month</option>
						</select>
						<div class="first-graph">
							<div id="container"></div>
						</div>
					</div>
					<!-- PRODUCTS GRAPH -->
					<div class="col-md-6 col-sm-12">
						<form action="<?php echo site_url('manage/home'); ?>" method="get">
							<select class="form-control filter-category-drpdown" name="cid" id="category">
								<option value=""  >Select Category</option>
								<?php foreach ($cat_list as $r){ ?>
									<option value="<?php echo $r->Id; ?>" <?php echo ($this->input->get('cid')==$r->Id)?'selected':''; ?>><?php echo $r->CategoryName; ?></option>
								<?php } ?>
							</select>
							<select class="form-control filter-product-drpdown"  name="pid" id="product">
								<option value="" >Select Product</option>
								<?php foreach ($pro_list as $r){ ?>
									<option value="<?php echo $r->Id; ?>" <?php echo ($this->input->get('pid')==$r->Id)?'selected':''; ?>><?php echo $r->ProductName; ?></option>
								<?php } ?>
							</select>
							<button class="primary btn-filter" >Go</button>
						</form>
						<div class="first-graph">
							<div id="container-order-list"></div>
						</div>
					</div>
				</div>
			</form>

		<div class="row">

				<div class="col-md-8 col-sm-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Orders</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
									<tr>
										<th>#</th>
										<th></th>
										<th>Category</th>
										<th>Item</th>
										<th>Qty</th>
										<th>Price</th>
										<th>Status</th>
									</tr>
									</thead>
									<tbody>
								<!-- 	<?php $j=1; foreach ($latest_order_list as $o){ ?>
										<tr>
											<td><?php echo $j; ?></td>
											<td class="product-img"><img style="border-radius: 50%;" class="img-rounded" src="<?php echo $o->Image!=''?site_url('uploads/product/thumb/'.$o->Image):site_url('uploads/default/default.png'); ?>" ></td>
											<td><?php echo $o->CategoryName; ?></td>
											<td><?php echo $o->ProductName; ?></td>
											<td><?php echo $o->Qty; ?></td>
											<td><?php echo $o->Price; ?></td>
											<?php $c = $o->OrderStatus;
												if($c=='Pending'){ $class = 'label-warning'; }
												elseif($c=='Approved'){ $class = 'label-info'; }
												elseif($c=='Shiped'){ $class = 'label-success'; }
												else{ $class = 'label-danger'; } ?>
											<td><span class="label <?php echo $class; ?> "><?php echo $o->OrderStatus; ?></span></td>
										</tr>
									<?php $j++; } ?> -->
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /.table-responsive -->
					<!-- /.box-body -->
					<div class="box-footer clearfix">
						<a href="<?php echo site_url('manage/order') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
					</div>
				</div>

				<div class="col-md-4">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Recently Added Products</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<ul class="products-list product-list-in-box">

							<!-- <?php foreach ($latest_product_list as $p){ ?>
								<li class="item">
									<div class="product-img">
										<img style="border-radius: 50%;" src="<?php echo $p->Image==""?site_url('uploads/default/default.png'):site_url('uploads/product/thumb/'.$p->Image); ?>" alt="Product Image">
									</div>
									<div class="product-info">
										<a href="<?php echo site_url('manage/product/edit/'.$p->Id); ?>" class="product-title"><?php echo $p->ProductName; ?>
											<span class="label label-warning pull-right"><i class="fa fa-rupee"></i> <?php echo $p->Price; ?></span></a>
											<span class="product-description">
											  <?php echo $p->CategoryName; ?>
											</span>
									</div>
								</li>
							<?php } ?> -->

							</ul>
						</div>
						<!-- /.box-body -->
						<div class="box-footer text-center">
							<a href="<?php echo site_url('manage/product/'); ?>" class="uppercase">View All Products</a>
						</div>
						<!-- /.box-footer -->
					</div>

				</div>
				<!-- /.box-footer -->
			</div>



		<?php } ?>
		<input type="hidden" name="csrf_name" id="csrf_name" value="<?php echo htmlspecialchars($unique_form_name);?>"/>
		<input type="hidden" name="csrf_token" id="csrf_token" value="<?php echo htmlspecialchars($token);?>"/>
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->




<script language="javascript" type="text/javascript">

	// ORDERS AND PRODUCTS GRAPH
	Highcharts.chart('container', {
		title: {
			text: 'Orders And Products',
			x: -20 //center
		},
		subtitle: {
			text: 'Last <?php echo $days; ?> days',
			x: -20
		},
		xAxis: {
			categories: [<?php  $timestamp = time()-$stamp;
				for ($i = 0 ; $i < $days; $i++) {
					echo '"'.date('Y-m-d', $timestamp) . '",';
					$timestamp += (24 * 3600);
				}?>]
		},
		yAxis: {
			title: {
				text: 'Amount'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		},
		series:
		[
			{
				name: 'Orders',
				data: [<?php $timestamp = time()-$stamp;
					for ($i = 0 ; $i < $days; $i++) {
						$val = $line_order[date('Y-m-d', $timestamp)]['ORDERS'];
						echo ($val!=''?$val:'0') . ', ';
						$timestamp += 24 * 3600;
					}?>],
			},
			{
				name: 'Products',
				data: [<?php $timestamp = time() - $stamp;
						for ($i = 0; $i < $days; $i++) {
							$val = $line_product[date('Y-m-d', $timestamp)]['PRODUCT'];
							echo ($val != '' ? $val : '0') . ', ';
							$timestamp += 24 * 3600;
						}?>]
			}
		]


	});

    // CATEGORY, PRODUCT AND PRICE VISE GRAPH
	Highcharts.chart('container-order-list', {
		chart: {
			type: 'column'
		},
		title: {
			text: 'Orders',
			x: -20 //center
		},
		subtitle: {
			text: 'Last <?php echo $days; ?> days',
			x: -20
		},
		xAxis: {
			categories: [<?php  $timestamp = time()-$stamp;
				for ($i = 0 ; $i < $days; $i++) {
					echo '"'.date('Y-m-d', $timestamp) . '",';
					$timestamp += (24 * 3600);
				}?>]
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Total'
			},
			stackLabels: {
				enabled: true,
				style: {
					fontWeight: 'bold',
					color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
				}
			}
		},
		legend: {
			align: 'right',
			x: -30,
			verticalAlign: 'top',
			y: 25,
			floating: true,
			backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || 'white',
			borderColor: '#CCC',
			borderWidth: 1,
			shadow: false
		},
		tooltip: {
			headerFormat: '<b>{point.x}</b><br/>',
			pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
		},
		plotOptions: {
			column: {
				stacking: 'normal',
				dataLabels: {
					enabled: true,
					color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white'
				}
			}
		},
		series: [
					{
						name: 'Orders',
						data: [
							<?php $timestamp = time() - $stamp;
							for ($i = 0; $i < $days; $i++) {
								$val = $list_order[date('Y-m-d', $timestamp)]['ORDERS'];
								echo ($val != '' ? $val : '0') . ', ';
								$timestamp += 24 * 3600;
							}?>
						]
					},
		]
	});




</script>