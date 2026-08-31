<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\Currency;

/**
 * @phpstan-type SpendingLimitResponseData array{amount: float, currency: string}
 */
final readonly class SpendingLimitDTO{

	/**
	 * @param SpendingLimitResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['amount'],
			Currency::tryFrom($data['currency']) ?? $data['currency']
		);
	}

	public function __construct(
		public float $amount,
		public string|Currency $currency
	){}

}
