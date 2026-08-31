<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\SpendingPeriodEndAction;

/**
 * @phpstan-type SpendingPeriodResponseData array{start_date?: string, end_date?: string, end_date_action?: string}
 */
final readonly class SpendingPeriodDTO{

	/**
	 * @param SpendingPeriodResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			isset($data['start_date']) ? new \DateTimeImmutable($data['start_date']) : null,
			isset($data['end_date']) ? new \DateTimeImmutable($data['end_date']) : null,
			isset($data['end_date_action'])
				? \Znojil\RevolutBusiness\Internal\EnumMapper::from(SpendingPeriodEndAction::class, $data['end_date_action'])
				: null
		);
	}

	public function __construct(
		public ?\DateTimeImmutable $startDate,
		public ?\DateTimeImmutable $endDate,
		public ?SpendingPeriodEndAction $endDateAction
	){}

	/**
	 * @return array<string, string>
	 */
	public function toRequestData(): array{
		return array_filter([
			'start_date' => $this->startDate?->format('Y-m-d'),
			'end_date' => $this->endDate?->format('Y-m-d'),
			'end_date_action' => $this->endDateAction?->value
		], fn(?string $v): bool => $v !== null);
	}

}
