<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness;

final readonly class Config{

	public function __construct(
		public string $clientId,
		public string $issuer,
		public string $privateKey,
		public bool $sandbox
	){}

	public function getApiUrl(): string{
		return $this->sandbox ? 'https://sandbox-b2b.revolut.com/api' : 'https://b2b.revolut.com/api';
	}

}
