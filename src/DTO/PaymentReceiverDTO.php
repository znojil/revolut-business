<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-type PaymentReceiverResponseData array{counterparty_id: string, account_id?: string, card_id?: string}
 */
final readonly class PaymentReceiverDTO{

	/**
	 * @param PaymentReceiverResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['counterparty_id'],
			$data['account_id'] ?? null,
			$data['card_id'] ?? null
		);
	}

	public function __construct(
		public string $counterpartyId,
		public ?string $accountId,
		public ?string $cardId
	){}

	/**
	 * @return PaymentReceiverResponseData
	 */
	public function toRequestData(): array{
		return ['counterparty_id' => $this->counterpartyId] + array_filter([
			'account_id' => $this->accountId,
			'card_id' => $this->cardId
		], fn(?string $v): bool => $v !== null);
	}

}
