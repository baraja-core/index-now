<?php

declare(strict_types=1);

namespace Baraja\IndexNow;


final class IndexNow
{
	public const
		ENGINE_BING = 'bing',
		ENGINE_YAHOO = 'yahoo';

	public const ENGINE_ENDPOINT = [
		self::ENGINE_BING => 'https://www.bing.com/indexnow?key={key}&url={url}',
		self::ENGINE_YAHOO => 'https://yandex.com/indexnow?key={key}&url={url}',
	];

	/** @var array<string, string> (engineName => url) */
	private array $endpointUrls = [];


	public function __construct(
		private string $apikey,
		string $searchEngine = self::ENGINE_BING,
	) {
		$this->resolveEndpointUrl($searchEngine);
	}


	public function sendChangedUrl(string $url): void
	{
		foreach ($this->endpointUrls as $endpointUrl) {
			$callbackUrl = str_replace('{url}', urlencode($url), $endpointUrl);
			$this->callEndpointCallback($callbackUrl);
		}
	}


	public function addEndpointUrl(string $engine, string $endpointUrl): void
	{
		$endpointUrl = str_replace('{key}', $this->apikey, $endpointUrl);
		$this->endpointUrls[strtolower($engine)] = $endpointUrl;
	}


	private function resolveEndpointUrl(string $searchEngine): void
	{
		if (isset(self::ENGINE_ENDPOINT[$searchEngine])) {
			$this->addEndpointUrl($searchEngine, self::ENGINE_ENDPOINT[$searchEngine]);
		}
	}


	private function callEndpointCallback(string $url): void
	{
		$curl = curl_init();
		curl_setopt_array(
			$curl,
			[
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_ENCODING => 'UTF-8',
			]
		);
		curl_exec($curl);
		curl_close($curl);
	}
}
