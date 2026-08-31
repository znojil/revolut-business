<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CounterpartyDTO;

/**
 * @extends BaseRequest<list<CounterpartyDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-counterparties
 *
 * @phpstan-import-type CounterpartyResponseData from CounterpartyDTO
 */
final class GetCounterpartiesRequest extends BaseRequest{

	public function __construct(
		private readonly ?string $name = null,
		private readonly ?string $accountNo = null,
		private readonly ?string $sortCode = null,
		private readonly ?string $iban = null,
		private readonly ?string $bic = null,
		private readonly ?\DateTimeInterface $createdBefore = null,
		private readonly ?int $limit = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('counterparties', [
			'name' => $this->name,
			'account_no' => $this->accountNo,
			'sort_code' => $this->sortCode,
			'iban' => $this->iban,
			'bic' => $this->bic,
			'created_before' => $this->formatDatetime($this->createdBefore),
			'limit' => $this->limit
		]);
	}

	/**
	 * @return list<CounterpartyDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<CounterpartyResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(CounterpartyDTO::fromResponseData(...), $data);
	}

}
