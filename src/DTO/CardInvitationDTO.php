<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-import-type SpendProgramResponseData from SpendProgramDTO
 * @phpstan-import-type SpendingLimitsWithUsageResponseData from SpendingLimitsWithUsageDTO
 * @phpstan-import-type SpendingPeriodResponseData from SpendingPeriodDTO
 * @phpstan-import-type MerchantControlsResponseData from MerchantControlsDTO
 * @phpstan-import-type MccControlsResponseData from MccControlsDTO
 * @phpstan-type CardInvitationResponseData array{id: string, state: string, created_at: string, updated_at: string, expiry_date?: string, card_id?: string, holder_id?: string, virtual: bool, label?: string, spend_program?: SpendProgramResponseData, spending_limits?: SpendingLimitsWithUsageResponseData, spending_period?: SpendingPeriodResponseData, categories?: list<string>, merchant_controls?: MerchantControlsResponseData, mcc_controls?: MccControlsResponseData, countries?: list<string>, accounts: list<string>}
 */
final readonly class CardInvitationDTO{

	/**
	 * @param CardInvitationResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			$data['id'],
			EnumMapper::from(Enum\CardInvitationState::class, $data['state']),
			new \DateTimeImmutable($data['created_at']),
			new \DateTimeImmutable($data['updated_at']),
			isset($data['expiry_date']) ? new \DateTimeImmutable($data['expiry_date']) : null,
			$data['card_id'] ?? null,
			$data['holder_id'] ?? null,
			$data['virtual'],
			$data['label'] ?? null,
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
	 * @param ?string $cardId returned once the invitation has been redeemed
	 * @param ?string $holderId not returned when the team member has been deleted since the invitation was created
	 * @param list<Enum\BusinessMerchantCategory> $categories
	 * @param list<string> $countries ISO 3166-1 alpha-2
	 * @param list<string> $accounts
	 */
	public function __construct(
		public string $id,
		public Enum\CardInvitationState $state,
		public \DateTimeImmutable $createdAt,
		public \DateTimeImmutable $updatedAt,
		public ?\DateTimeImmutable $expiryDate,
		public ?string $cardId,
		public ?string $holderId,
		public bool $virtual,
		public ?string $label,
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
