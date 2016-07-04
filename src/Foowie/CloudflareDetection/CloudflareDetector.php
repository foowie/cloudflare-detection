<?php

namespace Foowie\CloudflareDetection;
use Vectorface\Whip\IpRange\IpWhitelist;
use Vectorface\Whip\Request\RequestAdapter;
use Vectorface\Whip\Request\SuperglobalRequestAdapter;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
class CloudflareDetector {

	/** @var IpWhitelist */
	protected $ipWhitelist;

	/** @var RequestAdapter */
	protected $requestAdapter;

	/** @var IpAddresses */
	protected $ipAddresses;

	public function __construct(IpAddresses $cloudflareAddresses = null, RequestAdapter $requestAdapter = null) {
		$this->ipAddresses = $cloudflareAddresses === null ? new CloudflareIpAddresses() : $cloudflareAddresses;
		$this->ipWhitelist = new IpWhitelist([
			IpWhitelist::IPV4 => $this->ipAddresses->getIpV4Addresses(),
			IpWhitelist::IPV6 => $this->ipAddresses->getIpV6Addresses()
		]);
		$this->requestAdapter = $requestAdapter === null ? new SuperglobalRequestAdapter($_SERVER) : $requestAdapter;
	}

	/**
	 * @return bool
	 */
	public function isCloudflareRequest() {
	    return $this->hasCloudflareRequestHeader() && $this->isCloudflareRequestIp();
	}

	/**
	 * @return IpAddresses
	 */
	public function getCloudflareIpAddresses() {
		return $this->ipAddresses;
	}

	/**
	 * @return string
	 */
	public function getConnectingRequestIp() {
		$headers = $this->requestAdapter->getHeaders();
		return $headers['cf-connecting-ip'];
	}

	/**
	 * Get country code in ISO 3166-1 Alpha 2 format
	 * xx means there is no country data
	 * @return string
	 */
	public function getConnectingCountryCode() {
		$headers = $this->requestAdapter->getHeaders();
		return $headers['cf-ipcountry'];
	}

	/**
	 * @return bool
	 */
	protected function isCloudflareRequestIp() {
	    return $this->ipWhitelist->isIpWhitelisted($this->requestAdapter->getRemoteAddr());
	}

	/**
	 * @return bool
	 */
	protected function hasCloudflareRequestHeader() {
		$headers = $this->requestAdapter->getHeaders();
		return isset($headers['cf-connecting-ip']);
	}

}