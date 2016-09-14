<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div class="well background_white">
				<h1 class="text-center">Posting Report </h1>
				<div id="my-tab-content" class="tab-content">
					<div class="tab-pane tab-pane fade in active">
						<table id="posting_report_datatable" class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>
										Post Id
									</th>
									<th>
										Post Creatde By
									</th>
									<th>
										Category name
									</th>
									<th>
										Post Title
									</th>
									<th>
										Contact Person Name
									</th>
									<th>
										Contact Person Number
									</th>
									<th>
										Post Created
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($posting_report_array as $postingreport) { ?>
						<tr>
							<td><?php echo $postingreport['post_id']; ?></td>
							<td><?php echo $postingreport['user_full_name']; ?></td>
							<td><?php echo $postingreport['category_name']; ?></td>
							<td><?php echo $postingreport['post_title']; ?></td>
							<td><?php echo $postingreport['post_contact_name']; ?></td>
							<td><?php echo $postingreport['post_contact_number']; ?></td>
							<td><?php echo $postingreport['post_created']; ?></td>
						</tr>
					<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>