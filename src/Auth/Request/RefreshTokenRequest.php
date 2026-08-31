<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Auth\Request;

/**
 * @link https://developer.revolut.com/docs/guides/manage-accounts/get-started/make-your-first-api-request#get-a-new-access-token
 */
final class RefreshTokenRequest extends BaseRequest{

	public function __construct(
		private readonly string $refreshToken
	){}

	public function getData(): array{
		return [
			'grant_type' => 'refresh_token',
			'refresh_token' => $this->refreshToken
		];
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): \Znojil\RevolutBusiness\TokenPair{
		/** @var array{access_token: string, token_type: string, expires_in: int} */
		$data = \Znojil\RevolutBusiness\Internal\Json::decode((string) $httpResponse->getBody());

		return new \Znojil\RevolutBusiness\TokenPair(
			$data['access_token'],
			new \DateTimeImmutable("+{$data['expires_in']} seconds"),
			$this->refreshToken
		);
	}

}
