<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\ExpenseDTO;

/**
 * @unverified
 * @extends BaseRequest<ExpenseDTO>
 * @link https://developer.revolut.com/docs/api/business#get-expense
 *
 * Not verified against a live account — the Expenses endpoints are not available in the sandbox.
 *
 * @phpstan-import-type ExpenseResponseData from ExpenseDTO
 */
final class GetExpenseRequest extends BaseRequest{

	public function __construct(
		private readonly string $expenseId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'expenses/' . $this->expenseId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): ExpenseDTO{
		/** @var ExpenseResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return ExpenseDTO::fromResponseData($data);
	}

}
