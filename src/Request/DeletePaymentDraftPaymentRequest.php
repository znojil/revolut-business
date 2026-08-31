<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#delete-payment-draft-payment
 */
final class DeletePaymentDraftPaymentRequest extends BaseRequest{

	public function __construct(
		private readonly string $paymentDraftId,
		private readonly string $paymentId
	){}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return "payment-drafts/$this->paymentDraftId/payments/$this->paymentId";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
