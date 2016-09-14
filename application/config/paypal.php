<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
switch (ENVIRONMENT) {
	case 'development':
		$config['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$config['paypal_business_email'] = 'iirpl.innovative-facilitator@gmail.com';
		$config['paypal_api_url'] = 'https://api.sandbox.paypal.com/';
		$config['paypal_api_client_id'] = 'ASNa7YJOMwzUYZiiDB1daI2HQzMQs1jTiKwGgnZRUqZSUH2b_Z-FAGtBSVzux0iF8Yxr0xyEd8UgeE5U';
		$config['paypal_api_client_secret'] = 'EAvITi_L-eSgpJl5zK4DwrOvyGObutFUGBIiUGpj9pcFBIvh8YdfA8pDsgiXb_ZF2OZ1rH9rFaY5MOnV';
		break;
	case 'testing':
		$config['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$config['paypal_business_email'] = 'iirpl.innovative-facilitator@gmail.com';
		$config['paypal_api_url'] = 'https://api.sandbox.paypal.com/';
		$config['paypal_api_client_id'] = 'ASNa7YJOMwzUYZiiDB1daI2HQzMQs1jTiKwGgnZRUqZSUH2b_Z-FAGtBSVzux0iF8Yxr0xyEd8UgeE5U';
		$config['paypal_api_client_secret'] = 'EAvITi_L-eSgpJl5zK4DwrOvyGObutFUGBIiUGpj9pcFBIvh8YdfA8pDsgiXb_ZF2OZ1rH9rFaY5MOnV';
		break;
	case 'production':
		$config['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr';
		$config['paypal_business_email'] = 'edward@blendedd.com';
		$config['paypal_api_url'] = 'https://api.paypal.com/';
		$config['paypal_api_client_id'] = 'AY_WrLgjMCmMGxGI9eeyKnIRMWugEO_hgfSS4jBlmWHn610EU4egsjiuepuZ1fDXhczH1fVWgANa8Wgd';
		$config['paypal_api_client_secret'] = 'EBkm-U721uI-r5dRISlfrsYqhEmCe8Pq6A0dNld41m5OBF2xVmYfbwe8oVhE_Cjb5ovc6wnJadZsEQfj';
		break;
	default:
		$config['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		$config['paypal_business_email'] = 'iirpl.innovative-facilitator@gmail.com';
		$config['paypal_api_url'] = 'https://api.sandbox.paypal.com/';
		$config['paypal_api_client_id'] = 'ASNa7YJOMwzUYZiiDB1daI2HQzMQs1jTiKwGgnZRUqZSUH2b_Z-FAGtBSVzux0iF8Yxr0xyEd8UgeE5U';
		$config['paypal_api_client_secret'] = 'EAvITi_L-eSgpJl5zK4DwrOvyGObutFUGBIiUGpj9pcFBIvh8YdfA8pDsgiXb_ZF2OZ1rH9rFaY5MOnV';
		break;
}

