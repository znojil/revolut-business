<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TransactionResultDTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<TransactionResultDTO>
 * @link https://developer.revolut.com/docs/api/business#simulate-top-up
 *
 * @phpstan-import-type TransactionResultResponseData from TransactionResultDTO
 */
final class SimulateTopUpRequest extends BaseRequest{

	public function __construct(
		private readonly string $accountId,
		private readonly float $amount,
		private readonly Enum\Currency $currency,
		private readonly ?string $reference = null,
		private readonly ?Enum\SimulationTopUpState $state = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'sandbox/topup';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'account_id' => $this->accountId,
			'amount' => $this->amount,
			'currency' => $this->currency->value,
			'reference' => $this->reference,
			'state' => $this->state?->value
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): TransactionResultDTO{
		/** @var TransactionResultResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return TransactionResultDTO::fromResponseData($data);
	}

}
