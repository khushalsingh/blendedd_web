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
											<h3>My Account Information</h3>
										</div>
										<div class="col-lg-3 col-md-3 col-md-offset-3">
											<p class="text-right"><a href="<?php echo base_url(); ?>account/edit">Edit Account Information</a></p>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<div class="table-responsive">
												<table class="table table-striped table-bordered">
													<tr>
														<th>Account Type :</th>
														<td><?php
															if ($user_details_array['user_type'] === '1') {
																echo 'Business';
															} else {
																echo 'Individual';
															}
															?></td>
														<th><?php
															if ($user_details_array['user_type'] === '1') {
																echo 'Business Legal Name :';
															}
															?></th>
														<td><?php echo $user_details_array['business_legal_name']; ?></td>
													</tr>
													<tr>
														<th>User ID :</th>
														<td><?php echo $user_details_array['user_login']; ?></td>
														<th>Email Address :</th>
														<td><?php echo $user_details_array['user_email']; ?></td>
													</tr>
													<tr>
														<th>Contact First Name :</th>
														<td><?php echo $user_details_array['user_first_name']; ?></td>
														<th>Contact Last Name :</th>
														<td><?php echo $user_details_array['user_last_name']; ?></td>
													</tr>
													<tr>
														<th>Primary Contact Number :</th>
														<td><?php echo $user_details_array['user_primary_contact']; ?></td>
														<th></th>
														<td></td>
													</tr>
													<tr>
														<th>Facebook :</th>
														<td><?php echo $user_details_array['user_facebook_url']; ?></td>
														<th>Twitter :</th>
														<td><?php echo $user_details_array['user_twitter_url']; ?></td>
													</tr>
													<tr>
														<th>LinkedIn :</th>
														<td><?php echo $user_details_array['user_linkedin_url']; ?></td>
														<th>Instagram :</th>
														<td><?php echo $user_details_array['user_instagram_url']; ?></td>
													</tr>
													<tr>
														<th>Street Address 1 :</th>
														<td><?php echo $user_details_array['user_address_line_1']; ?></td>
														<th>Street Address 2 :</th>
														<td><?php echo $user_details_array['user_address_line_2']; ?></td>
													</tr>
													<tr>
														<th>Country :</th>
														<td><?php echo $user_details_array['country_name']; ?></td>
														<th>State/Province :</th>
														<td><?php echo $user_details_array['state_name']; ?></td>
													</tr>
													<tr>
														<th>City :</th>
														<td><?php echo $user_details_array['city_name']; ?></td>
														<th>ZipCode :</th>
														<td><?php echo $user_details_array['user_zipcode']; ?></td>
													</tr>
													<tr>
														<th>Communication Preferences :</th>
														<td><?php
															$communication_array = array();
															if ($user_details_array['user_communication_via_email'] === '1') {
																$communication_array[] = 'Email';
															}
															if ($user_details_array['user_communication_via_phone_call'] === '1') {
																$communication_array[] = 'Phone Call';
															}
															if ($user_details_array['user_communication_via_sms'] === '1') {
																$communication_array[] = 'SMS';
															}
															echo implode(', ', $communication_array);
															?></td>
														<th></th>
														<td></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
									<!--									<div class="row">
																			<div class="col-lg-6 col-md-6">
																				<p><span><strong>
																							Payment Methods for Seller Fees<br />
																							Automatic payment method- Cradit Card<br /><br />
																							Cradit Card Number-xxxx xxxx xxxx 0000<br />
																							Last Update Sept 14-12-2014</strong><br />
																					</span></p>
																				<a href="javascript:;">Change automatic payment method</a>
																			</div>
																			<div class="col-lg-6 col-md-6">
																				<div class="panel panel-default">
																					<div class="panel-body">
																						<p><span>Paypal is a conveniert opton for paying your blendeed seller fees.</span></p>
																						<p><img src="<?php echo base_url(); ?>assets/images/paypal.png" class="img img-responsive" alt="product" /></p>
																						<p><span>Automatic Paypal Payments</span></p>
																						<p>The simplest way to pay your seller fees.</p>
																						<p><a class="btn btn-default blue" href="javascript:;">Sign Up Today</a></p>
																					</div>
																				</div>
																			</div>
																		</div>-->
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

	});
</script>