<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="bar">
				<div class="btn-group btn-breadcrumb">
                    <a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
                    <a href="javascript:;" class="btn">Login</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row" id="login_success_redirect" style="display: none;">
		<div class="col-md-4 col-lg-offset-4">
			<div class="well background_white">
				<div class="alert alert-success">Login Successful. Please Wait...</div>
			</div>
		</div>
	</div>
	<div class="row" id="user_login_form_div">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="well background_white well_blocks">
				<h4 id="login_heading">Blendedd Customer</h4>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <a class="btn btn-social btn-facebook" href="<?php echo base_url(); ?>auth/login/facebook" title="Login with Facebook"><i class="fa fa-facebook"></i> Login with Facebook</a>
                    </div>
                    <div class="col-md-6 text-center">
                        <a class="btn btn-social btn-google-plus" href="<?php echo base_url(); ?>auth/login/google" title="Login with Google+"><i class="fa fa-google-plus"></i> Login with Google+</a>
                    </div>
				</div>
                <h4 class="text-center">OR</h4>
				<form id="user_login_form" class="" role="form" method="post" action="">
					<label for="user_login">Enter User ID OR Email</label>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input type="text" autocomplete="off" class="form-control" placeholder="Enter User ID OR Email" name="user_login" id="user_login" maxlength="256" value="">
						</div>
					</div>
					<div class="form-group">
						<label for="user_login_password">Enter Password</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock grey-icon"></i></span>
							<input type="text" class="form-control" placeholder="Enter Password" id="user_login_password" name="user_login_password" maxlength="32">
							<span class="input-group-addon"><a href="javascript:;" id="toggle_password">Hide</a></span>
						</div>
					</div>
					<?php if (isset($captcha_image)) { ?>
						<div class="form-group text-center">
							<?php echo $captcha_image; ?>
						</div>
						<div class="form-group">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-bullseye"></i></span>
								<input type="text" autocomplete="off" class="form-control" placeholder="Enter Image Text" name="captcha_image" id="captcha_image" maxlength="6">
							</div>
						</div>
					<?php } ?>
					<div class="form-group">
						<label><input type="checkbox" id="user_remember" name="user_remember" checked="checked" value="1" /> Remember Me</label>
						<a href="javascript:;" data-toggle="tooltip" data-placement="right" title="Keeps You Logged In."><i class="fa fa-question-circle"></i></a>
					</div>
					<div class="text-right">
                        <a class="pull-left" href="<?php echo base_url(); ?>recover" title="Forgot Password">Forgotten Password</a>
						<button id="user_login_button" class="btn btn-default blue" type="submit"><i class="glyphicon glyphicon-off"></i> Login</button>
					</div>
				</form>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="well background_white well_blocks">
				<h4>New to Blendedd</h4>
				<h5>Why do I need an account?</h5>
				<p>By creating an account, you will be able to rent and/or buy services, you can also rent out items and provide services yourself, pay with your credit card, keep track of your account and transactions, and receive and give feedback on all your transactions.</p>
				<br/><br/><br/><br/>
				<br/><br/><br/><br/>
				<div class="text-right">
                    <a class="btn btn-default blue" href="<?php echo base_url(); ?>signup" title="Sign Up"><i class="glyphicon glyphicon-user"></i> REGISTER NOW</a>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/md5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/base64.min.js"></script>
<script type="text/javascript">
	$(function () {
		$("#toggle_password").click(function () {
			if ($("#user_login_password").attr('type') === 'text') {
				$("#user_login_password").attr('type', 'password');
				$(this).html('Show');
			} else {
				$("#user_login_password").attr('type', 'text');
				$(this).html('Hide');
			}
		});
		$('[data-toggle="tooltip"]').tooltip();
		$("#user_login_form").validate({
			errorElement: 'span', errorClass: 'help-block',
			rules: {
				user_login: {
					required: true,
					minlength: 5
				},
				user_login_password: {
					required: true,
					minlength: 5
				}
			},
			messages: {
				user_login: {
					required: "The User ID field is required.",
					minlength: "The User ID field must be at least {0} characters in length."
				},
				user_login_password: {
					required: "The Password field is required.",
					minlength: "The Password field must be at least {0} characters in length."
				}
			},
			invalidHandler: function (event, validator) {
				show_login_error();
			},
			highlight: function (element) {
				$(element).closest('.form-group').addClass('has-error');
			},
			unhighlight: function (element) {
				$(element).closest('.form-group').removeClass('has-error');
			},
			success: function (element) {
				$(element).closest('.form-group').removeClass('has-error');
				$(element).closest('.form-group').children('span.help-block').remove();
			},
			errorPlacement: function (error, element) {
				error.appendTo(element.closest('.form-group'));
			},
			submitHandler: function (form) {
				$(".alert-danger").remove();
				$("#user_login_button").button('loading');
				$.post('', {'user_login': btoa(btoa($.trim($("#user_login").val()))), 'user_login_password': btoa(btoa(md5(md5($.trim($("#user_login_password").val()).toLowerCase())))), 'user_remember': $("#user_remember:checked").val(), 'captcha_image': $("#captcha_image").val()}, function (data) {
					if (data === '1') {
						$("#user_login_form_div").hide();
						$("#login_success_redirect").fadeIn('fast');
						document.location.href = base_url + 'dashboard';
					} else if (/^([a-z]([a-z]|\d|\+|-|\.)*):(\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?((\[(|(v[\da-f]{1,}\.(([a-z]|\d|-|\.|_|~)|[!\$&'\(\)\*\+,;=]|:)+))\])|((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=])*)(:\d*)?)(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*|(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)|((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)){0})(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(data)) {
						$("#user_login_form_div").hide();
						$("#login_success_redirect").fadeIn('fast');
						document.location.href = data;
					} else if (data === '-1') {
						document.location.href = '';
					} else {
						show_login_error();
					}
					$("#user_login_button").button('reset');
				});
			}
		});
	});

	function show_login_error() {
		$("h4#login_heading").after('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>You have entered an invalid username/email or password. Please check your Caps Lock key or Please make sure you click on the link in your email to activate your account.</div>');
	}
</script>