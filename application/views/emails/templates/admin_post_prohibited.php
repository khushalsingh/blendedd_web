<p>
	Post ID : <?php echo $post_id; ?> is Prohibited<br/>
	Post : <?php echo $post_title_shadow; ?><br/>
    Owner : <?php echo $user_full_name; ?> (<?php echo $user_login; ?>)<br/>
	Owner ID : <?php echo $user_id; ?><br/>
	From IP : <?php echo $this->input->server('REMOTE_ADDR'); ?><br/>
	User Agent : <?php echo $this->input->server('HTTP_USER_AGENT'); ?><br/>
	<a href="<?php echo $post_url; ?>">Click here</a> to view the post
</p>