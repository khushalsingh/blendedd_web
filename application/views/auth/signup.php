<div class="container">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="bar">
                <div class="btn-group btn-breadcrumb">
                    <a href="<?php echo base_url(); ?>" class="btn" title="Blendedd"><i class="glyphicon glyphicon-home"></i></a>
                    <a href="javascript:;" class="btn">Register</a>
                </div>
            </div>
        </div>
    </div>
    <div class="well well-lg background_white">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <p>Changes to the registration fields can be made when logged in. Press the My Blendedd button in top right hand corner and select appropriate tab.<br/>
                    Please select User ID and email carefully, as these may not be changed at this time. </p>
                <hr/>
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-md-push-8">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a title="Signup with Facebook" class="btn btn-social btn-facebook" href="<?php echo base_url(); ?>auth/signup/facebook"><i class="fa fa-facebook"></i> Signup with Facebook</a>
                            </div>
                            <br/>
                            <br/>
                            <div class="col-md-12 text-center">
                                <a title="Signup with Google+" class="btn btn-social btn-google-plus" href="<?php echo base_url(); ?>auth/signup/google"><i class="fa fa-google-plus"></i> Signup with Google+</a>
                            </div>
                            <hr/>
                        </div>
                        <p>
                            Hi,<br/>
                            <br/>
                            Thank you very much for your registration with Blendedd.<br/>
                            <br/>
                            <br/>
                            Blendedd uses Braintreepayments, a subsidiary of Paypal, for its payment provider.<br/>
                            <br/>
                            <a title="Braintree Payments" href="https://www.braintreepayments.com" target="_blank">www.braintreepayments.com</a><br/>
                            <br/>
                            To rent items and buy services, the only payment information needed is your credit card.<br/>
                            <br/>
                            To receive payments for rentals and services, certain information is required by Braintreepayments.<br/>
                            <br/>
                            Of special note:<br/>
                            <br/>
                            <br/>
                            Individuals:<br/>
                            <br/>
                            Social security number is optional but might be required in the future upon Braintreepayment's request.<br/>
                            <br/>
                            Businesses:<br/>
                            <br/>
                            Business legal name and tax id is required.<br/>
                            <a title="Braintree Payments Documentation" href="https://developers.braintreepayments.com/javascript+php/guides/marketplace/create" target="_blank" style="word-wrap: break-word;">https://developers.braintreepayments.com/javascript+php/guides/marketplace/create</a><br/>
                            <br/>
                            For any questions or help:<br/>
                            <a title="Contact Us" href="<?php echo base_url(); ?>contact-us" target="_blank"><?php echo base_url(); ?>contact-us</a>
                        </p>
                    </div>
                    <div class="col-md-8 col-lg-8 border-right-grey col-md-pull-4">
                        <p>Tell us about you or your business.</p>
                        <div class="row">
                            <form role="form" method="post" action="" id="user_signup_form">
                                <div class="col-md-12 col-lg-12" id="user_signup_form_div">
                                    <div class="row">
                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label><input type="radio" name="user_type" checked="checked" value="individual">Individual </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-lg-3">
                                            <div class="form-group">
                                                <div class="radio">
                                                    <label><input id="type_business" type="radio" name="user_type" value="business">Business</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 business_div" style="display: none;">
                                            <div class="form-group">
                                                <label for="business_legal_name">Business Legal Name <span class="required">*</span></label>
                                                <input type="text" class="form-control" id="business_legal_name" name="business_legal_name" placeholder="Business Legal Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_login">User ID <span class="required">*</span></label>
                                                <input type="text" class="form-control" id="user_login" name="user_login" placeholder="User id">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6 business_div" style="display: none;">
                                            <div class="form-group">
                                                <label for="business_tax_id">Business Tax ID <span class="required">*</span></label>
                                                <input type="text" class="form-control" id="business_tax_id" name="business_tax_id" placeholder="Required by payment provider">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_email">Email <span class="required">*</span></label>
                                                <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Email">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="confirm_user_email">Confirm Email <span class="required">*</span></label>
                                                <input type="email" class="form-control" id="confirm_user_email" name="confirm_user_email" placeholder="Confirm Email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_login_password">Password <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="user_login_password" name="user_login_password" placeholder="Password">
                                                    <span class="input-group-addon"><a href="javascript:;" id="toggle_password">Hide</a></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="confirm_user_login_password">Confirm Password <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="confirm_user_login_password" name="confirm_user_login_password" placeholder="Confirm Password">
                                                    <span class="input-group-addon"><a href="javascript:;" id="toggle_password_confirm">Hide</a></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="alert alert-info">
                                                Password must have at least 8 characters and contain at least two of the following: uppercase letters, lower case letters, number, and symbols.
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_primary_contact">Primary Contact Number <span class="required">*</span></label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <select name="user_country_code">
                                                            <option>+1</option>
                                                        </select>
                                                    </div>
                                                    <input type="text" class="form-control" id="user_primary_contact" name="user_primary_contact" placeholder="Primary Contact Number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_first_name">Contact First Name <span class="required">*</span></label>
                                                <input type="text" class="form-control" id="user_first_name" name="user_first_name" placeholder="Contact First Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_last_name">Contact Last Name <span class="required">*</span></label>
                                                <input type="text" class="form-control" id="user_last_name" name="user_last_name" placeholder="Contact Last Name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_dob">Date Of Birth (mm/dd/yyyy)<span class="required">*</span></label>
                                                <input type="text" class="form-control" id="user_dob" name="user_dob" placeholder="MM/DD/YYYY">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_ssn">Social Security Number (SSN) </label>
                                                <input type="text" class="form-control" id="user_ssn" name="user_ssn" placeholder="(Optional but might be required later)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_facebook_url">Facebook</label>
                                                <input type="text" class="form-control" id="user_facebook_url" name="user_facebook_url" placeholder="(Optional)">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_twitter_url">Twitter</label>
                                                <input type="text" class="form-control" id="user_twitter_url" name="user_twitter_url" placeholder="(Optional)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_linkedin_url">Linkedin</label>
                                                <input type="text" class="form-control" id="user_linkedin_url" name="user_linkedin_url" placeholder="(Optional)">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_instagram_url">Instagram</label>
                                                <input type="text" class="form-control" id="user_instagram_url" name="user_instagram_url" placeholder="(Optional)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_address_line_1">Street Address 1 <span class="required">*</span></label>
                                                <input type="text" class="form-control" id="user_address_line_1" name="user_address_line_1" placeholder="Street Address 1">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_address_line_2">Street Address 2</label>
                                                <input type="text" class="form-control" id="user_address_line_2" name="user_address_line_2" placeholder="Street Address 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="countries_id">Country <span class="required">*</span></label>
                                                <select class="form-control" id="countries_id" name="countries_id" data-placeholder="Select Country">
                                                    <option></option>
                                                    <?php foreach ($countries_array as $country) { ?>
                                                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['country_name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="states_id">State/Province <span class="required">*</span></label>
                                                <select class="form-control" id="states_id" name="states_id" data-placeholder="Select State / Province">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="cities_id">City (Please select the state first)<span class="required">*</span></label>
                                                <select class="form-control" id="cities_id" name="cities_id" data-placeholder="Select City">
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="user_zipcode">Zip Code <span class="required">*</span></label>
                                                <input type="text" class="form-control" id="user_zipcode" name="user_zipcode" maxlength="5" placeholder="Zip Code">
                                            </div>
                                        </div>
                                    </div>
                                    <!--									<div class="row">
                                                                            <div class="col-md-6 col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label for="user_security_question">Security Question 1 <span class="required">*</span></label>
                                                                                    <select class="form-control" id="user_security_question_1" name="user_security_question_1" data-placeholder="Select Question 1">
                                                                                        <option></option>
                                    <?php
                                    for ($i = 0; $i < 6; $i++) {
                                        echo "<option value=" . $security_questions_array[$i]['question_id'] . ">" . $security_questions_array[$i]['question'] . "</option>";
                                    }
                                    ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label for="user_security_question_ans">Answer 1 <span class="required">*</span></label>
                                                                                    <input type="text" class="form-control" id="user_security_answer_1" name="user_security_answer_1" placeholder="Security Answer 1">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6 col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label for="user_security_question">Security Question 2 <span class="required">*</span></label>
                                                                                    <select class="form-control" id="user_security_question_2" name="user_security_question_2" data-placeholder="Select Question 2">
                                                                                        <option></option>
                                    <?php
                                    for ($i = 6; $i < 12; $i++) {
                                        echo "<option value=" . $security_questions_array[$i]['question_id'] . ">" . $security_questions_array[$i]['question'] . "</option>";
                                    }
                                    ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label for="user_security_question_ans">Answer 2 <span class="required">*</span></label>
                                                                                    <input type="text" class="form-control" id="user_security_answer_2" name="user_security_answer_2" placeholder="Security Answer 2">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-6 col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label for="user_security_question">Security Question 3 <span class="required">*</span></label>
                                                                                    <select class="form-control" id="user_security_question_3" name="user_security_question_3" data-placeholder="Select Question 3">
                                                                                        <option></option>
                                    <?php
                                    for ($i = 12; $i < 18; $i++) {
                                        echo "<option value=" . $security_questions_array[$i]['question_id'] . ">" . $security_questions_array[$i]['question'] . "</option>";
                                    }
                                    ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6 col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label for="user_security_question_ans">Answer 3 <span class="required">*</span></label>
                                                                                    <input type="text" class="form-control" id="user_security_answer_3" name="user_security_answer_3" placeholder="Security Answer 3">
                                                                                </div>
                                                                            </div>
                                                                        </div>-->
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4">
                                            <div class="radio">
                                                <label><input type="radio" class="registration_for" name="registration_for" checked="checked" value="buy">To pay for rentals and services</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-6">
                                            <div class="radio">
                                                <label><input type="radio" class="registration_for" name="registration_for" value="sell">To receive payment for rentals and services</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-lg-2">
                                            <div class="radio">
                                                <label><input type="radio" class="registration_for" name="registration_for" value="both">Both</label>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="user_financial_info" name="user_financial_info" value="" />
                                    <div class="well background_white">
                                        <div class="row user_financial_div" id="user_buy_div">
                                            <div class="col-md-12">
                                                <div role="tabpanel">
                                                    <ul class="nav nav-pills" role="tablist">
                                                        <!--														<li role="presentation" class="active"><a href="#buy_paypal_account_info" class="registration_for_trigger" aria-controls="buy_paypal_account_info" role="tab" data-toggle="tab">PayPal Account Info</a></li>-->
                                                        <li role="presentation" class="active"><a href="#buy_credit_card_info"  class="registration_for_trigger" aria-controls="buy_credit_card_info" role="tab" data-toggle="tab">Credit Card Info</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <!--														<div role="tabpanel" class="tab-pane active" id="buy_paypal_account_info">
                                                                                                                </div>-->
                                                        <div role="tabpanel" class="tab-pane active" id="buy_credit_card_info">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row user_financial_div" id="user_sell_div" style="display: none;">
                                            <div class="col-md-12">
                                                <div role="tabpanel">
                                                    <ul class="nav nav-pills" role="tablist">
                                                        <!--														<li role="presentation" class="active"><a href="#sell_paypal_account_info" class="registration_for_trigger" aria-controls="sell_paypal_account_info" role="tab" data-toggle="tab">PayPal Account Info</a></li>-->
                                                        <li role="presentation" class="active"><a href="#sell_bank_account_info"  class="registration_for_trigger" aria-controls="sell_bank_account_info" role="tab" data-toggle="tab">Bank Account Info</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <!--														<div role="tabpanel" class="tab-pane active" id="sell_paypal_account_info">
                                                                                                                </div>-->
                                                        <div role="tabpanel" class="tab-pane active" id="sell_bank_account_info">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row user_financial_div" id="user_both_div" style="display: none;">
                                            <div class="col-md-12">
                                                <div role="tabpanel">
                                                    <ul class="nav nav-pills" role="tablist">
                                                        <!--														<li role="presentation" class="active"><a href="#both_paypal_bank_account_info" class="registration_for_trigger" aria-controls="both_paypal_bank_account_info" role="tab" data-toggle="tab">PayPal AND Bank Account Info</a></li>-->
                                                        <li role="presentation" class="active"><a href="#both_credit_card_bank_account_info" class="registration_for_trigger" aria-controls="both_credit_card_bank_account_info" role="tab" data-toggle="tab">Credit Card AND Bank Account Info</a></li>
                                                    </ul>
                                                    <div class="tab-content">
                                                        <!--														<div role="tabpanel" class="tab-pane active" id="both_paypal_bank_account_info">
                                                                                                                </div>-->
                                                        <div role="tabpanel" class="tab-pane active" id="both_credit_card_bank_account_info">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--										<div class="row registration_for_info buy_paypal_account_info sell_paypal_account_info both_paypal_bank_account_info" id="user_paypal_account_info" style="display: none;">
                                                                                    <hr/>
                                                                                    <div class="col-md-6 col-lg-6">
                                                                                        <div class="form-group">
                                                                                            <label for="user_paypal_email_address">PayPal ID <span class="required">*</span></label>
                                                                                            <input type="text" class="form-control" id="user_paypal_email_address" name="user_paypal_email_address" placeholder="Your PayPal Email Address">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>-->
                                        <div class="row registration_for_info buy_credit_card_info both_credit_card_bank_account_info" id="user_credit_card_info" style="display: none;">
                                            <hr/>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="row">
                                                    <div class="col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="user_credit_card_name">Credit Card Info <span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="user_credit_card_name" name="user_credit_card_name" placeholder="Name (as it appear on card)">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="user_credit_card_number">&nbsp; <span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="user_credit_card_number" name="user_credit_card_number" placeholder="Card Number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label for="user_credit_card_expiry_month">Expires On <span class="required">*</span></label>
                                                            <select class="form-control" id="user_credit_card_expiry_month" name="user_credit_card_expiry_month" data-placeholder="Select Month">
                                                                <option></option>
                                                                <option>01</option>
                                                                <option>02</option>
                                                                <option>03</option>
                                                                <option>04</option>
                                                                <option>05</option>
                                                                <option>06</option>
                                                                <option>07</option>
                                                                <option>08</option>
                                                                <option>09</option>
                                                                <option>10</option>
                                                                <option>11</option>
                                                                <option>12</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-lg-3">
                                                        <div class="form-group">
                                                            <label for="user_credit_card_expiry_year">&nbsp; <span class="required">*</span></label>
                                                            <select class="form-control" id="user_credit_card_expiry_year" name="user_credit_card_expiry_year" data-placeholder="Select Year">
                                                                <option></option>
                                                                <?php for ($i = 0; $i <= 7; $i++) { ?>
                                                                    <option><?php echo date('Y') + $i; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-lg-6">
                                                        <div class="form-group">
                                                            <label for="user_credit_card_cvv">&nbsp; <span class="required">*</span></label>
                                                            <input type="text" class="form-control" id="user_credit_card_cvv" name="user_credit_card_cvv" maxlength="4" placeholder="CVV">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row registration_for_info sell_bank_account_info both_paypal_bank_account_info both_credit_card_bank_account_info" id="user_bank_account_info" style="display: none;">
                                            <hr/>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="user_bank_account_number">Bank Account Number <span class="required">*</span></label>
                                                    <input type="text" class="form-control" id="user_bank_account_number" name="user_bank_account_number" placeholder="Bank Account Number">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="user_bank_name">Bank Name <span class="required">*</span></label>
                                                    <input type="text" class="form-control" id="user_bank_name" name="user_bank_name" placeholder="Bank Name">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="user_bank_route_code">Bank Route Code <span class="required">*</span></label>
                                                    <input type="text" class="form-control" id="user_bank_route_code" name="user_bank_route_code" placeholder="Bank Route Code">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <br/>
                                                    <label for="user_bank_account_online" class="checkbox-inline">
                                                        <input type="checkbox" value="1" id="user_bank_account_online" name="user_bank_account_online" checked="checked">My Bank Account Accepts Online Payments</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <label>Communication Preferences <span class="required">*</span></label>
                                            <div class="well background_white">
                                                <div class="row">
                                                    <div class="col-md-4 form-group">
                                                        <label for="user_communication_via_email" class="checkbox-inline">
                                                            <input type="checkbox" value="1" class="communication_preferences" id="user_communication_via_email" name="user_communication_via_email" checked="checked" disabled="" />
                                                            Email</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label for="user_communication_via_phone_call" class="checkbox-inline">
                                                            <input type="checkbox" value="1" class="communication_preferences" id="user_communication_via_phone_call" name="user_communication_via_phone_call" />
                                                            Phone</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <label for="user_communication_via_sms" class="checkbox-inline">
                                                            <input type="checkbox" value="1" class="communication_preferences" id="user_communication_via_sms" name="user_communication_via_sms" />
                                                            Text</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-lg-8">
                                            <div class="form-group checkbox">
                                                <label>
                                                    <input type="checkbox" value="1" id="user_agreement" name="user_agreement"> I accept the <a href="<?php echo base_url(); ?>terms" title="Terms">User Terms</a> and read the <a href="<?php echo base_url(); ?>privacy" target="_blank" title="Privacy">Privacy Policy <span class="required">*</span></a> .
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8 col-lg-8">
                                            <div class="checkbox">
                                                <label><input type="checkbox" value="1" id="user_newsletter_subscription" name="user_newsletter_subscription" checked="checked">I would like to receive email notifications of the latest news ,<br />promotion and services offered by Blendedd.</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br/>
                                                    <button type="submit" id="user_signup_button" class="btn btn-lg btn-default blue pull-right" data-loading-text="Please Wait..."><i class="fa fa-chevron-circle-right"></i> Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <br/>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $.validator.addMethod("lettersonly", function (value, element) {
        return this.optional(element) || /^[a-z\s]+$/i.test(value);
    }, "Please Use Alphabets Only.");
    $(function () {
        $("#toggle_password").click(function () {
            if ($("#user_login_password").attr('type') === 'text') {
                $("#user_login_password").attr('type', 'password');
                $(this).html('Show');
            } else {
                $("#user_login_password").attr('type', 'text');
                $(this).html('Hide');
            }
        });
        $("#toggle_password_confirm").click(function () {
            if ($("#confirm_user_login_password").attr('type') === 'text') {
                $("#confirm_user_login_password").attr('type', 'password');
                $(this).html('Show');
            } else {
                $("#confirm_user_login_password").attr('type', 'text');
                $(this).html('Hide');
            }
        });
        $("#user_primary_contact").mask("999-999-9999");
        $("#business_tax_id").mask("99-9999999");
        $("#user_ssn").mask("999-99-9999");
        $('select').select2();
        $("#user_dob").datepicker({
            clearBtn: true,
            format: 'mm/dd/yyyy',
            startDate: '-100y',
            endDate: '-15y'
        });
        $('[data-toggle="tooltip"]').tooltip();
        $('input[name=user_type]').change(function () {
            if ($(this).val() === 'business') {
                $(".business_div").fadeIn('fast');
            } else {
                $(".business_div").fadeOut('fast');
                $("#business_legal_name").val('');
                $("#business_tax_id").val('');
            }
        });
        $('input[name=registration_for]').change(function () {
            var that = this;
            $(".user_financial_div").each(function (i, v) {
                if ($(v).attr('id') !== 'user_' + $(that).val() + '_div') {
                    $(v).hide();
                } else {
                    $(v).show();
                }
            });
            handle_registration_for_rules();
        });
        $("#countries_id").change(function () {
            $("#states_id").html('<option value=""></option>').select2();
            $("#cities_id").html('<option value=""></option>').select2();
            $.getJSON(base_url + 'states/get_active_states_by_country_id/' + $(this).val(), function (data) {
                $.each(data, function (i, v) {
                    $("#states_id").append('<option value="' + v.state_id + '">' + v.state_name + '</option>');
                });
            });
        });
        $("#states_id").change(function () {
            $("#cities_id").html('<option value=""></option>').select2();
            $.getJSON(base_url + 'cities/get_active_cities_by_state_id/' + $(this).val(), function (data) {
                $.each(data, function (i, v) {
                    $("#cities_id").append('<option value="' + v.city_id + '">' + v.city_name + '</option>');
                });
            });
        });
        $("#countries_id").val('1').change();
        $("#user_signup_form").validate({
            errorElement: 'span',
            errorClass: 'help-block',
            focusInvalid: true,
            ignore: null,
            rules: {
                user_first_name: {
                    required: true,
                    lettersonly: true
                },
                user_last_name: {
                    required: true,
                    lettersonly: true
                },
                user_dob: {
                    required: true
                },
                user_login: {
                    required: true,
                    minlength: 5,
                    remote: {
                        url: base_url + "auth/validate_login",
                        type: "post"
                    }
                },
                business_legal_name: {
                    required: "#type_business:checked"
                },
                business_tax_id: {
                    required: "#type_business:checked"
                },
                user_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: base_url + "auth/validate_email",
                        type: "post"
                    }
                },
                confirm_user_email: {
                    required: true,
                    equalTo: "#user_email"
                },
                user_login_password: {
                    required: true,
                    minlength: 5
                },
                confirm_user_login_password: {
                    required: true,
                    equalTo: "#user_login_password"
                },
                user_primary_contact: {
                    required: true,
                    phoneUS: true
                },
                user_facebook_url: {
                    url: true
                },
                user_twitter_url: {
                    url: true
                },
                user_linkedin_url: {
                    url: true
                },
                user_instagram_url: {
                    url: true
                },
                user_security_question_1: {
                    required: true
                },
                user_security_answer_1: {
                    required: true
                },
                user_security_question_2: {
                    required: true
                },
                user_security_answer_2: {
                    required: true
                },
                user_security_question_3: {
                    required: true
                },
                user_security_answer_3: {
                    required: true
                },
                user_address_line_1: {
                    required: true
                },
                countries_id: {
                    required: true
                },
                states_id: {
                    required: true
                },
                cities_id: {
                    required: true
                },
                user_zipcode: {
                    required: true,
                    digits: true,
                    rangelength: [5, 5]
                },
                user_communication_via_email: {
                    require_from_group: [1, ".communication_preferences"]},
                user_communication_via_phone_call: {
                    require_from_group: [1, ".communication_preferences"]
                },
                user_communication_via_sms: {
                    require_from_group: [1, ".communication_preferences"]
                },
                user_agreement: {
                    required: true
                }
            },
            messages: {
                user_first_name: {
                    required: "First Name is required.",
                    lettersonly: "Please Use Alphabets Only."
                },
                user_last_name: {
                    required: "Last Name is required.",
                    lettersonly: "Please Use Alphabets Only."
                },
                user_dob: {
                    required: "Date Of Birth is required."
                },
                user_login: {
                    required: "User ID is required.",
                    minlength: "User ID must be at least {0} characters in length.",
                    remote: "User ID is already used."
                },
                business_legal_name: {
                    required: "Business Legal Name is required."
                },
                business_tax_id: {
                    required: "Business Tax ID is required."
                },
                user_email: {
                    required: "Email is required.",
                    email: "Email must contain a valid email id.",
                    remote: "Email is already used."
                },
                confirm_user_email: {
                    required: "Confirm Email is required.",
                    equalTo: "Confirm Email does not match the Email."
                },
                user_login_password: {
                    required: "Password is required.",
                    minlength: "Password must be at least {0} characters in length."
                },
                confirm_user_login_password: {
                    required: "Confirm Password is required.",
                    equalTo: "Confirm Password does not match the Password."
                },
                user_primary_contact: {
                    required: "Primary Contact Number is required.",
                    phoneUS: "Please Enter Valid Primary Contact."
                },
                user_facebook_url: {
                    url: "Please Enter Valid Facebook Link."
                },
                user_twitter_url: {
                    url: "Please Enter Valid Twitter Link."
                },
                user_linkedin_url: {
                    url: "Please Enter Valid Linkedin Link."
                },
                user_instagram_url: {
                    url: "Please Enter Valid Instagram Link."
                },
                user_address_line_1: {
                    required: "Street Address 1 is required."
                },
                //				user_security_question_1: {
                //					required: "Security Question 1 is required."
                //				},
                //				user_security_answer_1: {
                //					required: "Security Answer 1 is required."
                //				},
                //				user_security_question_2: {
                //					required: "Security Question 2 is required."
                //				},
                //				user_security_answer_2: {
                //					required: "Security Answer 2 is required."
                //				},
                //				user_security_question_3: {
                //					required: "Security Question 3 is required."
                //				},
                //				user_security_answer_3: {
                //					required: "Security Answer 3 is required."
                //				},
                countries_id: {
                    required: "Country is required."
                },
                states_id: {
                    required: "State/Province is required."
                },
                cities_id: {
                    required: "City is required."
                },
                user_zipcode: {
                    required: "Zip Code is required.",
                    digits: "Please enter Digits Only.",
                    rangelength: "Zip Code Should have 5 Digits."
                },
                user_communication_via_email: {
                    require_from_group: ""
                },
                user_communication_via_phone_call: {
                    require_from_group: "Please Select At Least One Mode."
                },
                user_communication_via_sms: {
                    require_from_group: ""
                },
                user_agreement: {
                    required: "Agreement is required."
                }
            },
            invalidHandler: function (event, validator) {
                show_signup_error();
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
                $("#user_signup_button").button('loading');
                $.post('', $("#user_signup_form").serialize(), function (data) {
                    if (data === '1') {
                        bootbox.alert('Thank you. Your Account has been created successfully.<br/>You must click on the the link sent to your email to activate your account before trying to log in.', function (data) {
                            document.location.href = base_url;
                        });
                    } else if (data === '0') {
                        bootbox.alert('Error While Creating an Account !!!');
                    } else {
                        bootbox.alert(data);
                    }
                    $("#user_signup_button").button('reset');
                });
            }
        });
        $('select').change(function () {
            $("#user_signup_form").validate().element($(this));
        });
        handle_registration_for_rules();
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            handle_registration_for_rules();
        });
    });

    function show_signup_error() {
        $("#user_signup_form_div").prepend('<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error While Creating an Account !!!</div>');
        setTimeout(function () {
            $('.alert-danger').fadeOut();
        }, 2000);
    }

    function handle_registration_for_rules() {
        $(".registration_for_info").hide();
        $(".registration_for_trigger").each(function (i, v) {
            if ($(v).is(':visible') && $(v).parent('li').hasClass('active')) {
                $("." + $(v).attr('aria-controls')).show();
                $("#user_financial_info").val($(v).attr('aria-controls'));
            }
        });
        //		if ($("#user_paypal_account_info").is(':visible')) {
        //			$("#user_paypal_email_address").rules('add', {
        //				required: true,
        //				email: true,
        //				remote: {
        //					url: base_url + "auth/validate_email",
        //					type: "post"
        //				},
        //				messages: {
        //					required: "PayPal ID is required.",
        //					email: "PayPal ID must contain a valid email id.",
        //					remote: "PayPal ID is already used."
        //				}
        //			});
        //		} else {
        //			$("#user_paypal_email_address").rules("remove");
        //			$("#user_paypal_email_address").val('');
        //		}
        if ($("#user_credit_card_info").is(':visible')) {
            $("#user_credit_card_name").rules('add', {
                required: true,
                lettersonly: true,
                messages: {
                    required: "Credit Card Name is required.",
                    lettersonly: "Please Use Alphabets Only."
                }
            });
            $("#user_credit_card_number").rules('add', {
                required: true,
                creditcard: true,
                messages: {
                    required: "Credit Card Number is required.",
                    creditcard: "Please Enter Valid Credit Card Number."
                }
            });
            $("#user_credit_card_expiry_month").rules('add', {
                required: true,
                messages: {
                    required: "Expiry Month is required."
                }
            });
            $("#user_credit_card_expiry_year").rules('add', {
                required: true,
                messages: {
                    required: "Expiry Year is required."
                }
            });
            $("#user_credit_card_cvv").rules('add', {
                required: true,
                digits: true,
                maxlength: 4,
                messages: {
                    required: "CVV Number is required.",
                    digits: "Please enter Digits Only.",
                    maxlength: "Please Enter upto 4 Digits Only."
                }
            });
        } else {
            $("#user_credit_card_name").rules("remove");
            $("#user_credit_card_number").rules("remove");
            $("#user_credit_card_expiry_month").rules("remove");
            $("#user_credit_card_expiry_year").rules("remove");
            $("#user_credit_card_cvv").rules("remove");
            $("#user_credit_card_name").val('');
            $("#user_credit_card_number").val('');
            $("#user_credit_card_expiry_month").val('');
            $("#user_credit_card_expiry_year").val('');
            $("#user_credit_card_cvv").val('');
        }
        if ($("#user_bank_account_info").is(':visible')) {
            $("#user_bank_account_number").rules('add', {
                required: true,
                messages: {
                    required: "Bank Account Number is required."
                }
            });
            $("#user_bank_name").rules('add', {
                required: true,
                messages: {
                    required: "Bank Name is required."
                }
            });
            $("#user_bank_route_code").rules('add', {
                required: true,
                messages: {
                    required: "Bank Route Code is required."
                }
            });
        } else {
            $("#user_bank_account_number").rules("remove");
            $("#user_bank_name").rules("remove");
            $("#user_bank_route_code").rules("remove");
            $("#user_bank_account_number").val('');
            $("#user_bank_name").val('');
            $("#user_bank_route_code").val('');
        }
        $('select').select2();
    }
</script>