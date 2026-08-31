<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type AccountingCategoryResponseData array{id: string, name: string, code?: string, created_at: string, updated_at: string, default_tax_rate_id?: string}
 */
final readonly class AccountingCategoryDTO{

	/**
	 * @param AccountingCategoryResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'],
			$data['code'] ?? null,
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			$data['default_tax_rate_id'] ?? null
		);
	}

	public function __construct(
		public string $id,
		public string $name,
		public ?string $code,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public ?string $defaultTaxRateId
	){}

}
