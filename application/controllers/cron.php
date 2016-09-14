<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends MY_Controller {

    public $public_methods = array();

    function __construct() {
        parent::__construct();
    }

    function braintree_release_from_escrow() {
        $days_ago_timestamp = date('Y-m-d H:i:s', strtotime('-5 days'));
        $this->load->model('Invoice_model');
        $transactions_awaiting_settlement_array = $this->Invoice_model->get_transactions_awaiting_settlement($days_ago_timestamp);
        foreach ($transactions_awaiting_settlement_array as $transaction_awaiting_settlement) {
            parent::braintree_release_from_escrow($transaction_awaiting_settlement['invoice_transaction_id']);
        }
    }

    function backup() {
        $this->load->database();
        $max_file_age = 30 * 86400;
        $database_backup_path = '/home/ubuntu/backups/database/';
        $files_backup_path = '/home/ubuntu/backups/files/';
        $database_backup_array = scandir($database_backup_path);
        foreach ($database_backup_array as $database_backup_file) {
            if (!in_array($database_backup_file, array('.', '..')) && is_file($database_backup_path . $database_backup_file)) {
                if (filemtime($database_backup_path . $database_backup_file) < time() - $max_file_age) {
                    @unlink($database_backup_path . $database_backup_file);
                }
            }
        }
        $files_backup_array = scandir($files_backup_path);
        foreach ($files_backup_array as $files_backup_file) {
            if (!in_array($files_backup_file, array('.', '..')) && is_file($files_backup_path . $files_backup_file)) {
                if (filemtime($files_backup_path . $files_backup_file) < time() - $max_file_age) {
                    @unlink($files_backup_path . $files_backup_file);
                }
            }
        }
        $date_time_now = date('Y-m-d-H-i-s');
        @shell_exec("mysqldump -u" . $this->db->username . " -p" . $this->db->password . " " . $this->db->database . " | gzip > " . $database_backup_path . $date_time_now . ".sql.gz");
        @chdir($files_backup_path);
        @shell_exec("zip -r " . $date_time_now . ".zip /var/www/html/");
    }

}