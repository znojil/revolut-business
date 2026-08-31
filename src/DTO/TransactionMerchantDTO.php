<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type TransactionMerchantResponseData array{id?: string, name?: string, full_name?: string, city?: string, category_code?: string, country?: string}
 */
final readonly class TransactionMerchantDTO{

	/**
	 * @param TransactionMerchantResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'] ?? null,
			$data['name'] ?? null,
			$data['full_name'] ?? null,
			$data['city'] ?? null,
			$data['category_code'] ?? null,
			$data['country'] ?? null
		);
	}

	/**
	 * @param ?string $country ISO 3166 alpha-3, occasionally alpha-2 when provided that way by the card network
	 */
	public function __construct(
		public ?string $id,
		public ?string $name,
		public ?string $fullName,
		public ?string $city,
		public ?string $categoryCode,
		public ?string $country
	){}

}
