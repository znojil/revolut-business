<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#delete-counterparty
 */
final class DeleteCounterpartyRequest extends BaseRequest{

	public function __construct(
		private readonly string $counterpartyId
	){}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return 'counterparty/' . $this->counterpartyId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
