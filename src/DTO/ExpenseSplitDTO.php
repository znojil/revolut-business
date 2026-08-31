<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-import-type MoneyResponseData from MoneyDTO
 * @phpstan-import-type ExpenseCategoryResponseData from ExpenseCategoryDTO
 * @phpstan-import-type ExpenseTaxRateResponseData from ExpenseTaxRateDTO
 * @phpstan-type ExpenseSplitResponseData array{amount: MoneyResponseData, category: ExpenseCategoryResponseData, tax_rate: ExpenseTaxRateResponseData}
 */
final readonly class ExpenseSplitDTO{

	/**
	 * @param ExpenseSplitResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			MoneyDTO::fromResponseData($data['amount']),
			ExpenseCategoryDTO::fromResponseData($data['category']),
			ExpenseTaxRateDTO::fromResponseData($data['tax_rate'])
		);
	}

	public function __construct(
		public MoneyDTO $amount,
		public ExpenseCategoryDTO $category,
		public ExpenseTaxRateDTO $taxRate
	){}

}
