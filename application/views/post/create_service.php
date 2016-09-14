<link rel="stylesheet" media="all" type="text/css" href="//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.css" />
<?php
if (isset($post_details_array) && count($post_details_array['post_time_availability_array'] > 0)) {
	foreach ($post_details_array['post_time_availability_array'] as $available_day) {
		$available_day_array[] = $available_day['post_availability_day'];
	}
}
?>
<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="bar">
				<div class="btn-group btn-breadcrumb">
					<a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
					<a href="<?php echo base_url(); ?>post/category" class="btn">Post</a>
					<a href="javascript:;" class="btn">Service</a>
					<a href="javascript:;" class="btn"><?php echo $category_details_array['category_name']; ?></a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="well well-lg background_white">
				<h4><?php
					if ($this->router->method === 'edit') {
						echo 'Edit';
					} else {
						echo 'Add';
					}
					?> : <b><?php echo $category_details_array['category_name']; ?></b></h4>
				<div class="row">
					<div class="col-md-12">
						<form id="post_service_form" method="post" name="post_service_form" action="<?php echo base_url(); ?>post/preview" target="post_preview_window">
							<input type="hidden" id="post_id" value="<?php
							if (isset($post_details_array) && $post_details_array != '') {
								echo $post_details_array['post_id'];
							}
							?>" name="post_id"/>
							<div class="row">
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="radio-inline"><input type="radio" name="post_show_actual_email" class="post_show_actual_email" value="1" <?php
											if (isset($post_details_array['post_show_actual_email'])) {
												if ($post_details_array['post_show_actual_email'] === '1') {
													echo ' checked="checked"';
												}
											}
											?>> Show My Email as :  <b><?php echo $user_actual_email; ?></b></label>
									</div>
								</div>
								<div class="col-lg-4 col-md-4">
									<div class="form-group">
										<label class="radio-inline"><input type="radio" name="post_show_actual_email" class="post_show_actual_email" value="0" <?php
											if (isset($post_details_array['post_show_actual_email'])) {
												if ($post_details_array['post_show_actual_email'] == '0') {
													echo ' checked="checked"';
												}
											} else {
												echo ' checked="checked"';
											}
											?>> Show My Email as :  <b><?php echo $user_email; ?></b></label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-2 col-lg-2">
									<br/>
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" id="post_communication_by_phone" name="post_communication_by_phone" class="communication_preferences" value="1" <?php
											if (isset($post_details_array['post_communication_by_phone'])) {
												if ($post_details_array['post_communication_by_phone'] == '1') {
													echo ' checked="checked"';
												}
											} else {
												echo ' checked="checked"';
											}
											?>> By Phone </label>
									</div>
								</div>
								<div class="col-md-2 col-lg-2">
									<br/>
									<div class="form-group">
										<label class="checkbox-inline"><input type="checkbox" id="post_communication_by_sms" name="post_communication_by_sms" class="communication_preferences" value="1" <?php
											if (isset($post_details_array['post_communication_by_sms'])) {
												if ($post_details_array['post_communication_by_sms'] == '1') {
													echo ' checked="checked"';
												}
											} else {
												echo ' checked="checked"';
											}
											?>> By Text </label>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="post_contact_name">Contact Name</label>
										<input type="text" id="post_contact_name" name="post_contact_name" class="form-control" placeholder="Enter Contact Name" value="<?php
										if (isset($post_details_array['post_contact_name'])) {
											if ($post_details_array['post_contact_name'] != '') {
												echo $post_details_array['post_contact_name'];
											}
										}
										?>">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="post_contact_number">Phone Number</label>
										<input type="text" id="post_contact_number" name="post_contact_number" class="form-control" placeholder="Enter Phone Number" value="<?php
										if (isset($post_details_array['post_contact_number'])) {
											if ($post_details_array['post_contact_number'] != '') {
												echo $post_details_array['post_contact_number'];
											}
										}
										?>">
									</div>
								</div>
							</div><hr />
							<div class="row">
								<div class="col-md-12">
									<div id="rootwizard">
										<ul>
											<li><a href="#tab_services" data-toggle="tab"><span class="glyphicon glyphicon-play-circle"></span> Service</a></li>
											<li><a href="#tab_availability" data-toggle="tab"><span class="glyphicon glyphicon-play-circle"></span> Availability</a></li>
											<li><a href="#tab_image_upload" data-toggle="tab"><span class="glyphicon glyphicon-play-circle"></span> Images</a></li>
										</ul>
										<div class="tab-content">
											<div class="tab-pane" id="tab_services">
												<div>&nbsp;</div>
												<div class="row">
													<div class="col-md-8">
														<div class="form-group">
															<label for="post_title">Post Title <span class="required">*</span></label>
															<input type="text" id="post_title" name="post_title" class="form-control" placeholder="Enter Post Title" value="<?php
															if (isset($post_details_array['post_title'])) {
																if ($post_details_array['post_title'] != '') {
																	echo $post_details_array['post_title'];
																}
															}
															?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-8">
														<div class="form-group">
															<label for="post_description">Description <span class="required">*</span></label>
															<textarea rows="16" id="post_description" name="post_description" class="form-control" placeholder="Enter Post Description"><?php
																if (isset($post_details_array['post_description'])) {
																	if ($post_details_array['post_description'] != '') {
																		echo $post_details_array['post_description'];
																	}
																}
																?></textarea>
														</div>
													</div>
													<div class="col-md-4">
														<br/>
														<?php foreach ($package_deals_array as $package_deal) { ?>
															<div class="row">
																<div class="col-md-4 form-group">
																	<label for="post_deal_<?php echo $package_deal['pricing_option_type']; ?>" class="control-label pull-right"><?php echo $package_deal['pricing_option_name']; ?><?php if ($package_deal['pricing_option_type'] === 1) { ?> <span class="required">*</span><?php } ?></label>
																</div>
																<div class="col-md-8 form-group">
																	<input type="text" class="form-control post_price" placeholder="<?php echo $package_deal['pricing_option_name']; ?> Price" id="post_deal_price_<?php echo $package_deal['pricing_option_type'] ?>" name="post_deal_price_<?php echo $package_deal['pricing_option_type'] ?>"  value="<?php
																	if (isset($post_details_array['post_deal_price_' . $package_deal['pricing_option_type']])) {
																		if ($post_details_array['post_deal_price_' . $package_deal['pricing_option_type']] != '0') {
																			echo $post_details_array['post_deal_price_' . $package_deal['pricing_option_type']];
																		}
																	}
																	?>">
																</div>
															</div>
														<?php } ?>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
															<label class="checkbox-inline"><input type="checkbox" id="post_show_on_map" name="post_show_on_map" value="1" <?php
																if (isset($post_details_array['post_show_on_map'])) {
																	if ($post_details_array['post_show_on_map'] == '1') {
																		echo ' checked="checked"';
																	}
																} else {
																	echo ' checked="checked"';
																}
																?>> Show On Map</label>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label for="post_street">Street</label>
															<input type="text" id="post_street" name="post_street" class="form-control" placeholder="Enter Street" value="<?php
															if (isset($post_details_array['post_street'])) {
																if ($post_details_array['post_street'] != '') {
																	echo $post_details_array['post_street'];
																}
															}
															?>">
														</div>
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<label for="post_cross_street">Cross Street</label>
															<input type="text" id="post_cross_street" name="post_cross_street" class="form-control" placeholder="Enter Cross Street" value="<?php
															if (isset($post_details_array['post_cross_street'])) {
																if ($post_details_array['post_cross_street'] != '') {
																	echo $post_details_array['post_cross_street'];
																}
															}
															?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2">
														<div class="form-group">
															<label for="countries_id">Country <span class="required">*</span></label>
															<select class="form-control" id="countries_id" name="countries_id" data-placeholder="Select Country">
																<option></option>
																<?php
																foreach ($countries_array as $country) {
																	?>
																	<option value="<?php echo $country['country_id']; ?>" <?php
																	if (isset($post_details_array['country_id']) && $country['country_id'] == $post_details_array['country_id']) {
																		echo ' selected="selected"';
																	}
																	?>><?php echo $country['country_name']; ?></option><?php
																		}
																		?>
															</select>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="states_id">State/Province <span class="required">*</span></label>
															<select class="form-control" id="states_id" name="states_id" data-placeholder="Select State / Province">
																<option></option>
																<?php
																if (isset($states_array) && $post_details_array['state_id']) {
																	foreach ($states_array as $state) {
																		?>
																		<option value="<?php echo $state['state_id']; ?>" <?php
																		if ($state['state_id'] == $post_details_array['state_id']) {
																			echo ' selected="selected"';
																		}
																		?>><?php echo $state['state_name']; ?> </option>
																				<?php
																			}
																		}
																		?>
															</select>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="cities_id">City <span class="required">*</span></label>
															<select class="form-control" id="cities_id" name="cities_id" data-placeholder="Select City">
																<option></option>
																<?php
																if (isset($cities_array) && $post_details_array['city_id']) {
																	foreach ($cities_array as $city) {
																		?>
																		<option value="<?php echo $city['city_id']; ?>" <?php
																		if ($city['city_id'] == $post_details_array['city_id']) {
																			echo ' selected="selected"';
																		}
																		?>><?php echo $city['city_name']; ?> </option>
																				<?php
																			}
																		}
																		?>
															</select>
														</div>
													</div>
													<div class="col-md-2">
														<div class="form-group">
															<label for="post_zipcode">Zip Code <span class="required">*</span></label>
															<input type="text" class="form-control" id="post_zipcode" name="post_zipcode" maxlength="5" placeholder="Enter Zip Code" value="<?php
															if (isset($post_details_array['post_zipcode'])) {
																if ($post_details_array['post_zipcode'] != '') {
																	echo $post_details_array['post_zipcode'];
																}
															}
															?>">
															<input type="hidden" class="form-control" id="post_image_created" name="post_image_created"  value="<?php
															if (isset($post_details_array['post_created'])) {
																if ($post_details_array['post_created'] != '') {
																	echo $post_details_array['post_created'];
																}
															}
															?>">
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="tab_availability"><br/>
												<div class="row">
													<div class="col-md-8">
														<div class="alert alert-info">
															You do not need to fill out availability hours. Please just select time zone.

														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="row">
															<div class="col-md-2">
																<div class="form-group">
																	<label class="checkbox-inline"><input type="checkbox" id="select_all_days" value="1">Select All</label>
																</div>
															</div>
															<div class="col-md-3 text-center"><b>Enter Available Hours</b></div>
															<div class="col-md-3">
																<div class="form-group">
																	<select class="form-control" id="time_zones_id" name="time_zones_id" data-placeholder="Select Time Zone">
																		<option></option>
																		<?php foreach ($time_zones_array as $time_zone) { ?>
																			<option value="<?php echo $time_zone['time_zone_id']; ?>"<?php
																			if (isset($post_details_array['time_zones_id']) && $post_details_array['time_zones_id'] === $time_zone['time_zone_id']) {
																				echo ' selected="selected"';
																			}
																			?>><?php echo $time_zone['time_zone_name']; ?></option>
																				<?php } ?>
																	</select>
																</div>
															</div>
														</div>
														<?php foreach ($weekdays_array as $key => $weekday) { ?>
															<div class="row">
																<div class="col-md-2">
																	<div class="form-group">
																		<label class="checkbox-inline"><input type="checkbox" class="post_availability" name="post_availability_<?php echo $key; ?>" id="post_availability_<?php echo $key; ?>" value="<?php echo $key; ?>"  <?php
																			if (isset($post_details_array['post_time_availability_array']) && count($post_details_array['post_time_availability_array']) > 0 && in_array($key, $available_day_array)) {
																				echo ' checked="checked"';
																			}
																			?>> <?php echo ucwords($weekday); ?> </label>
																	</div>
																</div>
																<div class="col-md-3">
																	<div class="form-group input-group">
																		<input type="text" class="form-control range_from range_availability_from" id="post_availability_<?php echo $key; ?>_from" name="post_availability_<?php echo $key; ?>_from" value="<?php
																		if (isset($post_details_array['post_time_availability_array']) && count($post_details_array['post_time_availability_array']) > 0 && in_array($key, $available_day_array)) {
																			foreach ($post_details_array['post_time_availability_array'] as $time_availability) {
																				if ($time_availability['post_availability_day'] == $key) {
																					echo date('h:i A', strtotime($time_availability['post_availability_from']));
																				}
																			}
																		}
																		?>"  <?php
																			   if (isset($post_details_array['post_time_availability_array']) && count($post_details_array['post_time_availability_array']) > 0) {
																				   if (!in_array($key, $available_day_array)) {
																					   echo ' disabled="disabled"';
																				   }
																			   } else {
																				   echo ' disabled="disabled"';
																			   }
																			   ?>><span class="input-group-addon">TO</span><input type="text" class="form-control range_to range_availability_to" id="post_availability_<?php echo $key; ?>_to" name="post_availability_<?php echo $key; ?>_to" value="<?php
																			   if (isset($post_details_array['post_time_availability_array']) && count($post_details_array['post_time_availability_array']) > 0 && in_array($key, $available_day_array)) {
																				   foreach ($post_details_array['post_time_availability_array'] as $time_availability) {
																					   if ($time_availability['post_availability_day'] == $key) {
																						   echo date('h:i A', strtotime($time_availability['post_availability_to']));
																					   }
																				   }
																			   }
																			   ?>" <?php
																			   if (isset($post_details_array['post_time_availability_array']) && count($post_details_array['post_time_availability_array']) > 0) {
																				   if (!in_array($key, $available_day_array)) {
																					   echo ' disabled="disabled"';
																				   }
																			   } else {
																				   echo ' disabled="disabled"';
																			   }
																			   ?>>
																	</div>
																</div>
																<?php if ($key == '1') { ?>
																	<div class="col-md-2">
																		<div class="form-group">
																			<label class="checkbox-inline"><input type="checkbox" id="apply_to_all_days">Apply Same to Selected</label>
																		</div>
																	</div>
																<?php } ?>
															</div>
														<?php } ?>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="tab_image_upload">
												<div>&nbsp;</div>
												<div class="row">
													<div class="col-md-12 text-center" id="uploader_container">
														<a id="uploader" href="javascript:;" class="btn btn-lg btn-success"><i class="fa fa-photo"></i> Select Images</a>
													</div>
												</div>
												<div>&nbsp;</div>
												<div class="row">
													<div class="col-md-12">
														<ul id="uploaded_images" style="list-style-type: none;">
															<?php
															if (isset($post_details_array['post_images_array']) && count($post_details_array['post_images_array']) > 0) {
																for ($i = 0; $i < count($post_details_array['post_images_array']); $i++) {
																	$old_images_ids_array[] = $post_details_array['post_images_array'][$i]['post_image_id'];
																	$post_image_name_array = explode('.', $post_details_array['post_images_array'][$i]['post_image_name']);
																	$extension = array_pop($post_image_name_array);
																	if (is_file(FCPATH . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_details_array['post_created'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension)) {
																		?>
																		<li class="pull-left col-md-2"><a class="pull-right remove_image_button" onclick="$(this).parent().remove();"><i class="fa fa-2x fa-times-circle"></i></a><div class="panel panel-default"><div class="panel-body"><input type="hidden" name="post_image_name[]" value="<?php echo $post_details_array['post_images_array'][$i]['post_image_name']; ?>"><input type="hidden" name="post_image_type[]" value="OLD"><img class="img img-responsive" src="<?php echo base_url() . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($post_details_array['post_created'])) . implode('.', $post_image_name_array) . '_thumb.' . $extension; ?>" /></div></div></li>
																		<?php
																	}
																}
															}
															?>
														</ul>
													</div>
												</div>
											</div>
											<hr/>
											<div class="col-md-12">
												<ul class="pager wizard">
													<li class="previous" id="pager_back" style="display: none;"><a href="javascript:;" class="btn btn-lg"><i class="fa fa-chevron-circle-left"></i> Back</a></li>
													<li class="next" id="pager_next"><a href="javascript:;" class="btn btn-lg">Continue <i class="fa fa-chevron-circle-right"></i></a></li>
													<li class="pull-right finish" id="pager_publish" style="display:none;"><a href="javascript:;" class="btn btn-lg" >Publish <i class="fa fa-chevron-circle-right"></i></a></li>
													<li class="pull-right finish" id="pager_finish" style="display:none;"><a href="javascript:;" class="btn btn-lg">Preview Draft <i class="fa fa-chevron-circle-right"></i></a> &nbsp; </li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap-wizard/1.2/jquery.bootstrap.wizard.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/plupload/js/plupload.full.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.3.1/jquery.maskedinput.min.js"></script>
<script type="text/javascript">
																$(function () {
																	$("#post_contact_number").mask("999-999-9999");
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
																	$("#select_all_days").click(function () {
																		if ($(this).is(':checked')) {
																			$('.post_availability').each(function (i, v) {
																				$(v).prop('checked', true);
																				$("#post_availability_" + $(v).val() + "_from").prop('disabled', false);
																				$("#post_availability_" + $(v).val() + "_to").prop('disabled', false);
																			});
																		} else {
																			$('.post_availability').each(function (i, v) {
																				$(v).prop('checked', false);
																				$("#post_availability_" + $(v).val() + "_from").val('').prop('disabled', true);
																				$("#post_availability_" + $(v).val() + "_to").val('').prop('disabled', true);
																			});
																		}
																	});
																	$("#apply_to_all_days").click(function () {
																		if ($(this).is(':checked')) {
																			$('.post_availability').each(function (i, v) {
																				if ($(v).is(':checked')) {
																					$("#post_availability_" + $(v).val() + "_from").val($(".range_availability_from").first().val());
																					$("#post_availability_" + $(v).val() + "_to").val($(".range_availability_to").first().val());
																				}
																			});
																		}
																	});
																	$(".post_availability").change(function () {
																		if ($(this).is(':checked')) {
																			$("#post_availability_" + $(this).val() + "_from").prop('disabled', false);
																			$("#post_availability_" + $(this).val() + "_to").prop('disabled', false);
																		} else {
																			$("#post_availability_" + $(this).val() + "_from").val('').prop('disabled', true);
																			$("#post_availability_" + $(this).val() + "_to").val('').prop('disabled', true);
																		}
																	});
																	$('.range_from').each(function (i, v) {
																		$(v).timepicker({timeFormat: "hh:mm TT"});
																		$(v).next('span').next('input.range_to').timepicker({timeFormat: "hh:mm TT"});
																	});
																	$("#uploaded_images").sortable();
																	$("#uploaded_images").disableSelection();
																	$.validator.addMethod("lettersonly", function (value, element) {
																		return this.optional(element) || /^[a-z\s]+$/i.test(value);
																	}, "Please Use Alphabets Only.");
																	$.validator.addMethod("price", function (value, element) {
																		return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/i.test(value);
																	}, "Please Enter Valid Price.");
																	var $validator = $("#post_service_form").validate({
																		errorElement: 'span',
																		errorClass: 'help-block',
																		focusInvalid: true,
																		ignore: null,
																		rules: {
//																			post_communication_by_phone: {
//																				require_from_group: [1, ".communication_preferences"]
//																			},
//																			post_communication_by_sms: {
//																				require_from_group: [1, ".communication_preferences"]
//																			},
																			post_contact_name: {
																				lettersonly: true
																			},
																			post_contact_number: {
																				phoneUS: true
																			},
																			post_title: {
																				required: true,
																				minlength: 10,
																				maxlength: 200
																			},
																			post_location: {
																				required: true
																			},
																			post_description: {
																				required: true,
																				minlength: 50,
																				maxlength: 32768
																			},
																			post_deal_price_1: {
																				require_from_group: [1, ".post_price"],
																				price: true,
																				min: 5
																			},
																			post_deal_price_2: {
                                                                                require_from_group: [1, ".post_price"],
																				price: true,
																				min: 5
																			},
																			post_deal_price_3: {
                                                                                require_from_group: [1, ".post_price"],
																				price: true,
																				min: 5
																			},
																			post_deal_price_custom: {
                                                                                require_from_group: [1, ".post_price"],
																				price: true,
																				min: 5
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
																			post_zipcode: {
																				required: true,
																				digits: true,
																				rangelength: [5, 5]
																			}
																		},
																		messages: {
//																			post_communication_by_phone: {
//																				require_from_group: "Please Select At least One Mode."
//																			},
//																			post_communication_by_sms: {
//																				require_from_group: ""
//																			},
																			post_title: {
																				required: "Post Title is required."
																			},
																			post_location: {
																				required: "Post Location is required."
																			},
																			post_description: {
																				required: "Post Description is required."
																			},
																			post_deal_price_1: {
																				require_from_group: 'Please Enter at least One Price.',
																				min: 'Minimum amount is USD 5.'
																			},
																			post_deal_price_2: {
                                                                                require_from_group: 'Please Enter at least One Price.',
																				min: 'Minimum amount is USD 5.'
																			},
																			post_deal_price_3: {
                                                                                require_from_group: 'Please Enter at least One Price.',
																				min: 'Minimum amount is USD 5.'
																			},
																			post_deal_price_custom: {
                                                                                require_from_group: 'Please Enter at least One Price.',
																				min: 'Minimum amount is USD 5.'
																			},
																			countries_id: {
																				required: "Country is required."
																			},
																			states_id: {
																				required: "State is required."
																			},
																			cities_id: {
																				required: "City is required."
																			},
																			post_zipcode: {
																				required: "Zip Code is required.",
																				digits: "Please enter Digits Only.",
																				rangelength: "Zip Code Should have 5 Digits."
																			}
																		},
																		invalidHandler: function (event, validator) {

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
																		}
																	});
																	$('select').change(function () {
																		$("#post_service_form").validate().element($(this));
																	});
																	$('#rootwizard').bootstrapWizard({
																		'tabClass': 'nav nav-pills',
																		'onNext': function (tab, navigation, index) {
																			return handle_wizard(tab, navigation, index, $validator);
																		},
																		'onPrevious': function (tab, navigation, index) {
																			return handle_wizard(tab, navigation, index, $validator);
																		},
																		'onTabClick': function (tab, navigation, index, currentIndex) {
																			return handle_wizard(tab, navigation, currentIndex, $validator);
																		}
																	});
																	var uploader = new plupload.Uploader({
																		runtimes: 'html5,flash,html4',
																		browse_button: "uploader",
																		container: "uploader_container",
																		url: base_url + 'post/upload_images',
																		chunk_size: '1mb',
																		unique_names: true,
																		flash_swf_url: base_url + 'assets/js/plugins/plupload/js/Moxie.swf',
																		silverlight_xap_url: base_url + 'assets/js/plugins/plupload/js/Moxie.xap',
																		filters: {
																			max_file_size: '10mb',
																			mime_types: [
																				{title: "Image files", extensions: "jpg,gif,png"}
																			]
																		},
																		init: {
																			FilesAdded: function (up, files) {
																				var maxfiles = 24 - $("ul#uploaded_images li").length;
																				if (up.files.length > maxfiles) {
																					up.splice();
																					bootbox.alert('You Can Add ' + maxfiles + ' Images Only !!!');
																				} else {
																					setTimeout(function () {
																						$('#pager_finish').hide();
																						$('#pager_publish').hide();
																						up.start();
																						$("#rootwizard").block();
																					}, 1);
																				}
																			},
																			FileUploaded: function (up, file) {
																				if ($("ul#uploaded_images li").length < 24) {
																					$("#uploaded_images").append('<li class="pull-left col-md-2"><a class="pull-right remove_image_button" onclick="$(this).parent().remove();"><i class="fa fa-2x fa-times-circle"></i></a><div class="panel panel-default"><div class="panel-body"><input type="hidden" name="post_image_name[]" value="' + file.target_name + '"><input type="hidden" name="post_image_type[]" value="NEW"><img class="img img-responsive" src="' + base_url + 'uploads/' + file.target_name + '" /></div></div></li>');
																					$("#uploaded_images").sortable();
																					$("#uploaded_images").disableSelection();
																				} else {
																					bootbox.alert('Max File Limit Reached !!!');
																				}
																			},
																			UploadComplete: function () {
																				$('#pager_finish').show();
																				$('#pager_publish').show();
																				$("#rootwizard").unblock();
																			},
																			Error: function (up, err) {
																				bootbox.alert(err.message);
																			}
																		}
																	});
																	uploader.init();
																	$("#pager_finish").click(function () {
																		   window.open("", "post_preview_window", "width=1366,height=768,toolbar=0,scrollbars=1");
																		   $("#post_service_form").submit();
																	});
																	$("#pager_publish").click(function () {
																		$.post('', $("#post_service_form").serialize(), function (data) {
																			if (parseInt(data) > 0) {
																				bootbox.alert('Congratulation: Your posting is now published. !!!', function () {
																					document.location.href = base_url + 'dashboard';
																				});
																			} else if (data === '0') {
																				bootbox.alert('Error !!');
																			} else {
																				bootbox.alert(data);
																			}
																		});
																	});
																});
																function handle_wizard(tab, navigation, index, $validator) {
																	var $valid = $("#post_service_form").valid();
																	if (!$valid) {
																		$validator.focusInvalid();
																		return false;
																	}
																	var total = navigation.find('li').length;
																	var current = index + 1;
																	if (current === 4) {
																		$("#time_zones_id").rules("add", {
																			required: true
																		});
																	}
																	if (total === current) {
																		$('#pager_next').hide();
																		$('#pager_finish').show();
																		$('#pager_publish').show();
																		setTimeout(function () {
																			$('#pager_finish').removeClass('disabled');
																			$('#pager_publish').removeClass('disabled');
																		}, 100);
																	} else {
																		$('#pager_next').show();
																		$('#pager_finish').hide();
																		$('#pager_publish').hide();
																	}
																	if (current === 1) {
																		$('#pager_back').hide();
																	} else {
																		$('#pager_back').show();
																	}
																}
</script>