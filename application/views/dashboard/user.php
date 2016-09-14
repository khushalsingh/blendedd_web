<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/9dcbecd42ad/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.min.js"></script>
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
						<div class="tab-pane active" id="activity">
							<?php if (count($item_posted_array) > 0) { ?>
								<style type="text/css">
	<?php if ($item_posted_array['post_status'] === '-1') { ?>
										#item_posted_div .item_posted_visible_non_deleted{
											display: none;
										}
	<?php } else { ?>
										#item_posted_div .item_posted_visible_deleted{
											display: none;
										}
	<?php } ?>
								</style>
								<div class="row" id="item_posted_div">
									<div class="col-lg-3 col-md-3">
										<div class="row">
											<div class="col-lg-12 col-md-12 edit_del">
												<ul class="list-inline">
													<li class="item_posted_visible_non_deleted"><a id="item_posted_edit_link" href="<?php echo base_url(); ?>post/edit/<?php echo $item_posted_array['post_id'] . '/' . $item_posted_array['category_slug']; ?>">Edit</a></li>
													<li class="item_posted_visible_non_deleted">| &nbsp; <a href="javascript:;" onclick="update_post_status('item_posted', '-1');">Delete</a></li>
													<li class="item_posted_visible_deleted"><a href="javascript:;" onclick="update_post_status('item_posted', '1');">Repost</a></li>
												</ul>
											</div>
										</div>
										<div class="row item_posted_visible_non_deleted">
											<div class="col-lg-5 col-md-5">
												<div class="radio">
													<label>
														<input id="item_posted_post_available" value="1" type="radio"<?php
														if ($item_posted_array['post_status'] === '1') {
															echo ' checked="checked"';
														}
														?> onclick="update_post_status('item_posted', '1');
																	return false;" />Available
													</label>
												</div>
											</div>
											<div class="col-lg-6 col-md-6">
												<div class="radio">
													<label>
														<input id="item_posted_post_unavailable" value="0" type="radio"<?php
														if ($item_posted_array['post_status'] === '0') {
															echo ' checked="checked"';
														}
														?> onclick="update_post_status('item_posted', '0');
																	return false;" />Unavailable
													</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-9 col-md-9">
										<div class="row">
											<div class="col-lg-5 col-md-5">
												<h3>Item Posted</h3>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="row">
													<div class="col-lg-6 col-md-6">
														<p>See items filter</p>
													</div>
													<div class="col-lg-6 col-md-6">
														<div class="hidden" id="item_posted_post_within_value"></div>
														<select class="items selectpicker" data-width="100px" data-style="btn-sm" id="item_posted_post_within" onchange="get_posted_pager('item_posted', '');">
															<option value="">All</option>
															<option value="60">60 days</option>
															<option value="30">30 days</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3">
												<div class="row">
													<div class="col-lg-5 col-md-5">
														<p>Filter by</p>
													</div>
													<div class="col-lg-7 col-md-7">
														<div class="hidden" id="item_posted_post_status_value"></div>
														<select class="items selectpicker" data-width="100px" data-style="btn-sm" id="item_posted_post_status" onchange="get_posted_pager('item_posted', '');">
															<option value="">All</option>
															<option value="1">Available</option>
															<option value="0">Unavailable</option>
															<option value="-1">Deleted</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<hr />
										<div class="row">
											<div class="col-lg-6 col-md-6">
												<p>Posted Date <br /><span id="item_posted_post_created"><?php echo date('M d, Y', strtotime($item_posted_array['post_created'])); ?></span></p>
											</div>
											<div class="col-lg-3 col-md-3">
												<p>Posted Price <br /><span id="item_posted_post_min_price">US $
														<?php echo sprintf('%01.2f', $item_posted_array['post_min_price']); ?>
													</span>
												</p>
											</div>
											<div class="col-lg-3 col-md-3 text-right">
											</div>
										</div>
										<div class="row">
											<div class="col-lg-2 col-md-2">
												<p>

														<!--<img id="item_posted_post_image_name" src="<?php // echo base_url() . 'uploads/posts' . date('/Y/m/d/H/i/s/', strtotime($item_posted_array['post_created'])) . $item_posted_array['post_image_name'];              ?>" class="img img-responsive" alt="" />-->


													<img id="item_posted_post_image_name" src="<?php echo $item_posted_array['post_image_url']; ?>" class="img img-responsive" alt="" />
												</p>
											</div>
											<div class="col-lg-6 col-md-6">
												<p><span id="item_posted_post_title"><?php echo $item_posted_array['post_title']; ?></span>
												</p>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div>Post ID - <span id="item_posted_post_id"><?php echo $item_posted_array['post_id']; ?></span></div>
											</div>
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div><a id="item_posted_post_link" target="_blank" href="<?php echo base_url(); ?>post/view/<?php echo $item_posted_array['post_slug'] . '/' . $item_posted_array['post_id']; ?>">See Posting</a></div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12">
												<nav>
													<ul class="pager">
														<li class="previous"><a href="javascript:;" id="previous_item_posted" onclick="get_posted_pager('item_posted', '>');" style="display: none;"><i class="fa fa-chevron-circle-left"></i> Previous</a></li>
														<li class="next"><a href="javascript:;" id="next_item_posted" onclick="get_posted_pager('item_posted', '<');"<?php if (!isset($next_item_posted)) { ?> style="display: none;"<?php } ?>>Next <i class="fa fa-chevron-circle-right"></i></a></li>
													</ul>
												</nav>
											</div>
										</div>
									</div>
								</div>
								<hr />
							<?php } if (count($item_rented_array) > 0) { ?>
								<div class="row" id="item_rented_div">
									<div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3" style="background-color: #fcfbfb">
										<div class="row">
											<div class="col-lg-5 col-md-5">
												<h3>Item Rented</h3>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="row">
													<div class="col-lg-6 col-md-6">
														<p>See items filter</p>
													</div>
													<div class="col-lg-6 col-md-6">
														<div class="hidden" id="item_rented_post_within_value"></div>
														<select class="items selectpicker" data-width="100px" data-style="btn-sm" id="item_rented_post_within" onchange="get_purchased_pager('item_rented', '');">
															<option value="">All</option>
															<option value="60">60 days</option>
															<option value="30">30 days</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<hr />
										<div class="row">
											<div class="col-lg-6 col-md-6">
												<p>Order Date  <br /><span id="item_rented_invoice_created"><?php echo date('M d, Y', strtotime($item_rented_array['invoice_created'])); ?></span></p>
											</div>
											<div class="col-lg-3 col-md-3">
												<p>Order Total <br /><span id="item_rented_invoice_amount">US $<?php echo sprintf('%01.2f', $item_rented_array['invoice_amount']); ?></span></p>
											</div>
											<div class="col-lg-3 col-md-3 text-right">
											</div>
										</div>
										<div class="row">
											<div class="col-lg-2 col-md-2">
												<p>
													<img id="item_rented_post_image_name" src="<?php echo $item_rented_array['post_image_url']; ?>" class="img img-responsive" alt="" />
												</p>
											</div>
											<div class="col-lg-6 col-md-6">
												<p>
													<span id="item_rented_post_title"><?php echo $item_rented_array['post_title']; ?></span>
												</p>
											</div>
											<div class="col-lg-4 col-md-4 selectpicker_last">
												<select class="selectpicker" id="item_rented_more_actions" data-width="100%" data-style="btn-sm" onchange="handle_more_actions('item_rented');">
													<option value="">More Actions</option>
													<option value="message">Contact Owner</option>
													<option value="invoice">View Order Details</option>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div>Post ID - <span id="item_rented_post_id"><?php echo $item_rented_array['post_id']; ?></span></div>
											</div>
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div><a id="item_rented_post_link" target="_blank" href="<?php echo base_url(); ?>post/view/<?php echo $item_rented_array['post_slug'] . '/' . $item_rented_array['post_id']; ?>">See Posting</a></div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12">
												<nav>
													<ul class="pager">
														<li class="previous"><a href="javascript:;" id="previous_item_rented" onclick="get_purchased_pager('item_rented', '>');" style="display: none;"><i class="fa fa-chevron-circle-left"></i> Previous</a></li>
														<li class="next"><a href="javascript:;" id="next_item_rented" onclick="get_purchased_pager('item_rented', '<');"<?php if (!isset($next_item_rented)) { ?> style="display: none;"<?php } ?>>Next <i class="fa fa-chevron-circle-right"></i></a></li>
													</ul>
												</nav>
											</div>
										</div>
									</div>
								</div>
								<hr />
							<?php } if (count($service_posted_array) > 0) { ?>
								<style type="text/css">
	<?php if ($service_posted_array['post_status'] === '-1') { ?>
										#service_posted_div .service_posted_visible_non_deleted{
											display: none;
										}
	<?php } else { ?>
										#service_posted_div .service_posted_visible_deleted{
											display: none;
										}
	<?php } ?>
								</style>
								<div class="row" id="service_posted_div">
									<div class="col-lg-3 col-md-3">
										<div class="row">
											<div class="col-lg-12 col-md-12 edit_del">
												<ul class="list-inline">
													<li class="service_posted_visible_non_deleted"><a id="service_posted_edit_link" href="<?php echo base_url(); ?>post/edit/<?php echo $service_posted_array['post_id'] . '/' . $service_posted_array['category_slug']; ?>">Edit</a></li>
													<li class="service_posted_visible_non_deleted">| &nbsp; <a href="javascript:;" onclick="update_post_status('service_posted', '-1');">Delete</a></li>
													<li class="service_posted_visible_deleted"><a href="javascript:;" onclick="update_post_status('service_posted', '1');">Repost</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class="col-lg-9 col-md-9">
										<div class="row">
											<div class="col-lg-5 col-md-5">
												<h3>Service Offered</h3>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="row">
													<div class="col-lg-6 col-md-6">
														<p>See items filter</p>
													</div>
													<div class="col-lg-6 col-md-6">
														<div class="hidden" id="service_posted_post_within_value"></div>
														<select class="items selectpicker" data-width="100px" data-style="btn-sm" id="service_posted_post_within" onchange="get_posted_pager('service_posted', '');">
															<option value="">All</option>
															<option value="60">60 days</option>
															<option value="30">30 days</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-lg-3 col-md-3">
												<div class="row">
													<div class="col-lg-5 col-md-5">
														<p>Filter by</p>
													</div>
													<div class="col-lg-7 col-md-7">
														<div class="hidden" id="service_posted_post_status_value"></div>
														<select class="items selectpicker" data-width="100px" data-style="btn-sm" id="service_posted_post_status" onchange="get_posted_pager('service_posted', '');">
															<option value="">All</option>
															<option value="1">Available</option>
															<option value="-1">Deleted</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<hr />
										<div class="row">
											<div class="col-lg-6 col-md-6">
												<p>Posted Date <br /><span id="service_posted_post_created"><?php echo date('M d, Y', strtotime($service_posted_array['post_created'])); ?></span></p>
											</div>
											<div class="col-lg-3 col-md-3">
												<p>Posted Price <br />
													<span id="service_posted_post_min_price">US $
														<?php echo sprintf('%01.2f', $service_posted_array['post_min_price']); ?>
													</span>
												</p>
											</div>
											<div class="col-lg-3 col-md-3 text-right">
											</div>
										</div>
										<div class="row">
											<div class="col-lg-2 col-md-2">
												<p>
													<img id="service_posted_post_image_name" src="<?php echo $service_posted_array['post_image_url']; ?>" class="img img-responsive" alt="" />
												</p>
											</div>
											<div class="col-lg-6 col-md-6">
												<p>
													<span id="service_posted_post_title"><?php echo $service_posted_array['post_title']; ?></span>
												</p>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div>Post ID - <span id="service_posted_post_id"><?php echo $service_posted_array['post_id']; ?></span></div>
											</div>
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div><a id="service_posted_post_link" target="_blank" href="<?php echo base_url(); ?>post/view/<?php echo $service_posted_array['post_slug'] . '/' . $service_posted_array['post_id']; ?>">See Posting</a></div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12">
												<nav>
													<ul class="pager">
														<li class="previous"><a href="javascript:;" id="previous_service_posted" onclick="get_posted_pager('service_posted', '>');" style="display: none;"><i class="fa fa-chevron-circle-left"></i> Previous</a></li>
														<li class="next"><a href="javascript:;" id="next_service_posted" onclick="get_posted_pager('service_posted', '<');"<?php if (!isset($next_service_posted)) { ?> style="display: none;"<?php } ?>>Next <i class="fa fa-chevron-circle-right"></i></a></li>
													</ul>
												</nav>
											</div>
										</div>
									</div>
								</div>
								<hr />
							<?php } if (count($service_paid_array) > 0) { ?>
								<div class="row" id="service_paid_div">
									<div class="col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3" style="background-color: #fcfbfb">
										<div class="row">
											<div class="col-lg-5 col-md-5">
												<h3>Service Paid</h3>
											</div>
											<div class="col-lg-4 col-md-4">
												<div class="row">
													<div class="col-lg-6 col-md-6">
														<p>See items filter</p>
													</div>
													<div class="col-lg-6 col-md-6">
														<select class="items selectpicker" data-width="100px" data-style="btn-sm" id="service_paid_post_within" onchange="get_purchased_pager('service_paid', '');">
															<option value="">All</option>
															<option value="60">60 days</option>
															<option value="30">30 days</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										<hr />
										<div class="row">
											<div class="col-lg-6 col-md-6">
												<p>Order Date  <br /><span id="service_paid_invoice_created"><?php echo date('M d, Y', strtotime($service_paid_array['invoice_created'])); ?></span></p>
											</div>
											<div class="col-lg-3 col-md-3">
												<p>Order Total <br /><span id="service_paid_invoice_amount">US $<?php echo sprintf('%01.2f', $service_paid_array['invoice_amount']); ?></span></p>
											</div>
											<div class="col-lg-3 col-md-3 text-right">
											</div>
										</div>
										<div class="row">
											<div class="col-lg-2 col-md-2">
												<img id="service_paid_post_image_name" src="<?php echo $service_paid_array['post_image_url']; ?>" class="img img-responsive" alt="" />
											</div>
											<div class="col-lg-6 col-md-6">
												<p><span id="service_paid_post_title"><?php echo $service_paid_array['post_title']; ?></span>
												</p>
											</div>
											<div class="col-lg-4 col-md-4 selectpicker_last">
												<select class="selectpicker" id="service_paid_more_actions" data-width="100%" data-style="btn-sm" onchange="handle_more_actions('service_paid');">
													<option value="">More Actions</option>
													<option value="message">Contact Owner</option>
													<option value="invoice">View Order Details</option>
												</select>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div>Post ID - <span id="service_paid_post_id"><?php echo $service_paid_array['post_id']; ?></span></div>
											</div>
											<div class="col-lg-4 col-md-4 col-lg-offset-2">
												<div><a id="service_paid_post_link" target="_blank" href="<?php echo base_url(); ?>post/view/<?php echo $service_paid_array['post_slug'] . '/' . $service_paid_array['post_id']; ?>">See Posting</a></div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12">
												<nav>
													<ul class="pager">
														<li class="previous"><a href="javascript:;" id="previous_service_paid" onclick="get_purchased_pager('service_paid', '>');" style="display: none;"><i class="fa fa-chevron-circle-left"></i> Previous</a></li>
														<li class="next"><a href="javascript:;" id="next_service_paid" onclick="get_purchased_pager('service_paid', '<');"<?php if (!isset($next_service_paid)) { ?> style="display: none;"<?php } ?>>Next <i class="fa fa-chevron-circle-right"></i></a></li>
													</ul>
												</nav>
											</div>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="tab-pane" id="messages">
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2><a class="active" href="javascript:;">Inbox</a></h2></div>
									</div>
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2><a href="javascript:;">Sent</a></h2></div>
									</div>
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2><a href="javascript:;">Trash</a></h2></div>
									</div>
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2><a href="javascript:;">Archive</a></h2></div>
									</div>
								</div>
								<div class="col-lg-9 col-md-9">
									<div class="row">
										<div class="col-lg-12 col-md-12"><h3>All Message</h3></div>
									</div>
									<hr />
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<div class="messages">
												<ul>
													<li><a class="active" href="javascript:;">All</a></li>
													<li><a href="javascript:;">Unread</a></li>
													<li><a href="javascript:;">Flagged</a></li>
													<li><a href="javascript:;">Filter</a></li>
												</ul>
											</div>
										</div>
									</div>
									<br />
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<div class="form-group message_list">
												<ul>
													<li>
														<input type="checkbox" />
													</li>
													<li>
														<a href="javascript:;" class="btn btn-default">Delete</a>
													</li>
													<li><a href="javascript:;" class="btn btn-default">Archive</a> </li>
													<li><a href="javascript:;" class="btn btn-default">Forward</a> </li>
													<li>
														<div class="dropdown">
															<a class="btn btn-default dropdown-toggle"  data-toggle="dropdown" aria-expanded="true">
																Move To
																<span class="caret"></span>
															</a>
															<ul class="dropdown-menu" role="menu">
																<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;">Trash</a></li>
																<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;">Archive</a></li>
																<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;">Folder</a></li>
															</ul>
														</div>
													</li>
													<li>
														<div class="dropdown">
															<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
																Marks as
																<span class="caret"></span>
															</a>
															<ul class="dropdown-menu" role="menu">
																<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;">Read</a></li>
																<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:;">Unread</a></li>
															</ul>
														</div>
													</li>
												</ul>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-1 col-md-1">
											<p>&nbsp;</p>
										</div>
										<div class="col-lg-1 col-md-1">
											<i class="glyphicon glyphicon-paperclip"></i>
										</div>
										<div class="col-lg-2 col-md-2">
											<p><span><strong>From</strong></span></p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p><span><strong>Subject</strong></span></p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p><span><strong>Date</strong></span></p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p><span><strong>Arrange By</strong></span></p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p><span><strong>Date from started</strong></span></p>
										</div>
									</div>
									<hr />	<div class="row">
										<div class="col-lg-1 col-md-1">
											<input type="checkbox" />
										</div>
										<div class="col-lg-1 col-md-1">
											----
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusm</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
									</div>
									<hr />	<div class="row">
										<div class="col-lg-1 col-md-1">
											<input type="checkbox" />
										</div>
										<div class="col-lg-1 col-md-1">
											----
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusm</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
									</div>
									<hr />	<div class="row">
										<div class="col-lg-1 col-md-1">
											<input type="checkbox" />
										</div>
										<div class="col-lg-1 col-md-1">
											----
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusm</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-lg-1 col-md-1">
											<input type="checkbox" />
										</div>
										<div class="col-lg-1 col-md-1">
											----
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusm</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>Lorem Ipusum dot</p>
										</div>
										<div class="col-lg-2 col-md-2">
											<p>05-12-2014</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="account">
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<div class="row">
										<div class="col-lg-8 col-md-8"><h2>My Blendedd</h2>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12">
											<p>
												<span>Personal Information<br />
													Address</span>
											</p>
										</div>
									</div>
								</div>
								<div class="col-lg-9 col-md-9">
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<h3>My Account</h3>
										</div>
										<div class="col-lg-3 col-md-3 col-md-offset-3">
											<p class="text-right"><a href="javascript:;">Close My Account</a></p>
										</div>
									</div>
									<hr />
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<p><span><strong>
														Payment Methods for Seller Fees<br />
														Automatic payment method- Cradit Card<br /><br />
														Cradit Card Number-xxxx xxxx xxxx 0000<br />
														Last Update Sept 14-12-2014</strong><br />
												</span></p>
											<a href="javascript:;">Change automatic payment method</a>
										</div>
										<div class="col-lg-6 col-md-6">
											<div class="panel panel-default">
												<div class="panel-body">
													<p><span>Paypal is a conveniert opton for paying your blendeed seller fees.</span></p>
													<p><img src="<?php echo base_url(); ?>assets/images/paypal.png" class="img img-responsive" alt="product" /></p>
													<p><span>Automatic Paypal Payments</span></p>
													<p>The simplest way to pay your seller fees.</p>
													<p><a class="btn btn-default blue" href="javascript:;">Sign Up Today</a></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--Send Message Modal Start-->
<div class="modal fade" id="create_message_modal" tabindex="-1" role="dialog" aria-labelledby="create_message_modal_tite" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="create_message_modal_tite">Contact Owner : Send a Message</h4>
			</div>
			<div class="modal-body">
				<textarea id="message_body" rows="5" class="form-control" placeholder="Enter Message Here..."></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="send_message_button">Send Message</button>
			</div>
		</div>
	</div>
</div>
<!--Send Message Modal End-->
<script type="text/javascript">
	$(function () {
		$('select.select2').select2();
	});

	function update_post_status(post_type, post_status) {
		var confirm_question = '';
		switch (post_status) {
			case '-1':
				confirm_question = 'Do You want to Delete this post ?<br/>Post will be removed from the search results too.';
				break;
			case '0':
				confirm_question = 'Do You want to make the post unavailable ?<br/>Post will be shown in the search results as Unavailable.';
				break;
			case '1':
				confirm_question = 'Do You want to make the post available ?<br/>Post will be shown in the search results as Available.';
				break;
		}
		bootbox.confirm(confirm_question, function (result) {
			if (result) {
				$.post(base_url + 'post/update', {post_id: $("#" + post_type + "_post_id").html(), post_status: post_status}, function (data) {
					if (data === '1') {
						document.location.href = '';
					} else {
						bootbox.alert('Failed !!!');
					}
				});
			}
		});
		return false;
	}

	function get_posted_pager(pager_section, pager_type) {
		var post_status = $("#" + pager_section + "_post_status").val();
		var post_within = $("#" + pager_section + "_post_within").val();
		$("#" + pager_section + "_div").block();
		$.post(base_url + 'post/get_posted_pager', {
			pager_section: pager_section,
			post_id: $("#" + pager_section + "_post_id").html(),
			pager_type: pager_type,
			post_status: post_status,
			post_within: post_within
		}, function (data) {
			$("#" + pager_section + "_div").unblock();
			if (data === '') {
				bootbox.alert('No More Posts !!!');
				$("#" + pager_section + "_post_status").selectpicker('val', $("#" + pager_section + "_post_status_value").html());
				$("#" + pager_section + "_post_within").selectpicker('val', $("#" + pager_section + "_post_within_value").html());
			} else {
				if (data.post_status === '-1') {
					$("." + pager_section + "_visible_deleted").show();
					$("." + pager_section + "_visible_non_deleted").hide();
				} else {
					$("." + pager_section + "_visible_deleted").hide();
					$("." + pager_section + "_visible_non_deleted").show();
					if (data.post_status === '0') {
						$("#" + pager_section + "_post_available").prop('checked', false);
						$("#" + pager_section + "_post_unavailable").prop('checked', true);
					} else if (data.post_status === '1') {
						$("#" + pager_section + "_post_available").prop('checked', true);
						$("#" + pager_section + "_post_unavailable").prop('checked', false);
					}
				}
				$("#" + pager_section + "_post_status_value").html(post_status);
				$("#" + pager_section + "_post_within_value").html(post_within);
				$("#" + pager_section + "_post_id").html(data.post_id);
				$("#" + pager_section + "_post_title").html(data.post_title);
				$("#" + pager_section + "_post_created").html(data.post_created);
				$("#" + pager_section + "_post_min_price").html('US $ ' + data.post_min_price);
				$("#" + pager_section + "_post_image_name").attr('src', data.post_image_url);

				$("#" + pager_section + "_edit_link").attr('href', base_url + 'post/edit/' + data.post_id + '/' + data.category_slug);
				$("#" + pager_section + "_post_link").attr('href', base_url + 'post/view/' + data.post_slug + '/' + data.post_id);
			}
			if (data.pager_next === 'true') {
				$("#next_" + pager_section).show();
			} else {
				$("#next_" + pager_section).hide();
			}
			if (data.pager_previous === 'true') {
				$("#previous_" + pager_section).show();
			} else {
				$("#previous_" + pager_section).hide();
			}
		});
	}
	function get_purchased_pager(pager_section, pager_type) {
		var post_within = $("#" + pager_section + "_post_within").val();
		$("#" + pager_section + "_div").block();
		$.post(base_url + 'post/get_purchased_pager', {
			pager_section: pager_section,
			post_id: $("#" + pager_section + "_post_id").html(),
			pager_type: pager_type,
			post_within: post_within
		}, function (data) {
			$("#" + pager_section + "_div").unblock();
			if (data === '') {
				bootbox.alert('No More Posts !!!');
				$("#" + pager_section + "_post_within").selectpicker('val', $("#" + pager_section + "_post_within_value").html());
			} else {
				$("#" + pager_section + "_post_within_value").html(post_within);
				$("#" + pager_section + "_post_id").html(data.post_id);
				$("#" + pager_section + "_post_title").html(data.post_title);
				$("#" + pager_section + "_invoice_created").html(data.invoice_created);
				$("#" + pager_section + "_invoice_amount").html('US $ ' + data.invoice_amount);
				$("#" + pager_section + "_post_image_name").attr('src', data.post_image_url);
				$("#" + pager_section + "_post_link").attr('href', base_url + 'post/view/' + data.post_slug + '/' + data.post_id);
			}
			if (data.pager_next === 'true') {
				$("#next_" + pager_section).show();
			} else {
				$("#next_" + pager_section).hide();
			}
			if (data.pager_previous === 'true') {
				$("#previous_" + pager_section).show();
			} else {
				$("#previous_" + pager_section).hide();
			}
		});
	}

	function handle_more_actions(pager_section) {
		switch (pager_section) {
			case 'item_rented':
				if ($("#" + pager_section + "_more_actions").val() === 'message') {
					$("#create_message_modal").modal('show');
					$("#message_body").val('');
					$("#send_message_button").attr('onclick', 'send_message(' + $("#" + pager_section + "_post_id").html() + ');');
				}
				break;
			case 'service_paid':
				break;
		}
		$("#" + pager_section + "_more_actions").selectpicker('val', "");
		return false;
	}

	function send_message(posts_id) {
		$.post(base_url + 'messages/create', {posts_id: posts_id, message_body: $("#message_body").val()}, function (data) {
			if (data === '1') {
				$("#message_body").before('<div id="message_sent_status" class="alert alert-success" role="alert">Message Sent Successfully.</div>');
			} else {
				$("#message_body").before('<div id="message_sent_status" class="alert alert-danger" role="alert">Message Not Sent.</div>');
			}
			setTimeout(function () {
				$("#message_sent_status").remove();
				if (data === '1') {
					$("#message_body").val('');
					$("#create_message_modal").modal('hide');
				}
			}, 2500);
		});
	}
</script>