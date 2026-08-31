<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<DTO\IndicativeQuoteDTO>
 * @link https://developer.revolut.com/docs/api/business#get-indicative-quote
 *
 * @phpstan-import-type IndicativeQuoteResponseData from DTO\IndicativeQuoteDTO
 */
final class GetIndicativeQuoteRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountId,
		private readonly DTO\PaymentReceiverDTO $receiver,
		private readonly float $amount,
		private readonly Enum\Currency $currency,
		private readonly ?Enum\ChargeBearer $chargeBearer = null,
		private readonly ?Enum\TransferReasonCode $transferReasonCode = null,
		private readonly ?Enum\ExchangeReasonCode $exchangeReasonCode = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'pay/indicative-quote';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'account_id' => $this->accountId,
			'receiver' => $this->receiver->toRequestData(),
			'amount' => $this->amount,
			'currency' => $this->currency->value,
			'charge_bearer' => $this->chargeBearer?->value,
			'transfer_reason_code' => $this->transferReasonCode?->value,
			'exchange_reason_code' => $this->exchangeReasonCode?->value
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\IndicativeQuoteDTO{
		/** @var IndicativeQuoteResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return DTO\IndicativeQuoteDTO::fromResponseData($data);
	}

}
