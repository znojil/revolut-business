<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness;

final class Client{

	private readonly Http\Client $httpClient;

	private readonly Auth\Client $authClient;

	private readonly Auth\AccessTokenProvider $accessTokenProvider;

	public function __construct(
		private readonly Config $config,
		private readonly TokenStorage $tokenStorage,
		?Http\Client $httpClient = null
	){
		$this->httpClient = $httpClient ?? new Http\ZnojilClient;
		$this->authClient = new Auth\Client($config, $this->httpClient);
		$this->accessTokenProvider = new Auth\AccessTokenProvider($this->authClient, $tokenStorage);
	}

	/**
	 * One-time exchange of the authorization code from the consent flow; stores the token pair.
	 *
	 * @throws Auth\Exception\AuthenticationException if the code exchange fails (invalid/expired code, wrong client configuration)
	 * @throws Auth\Exception\ClientAssertionException if the JWT client assertion cannot be created (invalid private key)
	 * @throws Exception\IOException if the bundled FileTokenStorage fails to persist the token pair (custom TokenStorage implementations may throw their own exceptions)
	 */
	public function authorize(string $code): void{
		$this->tokenStorage->save(
			$this->authClient->send(new Auth\Request\ExchangeAuthorizationCodeRequest($code))
		);
	}

	/**
	 * @template TResponse
	 * @param Http\Request<TResponse> $request
	 * @return TResponse
	 * @throws Exception\InvalidArgumentException if the request is constructed with invalid data (e.g. an update request with no properties to update)
	 * @throws Exception\MissingTokenException if no token pair is stored yet — run the authorization flow first
	 * @throws Auth\Exception\AuthenticationException if the access token refresh fails (e.g. revoked refresh token)
	 * @throws Auth\Exception\ClientAssertionException if the JWT client assertion cannot be created (invalid private key)
	 * @throws Exception\IOException if the bundled FileTokenStorage fails to persist refreshed tokens (custom TokenStorage implementations may throw their own exceptions)
	 * @throws Exception\ClientException on 4xx API response
	 * @throws Exception\ServerException on 5xx API response
	 * @throws Exception\ResponseException on any other non-2xx API response
	 * @throws Exception\JsonException if a successful response body is not valid JSON
	 * @throws Exception\UnexpectedValueException if a response contains an unknown enum value
	 */
	public function send(Http\Request $request): mixed{
		$uri = new \Znojil\Http\Message\Uri(
			$this->config->getApiUrl() . '/' . $request->getApiVersion()->value . '/' . ltrim($request->getUrn(), '/')
		);

		$headers = $request->getHeaders();
		$headers['Authorization'] = 'Bearer ' . $this->accessTokenProvider->getAccessToken();

		$response = $this->httpClient->send($request->getMethod(), $uri, $headers, $request->getData(), $request->getHttpClientOptions());

		$statusCode = $response->getStatusCode();
		if($statusCode < 200 || $statusCode >= 300){
			$body = (string) $response->getBody();

			$apiErrorId = null;
			$apiErrorCode = null;
			$message = "Request failed. Result:\n" . $body;
			try{
				$error = Internal\Json::decode($body);
				if(is_array($error) && isset($error['message']) && is_string($error['message'])){
					$apiErrorId = isset($error['error_id']) && is_string($error['error_id']) ? $error['error_id'] : null;
					$apiErrorCode = isset($error['code']) && is_int($error['code']) ? $error['code'] : null;
					$message = $error['message'] . ($apiErrorCode !== null ? " ($apiErrorCode)" : '');
				}
			}catch(Exception\JsonException){
				// non-JSON error body (proxy, outage) — keep the raw body message
			}

			throw match(true){
				$statusCode >= 500 => new Exception\ServerException($message, $statusCode, $apiErrorCode, $apiErrorId, $body),
				$statusCode >= 400 => new Exception\ClientException($message, $statusCode, $apiErrorCode, $apiErrorId, $body),
				default => new Exception\ResponseException($message, $statusCode, $apiErrorCode, $apiErrorId, $body)
			};
		}

		return $request->createResponse($response);
	}

}
