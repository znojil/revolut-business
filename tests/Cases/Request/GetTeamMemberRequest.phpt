<?php
declare(strict_types=1);

namespace Znojil\RevolutBusiness\Tests\Cases\Request;

use Tester\Assert;
use Znojil\RevolutBusiness\Request\GetTeamMemberRequest;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class GetTeamMemberRequestTest extends \Tester\TestCase{

	public function testConfiguration(): void{
		$request = new GetTeamMemberRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f');

		Assert::same('GET', $request->getMethod());
		Assert::same('team-members/7e18625a-3e6c-4d4f-8429-216c25309a5f', $request->getUrn());
	}

	public function testCreateResponse(): void{
		$result = (new GetTeamMemberRequest('7e18625a-3e6c-4d4f-8429-216c25309a5f'))
			->createResponse(\Znojil\RevolutBusiness\Tests\Fixtures\ResponseFactory::create('teams/team-member'));

		Assert::same('7e18625a-3e6c-4d4f-8429-216c25309a5f', $result->id);
		Assert::same(\Znojil\RevolutBusiness\Enum\TeamMemberState::Locked, $result->state);
		Assert::same('2026-01-15T10:20:30+00:00', $result->createdAt->format('c'));
		Assert::same('2026-03-10T14:45:12+00:00', $result->updatedAt->format('c'));
		Assert::null($result->departmentId);
		Assert::null($result->managerId);
	}

}

(new GetTeamMemberRequestTest)->run();
