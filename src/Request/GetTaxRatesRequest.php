<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;

/**
 * @extends BaseRequest<DTO\PaginatedListDTO<DTO\TaxRateDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-tax-rates
 *
 * @phpstan-import-type TaxRateResponseData from DTO\TaxRateDTO
 */
final class GetTaxRatesRequest extends BaseRequest{

	public function __construct(
		private readonly ?int $limit = null,
		private readonly ?string $pageToken = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('tax-rates', [
			'limit' => $this->limit,
			'page_token' => $this->pageToken
		]);
	}

	/**
	 * @return DTO\PaginatedListDTO<DTO\TaxRateDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\PaginatedListDTO{
		/** @var array{next_page_token?: string, tax_rates: list<TaxRateResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return new DTO\PaginatedListDTO(
			array_map(DTO\TaxRateDTO::fromResponseData(...), $data['tax_rates']),
			$data['next_page_token'] ?? null
		);
	}

}
