<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\ExchangeReasonDTO;

/**
 * @extends BaseRequest<list<ExchangeReasonDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-exchange-reasons
 *
 * @phpstan-import-type ExchangeReasonResponseData from ExchangeReasonDTO
 */
final class GetExchangeReasonsRequest extends BaseRequest{

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'exchange-reasons';
	}

	/**
	 * @return list<ExchangeReasonDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<ExchangeReasonResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(ExchangeReasonDTO::fromResponseData(...), $data);
	}

}
