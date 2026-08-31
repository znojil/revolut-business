<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#freeze-card
 */
final class FreezeCardRequest extends BaseRequest{

	public function __construct(
		private readonly string $cardId
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "cards/$this->cardId/freeze";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
