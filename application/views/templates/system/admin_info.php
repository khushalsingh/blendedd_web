<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<h1>Admin Panel <span class="user_name"><a href="javascript:;"><?php echo $_SESSION['user']['user_login']; ?></a> </span></h1>
		<?php if (isset($error_message) && trim($error_message) !== '') { ?>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $error_message; ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if (isset($success_message) && trim($success_message) !== '') { ?>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $success_message; ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if (isset($warning_message) && trim($warning_message) !== '') { ?>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="alert alert-warning alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<?php echo $warning_message; ?>
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
</div>