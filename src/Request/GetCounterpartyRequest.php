<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CounterpartyDTO;

/**
 * @extends BaseRequest<CounterpartyDTO>
 * @link https://developer.revolut.com/docs/api/business#get-counterparty
 *
 * @phpstan-import-type CounterpartyResponseData from CounterpartyDTO
 */
final class GetCounterpartyRequest extends BaseRequest{

	public function __construct(
		private readonly string $counterpartyId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'counterparty/' . $this->counterpartyId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): CounterpartyDTO{
		/** @var CounterpartyResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return CounterpartyDTO::fromResponseData($data);
	}

}
