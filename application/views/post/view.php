<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<?php
date_default_timezone_set($post_details_array['time_zone_set']);
$yourAddress = $post_details_array['city_name'] . ',' . $post_details_array['state_name'] . ',' . $post_details_array['country_name'] . ',' . $post_details_array['post_zipcode'];
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="bar">
                <div class="btn-group btn-breadcrumb">
                    <a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
                    <a href="javascript:;" class="btn">Post</a>
                    <a href="javascript:;" class="btn"><?php echo ($category_details_array['category_type'] === '1') ? 'Item' : 'Service'; ?></a>
                    <a href="javascript:;" class="btn"><?php echo $category_details_array['category_name']; ?></a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="well well-lg background_white">
                <?php if ($this->router->method === 'view') { ?>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <?php
                            if (isset($_SESSION['user']['user_id'])) {
                                if ($_SESSION['user']['user_id'] !== $post_details_array['users_id']) {
                                    ?>
                                    <a href="javascript:;" data-toggle="modal" data-target="#create_message_modal" class="btn btn-sm btn-default blue"><i class="fa fa-reply"></i> Reply</a>
                                    <?php
                                }
                            } else {
                                ?>
                                <a id="reply_popover" href="javascript:;" data-toggle="popover" title="Reply Options" data-content="" data-html="true" data-placement="bottom" data-trigger="click" class="btn btn-sm btn-default blue"><i class="fa fa-reply"></i> Reply</a>
                                <div class="hidden" id="reply_popover_content">
                                    <ul class="list-unstyled">
                                        <li><a target="_blank" href="https://mail.google.com/mail/?view=cm&fs=1&to=<?php echo urlencode($post_details_array['user_email']); ?>&su=<?php echo urlencode($post_details_array['post_title_shadow']); ?>&body=<?php echo current_url(); ?>"><i class="fa fa-google-plus"></i> Gmail</a></li>
                                        <li><a target="_blank" href="http://compose.mail.yahoo.com/?to=<?php echo urlencode($post_details_array['user_email']); ?>&subj=<?php echo urlencode($post_details_array['post_title_shadow']); ?>&body=<?php echo current_url(); ?>"><i class="fa fa-yahoo"></i> Yahoo Mail</a></li>
                                        <li><a target="_blank" href="https://mail.live.com/default.aspx?rru=compose&to=<?php echo urlencode($post_details_array['user_email']); ?>&subject=<?php echo urlencode($post_details_array['post_title_shadow']); ?>&body=<?php echo current_url(); ?>"><i class="fa fa-windows"></i> Hotmail / Outlook / Live Mail</a></li>
                                        <li><a target="_blank" href="http://mail.aol.com/mail/compose-message.aspx?to=<?php echo urlencode($post_details_array['user_email']); ?>&subject=<?php echo urlencode($post_details_array['post_title_shadow']); ?>&body=<?php echo current_url(); ?>"><i class="fa fa-envelope"></i> Aol Mail</a></li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] !== $post_details_array['users_id']) { ?>
                            <div class="modal fade" id="create_message_modal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body" id="create_message_body">
                                            <div class="form-group">
                                                <label for="message_body">Enter Your Message</label>
                                                <textarea class="form-control" id="message_body" name="message_body" rows="5"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" id="message_cancel" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <button type="button" id="message_send" class="btn btn-primary">Send Message</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    if (window.location.hash === '#create_message_modal') {
                                        $("#create_message_modal").modal('show');
                                    }
                                    $("#message_send").click(function () {
                                        $("#message_sent_status").remove();
                                        $.post(base_url + 'messages/create', {posts_id: '<?php echo $post_details_array['post_id']; ?>', 'message_body': $("#message_body").val()}, function (data) {
                                            if (data === '1') {
                                                $("#create_message_body").prepend('<div id="message_sent_status" class="alert alert-success">Message Delivered !!!</div>');
                                                $("#message_body").val('');
                                                setTimeout(function () {
                                                    $("#message_sent_status").remove();
                                                    $("#message_cancel").trigger('click');
                                                }, 2500);
                                            } else {
                                                $("#create_message_body").prepend('<div id="message_sent_status" class="alert alert-danger">Something Went Wrong !!! Try Again Later.</div>');
                                                setTimeout(function () {
                                                    $("#message_sent_status").remove();
                                                }, 2500);
                                            }
                                        });
                                    });
                                });
                            </script>
                        <?php } ?>
                        <div class="col-lg-4 col-md-4">
                            <?php if (isset($_SESSION['user']['user_id'])) { ?>
                                <div class="text-center" id="prohibit_post">
                                    <a href="javascript:;" onclick="prohibit_post();"><i class="fa fa-flag"></i> Prohibited</a> (click on to flag this posting)
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-lg-4 col-md-4">

                        </div>
                    </div>
                <?php } ?>
                <h4><b><?php echo $post_details_array['post_title']; ?> (<?php echo $post_details_array['city_name'] . ' , ' . $post_details_array['state_name']; ?>) $ <?php echo sprintf('%01.2f', $post_details_array['post_min_price']); ?></b>
                    <?php if (isset($post_details_array['post_status']) && $this->router->method === 'view' && $post_details_array['category_type'] === '1') { ?>
                        <small>
                            <?php
                            if ($post_details_array['post_status'] === '1') {
                                echo '<span class="pull-right label label-success"><i class="fa fa-check"></i> Available Now</span>';
                            } else {
                                echo '<span class="pull-right label label-danger"><i class="fa fa-exclamation"></i> Being Rented Now</span>';
                            }
                            ?>
                        </small>
                    <?php } ?>
                </h4>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="col-lg-5 col-md-5">
                                (<?php echo ($this->router->method === 'view') ? $post_details_array['user_login'] : $_SESSION['user']['user_login']; ?>) <b><?php echo $user_email; ?></b> (<?php
                    echo $post_details_array['user_feedbacks'];
                    if ($post_details_array['user_rating'] > 0) {
                        ?>
                                    <img style="border: 0;box-shadow: none" width="16" src="<?php echo base_url(); ?>assets/images/rating/<?php echo $post_details_array['user_rating']; ?>.png"/>
                                <?php } ?>) <?php echo $post_details_array['user_feedback_percentage']; ?>% positive feedback
                            </div>
                            <div class="col-lg-2 col-md-2 text-center">
                                <div class="btn-group" role="group">
                                    <?php if (isset($previous_post_details_array['post_id'])) { ?>
                                    <a title="Previous" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>post/view/<?php echo $previous_post_details_array['post_slug'] . '/' . $previous_post_details_array['post_id']; ?>"><i class="fa fa-chevron-left"></i> Prev</a>
                                    <?php } else { ?>
                                        <a title="Previous" class="btn btn-sm btn-default disabled" href="javascript:;"><i class="fa fa-chevron-left"></i> Prev</a>
                                    <?php } ?>
                                        <a title="Up" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>search/results/<?php echo $post_details_array['categories_id']; ?>/-/-/0/0/"><i class="fa fa-chevron-up"></i></a>
                                    <?php if (isset($next_post_details_array['post_id'])) { ?>
                                        <a title="Next" class="btn btn-sm btn-default" href="<?php echo base_url(); ?>post/view/<?php echo $next_post_details_array['post_slug'] . '/' . $next_post_details_array['post_id']; ?>">Next <i class="fa fa-chevron-right"></i></a>
                                    <?php } else { ?>
                                        <a title="Next" class="btn btn-sm btn-default disabled" href="javascript:;">Next <i class="fa fa-chevron-right"></i></a>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($this->router->method === 'preview') { ?>
                                <div class="col-lg-5 col-md-5">
                                    <button class="btn btn-lg btn-success pull-right" onclick="publish_post();">Publish Posting</button>
                                </div>
                                <script type="text/javascript">
                                    function publish_post() {
                                        window.close();
                                        window.opener.$("#pager_publish").trigger('click');
                                    }
                                </script>
                            <?php } ?>
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <?php if (count($post_details_array['post_images_array']) > 0) { ?>
                                    <div id="post_image_slider" class="carousel slide" data-ride="carousel" data-interval="false">
                                        <div class="carousel-inner" role="listbox">
                                            <?php for ($i = 0; $i < count($post_details_array['post_images_array']); $i++) { ?>
                                                <div class="item <?php
                                        if ($i == 0) {
                                            echo 'active';
                                        }
                                                ?>">
                                                         <?php if (isset($post_details_array['post_id']) || ( isset($post_details_array['post_images_array'][$i]['post_image_type']) && $post_details_array['post_images_array'][$i]['post_image_type'] == 'OLD')) { ?>
                                                    <img src="<?php echo base_url(); ?>uploads/posts<?php echo date('/Y/m/d/H/i/s/', strtotime($post_details_array['post_created'])) . $post_details_array['post_images_array'][$i]['post_image_name']; ?>" alt="<?php echo $post_details_array['post_title']; ?> Image <?php echo $i; ?>">
                                                    <?php } else { ?>
                                                        <img src="<?php echo base_url(); ?>uploads/<?php echo $post_details_array['post_images_array'][$i]['post_image_name']; ?>" alt="<?php echo $post_details_array['post_title']; ?> Image <?php echo $i; ?>">
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if (count($post_details_array['post_images_array']) != 1) { ?>
                                            <a class="left carousel-control" href="#post_image_slider" role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#post_image_slider" role="button" data-slide="next">
                                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                <img class="img img-responsive" src="http://placehold.it/800x600&text=No+Image+Found" alt="No Image Found">
                                <?php } ?>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <?php if ($post_details_array['post_show_on_map'] === '1') { ?>
                                    <iframe id="google_map_iframe" width="100%" height="100%" class="embed-responsive-item" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=<?php echo $yourAddress; ?>&output=embed"></iframe>
                                <?php } else { ?>
                                    <img class="img img-responsive" src="http://placehold.it/800x600&text=No+Map+Found" alt="No Map Found">
                                <?php } ?>
                            </div>
                        </div><hr />
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-md-6" id="post_description_div">
                                        <div class="form-group">
                                            <div style="word-wrap: break-word;"><?php echo nl2br($post_details_array['post_description']); ?></div>
                                        </div>
                                        <hr/>
                                        <ul class="list-inline">
                                            <li>POST ID - <?php echo ($this->router->method === 'view') ? $post_details_array['post_id'] : 'NA'; ?></li>
                                            <?php if ($this->router->method === 'view') { ?>
                                                <div class="modal fade" id="email_to_friend" tabindex="-1" role="dialog" aria-labelledby="email_to_friend_title" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">															<form method="post" action="javascript:;" id="email_to_friend_form">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                    <h3 class="modal-title" id="email_to_friend_title">Email to Friend</h3>
                                                                </div>
                                                                <div class="modal-body" id="email_to_friend_modal_body">
                                                                    <div class="row">
                                                                        <div class="col-md-8 col-md-offset-2">
                                                                            <?php if (!isset($_SESSION['user']['user_id'])) { ?>
                                                                                <div class="form-group">
                                                                                    <label for="sender_email_address">Your Email Address</label>
                                                                                    <input type="email" class="form-control" id="sender_email_address" name="sender_email_address" placeholder="Enter Your Email Address">
                                                                                </div>
                                                                            <?php } ?>
                                                                            <div class="form-group">
                                                                                <label for="destination_email_address">Destination Email Address</label>
                                                                                <input type="email" class="form-control" id="destination_email_address" name="destination_email_address" placeholder="Enter Destination Email Address">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary" id="email_to_friend_button" data-loading-text="Sending...">Send Email</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <li class="pull-right"><a title="Email to Friend" href="#email_to_friend" data-toggle="modal"><i class="fa fa-send"></i> Email to Friend</a></li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6" id="post_meta_div">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="post_street">Street</label>
                                                    <div><?php echo $post_details_array['post_street']; ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group">
                                                    <label for="post_cross_street">Cross Street</label>
                                                    <div><?php echo $post_details_array['post_cross_street']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3 col-sm-3">
                                                <label for="countries_id">Country </label>
                                                <div><?php echo $post_details_array['country_name']; ?></div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="states_id">State/Province </label>
                                                    <div><?php echo $post_details_array['state_name']; ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="city_name">City </label>
                                                    <div><?php echo $post_details_array['city_name']; ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <div class="form-group">
                                                    <label for="post_zipcode">Zip Code </label>
                                                    <div><?php echo $post_details_array['post_zipcode']; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if (isset($package_deals_array) && count($package_deals_array) > 0) { ?>
                                            <hr/>
                                            <?php
                                            foreach ($package_deals_array as $package_deal) {
                                                if ($post_details_array['post_deal_price_' . $package_deal['pricing_option_type']] > 0) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="<?php echo 'post_deal_' . $package_deal['pricing_option_type']; ?>"><?php echo $package_deal['pricing_option_name']; ?></label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <div>US $<?php echo sprintf('%01.2f', $post_details_array['post_deal_price_' . $package_deal['pricing_option_type']]); ?></div>
                                                        </div>
                                                        <?php if ($post_details_array['post_status'] == '1') { ?>
                                                            <div class="col-md-6 form-group">
                                                                <?php if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] === $post_details_array['users_id']) { ?>

                                                                <?php } else { ?>
                                                                    <a class="btn btn-info confirm_checkout" href="javascript:;" data-href="<?php echo base_url(); ?>post/checkout/<?php echo $post_details_array['post_id'] . '/' . $package_deal['pricing_option_type']; ?>">Checkout (US $<?php echo sprintf('%01.2f', $post_details_array['post_deal_price_' . $package_deal['pricing_option_type']]); ?>) <i class="fa fa-chevron-right"></i></a>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                        if (isset($item_price_array) && count($item_price_array) > 0) {
                                            ?>
                                            <hr/>
                                            <?php
                                            foreach ($item_price_array as $item_price) {
                                                if ($post_details_array['post_' . $item_price['pricing_option_type'] . '_price'] > 0) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-md-3 form-group">
                                                            <label for="<?php echo 'post_' . $item_price['pricing_option_type'] . '_price'; ?>"><?php echo ucwords($item_price['pricing_option_type']); ?> Price</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <div>US $<?php echo sprintf('%01.2f', $post_details_array['post_' . $item_price['pricing_option_type'] . '_price']); ?></div>
                                                        </div>
                                                        <?php if ($post_details_array['post_status'] == '1') { ?>
                                                            <div class="col-md-6 form-group">
                                                                <?php if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] === $post_details_array['users_id']) {
                                                                    ?>

                                                                <?php } else {
                                                                    ?>
                                                                    <a class="btn btn-info confirm_checkout" href="javascript:;" data-href="<?php echo base_url(); ?>post/checkout/<?php echo $post_details_array['post_id'] . '/' . $item_price['pricing_option_type']; ?>">Checkout (US $<?php echo sprintf('%01.2f', $post_details_array['post_' . $item_price['pricing_option_type'] . '_price']); ?>) <i class="fa fa-chevron-right"></i></a>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="text-justify">
                                            Payment does not have to be done before viewing rentals.Payment can be done on your smart phone via browser after inspection. Blendedd mobile app will be available on 5/15/15. For some rentals and services offered, payments might be required first such as a restaurant special deal.
                                        </div>
                                        <?php if (count($post_details_array['post_time_availability_array']) > 0) { ?>
                                            <hr/>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-3">

                                                        </div>
                                                        <div class="col-md-6 col-sm-3 text-center"><b>Available Hours ( <?php echo $post_details_array['time_zone_slug']; ?> )</b></div>
                                                    </div>
                                                    <?php foreach ($post_details_array['post_time_availability_array'] as $post_time_availability) { ?>
                                                        <div class="row<?php
                                                if (date('N') === $post_time_availability['post_availability_day']) {
                                                    echo ' text-danger';
                                                }
                                                        ?>">
                                                            <div class="col-md-3 col-sm-3">
                                                                <div class="form-group">
                                                                    <?php echo date('l', strtotime("Sunday + " . $post_time_availability['post_availability_day'] . " Days")); ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-sm-6 text-center">
                                                                <?php
                                                                echo date('h:i A', strtotime($post_time_availability['post_availability_from']));
                                                                ?> <b>TO</b> <?php
                                                        echo date('h:i A', strtotime($post_time_availability['post_availability_to']));
                                                                ?>
                                                            </div>
                                                            <div class="col-md-3 col-sm-3">
                                                                <?php
                                                                if (date('N') === $post_time_availability['post_availability_day']) {
                                                                    if (time() < strtotime(date('Y-m-d ') . $post_time_availability['post_availability_to']) && time() > strtotime(date('Y-m-d ') . $post_time_availability['post_availability_from'])) {
                                                                        echo '<span class="label label-success">Available Now</span>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <hr/>
                                        <ul class="list-inline">
                                            <li><a href="<?php echo base_url(); ?>safety" title="Safety Tips"><i class="fa fa-life-buoy"></i> Safety Tips</a></li>
                                            <li><a href="<?php echo base_url(); ?>prohibited" title="Prohibited Items"><i class="fa fa-warning"></i> Prohibited Items</a></li>
                                            <li><a href="<?php echo base_url(); ?>scams" title="Avoiding Scams"><i class="fa fa-shield"></i> Avoiding Scams</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#google_map_iframe").attr('height', $("#post_image_slider").height());
        if (parseInt($("#post_description_div").height()) > parseInt($("#post_meta_div").height())) {
            $("#post_description_div").addClass('border-right-grey');
        } else {
            $("#post_meta_div").addClass('border-left-grey');
        }
        setInterval(function () {
            handle_image_view();
        }, 100);
        $("#reply_popover").attr('data-content', $("#reply_popover_content").html());
        $("#reply_popover").popover();
        $(".confirm_checkout").click(function () {
            var that = this;
            bootbox.confirm('Do You want to proceed with checkout ?', function (result) {
                if (result) {
                    document.location.href = $(that).attr('data-href');
                }
            });
        });
        $("#email_to_friend_form").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            focusInvalid: true,
            rules: {
                sender_email_address: {
                    required: true,
                    email: true
                },
                destination_email_address: {
                    required: true,
                    email: true
                }
            },
            messages: {
                sender_email_address: {
                    required: 'Your Email Address is required.',
                    email: 'Please Enter Valid Email Address.'
                },
                destination_email_address: {
                    required: 'Destination Email Address is required.',
                    email: 'Please Enter Valid Email Address.'
                }
            },
            invalidHandler: function (event, validator) {

            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            success: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-group').children('span.help-block').remove();
            },
            errorPlacement: function (error, element) {
                error.appendTo(element.closest('.form-group'));
            },
            submitHandler: function (form) {
                email_to_friend();
                return false;
            }
        });
    });

    function handle_image_view() {
        var post_image_slider_height = parseInt($('#post_image_slider').height());
        $('.carousel-inner .item img').each(function (i, v) {
            if ($(v).is(':visible')) {
                var post_image_slider_active_height = parseInt($(v).height());
                if (post_image_slider_active_height < post_image_slider_height) {
                    $(v).css({'margin-top': ((post_image_slider_height - post_image_slider_active_height) / 2) + 'px'});
                }
                return;
            }
        });
    }

    function prohibit_post() {
        $.post(base_url + 'post/prohibit', {'ts': new Date()}, function (data) {
            if (data === '1') {
                $("#prohibit_post").removeAttr('onclick').html('<span class="required">Flagged</span>');
            } else {
                bootbox.alert("Critical Error !!!");
            }
        });
    }

    function email_to_friend() {
        $("#email_to_friend_button").button('loading');
        $.post(base_url + 'post/email_to_friend', $("#email_to_friend_form").serialize(), function (data) {
            $("#email_to_friend_button").button('reset');
            if (data === '1') {
                $("#email_to_friend_modal_body").prepend('<div id="email_to_friend_response" class="alert alert-success">Email Sent Successfully.</div>');
                $("#email_to_friend_form")[0].reset();
                setTimeout(function () {
                    $("#email_to_friend").modal('hide');
                }, 2500);
            } else if (data === '0') {
                $("#email_to_friend_modal_body").prepend('<div id="email_to_friend_response" class="alert alert-danger">Error Sending Email !!!</div>');
            } else {
                $("#email_to_friend_modal_body").prepend('<div id="email_to_friend_response" class="alert alert-danger">' + data + '</div>');
            }
            setTimeout(function () {
                $("#email_to_friend_response").remove();
            }, 2500);
        });
    }
</script>