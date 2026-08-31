<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#delete-card
 */
final class TerminateCardRequest extends BaseRequest{

	public function __construct(
		private readonly string $cardId
	){}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return 'cards/' . $this->cardId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
