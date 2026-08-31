<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TransactionResultDTO;
use Znojil\RevolutBusiness\Enum\Currency;

/**
 * @extends BaseRequest<TransactionResultDTO>
 * @link https://developer.revolut.com/docs/api/business#create-transfer
 *
 * @phpstan-import-type TransactionResultResponseData from TransactionResultDTO
 */
final class CreateTransferRequest extends BaseRequest{

	public function __construct(
		private readonly string $sourceAccountId,
		private readonly string $targetAccountId,
		private readonly float $amount,
		private readonly Currency $currency,
		private readonly string $requestId,
		private readonly ?string $reference = null
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'transfer';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'request_id' => $this->requestId,
			'source_account_id' => $this->sourceAccountId,
			'target_account_id' => $this->targetAccountId,
			'amount' => $this->amount,
			'currency' => $this->currency->value,
			'reference' => $this->reference
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): TransactionResultDTO{
		/** @var TransactionResultResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return TransactionResultDTO::fromResponseData($data);
	}

}
