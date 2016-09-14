Dear <?php echo $buyer_full_name; ?>,
<p>
	Payment of $ <?php echo sprintf('%01.2f', $invoice_amount); ?> is successful.<br/>
	Payment to Seller : $ <?php echo sprintf('%01.2f', ($invoice_amount - $invoice_amount / 10)); ?><br/>
	Payment to Blendedd : $ <?php echo sprintf('%01.2f', ($invoice_amount / 10)); ?><br/>
	<a href="<?php echo $post_url; ?>">Click here</a> to view the post
</p>