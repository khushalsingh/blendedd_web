Hi <?php echo $user_first_name . ' ' . $user_last_name; ?>,
<p>
    Please click on the below link to verify your email address.<br/>
    <a href="<?php echo base_url(); ?>auth/verify/<?php echo $user_security_hash; ?>">Click here</a> to validate your account
</p>
<p>After your account is verified, you can do postings on Blendedd.</p>
<ul>
    <li>Please make sure your bank account and routing numbers are correct.</li>
    <li>Some bank accounts require extra zeros in front of the account number.</li>
    <li>Please check your bank statement and/or contact your bank.</li>
</ul>