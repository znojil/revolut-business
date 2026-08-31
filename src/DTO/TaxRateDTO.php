<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type TaxRateResponseData array{id: string, name: string, created_at: string, updated_at: string, percentage?: float}
 */
final readonly class TaxRateDTO{

	/**
	 * @param TaxRateResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'],
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			$data['percentage'] ?? null
		);
	}

	public function __construct(
		public string $id,
		public string $name,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public ?float $percentage
	){}

}
