<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div id="offset_home_top"></div>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <h1 class="text-center"><img src="<?php echo base_url(); ?>assets/images/logo.png" alt="Blendedd Logo" title="Blendedd Logo" /></h1>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <div>&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">
                    <form class="home_search" role="search" method="post" action="<?php echo base_url(); ?>search/results">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" placeholder="Search Rental Items and Services with City or Zip Code" name="search_term" value="<?php
if (isset($search_term) && $search_term !== '-') {
    echo urldecode($search_term);
}
?>" maxlength="64">
                            <div class="input-group-btn">
                                <button class="btn btn-default blue" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                <a class="btn btn-default blue" href="<?php echo base_url(); ?>categories" title="Categories">Categories</a>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                                <a href="<?php echo base_url(); ?>search/advanced" title="Advanced Search">Advanced Search</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <br/>
            <div class="row text-center">
                    <a class="btn btn-social-icon btn-facebook" href="https://www.facebook.com/pages/Blendedd/799170836836921" target="_blank" title="Blendedd Facebook"><i class="fa fa-facebook"></i></a> &nbsp;
                    <a class="btn btn-social-icon btn-twitter" href="https://twitter.com/blendedd" target="_blank"><i class="fa fa-twitter" title="Blendedd Twitter"></i></a> &nbsp;
                    <a class="btn btn-social-icon btn-google-plus" href="https://plus.google.com/+Blendedd" target="_blank"><i class="fa fa-google-plus" title="Blendedd Google+"></i></a>
            </div>
			<br/>
			<div class="text-center">
				<a href="https://itunes.apple.com/in/app/blendedd/id1063968000" target="_blank" title="Blendedd on iTunes Store"><img height="46" src="<?php echo base_url(); ?>assets/images/apple_badge.png" alt="Blendedd on iTunes Store" /></a> &nbsp;
				<a href="https://play.google.com/store/apps/details?id=com.erginus.blendedd" target="_blank" title="Blendedd on Google Play"><img height="46" src="<?php echo base_url(); ?>assets/images/android_badge.png" alt="Blendedd on Google Play" /></a>
			</div>
        </div>
    </div>
</div>