<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-import-type MoneyResponseData from MoneyDTO
 * @phpstan-type ChargeOptionsResponseData array{from: MoneyResponseData, to: MoneyResponseData, rate?: string, fee?: MoneyResponseData}
 */
final readonly class ChargeOptionsDTO{

	/**
	 * @param ChargeOptionsResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			MoneyDTO::fromResponseData($data['from']),
			MoneyDTO::fromResponseData($data['to']),
			$data['rate'] ?? null,
			isset($data['fee']) ? MoneyDTO::fromResponseData($data['fee']) : null
		);
	}

	public function __construct(
		public MoneyDTO $from,
		public MoneyDTO $to,
		public ?string $rate,
		public ?MoneyDTO $fee
	){}

}
