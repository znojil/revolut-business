<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\TransactionState;
use Znojil\RevolutBusiness\Enum\TransactionType;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-import-type TransactionLegResponseData from TransactionLegDTO
 * @phpstan-import-type TransactionMerchantResponseData from TransactionMerchantDTO
 * @phpstan-import-type TransactionCardResponseData from TransactionCardDTO
 * @phpstan-type TransactionResponseData array{id: string, type: string, request_id?: string, state: string, reason_code?: string, created_at: string, updated_at: string, completed_at?: string, scheduled_for?: string, related_transaction_id?: string, merchant?: TransactionMerchantResponseData, reference?: string, legs: list<TransactionLegResponseData>, card?: TransactionCardResponseData, auth_code?: string}
 */
final readonly class TransactionDTO{

	/**
	 * @param TransactionResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			EnumMapper::from(TransactionType::class, $data['type']),
			$data['request_id'] ?? null,
			EnumMapper::from(TransactionState::class, $data['state']),
			$data['reason_code'] ?? null,
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			isset($data['completed_at']) ? new \DateTimeImmutable($data['completed_at']) : null,
			isset($data['scheduled_for']) ? new \DateTimeImmutable($data['scheduled_for']) : null,
			$data['related_transaction_id'] ?? null,
			isset($data['merchant']) ? TransactionMerchantDTO::fromResponseData($data['merchant']) : null,
			$data['reference'] ?? null,
			array_map(TransactionLegDTO::fromResponseData(...), $data['legs']),
			isset($data['card']) ? TransactionCardDTO::fromResponseData($data['card']) : null,
			$data['auth_code'] ?? null
		);
	}

	/**
	 * @param list<TransactionLegDTO> $legs two legs for transfers between your own accounts, otherwise one
	 */
	public function __construct(
		public string $id,
		public TransactionType $type,
		public ?string $requestId,
		public TransactionState $state,
		public ?string $reasonCode,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public ?\DateTimeImmutable $completedAt,
		public ?\DateTimeImmutable $scheduledFor,
		public ?string $relatedTransactionId,
		public ?TransactionMerchantDTO $merchant,
		public ?string $reference,
		public array $legs,
		public ?TransactionCardDTO $card,
		public ?string $authCode
	){}

}
