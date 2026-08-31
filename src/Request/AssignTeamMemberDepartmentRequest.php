<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#assign-department
 */
final class AssignTeamMemberDepartmentRequest extends BaseRequest{

	public function __construct(
		private readonly string $teamMemberId,
		private readonly string $departmentId
	){}

	public function getMethod(): string{
		return 'PUT';
	}

	public function getUrn(): string{
		return "team-members/$this->teamMemberId/department";
	}

	public function getHeaders(): array{
		return ['Content-Type' => 'application/json'];
	}

	public function getData(): array{
		return $this->buildData(['department_id' => $this->departmentId]);
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
