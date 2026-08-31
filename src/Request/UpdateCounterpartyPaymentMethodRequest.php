<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\AddressDTO;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#update-counterparty-payment-method
 * @link https://developer.revolut.com/docs/guides/manage-accounts/counterparties/update-counterparty-payment-method
 */
final class UpdateCounterpartyPaymentMethodRequest extends BaseRequest{

	public function __construct(
		private readonly string $counterpartyId,
		private readonly string $paymentMethodId,
		private readonly AddressDTO $address
	){}

	public function getMethod(): string{
		return 'PATCH';
	}

	public function getUrn(): string{
		return "counterparties/$this->counterpartyId/payment-methods/$this->paymentMethodId";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return ['address' => $this->address->toRequestData()];
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
