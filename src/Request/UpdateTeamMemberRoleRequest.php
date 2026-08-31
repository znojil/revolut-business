<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#update-team-member-role
 */
final class UpdateTeamMemberRoleRequest extends BaseRequest{

	public function __construct(
		private readonly string $teamMemberId,
		private readonly string $roleId
	){}

	public function getMethod(): string{
		return 'PUT';
	}

	public function getUrn(): string{
		return "team-members/$this->teamMemberId/role";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData(['role_id' => $this->roleId]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
