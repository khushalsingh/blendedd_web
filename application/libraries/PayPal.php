<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class PayPal {

	public $paypal_api_url = '';
	public $paypal_api_client_id = '';
	public $paypal_api_client_secret = '';
	public $access_token = '';
	public $token_type = '';

	function __construct($params = array()) {
		if (count($params) > 0) {
			$this->initialize($params);
		}
		$postvals = "grant_type=client_credentials";
		$uri = $this->paypal_api_url . 'v1/oauth2/token';
		$auth_response = $this->process($uri, 'POST', $postvals, TRUE);
		$this->access_token = $auth_response['body']->access_token;
		$this->token_type = $auth_response['body']->token_type;
		log_message('debug', "PayPal Class Initialized");
	}

	function initialize($params = array()) {
		if (count($params) > 0) {
			foreach ($params as $key => $val) {
				if (isset($this->$key)) {
					$this->$key = $val;
				}
			}
		}
	}

	function process($url, $method = 'GET', $postvals = null, $auth = FALSE) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if ($auth) {
			$headers = array("Accept: application/json", "Accept-Language: en_US");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $this->paypal_api_client_id . ":" . $this->paypal_api_client_secret);
		} else {
			$headers = array("Content-Type: application/json", "Authorization: {$this->token_type} {$this->access_token}");
		}
		$options = array(
			CURLOPT_SSLVERSION => 1,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_HEADER => 1,
			CURLINFO_HEADER_OUT => TRUE,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_VERBOSE => TRUE,
			CURLOPT_TIMEOUT => 100,
			CURLOPT_SSL_CIPHER_LIST => 'TLSv1',
			CURLOPT_USERAGENT => 'PayPal-PHP-SDK',
		);
		if ($method == 'POST') {
			$options[CURLOPT_POSTFIELDS] = $postvals;
			$options[CURLOPT_CUSTOMREQUEST] = $method;
		}
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		if (!$response) {
			$body = curl_error($ch);
		} else {
			$header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
			$body = json_decode(substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE)));
		}
		curl_close($ch);
		return array('header' => $header, 'body' => $body);
	}

	function payout($receiver_email, $sender_batch_id, $email_subject, $custom_note) {
		$postvals = '{
            "sender_batch_header":{
                "sender_batch_id":"' . $sender_batch_id . '",
                "email_subject":"' . $email_subject . '",
				"recipient_type":"EMAIL"
            },
            "items":[
                {
                    "recipient_type":"EMAIL",
                    "amount":{
                        "value":"0.01",
                        "currency":"USD"
                    },
                    "note":"' . $custom_note . '",
                    "sender_item_id":"' . $sender_batch_id . '",
                    "receiver":"' . $receiver_email . '"
                }
            ]
        }';
		return $this->process($this->paypal_api_url . 'v1/payments/payouts?sync_mode=true', 'POST', $postvals, FALSE);
	}

}
