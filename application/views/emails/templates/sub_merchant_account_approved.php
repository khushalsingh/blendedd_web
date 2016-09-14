Dear <?php echo $user_first_name . ' ' . $user_last_name; ?>,
<p>
	Your Bank Account is Verified.<br/>
	You Can Login and post on Blendedd.Com<br/>
	<br/>
	<?php if ($user_status !== '1') { ?>
		Please click on the below link to verify your email address.<br/>
		<a href="<?php echo base_url(); ?>auth/verify/<?php echo $user_security_hash; ?>">Click here</a> to validate your account
	<?php } ?>
</p>