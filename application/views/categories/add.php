<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		<!-- BEGIN PAGE HEADER-->
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PAGE TITLE & BREADCRUMB-->
				<h3 class="page-title">
					Product Categories <small>product categories list</small>
				</h3>
				<ul class="page-breadcrumb breadcrumb">
					<li>
						<i class="fa fa-dashboard"></i>
						<a href="javascript:;">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:;">Add Product Categories</a>
					</li>
				</ul>
				<!-- END PAGE TITLE & BREADCRUMB-->
			</div>
		</div>
		<!-- END PAGE HEADER-->
		<div class="row">
			<div class="col-lg-offset-2 col-lg-8 col-md-12 col-sm-12">
				<!-- BEGIN SAMPLE FORM PORTLET-->
				<div class="portlet box green">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-sitemap"></i> Add Product Categories
						</div>
					</div>
					<div class="portlet-body form">
						<form class="form-horizontal" role="form" method="post" id="add_category_form">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Select Brand</label>
									<div class="col-md-9">
										<select class="form-control input-medium select2me" data-placeholder="Select Brand" id="brands_id" name="brands_id">
											<option value=""></option>
											<?php foreach ($brands_array as $brand) { ?>
												<option value="<?php echo $brand['brand_id']; ?>"><?php echo $brand['brand_name']; ?></option>
											<?php } ?>
										</select>
										
									</div>
								</div>
								<div class="form-group clone_component">
									<label class="col-md-3 control-label">Name and Alias</label>
									<div class="col-md-4">
										<input type="text" class="form-control input-inline input-medium" placeholder="Enter Category Name" name="category_name[]">
									</div>
									<div class="col-md-5">
										<input type="text" class="form-control input-inline input-small" placeholder="Alias" name="category_alias[]">
										<a class="clone_component_button" href="javascript:;" onclick="clone_component(this);"><i class="fa fa-2x fa-plus-circle"></i></a>
										<a class="remove_component_button" href="javascript:;" onclick="remove_component(this);" style="display: none;"><i class="fa fa-2x fa-minus-circle"></i></a>
									</div>
								</div>
							</div>
							<div class="form-actions fluid">
								<div class="col-md-offset-3 col-md-9">
									<button type="button" class="btn green" onclick="add_category();">Submit</button>
									<button type="button" class="btn default">Cancel</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<!-- END SAMPLE FORM PORTLET-->
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- END CONTENT -->
<!-- BEGIN JAVASCRIPTS -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="<?php echo base_url(); ?>assets/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootbox/bootbox.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/scripts/common.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script type="text/javascript">
										jQuery(document).ready(function() {
											Metronic.init();
											Layout.init();
										});
										function add_category() {
											$.post('', $("#add_category_form").serialize(), function(data) {
												if (data === '1') {
													document.location.href = base_url + 'categories';
												} else {
													bootbox.alert(data);
												}
											});
										}
</script>
<!-- END JAVASCRIPTS -->