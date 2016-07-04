<?php

namespace Foowie\CloudflareDetection;

use Codeception\TestCase\Test;
use Codeception\Util\Stub;
use Vectorface\Whip\Request\RequestAdapter;
use Vectorface\Whip\Request\SuperglobalRequestAdapter;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CloudflareDetectorTest extends Test {

	/** @var IpAddresses */
	protected $ipAddresses;

	/** @var CloudflareDetector */
	protected $cloudflareDetector;

	protected function _before() {
		$this->ipAddresses = Stub::makeEmpty(IpAddresses::class, ['getIpV4Addresses' => ['103.21.244.0/22', '103.22.200.0/22'], 'getIpV6Addresses' => ['2400:cb00::/32']]);
		$this->cloudflareDetector = new CloudflareDetector(
			$this->ipAddresses,
			Stub::makeEmpty(RequestAdapter::class, ['getRemoteAddr' => '103.21.244.2', 'getHeaders' => ['cf-connecting-ip' => '11.22.33.44', 'cf-ipcountry' => 'US']])
		);
	}

	public function testIsCloudflareRequest() {
		$tests = [
			[Stub::makeEmpty(RequestAdapter::class, ['getRemoteAddr' => '103.21.244.2', 'getHeaders' => ['cf-connecting-ip' => '127.0.0.1']]), true],
			[Stub::makeEmpty(RequestAdapter::class, ['getRemoteAddr' => '103.22.200.1', 'getHeaders' => ['cf-connecting-ip' => '127.0.0.1']]), true],
			[Stub::makeEmpty(RequestAdapter::class, ['getRemoteAddr' => '103.21.243.2', 'getHeaders' => ['cf-connecting-ip' => '127.0.0.1']]), false],
			[Stub::makeEmpty(RequestAdapter::class, ['getRemoteAddr' => '103.21.244.2', 'getHeaders' => ['custom-ip' => '127.0.0.1']]), false],
		];
		foreach($tests as $testData) {
			list($request, $result) = $testData;
			$this->assertEquals($result, (new CloudflareDetector($this->ipAddresses, $request))->isCloudflareRequest());
		}
	}

	public function testGetConnectingRequestIp() {
		$this->assertEquals('11.22.33.44', $this->cloudflareDetector->getConnectingRequestIp());
	}

	public function testGetConnectingCountryCode() {
		$this->assertEquals('US', $this->cloudflareDetector->getConnectingCountryCode());
	}

	public function testIsCloudflareRequestIntegrationTest() {
		$ipAddresses = new CloudflareIpAddresses();
		$ipAddressWithMask = reset($ipAddresses->getIpV4Addresses());
		$ipAddress = substr($ipAddressWithMask, 0, strpos($ipAddressWithMask, '/'));
		$server = ['HTTP_CF_CONNECTING_IP' => '22.33.44.55', 'REMOTE_ADDR' => $ipAddress, 'HTTP_CF_IPCOUNTRY' => 'US'];
		$cloudflareDetector = new CloudflareDetector($ipAddresses, new SuperglobalRequestAdapter($server));
		$this->assertTrue($cloudflareDetector->isCloudflareRequest());
		$this->assertEquals('22.33.44.55', $cloudflareDetector->getConnectingRequestIp());
		$this->assertEquals('US', $cloudflareDetector->getConnectingCountryCode());
	}

}