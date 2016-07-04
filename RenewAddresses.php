<?php

$ipV4List = file_get_contents('https://www.cloudflare.com/ips-v4');
$ipV6List = file_get_contents('https://www.cloudflare.com/ips-v6');

date_default_timezone_set('UTC');
$fileContent = '<?php

namespace Foowie\CloudflareDetection;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 * @generated ' . date('c') . '
 */
class CloudflareIpAddresses implements IpAddresses {

	public function getIpV4Addresses() {
		return [
			\'' . implode("',\n\t\t\t'", array_filter(explode("\n", $ipV4List))) . '\'
		];
	}
	
	public function getIpV6Addresses() {
		return [
			\'' . implode("',\n\t\t\t'", array_filter(explode("\n", $ipV6List))) . '\'
		];
	}
	
}';

file_put_contents(__DIR__ . '/src/Foowie/CloudflareDetection/CloudflareIpAddresses.php', $fileContent);
