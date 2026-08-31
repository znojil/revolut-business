<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<DTO\TransactionResultDTO>
 * @link https://developer.revolut.com/docs/api/business#create-payment
 *
 * @phpstan-import-type TransactionResultResponseData from DTO\TransactionResultDTO
 */
final class CreatePaymentRequest extends BaseRequest{

	/**
	 * @param ?string $nameValidationId links a preceding CoP check to this payment, see {@see ValidateAccountNameRequest}
	 */
	public function __construct(
		private readonly string $accountId,
		private readonly DTO\PaymentReceiverDTO $receiver,
		private readonly float $amount,
		private readonly Enum\Currency $currency,
		private readonly string $requestId,
		private readonly ?string $reference = null,
		private readonly ?Enum\ChargeBearer $chargeBearer = null,
		private readonly ?Enum\TransferReasonCode $transferReasonCode = null,
		private readonly ?Enum\ExchangeReasonCode $exchangeReasonCode = null,
		private readonly ?string $fiscalCode = null,
		private readonly ?string $nameValidationId = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'pay';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'request_id' => $this->requestId,
			'account_id' => $this->accountId,
			'receiver' => $this->receiver->toRequestData(),
			'amount' => $this->amount,
			'currency' => $this->currency->value,
			'reference' => $this->reference,
			'charge_bearer' => $this->chargeBearer?->value,
			'transfer_reason_code' => $this->transferReasonCode?->value,
			'exchange_reason_code' => $this->exchangeReasonCode?->value,
			'fiscal_code' => $this->fiscalCode,
			'name_validation_id' => $this->nameValidationId
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): DTO\TransactionResultDTO{
		/** @var TransactionResultResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return DTO\TransactionResultDTO::fromResponseData($data);
	}

}
