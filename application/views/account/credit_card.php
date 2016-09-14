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
											<h3>Change Credit Card</h3>
										</div>
										<div class="col-lg-3 col-md-3 col-md-offset-3">
											<p class="text-right"><a href="<?php echo base_url(); ?>account">Go Back</a></p>
										</div>
									</div>
									<?php if ($user_details_array['user_credit_card_number'] !== '') { ?>
										<h3>Exiting Credit Card is : <?php echo $user_details_array['user_credit_card_number']; ?></h3>
										<hr/>
									<?php } ?>
									<form role="form" method="post" action="" id="user_edit_form">
										<div id="user_edit_form_div">
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_credit_card_name">Credit Card Info <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_credit_card_name" name="user_credit_card_name" placeholder="Name (as it appear on card)">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_credit_card_number">&nbsp; <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_credit_card_number" name="user_credit_card_number" placeholder="Card Number">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-3 col-lg-3">
													<div class="form-group">
														<label for="user_credit_card_expiry_month">Expires On <span class="required">*</span></label>
														<select class="form-control" id="user_credit_card_expiry_month" name="user_credit_card_expiry_month" data-placeholder="Select Month">
															<option></option>
															<option>01</option>
															<option>02</option>
															<option>03</option>
															<option>04</option>
															<option>05</option>
															<option>06</option>
															<option>07</option>
															<option>08</option>
															<option>09</option>
															<option>10</option>
															<option>11</option>
															<option>12</option>
														</select>
													</div>
												</div>
												<div class="col-md-3 col-lg-3">
													<div class="form-group">
														<label for="user_credit_card_expiry_year">&nbsp; <span class="required">*</span></label>
														<select class="form-control" id="user_credit_card_expiry_year" name="user_credit_card_expiry_year" data-placeholder="Select Year">
															<option></option>
															<?php for ($i = 0; $i <= 7; $i++) { ?>
																<option><?php echo date('Y') + $i; ?></option>
															<?php } ?>
														</select>
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_credit_card_cvv">&nbsp; <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_credit_card_cvv" name="user_credit_card_cvv" maxlength="4" placeholder="CVV">
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
				user_credit_card_name: {
					required: true,
					lettersonly: true
				},
				user_credit_card_number: {
					required: true,
					creditcard: true
				},
				user_credit_card_expiry_month: {
					required: true
				},
				user_credit_card_expiry_year: {
					required: true
				},
				user_credit_card_cvv: {
					required: true,
					digits: true,
					maxlength: 4
				}
			},
			messages: {
				user_credit_card_name: {required: "Credit Card Name is required.",
					lettersonly: "Please Use Alphabets Only."
				},
				user_credit_card_number: {
					required: "Credit Card Number is required.",
					creditcard: "Please Enter Valid Credit Card Number."
				},
				user_credit_card_expiry_month: {
					required: "Expiry Month is required."
				},
				user_credit_card_expiry_year: {
					required: "Expiry Year is required."
				},
				user_credit_card_cvv: {
					required: "CVV Number is required.",
					digits: "Please enter Digits Only.",
					maxlength: "Please Enter upto 4 Digits Only."
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
				$.post(base_url + 'users/update_credit_card', $("#user_edit_form").serialize(), function (data) {
					if (data === '1') {
						bootbox.alert('CC Edited Succesfully.', function (data) {
							document.location.href = base_url + 'account';
						});
					} else if (data === '0') {
						bootbox.alert('Error While Updating CC !!!');
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