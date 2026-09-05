<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-type AccountResponseData array{id: string, name?: string, balance: float, currency: string, state: string, public: bool, created_at: string, updated_at: string, account_type: string}
 */
final readonly class AccountDTO{

	/**
	 * @param AccountResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'] ?? null,
			$data['balance'],
			Enum\Currency::tryFrom($data['currency']) ?? $data['currency'],
			EnumMapper::from(Enum\AccountState::class, $data['state']),
			$data['public'],
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			EnumMapper::from(Enum\AccountType::class, $data['account_type'])
		);
	}

	public function __construct(
		public string $id,
		public ?string $name,
		public float $balance,
		public string|Enum\Currency $currency,
		public Enum\AccountState $state,
		public bool $public,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public Enum\AccountType $accountType
	){}

}
