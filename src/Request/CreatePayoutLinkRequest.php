<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\PayoutLinkDTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<PayoutLinkDTO>
 * @link https://developer.revolut.com/docs/api/business#create-payout-link
 *
 * @phpstan-import-type PayoutLinkResponseData from PayoutLinkDTO
 */
final class CreatePayoutLinkRequest extends BaseRequest{

	/**
	 * @param ?non-empty-list<Enum\PayoutMethod> $payoutMethods defaults to revolut and bank_account
	 * @param ?string $expiryPeriod ISO 8601 duration between P1D and P7D
	 */
	public function __construct(
		private readonly string $counterpartyName,
		private readonly string $accountId,
		private readonly float $amount,
		private readonly Enum\Currency $currency,
		private readonly string $reference,
		private readonly string $requestId,
		private readonly ?bool $saveCounterparty = null,
		private readonly ?array $payoutMethods = null,
		private readonly ?string $expiryPeriod = null,
		private readonly ?Enum\TransferReasonCode $transferReasonCode = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'payout-links';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'counterparty_name' => $this->counterpartyName,
			'save_counterparty' => $this->saveCounterparty,
			'request_id' => $this->requestId,
			'account_id' => $this->accountId,
			'amount' => $this->amount,
			'currency' => $this->currency->value,
			'reference' => $this->reference,
			'payout_methods' => $this->payoutMethods !== null
				? array_map(fn(Enum\PayoutMethod $m): string => $m->value, $this->payoutMethods)
				: null,
			'expiry_period' => $this->expiryPeriod,
			'transfer_reason_code' => $this->transferReasonCode?->value
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): PayoutLinkDTO{
		/** @var PayoutLinkResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return PayoutLinkDTO::fromResponseData($data);
	}

}
