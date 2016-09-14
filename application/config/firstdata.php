<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

switch (ENVIRONMENT) {
	case 'development':
		$config['firstdata_host'] = "api.demo.globalgatewaye4.firstdata.com";
		$config['firstdata_protocol'] = "https://";
		$config['firstdata_uri'] = "/transaction/v12";
		$config['firstdata_hmackey'] = "RRncnxcsgs0_GvM5GEMJF89zNQZSSUEY";
		$config['firstdata_keyid'] = "214979";
		$config['firstdata_gatewayid'] = "AH6913-05";
		$config['firstdata_password'] = "65j1qyj5m9anju5z467rf9yufz8gm95o";
		break;
	case 'testing':
		$config['firstdata_host'] = "api.demo.globalgatewaye4.firstdata.com";
		$config['firstdata_protocol'] = "https://";
		$config['firstdata_uri'] = "/transaction/v12";
		$config['firstdata_hmackey'] = "RRncnxcsgs0_GvM5GEMJF89zNQZSSUEY";
		$config['firstdata_keyid'] = "214979";
		$config['firstdata_gatewayid'] = "AH6913-05";
		$config['firstdata_password'] = "65j1qyj5m9anju5z467rf9yufz8gm95o";
		break;
	case 'production':
		$config['firstdata_host'] = "api.globalgatewaye4.firstdata.com";
		$config['firstdata_protocol'] = "https://";
		$config['firstdata_uri'] = "/transaction/v12";
		$config['firstdata_hmackey'] = "FcTTQC4sDd9UT6resWmYtd6bOL0fRLv6";
		$config['firstdata_keyid'] = "321587";
		$config['firstdata_gatewayid'] = "C95322-01";
		$config['firstdata_password'] = "0fux484x2jl8o3wab6l09ug4s1j1qj23";
		break;
	default:
		$config['firstdata_host'] = "api.demo.globalgatewaye4.firstdata.com";
		$config['firstdata_protocol'] = "https://";
		$config['firstdata_uri'] = "/transaction/v12";
		$config['firstdata_hmackey'] = "RRncnxcsgs0_GvM5GEMJF89zNQZSSUEY";
		$config['firstdata_keyid'] = "214979";
		$config['firstdata_gatewayid'] = "AH6913-05";
		$config['firstdata_password'] = "65j1qyj5m9anju5z467rf9yufz8gm95o";
		break;
}