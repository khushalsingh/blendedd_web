<div class="row">
	<div class="col-lg-8 col-md-8">
		<h2>
			<a<?php
			if ($this->router->method === 'index' || $this->router->method === 'edit') {
				echo ' class="active"';
			}
			?> href="<?php echo base_url(); ?>account">Personal Info</a>
		</h2>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 col-md-8">
		<h2>
			<a<?php
			if ($this->router->method === 'credit_card') {
				echo ' class="active"';
			}
			?> href="<?php echo base_url(); ?>account/credit_card">Credit Card</a>
		</h2>
	</div>
</div>
<!--<div class="row">
	<div class="col-lg-8 col-md-8">
		<h2>
			<a<?php
if ($this->router->method === 'paypal') {
	echo ' class="active"';
}
?> href="<?php echo base_url(); ?>account/paypal">PayPal</a>
		</h2>
	</div>
</div>-->
<div class="row">
	<div class="col-lg-8 col-md-8">
		<h2>
			<a<?php
			if ($this->router->method === 'bank_account') {
				echo ' class="active"';
			}
			?> href="<?php echo base_url(); ?>account/bank_account">Bank Account</a>
		</h2>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 col-md-8">
		<h2>
			<a<?php
			if ($this->router->method === 'payments_made') {
				echo ' class="active"';
			}
			?> href="<?php echo base_url(); ?>account/payments_made">Payments Made</a>
		</h2>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 col-md-8">
		<h2>
			<a<?php
			if ($this->router->method === 'payments_received') {
				echo ' class="active"';
			}
			?> href="<?php echo base_url(); ?>account/payments_received">Payments Received</a>
		</h2>
	</div>
</div>
<div class="row">
	<div class="col-lg-8 col-md-8">
		<h2>
			<a<?php
			if ($this->router->method === 'change_password') {
				echo ' class="active"';
			}
			?> href="<?php echo base_url(); ?>account/change_password">Change Password</a>
		</h2>
	</div>
</div>