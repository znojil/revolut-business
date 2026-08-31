<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-type PayoutLinkResponseData array{id: string, state: string, created_at: string, updated_at: string, counterparty_name: string, save_counterparty: bool, request_id: string, expiry_date?: string, payout_methods: list<string>, account_id: string, amount: float, currency: string, url?: string, reference: string, transfer_reason_code?: string, counterparty_id?: string, transaction_id?: string, cancellation_reason?: string}
 */
final readonly class PayoutLinkDTO{

	/**
	 * @param PayoutLinkResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			EnumMapper::from(Enum\PayoutLinkState::class, $data['state']),
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			$data['counterparty_name'],
			$data['save_counterparty'],
			$data['request_id'],
			isset($data['expiry_date']) ? new \DateTimeImmutable($data['expiry_date']) : null,
			array_map(
				fn(string $v): Enum\PayoutMethod => EnumMapper::from(Enum\PayoutMethod::class, $v),
				$data['payout_methods']
			),
			$data['account_id'],
			$data['amount'],
			Enum\Currency::tryFrom($data['currency']) ?? $data['currency'],
			$data['url'] ?? null,
			$data['reference'],
			isset($data['transfer_reason_code']) ? (Enum\TransferReasonCode::tryFrom($data['transfer_reason_code']) ?? $data['transfer_reason_code']) : null,
			$data['counterparty_id'] ?? null,
			$data['transaction_id'] ?? null,
			isset($data['cancellation_reason'])
				? EnumMapper::from(Enum\PayoutLinkCancellationReason::class, $data['cancellation_reason'])
				: null
		);
	}

	/**
	 * @param list<Enum\PayoutMethod> $payoutMethods
	 * @param ?string $counterpartyId returned only by the retrieval endpoints, never on creation
	 * @param ?string $transactionId returned only by the retrieval endpoints, never on creation
	 * @param ?Enum\PayoutLinkCancellationReason $cancellationReason returned only by the retrieval endpoints, never on creation
	 */
	public function __construct(
		public string $id,
		public Enum\PayoutLinkState $state,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public string $counterpartyName,
		public bool $saveCounterparty,
		public string $requestId,
		public ?\DateTimeImmutable $expiryDate,
		public array $payoutMethods,
		public string $accountId,
		public float $amount,
		public string|Enum\Currency $currency,
		public ?string $url,
		public string $reference,
		public string|Enum\TransferReasonCode|null $transferReasonCode,
		public ?string $counterpartyId,
		public ?string $transactionId,
		public ?Enum\PayoutLinkCancellationReason $cancellationReason
	){}

}
