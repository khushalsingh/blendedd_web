<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="bar">
				<div class="btn-group btn-breadcrumb">
					<a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
					<a href="javascript:;" class="btn">Forgot Password</a>
				</div>   
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-lg-offset-4">
			<div class="well background_white">
				<h4>Forgot Password</h4>
				<?php if (isset($success)) { ?>
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<?php echo $success; ?></div>
				<?php } else if (isset($error)) { ?>
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<?php echo $error; ?></div>
				<?php } ?>
				<form id="user_recovery_form" class="" role="form" method="post" action="">
					<label for="email_address">Enter User ID OR Email</label>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" autocomplete="off" class="form-control" placeholder="Enter User ID OR Email" name="email_address" id="email_address" maxlength="32">
						</div>
					</div>
					<div class="form-group text-center">
						<?php echo $captcha_image; ?>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-bullseye"></i></span>
							<input type="text" autocomplete="off" class="form-control" placeholder="Enter Image Text" name="captcha_image" id="captcha_image" maxlength="6">
						</div>
					</div>
					<div class="text-right">
						<a class="btn btn-default pull-left" href="<?php echo base_url(); ?>login"><i class="fa fa-chevron-left"></i> Back</a>
						<button id="user_recover_button" class="btn btn-default blue" type="submit"><i class="glyphicon glyphicon-off"></i> Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>