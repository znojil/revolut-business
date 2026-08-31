<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness;

/**
 * @phpstan-type TokenPairData array{accessToken: string, expirationDatetime: string, refreshToken: string}
 */
final readonly class TokenPair implements \JsonSerializable{

	/**
	 * @param TokenPairData $data
	 */
	public static function fromArray(array $data): self{
		return new self(
			$data['accessToken'],
			new \DateTimeImmutable($data['expirationDatetime']),
			$data['refreshToken']
		);
	}

	public function __construct(
		public string $accessToken,
		public \DateTimeImmutable $expirationDatetime,
		public string $refreshToken
	){}

	public function isExpired(int $marginSeconds = 60): bool{
		return $this->expirationDatetime->modify("-{$marginSeconds} seconds") < new \DateTimeImmutable;
	}

	/**
	 * @return TokenPairData
	 */
	public function jsonSerialize(): array{
		return [
			'accessToken' => $this->accessToken,
			'expirationDatetime' => $this->expirationDatetime->format('c'),
			'refreshToken' => $this->refreshToken
		];
	}

}
