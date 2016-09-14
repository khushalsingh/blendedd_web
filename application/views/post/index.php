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
							<table id="posting_datatable" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>
											Post ID
										</th>
										<th>
											Category Name
										</th>
										<th>
											Post Title
										</th>
										<th>
											Email
										</th>
										<th>
											Date
										</th>
										<th>
											Status
										</th>
										<th>
											Edit
										</th>
										<th>
											Delete
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
		$('#posting_datatable').dataTable({
			"aaSorting": [['0', 'desc']],
			"sAjaxSource": base_url + "post/posts_datatable",
			"oTableTools": {
				"sSwfPath": base_url + "assets/js/plugins/datatables/tabletools/swf/copy_csv_xls_pdf.swf",
				"aButtons": [{
						"sExtends": "pdf",
						"sButtonText": "<i class='fa fa-save'></i> PDF",
						"sTitle": "Posts_pdf",
						"sPdfOrientation": "landscape",
						"sPdfSize": "tabloid",
						"mColumns": [1, 2, 3, 4, 5]
					}, {
						"sExtends": "csv",
						"sButtonText": "<i class='fa fa-save'></i> CSV",
						"sTitle": "Posts_csv",
						"mColumns": [1, 2, 3, 4, 5]
					}]
			},
			"aoColumnDefs": [
				{
					"aTargets": [2],
					"bSearchable": false,
					"mRender": function (data, type, full) {
						return  '<a target="_blank" href=' + base_url + "post/view/" + full[6] + "/" + full[0] + '>' + full[2] + '</a>   ';
					}
				},
				{
					"aTargets": [5],
					"bSearchable": false,
					"mRender": function (data, type, full) {
						switch (data) {
							case '1':
								return '<span class="label label-success">Available</span>';
								break;
							case '-1':
								return '<span class="label label-danger">Deleted</span>';
								break;
							default:
								return '<span class="label label-danger">Not Available</span>';
								break;
						}
					}
				},
				{
					"aTargets": [6],
					"bSortable": false,
					"bSearchable": false,
					"mData": null,
					"mRender": function (data, type, full) {
						return  '<a target="_blank" href=' + base_url + "post/edit/" + full[0] + "/" + full[7] + '><i class="fa fa-edit"></i> Edit</a>   ';
					}
				},
				{
					"aTargets": [7],
					"bSortable": false,
					"bSearchable": false,
					"mData": null,
					"mRender": function (data, type, full) {
						if (full[5] == '-1') {
							return  '<a href="javascript:;"  onclick = "javascript:;" > <i class = "fa fa-times" > </i> Deleted</a>';
						} else {
							return  '<a href="javascript:;"  onclick = "update_post_status(' + full[0] + ');" > <i class = "fa fa-times" > </i> Delete</a>';
						}
					}
				}
			]
		}).fnSetFilteringDelay(700);

	});
	function update_post_status(id) {
		bootbox.confirm('Do You want to delete this post ?', function (result) {
			if (result) {
				$.post(base_url + 'post/post_status', {'post_id': id
				}, function (data) {
					if (data == '1') {
						document.location.href = '';
					} else {
						bootbox.alert('Error While deleting post!!!');
					}
				});
			}
		});
	}
</script>