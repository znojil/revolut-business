<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CardInvitationDTO;

/**
 * @extends BaseRequest<CardInvitationDTO>
 * @link https://developer.revolut.com/docs/api/business#get-card-invitation
 *
 * @phpstan-import-type CardInvitationResponseData from CardInvitationDTO
 */
final class GetCardInvitationRequest extends BaseRequest{

	public function __construct(
		private readonly string $cardInvitationId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'card-invitations/' . $this->cardInvitationId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): CardInvitationDTO{
		/** @var CardInvitationResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return CardInvitationDTO::fromResponseData($data);
	}

}
