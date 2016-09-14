<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
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
											<h3>Modify Bank Account</h3>
										</div>
										<div class="col-lg-3 col-md-3 col-md-offset-3">
											<p class="text-right"><a href="<?php echo base_url(); ?>account">Go Back</a></p>
										</div>
									</div>
									<h3>Bank Account Status : <?php
										if ($user_details_array['user_bank_verified'] === '1') {
											echo '<span style="color:green">Verified</span>';
										} else {
											if ($user_details_array['user_bank_account_number'] !== '') {
												echo '<span class="required">Not Verified</span>';
											} else {
												echo 'Unknown';
											}
										}
										?></h3>
									<hr/>
									<form role="form" method="post" action="" id="user_edit_form">
										<div id="user_edit_form_div">
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_bank_account_number">Bank Account Number <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_bank_account_number" name="user_bank_account_number" placeholder="Bank Account Number" value="<?php echo $user_details_array['user_bank_account_number']; ?>">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_bank_name">Bank Name <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_bank_name" name="user_bank_name" placeholder="Bank Name" value="<?php echo $user_details_array['user_bank_name']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_bank_route_code">Bank Route Code <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_bank_route_code" name="user_bank_route_code" placeholder="Bank Route Code" value="<?php echo $user_details_array['user_bank_route_code']; ?>">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label>&nbsp;</label>
														<br/>
														<label for="user_bank_account_online" class="checkbox-inline">
															<input type="checkbox" value="1" id="user_bank_account_online" name="user_bank_account_online" <?php
															if ($user_details_array['user_bank_account_online'] === '1') {
																echo 'checked="checked"';
															}
															?>>My Bank Account Accepts Online Payments</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 col-lg-12">
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
	$.validator.addMethod("lettersonly", function (value, element) {
		return this.optional(element) || /^[a-z\s]+$/i.test(value);
	}, "Please Use Alphabets Only.");
	$(function () {
		$('select').select2();
		$("#user_edit_form").validate({
			errorElement: 'span',
			errorClass: 'help-block',
			focusInvalid: true,
			ignore: null,
			rules: {
				user_bank_account_number: {
					required: true
				},
				user_bank_name: {
					required: true
				},
				user_bank_route_code: {
					required: true
				}
			},
			messages: {
				user_bank_account_number: {
					required: "Bank Account Number is required."
				},
				user_bank_name: {
					required: "Bank Name is required."
				},
				user_bank_route_code: {
					required: "Bank Route Code is required."
				}
			},
			invalidHandler: function (event, validator) {
				show_edit_error();
			},
			highlight: function (element) {
				$(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function (element) {
				$(element).closest('.form-group').removeClass('has-error');
			},
			success: function (element) {
				$(element).closest('.form-group').removeClass('has-error');
				$(element).closest('.form-group').children('span.help-block').remove();
			},
			errorPlacement: function (error, element) {
				error.appendTo(element.closest('.form-group'));
			},
			submitHandler: function (form) {
				$("#user_edit_button").button('loading');
				$.post('', $("#user_edit_form").serialize(), function (data) {
					if (data === '1') {
						bootbox.alert('Bank Account Updated Succesfully.', function (data) {
							document.location.href = base_url + 'account';
						});
					} else if (data === '0') {
						bootbox.alert('Error While Updating Bank Account !!!');
					} else {
						bootbox.alert(data);
					}
					$("#user_edit_button").button('reset');
				});
			}
		});
	});
	function show_edit_error() {
		$("#user_edit_form_div").prepend('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Please Correct the Errors !!!</div>');
		setTimeout(function () {
			$('.alert-danger').fadeOut();
		}, 2000);
	}
</script>