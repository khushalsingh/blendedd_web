<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Braintree_webhooks extends MY_Controller {

    public $webhook_notification;
    public $public_methods = array(
        'index'
    );

    function __construct() {
        parent::__construct();
        if (ENVIRONMENT === 'development') {
            // For Testing we can put the braintree log here
            $this->webhook_notification = json_decode('');
        } else {
            $this->load->config('braintree');
            include_once (FCPATH . 'application/libraries/Braintree.php');
            Braintree_Configuration::environment($this->config->item('environment'));
            Braintree_Configuration::merchantId($this->config->item('merchantId'));
            Braintree_Configuration::publicKey($this->config->item('publicKey'));
            Braintree_Configuration::privateKey($this->config->item('privateKey'));
            // Webhook Verification Code
            if (isset($_GET["bt_challenge"])) {
                echo(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
                die;
            }
            // Webhook Write Log Code
            if (isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) {
                $webhook_notification = Braintree_WebhookNotification::parse($_POST["bt_signature"], $_POST["bt_payload"]);
                file_put_contents(FCPATH . "uploads/braintree.txt", json_encode($webhook_notification) . str_repeat("\n", 2) . str_repeat('=', 120) . str_repeat("\n", 2), FILE_APPEND);
                $this->webhook_notification = json_decode(json_encode($webhook_notification));
            }
        }
    }

    function index() {
        if ($this->input->post()) {
            if (isset($this->webhook_notification->_attributes->kind)) {
                switch ($this->webhook_notification->_attributes->kind) {
                    case 'sub_merchant_account_approved':
                        if (
                                isset($this->webhook_notification->_attributes->merchantAccount->_attributes->status) &&
                                $this->webhook_notification->_attributes->merchantAccount->_attributes->status === 'active'
                        ) {
                            $this->load->model('Auth_model');
                            $registration_details_array = $this->Auth_model->get_registration_by_user_braintree_merchant_id($this->webhook_notification->_attributes->merchantAccount->_attributes->id);
                            if (count($registration_details_array) > 0) {
                                $registration_update_array = array(
                                    'user_bank_verified' => '1',
                                    'user_bank_account_verified_on' => date('Y-m-d H:i:s'),
                                    'user_modified' => date('Y-m-d H:i:s')
                                );
                                if ($this->Auth_model->update_registration_by_id($registration_details_array['user_id'], $registration_update_array)) {
                                    $email_id = parent::add_email_to_queue('', '', $registration_details_array['user_email'], '0', 'Bank Account Verified', $this->render_view($registration_details_array, 'emails', 'emails/templates/sub_merchant_account_approved', TRUE));
                                    if ($email_id > 0) {
                                        @file_get_contents(base_url() . 'emails/cron/' . $email_id);
                                    }
                                }
                            } else {
                                $user_details_array = $this->Auth_model->get_user_by_user_braintree_merchant_id($this->webhook_notification->_attributes->merchantAccount->_attributes->id);
                                if (count($user_details_array) > 0) {
                                    $user_update_array = array(
                                        'user_bank_verified' => '1',
                                        'user_bank_account_verified_on' => date('Y-m-d H:i:s'),
                                        'user_modified' => date('Y-m-d H:i:s')
                                    );
                                    if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
                                        $email_id = parent::add_email_to_queue('', '', $user_details_array['user_email'], $user_details_array['user_id'], 'Bank Account Verified', $this->render_view($user_details_array, 'emails', 'emails/templates/sub_merchant_account_approved', TRUE));
                                        if ($email_id > 0) {
                                            @file_get_contents(base_url() . 'emails/cron/' . $email_id);
                                        }
                                    }
                                } else {
                                    // No Merchant Found
                                }
                            }
                        }
                        break;
                    case 'sub_merchant_account_declined':
                        if (
                                isset($this->webhook_notification->_attributes->merchantAccount->_attributes->status) &&
                                $this->webhook_notification->_attributes->merchantAccount->_attributes->status !== 'active'
                        ) {
                            $this->load->model('Auth_model');
                            $registration_details_array = $this->Auth_model->get_registration_by_user_braintree_merchant_id($this->webhook_notification->_attributes->merchantAccount->_attributes->id);
                            if (count($registration_details_array) > 0) {
                                $registration_update_array = array(
                                    'user_bank_verified' => '0',
                                    'user_bank_account_verified_on' => '0000-00-00 00:00:00',
                                    'user_modified' => date('Y-m-d H:i:s')
                                );
                                if ($this->Auth_model->update_registration_by_id($registration_details_array['user_id'], $registration_update_array)) {
                                    $email_id = parent::add_email_to_queue('', '', $registration_details_array['user_email'], '0', 'Bank Account Verification Failed', $this->render_view($registration_details_array, 'emails', 'emails/templates/sub_merchant_account_approved', TRUE));
                                    if ($email_id > 0) {
                                        @file_get_contents(base_url() . 'emails/cron/' . $email_id);
                                    }
                                }
                            } else {
                                $user_details_array = $this->Auth_model->get_user_by_user_braintree_merchant_id($this->webhook_notification->_attributes->merchantAccount->_attributes->id);
                                if (count($user_details_array) > 0) {
                                    $user_update_array = array(
                                        'user_bank_verified' => '0',
                                        'user_bank_account_verified_on' => '0000-00-00 00:00:00',
                                        'user_modified' => date('Y-m-d H:i:s')
                                    );
                                    if ($this->Auth_model->edit_user_by_user_id($user_details_array['user_id'], $user_update_array)) {
                                        $email_id = parent::add_email_to_queue('', '', $user_details_array['user_email'], $user_details_array['user_id'], 'Bank Account Verification Failed', $this->render_view($user_details_array, 'emails', 'emails/templates/sub_merchant_account_declined', TRUE));
                                        if ($email_id > 0) {
                                            @file_get_contents(base_url() . 'emails/cron/' . $email_id);
                                        }
                                    }
                                } else {
                                    // No Merchant Found
                                }
                            }
                        }
                        break;
                    case 'disbursement':
                        if (
                                isset($this->webhook_notification->_attributes->disbursement->_attributes->success) &&
                                $this->webhook_notification->_attributes->disbursement->_attributes->success && is_array($this->webhook_notification->_attributes->disbursement->_attributes->transactionIds)
                        ) {
                            $this->load->model('Invoice_model');
                            foreach ($this->webhook_notification->_attributes->disbursement->_attributes->transactionIds as $invoice_transaction_id) {
                                $this->Invoice_model->mark_invoice_as_settled($invoice_transaction_id);
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }

}
