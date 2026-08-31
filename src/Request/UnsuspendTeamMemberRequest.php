<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Request;

/**
 * @extends BaseRequest<null>
 * @link https://developer.revolut.com/docs/api/business#unsuspend-team-member
 */
final class UnsuspendTeamMemberRequest extends BaseRequest{

	public function __construct(
		private readonly string $teamMemberId
	){}

	public function getMethod(): string{
		return 'POST';
	}

	public function getUrn(): string{
		return "team-members/$this->teamMemberId/unsuspend";
	}

	public function createResponse(\Psr\Http\Message\ResponseInterface $httpResponse): null{
		return null;
	}

}
