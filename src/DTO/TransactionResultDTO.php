<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\TransactionState;

/**
 * @phpstan-type TransactionResultResponseData array{id: string, state: string, created_at: string, completed_at?: string}
 */
final readonly class TransactionResultDTO{

	/**
	 * @param TransactionResultResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			\Znojil\RevolutBusiness\Internal\EnumMapper::from(TransactionState::class, $data['state']),
			new \DateTimeImmutable($data['created_at']),
			isset($data['completed_at']) ? new \DateTimeImmutable($data['completed_at']) : null
		);
	}

	public function __construct(
		public string $id,
		public TransactionState $state,
		public \DateTimeImmutable $createdAt,
		public ?\DateTimeImmutable $completedAt
	){}

}
