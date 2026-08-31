<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-import-type SpendingLimitResponseData from SpendingLimitDTO
 * @phpstan-import-type SpendingLimitWithUsageResponseData from SpendingLimitWithUsageDTO
 * @phpstan-type SpendingLimitsWithUsageResponseData array{single?: SpendingLimitResponseData, day?: SpendingLimitWithUsageResponseData, week?: SpendingLimitWithUsageResponseData, month?: SpendingLimitWithUsageResponseData, quarter?: SpendingLimitWithUsageResponseData, year?: SpendingLimitWithUsageResponseData, all_time?: SpendingLimitWithUsageResponseData}
 */
final readonly class SpendingLimitsWithUsageDTO{

	/**
	 * @param SpendingLimitsWithUsageResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			isset($data['single']) ? SpendingLimitDTO::fromResponseData($data['single']) : null,
			isset($data['day']) ? SpendingLimitWithUsageDTO::fromResponseData($data['day']) : null,
			isset($data['week']) ? SpendingLimitWithUsageDTO::fromResponseData($data['week']) : null,
			isset($data['month']) ? SpendingLimitWithUsageDTO::fromResponseData($data['month']) : null,
			isset($data['quarter']) ? SpendingLimitWithUsageDTO::fromResponseData($data['quarter']) : null,
			isset($data['year']) ? SpendingLimitWithUsageDTO::fromResponseData($data['year']) : null,
			isset($data['all_time']) ? SpendingLimitWithUsageDTO::fromResponseData($data['all_time']) : null
		);
	}

	public function __construct(
		public ?SpendingLimitDTO $single,
		public ?SpendingLimitWithUsageDTO $day,
		public ?SpendingLimitWithUsageDTO $week,
		public ?SpendingLimitWithUsageDTO $month,
		public ?SpendingLimitWithUsageDTO $quarter,
		public ?SpendingLimitWithUsageDTO $year,
		public ?SpendingLimitWithUsageDTO $allTime
	){}

}
