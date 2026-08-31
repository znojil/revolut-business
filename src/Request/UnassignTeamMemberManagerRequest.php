<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#unassign-manager
 */
final class UnassignTeamMemberManagerRequest extends BaseRequest{

	public function __construct(
		private readonly string $teamMemberId
	){}

	public function getMethod(): string{
		return 'DELETE';
	}

	public function getUrn(): string{
		return "team-members/$this->teamMemberId/manager";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
