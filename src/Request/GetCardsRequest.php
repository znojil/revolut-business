<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CardDTO;

/**
 * @extends BaseRequest<list<CardDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-cards
 *
 * @phpstan-import-type CardResponseData from CardDTO
 */
final class GetCardsRequest extends BaseRequest{

	public function __construct(
		private readonly ?\DateTimeInterface $createdBefore = null,
		private readonly ?int $limit = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('cards', [
			'created_before' => $this->formatDatetime($this->createdBefore),
			'limit' => $this->limit
		]);
	}

	/**
	 * @return list<CardDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<CardResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(CardDTO::fromResponseData(...), $data);
	}

}
