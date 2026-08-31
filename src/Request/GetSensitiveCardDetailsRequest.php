<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\SensitiveCardDetailsDTO;

/**
 * @extends BaseRequest<SensitiveCardDetailsDTO>
 * @link https://developer.revolut.com/docs/api/business#get-sensitive-card-details
 *
 * @phpstan-import-type SensitiveCardDetailsResponseData from SensitiveCardDetailsDTO
 */
final class GetSensitiveCardDetailsRequest extends BaseRequest{

	public function __construct(
		private readonly string $cardId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return "cards/$this->cardId/sensitive-details";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): SensitiveCardDetailsDTO{
		/** @var SensitiveCardDetailsResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return SensitiveCardDetailsDTO::fromResponseData($data);
	}

}
