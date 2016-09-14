Dear <?php echo $user_first_name . ' ' . $user_last_name; ?>,
<p>
    <b><?php echo $user_from_full_name; ?> (<?php echo $user_from_name; ?>)</b> sent you a message : <br/><br/>
<hr/>
<?php echo $message_body; ?>
<hr/>
Related Post : <a href="<?php echo $post_link; ?>" target="_blank"><?php echo $post_title; ?></a>
</p>