<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.9.4/css/jquery.dataTables.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.9.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/tabletools/js/dataTables.tableTools.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.delay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_custom.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
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
						<div class="tab-pane tab-pane fade in active">
							<table id="payment_datatable" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>
											Txn Id
										</th>
										<th>
											Post Title
										</th>
										<th>
											Email
										</th>
										<th>
											Net Amount
										</th>
										<th>
											Merchant Amount
										</th>
										<th>
											Service Fee
										</th>
										<th>
											Paid On
										</th>
										<th>
											Status
										</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('#payment_datatable').dataTable({
			"aaSorting": [['0', 'desc']],
			"sAjaxSource": base_url + "payments/invoices_datatable",
			"oTableTools": {
				"sSwfPath": base_url + "assets/js/plugins/datatables/tabletools/swf/copy_csv_xls_pdf.swf",
				"aButtons": [{
						"sExtends": "pdf",
						"sButtonText": "<i class='fa fa-save'></i> PDF",
						"sTitle": "Payment_pdf",
						"sPdfOrientation": "landscape",
						"sPdfSize": "tabloid",
						"mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
					}, {
						"sExtends": "csv",
						"sButtonText": "<i class='fa fa-save'></i> CSV",
						"sTitle": "Payment_csv",
						"mColumns": [0, 1, 2, 3, 4, 5, 6, 7]
					}]
			},
			"aoColumnDefs": [
				{
					"aTargets": [3],
					"bSearchable": false,
					"mRender": function (data, type, full) {
						return "$ " + parseFloat(full[3]).toFixed(2);
					}
				},
				{
					"aTargets": [4],
					"bSearchable": false,
					"mRender": function (data, type, full) {
						return "$ " + parseFloat(full[4]).toFixed(2);
					}
				},
				{
					"aTargets": [5],
					"bSearchable": false,
					"mRender": function (data, type, full) {
						return "$ " + parseFloat(full[5]).toFixed(2);
					}
				},
				{
					"aTargets": [7],
					"bSearchable": false,
					"mRender": function (data, type, full) {
						switch (data) {
							case '1':
								return '<span class="label label-success">Settled</span>';
								break;
							default:
								return '<span class="label label-danger">Not Settled</span>';
								break;
						}
					}
				}
			]
		}).fnSetFilteringDelay(700);
	});
</script>