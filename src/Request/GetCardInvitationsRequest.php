<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\CardInvitationDTO;
use Znojil\RevolutBusiness\Enum\CardInvitationState;

/**
 * @extends BaseRequest<list<CardInvitationDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-card-invitations
 *
 * @phpstan-import-type CardInvitationResponseData from CardInvitationDTO
 */
final class GetCardInvitationsRequest extends BaseRequest{

	/**
	 * @param ?non-empty-list<CardInvitationState> $state
	 */
	public function __construct(
		private readonly ?\DateTimeInterface $createdBefore = null,
		private readonly ?int $limit = null,
		private readonly ?array $state = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('card-invitations', [
			'created_before' => $this->formatDatetime($this->createdBefore),
			'limit' => $this->limit,
			'state' => $this->state !== null
				? array_map(fn(CardInvitationState $s): string => $s->value, $this->state)
				: null
		]);
	}

	/**
	 * @return list<CardInvitationDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<CardInvitationResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(CardInvitationDTO::fromResponseData(...), $data);
	}

}
