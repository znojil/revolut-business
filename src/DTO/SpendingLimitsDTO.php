<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * Request-side counterpart of {@see SpendingLimitsWithUsageDTO}, which is what every response is read into.
 */
final readonly class SpendingLimitsDTO{

	public function __construct(
		public ?SpendingLimitDTO $single,
		public ?SpendingLimitDTO $day,
		public ?SpendingLimitDTO $week,
		public ?SpendingLimitDTO $month,
		public ?SpendingLimitDTO $quarter,
		public ?SpendingLimitDTO $year,
		public ?SpendingLimitDTO $allTime
	){}

	/**
	 * @return array<string, array{amount: float, currency: string}>
	 */
	public function toRequestData(): array{
		$limits = [
			'single' => $this->single,
			'day' => $this->day,
			'week' => $this->week,
			'month' => $this->month,
			'quarter' => $this->quarter,
			'year' => $this->year,
			'all_time' => $this->allTime
		];

		$data = [];
		foreach($limits as $k => $v){
			if($v !== null){
				$data[$k] = [
					'amount' => $v->amount,
					'currency' => $v->currency instanceof \Znojil\RevolutBusiness\Enum\Currency ? $v->currency->value : $v->currency
				];
			}
		}

		return $data;
	}

}
