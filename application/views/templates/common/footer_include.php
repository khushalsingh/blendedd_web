<div class="footer">
    <hr/>
    <div class="container">
        <div class="row">
            <div class="col-md-10 text-center">
                <ul class="list-inline">
                    <li><a href="<?php echo base_url(); ?>about-us" title="About Us">About Us</a></li>
                    <li><a href="<?php echo base_url(); ?>how-does-it-work" title="">How Does It Work</a></li>
                    <li><a href="<?php echo base_url(); ?>faq" title="Frequently Asked Questions">FAQ</a></li>
                    <li><a href="<?php echo base_url(); ?>contact-us" title="Contact Us">Contact Us</a></li>
                    <li><a href="<?php echo base_url(); ?>stories" title="Stories">Stories</a></li>
                    <li><a href="<?php echo base_url(); ?>feedback" title="Feedback">Feedback</a></li>
                    <li><a href="<?php echo base_url(); ?>privacy" title="Privacy">Privacy</a></li>
                    <li><a href="<?php echo base_url(); ?>terms" title="Terms">Terms</a></li>
                </ul>
            </div>
            <div class="col-md-2 text-center"><small>Powered by <a href="http://scrumlink.us" target="_blank" title="Scrumlink Inc.">Scrumlink Inc.</a></small></div>
        </div>
    </div>
</div>
</div>
<?php if (ENVIRONMENT === 'production') { ?>
<script type="text/javascript">
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-63909280-1', 'auto');
        ga('send', 'pageview');
    </script>
<?php } ?>