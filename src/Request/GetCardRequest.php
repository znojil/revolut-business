<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CardDTO;

/**
 * @extends BaseRequest<CardDTO>
 * @link https://developer.revolut.com/docs/api/business#get-card
 *
 * @phpstan-import-type CardResponseData from CardDTO
 */
final class GetCardRequest extends BaseRequest{

	public function __construct(
		private readonly string $cardId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'cards/' . $this->cardId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): CardDTO{
		/** @var CardResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return CardDTO::fromResponseData($data);
	}

}
