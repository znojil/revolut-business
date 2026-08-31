<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\ExchangeRateDTO;
use Znojil\RevolutBusiness\Enum\Currency;

/**
 * @extends BaseRequest<ExchangeRateDTO>
 * @link https://developer.revolut.com/docs/api/business#get-rate
 *
 * @phpstan-import-type ExchangeRateResponseData from ExchangeRateDTO
 */
final class GetExchangeRateRequest extends BaseRequest{

	public function __construct(
		private readonly Currency $from,
		private readonly Currency $to,
		private readonly ?float $amount = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('rate', [
			'from' => $this->from->value,
			'to' => $this->to->value,
			'amount' => $this->amount
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): ExchangeRateDTO{
		/** @var ExchangeRateResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return ExchangeRateDTO::fromResponseData($data);
	}

}
