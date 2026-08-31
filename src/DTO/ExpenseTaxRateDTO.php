<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type ExpenseTaxRateResponseData array{id: string, name: string, percentage?: float}
 */
final readonly class ExpenseTaxRateDTO{

	/**
	 * @param ExpenseTaxRateResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'],
			$data['percentage'] ?? null
		);
	}

	public function __construct(
		public string $id,
		public string $name,
		public ?float $percentage
	){}

}
