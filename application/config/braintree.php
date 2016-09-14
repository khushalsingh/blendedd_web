<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

switch (ENVIRONMENT) {
	case 'development':
		$config['environment'] = 'sandbox';
		$config['merchantId'] = 'w5yvdp3pmpn623vf';
		$config['publicKey'] = 'jvjnrwpy5dj9xt2g';
		$config['privateKey'] = 'a2ecb69464158d03c69f517ec5bed5a7';
		$config['masterMerchantAccountId'] = 'xszqk7t59yd7v7yw';
		break;
	case 'testing':
		$config['environment'] = 'sandbox';
		$config['merchantId'] = 'w5yvdp3pmpn623vf';
		$config['publicKey'] = 'jvjnrwpy5dj9xt2g';
		$config['privateKey'] = 'a2ecb69464158d03c69f517ec5bed5a7';
		$config['masterMerchantAccountId'] = 'xszqk7t59yd7v7yw';
		break;
	case 'production':
		$config['environment'] = 'production';
		$config['merchantId'] = 'h9mcj6kks9x3y4f5';
		$config['publicKey'] = '6yq26ryvp5zshxbn';
		$config['privateKey'] = 'b8cc8f527524689a45ba09b015f9d82b';
		$config['masterMerchantAccountId'] = 'BlendeddInc_marketplace';
		break;
	default:
		$config['environment'] = 'sandbox';
		$config['merchantId'] = 'w5yvdp3pmpn623vf';
		$config['publicKey'] = 'jvjnrwpy5dj9xt2g';
		$config['privateKey'] = 'a2ecb69464158d03c69f517ec5bed5a7';
		$config['masterMerchantAccountId'] = 'xszqk7t59yd7v7yw';
		break;
}