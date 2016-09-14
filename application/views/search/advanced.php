<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="bar">
				<div class="btn-group btn-breadcrumb">
					<a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
                    <a href="javascript:;" class="btn" title="Advanced Search">Advanced Search</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="well background_white well_blocks">
				<h4>Advanced Search</h4>
				Find Item
				<form class="advance_search_form" role="form" action="<?php echo base_url(); ?>search/results" method="post" id="advance_search_form">
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="form-group">
								<label for="search_keywords">Enter Keywords</label>
								<input id="search_keywords" name="search_keywords" type="text" class="form-control" placeholder="Enter Keywords" maxlength="64">
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="form-group">
								<label for="search_type">Search Type</label>
								<select class="form-control" id="search_type" name="search_type" data-placeholder="Select Search Type">
									<option></option>
									<option value="all-any">All Words , Any Order</option>
									<option value="any-any">Any Words , Any Order</option>
									<option value="exact-exact">Exact Words , Exact Order</option>
									<option value="exact-any">Exact Words , Any Order</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="form-group">
								<label for="not_search_keywords">Exclude Words From Search</label>
								<input id="not_search_keywords" name="not_search_keywords" type="text" class="form-control" placeholder="Exclude Words From Search">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="form-group">
								<label for="categories_id">In this category</label>
								<select class="form-control" id="categories_id" name="categories_id" data-placeholder="Select Category">
									<option></option>
									<?php foreach ($categories_array as $category) { ?>
										<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
											<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<h4>Location</h4>
					<div class="row">
						<div class="col-lg-3 col-md-3">
							<div class="form-group">
								<label for="post_location_radius">Located</label>
								<select class="form-control" id="post_location_radius" name="post_location_radius" data-placeholder="Select Radius">
									<option></option>
									<option>2</option>
									<option>5</option>
									<option>10</option>
									<option>20</option>
									<option>50</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3 col-md-3">
							<div class="form-group">
								<label for="miles_of">Miles Of</label>
								<input id="miles_of" type="text" class="form-control" placeholder="Zip Code">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-3 col-md-3">
							<div class="form-group">
								<button type="submit" class="btn btn-primary blue"><i class="glyphicon glyphicon-search"></i> Search</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function () {
		$("select").select2({
			allowClear: true
		});
		$("#advance_search_form").validate({
			errorElement: 'span',
			errorClass: 'help-block',
			focusInvalid: true,
			rules: {
				search_keywords: {
					required: true
				}
			},
			messages: {
				search_keywords: {
					required: "Search Keywords is required."
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
			},
			submitHandler: function (form) {
				return true;
			}
		});
	});
</script>