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
						<div class="tab-pane active" id="account">
							<div class="row">
								<div class="col-lg-3 col-md-3">
									<?php $this->load->view('templates/system/account_left_menu'); ?>
								</div>
								<div class="col-lg-9 col-md-9">
									<div class="row">
										<div class="col-lg-6 col-md-6">
											<h3>Change Password</h3>
										</div>
										<div class="col-lg-3 col-md-3 col-md-offset-3">
											<p class="text-right"><a href="<?php echo base_url(); ?>account">Go Back</a></p>
										</div>
									</div>
									<hr/>
									<div class="row">
										<div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3">
											<form id="user_recovery_form" class="" role="form" method="post" action="">
												<label for="user_login_password">New Password</label>
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-lock"></i></span>
														<input type="text" autocomplete="off" class="form-control" placeholder="Enter New Password" name="user_login_password" id="user_login_password" maxlength="32">
													</div>
												</div>
												<label for="user_confirm_password">Confirm New Password</label>
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-lock"></i></span>
														<input type="text" autocomplete="off" class="form-control" placeholder="Re-enter Password" name="user_confirm_password" id="user_confirm_password" maxlength="32">
													</div>
												</div>
												<div class="text-right">
													<button id="user_recover_button" class="btn btn-default blue" type="submit"><i class="glyphicon glyphicon-off"></i> Submit</button>
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
	</div>
</div>
<script type="text/javascript">
	$(function () {
		$('select').select2();
	});
</script>