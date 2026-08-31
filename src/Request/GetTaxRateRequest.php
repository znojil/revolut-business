<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TaxRateDTO;

/**
 * @extends BaseRequest<TaxRateDTO>
 * @link https://developer.revolut.com/docs/api/business#get-tax-rate
 *
 * @phpstan-import-type TaxRateResponseData from TaxRateDTO
 */
final class GetTaxRateRequest extends BaseRequest{

	public function __construct(
		private readonly string $taxRateId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'tax-rates/' . $this->taxRateId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): TaxRateDTO{
		/** @var TaxRateResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return TaxRateDTO::fromResponseData($data);
	}

}
