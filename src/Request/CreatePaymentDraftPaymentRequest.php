<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\DraftPaymentDTO;

/**
 * @extends BaseRequest<string>
 * @link https://developer.revolut.com/docs/api/business#add-payment-draft-payment
 */
final class CreatePaymentDraftPaymentRequest extends BaseRequest{

	public function __construct(
		private readonly string $paymentDraftId,
		private readonly DraftPaymentDTO $payment
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "payment-drafts/$this->paymentDraftId/payments";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->payment->toRequestData();
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): string{
		/** @var array{id: string} */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return $data['id'];
	}

}
