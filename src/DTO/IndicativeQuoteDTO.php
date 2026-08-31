<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\DTO;

use Znojil\RevolutBusiness\Enum\QuoteWarning;
use Znojil\RevolutBusiness\Internal\EnumMapper;

/**
 * @phpstan-import-type MoneyResponseData from MoneyDTO
 * @phpstan-import-type QuoteFeeResponseData from QuoteFeeDTO
 * @phpstan-import-type EstimatedArrivalResponseData from EstimatedArrivalDTO
 * @phpstan-type IndicativeQuoteResponseData array{amount: MoneyResponseData, fee: QuoteFeeResponseData, estimated_total: MoneyResponseData, estimated_exchange_rate?: float|string, estimated_amount_after_exchange?: MoneyResponseData, estimated_arrival?: EstimatedArrivalResponseData, warnings?: list<string>}
 */
final readonly class IndicativeQuoteDTO{

	/**
	 * @param IndicativeQuoteResponseData $data
	 * @throws \Znojil\RevolutBusiness\Exception\UnexpectedValueException
	 */
	public static function fromResponseData(array $data): self{
		return new self(
			MoneyDTO::fromResponseData($data['amount']),
			QuoteFeeDTO::fromResponseData($data['fee']),
			MoneyDTO::fromResponseData($data['estimated_total']),
			// the API returns a float, the specification declares a string
			isset($data['estimated_exchange_rate']) ? (float) $data['estimated_exchange_rate'] : null,
			isset($data['estimated_amount_after_exchange']) ? MoneyDTO::fromResponseData($data['estimated_amount_after_exchange']) : null,
			isset($data['estimated_arrival']) ? EstimatedArrivalDTO::fromResponseData($data['estimated_arrival']) : null,
			array_map(
				fn(string $v): QuoteWarning => EnumMapper::from(QuoteWarning::class, $v),
				$data['warnings'] ?? []
			)
		);
	}

	/**
	 * @param ?float $estimatedExchangeRate omitted from the response for same-currency transfers
	 * @param ?MoneyDTO $estimatedAmountAfterExchange omitted from the response for same-currency transfers
	 * @param list<QuoteWarning> $warnings
	 */
	public function __construct(
		public MoneyDTO $amount,
		public QuoteFeeDTO $fee,
		public MoneyDTO $estimatedTotal,
		public ?float $estimatedExchangeRate,
		public ?MoneyDTO $estimatedAmountAfterExchange,
		public ?EstimatedArrivalDTO $estimatedArrival,
		public array $warnings
	){}

}
