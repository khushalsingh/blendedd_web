Hi <?php echo $seller_full_name; ?>,
<p>
    One of your posting has been paid for.<br/>
    By: <?php echo $buyer_full_name; ?> (<?php echo $buyer_user_login; ?>)<br/>
    Payment of $ <?php echo $invoice_amount; ?>
    Payment to Seller : $ <?php echo sprintf('%01.2f', ($invoice_amount - $invoice_amount / 10)); ?><br/>
    Payment to Blendedd : $ <?php echo sprintf('%01.2f', ($invoice_amount / 10)); ?><br/>
    <a href="<?php echo $post_url; ?>">Click here</a> to view the posting
</p>