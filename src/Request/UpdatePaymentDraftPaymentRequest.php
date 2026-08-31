<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\Clear;
use Znojil\RevolutBusiness\DTO\PaymentReceiverDTO;
use Znojil\RevolutBusiness\Enum\ChargeBearer;
use Znojil\RevolutBusiness\Enum\Currency;
use Znojil\RevolutBusiness\Enum\TransferReasonCode;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#update-payment-draft-payment
 */
final class UpdatePaymentDraftPaymentRequest extends BaseRequest{

	/**
	 * @param ChargeBearer|Clear|null $chargeBearer null leaves it unchanged, Clear::Value removes it
	 */
	public function __construct(
		private readonly string $paymentDraftId,
		private readonly string $paymentId,
		private readonly ?PaymentReceiverDTO $receiver = null,
		private readonly ?float $amount = null,
		private readonly ?Currency $currency = null,
		private readonly ?string $reference = null,
		private readonly ChargeBearer|Clear|null $chargeBearer = null,
		private readonly ?TransferReasonCode $transferReasonCode = null
	){}

	public function getMethod(): string{
		return 'PATCH';
	}

	public function getUrn(): string{
		return "payment-drafts/$this->paymentDraftId/payments/$this->paymentId";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildRequiredData([
			'receiver' => $this->receiver?->toRequestData(),
			'amount' => $this->amount,
			'currency' => $this->currency?->value,
			'reference' => $this->reference,
			'charge_bearer' => $this->chargeBearer instanceof ChargeBearer ? $this->chargeBearer->value : $this->chargeBearer,
			'transfer_reason_code' => $this->transferReasonCode?->value
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
