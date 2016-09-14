<!--API_TRIM_START-->
<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3">
        <ul class="nav nav-pills nav-justified">
            <li role="presentation" <?php
if ($this->router->method === 'safety') {
    echo 'class="active"';
}
?>><a href="<?php echo base_url(); ?>safety" title="Safety Tips"><i class="fa fa-life-buoy"></i> Safety Tips</a></li>
            <li role="presentation" <?php
                if ($this->router->method === 'prohibited') {
                    echo 'class="active"';
                }
?>><a href="<?php echo base_url(); ?>prohibited" title="Prohibited Items"><i class="fa fa-warning"></i> Prohibited Items</a></li>
            <li role="presentation" <?php
                if ($this->router->method === 'scams') {
                    echo 'class="active"';
                }
?>><a href="<?php echo base_url(); ?>scams" title="Avoiding Scams"><i class="fa fa-shield"></i> Avoiding Scams</a></li>
        </ul>
    </div>
</div>
<!--API_TRIM_END-->