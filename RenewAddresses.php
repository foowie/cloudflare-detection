<?php

$ipV4List = array_filter(explode("\n", file_get_contents('https://www.cloudflare.com/ips-v4')));
$ipV6List = array_filter(explode("\n", file_get_contents('https://www.cloudflare.com/ips-v6')));
sort($ipV4List);
sort($ipV6List);

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
			\'' . implode("',\n\t\t\t'", $ipV4List) . '\'
		];
	}
	
	public function getIpV6Addresses() {
		return [
			\'' . implode("',\n\t\t\t'", $ipV6List) . '\'
		];
	}
	
}';

file_put_contents(__DIR__ . '/src/Foowie/CloudflareDetection/CloudflareIpAddresses.php', $fileContent);
