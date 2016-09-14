<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<div class="modal fade bs-modal-lg" id="email_body_modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title" id="email_subject"></h4>
					</div>
					<div class="modal-body" id="email_body"></div>
					<div class="modal-footer">
						<button type="button" class="btn blue" id="resend_email">Resend Email</button>
						<button type="button" class="btn default" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		<!-- /.modal -->
		<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
		<!-- BEGIN PAGE HEADER-->
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PAGE TITLE & BREADCRUMB-->
				<h3 class="page-title">
					Email Logs <small>sent emails listing</small>
				</h3>
				<ul class="page-breadcrumb breadcrumb">
					<li>
						<i class="fa fa-dashboard"></i>
						<a href="javascript:;">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:;">Email Logs</a>
					</li>
					<li class="btn-group">
						<a style="color: white" href="<?php echo base_url(); ?>emails/download" role="button" class="btn blue" target="_blank"><i class="fa fa-download"></i> Download Report</a>
					</li>
				</ul>
				<!-- END PAGE TITLE & BREADCRUMB-->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box purple">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-exchange"></i> Email Logs
						</div>
					</div>
					<div class="portlet-body">
						<table class="table table-striped table-bordered table-hover jquery_datatable">
							<thead>
								<tr>
									<th>
										#
									</th>
									<th>
										Email Address
									</th>
									<th>
										Email Subject
									</th>
									<th>
										Opens
									</th>
									<th>
										Clicks
									</th>
									<th>
										Created
									</th>
									<th>
										Status
									</th>
									<th>
										Preview
									</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
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
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/data-tables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/data-tables/jquery.dataTables.delay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/data-tables/tabletools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/data-tables/DT_bootstrap.js"></script>
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
		$.extend(true, $.fn.DataTable.TableTools.classes, {
			"container": "btn-group tabletools-dropdown-on-portlet",
			"buttons": {
				"normal": "btn btn-sm default",
				"disabled": "btn btn-sm default disabled"
			},
			"collection": {
				"container": "DTTT_dropdown dropdown-menu tabletools-dropdown-menu"
			}
		});
		$.extend($.fn.DataTable.defaults, {
			"aLengthMenu": [
				[5, 15, 20],
				[5, 15, 20]
			],
			"iDisplayLength": 20,
			"sDom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
			"bProcessing": true,
			"bServerSide": true,
			"bDeferRender": true,
			"sServerMethod": "POST"
		});
		$('table.jquery_datatable').dataTable({
			"aaSorting": [['0', 'desc']],
			"sAjaxSource": base_url + "emails/datatable",
			"oTableTools": {
				"sSwfPath": base_url + "assets/global/plugins/data-tables/tabletools/swf/copy_csv_xls_pdf.swf",
				"aButtons": [{
						"sExtends": "pdf",
						"sButtonText": "<i class='fa fa-save'></i> PDF",
						"mColumns": [0, 1, 2, 3]
					}, {
						"sExtends": "csv",
						"sButtonText": "<i class='fa fa-save'></i> CSV",
						"mColumns": [0, 1, 2, 3]
					}]
			},
			"aoColumnDefs": [
				{
					"aTargets": [6],
					"bSearchable": false,
					"mData": null,
					"mRender": function(data, type, full) {
						switch (full[6]) {
							case '-1':
								return '<span class="badge badge-danger">Bounced</span>';
								break;
							case '0':
								return '<span class="badge badge-default">Queued</span>';
								break;
							case '1':
								return '<span class="badge badge-warning">Processed</span>';
								break;
							case '2':
								return '<span class="badge badge-primary">Sent</span>';
								break;
							case '3':
								return '<span class="badge badge-info">Opened</span>';
								break;
							case '4':
								return '<span class="badge badge-success">Clicked</span>';
								break;
							default:
								return '<span class="badge badge-danger">Unknown</span>';
								break;
						}
					}
				},
				{
					"aTargets": [7],
					"bSortable": false,
					"bSearchable": false,
					"mData": null,
					"mRender": function(data, type, full) {
						return '<a href="javascript:;" class="btn btn-sm blue" onclick="show_email_body(' + full[0] + ');"><i class="fa fa-pencil"></i> Preview</a>';
					}
				}]
		}).fnSetFilteringDelay(700);
		var tableWrapper = $('.dataTables_wrapper');
		jQuery('.dataTables_filter input', tableWrapper).addClass("form-control input-small input-inline");
		jQuery('.dataTables_length select', tableWrapper).addClass("form-control input-small");
		jQuery('.dataTables_length select', tableWrapper).select2();
	});

	function show_email_body(id) {
		$.post(base_url + 'emails/get_email_details_by_id', {email_id: id}, function(data) {
			$("#email_subject").html(data.email_subject);
			$("#email_body").html(data.email_body);
			$("#resend_email").attr('onclick', 'resend_email(' + id + ');');
			$("#email_body_modal").modal('show');
		});
	}

	function resend_email(id) {
		bootbox.confirm('<br/><input id="email_cc" type="text" class="form-control" placeholder="Comma Separated Email IDs for CC (Optional)" /><br/>Do you want to resend this email ?<br/><br/>If yes, You can add comma separeated optional CC email addresses above.<br/><br/>Press OK to Continue Else Cancel to Close.', function(result) {
			if (result) {
				Metronic.blockUI({target: $("#email_body_modal"), boxed: true});
				$.post(base_url + 'emails/resend_email', {email_id: id, email_cc: $.trim($("#email_cc").val())}, function(data) {
					if (data === '1') {
						$("#email_body").prepend('<div id="resend_alert" class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> Email Resent Successfully !!!</div>');
					} else if (data === '0') {
						$("#email_body").prepend('<div id="resend_alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> Error Sending Email !!!</div>');
					} else {
						$("#email_body").prepend('<div id="resend_alert" class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button> ' + data + '</div>');
					}
					setTimeout(function() {
						$("#resend_alert").remove();
					}, 2500);
					Metronic.unblockUI($("#email_body_modal"));
				});
			}
		});
	}
</script>
<!-- END JAVASCRIPTS -->