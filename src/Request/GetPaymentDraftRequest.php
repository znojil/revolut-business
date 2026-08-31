<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\PaymentDraftDTO;

/**
 * @extends BaseRequest<PaymentDraftDTO>
 * @link https://developer.revolut.com/docs/api/business#get-payment-draft
 *
 * @phpstan-import-type PaymentDraftResponseData from PaymentDraftDTO
 */
final class GetPaymentDraftRequest extends BaseRequest{

	public function __construct(
		private readonly string $paymentDraftId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'payment-drafts/' . $this->paymentDraftId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): PaymentDraftDTO{
		/** @var PaymentDraftResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return PaymentDraftDTO::fromResponseData($data);
	}

}
