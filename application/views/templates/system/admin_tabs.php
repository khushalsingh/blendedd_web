<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	<li<?php
	if ($this->router->class === 'users') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>users">User Listing</a></li>
	<li<?php
	if ($this->router->class === 'post') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>post">Posting</a></li>
	<li<?php
	if ($this->router->class === 'payments') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>payments">Payments</a></li>
	<li<?php
	if ($this->router->class === 'reports') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>reports">Reports</a></li>
</ul>
<br />