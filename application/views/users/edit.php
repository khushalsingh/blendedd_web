<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
<div class="container">
	<?php $this->load->view('templates/system/admin_info'); ?>
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div id="content">
				<?php $this->load->view('templates/system/admin_tabs'); ?>
				<div class="well background_white">
					<div id="my-tab-content" class="tab-content">
						<div class="tab-pane active" id="account">
							<div class="row">
								<div class="col-lg-12 col-md-12">
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<h3>Edit My Account Information</h3>
										</div>
										<div class="col-lg-3 col-md-3 col-md-offset-3">
											<p class="text-right"><a href="<?php echo base_url(); ?>users">Go Back</a></p>
										</div>
									</div>
									<form role="form" method="post" action="" id="user_edit_form">
										<div id="user_edit_form_div">
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_email">Email Address <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_email" name="user_email" placeholder="User Email" value="<?php echo $user_details_array['user_email']; ?>">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_primary_contact">Primary Contact Number <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_primary_contact" name="user_primary_contact" placeholder="Contact Number" value="<?php echo $user_details_array['user_primary_contact']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_first_name">Contact First Name <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_first_name" name="user_first_name" placeholder="Contact First Name" value="<?php echo $user_details_array['user_first_name']; ?>">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_last_name">Contact Last Name <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_last_name" name="user_last_name" placeholder="Contact Last Name" value="<?php echo $user_details_array['user_last_name']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_facebook_url">Facebook</label>
														<input type="text" class="form-control" id="user_facebook_url" name="user_facebook_url" placeholder="(Optional)" value="<?php echo $user_details_array['user_facebook_url']; ?>">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_twitter_url">Twitter</label>
														<input type="text" class="form-control" id="user_twitter_url" name="user_twitter_url" placeholder="(Optional)" value="<?php echo $user_details_array['user_twitter_url']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_linkedin_url">Linkedin</label>
														<input type="text" class="form-control" id="user_linkedin_url" name="user_linkedin_url" placeholder="(Optional)" value="<?php echo $user_details_array['user_linkedin_url']; ?>">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_instagram_url">Instagram</label>
														<input type="text" class="form-control" id="user_instagram_url" name="user_instagram_url" placeholder="(Optional)" value="<?php echo $user_details_array['user_instagram_url']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_address_line_1">Street Address 1 <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_address_line_1" name="user_address_line_1" placeholder="Street Address 1" value="<?php echo $user_details_array['user_address_line_1']; ?>">
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_address_line_2">Street Address 2</label>
														<input type="text" class="form-control" id="user_address_line_2" name="user_address_line_2" placeholder="Street Address 2" value="<?php echo $user_details_array['user_address_line_2']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="countries_id">Country <span class="required">*</span></label>
														<select class="form-control" id="countries_id" name="countries_id" data-placeholder="Select Country">
															<option></option>
															<?php foreach ($countries_array as $country) { ?>
																<option <?php
																if ($user_details_array['country_id'] === $country['country_id']) {
																	echo 'selected="selected"';
																}
																?> value="<?php echo $country['country_id']; ?>"><?php echo $country['country_name']; ?></option>
																<?php } ?>
														</select>
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="states_id">State/Province <span class="required">*</span></label>
														<select class="form-control" id="states_id" name="states_id" data-placeholder="Select State / Province">
															<option></option>
															<?php foreach ($states_array as $state) { ?>
																<option <?php
																if ($user_details_array['state_id'] === $state['state_id']) {
																	echo 'selected="selected"';
																}
																?> value="<?php echo $state['state_id']; ?>"><?php echo $state['state_name']; ?></option>
																<?php } ?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="cities_id">City <span class="required">*</span></label>
														<select class="form-control" id="cities_id" name="cities_id" data-placeholder="Select City">
															<option></option>
															<?php foreach ($cities_array as $city) { ?>
																<option <?php
																if ($user_details_array['city_id'] === $city['city_id']) {
																	echo 'selected="selected"';
																}
																?> value="<?php echo $city['city_id']; ?>"><?php echo $city['city_name']; ?></option>
																<?php } ?>
														</select>
													</div>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="form-group">
														<label for="user_zipcode">Zip Code <span class="required">*</span></label>
														<input type="text" class="form-control" id="user_zipcode" name="user_zipcode" maxlength="5" placeholder="Zip Code" value="<?php echo $user_details_array['user_zipcode']; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12 col-md-12">
													<label>Communication Preferences <span class="required">*</span></label>
													<div class="well background_white">
														<div class="row">
															<div class="col-md-4 form-group">
																<label for="user_communication_via_email" class="checkbox-inline">
																	<input type="checkbox" value="1" class="communication_preferences" id="user_communication_via_email" name="user_communication_via_email" <?php
																	if ($user_details_array['user_communication_via_email'] === '1') {
																		echo 'checked="checked"';
																	}
																	?> />
																	Email</label>
															</div>
															<div class="col-md-4 form-group">
																<label for="user_communication_via_phone_call" class="checkbox-inline">
																	<input type="checkbox" value="1" class="communication_preferences" id="user_communication_via_phone_call" name="user_communication_via_phone_call" <?php
																	if ($user_details_array['user_communication_via_phone_call'] === '1') {
																		echo 'checked="checked"';
																	}
																	?> />
																	Phone Call</label>
															</div>
															<div class="col-md-4 form-group">
																<label for="user_communication_via_sms" class="checkbox-inline">
																	<input type="checkbox" value="1" class="communication_preferences" id="user_communication_via_sms" name="user_communication_via_sms" <?php
																	if ($user_details_array['user_communication_via_sms'] === '1') {
																		echo 'checked="checked"';
																	}
																	?> />
																	SMS</label>
															</div>
														</div>
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
		$("#countries_id").change(function () {
			$("#states_id").html('<option value=""></option>').select2();
			$("#cities_id").html('<option value=""></option>').select2();
			$.getJSON(base_url + 'states/get_active_states_by_country_id/' + $(this).val(), function (data) {
				$.each(data, function (i, v) {
					$("#states_id").append('<option value="' + v.state_id + '">' + v.state_name + '</option>');
				});
			});
		});
		$("#states_id").change(function () {
			$("#cities_id").html('<option value=""></option>').select2();
			$.getJSON(base_url + 'cities/get_active_cities_by_state_id/' + $(this).val(), function (data) {
				$.each(data, function (i, v) {
					$("#cities_id").append('<option value="' + v.city_id + '">' + v.city_name + '</option>');
				});
			});
		});
		$("#user_edit_form").validate({
			errorElement: 'span',
			errorClass: 'help-block',
			focusInvalid: true,
			ignore: null,
			rules: {
				user_first_name: {
					required: true,
					lettersonly: true
				},
				user_last_name: {
					required: true,
					lettersonly: true
				},
				user_facebook_url: {
					url: true
				},
				user_twitter_url: {
					url: true
				},
				user_linkedin_url: {
					url: true
				},
				user_instagram_url: {
					url: true
				},
				user_address_line_1: {
					required: true
				},
				countries_id: {
					required: true
				},
				states_id: {
					required: true
				},
				cities_id: {
					required: true
				},
				user_zipcode: {
					required: true,
					digits: true,
					rangelength: [5, 5]
				},
				user_communication_via_email: {
					require_from_group: [1, ".communication_preferences"]},
				user_communication_via_phone_call: {
					require_from_group: [1, ".communication_preferences"]
				},
				user_communication_via_sms: {
					require_from_group: [1, ".communication_preferences"]
				}
			},
			messages: {
				user_first_name: {
					required: "First Name is required.",
					lettersonly: "Please Use Alphabets Only."
				},
				user_last_name: {
					required: "Last Name is required.",
					lettersonly: "Please Use Alphabets Only."
				},
				user_facebook_url: {
					url: "Please Enter Valid Facebook Link."
				},
				user_twitter_url: {
					url: "Please Enter Valid Twitter Link."
				},
				user_linkedin_url: {
					url: "Please Enter Valid Linkedin Link."
				},
				user_instagram_url: {
					url: "Please Enter Valid Instagram Link."
				},
				user_address_line_1: {
					required: "Street Address 1 is required."
				},
				countries_id: {
					required: "Country is required."
				},
				states_id: {
					required: "State/Province is required."
				},
				cities_id: {
					required: "City is required."
				},
				user_zipcode: {
					required: "Zip Code is required.",
					digits: "Please enter Digits Only.",
					rangelength: "Zip Code Should have 5 Digits."
				},
				user_communication_via_email: {
					require_from_group: ""},
				user_communication_via_phone_call: {
					require_from_group: "Please Select At Least One Mode."
				},
				user_communication_via_sms: {
					require_from_group: ""
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
						bootbox.alert('Account Edited Succesfully.', function (data) {
							document.location.href = base_url + 'users';
						});
					} else if (data === '0') {
						bootbox.alert('Error While Editing Account !!!');
					} else {
						bootbox.alert(data);
					}
					$("#user_edit_button").button('reset');
				});
			}
		});
	});
	function show_edit_error() {
		$("#user_edit_form_div").prepend('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error While Editing Account !!!</div>');
		setTimeout(function () {
			$('.alert-danger').fadeOut();
		}, 2000);
	}
</script>