<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#cancel-card-invitation
 */
final class CancelCardInvitationRequest extends BaseRequest{

	public function __construct(
		private readonly string $cardInvitationId
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "card-invitations/$this->cardInvitationId/cancel";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
