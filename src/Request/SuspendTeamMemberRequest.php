<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#suspend-team-member
 */
final class SuspendTeamMemberRequest extends BaseRequest{

	public function __construct(
		private readonly string $teamMemberId
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "team-members/$this->teamMemberId/suspend";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
