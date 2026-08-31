<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TeamMemberDTO;

/**
 * @extends BaseRequest<list<TeamMemberDTO>>
 * @link https://developer.revolut.com/docs/api/business#get-team-members
 *
 * @phpstan-import-type TeamMemberResponseData from TeamMemberDTO
 */
final class GetTeamMembersRequest extends BaseRequest{

	public function __construct(
		private readonly ?\DateTimeInterface $createdBefore = null,
		private readonly ?int $limit = null
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return $this->buildUrn('team-members', [
			'created_before' => $this->formatDatetime($this->createdBefore),
			'limit' => $this->limit
		]);
	}

	/**
	 * @return list<TeamMemberDTO>
	 */
	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): array{
		/** @var list<TeamMemberResponseData> */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return array_map(TeamMemberDTO::fromResponseData(...), $data);
	}

}
