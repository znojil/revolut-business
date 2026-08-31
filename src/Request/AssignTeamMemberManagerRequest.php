<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#assign-manager
 */
final class AssignTeamMemberManagerRequest extends BaseRequest{

	public function __construct(
		private readonly string $teamMemberId,
		private readonly string $managerId
	){}

	public function getMethod(): string{
		return 'PUT';
	}

	public function getUrn(): string{
		return "team-members/$this->teamMemberId/manager";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData(['manager_id' => $this->managerId]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
