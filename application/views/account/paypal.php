<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
<div class="container">
	<?php $this->load->view('templates/system/user_info'); ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="content">
				<?php $this->load->view('templates/system/user_tabs'); ?>
				<div class="well background_white">
					<div id="my-tab-content" class="tab-content">
						<div class="tab-pane active" id="account">
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<?php $this->load->view('templates/system/account_left_menu'); ?>
								</div>
								<div class="col-lg-9 col-md-9">
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<h3>Modify Paypal Account</h3>
										</div>
										<div class="col-lg-3 col-md-3 col-md-offset-3">
											<p class="text-right"><a href="<?php echo base_url(); ?>account">Go Back</a></p>
										</div>
									</div>
									<h3>PayPal Account Status : <?php
										if ($user_details_array['user_paypal_verified'] === '1') {
											echo '<span style="color:green">Verified</span>';
										} else {
											if ($user_details_array['user_paypal_email_address'] !== '') {
												echo '<span class="required">Not Verified</span>';
											} else {
												echo 'Unknown';
											}
										}
										?></h3>
									<hr/>
									<form role="form" method="post" action="" id="user_edit_form">
										<div id="user_edit_form_div">
											<?php if ($user_details_array['user_paypal_email_address'] === '' || $user_details_array['user_paypal_verified'] === '1') { ?>
												<div class="row">
													<div class="col-md-6 col-lg-6">
														<div class="form-group">
															<label for="user_paypal_email_address">PayPal ID <span class="required">*</span></label>
															<input type="text" class="form-control" id="user_paypal_email_address" name="user_paypal_email_address" placeholder="Your PayPal Email Address" value="<?php echo $user_details_array['user_paypal_email_address']; ?>">
														</div>
													</div>
												</div>
											<?php } else { ?>
												<div class="row">
													<div class="col-md-6 col-lg-6">
														<div class="form-group">
															<label for="user_paypal_verification_code">Code Sent in PayPal Note : <span class="required">*</span></label>
															<input type="text" class="form-control" id="user_paypal_verification_code" name="user_paypal_verification_code" placeholder="Verification Code">
														</div>
													</div>
												</div>
											<?php } ?>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="pull-right">
														<button type="submit" class="btn btn-default blue" id="user_edit_button"><i class="fa fa-chevron-circle-right"></i> Submit</button>
													</div> 
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
<script type="text/javascript">
	$(function () {
		$('select').select2();
	});
</script>