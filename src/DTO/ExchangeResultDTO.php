<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\TransactionState;

/**
 * @phpstan-type ExchangeResultResponseData array{id: string, type?: string, reason_code?: string, state: string, created_at: string, completed_at?: string}
 */
final readonly class ExchangeResultDTO{

	/**
	 * @param ExchangeResultResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['type'] ?? null,
			$data['reason_code'] ?? null,
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(TransactionState::class, $data['state']),
			new \DateTimeImmutable($data['created_at']),
			isset($data['completed_at']) ? new \DateTimeImmutable($data['completed_at']) : null
		);
	}

	/**
	 * @param ?string $reasonCode present only when $state is declined or failed
	 */
	public function __construct(
		public string $id,
		public ?string $type,
		public ?string $reasonCode,
		public TransactionState $state,
		public \DateTimeImmutable $createdAt,
		public ?\DateTimeImmutable $completedAt
	){}

}
