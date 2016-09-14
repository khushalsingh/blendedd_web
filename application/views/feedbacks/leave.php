<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.9.4/css/jquery.dataTables.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.9.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.delay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_custom.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
<div class="container">
	<?php $this->load->view('templates/system/user_info'); ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="content">
				<?php $this->load->view('templates/system/user_tabs'); ?>
				<div class="well background_white">
					<h4>Leave Feedback : <?php echo $invoice_details_array[0]['post_title']; ?></h4>
					<div class="row">
						<div class="col-lg-2 col-md-2">
							<img src="<?php echo base_url(); ?>assets/images/blank_profile.png" />
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<th>Owner / Service Provider : </th>
										<td><?php echo $invoice_details_array[0]['invoice_by_user_login']; ?></td>
									</tr>
									<tr>
										<th>Renter / Service Buyer : </th>
										<td><?php echo $invoice_details_array[0]['invoice_to_user_login']; ?></td>
									</tr>
									<tr>
										<th>Payment Made On : </th>
										<td><?php echo date('d M Y', strtotime($invoice_details_array[0]['invoice_created'])); ?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-lg-4 col-md-4">
							<div class="table-responsive">
								<table class="table table-bordered">
									<?php if ($invoice_details_array[0]['invoice_transaction_id'] !== '') { ?>
										<tr>
											<th>Txn Id : </th>
											<td><?php echo $invoice_details_array[0]['invoice_transaction_id']; ?></td>
										</tr>
										<tr>
											<th>Txn Amount : </th>
											<td><?php echo sprintf('%01.2f', $invoice_details_array[0]['invoice_amount']) . ' ' . $invoice_details_array[0]['invoice_currency']; ?></td>
										</tr>
										<tr>
											<th>Txn Via : </th>
											<td>Paypal</td>
										</tr>
									<?php } else { ?>
										<tr>
											<th>Txn Id : </th>
											<td><?php echo $invoice_details_array[0]['firstdata_transaction_id']; ?></td>
										</tr>
										<tr>
											<th>Txn Amount : </th>
											<td><?php echo sprintf('%01.2f', $invoice_details_array[0]['invoice_amount']) . ' ' . $invoice_details_array[0]['invoice_currency']; ?></td>
										</tr>
										<tr>
											<th>Txn Via : </th>
											<td>Credit Card</td>
										</tr>
									<?php } ?>
								</table>
							</div>
						</div>
					</div>
					<hr/>
					<h4>Rate this Transaction</h4>
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<form id="feedback_form" action="javascript:;" method="post">
								<div class="form-group">
									<label class="radio-inline text-success"><input type="radio" name="feedback_rating" value="1" checked="checked" /> <i class="fa fa-2x fa-plus-circle"></i> Positive</label>
									<label class="radio-inline"><input type="radio" name="feedback_rating" value="0" /> <i class="fa fa-2x fa-circle"></i> Neutral</label>
									<label class="radio-inline text-danger"><input type="radio" name="feedback_rating" value="-1" /> <i class="fa fa-2x fa-minus-circle"></i> Negative</label>
								</div>
								<div class="form-group" id="negative_feedback_info" style="display: none;">
									<div class="alert alert-danger">
										No one likes negative feedback. Please contact the other party to see what resolution and/or compromise can be worked out before leaving negative feedback.
									</div>
								</div>
								<div class="form-group">
									<label for="feedback_description">Tell Us More :</label>
									<input type="text" class="form-control" id="feedback_description" name="feedback_description" placeholder="Enter Feedback Here..." maxlength="80">
								</div>
								<a href="javascript:;" class="btn btn-default blue" onclick="submit_feedback();"><i class="fa fa-chevron-circle-right"></i> Submit</a>
								<a href="<?php echo base_url(); ?>feedbacks" class="">Cancel</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$("input[name=feedback_rating]").change(function () {
			if ($(this).val() === '-1') {
				$("#negative_feedback_info").fadeIn();
			} else {
				$("#negative_feedback_info").hide();
			}
		});
	});

	function submit_feedback() {
		$.post('', $("#feedback_form").serialize(), function (data) {
			if (data === '1') {
				bootbox.alert('Thank you for your feedback !!!', function () {
					document.location.href = base_url + 'feedbacks';
				});
			} else {
				bootbox.alert(data);
			}
		});
	}
</script>