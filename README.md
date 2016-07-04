# Cloudflare Request Detection
[![CircleCI](https://circleci.com/gh/foowie/cloudflare-detection/tree/master.svg?style=svg)](https://circleci.com/gh/foowie/cloudflare-detection/tree/master)

You can use this library to detect if request is done through Cloudflare.

```
	$cloudflare = new CloudflareDetector();
	echo($cloudflare->isCloudflareRequest());
	echo($cloudflare->getConnectingRequestIp());
	echo($cloudflare->getConnectingCountryCode());

```