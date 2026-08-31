<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<list<array{country: string, currencies: list<string>}>>
 * @link https://developer.revolut.com/docs/api/business#get-counterparty-countries
 */
final class GetCounterpartyCountriesRequest extends BaseRequest{

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'counterparties/countries';
	}

	/**
	 * @return list<array{country: string, currencies: list<string>}>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var array{countries: list<array{country: string, currencies: list<string>}>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return $data['countries'];
	}

}
