<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TransactionResultDTO;

/**
 * @extends BaseRequest<TransactionResultDTO>
 * @link https://developer.revolut.com/docs/api/business#simulate-transfer-state-update
 *
 * @phpstan-import-type TransactionResultResponseData from TransactionResultDTO
 */
final class SimulateTransactionStateRequest extends BaseRequest{

	public function __construct(
		private readonly string $transactionId,
		private readonly \Znojil\RevolutBusiness\Enum\SimulationAction $action
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "sandbox/transactions/$this->transactionId/{$this->action->value}";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): TransactionResultDTO{
		/** @var TransactionResultResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return TransactionResultDTO::fromResponseData($data);
	}

}
