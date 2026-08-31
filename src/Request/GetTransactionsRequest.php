<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TransactionDTO;
use Znojil\RevolutBusiness\Enum;

/**
 * @extends BaseRequest<list<TransactionDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-transactions
 *
 * @phpstan-import-type TransactionResponseData from TransactionDTO
 */
final class GetTransactionsRequest extends BaseRequest{

	/**
	 * @param ?non-empty-list<Enum\TransactionState> $state
	 */
	public function __construct(
		private readonly ?\DateTimeInterface $from = null,
		private readonly ?\DateTimeInterface $to = null,
		private readonly ?string $accountId = null,
		private readonly ?int $count = null,
		private readonly ?Enum\TransactionType $type = null,
		private readonly ?string $requestId = null,
		private readonly ?array $state = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('transactions', [
			'from' => $this->formatDatetime($this->from),
			'to' => $this->formatDatetime($this->to),
			'account' => $this->accountId,
			'count' => $this->count,
			'type' => $this->type?->value,
			'request_id' => $this->requestId,
			'state' => $this->state !== null
				? array_map(fn(Enum\TransactionState $s): string => $s->value, $this->state)
				: null
		]);
	}

	/**
	 * @return list<TransactionDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<TransactionResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(TransactionDTO::fromResponseData(...), $data);
	}

}
