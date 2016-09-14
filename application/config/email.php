<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

$config['email_smtp'] = FALSE; // Set to TRUE if emails are sent via smtp.
$config['email_from'] = 'info@blendedd.com';
$config['email_from_name'] = 'Blendedd';
$config['mailtype'] = "html";
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";
$config['wordwrap'] = FALSE;
$config['charset'] = "utf-8";

/**
 * SMTP Settings
 */
if ($config['email_smtp'] === TRUE) {
	$config['protocol'] = "smtp";
	$config['smtp_host'] = 'smtp.mandrillapp.com';
	$config['smtp_port'] = '587';
	$config['smtp_user'] = '';
	$config['smtp_pass'] = '';
}
