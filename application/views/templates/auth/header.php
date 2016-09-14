<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title><?php echo isset($title) ? $title : (($this->router->method === 'index') ? '' : ' ' . ucwords(str_replace('_', ' ', $this->router->method))); ?> | Blendedd</title>
        <meta name="keywords" content="<?php echo isset($keywords) ? $keywords : ''; ?>" />
        <meta name="description" content="<?php echo isset($description) ? $description : ''; ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-social/4.2.1/bootstrap-social.min.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2-bootstrap.min.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/css/bootstrap-datepicker3.standalone.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/blendedd.css" />
        <?php if (is_file(FCPATH . 'assets/css/blendedd/' . $this->router->class . '/' . $this->router->method . '.css')) { ?>
            <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/blendedd/' . $this->router->class . '/' . $this->router->method . '.css'; ?>" />
        <?php } ?>
        <script type="text/javascript" src="//code.jquery.com/jquery.min.js"></script>
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.0/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.3.1/jquery.maskedinput.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
        <!--[if lt IE 9]>
            <script src="http://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="http://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">var base_url = '<?php echo base_url(); ?>';</script>
        <?php $this->load->view('templates/common/header_include'); ?>
    </head>
    <body>
        <div class="container-fluid">
            <div class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3">
                            <a href="<?php echo base_url(); ?>" title="Blendedd">
                                <img class="img img-responsive logo_brand" src="<?php echo base_url(); ?>assets/images/logo.png" alt="Blendedd Logo" title="Blendedd Logo" />
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <form class="home_search" role="search" method="post" action="<?php echo base_url(); ?>search/results">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search Rental Items and Services with City or Zip Code" name="search_term" value="<?php
        if (isset($search_term) && $search_term !== '-') {
            echo urldecode($search_term);
        }
        ?>" maxlength="64">
                                    <div class="input-group-btn">
                                        <button class="btn btn-default blue" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                    </div>
                                </div>
                                <p><a class="pull-left" href="<?php echo base_url(); ?>categories" title="Categories">Categories</a><a class="pull-right" href="<?php echo base_url(); ?>search/advanced" title="Advanced Search">Advanced Search</a></p>
                            </form>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 text-right">
                            <a href="<?php echo base_url(); ?>login" class="btn btn-default blue" title="Login"><i class="glyphicon glyphicon-off"></i> Login</a>
                            <a href="<?php echo base_url(); ?>signup" class="btn btn-default blue" title="Sign Up"><i class="glyphicon glyphicon-user"></i> Sign Up</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>