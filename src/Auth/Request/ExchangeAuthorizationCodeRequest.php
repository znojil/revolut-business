<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Auth\Request;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/get-started/make-your-first-api-request#4-exchange-authorization-code-for-access-token
 */
final class ExchangeAuthorizationCodeRequest extends BaseRequest{

	public function __construct(
		private readonly string $code
	){}

	public function getData(): array{
		return [
			'grant_type' => 'authorization_code',
			'code' => $this->code
		];
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): \Znojil\RevolutBusiness\TokenPair{
		/** @var array{access_token: string, token_type: string, expires_in: int, refresh_token: string} */
		$data = \Znojil\RevolutBusiness\Internal\Json::decode((string) $httpResponse->getBody());

		return new \Znojil\RevolutBusiness\TokenPair(
			$data['access_token'],
			new \DateTimeImmutable("+{$data['expires_in']} seconds"),
			$data['refresh_token']
		);
	}

}
