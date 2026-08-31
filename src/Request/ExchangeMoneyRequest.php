<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\ExchangeResultDTO;
use Znojil\RevolutBusiness\Enum\Currency;
use Znojil\RevolutBusiness\Enum\ExchangeReasonCode;

/**
 * @extends BaseRequest<ExchangeResultDTO>
 * @link https://developer.revolut.com/docs/api/business#exchange-money
 *
 * @phpstan-import-type ExchangeResultResponseData from ExchangeResultDTO
 */
final class ExchangeMoneyRequest extends BaseRequest{

	public static function sell(
		float $amount,
		string $fromAccountId,
		Currency $fromCurrency,
		string $toAccountId,
		Currency $toCurrency,
		string $requestId,
		?string $reference = null,
		?ExchangeReasonCode $exchangeReasonCode = null
	): self{
		return new self(
			$fromAccountId,
			$fromCurrency,
			$toAccountId,
			$toCurrency,
			$requestId,
			fromAmount: $amount,
			reference: $reference,
			exchangeReasonCode: $exchangeReasonCode
		);
	}

	public static function buy(
		float $amount,
		string $fromAccountId,
		Currency $fromCurrency,
		string $toAccountId,
		Currency $toCurrency,
		string $requestId,
		?string $reference = null,
		?ExchangeReasonCode $exchangeReasonCode = null
	): self{
		return new self(
			$fromAccountId,
			$fromCurrency,
			$toAccountId,
			$toCurrency,
			$requestId,
			toAmount: $amount,
			reference: $reference,
			exchangeReasonCode: $exchangeReasonCode
		);
	}

	private function __construct(
		private readonly string $fromAccountId,
		private readonly Currency $fromCurrency,
		private readonly string $toAccountId,
		private readonly Currency $toCurrency,
		private readonly string $requestId,
		private readonly ?float $fromAmount = null,
		private readonly ?float $toAmount = null,
		private readonly ?string $reference = null,
		private readonly ?ExchangeReasonCode $exchangeReasonCode = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'exchange';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'from' => $this->buildData([
				'account_id' => $this->fromAccountId,
				'currency' => $this->fromCurrency->value,
				'amount' => $this->fromAmount
			]),
			'to' => $this->buildData([
				'account_id' => $this->toAccountId,
				'currency' => $this->toCurrency->value,
				'amount' => $this->toAmount
			]),
			'reference' => $this->reference,
			'request_id' => $this->requestId,
			'exchange_reason_code' => $this->exchangeReasonCode?->value
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): ExchangeResultDTO{
		/** @var ExchangeResultResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return ExchangeResultDTO::fromResponseData($data);
	}

}
