<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TeamMemberDTO;

/**
 * @extends BaseRequest<TeamMemberDTO>
 * @link https://developer.revolut.com/docs/api/business#get-team-member-by-id
 *
 * @phpstan-import-type TeamMemberResponseData from TeamMemberDTO
 */
final class GetTeamMemberRequest extends BaseRequest{

	public function __construct(
		private readonly string $teamMemberId
	){}

	public function getMethod(): string{
		return 'GET';
	}

	public function getUrn(): string{
		return 'team-members/' . $this->teamMemberId;
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): TeamMemberDTO{
		/** @var TeamMemberResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return TeamMemberDTO::fromResponseData($data);
	}

}
