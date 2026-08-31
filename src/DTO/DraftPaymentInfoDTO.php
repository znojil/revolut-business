<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\Currency;
use Znojil\RevolutBusiness\Enum\PaymentState;
use Znojil\RevolutBusiness\Enum\TransferReasonCode;

/**
 * @phpstan-import-type MoneyResponseData from MoneyDTO
 * @phpstan-import-type PaymentReceiverResponseData from PaymentReceiverDTO
 * @phpstan-import-type ChargeOptionsResponseData from ChargeOptionsDTO
 * @phpstan-type DraftPaymentInfoResponseData array{id: string, amount: MoneyResponseData, currency?: string, account_id: string, receiver?: PaymentReceiverResponseData, state: string, reason?: string, error_message?: string, current_charge_options: ChargeOptionsResponseData, reference?: string, transfer_reason_code?: string}
 */
final readonly class DraftPaymentInfoDTO{

	/**
	 * @param DraftPaymentInfoResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			MoneyDTO::fromResponseData($data['amount']),
			isset($data['currency']) ? (Currency::tryFrom($data['currency']) ?? $data['currency']) : null,
			$data['account_id'],
			isset($data['receiver']) ? PaymentReceiverDTO::fromResponseData($data['receiver']) : null,
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(PaymentState::class, $data['state']),
			$data['reason'] ?? null,
			$data['error_message'] ?? null,
			ChargeOptionsDTO::fromResponseData($data['current_charge_options']),
			$data['reference'] ?? null,
			isset($data['transfer_reason_code']) ? (TransferReasonCode::tryFrom($data['transfer_reason_code']) ?? $data['transfer_reason_code']) : null
		);
	}

	public function __construct(
		public string $id,
		public MoneyDTO $amount,
		public string|Currency|null $currency,
		public string $accountId,
		public ?PaymentReceiverDTO $receiver,
		public PaymentState $state,
		public ?string $reason,
		public ?string $errorMessage,
		public ChargeOptionsDTO $currentChargeOptions,
		public ?string $reference,
		public string|TransferReasonCode|null $transferReasonCode
	){}

}
