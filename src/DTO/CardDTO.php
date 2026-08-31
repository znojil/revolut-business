<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-import-type CardProductResponseData from CardProductDTO
 * @phpstan-import-type CardReferenceResponseData from CardReferenceDTO
 * @phpstan-import-type SpendProgramResponseData from SpendProgramDTO
 * @phpstan-import-type SpendingLimitsWithUsageResponseData from SpendingLimitsWithUsageDTO
 * @phpstan-import-type SpendingPeriodResponseData from SpendingPeriodDTO
 * @phpstan-import-type MerchantControlsResponseData from MerchantControlsDTO
 * @phpstan-import-type MccControlsResponseData from MccControlsDTO
 * @phpstan-type CardResponseData array{id: string, holder_id?: string, contact_ids?: list<string>, created_at: string, updated_at: string, terminated_at?: string, product?: CardProductResponseData, virtual: bool, last_digits: string, expiry: string, label?: string, references?: list<CardReferenceResponseData>, state: string, can_be_unlocked?: bool, spend_program?: SpendProgramResponseData, spending_limits?: SpendingLimitsWithUsageResponseData, spending_period?: SpendingPeriodResponseData, categories?: list<string>, merchant_controls?: MerchantControlsResponseData, mcc_controls?: MccControlsResponseData, countries?: list<string>, accounts: list<string>}
 */
final readonly class CardDTO{

	/**
	 * @param CardResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			$data['holder_id'] ?? null,
			$data['contact_ids'] ?? [],
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			isset($data['terminated_at']) ? new \DateTimeImmutable($data['terminated_at']) : null,
			isset($data['product']) ? CardProductDTO::fromResponseData($data['product']) : null,
			$data['virtual'],
			$data['last_digits'],
			$data['expiry'],
			$data['label'] ?? null,
			array_map(CardReferenceDTO::fromResponseData(...), $data['references'] ?? []),
			EnumMapper::from(Enum\CardState::class, $data['state']),
			$data['can_be_unlocked'] ?? null,
			isset($data['spend_program']) ? SpendProgramDTO::fromResponseData($data['spend_program']) : null,
			isset($data['spending_limits']) ? SpendingLimitsWithUsageDTO::fromResponseData($data['spending_limits']) : null,
			isset($data['spending_period']) ? SpendingPeriodDTO::fromResponseData($data['spending_period']) : null,
			array_map(
				fn(string $v): Enum\BusinessMerchantCategory => EnumMapper::from(Enum\BusinessMerchantCategory::class, $v),
				$data['categories'] ?? []
			),
			isset($data['merchant_controls']) ? MerchantControlsDTO::fromResponseData($data['merchant_controls']) : null,
			isset($data['mcc_controls']) ? MccControlsDTO::fromResponseData($data['mcc_controls']) : null,
			$data['countries'] ?? [],
			$data['accounts']
		);
	}

	/**
	 * @param list<string> $contactIds
	 * @param ?\DateTimeImmutable $terminatedAt returned only by the card detail endpoint
	 * @param string $expiry MM/YYYY
	 * @param list<CardReferenceDTO> $references
	 * @param ?SpendProgramDTO $spendProgram not returned when the card is created
	 * @param list<Enum\BusinessMerchantCategory> $categories
	 * @param list<string> $countries ISO 3166-1 alpha-2
	 * @param list<string> $accounts
	 */
	public function __construct(
		public string $id,
		public ?string $holderId,
		public array $contactIds,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public ?\DateTimeImmutable $terminatedAt,
		public ?CardProductDTO $product,
		public bool $virtual,
		public string $lastDigits,
		public string $expiry,
		public ?string $label,
		public array $references,
		public Enum\CardState $state,
		public ?bool $canBeUnlocked,
		public ?SpendProgramDTO $spendProgram,
		public ?SpendingLimitsWithUsageDTO $spendingLimits,
		public ?SpendingPeriodDTO $spendingPeriod,
		public array $categories,
		public ?MerchantControlsDTO $merchantControls,
		public ?MccControlsDTO $mccControls,
		public array $countries,
		public array $accounts
	){}

}
