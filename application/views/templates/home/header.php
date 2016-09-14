<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <title>Blendedd : Sign Up to rent items and provide services</title>
        <meta name="keywords" content="Rental items, services, rent out household items, bikes, cars, toys, tents" />
        <meta name="description" content="Search for rental items and services needed in your area. Great way to rent out items not being used all the time." />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" />
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-social/4.2.1/bootstrap-social.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/blendedd.css" />
        <?php if (is_file(FCPATH . 'assets/css/' . $this->router->class . '/' . $this->router->method . '.css')) { ?>
            <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/' . $this->router->class . '/' . $this->router->method . '.css'; ?>" />
        <?php } ?>
        <script src="//code.jquery.com/jquery.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
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
                    <?php if (isset($_SESSION['user']['group_slug'])) { ?>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-8 col-md-offset-8 col-md-offset-8 text-right">
                                <a href="<?php echo base_url(); ?>dashboard" class="btn btn-default blue"><i class="fa fa-dashboard"></i> My Blendedd</a>
                                <a href="<?php echo base_url(); ?>post/category" class="btn btn-default blue"><i class="fa fa-upload"></i> Post</a>
                                <a href="<?php echo base_url(); ?>auth/logout" class="btn btn-default blue"><i class="fa fa-lock"></i> Logout</a>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    <?php } else { ?><div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="pull-right">
                                    <a class="" href="<?php echo base_url(); ?>signup" title="Sign Up">Sign up for free to Rent Items or Provide Services</a> &nbsp;
                                    <a class="btn btn-default blue" href="<?php echo base_url(); ?>login" title="Login"><i class="glyphicon glyphicon-off"></i> Login</a>
                                    <a class="btn btn-default blue" href="<?php echo base_url(); ?>signup" title="Sign Up"><i class="glyphicon glyphicon-user"></i> Sign Up</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>