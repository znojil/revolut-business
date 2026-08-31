<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CounterpartyFieldDTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<list<CounterpartyFieldDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-counterparty-requirements
 *
 * @phpstan-import-type CounterpartyFieldResponseData from CounterpartyFieldDTO
 */
final class GetCounterpartyFieldsRequest extends BaseRequest{

	public function __construct(
		private readonly string $country,
		private readonly Enum\Currency $currency,
		private readonly Enum\ProfileType $recipientType,
		private readonly ?Enum\PaymentRoute $route = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('counterparties/fields', [
			'country' => $this->country,
			'currency' => $this->currency->value,
			'recipient_type' => $this->recipientType->value,
			'route' => $this->route?->value
		]);
	}

	/**
	 * @return list<CounterpartyFieldDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var array{fields: list<CounterpartyFieldResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(CounterpartyFieldDTO::fromResponseData(...), $data['fields']);
	}

}
