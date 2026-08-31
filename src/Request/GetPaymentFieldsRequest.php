<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;

/**
 * @extends BaseRequest<list<DTO\PaymentFieldDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-payment-requirements
 *
 * @phpstan-import-type PaymentFieldResponseData from DTO\PaymentFieldDTO
 */
final class GetPaymentFieldsRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountId,
		private readonly DTO\PaymentReceiverDTO $receiver
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'pay/fields';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'account_id' => $this->accountId,
			'receiver' => $this->receiver->toRequestData()
		]);
	}

	/**
	 * @return list<DTO\PaymentFieldDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var array{fields: list<PaymentFieldResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(DTO\PaymentFieldDTO::fromResponseData(...), $data['fields']);
	}

}
