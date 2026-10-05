<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Auth;

use Znojil\RevolutBusiness\Exception\JsonException;
use Znojil\RevolutBusiness\Exception\UnexpectedResponseException;

final class Client{

	private readonly ClientAssertionFactory $assertionFactory;

	public function __construct(
		private readonly \Znojil\RevolutBusiness\Config $config,
		private readonly \Znojil\RevolutBusiness\Http\Client $httpClient
	){
		$this->assertionFactory = new ClientAssertionFactory($config);
	}

	/**
	 * @throws Exception\AuthenticationException if the token endpoint returns a non-2xx response (invalid/expired code, revoked refresh token, wrong client configuration)
	 * @throws Exception\ClientAssertionException if the JWT client assertion cannot be created (invalid private key)
	 * @throws JsonException if a successful response body is not valid JSON
	 * @throws UnexpectedResponseException if a successful response body does not match the expected shape
	 */
	public function send(Request\BaseRequest $request): \Znojil\RevolutBusiness\TokenPair{
		$uri = new \Znojil\Http\Message\Uri($this->config->getApiUrl() . '/' . ltrim($request->getUrn(), '/'));

		$data = $request->getData() + [
			'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
			'client_assertion' => $this->assertionFactory->create()
		];

		$response = $this->httpClient->send($request->getMethod(), $uri, $request->getHeaders(), $data);

		$statusCode = $response->getStatusCode();
		$body = (string) $response->getBody();

		if($statusCode < 200 || $statusCode >= 300){
			$apiErrorCode = null;
			$message = "Auth request failed. Result:\n" . $body;
			try{
				$error = \Znojil\RevolutBusiness\Internal\Json::decode($body);
				if(is_array($error)){
					if(isset($error['message']) && is_string($error['message'])){
						$apiErrorCode = isset($error['code']) && is_int($error['code']) ? $error['code'] : null;
						$message = $error['message'] . ($apiErrorCode !== null ? " ($apiErrorCode)" : '');
					}elseif(isset($error['error_description']) && is_string($error['error_description'])){
						$message = $error['error_description'] . (isset($error['error']) && is_string($error['error']) ? " ({$error['error']})" : '');
					}
				}
			}catch(\ValueError|\TypeError|\Exception){
				// unexpected error body (proxy, outage) — keep the raw body message
			}

			throw new Exception\AuthenticationException($message, $statusCode, $apiErrorCode, responseBody: $body);
		}

		try{
			return $request->createResponse($response);
		}catch(\Znojil\RevolutBusiness\Exception\Exception $e){
			throw $e;
		}catch(\ValueError|\TypeError|\Exception $e){
			throw new UnexpectedResponseException('Unexpected response: ' . $e->getMessage(), 0, responseBody: $body, previous: $e);
		}
	}

}
