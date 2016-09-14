<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.3.1/jquery.maskedinput.min.js"></script>

<div class="container" id="checkout_container_div">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<?php if (trim($error_message) !== '') { ?>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $error_message; ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<?php if (trim($success_message) !== '') { ?>
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<?php echo $success_message; ?>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="bar">
				<div class="btn-group btn-breadcrumb">
					<a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
					<a href="<?php echo base_url(); ?>post/category" class="btn">Post</a>
					<a href="javascript:;" class="btn">Checkout</a>
				</div>   
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="well well-lg background_white">
				<h4><b><?php echo $post_details_array['post_title']; ?> (<?php echo $post_details_array['city_name'] . ' , ' . $post_details_array['state_name']; ?>)</b>
				</h4>
				<div class="row">
					<div class="col-md-12">
						<h3>Total Payment : US $<?php echo $post_payment_price; ?></h3>
					</div>
				</div>
				<br/>
				<br/>
				<div class="row">
					<div class="col-md-12 text-center">
						<?php if (isset($user_details_array['masked_user_credit_card_number']) && $user_details_array['masked_user_credit_card_number'] !== '') { ?>
							Credit Card Number : <b><?php echo $user_details_array['masked_user_credit_card_number']; ?></b>
							<form action="<?php echo base_url(); ?>post/checkout_braintree" method="post" id="form_checkout_braintree">
								<input type="hidden" name="posts_id" value="<?php echo $post_details_array['post_id']; ?>">
								<input type="hidden" name="amount" value="<?php echo $post_payment_price; ?>">
								<input type="hidden" name="item_name" value="<?php echo $post_details_array['post_title']; ?>">
								<input type="hidden" name="currency_code" value="USD">
								<input type="hidden" name="pricing_options_id" value="<?php echo $pricing_options_id; ?>">
								<input type="hidden" name="return" value="<?php echo base_url(); ?>post/checkout_success/braintree/" id="form_checkout_braintree_return">
								<button id="checkout_braintree" type="button" class="btn btn-lg btn-info blue button_checkout"><i class="fa fa-credit-card"></i> Pay Via Credit Card</button>
							</form>							
						<?php } else { ?>
							<button type="button" class="btn btn-lg btn-info blue" data-toggle="modal" data-target="#cc_modal"><i class="fa fa-credit-card"></i> Pay Via Credit Card</button>
						<?php } ?>
					</div>
					<div class="modal fade" id="cc_modal" tabindex="-1" role="dialog" aria-labelledby="cc_modal_label" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<form role="form" method="post" action="" id="cc_form">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h3 class="modal-title" id="cc_modal_label">Credit Card Information</h3>
									</div>
									<div class="modal-body">
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
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-primary">Save Credit Card</button>
									</div>
								</form>
							</div>
						</div>
					</div>
<!--					<div class="col-md-6 text-center">
						Pay with your PayPal Account
						<form action="<?php echo $paypal_url; ?>" method="post" id="form_checkout_paypal">
							<input type="hidden" name="business" value="<?php echo $paypal_business_email; ?>">
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="posts_id" value="<?php echo $post_details_array['post_id']; ?>">
							<input type="hidden" name="amount" value="<?php echo $post_payment_price; ?>">
							<input type="hidden" name="item_name" value="<?php echo $post_details_array['post_title']; ?>">
							<input type="hidden" name="cpp_header_image" value="<?php echo base_url(); ?>assets/images/logo.png">
							<input type="hidden" name="currency_code" value="USD">
							<input type="hidden" name="pricing_options_id" value="<?php echo $pricing_options_id; ?>">
							<input type="hidden" name="cancel_return" value="<?php echo current_url(); ?>">
							<input type="hidden" name="return" value="<?php echo base_url(); ?>post/checkout_success/paypal/" id="form_checkout_paypal_return">
							<button id="checkout_paypal" type="button" class="btn btn-lg btn-info blue button_checkout"><i class="fa fa-paypal"></i> Pay Via PayPal</button>
						</form>
					</div>-->
				</div>
				<br/>
				<br/>
				<div class="row">
					<div class="col-md-12 text-center">
						<ul class="list-inline">
<!--							<li>
								<i class="fa fa-4x fa-cc-paypal"></i>
							</li>-->
							<li>
								<i class="fa fa-4x fa-cc-amex"></i>
							</li>
							<li>
								<i class="fa fa-4x fa-cc-discover"></i>
							</li>
							<li>
								<i class="fa fa-4x fa-cc-mastercard"></i>
							</li>
							<li>
								<i class="fa fa-4x fa-cc-visa"></i>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php
if (isset($user_details_array['masked_user_credit_card_number']) && $user_details_array['masked_user_credit_card_number'] !== '') {
	
} else {
	?>
	<script type="text/javascript">
		$.validator.addMethod("lettersonly", function (value, element) {
			return this.optional(element) || /^[a-z\s]+$/i.test(value);
		}, "Please Use Alphabets Only.");
		$(function () {
			$('select').select2();
			$("#cc_form").validate({
				errorElement: 'span',
				errorClass: 'help-block',
				focusInvalid: true,
				ignore: null,
				rules: {
					user_credit_card_name: {
						required: true,
						lettersonly: true
					},
					user_credit_card_number: {required: true,
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
					$("#cc_modal_label").after('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Please Correct the Errors</div>');
					setTimeout(function () {
						$('.alert-danger').fadeOut();
					}, 2000);
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
					$.post(base_url + 'users/update_credit_card', $("#cc_form").serialize(), function (data) {
						if (data === '1') {
							$("#cc_modal_label").after('<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Credit Card Info Saved !!!</div>');
							setTimeout(function () {
								document.location.href = '';
							}, 2000);
						} else {
							$("#cc_modal_label").after('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + data + '</div>');
							setTimeout(function () {
								$('.alert-danger').fadeOut();
							}, 2000);
						}
					});
				}
			});
			$('select').change(function () {
				$("#cc_form").validate().element($(this));
			});
		});
	</script>
<?php } ?>
<script type="text/javascript">
	$(function () {
		$(".button_checkout").click(function () {
			$('#checkout_container_div').block();
			var button_id = $(this).attr('id');
			var form_id = 'form_' + button_id;
			$.post(base_url + 'invoices/create', $("#" + form_id).serialize(), function (data) {
				if (parseInt(data) > 0) {
					var i = $("#" + form_id + "_return").val().split('/');
					i.pop();
					$("#" + form_id + "_return").val(i.join('/') + '/' + data);
					setTimeout(function () {
						$("#" + form_id).submit();
					}, 500);
				} else {
					bootbox.alert('Something went wrong !!!');
				}
			});
		});
	});
</script>