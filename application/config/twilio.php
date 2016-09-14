<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
$config['twilio_from'] = '+16508668985';
switch (ENVIRONMENT) {
	case 'development':
		$config['twilio_sid'] = 'ACaecc15056955ecab6cb0591a79288710';
		$config['twilio_token'] = '4373cd8ce07a18718d9281d2a4f81a81';
		break;
	case 'testing':
		$config['twilio_sid'] = 'ACaecc15056955ecab6cb0591a79288710';
		$config['twilio_token'] = '4373cd8ce07a18718d9281d2a4f81a81';
		break;
	case 'production':
		$config['twilio_sid'] = 'ACf05226bdf9c62c784ce27ce8475aafc8';
		$config['twilio_token'] = 'a11a911f49e11dd303e18e603709f0a8';
		break;
	default:
		$config['twilio_sid'] = 'ACaecc15056955ecab6cb0591a79288710';
		$config['twilio_token'] = '4373cd8ce07a18718d9281d2a4f81a81';
		break;
}
