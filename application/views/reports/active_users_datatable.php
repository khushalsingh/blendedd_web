<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="well background_white">
			<h1 class="text-center">Active User Report</h1>
			<table id="active_user_datatable" class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>
							User ID
						</th>
						<th>
							User Full Name
						</th>
						<th>
							User Type
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
							User Created
						</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($active_users_array as $active_user) { ?>
					<tr>
						<td><?php echo $active_user['user_id']; ?></td>
						<td><?php echo $active_user['user_full_name']; ?></td>
						<td><?php echo $active_user['user_type']; ?></td>
						<td><?php echo $active_user['user_email']; ?></td>
						<td><?php echo $active_user['user_primary_contact']; ?></td>
						<td><?php echo $active_user['user_address_line_1']; ?></td>
						<td><?php echo $active_user['user_address_line_2']; ?></td>
						<td><?php echo $active_user['state_name']; ?></td>
						<td><?php echo $active_user['city_name']; ?></td>
						<td><?php echo $active_user['user_zipcode']; ?></td>
						<td><?php echo $active_user['user_created']; ?></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>