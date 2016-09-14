<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.9.4/css/jquery.dataTables.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.9.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.delay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_custom.js"></script>
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
						<div class="tab-pane active" id="messages">
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2><a class="active" href="<?php echo base_url(); ?>messages/inbox">Inbox</a></h2></div>
									</div>
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2><a href="<?php echo base_url(); ?>messages/sent">Sent</a></h2></div>
									</div>
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2><a href="<?php echo base_url(); ?>messages/trash">Trash</a></h2></div>
									</div>
								</div>
								<div class="col-lg-9 col-md-9" id="messages_data_div">
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<div class="form-group message_list">
												<ul class="list-unstyled list-inline">
													<li>
														<input type="checkbox" id="mass_actions_checkbox" onchange="select_unselect_all(this);" />
													</li>
													<li>
														<a href="javascript:;" class="btn btn-default" onclick="delete_messages();">Delete</a>
													</li>
													<li>
														<div class="dropdown">
															<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
																Mark As
																<span class="caret"></span>
															</a>
															<ul class="dropdown-menu" role="menu">
																<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;" onclick="mark_messages_read();">Read</a></li>
																<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;" onclick="mark_messages_unread();">Unread</a></li>
															</ul>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<table id="messages_datatable" class="table table-striped">
												<thead>
													<tr>
														<th>
															&nbsp;
														</th>
														<th>
															From
														</th>
														<th>
															Subject
														</th>
														<th>
															Dated
														</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="col-lg-9 col-md-9" id="post_messages_data_div" style="display: none;">
									<div class="row">
										<div class="col-md-2">
											<button id="show_messages_data_div" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</button>
										</div>
										<div class="col-md-8" id="post_messages_subject"></div>
										<div class="col-md-2">
											<button id="post_message_reply" class="btn btn-info"><i class="fa fa-reply"></i> Reply</button>
										</div>
									</div>
									<div class="row" id="post_message_reply_div" style="display: none;">
										<div class="col-md-8 col-md-offset-2">
											<textarea id="new_message_body" rows="4" class="form-control" placeholder="Enter Message Here.."></textarea>
										</div>
										<div class="col-md-2">
											<br/>
											<button id="message_send" class="btn btn-success"><i class="fa fa-send"></i> Post Reply</button>
											<br/>
											<br/>
											<button id="message_cancel" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</button>
										</div>
									</div>
									<hr/>
									<div id="post_messages_body"></div>
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
	$(function () {
		$('select.select2').select2();
	});
	$('#messages_datatable').dataTable({
		"aaSorting": [['0', 'desc']],
		"sAjaxSource": base_url + "messages/inbox_datatable",
		"aoColumnDefs": [
			{
				"aTargets": [0],
				"bSortable": false,
				"bSearchable": false,
				"mData": null,
				"mRender": function (data, type, full) {
					return '<input type="checkbox" class="post_checkbox" value="' + full[0] + '"><span style="display:none">' + full[4] + '</span>';
				}
			}
		],
		"fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			if (aData[5] === '0') {
				$(nRow).addClass('text-bold');
			}
		}
	}).fnSetFilteringDelay(700);
	$('#messages_datatable tbody').on('click', 'tr', function (e) {
		if (e.target.type === 'checkbox') {
			e.stopPropagation();
		} else {
			if ($('td', this).hasClass('dataTables_empty')) {
				return;
			}
			$("#messages_data_div").block();
			var post_id = $('td', this).eq(0).children('input').val();
			var for_user_id = $('td', this).eq(0).text();
			var that = this;
			if ($(this).hasClass('text-bold')) {
				$.post(base_url + 'messages/mark_inbox_messages_read_by_post_id', {'posts_id[]': post_id}, function (data) {
					if (data === '1') {
						$(that).removeClass('text-bold');
					} else {
						bootbox.alert('System Error !!!');
						return;
					}
				});
			}
			$.post(base_url + 'messages/get_messages_by_post_id', {'post_id': post_id}, function (data) {
				$("#messages_data_div").unblock();
				$("#messages_data_div").hide();
				$("#post_messages_data_div").fadeIn('fast');
				$("#post_messages_subject").html(data[0].post_title);
				$("#new_message_body").attr('data-post-id', post_id);
				$("#new_message_body").attr('data-for-user-id', for_user_id);
				var messages_html = '';
				$.each(data, function (i, v) {
					var message_body = v.message_body.replace(/\r?\n/g, '<br />');
					messages_html += '<div class="row">' +
							'<div class="col-md-12">' +
							'<div class="pull-left">From : <b>' + v.user_login + '</b></div>' +
							'<div class="pull-right"><b>' + v.message_created + '</b></div>' +
							'<div class="clearfix"></div>' +
							'</div>' +
							'</div><br/>' +
							message_body +
							'<hr/>';
				});
				$("#post_messages_body").html(messages_html);
			});
		}
	});

	$("#show_messages_data_div").click(function () {
		$("#messages_data_div").fadeIn('fast');
		$("#post_messages_data_div").hide();
		$("#message_cancel").trigger('click');
	});

	$("#post_message_reply").click(function () {
		$(this).hide();
		$("#post_message_reply_div").fadeIn('fast');
		$("#new_message_body").val('').focus();
	});

	$("#message_cancel").click(function () {
		$("#post_message_reply_div").hide();
		$("#new_message_body").val('');
		$("#post_message_reply").fadeIn('fast');
	});

	$("#message_send").click(function () {
		$.post(base_url + 'messages/create', {posts_id: $("#new_message_body").attr('data-post-id'), 'message_to_users_id': $("#new_message_body").attr('data-for-user-id'), 'message_body': $("#new_message_body").val()}, function (data) {
			if (data === '1') {
				var message_body = $("#new_message_body").val().replace(/\r?\n/g, '<br />');
				var message_html = '<div class="row">' +
						'<div class="col-md-12">' +
						'<div class="pull-left">From : <b><?php echo $_SESSION['user']['user_login']; ?></b></div>' +
						'<div class="pull-right"><b>Just Now</b></div>' +
						'<div class="clearfix"></div>' +
						'</div>' +
						'</div><br/>' +
						message_body +
						'<hr/>';
				$("#post_messages_body").prepend(message_html);
				$("#message_cancel").trigger('click');
			} else {
				bootbox.alert('Something Went Wrong !!!');
			}
		});
	});

	function select_unselect_all(t) {
		if ($(t).is(':checked')) {
			$(".post_checkbox").each(function (i, v) {
				$(v).prop('checked', true);
			});
		} else {
			$(".post_checkbox").each(function (i, v) {
				$(v).removeAttr('checked');
			});
		}
	}

	function delete_messages() {
		var posts_ids_array = [];
		$(".post_checkbox").each(function (i, v) {
			if ($(v).is(':checked')) {
				posts_ids_array.push($(v).val());
			}
		});
		if (posts_ids_array.length > 0) {
			bootbox.confirm('Do You want to move selected messages to trash ?', function (result) {
				if (result) {
					$.post(base_url + 'messages/mark_inbox_messages_deleted_by_post_id', {'posts_id[]': posts_ids_array}, function (data) {
						if (data === '1') {
							document.location.href = '';
						} else {
							bootbox.alert('System Error !!!');
							return;
						}
					});
				}
			});
		}
	}
	function mark_messages_read() {
		var posts_ids_array = [];
		$(".post_checkbox").each(function (i, v) {
			if ($(v).is(':checked')) {
				posts_ids_array.push($(v).val());
			}
		});
		if (posts_ids_array.length > 0) {
			$.post(base_url + 'messages/mark_inbox_messages_read_by_post_id', {'posts_id[]': posts_ids_array}, function (data) {
				if (data === '1') {
					$(".post_checkbox").each(function (i, v) {
						if ($(v).is(':checked')) {
							$(v).parent('td').parent('tr').removeClass('text-bold');
						}
					});
				} else {
					bootbox.alert('System Error !!!');
					return;
				}
			});
		}
	}

	function mark_messages_unread() {
		var posts_ids_array = [];
		$(".post_checkbox").each(function (i, v) {
			if ($(v).is(':checked')) {
				posts_ids_array.push($(v).val());
			}
		});
		if (posts_ids_array.length > 0) {
			$.post(base_url + 'messages/mark_inbox_messages_unread_by_post_id', {'posts_id[]': posts_ids_array}, function (data) {
				if (data === '1') {
					$(".post_checkbox").each(function (i, v) {
						if ($(v).is(':checked')) {
							$(v).parent('td').parent('tr').addClass('text-bold');
						}
					});
				} else {
					bootbox.alert('System Error !!!');
					return;
				}
			});
		}
	}
</script>