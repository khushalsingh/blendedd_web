<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	<li<?php
	if ($this->router->class === 'dashboard') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>dashboard">Activities</a></li>
	<li<?php
	if ($this->router->class === 'messages') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>messages/inbox">Messages</a></li>
	<li<?php
	if ($this->router->class === 'account') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>account">Account</a></li>
	<li<?php
	if ($this->router->class === 'feedbacks') {
		echo ' class="active"';
	}
	?>><a href="<?php echo base_url(); ?>feedbacks">Feedback</a></li>
</ul>