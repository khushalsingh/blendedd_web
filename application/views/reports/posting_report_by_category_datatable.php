<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="well background_white">
			<h1 class="text-center">Posting Report By Category</h1>
			<div id="my-tab-content" class="tab-content">
				<div class="tab-pane tab-pane fade in active">
					<table id="posting_report_by_category_datatable" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>
									Invoice Id
								</th>
								<th>
									Paypal Transaction Id
								</th>
								<th>
									Post Id
								</th>
								<th>
									Post Title
								</th>
								<th>
									Post Description
								</th>
								<th>
									Category Name
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($posting_report_by_category_array as $postingreportbycategory) { ?>
								<tr>
									<td><?php echo $postingreportbycategory['invoice_id']; ?></td>
									<td><?php echo $postingreportbycategory['invoice_transaction_id']; ?></td>
									<td><?php echo $postingreportbycategory['post_id']; ?></td>
									<td><?php echo $postingreportbycategory['post_title']; ?></td>
									<td><?php echo $postingreportbycategory['post_description']; ?></td>
									<td><?php echo $postingreportbycategory['category_name']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>