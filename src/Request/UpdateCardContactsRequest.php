<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<list<string>>
 * @link https://developer.revolut.com/docs/api/business#update-card-contacts
 */
final class UpdateCardContactsRequest extends BaseRequest{

	/**
	 * @param non-empty-list<string> $contactIds max 5, must be unique
	 */
	public function __construct(
		private readonly string $cardId,
		private readonly array $contactIds
	){}

	public function getMethod(): string{
		return 'PUT';
	}

	public function getUrn(): string{
		return "cards/$this->cardId/contacts";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->contactIds;
	}

	/**
	 * @return list<string>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<string> */
		return $this->parseJsonResponseBody((string) $httpResponse->getBody());
	}

}
