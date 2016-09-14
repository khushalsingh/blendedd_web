<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="well background_white">
			<h1 class="text-center">Revenue Report</h1>

			<div class="tab-pane tab-pane fade in active">
				<table id="revenue_report_datatable" class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>
								Transaction Id
							</th>
							<th>
								User Full Name
							</th>
							<th>
								Post Title
							</th>
							<th>
								User Email
							</th>
							<th>
								Post Title
							</th>
							<th>
								Amount
							</th>
							<th>
								Currency
							</th>
							<th>
								Invoice Status
							</th>
							<th>
								Invoice Date
							</th>
							<th>
								Invoice Paid On
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($revenue_report_array as $revenue_report) { ?>
					<tr>
						<td><?php echo $revenue_report['invoice_transaction_id']; ?></td>
						<td><?php echo $revenue_report['user_full_name']; ?></td>
						<td><?php echo $revenue_report['user_type']; ?></td>
						<td><?php echo $revenue_report['user_email']; ?></td>
						<td><?php echo $revenue_report['post_title']; ?></td>
						<td><?php echo $revenue_report['invoice_amount']; ?></td>
						<td><?php echo $revenue_report['invoice_currency']; ?></td>
						<td><?php echo $revenue_report['invoice_status']; ?></td>
						<td><?php echo $revenue_report['invoice_created']; ?></td>
						<td><?php echo $revenue_report['invoice_paid_on']; ?></td>
						
					</tr>
					<?php } ?>
					</tbody>
				</table>


			</div>
		</div>
	</div>