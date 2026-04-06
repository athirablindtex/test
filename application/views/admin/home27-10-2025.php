<div class="main-panel">
	<div class="content">
		<div class="page-inner">
			<div class="page-header">
				<h4 class="page-title">Dashboard</h4>
				<div class="btn-group btn-group-page-header ml-auto">
					<!-- <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						All Company
					</button>
					<div class="dropdown-menu">
						<div class="arrow"></div>
						<a class="dropdown-item" href="#">Company 1</a>
						<a class="dropdown-item" href="#">Company 2</a>
						<a class="dropdown-item" href="#">Company 3</a>
					</div> -->
					<select class="form-control" name="company" id="company">
						<option value="0">All Company</option>
						<?php foreach ($companies as $p) { ?>
							<option value="<?php echo $p->id; ?>" <?php if ($p->id == @$this->input->get('company')) {
																		echo "selected";
																	} ?>><?php echo $p->name; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-primary card-round mb-3" style="
    background: #67abff;
">
						<div class="card-body">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="flaticon-box-3
"></i>
									</div>
								</div>
								<div class="col col-stats">
									<div class="numbers">
										<p class="card-category">Companies</p>
										<h4 class="card-title counter" id="companies_count">0</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-info card-round mb-3" style="
    background: #ffb23f;
">
						<div class="card-body">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="flaticon-arrows-2
"></i>
									</div>
								</div>
								<div class="col col-stats">
									<div class="numbers">
										<p class="card-category">Quotation Sent

										</p>
										<h4 class="card-title counter" id="quotation_sent_count">0</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-success card-round mb-3" style="background: #20cc9c;">
						<div class="card-body ">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="flaticon-hands"></i>
									</div>
								</div>
								<div class="col col-stats">
									<div class="numbers">
										<p class="card-category">Quot. Confirmed

										</p>
										<h4 class="card-title counter" id="quotation_confirmed_count">0</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-secondary card-round mb-3" style="
    background: #2bd652;
">
						<div class="card-body ">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="flaticon-user
"></i>
									</div>
								</div>
								<div class="col col-stats">
									<div class="numbers">
										<p class="card-category">Sales Person

										</p>
										<h4 class="card-title counter" id="sales_person_count">0</h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">
					<div class="card card-stats card-success card-round" style="
    background: #ff5d7e;
">
						<div class="card-body ">
							<div class="row">
								<div class="col-5">
									<div class="icon-big text-center">
										<i class="flaticon-analytics"></i>
									</div>
								</div>
								<div class="col col-stats">
									<div class="numbers">
										<p class="card-category">Sales
										</p>
										<h4 class="card-title ">₹<span class="counter1" id="sales_amount">0</span></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- 	<div class="row row-card-no-pd row-count">
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="far fa-building"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Companies</p>
												<h4 class="card-title">1.2985</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="far fa-paper-plane"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Quotation Sent</p>
												<h4 class="card-title">1202</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-clipboard-check"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Quotation Confirmed

</p>
												<h4 class="card-title">23</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-user-tie"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Sales Person

</p>
												<h4 class="card-title">23</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
							<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-tag"></i>
											</div>
										</div>
										<div class="col col-stats">
											<div class="numbers">
												<p class="card-category">Sales</p>
												<h4 class="card-title">45</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div> -->

			<div class="row">
				<div class="col-md-4">
					<div class="card">
						<div class="card-header">
							<div class="card-title">Company Quotaion</div>
						</div>
						<div class="card-body pb-0">
							<canvas id="chartCompanyQuotation" width="400" height="300"></canvas>
							<h4 class="card-title text-center mb-3 mt-3" id="company_quotation_total_count">0</h4>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card">
						<div class="card-header">
							<div class="card-title">Product Type</div>
						</div>
						<div class="card-body pb-0">
							<canvas id="chartProductType" width="400" height="300"></canvas>
							<h4 class="card-title text-center mb-3 mt-3" id="product_type_total_count">0</h4>

						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="card">
						<div class="card-header">
							<div class="card-title">Quataion Stats</div>
						</div>
						<div class="card-body pb-0">
							<canvas id="chartQuotationStats" width="400" height="300"></canvas>

							<h4 class="card-title text-center mb-3 mt-3" id="quotation_stats_total_count">0</h4>

						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="card-title">Order/Month</div>
						</div>
						<div class="card-body ">
							<canvas width="400" height="300" id="chartOrderPerMonth"></canvas>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="card-title">Sales/Month</div>
						</div>
						<div class="card-body ">
							<canvas width="400" height="300" id="chartSalesPerMonth"></canvas>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<div class="card-header">
							<div class="card-title">Top Companies</div>
						</div>
						<div class="card-body pb-0" id="top_companies">


							<!-- <div class="d-flex">
								<div class="avatar">
									<img src="<?php echo base_url(); ?>assets/admin/img/logoproduct.svg" alt="..." class="avatar-img rounded-circle">
								</div>
								<div class="clearfix d-flex align-items-center justify-content-center justify-content-end pl-2">
									<div>
										<h5 class="fw-bold mb-1">Company Name</h5>
									</div>
								</div>
								<div class="d-flex ml-auto align-items-center">
									<h3 class="text-dark fw-bold">$100 - $600 <span class="text-success">(10%)</span></h3>
								</div>
							</div>
							<div class="separator-dashed"></div>
	 -->

						</div>
					</div>
				</div>


			</div>
			<div class="row">


			</div>
		</div>
	</div>

</div>

<script>
	$(document).ready(function() {
		load_data();
	});

	$('#company').change(function() {
		load_data();
	})

	function load_data() {
		let filter = {
			'company': $('#company').val()
		};
		$.ajax({
			'type': 'POST',
			'url': '<?php echo site_url('admin/dashboard/get_data_dashboard') ?>',
			'data': filter,
			'success': function(data) {
				var dt = JSON.parse(data);

				$('#companies_count').html(dt.companies);
				$('#quotation_sent_count').html(dt.quotation_sent);
				$('#quotation_confirmed_count').html(dt.quotation_confirmed);
				$('#sales_person_count').html(dt.salesperson);
				$('#sales_amount').html(dt.sales);

				company_quotation_pieChart(dt.company_quataion_chart);
				product_type_pieChart(dt.product_type_chart);
				quotation_stats_pieChart(dt.quotation_stats);

				order_per_month_graph(dt.order_per_month_graph);
				sales_per_month_graph(dt.sales_per_month_graph);

				$('#top_companies').html(dt.top_companies)

				counterAnimate()
				counterAnimateFloat()
			}
		});
	}

	var chartCompanyQuotation;
	var chartProductType;
	var chartQuotationStats;
	var chartOrderPerMonth;
	var chartSalesPerMonth;

	function company_quotation_pieChart(data) {
		$('#company_quotation_total_count').text(data.total_count);
		if (window.chartCompanyQuotation != undefined) {
			window.chartCompanyQuotation.destroy();
		}
		var ctx = document.getElementById('chartCompanyQuotation').getContext('2d');
		chartCompanyQuotation = new Chart(ctx, data.chart);
		chartCompanyQuotation.update();
	}

	function product_type_pieChart(data) {
		$('#product_type_total_count').text(data.total_count);
		if (window.chartProductType != undefined) {
			window.chartProductType.destroy();
		}
		var ctx = document.getElementById('chartProductType').getContext('2d');
		chartProductType = new Chart(ctx, data.chart);
		chartProductType.update();
	}

	function quotation_stats_pieChart(data) {
		$('#quotation_stats_total_count').text(data.total_count);
		if (window.chartQuotationStats != undefined) {
			window.chartQuotationStats.destroy();
		}
		var ctx = document.getElementById('chartQuotationStats').getContext('2d');
		chartQuotationStats = new Chart(ctx, data.chart);
		chartQuotationStats.update();
	}

	function order_per_month_graph(data) {
		if (window.chartOrderPerMonth != undefined) {
			window.chartOrderPerMonth.destroy();
		}
		var ctx = document.getElementById('chartOrderPerMonth').getContext('2d');
		chartOrderPerMonth = new Chart(ctx, data);
		chartOrderPerMonth.update();
	}

	function sales_per_month_graph(data) {
		if (window.chartSalesPerMonth != undefined) {
			window.chartSalesPerMonth.destroy();
		}
		var ctx = document.getElementById('chartSalesPerMonth').getContext('2d');
		chartSalesPerMonth = new Chart(ctx, data);
		chartSalesPerMonth.update();
	}




	function counterAnimate() {
		$('.counter').each(function() {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 1000,
				easing: 'swing',
				step: function(now) {
					$(this).text(Math.ceil(now));
				}
			});
		});
	}

	function counterAnimateFloat() {
		$('.counter1').each(function() {
			$(this).prop('Counter', 0).animate({
				Counter: $(this).text()
			}, {
				duration: 1000,
				easing: 'swing',
				step: function(now) {
					$(this).text(parseFloat(now.toFixed(2)));
				}
			});
		});
	}
</script>
<script>
	// var ctx = document.getElementById('chart1').getContext('2d');
	// var myChart = new Chart(ctx, {
	// 	type: 'pie',
	// 	data: {
	// 		labels: ['Red', 'Blue', 'Yellow'],
	// 		datasets: [{
	// 			label: '# of Votes',
	// 			data: [12, 19, 3],
	// 			backgroundColor: [
	// 				'#4ce670',
	// 				'#67abff',
	// 				'#20cc9c',
	// 			],
	// 			borderWidth: 1
	// 		}]
	// 	},

	// });


	// var ctx = document.getElementById('chart2').getContext('2d');
	// var myChart = new Chart(ctx, {
	// 	type: 'doughnut',
	// 	data: {
	// 		labels: ['Red', 'Blue', 'Yellow'],
	// 		datasets: [{
	// 			label: '# of Votes',
	// 			data: [12, 19, 3],
	// 			backgroundColor: [
	// 				'#1ee882',
	// 				'#ffb23f',
	// 				'#20cc9c',
	// 			],
	// 			borderWidth: 1
	// 		}]
	// 	},

	// });


	// var ctx = document.getElementById('chart3').getContext('2d');
	// var myChart = new Chart(ctx, {
	// 	type: 'pie',
	// 	data: {
	// 		labels: ['Red', 'Blue', 'Yellow'],
	// 		datasets: [{
	// 			label: '# of Votes',
	// 			data: [12, 19, 3],
	// 			backgroundColor: [
	// 				'#ff5d7e',
	// 				'#5bf5ca',
	// 				'#69c3c4',
	// 			],
	// 			borderWidth: 1
	// 		}]
	// 	},

	// });




	// var ctx = document.getElementById('chart4').getContext('2d');
	// var myChart = new Chart(ctx, {
	// 	type: 'bar',
	// 	data: {
	// 		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	// 		datasets: [{
	// 			label: '# of Votes',
	// 			data: [8, 10, 8, 9, 9, 7, 7, 7, 10, 8, 6, 10],
	// 			backgroundColor: [
	// 				'#67abff',
	// 				'#69c3c4',
	// 				'#67abff',
	// 				'#69c3c4',
	// 				'#67abff',
	// 				'#69c3c4',
	// 				'#67abff',
	// 				'#69c3c4',
	// 				'#67abff',
	// 				'#69c3c4',
	// 				'#67abff',
	// 				'#69c3c4',
	// 				'#67abff',
	// 				'#69c3c4'

	// 			],
	// 			borderWidth: 1
	// 		}]
	// 	},

	// });



	// var ctx = document.getElementById('chart5').getContext('2d');
	// var myChart = new Chart(ctx, {
	// 	type: 'line',
	// 	data: {
	// 		labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
	// 		datasets: [{
	// 			label: '# of Votes',
	// 			data: [8, 10, 8, 9, 9, 7, 7, 7, 10, 8, 6, 10],
	// 			backgroundColor: [
	// 				'#20cc9c',

	// 			],
	// 			borderWidth: 1
	// 		}]
	// 	},

	// });


	// $('input').tagsinput({
	// 		tagClass: 'badge-info'
	// 	});
</script>