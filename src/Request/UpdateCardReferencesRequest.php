<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CardReferenceDTO;

/**
 * @extends BaseRequest<list<CardReferenceDTO>>
 * @link https://developer.revolut.com/docs/api/business#update-card-references
 *
 * @phpstan-import-type CardReferenceResponseData from CardReferenceDTO
 */
final class UpdateCardReferencesRequest extends BaseRequest{

	/**
	 * @param non-empty-list<CardReferenceDTO> $references max 5, names must be unique
	 */
	public function __construct(
		private readonly string $cardId,
		private readonly array $references
	){}

	public function getMethod(): string{
		return 'PUT';
	}

	public function getUrn(): string{
		return "cards/$this->cardId/references";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return array_map(fn(CardReferenceDTO $r): array => $r->toRequestData(), $this->references);
	}

	/**
	 * @return list<CardReferenceDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<CardReferenceResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(CardReferenceDTO::fromResponseData(...), $data);
	}

}
