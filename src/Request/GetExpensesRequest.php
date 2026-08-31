<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\ExpenseDTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @unverified
 * @extends BaseRequest<list<ExpenseDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-expenses
 *
 * Not verified against a live account — the Expenses endpoints are not available in the sandbox.
 *
 * @phpstan-import-type ExpenseResponseData from ExpenseDTO
 */
final class GetExpensesRequest extends BaseRequest{

	public function __construct(
		private readonly ?\DateTimeInterface $from = null,
		private readonly ?\DateTimeInterface $to = null,
		private readonly ?int $count = null,
		private readonly ?Enum\ExpenseState $state = null,
		private readonly ?Enum\ExpenseTransactionType $transactionType = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('expenses', [
			'from' => $this->formatDatetime($this->from),
			'to' => $this->formatDatetime($this->to),
			'count' => $this->count,
			'state' => $this->state?->value,
			'transaction_type' => $this->transactionType?->value
		]);
	}

	/**
	 * @return list<ExpenseDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<ExpenseResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(ExpenseDTO::fromResponseData(...), $data);
	}

}
