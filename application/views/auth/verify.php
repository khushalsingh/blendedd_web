<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="bar">
				<div class="btn-group btn-breadcrumb">
                    <a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
					<a href="javascript:;" class="btn">Verify Account</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-4 col-lg-offset-4">
			<div class="well background_white">
				<?php if (isset($success)) { ?>
					<div class="alert alert-success"><?php echo $success; ?>
						<br/> Redirecting to Login...
					</div>
				<?php } else if (isset($error)) { ?>
					<div class="alert alert-danger"><?php echo $error; ?>
						<br/> Redirecting to Login...
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
		setTimeout(function () {
			document.location.href = base_url + 'login';
		}, 2500);
	});
</script>