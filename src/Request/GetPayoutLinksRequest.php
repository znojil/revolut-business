<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\PayoutLinkDTO;
use Znojil\RevolutBusiness\Enum\PayoutLinkState;

/**
 * @extends BaseRequest<list<PayoutLinkDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-payout-links
 *
 * @phpstan-import-type PayoutLinkResponseData from PayoutLinkDTO
 */
final class GetPayoutLinksRequest extends BaseRequest{

	/**
	 * @param ?non-empty-list<PayoutLinkState> $state
	 */
	public function __construct(
		private readonly ?array $state = null,
		private readonly ?\DateTimeInterface $createdBefore = null,
		private readonly ?int $limit = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('payout-links', [
			'state' => $this->state !== null
				? array_map(fn(PayoutLinkState $s): string => $s->value, $this->state)
				: null,
			'created_before' => $this->formatDatetime($this->createdBefore),
			'limit' => $this->limit
		]);
	}

	/**
	 * @return list<PayoutLinkDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<PayoutLinkResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(PayoutLinkDTO::fromResponseData(...), $data);
	}

}
