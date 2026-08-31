<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\CounterpartyState;
use Znojil\RevolutBusiness\Enum\ProfileType;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-import-type CounterpartyAccountResponseData from CounterpartyAccountDTO
 * @phpstan-import-type CounterpartyCardResponseData from CounterpartyCardDTO
 * @phpstan-type CounterpartyResponseData array{id: string, name: string, revtag?: string, profile_type?: string, country?: string, state: string, created_at: string, updated_at: string, accounts?: list<CounterpartyAccountResponseData>, cards?: list<CounterpartyCardResponseData>}
 */
final readonly class CounterpartyDTO{

	/**
	 * @param CounterpartyResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['name'],
			$data['revtag'] ?? null,
			isset($data['profile_type']) ? EnumMapper::from(ProfileType::class, $data['profile_type']) : null,
			$data['country'] ?? null,
			EnumMapper::from(CounterpartyState::class, $data['state']),
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			array_map(CounterpartyAccountDTO::fromResponseData(...), $data['accounts'] ?? []),
			array_map(CounterpartyCardDTO::fromResponseData(...), $data['cards'] ?? [])
		);
	}

	/**
	 * @param ?string $country ISO 3166-1 alpha-2
	 * @param list<CounterpartyAccountDTO> $accounts
	 * @param list<CounterpartyCardDTO> $cards
	 */
	public function __construct(
		public string $id,
		public string $name,
		public ?string $revtag,
		public ?ProfileType $profileType,
		public ?string $country,
		public CounterpartyState $state,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public array $accounts,
		public array $cards
	){}

}
