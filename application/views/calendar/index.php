<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">
		<!-- Modal -->
		<div class="modal fade" data-backdrop="static" data-keyboard="false"  id="eventmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Create Event</h4>
					</div>
					<div class="modal-body">
						<form action="#" class="form-horizontal" id="eventform">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Event Title:<span class="required">
											*
										</span></label>
									<div class="col-md-7">
										<input type="text" class="form-control" placeholder="Enter text" name="eventname" id="eventname">

									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Start Date:<span class="required">
											*
										</span></label>
									<div class="col-md-7">
										<div class="input-group date form_datetime" data-date="<?php echo date('Y-m-d H:i'); ?>">
											<input type="text" size="16" readonly class="form-control" name="startdate" id="startdate">	
											<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<!-- /input-group -->
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">All day event:</label>
									<div class="col-md-1">
										<input type="checkbox" class="form-control" name="eventtype" id="eventtype">	
										<!-- /input-group -->
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">End Date:</label>
									<div class="col-md-7">
										<div class="input-group date form_datetime" data-date="<?php echo date('Y-m-d H:i'); ?>">
											<input type="text" size="16" readonly class="form-control" name="enddate" id="enddate">	
											<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<!-- /input-group -->
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Event Color:</label>
									<div class="col-md-7">
										<div class="input-group color colorpicker-default" data-color="#69a4e0" data-color-format="rgba">
											<input type="text" class="form-control" value="#69a4e0" readonly name="event_color" id="eventcolor">
											<span class="input-group-btn">
												<button class="btn default" type="button"><i style="background-color: #69a4e0;"></i>&nbsp;</button>
											</span>
										</div>
										<!-- /input-group -->
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="insert-event">Save changes</button>
					</div>
				</div>
			</div>
		</div>
		<!--end calendar popup-->
		<!-- edit event model -->
		<div class="modal fade" data-backdrop="static" data-keyboard="false"  id="editeventmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">Edit Event</h4>
					</div>
					<div class="modal-body">
						<form action="#" class="form-horizontal" id="eventform">
							<div class="form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">Event Title:<span class="required">
											*
										</span></label>
									<div class="col-md-7">
										<input type="hidden" id="calendarid" name="calendarid"/>
										<input type="text" class="form-control" placeholder="Enter text" name="edit-eventname" id="edit-eventname">

									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Start Date:<span class="required">
											*
										</span></label>
									<div class="col-md-7">
										<div class="input-group date form_datetime" data-date="<?php echo date('Y-m-d H:i'); ?>">
											<input type="text" size="16" readonly class="form-control" name="edit-startdate" id="edit-startdate">	
											<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<!-- /input-group -->
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">All day event:</label>
									<div class="col-md-1">
										<input type="checkbox" class="form-control" name="edit-eventtype" id="edit-eventtype">	
										<!-- /input-group -->
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">End Date:</label>
									<div class="col-md-7">
										<div class="input-group date form_datetime" data-date="<?php echo date('Y-m-d H:i'); ?>">
											<input type="text" size="16" readonly class="form-control" name="edit-enddate" id="edit-enddate">	
											<span class="input-group-btn">
												<button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
										<!-- /input-group -->
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Event Color:</label>
									<div class="col-md-7">
										<div class="input-group color colorpicker-default" data-color="#69a4e0" data-color-format="rgba">
											<input type="text" class="form-control" value="#69a4e0" readonly name="event_color" id="edit-eventcolor">
											<span class="input-group-btn">
												<button class="btn default" type="button"><i style="background-color: #69a4e0;"></i>&nbsp;</button>
											</span>
										</div>
										<!-- /input-group -->
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" id="delete-event">Delete</button>
						<button type="button" class="btn btn-primary" id="update-event">Save changes</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<!--end edit event model-->
		<!-- BEGIN PAGE HEADER-->
		<div class="row">
			<div class="col-md-12">
				<!-- BEGIN PAGE TITLE & BREADCRUMB-->
				<h3 class="page-title">
					My Calendar <small>manage calendar</small>
				</h3>
				<ul class="page-breadcrumb breadcrumb">
					<li>
						<i class="fa fa-dashboard"></i>
						<a href="javascript:;">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="javascript:;">My Calendar</a>
					</li>
				</ul>
				<!-- END PAGE TITLE & BREADCRUMB-->
			</div>
		</div>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">
				<div class="portlet box purple calendar">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-calendar"></i>My Calendar
						</div>
					</div>
					<div class="portlet-body">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div id="calendar" class="has-toolbar">
								</div>
							</div>
						</div>
						<!-- END CALENDAR PORTLET-->
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTENT-->
	</div>
</div>
<!-- END CONTENT -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
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
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/global/plugins/bootbox/bootbox.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- IMPORTANT! fullcalendar depends on jquery-ui-1.10.3.custom.min.js for drag & drop support -->
<script src="<?php echo base_url(); ?>assets/global/plugins/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo base_url(); ?>assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/admin/layout/scripts/layout.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		Metronic.init();
		Layout.init();
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		var h = {};
		if (Metronic.isRTL()) {
			if ($('#calendar').parents(".portlet").width() <= 720) {
				$('#calendar').addClass("mobile");
				h = {
					right: 'title, prev, next',
					center: '',
					left: 'agendaDay, agendaWeek, month, today'
				};
			} else {
				$('#calendar').removeClass("mobile");
				h = {
					right: 'title',
					center: '',
					left: 'agendaDay, agendaWeek, month, today, prev,next'
				};
			}
		} else {
			if ($('#calendar').parents(".portlet").width() <= 720) {
				$('#calendar').addClass("mobile");
				h = {
					left: 'title, prev, next',
					center: '',
					right: 'today,month,agendaWeek,agendaDay'
				};
			} else {
				$('#calendar').removeClass("mobile");
				h = {
					left: 'title',
					center: '',
					right: 'prev,next,today,month,agendaWeek,agendaDay'
				};
			}
		}
		$('#calendar').fullCalendar('destroy');
		$('#calendar').fullCalendar({
			header: h,
			slotMinutes: 15,
			editable: true,
			droppable: true,
			events: <?php echo $events_json; ?>,
			dayClick: function(date, jsEvent, view) {
				$('#eventmodal').modal('show');
				$('#eventname').val('');
				$('#enddate').val('');
				$('#eventtype').removeAttr('checked');
				$.uniform.update();
				$("#startdate").val($.fullCalendar.formatDate(date, 'yyyy-MM-dd HH:mm'));
			},
			eventClick: function(calEvent, jsEvent, view, date) {
				if (calEvent.allDay === true) {
					$('#edit-eventtype').attr('checked', 'checked');
				} else {
					$('#edit-eventtype').removeAttr('checked');
				}
				$.uniform.update();
				$('#editeventmodal').modal('show');
				$("#edit-eventname").val(calEvent.title);
				$("#calendarid").val(calEvent.id);
				$("#edit-startdate").val($.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd HH:mm"));
				if ($.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd HH:mm") === "") {
					$("#edit-enddate").val($.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd HH:mm"));
				} else {
					$("#edit-enddate").val($.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd HH:mm"));
				}
				$(".colorpicker-default").colorpicker('setValue', calEvent.backgroundColor);
				$("#edit-eventcolor").val(calEvent.backgroundColor);
			},
			eventDrop: function(calEvent, dayDelta, minuteDelta, allDay, revertFunc) {
				bootbox.confirm("Do you want to change this event ?<br/>This action can not be undone.", function(confirmed) {
					if (confirmed) {
						$.post(base_url + 'calendar/resize', {'title': calEvent.title, 'event_start': $.fullCalendar.formatDate(calEvent.start, "yyyy-MM-dd HH:mm"), 'event_end': $.fullCalendar.formatDate(calEvent.end, "yyyy-MM-dd HH:mm"), 'id': calEvent.id}, function(data) {
							if ($.trim(data) === '1') {
								$('#editeventmodal').modal('hide');
							} else {
								bootbox.alert(data);
							}
						});
					} else {
						revertFunc();
					}
				});
			},
			eventResize: function(event, dayDelta, minuteDelta, revertFunc) {
				bootbox.confirm("Do you want to change this event ?<br/>This action can not be undone.", function(confirmed) {
					if (confirmed) {
						$.post(base_url + 'calendar/resize', {'title': event.title, 'event_start': $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm"), 'event_end': $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm"), 'id': event.id}, function(data) {
							if ($.trim(data) === '1') {
								$('#editeventmodal').modal('hide');
							} else {
								bootbox.alert(data);
							}
						});
					} else {
						revertFunc();
					}
				});
			}
		});
		$('#insert-event').click(function() {
			$.post(base_url + 'calendar/create', {'title': $('#eventname').val(), 'event_start': $('#startdate').val(), 'event_end': $('#enddate').val(), 'event_is_full_day': $('#eventmodal :checkbox:checked').length, 'event_color': $("#eventcolor").val()}, function(data) {
				if ($.isNumeric(data) === true) {
					var myCalendar = $('#calendar');
					var myEvent = {
						id: data,
						title: $('#eventname').val(),
						allDay: ($('#eventmodal :checkbox:checked').length === 0) ? false : true,
						start: $('#startdate').val(),
						end: $('#enddate').val(),
						backgroundColor: $("#eventcolor").val()
					};
					myCalendar.fullCalendar('renderEvent', myEvent);
					$('#eventmodal').modal('hide');
				} else {
					bootbox.alert(data);
				}
				$.uniform.update();
			});
		});
		$('#update-event').click(function() {
			$.post(base_url + 'calendar/update', {'title': $('#edit-eventname').val(), 'event_start': $('#edit-startdate').val(), 'event_end': $('#edit-enddate').val(), 'id': $('#calendarid').val(), 'event_is_full_day': $('#editeventmodal :checkbox:checked').length, 'event_color': $("#edit-eventcolor").val()}, function(data) {
				if ($.trim(data) === '1') {
					var myCalendar = $('#calendar');
					myCalendar.fullCalendar('removeEvents', $('#calendarid').val());
					var myEvent = {
						id: $('#calendarid').val(),
						title: $('#edit-eventname').val(),
						allDay: ($('#editeventmodal :checkbox:checked').length === 0) ? false : true,
						start: $('#edit-startdate').val(),
						end: $('#edit-enddate').val(),
						backgroundColor: $("#edit-eventcolor").val()
					};
					myCalendar.fullCalendar('renderEvent', myEvent);
					$('#editeventmodal').modal('hide');
				} else {
					bootbox.alert(data);
				}
			});
		});
		$('#delete-event').click(function() {
			bootbox.confirm("Do you want to delete this event ?<br/>This action can not be undone.", function(confirmed) {
				if (confirmed) {
					$.post(base_url + 'calendar/delete', {'id': $('#calendarid').val()}, function(data) {
						if ($.trim(data) === '1') {
							var myCalendar = $('#calendar');
							myCalendar.fullCalendar('removeEvents', $('#calendarid').val());
							$('#editeventmodal').modal('hide');
						} else {
							bootbox.alert(data);
						}
					});
				}
			});
		});
		$(".form_datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii', autoclose: true});
		$('.colorpicker-default').colorpicker({format: 'hex'});
	});
</script>
<!-- END PAGE LEVEL SCRIPTS -->