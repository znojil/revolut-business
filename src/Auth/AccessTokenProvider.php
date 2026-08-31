<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Auth;

final class AccessTokenProvider{

	private ?\Znojil\RevolutBusiness\TokenPair $tokenPair = null;

	public function __construct(
		private readonly Client $authClient,
		private readonly \Znojil\RevolutBusiness\TokenStorage $tokenStorage
	){}

	public function getAccessToken(): string{
		$tokenPair = $this->tokenPair ??= $this->tokenStorage->load()
			?? throw new \Znojil\RevolutBusiness\Exception\MissingTokenException('No token pair stored. Run the authorization flow first.');

		if($tokenPair->isExpired()){
			$tokenPair = $this->authClient->send(new Request\RefreshTokenRequest($tokenPair->refreshToken));

			$this->tokenStorage->save($tokenPair);
			$this->tokenPair = $tokenPair;
		}

		return $tokenPair->accessToken;
	}

}
