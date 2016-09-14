<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class FirstData {

	public $host = '';
	public $protocol = '';
	public $uri = '';
	public $hmackey = '';
	public $keyid = '';
	public $gatewayid = '';
	public $password = '';

	function __construct($params = array()) {
		if (count($params) > 0) {
			$this->initialize($params);
		}
		log_message('debug', "First Data Payment Gateway Class Initialized");
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

	function cc_purchase($customer_ref, $invoice_id, $amount, $cardholder_name, $cc_number, $cc_expiry) {
		$location = $this->protocol . $this->host . $this->uri;
		$request = array(
			'transaction_type' => '00',
			'amount' => $amount,
			'cc_expiry' => $cc_expiry,
			'cc_number' => $cc_number,
			'cardholder_name' => $cardholder_name,
			'reference_no' => $invoice_id,
			'customer_ref' => $customer_ref,
			'gateway_id' => $this->gatewayid,
			'password' => $this->password
		);
		$content = json_encode($request);
		$gge4Date = gmdate("Y-m-d") . 'T' . gmdate("H:i:s") . 'Z';
		$contentType = "application/json";
		$contentDigest = sha1($content);
		$method = "POST";
		$hashstr = "$method\n$contentType\n$contentDigest\n$gge4Date\n$this->uri";
		$authstr = 'GGE4_API ' . $this->keyid . ':' . base64_encode(hash_hmac("sha1", $hashstr, $this->hmackey, true));
		$headers = array(
			"Content-Type: $contentType",
			"X-GGe4-Content-SHA1: $contentDigest",
			"X-GGe4-Date: $gge4Date",
			"Authorization: $authstr",
			"Accept: $contentType"
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_URL, $location);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		$output = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = $this->parse_header(substr($output, 0, $header_size));
		$body = substr($output, $header_size);
		curl_close($ch);
		if (isset($header['authorization'])) {
			return json_decode($body);
		} else {
			return $body;
		}
	}

	private function parse_header($rawHeader) {
		$header = array();
		$lines = preg_split('/\r\n|\r|\n/', $rawHeader);
		foreach ($lines as $key => $line) {
			$keyval = explode(': ', $line, 2);
			if (isset($keyval[0]) && isset($keyval[1])) {
				$header[strtolower($keyval[0])] = $keyval[1];
			}
		}
		return $header;
	}

}
