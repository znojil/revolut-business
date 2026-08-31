<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\ReceiptDTO;

/**
 * @unverified
 * @extends BaseRequest<ReceiptDTO>
 * @link https://developer.revolut.com/docs/api/business#get-expense-receipt
 *
 * Not verified against a live account — the Expenses endpoints are not available in the sandbox.
 */
final class GetExpenseReceiptRequest extends BaseRequest{

	public function __construct(
		private readonly string $expenseId,
		private readonly string $receiptId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return "expenses/$this->expenseId/receipts/$this->receiptId/content";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): ReceiptDTO{
		return new ReceiptDTO(
			(string) $httpResponse->getBody(),
			$httpResponse->hasHeader('Content-Type') ? $httpResponse->getHeaderLine('Content-Type') : null
		);
	}

}
