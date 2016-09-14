<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.9.4/css/jquery.dataTables.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.css" />
<script type="text/javascript" src="//cdn.datatables.net/1.9.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/jquery.dataTables.delay.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/plugins/datatables/DT_custom.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.blockUI/2.66.0-2013.10.09/jquery.blockUI.min.js"></script>
<div class="container">
    <?php $this->load->view('templates/system/user_info'); ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div id="content">
                <?php $this->load->view('templates/system/user_tabs'); ?>
                <div class="well background_white">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-lg-2 col-md-2">
                                    <p>
                                        <img src="<?php echo base_url(); ?>assets/images/blank_profile.png" class="img img-responsive" alt="product" />
                                    </p>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <h3>Feedback ratings</h3>
                                    <br/>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 text-success">
                                            <i class="fa fa-2x fa-plus-circle"></i> Positive
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <i class="fa fa-2x fa-circle"></i> Neutral
                                        </div>
                                        <div class="col-lg-4 col-md-4 text-danger">
                                            <i class="fa fa-2x fa-minus-circle"></i> Negative
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <h3>Feedback analysis</h3>
                                    <br/>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tr class="active">
                                                <th></th>
                                                <th>1 Month</th>
                                                <th>6 Months</th>
                                                <th>12 Months</th>
                                                <th>All Feedback</th>
                                            </tr>
                                            <tr>
                                                <th><div class="text-success"><i class="fa fa-2x fa-plus-circle"></i> Positive</div></th>
                                            <?php
                                            foreach ($positive_counts_array as $positive_count) {
                                                echo '<td>' . $positive_count . '</td>';
                                            }
                                            ?>
                                            </tr>
                                            <tr>
                                                <th><div><i class="fa fa-2x fa-circle"></i> Neutral</div></th>
                                            <?php
                                            foreach ($neutral_counts_array as $neutral_count) {
                                                echo '<td>' . $neutral_count . '</td>';
                                            }
                                            ?>
                                            </tr>
                                            <tr>
                                                <th><div class="text-danger"><i class="fa fa-2x fa-minus-circle"></i> Negative</div></th>
                                            <?php
                                            foreach ($negative_counts_array as $negative_count) {
                                                echo '<td>' . $negative_count . '</td>';
                                            }
                                            ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <h3>Please note</h3>
                                    <br/>
                                    <p>No one likes negative feedback. Please contact the other party to see what
                                        resolution and/or compromise can be worked out before leaving negative feedback.</p>
                                </div>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-12">
                                    <div role="tabpanel">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li role="presentation"><a href="#feedback_seller" aria-controls="feedback_seller" role="tab" data-toggle="tab" class="feedback_datatable">Feedback as a Owner / Service Provider</a></li>
                                            <li role="presentation"><a href="#feedback_buyer" aria-controls="feedback_buyer" role="tab" data-toggle="tab" class="feedback_datatable">Feedback as a Renter / Service Buyer</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane" id="feedback_seller">
                                                <br/>
                                                <table id="feedback_seller_datatable" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                Rating
                                                            </th>
                                                            <th>
                                                                Post
                                                            </th>
                                                            <th>
                                                                Received From
                                                            </th>
                                                            <th>
                                                                Description
                                                            </th>
                                                            <th>
                                                                Dated
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div role="tabpanel" class="tab-pane" id="feedback_buyer">
                                                <br/>
                                                <table id="feedback_buyer_datatable" class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                Rating
                                                            </th>
                                                            <th>
                                                                Post
                                                            </th>
                                                            <th>
                                                                Received From
                                                            </th>
                                                            <th>
                                                                Description
                                                            </th>
                                                            <th>
                                                                Dated
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if (count($awaiting_feedback_array) > 0) { ?>
                                <h4>Awaiting Feedback</h4>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <tr>
                                                    <th>Owner / Service Provider</th>
                                                    <th>Renter / Service Buyer</th>
                                                    <th>Post</th>
                                                    <th>Txn ID</th>
                                                    <th>Txn Via</th>
                                                    <th>Txn Date</th>
                                                    <th>Leave Feedback</th>
                                                </tr>
                                                <?php foreach ($awaiting_feedback_array as $awaiting_feedback) { ?>
                                                    <tr>
                                                        <td><?php echo $awaiting_feedback['invoice_by_user_login'] ?></td>
                                                        <td><?php echo $awaiting_feedback['invoice_to_user_login'] ?></td>
                                                        <td><?php echo $awaiting_feedback['post_title']; ?></td>
                                                        <?php if ($awaiting_feedback['invoice_transaction_id'] !== '') { ?>
                                                            <td>
                                                                <?php echo $awaiting_feedback['invoice_transaction_id']; ?><br/>
                                                                <b><?php echo sprintf('%01.2f', $awaiting_feedback['invoice_amount']) . ' ' . $awaiting_feedback['invoice_currency']; ?></b>
                                                            </td>
                                                            <td>Paypal</td>
                                                        <?php } else { ?>
                                                            <td>
                                                                <?php echo $awaiting_feedback['firstdata_transaction_id']; ?>
                                                                <b><?php echo sprintf('%01.2f', $awaiting_feedback['invoice_amount']) . ' ' . $awaiting_feedback['invoice_currency']; ?></b>
                                                            </td>
                                                            <td>Credit Card</td>
                                                        <?php } ?>
                                                        <td>
                                                            <?php echo date('d M Y', strtotime($awaiting_feedback['invoice_created'])); ?>
                                                        </td>
                                                        <td>
                                                            <a href="<?php echo base_url(); ?>feedbacks/leave/<?php echo $awaiting_feedback['invoice_id']; ?>" class="btn btn-sm btn-default blue"><i class="fa fa-chevron-circle-right"></i> Feedback</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $('a.feedback_datatable:first').tab('show');
    });
    $('a.feedback_datatable').on('shown.bs.tab', function (e) {
        switch (e.target.hash) {
            case '#feedback_seller':
                if (!$('#feedback_seller_datatable').hasClass('dataTable')) {
                    $('#feedback_seller_datatable').dataTable({
                        "aaSorting": [['0', 'desc']],
                        "sAjaxSource": base_url + "feedbacks/feedback_seller_datatable",
                        "aoColumnDefs": [
                            {
                                "aTargets": [0],
                                "bSearchable": false,
                                "mData": null,
                                "mRender": function (data, type, full) {
                                    switch (full[0]) {
                                        case '1':
                                            return '<div class="text-success text-center"><i class="fa fa-2x fa-plus-circle"></i></div>';
                                            case '0':
                                                return '<div class="text-center"><i class="fa fa-2x fa-circle"></i></div>';
                                                case '-1':
                                                    return '<div class="text-danger text-center"><i class="fa fa-2x fa-minus-circle"></i></div>';
                                                }
                                            }
                                        }
                                    ]
                                }).fnSetFilteringDelay(700);
                            }
                            break;
                        case '#feedback_buyer':
                            if (!$('#feedback_buyer_datatable').hasClass('dataTable')) {
                                $('#feedback_buyer_datatable').dataTable({
                                    "aaSorting": [['0', 'desc']],
                                    "sAjaxSource": base_url + "feedbacks/feedback_buyer_datatable",
                                    "aoColumnDefs": [
                                        {
                                            "aTargets": [0],
                                            "bSearchable": false,
                                            "mData": null,
                                            "mRender": function (data, type, full) {
                                                switch (full[0]) {
                                                    case '1':
                                                        return '<div class="text-success text-center"><i class="fa fa-2x fa-plus-circle"></i></div>';
                                                        case '0':
                                                            return '<div class="text-center"><i class="fa fa-2x fa-circle"></i></div>';
                                                            case '-1':
                                                                return '<div class="text-danger text-center"><i class="fa fa-2x fa-minus-circle"></i></div>';
                                                            }
                                                        }
                                                    }
                                                ]
                                            }).fnSetFilteringDelay(700);
                                        }
                                        break;
                                    }
                                });
</script>