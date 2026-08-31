<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\PaymentDraftInfoDTO;

/**
 * @extends BaseRequest<list<PaymentDraftInfoDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-payment-drafts
 *
 * @phpstan-import-type PaymentDraftInfoResponseData from PaymentDraftInfoDTO
 */
final class GetPaymentDraftsRequest extends BaseRequest{

	public function __construct(
		private readonly ?\Znojil\RevolutBusiness\Enum\PaymentDraftSourceFilter $source = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('payment-drafts', ['source' => $this->source?->value]);
	}

	/**
	 * @return list<PaymentDraftInfoDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var array{payment_orders: list<PaymentDraftInfoResponseData>} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(PaymentDraftInfoDTO::fromResponseData(...), $data['payment_orders']);
	}

}
