<div class="container">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="bar">
				<div class="btn-group btn-breadcrumb">
                    <a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
                    <a href="javascript:;" class="btn" title="Categories">Categories</a>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="well well-lg background_white">
				<h4>Categories</h4>
				<div class="row">
					<?php foreach ($categories as $category) { ?>
						<div class="col-md-3 category_links">
                            <a title="<?php echo $category['category_name']; ?>" class="grey_link" href="<?php echo base_url(); ?>search/results/<?php echo $category['category_id']; ?>/-/-/0/0/"><i class="fa fa-chevron-right"></i> <?php echo $category['category_name']; ?></a>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
