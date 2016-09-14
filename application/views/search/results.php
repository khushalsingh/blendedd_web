<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="bar">
                <div class="btn-group btn-breadcrumb">
                    <a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
                    <?php if (isset($category_details_array)) { ?>
                    <a href="<?php echo base_url(); ?>categories" class="btn" title="Categories">Categories</a>
                        <a href="javascript:;" class="btn"><?php echo $category_details_array['category_name']; ?></a>
                    <?php } else { ?>
                        <a href="javascript:;" class="btn">Search Results</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="well background_white well_blocks">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="pull-right">
                            <?php
                            $sort_order_array = array(
                                '-' => 'Sort by default order',
                                'price_asc' => 'Sort by price low to high',
                                'price_desc' => 'Sort by price high to low'
                            );
                            ?>
                            <select class="selectpicker" data-width="100%" data-style="btn-sm" id="post_sort_by">
                                <?php
                                $uri_segments_array = $this->uri->segments;
                                foreach ($sort_order_array as $key => $sort_order) {
                                    $uri_segments_array[5] = $key;
                                    ?>
                                    <option value="<?php echo base_url() . implode('/', $uri_segments_array); ?>"<?php
                                if ($this->uri->segments[5] === $key) {
                                    echo ' selected';
                                }
                                    ?>><?php echo $sort_order; ?></option>
                                        <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <hr />
                <?php
                if (count($posts_array) > 0) {
                    $counter = 0;
                    foreach ($posts_array as $post) {
                        if ($counter % 4 === 0) {
                            ?>
                            <div class="row">
                            <?php } ?>
                            <div class="col-lg-3 col-md-3">
                                <div class="text-center">
                                    <a href="<?php echo base_url() . 'post/view/' . $post['post_slug'] . '/' . $post['post_id']; ?>"><img src="<?php echo $post['post_image_url']; ?>" class="img img-responsive" alt="<?php echo $post['post_title'] ?>" style="min-height: 146px;" /></a>
                                </div>
                                <div class="text-center"><?php echo $post['post_title']; ?></div>
                                <div class="text-center">
                                    <strong>US $
                                        <?php echo sprintf('%01.2f', $post['post_min_price']); ?>
                                    </strong>
                                </div>
                                <div class="text-center text-primary">
                                    <small>
                                        <?php echo $post['city_name'] . ' ' . $post['state_code'] . ' ' . $post['post_zipcode']; ?>
                                    </small>
                                </div>
                            </div>
                            <?php
                            $counter++;
                            if ($counter % 4 === 0 || $counter == count($posts_array)) {
                                ?>
                            </div>
                            <br/>
                            <br/>
                            <br/>
                            <?php
                        }
                    }
                    ?>
                    <div class="row">
                        <div class="pull-right">
                            <div class="col-lg-12 col-md-12">
                                <nav>
                                    <?php echo $pagination; ?>
                                </nav>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-lg-offset-3 col-md-offset-3">
                            Your search - <b><?php echo $search_term; ?></b> - did not match any documents.<br/>
                            Suggestions:
                            <ul>
                                <li>Make sure that all words are spelled correctly.</li>
                                <li>Try different keywords.</li>
                                <li>Try more general keywords.</li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $("#post_sort_by").change(function(){
            document.location.href= $(this).val();
        });
    });
</script>