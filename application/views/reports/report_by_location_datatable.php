<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="well background_white">
				<h1 class="text-center">User Report By Location </h1>
										<table id="report_by_location_datatable" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>
										User ID
									</th>
									<th>
										User Full Name
									</th>
									<th>
										User Email
									</th>
									<th>
										User Contact Number
									</th>
									<th>
										Address 1
									</th>
									<th>
										Address 2
									</th>
									<th>
										State
									</th>
									<th>
										City
									</th>
									<th>
										Zip Code
									</th>
									<th>
										User Status
									</th>
									<th>
										User Created
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($report_by_location_array as $reportbylocation) { ?>
						<tr>
							<td><?php echo $reportbylocation['user_id']; ?></td>
							<td><?php echo $reportbylocation['user_full_name']; ?></td>
							<td><?php echo $reportbylocation['user_email']; ?></td>
							<td><?php echo $reportbylocation['user_primary_contact']; ?></td>
							<td><?php echo $reportbylocation['user_address_line_1']; ?></td>
							<td><?php echo $reportbylocation['user_address_line_2']; ?></td>
							<td><?php echo $reportbylocation['state_name']; ?></td>
							<td><?php echo $reportbylocation['city_name']; ?></td>
							<td><?php echo $reportbylocation['user_zipcode']; ?></td>
							<td><?php echo $reportbylocation['user_status']; ?></td>
							<td><?php echo $reportbylocation['user_created']; ?></td>
						</tr>
					<?php } ?>
							</tbody>
						</table>
				
			</div>
		</div>
	</div>