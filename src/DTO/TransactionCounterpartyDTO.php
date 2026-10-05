<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\TransactionCounterpartyAccountType;

/**
 * @phpstan-type TransactionCounterpartyResponseData array{account_id?: string, account_type: string, id?: string}
 */
final readonly class TransactionCounterpartyDTO{

	/**
	 * @param TransactionCounterpartyResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'] ?? null,
			$data['account_id'] ?? null,
			TransactionCounterpartyAccountType::from($data['account_type'])
		);
	}

	public function __construct(
		public ?string $id,
		public ?string $accountId,
		public TransactionCounterpartyAccountType $accountType
	){}

}
