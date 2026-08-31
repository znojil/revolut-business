<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\Currency;

/**
 * @phpstan-type SpendingLimitWithUsageResponseData array{amount: float, currency: string, usage?: float}
 */
final readonly class SpendingLimitWithUsageDTO{

	/**
	 * @param SpendingLimitWithUsageResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['amount'],
			Currency::tryFrom($data['currency']) ?? $data['currency'],
			$data['usage'] ?? null
		);
	}

	public function __construct(
		public float $amount,
		public string|Currency $currency,
		public ?float $usage = null
	){}

}
