<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Auth;

use Znojil\RevolutBusiness\Internal\Json;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/get-started/make-your-first-api-request#2-generate-a-client-assertion
 */
final class ClientAssertionFactory{

	private const Algorithm = 'RS256';

	private const Audience = 'https://revolut.com';

	private const ExpirationSeconds = 300;

	public function __construct(
		private readonly \Znojil\RevolutBusiness\Config $config
	){}

	/**
	 * @throws Exception\ClientAssertionException
	 */
	public function create(): string{
		$segments = [];

		// header
		$segments[] = $this->base64UrlEncode(Json::encode([
			'alg' => self::Algorithm,
			'typ' => 'JWT'
		]));
		// payload
		$segments[] = $this->base64UrlEncode(Json::encode([
			'iss' => $this->config->issuer,
			'sub' => $this->config->clientId,
			'aud' => self::Audience, // Json::encode must keep JSON_UNESCAPED_SLASHES, or the signature gets rejected
			'exp' => time() + self::ExpirationSeconds
		]));

		$signingInput = implode('.', $segments);

		return $signingInput . '.' . $this->base64UrlEncode($this->sign($signingInput));
	}

	private function sign(string $data): string{
		$key = openssl_pkey_get_private($this->config->privateKey);
		if($key === false){
			throw new Exception\ClientAssertionException("Private key is not valid: " . (openssl_error_string() ?: 'unknown error'));
		}

		if(!openssl_sign($data, $signature, $key, OPENSSL_ALGO_SHA256) || !is_string($signature)){
			throw new Exception\ClientAssertionException("Signing failed: " . (openssl_error_string() ?: 'unknown error'));
		}

		return $signature;
	}

	private function base64UrlEncode(string $data): string{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

}
