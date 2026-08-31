<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-import-type MoneyResponseData from MoneyDTO
 * @phpstan-type ExchangeRateResponseData array{from: MoneyResponseData, to: MoneyResponseData, rate: float, fee: MoneyResponseData, rate_date: string}
 */
final readonly class ExchangeRateDTO{

	/**
	 * @param ExchangeRateResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			MoneyDTO::fromResponseData($data['from']),
			MoneyDTO::fromResponseData($data['to']),
			$data['rate'],
			MoneyDTO::fromResponseData($data['fee']),
			new \DateTimeImmutable($data['rate_date'])
		);
	}

	public function __construct(
		public MoneyDTO $from,
		public MoneyDTO $to,
		public float $rate,
		public MoneyDTO $fee,
		public \DateTimeImmutable $rateDate
	){}

}
