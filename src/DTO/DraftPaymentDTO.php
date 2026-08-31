<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;

final readonly class DraftPaymentDTO{

	public function __construct(
		public string $accountId,
		public PaymentReceiverDTO $receiver,
		public float $amount,
		public Enum\Currency $currency,
		public string $reference,
		public ?Enum\ChargeBearer $chargeBearer = null,
		public ?Enum\TransferReasonCode $transferReasonCode = null
	){}

	/**
	 * @return array<string, mixed>
	 */
	public function toRequestData(): array{
		return [
			'account_id' => $this->accountId,
			'receiver' => $this->receiver->toRequestData(),
			'amount' => $this->amount,
			'currency' => $this->currency->value,
			'reference' => $this->reference
		] + array_filter([
			'charge_bearer' => $this->chargeBearer?->value,
			'transfer_reason_code' => $this->transferReasonCode?->value
		], fn(?string $v): bool => $v !== null);
	}

}
