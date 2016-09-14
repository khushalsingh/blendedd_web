<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.9.4/css/jquery.dataTables.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.9.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/tabletools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.delay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_custom.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
<div class="container">
	<?php $this->load->view('templates/system/admin_info'); ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="content">
				<?php $this->load->view('templates/system/admin_tabs'); ?>
				<div class="well background_white">
					<div id="my-tab-content" class="tab-content">
						<div class="tab-pane tab-pane fade in active">
							<div class="row">
								<div class="col-md-12">
									<form class="form-horizontal" role="form" id="download_report_form" method="post" action="dashboard/download_report">
										<div class="form-body">
											<div class="form-group">
												<label class="col-md-2 control-label">Report Type:</label>
												<div class="col-md-4">
													<select id="report_select_id" name="report_select" class="form-control input-medium select2" data-placeholder="Select Report Type">
														<option value="">Select Report Type:</option>
														<option value="revenue-report">Revenue Report:</option>
														<option value="new-user-report">New User Report:</option>
														<option value="active-user-report">Active User Report:</option>
														<option value="user-report-by">User Report By Location:</option>
														<option value="posting-report">Posting Report:</option>
														<option value="posting-report-by-category">Posting Report By Category:</option>
													</select>
												</div>
											</div>
											<div class="form-group report-location_by_category">
												<label class="col-md-2 control-label user_location_label">Select Category:</label>
												<div class="col-md-4">
													<select id="revenue_by_category" name="revenue_by_category" class="form-control input-medium select2 user_location" data-placeholder="">
														<?php foreach ($category_array as $category) { ?>
															<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>

														<?php } ?>
													</select>

												</div>
											</div>
											<div class="form-group report-year">
												<label class="col-md-2 control-label by">Report By:</label>
												<div class="col-md-4">
													<select id="report_by_id" name="report_by" class="form-control input-medium select2 report" data-placeholder="">
													</select>
												</div>
											</div>
											<div class="form-group report-date">
												<label class="col-md-2 control-label date_label">Select Date:</label>
												<div class="col-md-4">
													<div class='input-group date col-md-6 date_pick' id='datetimepicker'>
														<input type="text" class="form-control" name="report_date" id="report_date" />
														<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
														</span>
													</div>
												</div>
											</div>
											<div class="form-group report-location">
												<label class="col-md-2 control-label location_label">By Location:</label>
												<div class="col-md-4">
													<select id="location_by_id" name="location_by" class="form-control input-medium select2 location" data-placeholder="">
													</select>
												</div>
											</div>
											<div class="form-group report-location_by_country">
												<label class="col-md-2 control-label user_location_label">Select Country:</label>
												<div class="col-md-4">
													<select id="user_location_by_country_id" name="user_location_by_country" class="form-control input-medium select2 user_location" data-placeholder="">
													</select>
												</div>
											</div>
											<div class="form-group report-location_by_state">
												<label class="col-md-2 control-label user_location_label">Select State:</label>
												<div class="col-md-4">
													<select id="user_location_by_state_id" name="user_location_by_state" class="form-control input-medium select2 user_location" data-placeholder="">
													</select>
												</div>
											</div>
											<div class="form-group report-location_by_city">
												<label class="col-md-2 control-label user_location_label">Select City:</label>
												<div class="col-md-4">
													<select id="user_location_by_city_id" name="user_location_by_city" class="form-control input-medium select2 user_location" data-placeholder="">
													</select>
												</div>
											</div>
											<div class="form-group report-location_by_zip">
												<label class="col-md-2 control-label user_location_label">Select Zip:</label>
												<div class="col-md-4">
													<select id="user_location_by_zip_id" name="user_location_by_zip" class="form-control input-medium select2 user_location" data-placeholder="">
													</select>
												</div>
											</div>

											<div class="form-actions fluid">
												<div class="col-md-offset-3 col-md-9">
													<input type="submit" class="btn btn-default blue" value="Download">
													<input type="button" id="show_list" class="btn btn-default blue" value="Show List">
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container list">

</div>
<script type="text/javascript">
	$("#show_list").click(function () {
		$('select.select2').select2();
		val = $('select.select2').val();
		if (val == 'revenue-report')
		{
			// Fill the details in table
			$(".container.list").html('');
			$.post(base_url + 'reports/revenue_report_datatable', {'report_by': $('#report_by_id').val(), 'report_date': $('#report_date').val(), 'report_select': $('#report_select_id').val()}, function (data) {
				if (data == '1') {
					alert('All fields are required!!');
				}
				else {
					$(".container.list").html(data);
				}
			});
		}
		else if (val == 'new-user-report') {
			// Fill the details in table
			$(".container.list").html('');
			$.post(base_url + 'reports/new_user_datatable', {'report_by': $('#report_by_id').val(), 'report_date': $('#report_date').val(), 'report_select': $('#report_select_id').val()}, function (data) {
				if (data == '1') {
					alert('All fields are required!!');
				}
				else {
					$(".container.list").show();
					$(".container.list").html(data);
				}
			});
		}
		else if (val == 'active-user-report') {
			// Fill the details in table

			$(".container.list").html('');
			$.get(base_url + 'reports/active_users_datatable', function (data) {
				$(".container.list").show();
				$(".container.list").html(data);
			});
		}
		else if (val == 'user-report-by') {
			// Fill the details in table
			$(".container.list").html('');
			$.post(base_url + 'reports/report_by_location_datatable', {'location_by': $('#location_by_id').val(), 'user_location_by_country': $('#user_location_by_country_id').val(), 'user_location_by_state': $('#user_location_by_state_id').val(), 'user_location_by_city': $('#user_location_by_city_id').val(), 'report_select': $('#report_select_id').val()}, function (data) {
				if (data == '1') {
					alert('All fields are required!!');
				}
				else {
					$(".container.list").show();
					$(".container.list").html(data);
				}

			});
		}
		else if (val == 'posting-report') {
			// Fill the details in table
			$(".container.list").html('');
			$.post(base_url + 'reports/posting_report_datatable', function (data) {
				$(".container.list").show();
				$(".container.list").html(data);
			});
		}
		else if (val == 'posting-report-by-category') {
			// Fill the details in table
			$(".container.list").html('');
			$.post(base_url + 'reports/posting_report_by_category_datatable', {'report_by': $('#report_by_id').val(), 'revenue_by_category': $('#revenue_by_category').val(), 'report_date': $('#report_date').val(), 'report_select': $('#report_select_id').val()}, function (data) {
				if (data == '1') {
					alert('All fields are required!!');
				}
				else {
					$(".container.list").show();
					$(".container.list").html(data);
				}
			});
		}
	});
</script>
<script type="text/javascript">
	$(function () {
		$('select.select2').select2();
		$(".container.list").html('');
		$('.report-year').hide();
		$('.report-date').hide();
		$('.report-location').hide();
		$('.container.revenue').hide();
		$('.container.newuser').hide();
		$('.container.activeuser').hide();
		$('.container.reportbylocation').hide();
		$('.container.reportbycategory').hide();
		$('.container.postingreport').hide();
		$('.report-location_by_country').hide();
		$('.report-location_by_state').hide();
		$('.report-location_by_city').hide();
		$('.report-location_by_zip').hide();
		$('.report-location_by_category').hide();
		$('.report-by-category').hide();
		$("#report_select_id").change(function () {
			$(".container.list").html('');
			val = $(this).val();
			if (val == 'revenue-report')
			{
				$('.container.revenue').show();
				$('.container.newuser').hide();
				$('.container.activeuser').hide();
				$('.container.reportbylocation').hide();
				$('.container.postingreport').hide();
				$('.container.reportbycategory').hide();
				$('.report-year').show();
				$('.report-date').show();
				$('.report-by-category').hide();
				$('.report-by-category').hide();
				$('.report-location_by_country').hide();
				$('.report-location_by_state').hide();
				$('.report-location_by_city').hide();
				$('.report-location_by_zip').hide();
				$('.report-location_by_category').hide();
				$('.report-location').hide();
				$('.report-by-category').hide();
				$("#report_by_id").html('<option value=""></option>').select2();
				$("#report_by_id").append('<option value="by">Select by</option><option value="year">Year</option><option value="month">Month</option><option value="day">Day</option>');

			}
			if (val == 'new-user-report')
			{
				$('.container.revenue').hide();
				$('.container.newuser').hide();
				$('.container.activeuser').hide();
				$('.container.reportbylocation').hide();
				$('.container.postingreport').hide();
				$('.container.reportbycategory').hide();
				$('.report-year').show();
				$('.report-date').show();
				$('.report-by-category').hide();
				$('.report-by-category').hide();
				$('.report-location_by_country').hide();
				$('.report-location_by_state').hide();
				$('.report-location_by_city').hide();
				$('.report-location_by_zip').hide();
				$('.report-location_by_category').hide();
				$('.report-location').hide();
				$('.report-by-category').hide();
				$("#report_by_id").html('<option value=""></option>').select2();
				$("#report_by_id").append('<option value="by">Select by</option><option value="year">Year</option><option value="month">Month</option><option value="day">Day</option>');
			}
			if (val == 'active-user-report')
			{
				$('.report-year').hide();
				$('.report-date').hide();
				$('.report-by-category').hide();
				$('.report-by-category').hide();
				$('.report-location').hide();
				$('.report-location_by_country').hide();
				$('.report-location_by_state').hide();
				$('.report-location_by_city').hide();
				$('.report-location_by_zip').hide();
				$('.report-location_by_category').hide();
				$('.report-by-category').hide();
				$('.container.revenue').hide();
				$('.container.newuser').hide();
				$('.container.activeuser').hide();
				$('.container.reportbylocation').hide();
				$('.container.postingreport').hide();
				$('.container.reportbycategory').hide();

			}
			if (val == 'user-report-by')
			{
				$('.report-by-category').hide();
				$('.report-by-category').hide();
				$('.report-year').hide();
				$('.report-date').hide();
				$('.report-location_by').hide();
				$('.report-location').show();
				$('.report-by-category').hide();
				$('.container.revenue').hide();
				$('.container.newuser').hide();
				$('.container.activeuser').hide();
				$('.container.postingreport').hide();
				$('.container.reportbycategory').hide();
				$('.container.reportbylocation').show();
				$('.report-location_by_category').hide();
				$("#location_by_id").html('<option value=""></option>').select2();
				$("#location_by_id").append('<option value="by">Select by</option><option value="country">Country</option><option value="state">State</option><option value="city">City</option><option value="zip">Zip</option>');
			}
			if (val == 'posting-report')
			{
				$('.report-year').show();
				$('.report-date').show();
				$('.report-location_by_category').hide();
				$('.report-by-category').hide();
				$('.report-year').hide();
				$('.report-date').hide();
				$('.report-location').hide();
				$('.report-location_by_country').hide();
				$('.report-location_by_state').hide();
				$('.report-location_by_city').hide();
				$('.report-location_by_zip').hide();
				$('.container.revenue').hide();
				$('.container.newuser').hide();
				$('.container.activeuser').hide();
				$('.container.reportbylocation').hide();
				$('.container.reportbycategory').hide();
				$('.container.postingreport').show();
				$("#report_by_id").html('<option value=""></option>').select2();
				$("#report_by_id").append('<option value="by">Select by</option><option value="year">Year</option><option value="month">Month</option><option value="day">Day</option>');
			}
			if (val == 'posting-report-by-category')
			{
				$('.report-year').show();
				$('.report-date').show();
				$('.report-location').hide();
				$('.report-location_by_country').hide();
				$('.report-location_by_state').hide();
				$('.report-location_by_city').hide();
				$('.report-location_by_zip').hide();
				$('.container.revenue').hide();
				$('.container.newuser').hide();
				$('.container.activeuser').hide();
				$('.container.reportbylocation').hide();
				$('.container.postingreport').hide();
				$('.container.reportbycategory').hide();
				$('.report-location_by_category').show();
				$('.report-by-category').show();
				$("#report_by_id").html('<option value=""></option>').select2();
				$("#report_by_id").append('<option value="by">Select by</option><option value="year">Year</option><option value="month">Month</option><option value="day">Day</option>');
			}
		});
		$("#report_by_id").change(function () {
			val = $(this).val();
			if (val == 'year')
			{
				$('#datetimepicker').datepicker('update', '');
				$('#datetimepicker').datepicker('remove');
				$("#datetimepicker").datepicker({
					format: "yyyy",
					viewMode: "years",
					minViewMode: "years"
				});
				$('#datetimepicker').datepicker('update');
			}
			if (val == 'month')
			{
				$('#datetimepicker').datepicker('update', '');
				$('#datetimepicker').datepicker('remove');
				$("#datetimepicker").datepicker({
					format: "yyyy-mm",
					viewMode: "months",
					minViewMode: "months"
				});
				$('#datetimepicker').datepicker('update');
			}
			if (val == 'day')
			{
				$('#datetimepicker').datepicker('update', '');
				$('#datetimepicker').datepicker('remove');
				$("#datetimepicker").datepicker({
					format: "yyyy-mm-dd",
				});
				$('#datetimepicker').datepicker('update');
			}
		});
		$("#location_by_id").change(function () {
			type = $("#location_by_id").val();
			if (type == 'country' || type == 'state' || type == 'city') {
				$('.report-location_by_state').hide();
				$('.report-location_by_city').hide();
				$('.report-location_by_zip').hide();
				$('.report-location_by_country').show();
				val = $(this).val();
				$("#user_location_by_country_id").html('<option value=""></option>').select2();
				$.get(base_url + 'dashboard/get_all_active_countries', {
				}, function (data) {
					$.each(data, function (i, v) {
						$("#user_location_by_country_id").append('<option value="' + v.country_id + '">' + v.country_name + '</option>');
					});
				});
			}
			else if (type == 'zip') {
				$('.report-by-category').hide();
				$('.report-by-category').hide();
				$('.report-location_by_state').hide();
				$('.report-location_by_city').hide();
				$('.report-location_by_zip').hide();
				$('.report-location_by_country').hide();
				$('.report-location_by_zip').show();
				$("#user_location_by_zip_id").html('<option value=""></option>').select2();
				$.post(base_url + 'cities/get_all_zip_codes', {
				}, function (data) {
					$.each(data, function (i, v) {
						//$("#user_location_by_zip_id").append('<option value="' + v.zip_code + '">' + v.zip_code + '</option>');
					});
				});
			}
		});
		$("#user_location_by_country_id").change(function () {
			loc = $("#location_by_id").val();
			if (loc == 'state' || loc == 'city') {
				$('.report-location_by_state').show();
				$("#user_location_by_state_id").html('<option value=""></option>').select2();
				$.getJSON(base_url + 'states/get_active_states_by_country_id/' + $(this).val(), function (data) {
					$.each(data, function (i, v) {
						$("#user_location_by_state_id").append('<option value="' + v.state_id + '">' + v.state_name + '</option>');
					});
				});
			}
		});
		$("#user_location_by_state_id").change(function () {
			loc = $("#location_by_id").val();
			if (loc == 'city') {
				$('.report-location_by_city').show();
				$("#user_location_by_city_id").html('<option value=""></option>').select2();
				$.getJSON(base_url + 'cities/get_active_cities_by_state_id/' + $(this).val(), function (data) {
					$.each(data, function (i, v) {
						$("#user_location_by_city_id").append('<option value="' + v.city_id + '">' + v.city_name + '</option>');
					});
				});
			}
		});
	});
</script>