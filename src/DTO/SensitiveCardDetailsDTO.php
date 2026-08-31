<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type SensitiveCardDetailsResponseData array{pan: string, cvv: string, expiry: string}
 */
final readonly class SensitiveCardDetailsDTO{

	/**
	 * @param SensitiveCardDetailsResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['pan'],
			$data['cvv'],
			$data['expiry']
		);
	}

	/**
	 * @param string $expiry MM/YYYY
	 */
	public function __construct(
		public string $pan,
		public string $cvv,
		public string $expiry
	){}

}
