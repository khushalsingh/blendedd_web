New Post is Created,
<p>
	Created By : <b><?php echo $user_full_name; ?></b> (<?php echo $user_login; ?>) <br/>
	User Details : <?php echo $user_email; ?> (<?php echo $user_primary_contact; ?>)<br/><br/>
	Title : <b><?php echo $post_title; ?></b><br/><br/>

<hr/>
<?php echo $post_description; ?>
<hr/>
Link : <a href="<?php echo $post_link; ?>" target="_blank"><?php echo $post_title; ?></a>
</p>