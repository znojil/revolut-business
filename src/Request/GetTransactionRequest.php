<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TransactionDTO;

/**
 * @extends BaseRequest<TransactionDTO>
 * @link https://developer.revolut.com/docs/api/business#get-transaction
 *
 * @phpstan-import-type TransactionResponseData from TransactionDTO
 */
final class GetTransactionRequest extends BaseRequest{

	public function __construct(
		private readonly string $transactionId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'transaction/' . $this->transactionId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): TransactionDTO{
		/** @var TransactionResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return TransactionDTO::fromResponseData($data);
	}

}
