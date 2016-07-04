<?php

namespace Foowie\CloudflareDetection;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 * @generated 2016-07-04T10:58:31+00:00
 */
class CloudflareIpAddresses implements IpAddresses {

	public function getIpV4Addresses() {
		return [
			'103.21.244.0/22',
			'103.22.200.0/22',
			'103.31.4.0/22',
			'104.16.0.0/12',
			'108.162.192.0/18',
			'131.0.72.0/22',
			'141.101.64.0/18',
			'162.158.0.0/15',
			'172.64.0.0/13',
			'173.245.48.0/20',
			'188.114.96.0/20',
			'190.93.240.0/20',
			'197.234.240.0/22',
			'198.41.128.0/17',
			'199.27.128.0/21'
		];
	}
	
	public function getIpV6Addresses() {
		return [
			'2400:cb00::/32',
			'2405:8100::/32',
			'2405:b500::/32',
			'2606:4700::/32',
			'2803:f800::/32'
		];
	}
	
}