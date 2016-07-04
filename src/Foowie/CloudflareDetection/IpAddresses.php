<?php

namespace Foowie\CloudflareDetection;

/**
 * @author Daniel Robenek <daniel.robenek@me.com>
 */
interface IpAddresses {

	function getIpV4Addresses();

	function getIpV6Addresses();

}