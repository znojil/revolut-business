<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\Currency;

/**
 * @phpstan-import-type TransactionCounterpartyResponseData from TransactionCounterpartyDTO
 * @phpstan-type TransactionLegResponseData array{leg_id: string, amount: float, fee?: float, currency: string, bill_amount?: float, bill_currency?: string, account_id: string, counterparty?: TransactionCounterpartyResponseData, description?: string, balance?: float}
 */
final readonly class TransactionLegDTO{

	/**
	 * @param TransactionLegResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['leg_id'],
			$data['amount'],
			$data['fee'] ?? null,
			Currency::tryFrom($data['currency']) ?? $data['currency'],
			$data['bill_amount'] ?? null,
			isset($data['bill_currency']) ? (Currency::tryFrom($data['bill_currency']) ?? $data['bill_currency']) : null,
			$data['account_id'],
			isset($data['counterparty']) ? TransactionCounterpartyDTO::fromResponseData($data['counterparty']) : null,
			$data['description'] ?? null,
			$data['balance'] ?? null
		);
	}

	public function __construct(
		public string $legId,
		public float $amount,
		public ?float $fee,
		public string|Currency $currency,
		public ?float $billAmount,
		public string|Currency|null $billCurrency,
		public string $accountId,
		public ?TransactionCounterpartyDTO $counterparty,
		public ?string $description,
		public ?float $balance
	){}

}
