<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

/**
 * @phpstan-import-type MoneyResponseData from MoneyDTO
 * @phpstan-type QuoteFeeResponseData array{amount: float, currency: string, breakdown?: array{transfer_fee?: MoneyResponseData, exchange_fee?: MoneyResponseData}}
 */
final readonly class QuoteFeeDTO{

	/**
	 * @param QuoteFeeResponseData $data
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			MoneyDTO::fromResponseData(['amount' => $data['amount'], 'currency' => $data['currency']]),
			isset($data['breakdown']['transfer_fee']) ? MoneyDTO::fromResponseData($data['breakdown']['transfer_fee']) : null,
			isset($data['breakdown']['exchange_fee']) ? MoneyDTO::fromResponseData($data['breakdown']['exchange_fee']) : null
		);
	}

	/**
	 * @param ?MoneyDTO $transferFee the breakdown is omitted from the response when the total fee is zero
	 * @param ?MoneyDTO $exchangeFee the breakdown is omitted from the response when the total fee is zero
	 */
	public function __construct(
		public MoneyDTO $total,
		public ?MoneyDTO $transferFee,
		public ?MoneyDTO $exchangeFee
	){}

}
