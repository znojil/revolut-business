<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

use Znojil\RevolutBusiness\DTO\TeamMemberInvitationDTO;

/**
 * @extends BaseRequest<TeamMemberInvitationDTO>
 * @link https://developer.revolut.com/docs/api/business#invite-team-member
 *
 * @phpstan-import-type TeamMemberInvitationResponseData from TeamMemberInvitationDTO
 */
final class InviteTeamMemberRequest extends BaseRequest{

	public function __construct(
		private readonly string $email,
		private readonly string $roleId
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return 'team-members';
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData([
			'email' => $this->email,
			'role_id' => $this->roleId
		]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): TeamMemberInvitationDTO{
		/** @var TeamMemberInvitationResponseData */
		$data = $this->parseJsonResponseBody((string) $httpResponse->getBody());

		return TeamMemberInvitationDTO::fromResponseData($data);
	}

}
